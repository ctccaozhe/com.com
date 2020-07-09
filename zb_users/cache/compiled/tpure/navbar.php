<?php  /*Template Name:页头导航栏公共区*/  ?>
<div class="header<?php if ($zbp->Config('tpure')->PostFIXMENUON=='1') { ?> fixed<?php } ?>">
    <div class="wrap">
<?php if ($zbp->Config('tpure')->PostLOGOON == '1') { ?>
        <div class="logo<?php if ($zbp->Config('tpure')->PostLOGOHOVERON=='1') { ?> on<?php } ?>"><a href="<?php  echo $host;  ?>"><img src="<?php if ($zbp->Config('tpure')->PostLOGO) { ?><?php  echo $zbp->Config('tpure')->PostLOGO;  ?><?php }else{  ?><?php  echo $host;  ?>zb_users/zb_users/<?php  echo $theme;  ?>/style/images/logo.png<?php } ?>" alt="<?php  echo $name;  ?>"></a></div>
<?php }else{  ?>
        <h1 class="name"><a href="<?php  echo $host;  ?>"><?php  echo $name;  ?></a></h1>
<?php } ?>
        <div class="head">
            <div class="menuico"><span></span><span></span><span></span></div>
            <div class="menu">
                <ul<?php if ($zbp->Config('tpure')->PostSEARCHON=='0') { ?> class="nosch"<?php } ?>>
                    <?php  if(isset($modules['navbar'])){echo $modules['navbar']->Content;}  ?>

<?php if ($zbp->Config('tpure')->PostSEARCHON=='1') { ?>
                    <div class="schico statefixed">
                        <a href="javascript:;"></a>
                        <div class="schfixed">
                            <form method="post" name="search" action="<?php  echo $host;  ?>zb_system/cmd.php?act=search">
                                <input type="text" name="q" placeholder="<?php if ($zbp->Config('tpure')->PostSCHTXT) { ?><?php  echo $zbp->Config('tpure')->PostSCHTXT;  ?><?php }else{  ?><?php  echo $lang['tpure']['schtxt'];  ?><?php } ?>" class="schinput">
                                <button type="submit" class="btn"></button>
                            </form>
                        </div>
                    </div>
<?php } ?>
                </ul>
<?php if ($zbp->Config('tpure')->PostSEARCHON=='1') { ?>
                <form method="post" name="search" action="<?php  echo $host;  ?>zb_system/cmd.php?act=search" class="sch-m">
                    <input type="text" name="q" placeholder="<?php if ($zbp->Config('tpure')->PostSCHTXT) { ?><?php  echo $zbp->Config('tpure')->PostSCHTXT;  ?><?php }else{  ?><?php  echo $lang['tpure']['schtxt'];  ?><?php } ?>" class="schinput">
                    <button type="submit" class="btn"></button>
                </form>
<?php } ?>
            </div>
        </div>
    </div>
</div>