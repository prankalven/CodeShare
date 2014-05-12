<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>作者文章</title>
</head>
<body>
<div class="container article-view">
	<?php include("_content_nav.php") ?>	
	<!-- content -->
	<div class="content">

		<h1> <?=$user->Account ?></h1>

		<?php foreach($results as $article){ ?>
		<table class="table table-bordered">
			<tr>
				<td width="50">標題</td>
				<td>
					<a href="<?=site_url("article/view/".$article->ArticleID)?>">
					<?=htmlspecialchars($article->Title)?></a>
				</td>
			</tr>
			<tr>
				<td> 內容 </td>
				<td><?=nl2br(htmlspecialchars($article->Content))?></td>
			</tr>
			<tr>
				<td colspan="2">
					<a href="<?=site_url("article/edit/".$article->ArticleID)?>">編輯此文章</a>
					<a class="btn" href="<?=site_url("article/del/".$article->ArticleID)?>">刪除文章</a>
				</td>
			</tr>
		</table>
		<?php } ?>
		<p>
			<?=$pageLinks?>
		</p>

	</div>
</div>
</body>
</html>
