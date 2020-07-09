<?php  /*Template Name:首页*/  ?>
<?php  include $this->GetTemplate('header');  ?>
<body class="<?php  echo $type;  ?>">
<div class="wrapper">
    <?php  include $this->GetTemplate('navbar');  ?>
    <div class="main<?php if ($zbp->Config('tpure')->PostFIXMENUON=='1') { ?> fixed<?php } ?>">
        <div class="banner display"<?php if ($zbp->Config('tpure')->PostBANNER) { ?> style="background-image:url(<?php  echo $zbp->Config('tpure')->PostBANNER;  ?>);"<?php } ?>><div class="wrap"><h2><?php  echo $subname;  ?></h2></div></div>
        <div class="wrap">
            <div class="content">
                <div class="block">
                    <?php  foreach ( $articles as $article) { ?>
                        <?php if ($article->IsTop) { ?>
                        <?php  include $this->GetTemplate('post-istop');  ?>
                        <?php }else{  ?>
                        <?php  include $this->GetTemplate('post-multi');  ?>
                        <?php } ?>
                    <?php }   ?>
                </div>
                <div class="pagebar">
                    <?php  include $this->GetTemplate('pagebar');  ?>
                </div>
            </div>
            <div class="sidebar">
                <?php  include $this->GetTemplate('sidebar');  ?>
            </div>
        </div>
    </div>
    <?php  include $this->GetTemplate('footer');  ?>