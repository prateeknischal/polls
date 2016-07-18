<?php 
defined('BASEPATH') or exit('No Direct Script Access Allowed');

class Notes extends CI_Controller{
	public function __construct(){
		parent::__construct();
		$this->load->model("notes_model");
		$this->load->model("member_model");
	}

	private function _redirect($url){
		$url = site_url($url);

		/* to prevent cross site ajax calls 
		localhost and 127.0.0.1 is interpreted as different urls */
		$url = str_replace("127.0.0.1", "localhost", $url);
		redirect($url);
	}

	public function check_login_status(){
		if (session_status() != PHP_SESSION_ACTIVE || !isset($this->session->userdata['handle'])){
			$this->_redirect("welcome/login");
		}
	}

	public function login_home(){
		$this->check_login_status();
		$d = $this->notes_model->get_top_five_events($this->session->userdata('handle'));

		$this->load->view('templates/dash.php', array('active' => ''));
		$this->load->view('notes/login_home.php', array("data" => $d));
		$this->load->view('templates/footer.php');
	}

	/* Landing page after login */
	public function dash(){
		$this->check_login_status();
		$res = $this->notes_model->get_all_notes_by_user_assoc($this->session->userdata('handle'));
		
		$this->load->view('templates/dash.php', array("active" => "dash"));
		$this->load->view('notes/notes_list_view.php', array('data' => $res));
		$this->load->view('templates/footer.php');
	}

	public function new_note(){
		$this->check_login_status();
		$this->load->view('templates/dash.php', array("active" => ""));
		$this->load->view('notes/create_note_view.php');
		$this->load->view('templates/footer.php');
	}

	public function new_task(){
		$this->check_login_status();
		$this->load->view('templates/dash.php', array("active" => ""));
		$this->load->view('polls/create_task_view.php');
		$this->load->view('templates/footer.php');
	}

	public function new_poll(){
		$this->check_login_status();
		$this->load->view('templates/dash.php', array('active' => ""));
		$this->load->view('polls/create_poll_view.php');
		$this->load->view('templates/footer.php');
	}
	
	public function insert_new_post(){
		$this->check_login_status();
		if (!isset($_POST['title'])){
			$this->_redirect('notes/new_note');
		}

		$title = $_POST['title'];
		$content = $_POST['content'];
		$handle = $this->session->userdata('handle');

		$assoc_handle = explode(',', $_POST['handles']);
		$v = array();
		/* add owner of the post to the list */
		array_push($assoc_handle, $handle);
		/* remove duplicates from the list */
		sort($assoc_handle);
		$i = 0;
		while ($i <  count($assoc_handle) && strlen($assoc_handle[$i]) == 0){
			$i++;
		}

		if ($i < count($assoc_handle)){
			array_push($v, $assoc_handle[$i]);
			$i++;
		}
		$v_idx = 0;
		for (; $i < count($assoc_handle); $i++){
			if ($v[$v_idx] != $assoc_handle[$i] && strlen($assoc_handle[$i]) != 0){
				array_push($v, $assoc_handle[$i]);
				$v_idx++;
			}
		}
		$this->notes_model->insert_new_post($title, $content, $handle, $v, 1);


		$this->_redirect("notes/login_home");
	}

	public function get_user_details(){
		if (!isset($_POST['assoc_handle'])){ return "__no_user_found__";}
		$handle = $_POST['assoc_handle'];
		$res = $this->member_model->get_user_details($handle);
		if (count($res) == 0){ print_r("__no_user_found__");}
		else{
			$user_name = $res[0]['username'];
			$handle = $res[0]['handle'];

			//send userdata encoded as json
			print_r("{\"username\":\"$user_name\", \"handle\" : \"$handle\"}");
		}
	}

	/**
	*	@param 	string 	contains a serialized array object of poll choices 
	* 	@return boolean returns if the content is a walmart product id
	*/
	private function check_product_content($v){
		$j = @unserialize($v);
		if ($j == false) return false;
		$op_1 = $j['op_1']['value'];
		$op_2 = $j['op_2']['value'];

		$id_1 = explode('__', $op_1);
		$id_2 = explode('__', $op_2);

		if (count($id_1) == 2 && count($id_2) == 2){
			//product found
			return true;
		}
		return false;
	}

	private function get_product_id_from_content($v){
		$j = unserialize($v);
		$op_1 = $j['op_1']['value'];
		$op_2 = $j['op_2']['value'];

		$id_1 = explode('__', $op_1);
		$id_2 = explode('__', $op_2);
		return array('op_1' => $id_1[0], 'op_2' => $id_2[0]);
	}

	public function view_note_details($_post_id){
		/* post id is of the format post_xxxx */
		$post_id = explode('_', $_post_id)[1];
		$handle = $this->session->userdata('handle');
		$post_details = $this->notes_model->get_notes_details($post_id, $handle);

		if (count($post_details) == 0){
			$this->_redirect("notes/dash");
		}

		$data = array("post_content" => $post_details[0], "assoc_handle" => $post_details[1]);
	
		/* check for task list */
		if ($post_details[0]['post_type'] == 3){
			$this->load->view('templates/dash.php', array("active" => ""));
			$this->load->view('notes/task_detail_view.php', $data);
			$this->load->view('templates/footer.php');
		}
		else{
			/*
			* Check for product poll
			*/
			$b = $this->check_product_content($post_details[0]['content']);

			if ($b == true){
				/* Send to different function to process */
				$this->view_product_poll_details($data);
			}
			else{
				$this->load->view('templates/dash.php', array("active" => ""));
				$this->load->view('notes/notes_details_view.php', $data);
				$this->load->view('templates/footer.php');
			}
		}
	}

	/**
	* 	This function uses php5-curl package on ubuntu
	*	install : $ sudo apt-get install php5-curl
	*	url : http://php.net/manual/en/book.curl.php
	*
	* 	@param 	string 	the url to which GET request is required, properly formatted
	*	@return array 	an associative array is returned of a json response to the GET
	*/
	private function api_get_request($url){
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => $url
		));
		$res = @json_decode(curl_exec($curl), true);
		curl_close($curl);
		return $res;
	}

	/** 
	*	this function uses the walmart api to get product details and display a gist 
	*	in the view 
	*
	*	@param 	array 	Complete data about the post
	*/
	private function view_product_poll_details($data){
		
		$url = "http://api.walmartlabs.com/v1/items/";
		$q = "?format=json&apiKey=".API_KEY;

		$id = $this->get_product_id_from_content($data['post_content']['content']);
		$res_1 = $this->api_get_request($url.$id['op_1'].$q);
		$res_2 = $this->api_get_request($url.$id['op_2'].$q);

		$d = array("post_content" =>  $data['post_content'], 
						"assoc_handle" => $data['assoc_handle'],
						"product_info" => array("op_1" => $res_1, "op_2" => $res_2));
		// var_dump($d);
		/* View for product comapare */
		$this->load->view('templates/dash.php', array("active" => "dash"));
		$this->load->view('polls/product_poll_view.php', $d);
		$this->load->view('templates/footer.php');
	}

	public function delete_note(){
		if (!isset($_POST['post_id'])){
			$this->_redirect("notes/dash");
		}

		$post_id = $_POST['post_id'];
		$this->notes_model->delete_note_by_post_id($post_id);
		$this->_redirect('notes/dash');
	}


	public function insert_new_poll(){
		$this->check_login_status();
		if (!isset($_POST['title'])){
			$this->_redirect('notes/dash');
		}

		$title = $_POST['title'];
		$handle = $this->session->userdata('handle');
		$op_1 = $_POST['op_1'];
		$op_2 = $_POST['op_2'];

		$value = array("op_1" => array("value" => $op_1, "count" => 0), 
						"op_2" => array("value" => $op_2, "count" => 0)
					);

		$j = serialize($value);

		/* get unique list of associated users*/

		$assoc_handle = explode(',', $_POST['handles']);
		$v = array();
		/* add owner of the post to the list */
		array_push($assoc_handle, $handle);
		/* remove duplicates from the list */
		sort($assoc_handle);
		$i = 0;
		while ($i <  count($assoc_handle) && strlen($assoc_handle[$i]) == 0){
			$i++;
		}

		if ($i < count($assoc_handle)){
			array_push($v, $assoc_handle[$i]);
			$i++;
		}
		$v_idx = 0;
		for (; $i < count($assoc_handle); $i++){
			if ($v[$v_idx] != $assoc_handle[$i] && strlen($assoc_handle[$i]) != 0){
				array_push($v, $assoc_handle[$i]);
				$v_idx++;
			}
		}

		/* Insert into db */

		$this->notes_model->insert_new_post($title, $j, $handle, $v, 2);
		$this->_redirect("notes/login_home");
	}

	public function record_vote(){
		$this->check_login_status();
		if (! isset($_POST['vote'])){
			$this->_redirect('notes/dash');
		}

		$vote_option = $_POST['vote'];
		$post_id = $_POST['post_id'];
		$handle = $this->session->userdata('handle');

		$this->notes_model->insert_vote_for_poll($handle, $vote_option, $post_id);
		print_r("done");
	}

	public function new_task_list(){
		$this->check_login_status();
		if (!isset($_POST['list'])){
			$this->_redirect("notes/dash");
		}

		$d = explode(',', $_POST['list']);
		$title = $d[0];
		$content = array();
		for ($i = 1; $i < count($d); $i++){
			array_push($content, array("value" => $d[$i], "done" => 0));
		} 

		/* structure of the data store
		 	[
		 		(i)=>
					[
						"value" : string,
						"done"	: enum{0, 1}
					],
		 	]
		*/

		$handle = $this->session->userdata('handle');
		$this->notes_model->insert_new_post($title, serialize($content), $handle, array($handle), 3);
		$this->_redirect("notes/dash");
	}

	public function do_update_task_list(){

		$this->check_login_status();
		if (!isset($_POST['update'])){
			$this->_redirect("notes/dash");
		}
		// $d = explode(',', $_POST['update']);
		$post_id = $_POST['update_post_id'];
		$this->notes_model->update_task_list($post_id, $_POST['update']);
		$this->_redirect("notes/dash");
	}
}


?>