<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Article extends MY_Controller {
	public function author($author = null,$offset = 0){
		if($author == null){
			show_404("Author not found !");
			return true;
		}
 
		//引入 model
		$this->load->model("UserModel");
		$this->load->model("ArticleModel");
 
	 	//先查詢使用者是否存在
		$user = $this->UserModel->getUserByAccount($author);
		if($user == null){
			show_404("Author not found !");
		}
		$pageSize = 4;
 
 
	    $this->load->library('pagination');
	    $config['uri_segment'] = 4;
	    $config['base_url'] = site_url('/article/author/'.$author.'/');
	    //取得總數量
	    $config['total_rows'] = $this->ArticleModel->countArticlesByUserID($user->UserID);
 
	    $config['per_page'] = $pageSize;

	    $this->pagination->initialize($config);
			
	    $results = $this->ArticleModel->getArticlesByUserID($user->UserID,$offset,$pageSize);
 
		$this->load->view('article_author',
			Array(
				"results" => $results,
				"user" => $user,
				"pageLinks" => $this->pagination->create_links()
			)
		);
	}
	
	public function edit($articleID = null){
		if (!isset($_SESSION["user"]) || $_SESSION["user"] == null ){ 
			//沒有登入的，導回登入頁面
			redirect(site_url("/user/login")); 
			return true;
		}	

		if ( $articleID == null){
			show_404("Article not found !");
			return true;
		}


		$this->load->model("ArticleModel"); //完成取資料動作
		$article = $this->ArticleModel->get($articleID); 

		if ($article->Author != $_SESSION["user"]->UserID ){
			show_404("Article not found !"); 
			//不是作者無法編輯，送他回首頁
			redirect(site_url("/")); 
			return true;
		}

		$this->load->view('article_edit',Array(
			"pageTitle" => "修改文章 [".$article->Title."]",
			"article" => $article
		));	
	}

	//表單送出後更新資料頁
	public function update(){
		$articleID = $this->input->post("articleID");
 
		//就算是進行更新動作，該做的檢查還是都不能少
		if (!isset($_SESSION["user"]) || $_SESSION["user"] == null ){
			//沒有登入的，導回登入頁面
			redirect(site_url("/user/login")); 
			return true;
		}		
 
		if ( $articleID == null){
			show_404("Article not found !");
			return true;
		}
		
 
		$this->load->model("ArticleModel");
		//完成取資料動作
		$article = $this->ArticleModel->get($articleID);  
 
		if ($article->Author != $_SESSION["user"]->UserID ){
			show_404("Article not found !");
			//不是作者無法編輯，送他回首頁
			redirect(site_url("/")); 
			return true;
		}
 
		$this->ArticleModel->updateArticle(
			$articleID,
			$this->input->post("title"),
			$this->input->post("content")
		);
 
		//更新完後送他回文章檢視頁面
		redirect(site_url("article/view/".$articleID));
 
	}
}
?>
