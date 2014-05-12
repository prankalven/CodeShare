<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>檢視文章</title>
</head>
<body>
<div class="container article-view">
	<?php include("_content_nav.php") ?>	
	<!-- content -->
	<div class="content blog-header">
		<div class="col-sm-6 col-sm-offset-3">
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
			<tr>
				<td></td>
				<td><a href="<?=site_url("article/edit/".$article->ArticleID)?>">編輯此文章</a></td>
			</tr>
		</table>
		</div>
	</div>
</div>
</body>
</html>
