<?php 
defined('BASEPATH') or exit('No Direct Script Access Allowed');

class Notes_model extends CI_Model{
	public function __construct(){
		parent::__construct();
		/* Code Ignitor database helper */
		$this->load->database();
	}

	/**
	*	@param 	string 	takes the handle of the user
	*	@return array 	list of all notes along with data which is hosted by user handle
	*/
	public function get_all_notes_by_user_admin($handle){
		/*
			returns all the notes (type = 1)made by the user
		*/
		$q = "SELECT * FROM posts WHERE admin_handle = '$handle' AND post_type = '1'";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	/**
	*	@param 	string 	handle of the user
	*	@return array 	list of all notes with which the user is associated
	*/
	public function get_all_notes_by_user_assoc($handle){
		/* 
			returns all the notes that the user is associated with
		*/
		$q = "SELECT * FROM posts". 
				" WHERE posts.post_id in ".
				" (SELECT DISTINCT(post_id) FROM post_user_assoc WHERE user_assoc = '$handle')";
		$res = $this->db->query($q)->result_array();
		return $res;
	}

	/**
	*	@param 	string 	the handle of a user
	*	@return array 	list of 5 most recent events with which the user
	*					is associated with
	*/
	public function get_top_five_events($handle){
		$q = 	"SELECT * FROM posts ".
				"WHERE posts.post_id in ".
					"(".
						"SELECT post_id FROM post_user_assoc ".
						"WHERE user_assoc = '$handle'".
						") ".
				"ORDER BY ts DESC LIMIT 5";

		$res = $this->db->query($q)->result_array();
		return $res;
	}

	/**
	*	@param 	string 	Title of the post
	*	@param 	string 	the content of the post
	*	@param 	string 	the handle of the admin of post
	* 	@param 	array 	the list of users that are associated with the post
	*/
	public function insert_new_post($title, $content, $handle, $assoc_handles, $post_type){
		/* Insert into table 'posts' for the admin of the post */
		/* Post id = 1 for the note category */
		$t = date('Y-m-d h:m:s', time());
		$q = "INSERT INTO posts (admin_handle, title, content, ts, post_type) ".
				"VALUES ('$handle', '$title', '$content', '$t', $post_type)";

		$this->db->query($q);

		/* get the post_id of the post */
		$q = "SELECT post_id FROM posts WHERE admin_handle = '$handle' AND ts = '$t'";
		$res = $this->db->query($q)->result_array();

		/* Insert into table 'post_user_assoc'*/
		$post_id = $res[0]['post_id'];

		$q = "INSERT INTO post_user_assoc(post_id, user_assoc) VALUES";
		for ($i = 0; $i < count($assoc_handles); $i++){
			$user_handle = $assoc_handles[$i];
			$q = $q." ('$post_id', '$user_handle') ";
			if ($i != count($assoc_handles) - 1)
				$q = $q.",";
		}

		$this->db->query($q);
	}

	/**
	*	@param 	string 	the if of the post
	*	@param 	string 	handle of a user
	*	@return array 	get all details of a post with which the user is associated
	*/
	public function get_notes_details($post_id, $handle){
		/* validate if user has access to the post */
		$q = "SELECT count(*) FROM post_user_assoc WHERE post_id = '$post_id' AND user_assoc = '$handle'";
		$r = $this->db->query($q)->result_array();

		if ((int)($r[0]['count(*)']) == 0) return array();

		$q = "SELECT * FROM posts WHERE post_id = '$post_id'";
		$res = $this->db->query($q)->result_array();

		$q = "SELECT * from post_user_assoc WHERE post_id = '$post_id'";
		$u = $this->db->query($q)->result_array();
		return array($res[0], $u);
	}


	/**
	* 	@param 	string 	post_id of a post to delete
	*/
	public function delete_note_by_post_id($post_id){
		/* remove posts from the user_assoc table */
		$q = "DELETE FROM post_user_assoc WHERE post_id = '$post_id'";
		$this->db->query($q);

		/* delete the post from the posts table */
		$q = "DELETE FROM posts WHERE post_id = '$post_id'";
		$this->db->query($q);
	}

	/**
	*	@param 	string 	the handle of a user
	*	@param 	string 	value from {op_1, op_2} for vote over a poll
	*	@param 	string 	post_id of the poll
	*/
	public function insert_vote_for_poll($handle, $vote_option, $post_id){
		$ch = 1;
		if ($vote_option == "op_2") $ch = 2;
		$q = "UPDATE post_user_assoc SET choice = '$ch' WHERE post_id = '$post_id' AND user_assoc = '$handle'";
		$this->db->query($q);

		$q = "SELECT content FROM posts WHERE post_id = '$post_id'";
		$res = $this->db->query($q)->result_array()[0];

		$j = unserialize($res['content']);
		$j[$vote_option]['count']++;
		$mj = serialize($j);

		$q = "UPDATE posts SET content = '$mj' WHERE post_id = '$post_id'";
		$this->db->query($q);
	}

	/**
	* 	@param 	string 	post id of the post
	*	@param 	array 	the list of changes in the task stats
	*/
	public function update_task_list($post_id, $d){
		$q = "SELECT content FROM posts WHERE post_id = '$post_id'";
		$res = $this->db->query($q)->result_array();
		/* the db stores the task information as a serilized array */
		$res = unserialize($res[0]['content']);
		/* Unserialize and perform the changes as in $d */
		for ($i = 0; $i < count($d); $i++){
			$res[(int)$d[$i]]['done'] = 1;
		}
		/* serialize the data to put back into the db */
		$res = serialize($res);
		$q = "UPDATE posts SET content = '$res' WHERE post_id = '$post_id'";
		$this->db->query($q);
	}
}


?>