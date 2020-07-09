<div class="content">
    <div class="block">
        <div class="post">
            <h1><?php  echo $article->Title;  ?></h1>
            <div class="info">
                <?php 
                $post_info = array(
                    'user'=>$article->Author->StaticName,
                    'date'=>tpure_TimeAgo($article->Time()),
                    'cate'=>'<a href="'.$article->Category->Url.'" target="_blank">'.$article->Category->Name.'</a>',
                    'view'=>$article->ViewNums,
                    'cmt'=>$article->CommNums,
                );
                $article_info = json_decode($zbp->Config('tpure')->PostARTICLEINFO,true);
                if(count((array)$article_info)){
                    foreach($article_info as $key => $info){
                        echo $info==1?'<span class="'.$key.'">'.$post_info[$key].'</span>':'';
                    }
                }else{
                    echo '<span class="user">{$article.Author.StaticName}</span>
                    <span class="date">{tpure_TimeAgo($article.Time())}</span>
                    <span class="view">{$article.ViewNums}</span>';
                }
                 ?>
            </div>
            <div class="single postcon">
                <?php  echo $article->Content;  ?>
                <?php if (count($article->Tags)>0) { ?>
                <div class="tags">
                    <?php  echo $lang['tpure']['tags'];  ?>
                    <?php  foreach ( $article->Tags as $tag) { ?><a href='<?php  echo $tag->Url;  ?>' title='<?php  echo $tag->Name;  ?>'><?php  echo $tag->Name;  ?></a><?php }   ?>
                </div>
                <?php } ?>
                <?php if ($zbp->Config('tpure')->PostSHARE) { ?>
                <div class="bdshare">
                    <?php  echo $zbp->Config('tpure')->PostSHARE;  ?>
                </div>
                <?php } ?>
            </div>
        </div>
        <div class="pages">
            <a href="<?php  echo $article->Category->Url;  ?>" class="backlist"><?php  echo $lang['tpure']['backlist'];  ?></a>
            <p><?php if ($article->Prev) { ?><?php  echo $lang['tpure']['prev'];  ?><a href="<?php  echo $article->Prev->Url;  ?>" class="single-prev"><?php  echo $article->Prev->Title;  ?></a><?php }else{  ?><span><?php  echo $lang['tpure']['noprev'];  ?></span><?php } ?></p>
            <p><?php if ($article->Next) { ?><?php  echo $lang['tpure']['next'];  ?><a href="<?php  echo $article->Next->Url;  ?>" class="single-next"><?php  echo $article->Next->Title;  ?></a><?php }else{  ?><span><?php  echo $lang['tpure']['nonext'];  ?></span><?php } ?></p>
        </div>
    </div>
<?php  include $this->GetTemplate('mutuality');  ?>
<?php if (!$article->IsLock && $zbp->Config('tpure')->PostARTICLECMTON=='1') { ?>
    <?php  include $this->GetTemplate('comments');  ?>
<?php } ?>
</div>
<div class="sidebar">
    <?php  include $this->GetTemplate('sidebar3');  ?>
</div>