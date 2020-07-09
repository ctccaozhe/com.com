<label id="cmt<?php  echo $comment->ID;  ?>"></label><div class="cmtsitem">
    <a href="" class="avatar"><img src="<?php  echo $user->Avatar;  ?>" alt="<?php  echo $comment->Author->StaticName;  ?>"></a>
    <div class="cmtscon">
        <div class="cmtshead">
            <div class="cmtsname"><a href="<?php  echo $comment->Author->HomePage;  ?>" rel="nofollow" target="_blank"><?php  echo $comment->Author->StaticName;  ?></a></div>
            <div class="cmtsdate"><?php  echo $comment->Time();  ?></div>
        </div>
        <div class="cmtsbody" data-commentid="<?php  echo $comment->ID;  ?>"><p><?php  echo $comment->Content;  ?></p></div>
        <?php  foreach ( $comment->Comments as $comment) { ?>
            <?php  include $this->GetTemplate('commentreply');  ?>
        <?php }   ?>
        <div class="cmtsfoot"><a href="#comment" onclick="zbp.comment.reply('<?php  echo $comment->ID;  ?>')" class="reply"><?php  echo $lang['tpure']['reply'];  ?></a></div>
    </div>
</div>