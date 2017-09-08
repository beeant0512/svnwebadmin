<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller
{
    // data for view, we do this so we can set value in __construct
    // and pass to other functions if needed
    var $data = array();

    function __construct()
    {
        // Call the Controller constructor
        parent::__construct();
        $this->load->model('m_admin_user');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $account = $this->input->get('account');
        if($account){
            $data = array();
            $data['authorities'] = $this->svn->user_authority($account);
            $svn_config = $this->config->item('svn');
            $data['svn_server'] = $svn_config['server'];
            $this->load->view('svn_authority_list',$data);
        } else {
            $this->load->view('welcome_message');
        }
    }

    public function login()
    {
        $this->load->view('welcome_message');

//        $this->load->library('SVN');
//
//        $this->SVN->list_repositories();

        if ($this->m_admin_user->is_logged_in()) {
            redirect('admin');
        }

        $this->form_validation->set_rules('usr', 'usr', 'required');
        $this->form_validation->set_rules('pwd', 'pwd', 'required');

        if ($this->form_validation->run()) {
            $account = $this->input->post('usr');
            $password = $this->input->post('pwd');

            if ($user = $this->m_admin_user->get_by_account($account)) {
                if ($this->m_admin_user->check_password($password, $user['password'])) {
                    $this->m_admin_user->allow_pass($user);
                    redirect('admin');
                } else {
                    $this->data['login_error'] = 'Invalid username or password';
                }
            } else {
                $this->data['login_error'] = 'Username not found';
            }
        }
        $this->load->view('login/v_login', $this->data);
    }
}
