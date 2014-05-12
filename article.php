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
}
?>
