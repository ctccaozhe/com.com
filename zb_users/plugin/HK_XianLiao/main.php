<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('HK_XianLiao')) {$zbp->ShowError(48);die();}


$set_zbp=false;
if(isset($_POST['WebId']))
{
    $zbp->Config('HK_XianLiao')->WebId=$_POST['WebId'];
    $set_zbp=true;
}
if(isset($_POST['SSO_KEY']))
{
    $zbp->Config('HK_XianLiao')->SSO_KEY=$_POST['SSO_KEY'];
    $set_zbp=true;
}
if($set_zbp)
{
    $zbp->SaveConfig('HK_XianLiao');
    $zbp->SetHint('good');
}

$blogtitle='闲聊么设置';
require $blogpath . 'zb_system/admin/admin_header.php';
require $blogpath . 'zb_system/admin/admin_top.php';
?>
    <div id="divMain">
        <div class="divHeader"><?php echo $blogtitle;?></div>
        <div class="SubMenu">
        </div>
        <div id="divMain2">
            <form method="post" action="#">
                <table width="100%" border="1" width="100%" class="tableBorder">
                    <tr>
                        <td colspan="2" style="color: red;">需要先去闲聊么官网注册一个账号，获取网站ID以及SSO_KEY (请勿泄露给第三方)，官网：https://www.xianliao.me/</td>
                    </tr>
                    <tr>
                        <td width="300px">网站ID</td>
                        <td><input style="width:300px;" type="text" placeholder="网站ID" name="WebId" value="<?php echo $zbp->Config('HK_XianLiao')->WebId?$zbp->Config('HK_XianLiao')->WebId:"";?>"/></td>
                    </tr>
                    <tr>
                        <td>SSO_KEY</td>
                        <td><input style="width:300px;" type="text" placeholder="SSO_KEY" name="SSO_KEY" value="<?php echo $zbp->Config('HK_XianLiao')->SSO_KEY?$zbp->Config('HK_XianLiao')->SSO_KEY:"";?>"/></td>
                    </tr>
                </table>
                <input type="submit" value="保存">
            </form>
        </div>
    </div>

<?php
require $blogpath . 'zb_system/admin/admin_footer.php';
RunTime();
?>