<?php  /* Template Name:列表页普通文章 */  ?>
<div class="post">
    <h2><a href="<?php  echo $article->Url;  ?>" target="_blank"><?php  echo $article->Title;  ?></a></h2>
    <div class="info">
        <?php 
        $post_info = array(
            'user'=>$article->Author->StaticName,
            'date'=>tpure_TimeAgo($article->Time()),
            'cate'=>'<a href="'.$article->Category->Url.'" target="_blank">'.$article->Category->Name.'</a>',
            'view'=>$article->ViewNums,
            'cmt'=>$article->CommNums,
        );
        $list_info = json_decode($zbp->Config('tpure')->PostLISTINFO,true);
        if(count((array)$list_info)){
            foreach($list_info as $key => $info){
                echo $info==1?'<span class="'.$key.'">'.$post_info[$key].'</span>':'';
            }
        }else{
            echo '<span class="user">{$article.Author.StaticName}</span>
            <span class="date">{tpure_TimeAgo($article.Time())}</span>
            <span class="view">{$article.ViewNums}</span>';
        }
         ?>
    </div>
    <?php if (tpure_Thumb($article) != '') { ?><div class="postimg"><a href="<?php  echo $article->Url;  ?>" target="_blank"><img src="<?php  echo tpure_Thumb($article);  ?>" alt="<?php  echo $article->Title;  ?>"></a></div><?php } ?>
    <div class="intro<?php if (tpure_Thumb($article) != '') { ?> isimg<?php } ?>">
        <?php if (tpure_isMobile()) { ?><a href="<?php  echo $article->Url;  ?>" target="_blank"><?php } ?>
            <?php if ($zbp->Config('tpure')->PostINTRONUM) { ?>
            <?php $intro = preg_replace('/[\r\n\s]+/', ' ', trim(SubStrUTF8(TransferHTML($article->Intro,'[nohtml]'),$zbp->Config('tpure')->PostINTRONUM)).'...'); ?><?php  echo $intro;  ?>
            <?php }else{  ?><?php  echo $article->Intro;  ?><?php } ?>
        <?php if (tpure_isMobile()) { ?></a><?php } ?>
    </div>
    <?php if ($zbp->Config('tpure')->PostMOREBTNON) { ?><div><a href="<?php  echo $article->Url;  ?>" target="_blank" class="readmore"><?php  echo $lang['tpure']['viewmore'];  ?></a></div><?php } ?>
</div>