<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Svn
{
    // 定义私有日志变量log
    private $log;
    protected $CI;
    protected $config;
    protected $svnlook;
    protected $new_line = "\r\n";

    public function __construct()
    {
        // 生成log
        $this->log = Logger::getLogger(__CLASS__);

        $this->CI =& get_instance();
        $this->config = $this->CI->config->config['svn'];
        $this->svnlook = $this->config['svnlook'];
    }

    public function read_ini_file($filename)
    {
        $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
        //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
        if (filesize($filename) != 0) {
            $contents = fread($handle, filesize($filename));
            $contents = str_replace("!", "", $contents);
            fclose($handle);
            $handle = fopen($filename, "w");//读取二进制文件时，需要将第二个参数设置成'rb'
            fwrite($handle, $contents);
            fclose($handle);

            $file_array = parse_ini_file($filename, true);
            return $file_array;
        }
        return array();
    }

    public function get_users()
    {
        $users = array();
        $filename = $this->config['htpasswd_file'];
        $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
        // if条件避免无效指针
        $user = '';
        if ($handle) {
            while (!feof($handle)) {
                $user = fgets($handle);
                $user = substr($user, 0, stripos($user, ":"));
                if ($user != '') {
                    array_push($users, $user);
                }
            }
        }
        fclose($handle);
        return $users;
    }

    /**
     * 获取用户权限
     * get user's authorities
     *
     * @param $username
     * @return array
     */
    public function user_authority($username)
    {
        $groupTreeMenu = null;
        $repositories = self::list_repositories();
        $repositories_authority = array();
        if ($this->config['authz_type'] == 'repository') {
            foreach ($repositories as $repository) {
                $repositories_authority[$repository] = self::read_ini_file($this->config['repositories_path'] . '\\' . $repository . '\\conf\\' . $this->config['authz_file']);
            }
        } else {
            // todo for not auzht file auth
        }
        $groups = self::read_ini_file($this->config['group_file']);
        $groupAs = $groups['groups'];
        $userGroup = array();
        foreach ($groupAs as $group => $users) {
            $users = explode(",", $users);
            if (in_array($username, $users)) {
                $userGroup[] = "@" . $group;
            }
        }

        $parGroup = array();
        foreach ($groupAs as $groupA => $usersA) {
            foreach ($userGroup as $sunGroup) {
                $usersA = explode(",", $usersA);
                #echo $groupA;
                if (in_array($sunGroup, $usersA)) {
                    $parGroup[] = "@" . $groupA;
                }
            }
        }
        $rights = array();
        foreach ($repositories_authority as $repo => $repository_authority) {
            if (is_array($repositories_authority[$repo])) {
                foreach ($repositories_authority[$repo] as $folder => $right) {
                    $rights[$repo . $folder] = $right;
                }
            }
        }

        $userRights = array();
        foreach ($rights as $folder => $users) {
            foreach ($users as $user => $right) {
                if ($user == $username || in_array($user, $userGroup) || in_array($user, $parGroup)) {
                    if ($right != "")
                        $userRights[$folder] = $right;
                }
                if ($user == "*" && $right != "") {
                    $userRights[$folder] = $right;
                }
            }
        }
        return $userRights;
    }

    public function create_user($username, $password)
    {
        $this->log->info('create svn account：' . $username);
        exec('htpasswd -b ' . $this->config['htpasswd_file'] . ' ' . $username . ' ' . $password);
    }

    public function get_folder_authority_users($repo, $folder_path)
    {
        $folder_path_size = sizeof($folder_path);
        if (substr($folder_path, $folder_path_size - 2) == '/') {
            $folder_path = substr($folder_path, 0, $folder_path_size - 2);
        }

        $explode_folder = explode("/", $folder_path);
        $explode_folder_size = sizeof($explode_folder);
        for ($index = 0; $index < $explode_folder_size; $index++) {
            if ($index == 0) {
                continue;
            }

            $explode_folder[$index] = $explode_folder[$index - 1] . "/" . $explode_folder[$index];
        }
        $explode_folder[0] = "/";
        $folder_level = $explode_folder_size - 1;
        $svn_auth_file = $this->config['repositories_path'] . '\\' . $repo . '\\conf\\' . $this->config['authz_file'];
        $svn_auth_rights = $this->read_ini_file($svn_auth_file);
        $users = array();
        foreach ($svn_auth_rights as $folder => $user_rights) {
            if (in_array($folder, $explode_folder)) {
                foreach ($user_rights as $user => $rights) {
                    $users[$user] = $rights == "" ? "-" : $rights;
                }
            }
        }

        return $users;
    }

    public function set_authorities($users, $rights, $repository, $folder_path)
    {
        $folder_path_size = sizeof($folder_path);
        if (substr($folder_path, $folder_path_size - 2) == '/') {
            $folder_path = substr($folder_path, 0, $folder_path_size - 2);
        }
        $svn_auth_file = $this->config['repositories_path'] . '\\' . $repository . '\\conf\\' . $this->config['authz_file'];
        $svn_auth_rights = $this->read_ini_file($svn_auth_file);
        $new_folder = true;
        foreach ($svn_auth_rights as $folder => $user_rights) {
            if ($folder == $folder_path) {
                $new_folder = false;
                foreach ($users as $index => $user) {
                    $user_rights[$user] = $rights;
                }
                $svn_auth_rights[$folder] = $user_rights;
            }
        }

        if ($new_folder) {
            $svn_auth_rights[$folder_path] = array();
            foreach ($users as $index => $user) {
                $svn_auth_rights[$folder_path][$user] = $rights;
            }
        }
        $this->log->info("set user's rights " . $users . ' ' . $rights);
        ksort($svn_auth_rights);
        $this->write_svn_authz($svn_auth_file, $svn_auth_rights);
    }

    private function write_svn_authz($file, $svn_auth_rights)
    {
        $handle = fopen($file, "w + ");//读取二进制文件时，需要将第二个参数设置成'rb'
        //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
        $contents = "# This configuration file stores VisualSVN Server authorization settings. The" . $this->new_line;
        $contents .= "# file is autogenerated by VisualSVN Server and is not intended to be edited" . $this->new_line;
        $contents .= "# manually." . $this->new_line;
        $contents .= "#" . $this->new_line;
        $contents .= "# DO NOT EDIT THIS FILE MANUALLY" . $this->new_line;
        $contents .= "#" . $this->new_line;
        $contents .= "# Use VisualSVN Server Manager or VisualSVN Server PowerShell module to" . $this->new_line;
        $contents .= "# configure access permissions on your server." . $this->new_line;
        $contents .= $this->new_line;
        foreach ($svn_auth_rights as $folder => $user_rights) {
            $contents .= "[" . $folder . "]" . $this->new_line;
            foreach ($user_rights as $user => $rights) {
                if ($rights == "-") {
                    $contents .= $user . "=" . $this->new_line;
                } else {
                    $contents .= $user . "=" . $rights . $this->new_line;
                }
            }
            $contents .= $this->new_line;
        }
        fwrite($handle, $contents);
        fclose($handle);
    }

    public function is_repository($path)
    {
        return is_dir($path);
    }

    public function create_repository($reponame, $type = '')
    {
        $this->log->info("create repository " . $reponame);
        $command = $this->config['svnadmin'] . ' create ' . $this->config['repositories_path'] . '\\' . $reponame;
        exec($command, $res);
        return $res;
    }

    /**
     * 获取SVN库列表
     *
     * @return array
     * @throws Exception
     */
    public function list_repositories()
    {
        $basePath = $this->config['repositories_path'];
        if (!file_exists($basePath)) {
            throw new Exception('The repository parent path (SVNParentPath) does not exists: ' . $basePath);
        }

        $ret = array();

        $hd = opendir($basePath);
        while (($file = readdir($hd)) !== false) {
            if ($file == "." || $file == "..") {
                continue;
            }

            $absolute_path = $basePath . "\\" . $file;
            if (self::is_repository($absolute_path)) {
                $ret[] = $file;
            }
        }
        closedir($hd);

        return $ret;
    }

    /**
     * tree: usage: svnlook tree REPOS_PATH [PATH_IN_REPOS]
     *
     * Print the tree, starting at PATH_IN_REPOS (if supplied, at the root
     * of the tree otherwise), optionally showing node revision ids.
     *
     * Valid options:
     * -r [--revision] ARG      : specify revision number ARG
     * -t [--transaction] ARG   : specify transaction name ARG
     * -N [--non-recursive]     : operate on single directory only
     * --show-ids               : show node revision ids for each path
     * --full-paths             : show full paths instead of indenting them
     */
    public function get_repo_tree($repo_name)
    {
        // $command = $this->svnlook . ' tree ' .  $this->getRepoFullPath($repo_name);
        $command = 'svnlook tree ' . $this->get_repo_full_path($repo_name);
        exec($command, $res);
        $res_ary = array();
        foreach ($res as $index => $folder) {
            $level = substr_count($folder, ' ');
            $folder_name = trim($folder);
            $source_folder_name = $folder;
            $folder = new stdClass();
            $folder->level = $level;
            $folder->name = $source_folder_name;
            $folder->fullpath = $folder_name;
            $folder->child = array();
            array_push($res_ary, $folder);
        }
        foreach ($res_ary as $index => $folder_info) {
            $in_loop = true;
            foreach ($res_ary as $index2 => $sub_folder_info) {
                if ($folder_info->level == $sub_folder_info->level && $index2 > $index) {
                    break;
                }
                if ($sub_folder_info->level == $folder_info->level + 1 && $index2 > $index) {
                    $sub_folder_info->fullpath = $folder_info->fullpath . $sub_folder_info->fullpath;
                    array_push($folder_info->child, $sub_folder_info);
                }
            }
        }
        return $res_ary[0];
    }

    private function get_repo_full_path($repo_name)
    {
        return $this->config['repositories_path'] . '\\' . $repo_name;
    }
}