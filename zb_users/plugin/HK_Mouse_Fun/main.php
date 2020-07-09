<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action='root';
if (!$zbp->CheckRights($action)) {$zbp->ShowError(6);die();}
if (!$zbp->CheckPlugin('HK_Mouse_Fun')) {$zbp->ShowError(48);die();}

$set_zbp=false;
if(isset($_POST['Mouse_txt']))
{
    $zbp->Config('HK_Mouse_Fun')->Mouse_txt=$_POST['Mouse_txt'];
    $set_zbp=true;
}
if(isset($_POST['Mouse_Select']))
{
    $zbp->Config('HK_Mouse_Fun')->Mouse_Select=$_POST['Mouse_Select'];
    $set_zbp=true;
}
if($set_zbp)
{
    $zbp->SaveConfig('HK_Mouse_Fun');
    $zbp->SetHint('good');
}

$blogtitle='鼠标特效设置';
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
                        <td>特效选择</td>
                        <td><select name="Mouse_Select">
                                <option <?php if($zbp->Config('HK_Mouse_Fun')->Mouse_Select=="文字特效"){echo 'selected="selected"';}?>>文字特效</option>
                            </select></td>
                    </tr>
                    <tr>
                        <td width="300px">文字特效设置<br/>(用|隔开每个字,鼠标点击时就会出现这些字)</td>
                        <td><input style="width:100%;" type="text" placeholder="我很帅|我很酷|我很美|不装逼|少年你来对地方了|让老纳收了你" name="Mouse_txt" value="<?php echo $zbp->Config('HK_Mouse_Fun')->Mouse_txt?$zbp->Config('HK_Mouse_Fun')->Mouse_txt:"";?>"/></td>
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