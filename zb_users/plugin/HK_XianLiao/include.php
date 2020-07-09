<?php
#注册插件
RegisterPlugin("HK_XianLiao","ActivePlugin_HK_XianLiao");

function ActivePlugin_HK_XianLiao() {
    Add_Filter_Plugin("Filter_Plugin_ViewPost_Template","HK_XianLiao_Set_Js");
    Add_Filter_Plugin("Filter_Plugin_ViewList_Template","HK_XianLiao_Set_Js");
    Add_Filter_Plugin("Filter_Plugin_ViewAuto_End","HK_XianLiao_Set_Js");
}
function InstallPlugin_HK_XianLiao() {}
function UninstallPlugin_HK_XianLiao() {}

function HK_XianLiao_Set_Js()
{
    global $zbp;
    if(is_numeric(trim($zbp->Config('HK_XianLiao')->WebId))&&trim($zbp->Config('HK_XianLiao')->WebId)>0&&trim($zbp->Config('HK_XianLiao')->SSO_KEY)!="")
    {
        //如果不Load就没有办法获取$zbp->User的值
        $zbp->Load();
        $web_id=trim($zbp->Config('HK_XianLiao')->WebId);
        $web_uid=$zbp->user->ID?$zbp->user->ID:0;
        $web_avatar=isset($zbp->user->Avatar)?$zbp->user->Avatar:$zbp->host."zb_users/avatar/0.png";
        $web_name=$zbp->user->Name?$zbp->user->Name:"";
        $web_time=time();
        $web_key=trim($zbp->Config('HK_XianLiao')->SSO_KEY);
        $web_hash=hash('sha512', $web_id.'_'.$web_uid.'_'.$web_time.'_'.$web_key);
        $zbp->footer.="<script>var xlm_wid='".$web_id."';var xlm_url='https://www.xianliao.me/';var xlm_uid='".$web_uid."';var xlm_name='".$web_name."';var xlm_avatar='".$web_avatar."';var xlm_time='".$web_time."';var xlm_hash='".$web_hash."';</script><script type='text/javascript' charset='UTF-8' src='https://www.xianliao.me/embed.js'></script>";
    }
}