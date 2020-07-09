<div class="content wide">
    <div class="block">
        <div class="post">
            <h1><?php  echo $article->Title;  ?></h1>
            <div class="info">
                <?php 
                $post_info = array(
                    'user'=>$article->Author->StaticName,
                    'date'=>tpure_TimeAgo($article->Time()),
                    'view'=>$article->ViewNums,
                    'cmt'=>$article->CommNums,
                );
                $page_info = json_decode($zbp->Config('tpure')->PostPAGEINFO,true);
                if(count($page_info)){
                    foreach($page_info as $key => $info){
                        echo $info==1?'<span class="'.$key.'">'.$post_info[$key].'</span>':'';
                    }
                }
                 ?>
            </div>
            <div class="single">
                <?php  echo $article->Content;  ?>
            </div>
        </div>
    </div>
<?php if (!$article->IsLock && $zbp->Config('tpure')->PostPAGECMTON=='1') { ?>
    <?php  include $this->GetTemplate('comments');  ?>
<?php } ?>
</div>