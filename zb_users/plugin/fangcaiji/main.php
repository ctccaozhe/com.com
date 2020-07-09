<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('fangcaiji')) {$zbp->ShowError(48);die();}

$blogtitle='fangcaiji';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';

if(count($_POST)>0){
    $zbp->Config('fangcaiji')->bobocanshu1 = $_POST['bobocanshu1'];
    $zbp->Config('fangcaiji')->bobocanshu2 = $_POST['bobocanshu2'];
    $zbp->Config('fangcaiji')->bobocanshu3 = $_POST['bobocanshu3'];
    $zbp->Config('fangcaiji')->bobocanshu4 = $_POST['bobocanshu4'];
    $zbp->Config('fangcaiji')->bobocanshu5 = $_POST['bobocanshu5'];
    $zbp->Config('fangcaiji')->bobocanshu6 = $_POST['bobocanshu6'];
    $zbp->Config('fangcaiji')->bobocanshu7 = $_POST['bobocanshu7'];
    $zbp->Config('fangcaiji')->bobocanshu8 = $_POST['bobocanshu8'];
    $zbp->Config('fangcaiji')->bobocanshu9 = $_POST['bobocanshu9'];
    $zbp->Config('fangcaiji')->bobocanshu10 = $_POST['bobocanshu10'];
    $zbp->SaveConfig('fangcaiji');
    $zbp->SetHint('good');
    Redirect('./main.php');
}

?>


<div id="divMain">
  <div class="divHeader">仿采集插件</div>
  <div class="SubMenu">
  </div>
  <div id="divMain2">
      <p>
      我曾经用火车头采集器采集过上百万的数据，我更加了解采集的逻辑和防止采集的方法
      ，
      在文章底部随机显示10种随机版权更加安全，
      火车头是根据标签的结束和开始进行识别内容采集的，如果在文章底部随机显示多种版权文字，采集者就需要使用火车头的替换功能一条一条进行配置，第一这样增加了采集者的时间成本；第二采集者是不知道您有多少条版权随机的，从而采集走您的版权，渐渐的给您无形中做了外链，百度抓取到他的页面的时候，也可以根据版权信息的文字识别出文章并非采集者原创的，从而不会导致权重的丢失！！！
      <br/>
      作者qq571025067
      </p>
<form id="form1" name="form1" method="post">
    <table width="100%" style='padding:0px;margin:0px;' cellspacing='0' cellpadding='0' class="tableBorder">
  <tr>
    <td><b><label><p align="center">第1条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu1" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu1;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第2条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu2" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu2;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第3条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu3" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu3;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第4条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu4" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu4;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第5条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu5" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu5;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第6条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu6" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu6;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第7条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu7" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu7;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第8条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu8" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu8;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第9条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu9" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu9;?></textarea></p></td>
  </tr>
  <tr>
    <td><b><label><p align="center">第10条随机版权</p></label></b></td>
    <td><p align="left"><textarea rows="1" name="bobocanshu10" type="text" id="bobocanshu" style="width: 80%;padding : 5px;" ><?php echo $zbp->Config('fangcaiji')->bobocanshu10;?></textarea></p></td>
  </tr>
</table>
 <br />
   <input name="" type="Submit" class="button" value="保存"/>
    </form>
  </div>
</div>



<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>