<?php
defined('BASEPATH') OR exit("No direct script access allowed");

class Member_model extends CI_Model{
	public function __construct(){
		$this->load->database();
	}

	/**
	* 	@param 	string 	handle of user signing up
	* 	@return int 	the number of users that have the same handle as supplied
	*/
	public function check_validity_of_sign_up_info($handle){
		$q = "SELECT count(*) FROM users WHERE handle = '$handle'";
		$res = $this->db->query($q)->result_array();
		return $res[0]['count(*)'];
	}

	/**
	* 	@param 	array 	the data that user supplied for sign up to create new user
	*/
	public function create_new_user($data){
		$username = $data['username'];
		$handle = $data['handle'];
		$password = $data['password'];

		$q = "INSERT INTO users (handle, username, password) VALUES('$handle', '$username', '$password')";
		$this->db->query($q);
	}
	
	/**
	*	@param 	string 	the handle of the user
	*	@param 	string 	the password of the user trying to login
	*	@return boolean returns if a user with the above handle-password combo is found
	*/
	public function find_user($handle, $password){
		$q = "SELECT count(*) FROM users WHERE handle = '$handle' AND password = '$password'";
		$res = $this->db->query($q)->result_array();
		if ($res[0]['count(*)'] == 0){
			return false;
		}
		else{ 
			// user found
			return true;
		}
	}

	/**
	* 	@param string 	handle of the users whose details are required
	*	@return array 	the details of the user, if no such user exists then an empty array is returned
	*/
	public function get_user_details($handle){
		$q = "SELECT username, handle FROM users WHERE handle = '$handle'";
		$res = $this->db->query($q)->result_array();
		return $res;
	}
}
?>