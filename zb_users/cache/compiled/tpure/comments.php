<?php if ($socialcomment) { ?>
<?php  echo $socialcomment;  ?>
<?php }else{  ?>
<div <?php if ($article->CommNums == '0') { ?>data-content="<?php  echo $lang['tpure']['nocmt'];  ?>"<?php } ?> class="cmts block<?php if ($article->CommNums=='0') { ?> nocmt<?php } ?>">
    <?php if ($article->CommNums>0) { ?><div class="posttitle"><h4><?php  echo $lang['tpure']['cmtlist'];  ?></h4></div><?php } ?>
    <label id="AjaxCommentBegin"></label>
	<?php  foreach ( $comments as $key => $comment) { ?>
	<?php  include $this->GetTemplate('comment');  ?>
	<?php }   ?>
    <?php if ($article->CommNums>0) { ?>
    <div class="cmtpagebar"><?php  include $this->GetTemplate('pagebar');  ?></div>
    <?php } ?>
	<label id="AjaxCommentEnd"></label>
</div>
<?php  include $this->GetTemplate('commentpost');  ?>
<?php } ?>