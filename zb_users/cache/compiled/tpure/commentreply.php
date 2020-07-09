<div class="cmtsreply">
    <div class="cmtsreplyname"><a href="<?php  echo $comment->Author->HomePage;  ?>" rel="nofollow" target="_blank"><?php  echo $comment->Author->StaticName;  ?></a> <?php  echo $lang['tpure']['replytxt'];  ?></div>
    <div class="cmtsreplycon"><?php  echo $comment->Content;  ?></div>
    <div class="cmtsreplydate"><?php  echo $comment->Time();  ?></div>
</div>
<?php  foreach ( $comment->Comments as $comment) { ?>
    <?php  include $this->GetTemplate('commentreply');  ?>
<?php }   ?>