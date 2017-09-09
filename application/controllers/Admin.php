<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends CI_Controller
{
    var $data = array();
    var $svn_server;
    function __construct()
    {
        // Call the Controller constructor
        parent::__construct();
        $svn_config = $this->config->item('svn');
        $this->svn_server = $svn_config['server'];
        $this->load->model('m_admin_user');
        $isLoginPage = uri_string(current_url()) == 'admin/login';
//        if(!$isLoginPage){
//            if ($this->m_admin_user->is_logged_in() === FALSE) {
//                $this->m_admin_user->remove_pass();
//                redirect('admin/login');
//            } else {
//                // is_logged_in also put user data in session
//                $this->data['user'] = $this->session->userdata('user');
//            }
//        }
    }

    public function login()
    {
        $this->load->view('admin/v_login');
    }


    public function index()
    {
        $data['svn_server'] = $this->svn_server;
        $data['repositories'] = $this->svn->list_repositories();
        $this->load->view('admin/v_repositories', $data);
    }

    public function users()
    {
        $data['users'] = $this->svn->get_users();
        $this->load->view('admin/v_users', $data);
    }

    public function authority(){
        $repo_name = $this->input->get('repo');
        $repo_tree = $this->svn->get_repo_tree($repo_name);
        $data['users'] = $this->svn->get_users();
        $data['svn_server'] = $this->svn_server;
        $data['repo_tree'] = $repo_tree;
        $data['repo_name'] = $repo_name;
        $this->load->view('admin/v_authority', $data);
    }

    public function create_repo(){
        $repo_name = $this->input->post('repo');
        $this->svn->create_repository($repo_name);
    }

    public function change_pwd()
    {
        echo 'Hello World!';
    }

}