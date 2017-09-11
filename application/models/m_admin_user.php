<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * User: huangrongbiao
 * Date: 2017/9/6
 * Time: 10:06
 */
class M_admin_user extends CI_Model
{
    protected $users;
    var $table = 'svn_admin_user';
    var $max_idle_time = 300; // allowed idle time in secs, 300 secs = 5 minute

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->users = $this->config->config['svn']['admin'];
    }

    // Save a new user.
    function save($user_data)
    {
        $this->db->insert($this->table, $user_data);
        return $this->db->insert_id();
    }

    // Update an existing user
    function update($user_data)
    {
        if (isset($user_data['id'])) {
            $this->db->where('id', $user_data['id']);
            $this->db->update($this->table, $user_data);
            return $this->db->affected_rows();
        }
        return false;
    }

    public function get_by_account($account)
    {
        return $this->users[$account];
    }

    // Check if user is logged in and update session
    function is_logged_in()
    {
        $last_activity = $this->session->userdata('last_activity');
        $logged_in = $this->session->userdata('logged_in');
        $user = $this->session->userdata('user');

        if (($logged_in == 'yes')
            && ((time() - $last_activity) < $this->max_idle_time)) {
            $this->allow_pass($user);
            return true;
        } else {
            $this->remove_pass();
            return false;
        }
    }

    // set login session
    function allow_pass($user_data)
    {
        $this->session->set_userdata(array('last_activity' => time(), 'logged_in' => 'yes', 'user' => $user_data));
    }

    // remove pass
    function remove_pass()
    {
        $array_items = array('last_activity' => '', 'logged_in' => '', 'user' => '');
        $this->session->unset_userdata($array_items);
    }

    // get user by id
    function get_by_id($id)
    {
        $query = $this->db->get_where($this->table, array('id' => $id), 1);
        if ($query->num_rows() > 0) return $query->row_array();
        return false;
    }

    // Generate hashed password
    function hash_password($password)
    {
        $salt = $this->generate_salt();
        return $salt . '.' . md5($salt . $password);
    }

    // Check if username already exists
    function account_exists($account)
    {
        $query = $this->db->get_where($this->table, array('account' => $account), 1);
        if ($query->num_rows() > 0) return true;
        return false;
    }

    // create salt for password hashing
    private function generate_salt($length = 10)
    {
        $characterList = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $i = 0;
        $salt = "";
        while ($i < $length) {
            $salt .= $characterList{mt_rand(0, (strlen($characterList) - 1))};
            $i++;
        }
        return $salt;
    }

    // Check if password is valid
    function check_password($password, $hashed_password)
    {
        return md5($password) == $hashed_password;
    }
}