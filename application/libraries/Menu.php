<?php
defined("BASEPATH") or exit("rika mau ngapa kang? :/");
class Menu
{
    protected $ci;
    function __construct()
    {
        $this->ci =& get_instance();
    }

    function _menu($title, $view)
    {
        $ini    = $this->ci;
        $data['jquery'] 	= $ini->config->base_url("aset/js/jquery.js");
		$data['js']   		= $ini->config->base_url("aset/js/chat.js?" . acak_string());
		$data['js_admin']   = $ini->config->base_url("aset/js/admin-chat.js?" . acak_string());
		$data['js_super_admin']= $ini->config->base_url("aset/js/super-admin.js?" . acak_string());
		$data['socket']	    = $ini->config->base_url("node_modules/socket.io-client/dist/socket.io.js");
		$data['css']   		= $ini->config->base_url("aset/css/chat.css?" . acak_string());
		$data['arjunane_css']= $ini->config->base_url("aset/css/arjunane.css?" . acak_string());
        $data['base_chat']	= $ini->config->base_url("chat/");
        $data['body_admin'] = ($ini->session->userdata('admin') || $ini->session->userdata('super_admin')) ? '__admin"' : "";
		$data['title']		= $title;
        $data['menu']		= $view;
        $ini->load->view('main-view', $data);
    }

    function _helper()
    {
        $ini    = $this->ci;
        $data['busy']        = "<span>Sorry, our wechat officer is serving other customers. Please wait for a moments.</span>";
        $data['done']        = '<div class="__msg-error"><span>Sorry, your session chat is done. Please try again later.</span></div>';
        $data['block']       = '<div class="__msg-error"><span>Sorry, your chat has been blocked by our CS. Please try again later.</span></div>';
        $data['done_admin']  = '<div class="__msg-error"><span>Sorry, this user session chat is done.</span></div>';
        $data['block_admin'] = '<div class="__msg-error"><span>Sorry, this user chat has been blocked by you.</span></div>';
        $data['logout']      = '<a href="' . $ini->config->site_url('chat/review/') .'" class="__review"><img src="' . photo("logout.png") . '"></a>';

        $data['statusChatUser']  = [
            2 => '<div class="__msg-error"><span>Thanks, please rate our officer WebChat.</span></div>',
            3 => '<div class="__msg-error"><span>Sorry, your session chat is done. Please try again later.</span></div>',
            4 => '<div class="__msg-error"><span>Sorry, your chat has been blocked by Our Officer.</span></div>',
            5 => '<div class="__msg-success"><span>You already has sending a rated.</span></div>'
        ];
        
        $data['statusChatAdmin'] = [
            2 => '<div class="__msg-error"><span>User end the chat.</span></div>',
            3 => '<div class="__msg-error"><span>Sorry, this user session chat is done.</span></div>',
            4 => '<div class="__msg-error"><span>Sorry, this user chat has been blocked by you.</span></div>',
            5 => '<div class="__msg-success"><span>User already has sending a rated.</span></div>'
        ];
        
        $data['statusChatSuperAdmin'] = [
            2 => '<div class="__msg-error"><span>User end the chat.</span></div>',
            3 => '<div class="__msg-error"><span>Sorry, this user session chat is done.</span></div>',
            4 => '<div class="__msg-error"><span>Sorry, this user chat has been blocked by this Admin.</span></div>',
            5 => '<div class="__msg-success"><span>User already has sending a rated.</span></div>'
        ];

        $data['status_admin'] = [
            1 => '<strong style="color:#2ecc71">Your Status is Online</strong>',
            2 => '<strong style="color:#e74c3c">Your Status is Offline / Busy</strong>'
        ];
        return $data;
    }

    function min()
    {
        // 60s * 30mins = 1800s
        return 60 * 30;
    }
}