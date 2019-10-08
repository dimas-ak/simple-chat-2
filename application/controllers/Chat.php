<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Chat extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model("dbs");
        $this->load->library("menu");
        if(!$this->input->is_ajax_request() || login_admin() || login_super_admin())
        {
            die();
        }
    }

    protected function _menu($menu)
    {
        $data['menu']       = $menu;
        $data['logout']     = (login_user() && !isReview()) ? $this->menu->_helper()['logout'] : "";
        // add class Aktif to body-chat if user successfully login and start chat
		$data['aktif_chat'] = login_user() ? ' aktif' : "";
        view("chat/body-chat", $data);
    }

    function index()
    {
        // if user successfully login chat and start chat
        if(login_user('user'))
        {
            // (isReview()) if user click logout to end the chat
            $data['form'] = isReview() ? form_open("chat/form-review/", 'id="chat-review"') : "";
            $view         = isReview() ? view('chat/review', $data, TRUE) : $this->_main_chat();

            $get_user     = $this->dbs->row_array('id', userdata('user'), 'user');
            if(!$get_user)
            {
                unset_userdata(['user', 'review_for', 'user_time', 'review', 'name_user', 'name_admin']);
            }
        }
        else
        {
            // if officer WebChat are busy
            $data['form']  = form_open("chat/login", 'id="chat-login"');
            $view     = ($this->dbs->isBusy()) ? $this->menu->_helper()['busy'] : view('chat/login', $data, TRUE);
        }
        $this->_menu($view);
    }

    function login()
    {
        set_rules("name",'      Full Name',     'required');
        set_rules("email",      'E-Mail',       'required');
        set_rules("telephon",   'No. Telephon', 'required');
        set_rules("address",    'Address',      'required');
        set_error_delimiters('<div class="__msg-error"><span>','</span></div>');

        $msg['result']     = false;    
        if(valid_run())
        {
            $msg['result'] = true;
            
            // if officers WebChat are busy
            if($this->dbs->isBusy())
            {
                $msg['send_msg']= false;
                $msg['view']    = $this->menu->_helper()['busy'];
            }
            else
            {
                $insert_user    = [
                    'name'      => posts('name'), 
                    'email'     => posts('email'),
                    'address'   => posts('address'),
                    'telephon'  => posts('telephon'),
                    'date'      => date('Y-m-d H:i:s'),
                    'status'    => 1 
                ];
                // status 1 = chat
                // status 2 = user end the chat and try to rate to Officer
                // status 3 = out of time
                // status 4 = blocked by Officer WebChat
                // status 5 = user finish the chat

                $this->dbs->insert($insert_user, 'user');
                $id_user        = $this->db->insert_id();
                
                set_userdata('user', $id_user);

                // set time now in session and convert it to strtotime
                set_userdata('user_time', strtotime(date("Y-m-d H:i:s")));

                // get first officer where are not busy
                $get_officer    = $this->dbs->row_array('status', 1, 'admin');
                $insert_chat    = [
                    'id_admin'  => $get_officer['id'],
                    'id_user'   => $id_user,
                    'message'   => 'Welcome to our WebChat, what can we do for you?',
                    'date'      => date("Y-m-d H:i:s"),
                    'msg_from'  => '1' // from admin
                ];
                
                // set session id_admin, name user, name admin
                set_userdata('review_for', $get_officer['id']);
                set_userdata('name_user', posts('name'));
                set_userdata('name_admin', $get_officer['name']);

                // insert chat to chat
                $this->dbs->insert($insert_chat, 'chat');

                // update officer WebChat to Busy
                //$update_officer['status'] = 2;
                //$this->dbs->update('id', $get_officer['id'], $update_officer, 'admin');

                $msg['logout']  = $this->menu->_helper()['logout'];
                $msg['send_msg']= true;
                $msg['user']    = $id_user;
                $msg['admin']   = $get_officer['id'];
                $msg['nameUser']= posts('name');
                $msg['nameAdmin']= $get_officer['name'];
                $msg['photo']   = photo('users.png');
                $msg['view']    = $this->_main_chat();
                $msg['logout']  = $this->menu->_helper()['logout'];
            }
        }
        else
        {
            $msg['name']        = form_error("name");
            $msg['email']       = form_error("email");
            $msg['telephon']    = form_error("telephon");
            $msg['address']     = form_error("address");
        }
        echo json_encode($msg);
    }

    protected function _main_chat()
    {
        if(login_user())
        {
            $data['form_send'] = form_open('chat/form-send/', 'id="form-send" class="form-send"');
            $data['get_chat']  = $this->dbs->getChat(userdata('user'));
            $data['user']      = $this->dbs->row_array('id', userdata('user'), 'user');
            $view              = view('chat/main-chat', $data, TRUE);
            return $view;
        }
    }

    function form_send()
    {
        $msg['result']      = false;
        $msg['result_chat'] = true;
        set_rules("msg", "Message", 'required');
        if(valid_run())
        {
            $user               = $this->dbs->row_array('id', userdata('user'), 'user');

            $time_user      = userdata('user_time') + $this->menu->min();
            $now            = strtotime(date("Y-m-d H:i:s"));
            $date_now       = date("Y-m-d H:i:s");
            // compare last time user send the chat with time now
            // if last chat more than 15 mins
            if($now > $time_user || $user['status'] != 1)
            {
                $msg['result_chat'] = false;
                $msg['view']        = $this->menu->_helper()['statusChatUser'][3];

                // update status user to "expired for chat"
                $update['status']   = 3;
                $this->dbs->update('id', userdata('user'), $update, 'user'); 
            }
            else
            {
                $insert = [
                    'id_admin'  => userdata("review_for"),
                    'id_user'   => userdata('user'),
                    'message'   => posts('msg'),
                    'date'      => $date_now,
                    'msg_from'  => 2 // message from user
                ];

                $this->dbs->insert($insert, 'chat');

                set_userdata('user_time', $now);

                $msg['nameUser']    = userdata('name_user');
                $msg['nameAdmin']   = userdata('name_admin');
                $msg['photo']       = photo('users.png');
                $msg['message']     = posts('msg');
                $msg['user']        = $user['id'];
                $msg['admin']       = userdata("review_for");
                $msg['date']        = format_tanggal($date_now);
           }
            $msg['result']  = true;
        }
        $msg['msg'] = form_error('msg');
        echo json_encode($msg);
    }

    function form_review()
    {
        set_rules('star', 'Star',    'required');
        set_rules('msg',  'Message', 'required');
        set_error_delimiters('<div class="__msg-error"><span>','</span></div.');

        $msg['result']  = false;

        if(valid_run())
        {
            $insert = [
                'id_admin'  => userdata('review_for'),
                'id_user'   => userdata('user'),
                'star'      => posts('star'),
                'message'   => posts('msg'),
                'date'      => date("Y-m-d H:i:s")
            ];
            // update status user to finish
            $update['status'] = 5;
            $this->dbs->update('id', userdata('user'), $update, 'user');

            $this->dbs->insert($insert, 'review');

            // data for view login
            $data['form']  = form_open("chat/login", 'id="chat-login"');
            $data['info']  = '<div class="__msg-success"><span>Thank you so much!!</span></div>';

            $msg['result'] = true;
            $msg['admin']  = userdata("review_for");
            $msg['user']   = userdata("user");
            $msg['status'] = 5;
            $msg['view']   = view('chat/login', $data, TRUE);

            // unset session
            unset_userdata(['user', 'review_for', 'user_time', 'review', 'name_user', 'name_admin']);
        }

        $msg['star'] = form_error('star');
        $msg['msg']  = form_error('msg');
        echo json_encode($msg);
    }

    function review()
    {
        if(login_user())
        {
            $data['form'] = form_open("chat/form-review/", 'id="chat-review"');
            $msg['view']  = view('chat/review', $data, TRUE);
            $msg['admin'] = userdata('review_for');
            $msg['user']  = userdata('user');

            // update status user to review
            $update['status'] = 2;
            $this->dbs->update('id', userdata('user'), $update, 'user');

            //set session review
            set_userdata('review', userdata('user'));
            echo json_encode($msg); 
        }
    }
}