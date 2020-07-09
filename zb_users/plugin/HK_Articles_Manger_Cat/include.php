<?php
#注册插件
RegisterPlugin("HK_Articles_Manger_Cat","ActivePlugin_HK_Articles_Manger_Cat");

function ActivePlugin_HK_Articles_Manger_Cat() {
    Add_Filter_Plugin("Filter_Plugin_Admin_ArticleMng_SubMenu","HK_Articles_Manger_Cat_Set_SubMenu");
}
function InstallPlugin_HK_Articles_Manger_Cat() {}
function UninstallPlugin_HK_Articles_Manger_Cat() {}

function HK_Articles_Manger_Cat_Set_SubMenu()
{
    global $zbp;
    echo '<a href="'. $zbp->host .'zb_system/admin/index.php?act=ArticleMng"><span class="m-left m-now">系统文章管理</span></a>';
    echo '<a href="'. $zbp->host .'zb_users/plugin/HK_Articles_Manger_Cat/main.php"><span class="m-left">插件文章管理</span></a>';
}