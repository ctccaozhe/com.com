<?php  /*Template Name:404错误页*/  ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Language" content="<?php  echo $language;  ?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title><?php  echo $lang['tpure']['error404'];  ?> - <?php  echo $name;  ?></title>
    <link rel="stylesheet" rev="stylesheet" href="<?php  echo $host;  ?>zb_users/theme/<?php  echo $theme;  ?>/style/<?php  echo $style;  ?>.css" type="text/css" media="all"/>
<?php if ($zbp->Config('tpure')->PostCOLORON == '1') { ?>
    <link rel="stylesheet" rev="stylesheet" href="<?php  echo $host;  ?>zb_users/theme/<?php  echo $theme;  ?>/include/skin.css" type="text/css" media="all"/>
<?php } ?>
    <script src="<?php  echo $host;  ?>zb_system/script/jquery-2.2.4.min.js" type="text/javascript"></script>
    <script src="<?php  echo $host;  ?>zb_system/script/zblogphp.js" type="text/javascript"></script>
    <script src="<?php  echo $host;  ?>zb_system/script/c_html_js_add.php" type="text/javascript"></script>
    <script type="text/javascript" src="<?php  echo $host;  ?>zb_users/theme/<?php  echo $theme;  ?>/script/common.js"></script>
    <script type="text/javascript">window.tpure={<?php if ($zbp->Config('tpure')->PostBANNERDISPLAYON=='1') { ?>bannerdisplay:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostVIEWALLON=='1') { ?>viewall:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostVIEWALLSTYLE) { ?>viewallstyle:'1',<?php }else{  ?>viewallstyle:'0',<?php } ?><?php if ($zbp->Config('tpure')->PostVIEWALLHEIGHT) { ?>viewallheight:'<?php  echo $zbp->Config('tpure')->PostVIEWALLHEIGHT;  ?>',<?php } ?><?php if ($zbp->Config('tpure')->PostSINGLEKEY=='1') { ?>singlekey:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostPAGEKEY=='1') { ?>pagekey:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostREMOVEPON=='1') { ?>removep:'on',<?php } ?><?php if ($zbp->Config('tpure')->PostBACKTOTOPON=='1') { ?>backtotop:'on'<?php } ?>}</script>
<?php if ($zbp->Config('tpure')->PostBLANKON=='1') { ?>
    <base target="_blank" />
<?php } ?>
<?php if ($zbp->Config('tpure')->PostGREYON=='1') { ?>
<style>html {filter: grayscale(100%);}</style>
<?php } ?>
    <?php  echo $header;  ?>
</head>
<body class="<?php  echo $type;  ?>">
<div class="wrapper">
    <?php  include $this->GetTemplate('navbar');  ?>
    <div class="main<?php if ($zbp->Config('tpure')->PostFIXMENUON=='1') { ?> fixed<?php } ?>">
        <div class="mask"></div>
        <div class="wrap">
            <div class="errorpage">
				<h3><?php  echo $lang['tpure']['error404txt'];  ?></h3>
				<h4><?php  echo $lang['tpure']['nopage'];  ?></h4>
				<p><?php  echo $lang['tpure']['trysearch'];  ?></p>
				<form name="search" method="post" action="<?php  echo $host;  ?>zb_system/cmd.php?act=search" class="errorsearch">
				<input type="text" name="q" size="11" class="errschtxt"> 
				<input type="submit" value="<?php  echo $lang['tpure']['search'];  ?>" class="errschbtn">
				</form>
				<a class="goback" href="<?php  echo $host;  ?>"><?php  echo $lang['tpure']['back'];  ?> <?php  echo $name;  ?> <?php  echo $lang['tpure']['index'];  ?></a>
			</div>
        </div>
    </div>
    <?php  include $this->GetTemplate('footer');  ?>