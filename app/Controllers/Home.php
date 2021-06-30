<?php

namespace App\Controllers;

use App\Models\CommentsModel;
use App\Models\ConfessionsModel;
use App\Models\ReplyToCommentModel;

class Home extends BaseController
{
	public $db;

	public function __construct(){
		$this->db = db_connect();
	}

	/*********************** 
			 Pages
	 ***********************/

	public function index(){
		$page = (int)$this->request->getVar("page") ? (int)$this->request->getVar("page") : 1; // Current page on index.php
		$record_per_page = 10; // Set Record per page that we want
		$start_from = ($page - 1) * $record_per_page; // Starting from
		// Ordering by on click button
		$order_by_latest = $this->request->getVar("latest") ;
		$order_by_approvals = $this->request->getVar("approvals") ;
		$order_by_disapprovals = $this->request->getVar("disapprovals") ;
		$order_by_oldest = $this->request->getVar("oldest") ;
		$order_by_random = $this->request->getVar("random");
		$check_order = 'RAND()';

		if(isset($order_by_latest)){
			$check_order = 'confessions.id DESC';
		}

		if(isset($order_by_approvals)){
			$check_order = 'actions.approvals DESC';
		}

		if(isset($order_by_disapprovals)){
			$check_order = 'actions.disapprovals DESC';
		}

		if(isset($order_by_oldest)){
			$check_order = 'confessions.id ASC';
		}

		if(isset($order_by_random)){
			$check_order = 'RAND()';
		}
		$query = "SELECT confessions.*, actions.approvals, actions.disapprovals, COUNT(comments.confess_id) as comments FROM confessions LEFT JOIN actions on confessions.id = actions.confess_id LEFT JOIN comments ON confessions.id = comments.confess_id GROUP BY confessions.id  ORDER BY $check_order LIMIT $start_from, $record_per_page"; // Make a query for pages
		$result = $this->db->query($query);
		$all_confessions = $result->getResultArray(); // Getting the results

		$page_query = "SELECT * FROM `confessions` ORDER BY `id` DESC"; // Making query to show page numbers
		$page_result = $this->db->query($page_query);
		$total_records = $page_result->getNumRows(); // Get all records as number
		$total_pages = ceil($total_records / $record_per_page); // Divede total records with records per page to show and get number of pages to show

		$data = [
			"title" => "myconfessio.com - Anonymous and personal confessions",
			"all_confessions" => $all_confessions,
			"total_pages" => $total_pages,
		];

		return view('index', $data);
	}

	public function confess(){
		$siteData = [
			"title" => "myconfessio.com - Anonymous and personal confessions"
		];

		if ($this->request->getMethod() == "post") {
			$rules = [
				"confession_text"=> [
					"rules"=> "required|min_length[50]",
					"errors"=>[
						"required"=> "Sorry, but you cannot post an empty confession!",
						"min_length"=> "Sorry, but the minimum is 50 characters to post a confession!"
					]
				]
			];

			$validation = \Config\Services::validation();

			if(!$this->validate($rules)){
				return view('confess', ["title" => "confessions.com - Anonymous and personal confessions", "validation" => $this->validator]);
			}else{
				$confession_text = strip_tags(ucfirst($this->request->getVar("confession_text"))); // Strip tags and frist letter upper case
				$data = [
					"confession_text"=> $confession_text
				]; // Pass it into array so we can inserted

				$confession_model = new ConfessionsModel(); // Call the ConfessionsModel that contain all data about our table confessions
				$confession_model->insert($data); // Inserting data into confessions table

				$session = session(); // Set session for success message on insert
				$session->setFlashdata('success', "Confession Successfully Posted!"); // Set session on success and message
			}
		}

		return view('confess', $siteData);
	}

	public function single_confess(){
		$confess_id = strip_tags((int)$this->request->getVar("confess_id"));
		$query = $this->db->query("SELECT confessions.*, actions.approvals, actions.disapprovals, COUNT(comments.confess_id) as comments FROM confessions LEFT JOIN actions on confessions.id = actions.confess_id LEFT JOIN comments ON confessions.id = comments.confess_id WHERE confessions.id= :id:", ["id"=> $confess_id]);

		$count = $query->getNumRows();
		if($count > 0){
			$fetch = $query->getRowArray();
			$title = substr($fetch["confession_text"], 0, 19);
			$siteData = [
				"title" => $title. " - confessions.com Anonymous and personal confessions",
				"single_confession" => $fetch
			];

			return view('single_confess', $siteData);
		}else{
			return redirect()->to("/index.php?error=Invalid-Confess-Id");
		}
	}

	public function search(){
		$search_query = strip_tags($this->request->getVar("q"));
		$siteData = [
				"title" => "$search_query - myconfessio.com - Anonymous and personal confessions"
		];

		if (!isset($search_query) || empty($search_query)) {
			$siteData["error"] = "Sorry, but search query cannot be empty.";
			return view("search", $siteData);
		} else {
			if (strlen($search_query) >= 5) {
				$query = $this->db->query("SELECT confessions.*,actions.approvals, actions.disapprovals, COUNT(comments.confess_id) as comments FROM confessions LEFT JOIN actions on confessions.id = actions.confess_id LEFT JOIN comments ON confessions.id = comments.confess_id GROUP BY confessions.id HAVING confessions.confession_text LIKE '%$search_query%' ORDER BY id DESC");
				$count = $query->getNumRows();
				if ($count > 0) {
					$fetchAll = $query->getResultArray();
					$siteData["count"] = $count;
					$siteData["fetchAll"] = $fetchAll;

					return view("search", $siteData);
				} else {
					$siteData["error"] = "Sorry, we can't find anything related to $search_query, please try with something different.";
					return view("search", $siteData);
				}
			} else {
				$siteData["error"] = "Sorry, but the minimum length to search is 5 characters.";
				return view("search", $siteData);
			}
		}
	}

	/*********************** 
			 AJAX
	***********************/

	public function confess_actions(){
		$result = array();

		$confess_id = strip_tags((int)$this->request->getVar("confess_id"));
		$clicked_value = strip_tags($this->request->getVar("clicked_value"));
		$is_clicked = strip_tags((int)$this->request->getVar("is_clicked"));
		if($is_clicked > 0 && $is_clicked < 11){
			$check_confess_action = $this->db->query("SELECT * FROM actions WHERE confess_id = ?", [$confess_id]);
			$check_confess_action_row_count = $check_confess_action->getNumRows();

			if ($clicked_value == "approve") {
				if ($check_confess_action_row_count > 0) {
					if ($update_a = $this->db->query("UPDATE actions SET approvals = approvals + 1 WHERE confess_id = ?", [$confess_id])) {
						$result["confess_id"] = $confess_id;
						$result["action"] = "approve";
						$result["updated"] = true;
					}
				} else {
					if ($insert = $this->db->query("INSERT INTO actions(confess_id, approvals) VALUES(?, ?)", [$confess_id, 1])) {
						$result["confess_id"] = $confess_id;
						$result["action"] = "approve";
					}
				}
			}

			if ($clicked_value == "disaprove") {
				if ($check_confess_action_row_count > 0) {
					if ($update_d = $this->db->query("UPDATE actions SET disapprovals = disapprovals + 1 WHERE confess_id = ?", [$confess_id])) {
						$result["confess_id"] = $confess_id;
						$result["action"] = "disaprove";
						$result["updated"] = true;
					}
				} else {
					if ($insert = $this->db->query("INSERT INTO actions(confess_id, disapprovals) VALUES(?, ?)", [$confess_id, 1])) {
						$result["confess_id"] = $confess_id;
						$result["action"] = "disaprove";
					}
				}
			}
		}else{
			$result["action"] = "Already Voted!";
		}
		echo json_encode($result);
	}

	public function insert_comment(){
		$result = array();

		$username = strip_tags($this->request->getVar("username"));
		$comment_text = strip_tags($this->request->getVar("comment_text"));
		$confess_id = strip_tags((int)$this->request->getVar("confess_id"));
		$reply_to = strip_tags((int)$this->request->getVar("reply_to"));

		if(!isset($confess_id) || empty($confess_id)){
			$result["error"][] = "Confession Id cannot be empty!";
		}

		if(!isset($username) || empty($username)){
			$username = "anyonimous";
		}

		if(isset($username) && !empty($username)){
			if(strlen($username) < 3){
				$result["error"][] = "Username cannot be less than 3 characthers!";
			}
		}

		if(!isset($comment_text) || empty($comment_text)){
			$result["error"][] = "Comment text cannot be empty!";
		}

		if(strlen($comment_text) < 50){
			$result["error"][] = "Comment text cannot be less than 50 characthers!";
		}

		if(empty($result["error"])){
			$comment_model = new CommentsModel();
			$reply_to_comment_model = new ReplyToCommentModel();

			if(!isset($reply_to) || empty($reply_to)){
				$data = [
					"username" => $username,
					"comment_text" => $comment_text,
					"confess_id" => $confess_id
				];
				if ($comment_model->save($data)) {
					$result["success"] = true;
				} else {
					$result["error"][] = "Sorry, currently we cannot post this comment, please try again later!";
				}
			}else{
				$reply_to = [
					"confess_id" => $confess_id,
					"reply_to_comment_id" => $reply_to,
					"comment_text" => $comment_text,
					"username" => $username
				];

				if ($reply_to_comment_model->save($reply_to)) {
					$result["success"] = true;
					$result["reply"] = "Successfully";
				}
			}
		
		}

		echo json_encode($result);
	}

	public function recive_comments(){
		$result = array();
		$confess_id = strip_tags((int)$this->request->getVar("confess_id"));

		if(!isset($confess_id) || empty($confess_id)){
			return redirect()->to("/");
		}else{
			$select_comments = $this->db->query("SELECT comments.*, comments_actions.approvals, comments_actions.disapprovals FROM comments LEFT JOIN comments_actions ON comments.confess_id = comments_actions.confess_id AND comments.id = comments_actions.comment_id WHERE comments.confess_id = ? ORDER BY comments.id DESC ", [$confess_id]);
			$select_reply_comments = $this->db->query("SELECT reply_to_comment.*, comments_actions.approvals, comments_actions.disapprovals FROM reply_to_comment LEFT JOIN comments_actions ON reply_to_comment.confess_id = comments_actions.confess_id AND reply_to_comment.id = comments_actions.reply_comment_id WHERE reply_to_comment.confess_id = ? ORDER BY reply_to_comment.id DESC",[$confess_id]);

			if($select_comments->getNumRows() > 0 || $select_reply_comments->getNumRows() > 0){
				$comments = "";

				$all_comments_results = $select_comments->getResultArray();
				$all_reply_comments = $select_reply_comments->getResultArray();

				foreach($all_comments_results as $all_comments){
					$comments .= '<div class="comment-'. $all_comments['id'] .' mt-3"><p>' . $all_comments['username'] . '</p>
									<p class="ml-3">' . $all_comments['comment_text'] .'</p>
									<p class="text-right text-muted">'. date('d M Y', strtotime($all_comments['created_at'])) .'</p>
									<form method="post" class="d-flex justify-content-around p-2">
										<div class="form-group mb-0">
											<button type="button" class="comment-action comment-approve-'. $all_comments["id"] .'" data-value="approve" data-comment-id="'. $all_comments["id"] .'" data-confess-id="'. $all_comments["confess_id"] .'">
												<i class="fas fa-thumbs-up"></i>
											</button>
											<span>'. $all_comments["approvals"] .'</span>
										</div>
										<div class="form-group mb-0">
											<button type="button" class="comment-action comment-disapprove-'. $all_comments["id"] .'"" data-value="disapprove" data-comment-id="'. $all_comments["id"] .'" data-confess-id="'. $all_comments["confess_id"] .'">
												<i class="fas fa-thumbs-down"></i>
											</button>
											<span>'. $all_comments["disapprovals"] .'</span>
										</div>
										<button class="reply" data-reply="' . $all_comments['id'] . '"><i class="fas fa-reply"></i></button>
									</form>
									<div class="reply-to-section-'. $all_comments['id'] .'">';
										foreach($all_reply_comments as $reply_comments){
											if ($all_comments["id"] == $reply_comments["reply_to_comment_id"]) {
												$comments .= '<div class="reply-comment ml-5 mt-5">
																<p>- '. $reply_comments["username"] . '</p>
																<p>'. $reply_comments["comment_text"] . '</p> 
																<p class="text-right text-muted">'. date("d M Y", strtotime($reply_comments["created_at"])) .'</p>
																<form method="post" class="d-flex justify-content-around p-2">
																	<div class="form-group mb-0">
																		<button type="button" class="comment-action reply-approve-'. $reply_comments["id"] .'" data-value="approve" data-reply-comment-id="'. $reply_comments["id"]  .'" data-confess-id="'. $reply_comments["confess_id"] .'">
																			<i class="fas fa-thumbs-up"></i>
																		</button>
																		<span>'. $reply_comments["approvals"] .'</span>
																	</div>
																	<div class="form-group mb-0">
																		<button type="button" class="comment-action reply-disapprove-'. $reply_comments["id"] .'" data-value="disapprove" data-reply-comment-id="'. $reply_comments["id"]  .'" data-confess-id="'. $reply_comments["confess_id"] .'">
																			<i class="fas fa-thumbs-down"></i>
																		</button>
																		<span>'. $reply_comments["disapprovals"] .'</span>
																	</div>
																	<button class="reply" data-reply="'. $reply_comments["reply_to_comment_id"] .'"><i class="fas fa-reply"></i></button>
																</form>
															</div>';
											}
										}
						
                            $comments .= '
									<hr>
									</div>
							</div>';
				}
				$result["comments"] = $comments;
			}
		}
		echo json_encode($result);
	}

	public function comments_actions(){
		$result = array();
		$confess_id = strip_tags((int)$this->request->getVar('confess_id'));
		$clicked_value = strip_tags($this->request->getVar('clicked_value'));
		$comment_id = strip_tags((int)$this->request->getVar('comment_id'));
		$reply_comment_id = strip_tags((int)$this->request->getVar('reply_comment_id'));
		$is_clicked = strip_tags((int)$this->request->getVar('is_clicked'));

		if($is_clicked > 0 && $is_clicked < 11){
			$query = $this->db->query("SELECT * FROM comments_actions WHERE confess_id = ? AND comment_id = ? AND reply_comment_id = ?", [$confess_id, $comment_id, $reply_comment_id]);
			if($query->getNumRows() > 0){
				$fetch = $query->getRowArray();

				if($clicked_value == "approve"){
					$update = $this->db->query("UPDATE comments_actions SET approvals = approvals + 1 WHERE confess_id = ? AND comment_id = ? AND reply_comment_id = ?", [$confess_id, $comment_id, $reply_comment_id]);

					$result["success_update"] = true;
					$result["clicked_value"] = $clicked_value;
					$result["comment_id"] = $comment_id;
					$result["reply_comment_id"] = $reply_comment_id;
				}	
				
				if($clicked_value == "disapprove"){
					$update = $this->db->query("UPDATE comments_actions SET disapprovals = disapprovals + 1 WHERE confess_id = ? AND comment_id = ? AND reply_comment_id = ?",  [$confess_id, $comment_id, $reply_comment_id]);

					$result["success_update"] = true;
					$result["clicked_value"] = $clicked_value;
					$result["comment_id"] = $comment_id;
					$result["reply_comment_id"] = $reply_comment_id;
				}
			}else{
				if($clicked_value == "approve"){
					$insert = $this->db->query("INSERT INTO comments_actions (confess_id, comment_id, reply_comment_id, approvals) VALUES(?, ?, ?, 1) ", [$confess_id, $comment_id, $reply_comment_id]);

					$result["success_update"] = true;
					$result["clicked_value"] = $clicked_value;
					$result["comment_id"] = $comment_id;
					$result["reply_comment_id"] = $reply_comment_id;
				}	
				
				if($clicked_value == "disapprove"){
					$insert = $this->db->query("INSERT INTO comments_actions (confess_id, comment_id, reply_comment_id, disapprovals) VALUES(?, ?, ?, 1) ", [$confess_id, $comment_id, $reply_comment_id]);
					
					$result["success_update"] = true;
					$result["clicked_value"] = $clicked_value;
					$result["comment_id"] = $comment_id;
					$result["reply_comment_id"] = $reply_comment_id;
				}
			}

		}else{
			$result["error"] ="Sorry, you already voted!";
		}
		echo json_encode($result);
	}
}
