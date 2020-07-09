<?php  /* Template Name:列表页普通文章 */  ?>
<div class="post multi">
	<h2 class="post-title"><a href="<?php  echo $article->Url;  ?>"><?php  echo $article->Title;  ?></a><span class="post-date"><?php  echo $article->Time();  ?></span></h2>
	<div class="post-body"><?php  echo $article->Intro;  ?></div>
	<h6 class="post-footer">
		<?php  echo $lang['msg']['author'];  ?>:<?php  echo $article->Author->StaticName;  ?> <small>|</small> <?php  echo $lang['msg']['category'];  ?>:<?php  echo $article->Category->Name;  ?> <small>|</small> <?php  echo $lang['default']['view'];  ?>:<?php  echo $article->ViewNums;  ?> <small>|</small> <?php  echo $lang['msg']['comment'];  ?>:<?php  echo $article->CommNums;  ?>
	</h6>
</div>