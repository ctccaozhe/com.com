<?php
/**
 * 插件配置接口.
 *
 * @author 心扬 <chrishyze@163.com>
 */

//引入预处理与公共函数
require_once __DIR__.'/common.php';

//检测管理员权限
if (!$zbp->CheckRights('root')) {
    reject('没有访问权限!');
}

// 判断请求类型
if ('GET' === strtoupper($_SERVER['REQUEST_METHOD'])) {
    // 判断操作
    $act = GetVars('action', 'GET');
    if ('offnotify' === $act) {
        // 隐藏更新提示
        $config                          = json_decode($zbp->Config('Editormd')->plugin);
        $config->notify                  = 0;
        $zbp->Config('Editormd')->plugin = json_encode($config);
        $zbp->SaveConfig('Editormd');
    } elseif ('reset' === $act) {
        // 重置配置
        ResetConfig_Editormd(true);
        echo jsonResponse(array(true, '重置配置成功!'));
    }
} elseif ('POST' === strtoupper($_SERVER['REQUEST_METHOD'])) {
    $type = GetVars('type', 'POST'); // 配置类型
    // 整型配置
    $intTypeConfig = array(
        'preview',
        'autoheight',
        'scrolling',
        'dynamictheme',
        'emoji',
        'extras',
        'htmldecode',
        'tocm',
        'tasklist',
        'flowchart',
        'katex',
        'sdiagram',
        'notify',
        'keepconfig',
        'mipsupport',
        'keepmeta',
        'extstyle',
        'intro',
    );
    $config = json_decode($zbp->Config('Editormd')->$type, true);
    if ($config) {
        foreach ($config as $key => $value) {
            if (null !== $v = GetVars($key, 'POST')) {
                // 类型转换
                if (in_array($key, $intTypeConfig)) {
                    $config[$key] = (int) $v;
                } else {
                    $config[$key] = trim($v);
                }
            }
        }
    }
    $zbp->Config('Editormd')->$type = json_encode($config);
    $zbp->SaveConfig('Editormd');
    echo jsonResponse(array(true, '配置保存成功!'));
}
