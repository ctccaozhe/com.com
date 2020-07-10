<?php
#注册插件
RegisterPlugin("SaveRemoteImage","ActivePlugin_SaveRemoteImage");

function ActivePlugin_SaveRemoteImage() {
    Add_Filter_Plugin('Filter_Plugin_Post_Save', 'SaveRemoteImage');
}

function InstallPlugin_SaveRemoteImage() {
    global $zbp;
    if (!$zbp->Config('SaveRemoteImage')->filter){
        $url = parse_url($zbp->host);
        $zbp->Config('SaveRemoteImage')->filter = 'qiniu.' . $url['host'] . '|' . 'tva1.sinaimg.cn';
        $zbp->Config('SaveRemoteImage')->Save();
    }
}

function UninstallPlugin_SaveRemoteImage() {
    // $GLOBALS['zbp']->Config('SaveRemoteImage')->Delete();
}

function SaveRemoteImage($post){
    require_once('SaveRemoteImage.class.php');
    (new SaveRemoteImage($post))->Action();
}