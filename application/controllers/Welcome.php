<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model('member_model');
	}
	public function home(){
		$d = array("active" => "");
		$this->load->view('templates/header.php', $d);
		$this->load->view('landing.php');
		$this->load->view('templates/footer.php');
	}

	public function home_dash(){
		$d = array("active" => "");
		$this->load->view('templates/dash.php', $d);
		$this->load->view('landing.php');
		$this->load->view('templates/footer.php');
	}

	public function login(){
		if (session_status() == PHP_SESSION_ACTIVE){
			/* 	
				check for direct access from url
				if session is running then destroy session
			*/
			session_destroy();
		}

		$d = array("active" => "login");
		$this->load->view('templates/header.php', $d);
		$this->load->view('login.php');
		$this->load->view('templates/footer.php');
		
	}

	public function sign_up(){
		if (session_status() == PHP_SESSION_ACTIVE){
			/* 	
				check for direct access from url
				if session is running then destroy session
			*/
			session_destroy();
		}

		$d = array("active" => "sign_up");
		$this->load->view('templates/header.php', $d);
		$this->load->view('sign_up.php');
		$this->load->view('templates/footer.php');
	}

	public function about(){
		$d = array("active" => "about");
		$this->load->view('templates/header.php', $d);
		$this->load->view('about.php');
		$this->load->view('templates/footer.php');
	}

	public function validate_sign_up_info(){
		if ( !isset($_POST['handle'])){
			$this->login();
		}
		$handle = $_POST['handle'];
		$username = $_POST['username'];
		/* currently storing password as plain text
			->change this to encrypted forms
		*/
		$password = $_POST['password'];

		$count = $this->member_model->check_validity_of_sign_up_info($handle);
		if ($count == 0){
			// No members with same handle
			// return to ajax call in view/sign_up.php
			print_r("ok");
			$this->member_model->create_new_user(array('handle' => $handle,
														'username' => $username,
														'password' => $password));

		}
		else{
			//return to ajax call in view/sign_up.php
			print_r("used");
		}
	}

	public function validate_user_login(){
		if (!isset($_POST['handle'])){
			$this->login();
		}

		$handle = $_POST['handle'];
		$password = $_POST['password'];

		$user_found = $this->member_model->find_user($handle, $password);
		if ($user_found == false){
			print_r("false");
		}
		else{
			print_r("true");
			session_start();
			$data = array();
			/* Get user data from the database */
			$res = $this->member_model->get_user_details($_POST['handle']);

			/*Setting session userdata */
			$data['last_login'] = strftime("%c", $this->session->userdata['__ci_last_regenerate']);
			$data['handle'] = $_POST['handle'];
			$data['username'] = $res[0]['username'];
			$data['ip_addr'] = $_SERVER['REMOTE_ADDR'];
			$data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

			$this->session->set_userdata($data);
		}
	}

	public function logout_user(){
		session_destroy();
		$this->login();
	}
}

?>