<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Requests extends Api_Request{
	function __construct() {
		parent::__construct();

		$this->load->helper('url');
	}

	public function index($page = 1, $recordsInPage = 5, $pageRange = 5) {
		$this->load->model("Mdl_Requests");
		$this->load->model("Mdl_Users");

		$requests = $this->Mdl_Requests->get_list(
			$recordsInPage,
			$page,
			'',
			'',
			'',
			'');

		$requestCnt = $this->Mdl_Requests->get_list(
			$recordsInPage,
			$page,
			'',
			'',
			'',
			'',
			true);

		/*
			Pagination....
		*/
		$pageCnt = ceil($requestCnt / $recordsInPage);

		$paginationHTML = "<nav style='text-align:center'><ul class='pagination'>";

		$paginationHTML .= "<li><a href='" . site_url("Requests/" . max(1, $page - 1)) . "' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";

		for ($i=0; $i<$pageRange; $i++) {
			$currentPage = $i + $page;
			$className = "";

			if ($currentPage > $pageCnt) {
				$className = "deadlink";
			}

			$paginationHTML .= "<li><a class='" . $className . "' href='" . site_url("Requests/" . $currentPage) . "'>" . $currentPage . "</a></li>";
		}

		$paginationHTML .= "<li><a href='" . site_url("Requests/" . min($pageCnt, $page + 1)) . "' aria-label='Previous'><span aria-hidden='true'>&raquo;</span></a></li>";

		$paginationHTML .= "</ul></nav>";

		$requestsHTML = array();

		foreach ($requests as $key=>$req) {

			$link = site_url($this->ctrlName . "/edit/" . $req->id);

			$reqHtmlRow = "<a class='table-row req-row' href='" . $link . "'>";

			$host = $this->Mdl_Users->get($req->host);
			$reqHtmlRow .= "<div class='table-cell'>" . $host->username . "</div>";

			$reqHtmlRow .= "<div class='table-cell' style='text-align:center'>";

			if ($req->type == "REQ_FEED") {
				if 		($req->mediatype == "IMG") {
					$reqHtmlRow .= "<img class='req-feed-img' src='" . $req->mediaurl . "''></img>";
				}
				else if ($req->mediatype == "VIDEO") {
					$reqHtmlRow .= "<video class='req-feed-vid' controls='' name='media'>";
					$reqHtmlRow .= "<source src='" . $req->mediaurl . "'' type='video/mp4'>";
					$reqHtmlRow .= "</video>";
				}
				else if ($req->mediatype == "TEXT") {
					$reqHtmlRow .= "No media attached.";
				}
			}
			else {
				$reqHtmlRow .= "<div>...</div>";
			}

			$reqHtmlRow .= "</div>";

			if ($req->motive == null || $req->motive == "")		$req->motive = "Not provided.";
			if ($req->detail == null || $req->detail == "")		$req->detail = "Not provided.";


			$reqHtmlRow .= "<div class='table-cell'>" . $req->motive . "</div>";
			$reqHtmlRow .= "<div class='table-cell'>" . $req->detail . "</div>";

			
			$reqHtmlRow .= "</a>";

			array_push($requestsHTML, $reqHtmlRow);
		}

		parent::initView($this->ctrlName . '/list.php', 'pray request list.', 'Manage motive, details and media such as images and videos.',
			array(
				"requestsHTML" => $requestsHTML,
				"paginationHTML" => $paginationHTML
				)
		);

		parent::loadView();
	}

	public function edit($id) {

		$this->load->model("Mdl_Requests");
		$this->load->model("Mdl_Comments");
		$this->load->model("Mdl_Users");

		$req = $this->Mdl_Requests->get($id);
		$requestMediaHTML = "";

		if ($req->type == "REQ_FEED") {
			if ($req->mediatype == "IMG") {
				$requestMediaHTML .= "<img class='req-feed-img' src='" . $req->mediaurl . "''></img>";
			}
			else if ($req->mediatype == "VIDEO") {
				$requestMediaHTML .= "<video class='req-feed-vid' controls='' name='media'>";
				$requestMediaHTML .= "<source src='" . $req->mediaurl . "'' type='video/mp4'>";
				$requestMediaHTML .= "</video>";
			}
			else if ($req->mediatype == "TEXT") {
				$requestMediaHTML = "No media attached.";
			}
		}
		else {
			$requestMediaHTML .= "...";
		}

		$comments = $this->Mdl_Comments->getAll("request", $req->id);

		$commentsHTML = "<div>";

		if (count($comments)) {
			foreach ($comments as $comm) {
				$comm->commenter = $this->Mdl_Users->get($comm->commenter);

				$userLink = site_url("Users/edit/" . $comm->commenter->id);

				$commentsHTML .= "<p class='req-comment'>";
				$commentsHTML .= $comm->comment;
				$commentsHTML .= "<div>Written by <span class='req-commenter'><a href='" . $userLink . "'>" . $comm->commenter->username . "  </a></span> " . $comm->updated_time . "</div>";
				$commentsHTML .= "</p>";
			}
		}
		else {
			$commentsHTML .= "No comments provided.";
		}

		$commentsHTML .= "</div>";

		parent::initView($this->ctrlName . '/edit.php', 'Pray request', 'Manage media such as images and videos',
			array(
				"request" => $req,
				"requestMediaHTML" => $requestMediaHTML,
				"commentsHTML" => $commentsHTML,
				"commentCount" => count($comments)
				)
		);

		parent::loadView();
	}
}

?>