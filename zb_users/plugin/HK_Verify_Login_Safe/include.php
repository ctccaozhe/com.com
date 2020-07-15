<?php
#注册插件
RegisterPlugin("HK_Verify_Login_Safe","ActivePlugin_HK_Verify_Login_Safe");

function ActivePlugin_HK_Verify_Login_Safe() {
    //JS插入
    Add_Filter_Plugin("Filter_Plugin_Login_Header","HK_Verify_Login_Safe_Set_Header_js");
    //验证
    Add_Filter_Plugin("Filter_Plugin_Cmd_Begin","HK_Verify_Login_Safe_Verify_Code");
}
function InstallPlugin_HK_Verify_Login_Safe() {}
function UninstallPlugin_HK_Verify_Login_Safe() {}

function HK_Verify_Login_Safe_Set_Header_js()
{
    global $zbp;
    ?>
    <script type='text/javascript'>
        $(function() {
            $(".password").after('<dd class="hkverify"><label for="hkverify">验证码：</label><input type="text" id="hkverify" name="hkverify" size="20" tabindex="2" /></dd><img src="<?php echo $zbp->host;?>zb_system/script/c_validcode.php?id=HK_Verify_Login_Safe" onClick="this.src=this.src+\'&amp;tm=\'+Math.random()"/>');
        });
    </script>
    <?php

}
function HK_Verify_Login_Safe_Verify_Code()
{
    $action = GetVars('act', 'GET');
    if($action=="verify")
    {
        global $zbp;
        if(!$zbp->CheckValidCode(GetVars('hkverify','POST'),'HK_Verify_Login_Safe')){
            $zbp->ShowError('验证码错误，请重新输入');
            die();
        }
    }


}