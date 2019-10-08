<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Super_admin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("dbs");
        $this->load->library("menu");
        if(!login_super_admin())
        {
            redirect();
        }
    }

    function index()
    {
        $top['account']             = site_url('super-admin/account/');
        $top['logout']              = site_url('super-admin/logout/');

        $home['account_admin']      = site_url('super-admin/account-admin/');

        $array['open_admin']        = site_url('super-admin/open-admin/');
        $array['admin']             = $this->dbs->result_key('level', 2, 'admin');

        // view account admin
        $data['admin_account']      = view('admin/account', "", TRUE);

        // view home of admin related
        $data['admin_home']         = view('super-admin/admin-home', $home, TRUE);

        // top menu
        $data['top_menu']           = view('super-admin/top-menu', $top, TRUE);

        // view all admin
        $data['menu_array_admin']   = view('super-admin/array-admin', $array, TRUE);
        $view                       = view('super-admin/home', $data, TRUE);
        $this->menu->_menu("Super Admin", $view);
    }

    function open_admin($id_admin = null)
    {
        $admin              = $this->dbs->row_array('id', $id_admin, 'admin');
        $msg['name']        = $admin['name'];
        $msg['username']    = $admin['username'];
        $msg['id']          = $admin['id'];

        $chat['user']       = $this->dbs->getUserChat($admin['id'], TRUE);
        $chat['open_chat']  = site_url('super-admin/open-chat/');
        $chat['status']     = $this->menu->_helper()['statusChatSuperAdmin'];
        $msg['user_chat']   = view('super-admin/user-related', $chat, TRUE);

        echo json_encode($msg);
    }

    function open_chat($id_user = null)
    {
        if($id_user != NULL)
        {
            $data['get_chat']   = $this->dbs->getChat($id_user);
            $msg['view']        = view('super-admin/chat', $data, TRUE);
            echo json_encode($msg);
        }
    }

    function account_admin($id_admin = NULL)
    {
        if($id_admin != NULL)
        {
            $admin              = $this->dbs->row_array('id', $id_admin, 'admin');
            $msg['name']        = $admin['name'];
            $msg['username']    = $admin['username'];
            
            $msg['star_5']      = $this->dbs->review_star($id_admin, 5);
            $msg['star_4']      = $this->dbs->review_star($id_admin, 4);
            $msg['star_3']      = $this->dbs->review_star($id_admin, 3);
            $msg['star_2']      = $this->dbs->review_star($id_admin, 2);
            $msg['star_1']      = $this->dbs->review_star($id_admin, 1);

            echo json_encode($msg);
        }
    }

    function logout()
    {
        $this->session->sess_destroy();
        redirect();
    }

}