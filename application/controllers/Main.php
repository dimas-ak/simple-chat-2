<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("dbs");
		$this->load->library("menu");
		if(login_admin() || login_super_admin())
		{
			redirect("admin/");
		}
	}

	public function index()
	{
		$data['login']	    = site_url("login/");
		$this->menu->_menu("Main Menu", view("home",$data, TRUE));
	}

	function login()
	{
		$data['form'] = form_open('login');
		set_rules('username',"Username", 'required');
		set_rules('password',"Username", 'required');
		if(valid_run())
		{
			$check = $this->dbs->row_array('username', posts('username'), 'admin');
			if($check && decode_str($check["password"]) == posts('password'))
			{
				$name_session = ($check['level'] == 1) ? "super_admin" : "admin";
				
				set_userdata($name_session, $check['id']);
				$redirect 	  = str_replace("_",'-', $name_session);
				redirect($redirect . '/');
			}
			else
			{
				set_flashdata('error', info_error('Username || Password not valid'));
				redirect('login/');
			}
		}
		$this->menu->_menu("Login Admin", view("login", $data, TRUE));
	}
}
