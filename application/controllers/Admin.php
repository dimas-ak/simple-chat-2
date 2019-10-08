<?php
defined("BASEPATH") or exit("Rika mau ngapa kang? :/");
class Admin extends CI_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model("dbs");
        $this->load->library("menu");
        if(!login_admin() && !login_super_admin())
        {
            redirect();
        }
    }

    function index()
    {
        $admin                  = $this->dbs->row_array('id', userdata('admin'), 'admin');

        $wrap['get_chat']       = $this->dbs->getUserChat(userdata('admin'));
        $wrap['open_chat']      = site_url('admin/chat/');

        $top['delete']          = site_url('admin/delete/');
        $top['account']         = site_url('admin/account/');
        $top['change_status']   = site_url('admin/change-status/');
        $top['logout']          = site_url('admin/logout/');
        $top['status']          = $this->menu->_helper()['status_admin'][$admin['status']];

        $account['admin']       = $admin;

        $menu['wrap_menu']      = view('admin/wrap-chat', $wrap, TRUE);
        $menu['top_menu']       = view('admin/top-menu', $top, TRUE);
        $menu['account_view']   = view('admin/account', $account, TRUE);

        $view                   = view('admin/admin', $menu, TRUE);
        $this->menu->_menu("Admin", $view);
    }

    function chat($id_user = null)
    {
        if($id_user != null && $this->input->is_ajax_request())
        {
            // upodate status user to 3 (out of time)
            $update['status']  = 3;
            if($this->dbs->isDone($id_user))
            {
                $this->dbs->update('id', $id_user, $update, 'user');
            }
            $data['get_chat']  = $this->dbs->getChat($id_user);
            $data['user']      = $this->dbs->row_array('id', $id_user, 'user');
            $data['admin']     = $this->dbs->row_array('id', userdata('admin'), 'admin');
            $data['form_send'] = form_open('admin/send-message/' . $data['user']['id'], 'class="form-send" id="form-send"');
            $msg['view']  = view("admin/chat", $data, TRUE);
            echo json_encode($msg);
        }
    }

    function send_message($id_user = null)
    {
        if($id_user != NULL)
        {
            $msg['result']      = false;
            $msg['result_chat'] = true;
            set_rules("msg", "Message", 'required');
            if(valid_run())
            {
                $user               = $this->dbs->row_array('id', $id_user, 'user');

                $date_now       = date("Y-m-d H:i:s");
                // compare last time user send the chat with time now
                // if status user not equal 1 (is chat)
                if($user['status'] != 1)
                {
                    $msg['result_chat'] = false;
                    $msg['view']        = $this->menu->_helper()['statusChatAdmin'][$user['status']];
                }
                else
                {
                    $insert = [
                        'id_admin'  => userdata("admin"),
                        'id_user'   => $user['id'],
                        'message'   => posts('msg'),
                        'date'      => $date_now,
                        'msg_from'  => 1 // message from admin
                    ];

                    $this->dbs->insert($insert, 'chat');

                    $msg['name']        = $user['name'];
                    $msg['photo']       = photo('cs.png');
                    $msg['message']     = posts('msg');
                    $msg['user']        = $user['id'];
                    $msg['admin']       = userdata("admin");
                    $msg['date']        = format_tanggal($date_now);
                }
                $msg['result']  = true;
            }

            $msg['msg'] = form_error('msg');
            
            echo json_encode($msg);
        }
    }

    function account()
    {
        $admin              = $this->dbs->row_array('id', userdata('admin'), 'admin');
        $msg['star_5']      = $this->dbs->review_star(userdata('admin'), 5);
        $msg['star_4']      = $this->dbs->review_star(userdata('admin'), 4);
        $msg['star_3']      = $this->dbs->review_star(userdata('admin'), 3);
        $msg['star_2']      = $this->dbs->review_star(userdata('admin'), 2);
        $msg['star_1']      = $this->dbs->review_star(userdata('admin'), 1);

        $msg['username']    = $admin['username'];
        $msg['name']        = $admin['name'];

        echo json_encode($msg);
    }

    function change_status()
    {
        $admin              = $this->dbs->row_array('id', userdata('admin'), 'admin');
        $update['status']   = ($admin['status'] == 1) ? 2 : 1;
        $this->dbs->update('id', $admin['id'], $update, 'admin');
        $msg                = $this->menu->_helper()['status_admin'][$update['status']];
        echo $msg;
    }

    function delete($id_user)
    {
        $user           = $this->dbs->row_array('id', $id_user, 'user');

        $msg['result']  = false;
        $msg['msg']     = 'Delete the chat AFTER user end the chat.';

        if($user['status'] != 1)
        {
            $msg['result'] = true;
            // update user that Officer WebChat deleted the chat.
            $update['delete_by_admin'] = 1;
            $this->dbs->update('id', $user['id'], $update, 'user');

            $msg['msg'] = 'Chat successfully deleted';
            $msg['url'] = site_url('admin/');
        }

        echo json_encode($msg);
    }

    function logout()
    {
        // update Officer WebChat status to Offline/Busy before Logout
        $update['status'] = 2;
        $this->dbs->update('id', userdata('admin'), $update, 'admin');

        $this->session->sess_destroy();
        redirect();
    }
}