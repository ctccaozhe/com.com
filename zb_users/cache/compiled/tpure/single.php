<?php  /*Template Name:文章页/单页模板*/  ?>
<?php  include $this->GetTemplate('header');  ?>
<body class="<?php  echo $type;  ?>">
<div class="wrapper">
    <?php  include $this->GetTemplate('navbar');  ?>
    <div class="main<?php if ($zbp->Config('tpure')->PostFIXMENUON=='1') { ?> fixed<?php } ?>">
        <div class="mask"></div>
        <div class="wrap">
            <?php if ($article->Type==ZC_POST_TYPE_ARTICLE) { ?>
                <?php  include $this->GetTemplate('post-single');  ?>
            <?php }else{  ?>
                <?php  include $this->GetTemplate('post-page');  ?>
            <?php } ?>
        </div>
    </div>
    <?php  include $this->GetTemplate('footer');  ?>