<?php  /* Template Name:搜索页文章列表 */  ?>
<div class="post multi">
	<h2 class="post-title"><a href="<?php  echo $article->Url;  ?>"><?php  echo $article->Title;  ?></a><span class="post-date"><?php  echo $article->Time();  ?></span></h2>
	<div class="post-body"><?php  echo $article->Intro;  ?></div>
</div>