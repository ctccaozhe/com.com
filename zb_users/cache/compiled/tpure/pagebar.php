<?php if ($pagebar && $pagebar->PageAll > 1) { ?>
    <?php  foreach ( $pagebar->buttons as $k => $v) { ?>
        <?php if ($pagebar->PageNow==$k) { ?>
        <span class="now-page"><?php  echo $k;  ?></span>
        <?php }elseif($pagebar->PageNow+1==$k) {  ?>
        <span class="next-page"><a href="<?php  echo $v;  ?>"><?php  echo $k;  ?></a></span>
        <?php }else{  ?>
        <a href="<?php  echo $v;  ?>"><?php  echo $k;  ?></a>
        <?php } ?>
    <?php }   ?>
<?php } ?>