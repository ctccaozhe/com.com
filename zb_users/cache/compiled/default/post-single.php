<?php  /* Template Name:文章页文章内容 */  ?>
<div class="post single">
	<h2 class="post-title"><?php  echo $article->Title;  ?><span class="post-date"><?php  echo $article->Time();  ?></span></h2>
	<div class="post-body"><?php  echo $article->Content;  ?></div>
	<h5 class="post-tags">
                <?php if (count($article->Tags)>0) { ?><?php  echo $lang['msg']['tags'];  ?>:<?php  foreach ( $article->Tags as $i => $tag) { ?>&nbsp;<a href='<?php  echo $tag->Url;  ?>' title='<?php  echo $tag->Name;  ?>'><?php  echo $tag->Name;  ?></a>&nbsp;<?php if (count($article->Tags) > $i) { ?><small>,</small><?php } ?><?php }   ?><?php } ?>
	</h5>
	<h6 class="post-footer">
		<?php  echo $lang['msg']['author'];  ?>:<?php  echo $article->Author->StaticName;  ?> <small>|</small> <?php  echo $lang['msg']['category'];  ?>:<?php  echo $article->Category->Name;  ?> <small>|</small> <?php  echo $lang['default']['view'];  ?>:<?php  echo $article->ViewNums;  ?> <small>|</small> <?php  echo $lang['msg']['comment'];  ?>:<?php  echo $article->CommNums;  ?>
	</h6>
</div>

<?php if (!$article->IsLock) { ?>
<?php  include $this->GetTemplate('comments');  ?>
<?php } ?>