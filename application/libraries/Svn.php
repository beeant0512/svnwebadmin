<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Svn
{
    protected $CI;
    protected $config;
    protected $svnlook;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->config = $this->CI->config->config['svn'];
        $this->svnlook = $this->config['svnlook'];
    }

    public function read_ini_file($filename)
    {
        $handle = fopen($filename, "r");//读取二进制文件时，需要将第二个参数设置成'rb'
        //通过filesize获得文件大小，将整个文件一下子读到一个字符串中
        $contents = fread($handle, filesize($filename));
        $contents = str_replace("!", "", $contents);
        fclose($handle);
        $handle = fopen($filename, "w");//读取二进制文件时，需要将第二个参数设置成'rb'
        fwrite($handle, $contents);
        fclose($handle);

        $file_array = parse_ini_file($filename, true);
        return $file_array;
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
            // todo
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

        // todo
        exec('htpasswd -b F:\Repositories\htpasswd demo 123456' . $username . ' ' . $password);
    }

    public function isRepository($path)
    {
        return is_dir($path);
    }

    public function create_repository($reponame, $type = '')
    {
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
            if (self::isRepository($absolute_path)) {
                $ret[] = $file;
            }
        }
        closedir($hd);

        return $ret;
    }

    /**
     * proplist (plist, pl): usage: 1. svnlook proplist REPOS_PATH PATH_IN_REPOS
     * 2. svnlook proplist --revprop REPOS_PATH
     *
     * List the properties of a path in the repository, or
     * with the --revprop option, revision properties.
     * With -v, show the property values too.
     *
     * Valid options:
     * -r [--revision] ARG      : specify revision number ARG
     * -t [--transaction] ARG   : specify transaction name ARG
     * -v [--verbose]           : be verbose
     * --revprop                : operate on a revision property (use with -r or -t)
     * --xml                    : output in XML
     * --show-inherited-props   : show path's inherited properties
     */
    public function proplist()
    {

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
        $command = 'svnlook tree ' . $this->getRepoFullPath($repo_name);
        exec($command, $res);
        $tree = array();
//        foreach ($res as $index => $folder) {
//            $level = substr_count($folder, ' ');
//            $tree[$folder] = $folder;
//            foreach ($res as $index2 => $folder2) {
//                $level2 = substr_count($folder2, ' ');
//                var_dump($folder);
//                var_dump($folder2);
//                if($folder == $folder2 || $level2 == $level){
//                    continue;
//                }
//
//                $folder[trim($folder2)] = trim($folder2);
//            }
//            $pre_level = $level;
//        }
        return $res;
    }

    private function getRepoFullPath($repo_name)
    {
        return $this->config['repositories_path'] . '\\' . $repo_name;
    }
}