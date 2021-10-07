<?php 
defined('BASEPATH') OR exit('No direct script access allowed');


class video_api_model extends CI_Model
{
	public function __construct(){
		$this->load->database();
	}


	/* Work start */

	public function top_user(){
		$this->db->select("*");
		$this->db->from("user");
		$this->db->limit('10');
		$this->db->order_by("total_points", "desc");
		$q = $this->db->get();
		return $q->result();

	} 

	// End work 


	// Below api are not used

	/*==========Category=======*/

	public function category(){
		$query = $this->db->query("select * from `category` where status='enable'");
		return $query->result_array();
	}

	public function video_by_category($where){
		$this->db->select("*");
		$this->db->from("video");
		$this->db->join('category', 'category.id = video.cat_id');
		if($where!=''){
			$this->db->where($where);
		}
		$q = $this->db->get();
		return $q->result();
	} 

	/*==========End Category=======*/


	/*==========User=======*/

	public function profile($where){
		$this->db->select("*");
		$this->db->from("user");	 
		$this->db->where($where);
		$q = $this->db->get();
		return $q->result();
	}

	public function get_userlist(){
		$query = $this->db->query("select * from `user`");
		return $query->result_array();
	}
	public function video_by_user($where){
		$this->db->select("*");
		$this->db->from("video");
		$this->db->join('category', 'category.id = video.cat_id');
		$this->db->where($where);
		$this->db->order_by("video.dowanload", "desc");
		$q = $this->db->get();
		return $q->result();

	} 

	public function update_users($data,$where){
		$this->db->where($where);
		$this->db->update('user', $data);
		return TRUE;
	} 

	public function insert_user($data){
		$query = $this->db->insert('user',$data);
		return $this->db->insert_id();
	}

	public function get_login($where){
		$this->db->select("*");
		$this->db->from("user");
		if($where!=''){
			$this->db->where($where);
		}
		$q = $this->db->get();
		return $q->row_array();
	}

	public function update_profile_img($data,$where){			
		$this->db->where($where);
		return $this->db->update('user',$data);
	}

	public function update_profile($data,$where){			
		$this->db->where($where);
		return $this->db->update('user',$data);
	}

	/*==============Earn Points================*/

	public function insert_point($data){
		$query = $this->db->insert('v_earnpoint',$data);
		return $this->db->insert_id();
	}

	public function point_by_user($where){
		$this->db->select("SUM(POINT) as point");
		$this->db->from("v_earnpoint");
		$this->db->where($where);
		$q = $this->db->get();
		return $q->result();
	}

	public function add_favorite($data){
		$query = $this->db->insert('v_bookmark',$data);
		return $this->db->insert_id();
	}

	public function favorite($where){
		$this->db->select("*");
		$this->db->from("v_bookmark");		  
		$this->db->where($where);
		     // $this->db->join('video', 'video.id = v_bookmark.b_v_id');	
		$q = $this->db->get();
		return $q->result();
	}

	public function delete_favorite($where){
		$this->db->where($where);
		return $this->db->delete("v_bookmark");		    
	}

	/*==============Earn Points================*/

	/*==========End User=======*/


	public function get_videoorder(){
		$this->db->select("video.*,category.category_name");
		$this->db->from("video");
		$this->db->join('category', 'category.id = video.cat_id');
		$this->db->order_by("video.c_date", "desc");
		$this->db->limit('10');
		$q = $this->db->get();
		return $q->result();
	} 
	
	public function insert_video($data){
		$query = $this->db->insert('video',$data);
		return $this->db->insert_id();
	}

	public function popular_video(){
		$this->db->select("video.*,category.category_name");
		$this->db->from("video");
		$this->db->join('category', 'category.id = video.cat_id');
		$this->db->order_by("video.dowanload", "desc");
		$this->db->limit('10');
		$q = $this->db->get();
		return $q->result();

	} 

	public function latest_video(){
		$this->db->select("AVG(rating) AS avg_rating,v_ratings.v_id,video.*,category.category_name");
	    $this->db->from("v_ratings");
	    $this->db->join("video", "video.id=v_ratings.v_id");
	    $this->db->join('category', 'category.id = video.cat_id');
	    $this->db->group_by("v_id");
	    $this->db->order_by("rating", "desc");
		$this->db->limit('10');
	    $query = $this->db->get();
		$result =  $query->result();
		return $result;
	}

	
	public function popular_video_rating($where){
		$this->db->select("avg(rating) as avg_rating");
		$this->db->from("v_ratings");
		$this->db->where($where);
		$q = $this->db->get();
		return $q->row();

	} 

	public function get_videostatus($where){
		$this->db->select("video.*,category.category_name");
		$this->db->from("video");
		$this->db->join('category', 'category.id = video.cat_id');
		if($where!=''){
			$this->db->where($where);
		}
		$q = $this->db->get();
		return $q->result();
	} 

	public function get_newarriaval($limit, $start) {
		$this->db->limit($limit, $start);
		$this->db->select("video.*,category.category_name");
		$this->db->from("video");
		$this->db->join('category', 'category.id = video.cat_id');
		$query = $this->db->get();
		if ($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function record_video() {

		return $this->db->count_all("video");
	}

	public function get_banner_video(){
		$this->db->select("video.*,category.category_name");
		$this->db->from("video");
		$this->db->join('category', 'category.id = video.cat_id');
		$this->db->order_by("c_date", "DESC");
		$this->db->limit("5");
		$q = $this->db->get();
		return $q->result();
	}

	public function genaral_setting(){
		$this->db->select("*");
		$this->db->from("general_setting");

		$q = $this->db->get();
		return $q->result_array();
	}

	public function insert_dowanload($data){
		$query = $this->db->insert('user_dowanload',$data);
		return $this->db->insert_id();
	}

	public function insert_follower($data){
		$query = $this->db->insert('follow',$data);
		return $this->db->insert_id();
	}

	public function insert_like($data){
		$query = $this->db->insert('like',$data);
		return $this->db->insert_id();
	}

	public function delet_like($wehre){
		$this->db->where($wehre);
		$this->db->delete('like');

	}

	public function get_follow($where){

		$this->db->select("*");
		$this->db->from("follow");		  
		$this->db->where($where);
		$q = $this->db->get();
		return $q->result();
	}
	
	public function delet_follower($wehre){

		$this->db->where($wehre);
		$this->db->delete('follow');

	}
	
	public function get_dowanload_videoid($where){
		
		$this->db->select("v_id");
		$this->db->from("user_dowanload");		  
		$this->db->where($where);
		$this->db->group_by('v_id'); 

		$this->db->limit('10');
		$q = $this->db->get();
		return $q->result();
	}

	public function update_view($where){

		$this->db->set('view', 'view+1', FALSE);
		$this->db->where($where);
		$this->db->update('video');
		return true;
	} 

	public function update_dowanload($where){

		$this->db->set('dowanload', 'dowanload+1', FALSE);
		$this->db->where($where);
		$this->db->update('video');
	} 

	public function update_user($id,$data){
		$this->db->where('id', $id);
		$this->db->update('user', $data);
		return true;
	}

	public function add_bookmark($data){
		$query = $this->db->insert('v_bookmark',$data);
		return $this->db->insert_id();
	}

	public function delete_bookmark($where){
		$this->db->where($where);
		$this->db->delete("v_bookmark");
		return "1";
	}

	public function bookmarks($where){
		$this->db->select("*");
		$this->db->from("v_bookmark");		  
		$this->db->where($where);
		$q = $this->db->get();
		return $q->result();
	}

	public function add_comment($data){
		$query = $this->db->insert('v_comment',$data);
		return $this->db->insert_id();
	}

	public function view_comment($where){
		$this->db->select("*");
		$this->db->from("v_comment");	
		$this->db->join('user', 'user.id = v_comment.user_id');	  
		$this->db->where($where);
		$q = $this->db->get();
		return $q->result();
	}
	

}