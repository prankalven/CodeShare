<!-- Article post content -->
	<div class="content">
		<form class="form-horizontal" role="form" action="<?=site_url("article/posting")?>" method="post">
			<div class="col-sm-6 col-sm-offset-3">
			<?php if(isset($errorMessage)){ ?>
				<div class="text-danger"><?=$errorMessage?></div>
			<?php } ?>
			<div class="form-group">
				<label class="col-sm-3">標題</label>
				<div class="col-sm-9">
					<?php if(isset($title)){ ?>
						<input type="text" placeholder="請輸入標題" name="title" value="<?=htmlspecialchars($title)?>" />
					<?php }else{ ?>
						<input type="text" placeholder="請輸入標題" name="title" />
					<?php } ?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3">內容</label>
				<div class="col-sm-9">
					<textarea name="content" class="form-control" rows="10" cols="60"><?php
							if(isset($content)){
								echo $content;
							}
					?></textarea>
		    	</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-3 col-sm-9">
					<button type="submit" class="btn btn-default">送出</button>
				</div>
			</div>
			</div>
		</form>
	</div>

<!-- Navbar -->
	<div class="collapse navbar-collapse">
	      <ul class="nav navbar-nav">
	        <li><a href="#">New post</a></li>
	        <li><a href="#">About</a></li>
	      </ul>
	      <!-- login status -->
	      <ul class="nav navbar-nav navbar-right">
	      	<?php if(isset($_SESSION["user"]) && $_SESSION["user"] != null){ ?>
		      	<li><a href="<?=site_url("user/logout")?>">Logout</a></li>
		      	<li><a href="#">Hi, <?=$_SESSION["user"]->Account?></a></li>
	          <li><a href="<?=site_url("article/post")?>">Post</a></li>
		      <?php }else{ ?>
		      	<li><a href="<?=site_url("/user/login")?>">Login</a></li>
		      	<li><a href="<?=site_url("/user/register")?>">Sign up</a></li>
		    <?php } ?>
	      </ul>
	</div>


<!-- Article Controller -->
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Article extends CI_Controller {
	public function author(){
		session_start();
		$this->load->view('article_author');
	}

	public function post(){
		session_start();
		if (!isset($_SESSION["user"])){//尚未登入時轉到登入頁
			redirect(site_url("/user/login")); //轉回登入頁
			return true;
		}
		$this->load->view('article_post');	
	}

	public function posting(){
		session_start();
		$title = trim($this->input->post("title"));
		$content= trim($this->input->post("content"));
		
		if( $title =="" || $content =="" ){
			$this->load->view('article_post',Array(
				"errorMessage" => "Title or Content shouldn't be empty,please check!" ,
				"title" => $title,
				"content" => $content
			));
			return false;
		}

		$this->load->model("ArticleModel");
		$insertID = $this->ArticleModel->insert($_SESSION["user"]->UserID,$title,$content);  //完成新增動作
		redirect(site_url("article/postSuccess/".$insertID));
	}	

	public function postSuccess($articleID){
		session_start();
		$this->load->view('article_success', $articleID);
	}

	public function edit(){
		session_start();
		$this->load->view('article_edit');	
	}

	public function view($articleID = null){
		session_start();
		if($articleID == null){
			show_404("Article not found !");
			return true;
		}

		$this->load->model("ArticleModel");
		//完成取資料動作
		$article = $this->ArticleModel->get($articleID); 

		if($article == null){
			show_404("Article not found !");
			return true;	
		}

		$this->load->view('article_view',Array(
			//設定網頁標題
			"pageTitle" => "發文系統 - 文章 [".$article->Title."] ", 
			"article" => $article
		));
	}
}

?>


<!-- Article Model -->
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ArticleModel extends CI_Model {
    function __construct()
    {
        parent::__construct();
    }
 
    function insert($author,$title,$content){
        $this->db->insert("article", 
            Array(
            "Author" =>  $author,
            "Title" => $title,
            "Content" => $content,
            "Views" => 0,
        ));     
        return $this->db->insert_id() ;
    }

    function get($articleID){
        //CI 裡面跨資料表結合的寫法
        $this->db->select("article.*,user.Account");
        $this->db->from('article');
        $this->db->join('user', 'article.author = user.userID', 'left');
        $this->db->where(Array("articleID" => $articleID));
        $query = $this->db->get();

        if ($query->num_rows() <= 0){
            return null; //無資料時回傳 null
        }

        return $query->row();  //回傳第一筆
    }    
}
?>

<!-- Article Success view -->
<div class="container">
	<?php include("_content_nav.php") ?>
	<div class="content">
		<div class="alert alert-success">
			<!-- Watch up for html injection -->
			文章發表成功，<a href="<?=site_url("article/view/".htmlspecialchars($articleID))?>">馬上連往瀏覽!</a>
		</div>
	</div>
</div>

<!-- Article view -->
<table class="table table-bordered">
	<tr>
		<td>作者</td>
		<td><?=htmlspecialchars($article->Account)?></td>
	</tr>
	<tr>
		<td>標題</td>
		<td><?=htmlspecialchars($article->Title)?></td>
	</tr>
	<tr>
		<td> 內容 </td>
		<td><?=nl2br(htmlspecialchars($article->Content))?></td>
	</tr>
</table>

<!-- MY_Controller.php -->
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class  MY_Controller  extends  CI_Controller  {
	public function __construct(){
		parent::__construct();
		$this->_init();
	}
	protected function _init(){
		session_start();
	}
}
