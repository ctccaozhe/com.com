<?php
/**
* 多主题插件 AJAX 配置
*
* MultiThemes for Z-BlogPHP
*
* @author  心扬 <chrishyze@163.com>
*/

//系统初始化
require_once __DIR__.'/../../../zb_system/function/c_system_base.php';
//后台初始化
require_once __DIR__.'/../../../zb_system/function/c_system_admin.php';
//加载系统
$zbp->Load();

// 声明HTTP资源类型为JSON
if (!headers_sent()) {
    header('Content-Type: application/json;charset=utf-8');
}

// 验证 CSRF Token
if (!GetVars('csrfToken') || !$zbp->VerifyCSRFToken(GetVars('csrfToken'), 'MultiThemes')) {
    echo jsonResponse(false, '非法访问!');
    die();
}

// 检测权限
if (!$zbp->CheckRights('root')) {
    echo jsonResponse(false, '没有访问权限!');
    die();
}
// 检测主题/插件启用状态
if (!$zbp->CheckPlugin('MultiThemes')) {
    echo jsonResponse(false, '插件未启用!');
    die();
}

// 配置项
$clients = $zbp->Config('MultiThemes')->clients;
$action = GetVars('action', 'GET');

if ('get' === $action) { //获取主题
    $clientId   = GetVars('cid', 'GET');
    $themesInfo = array();
    $allthemes = $zbp->LoadThemes();

    foreach ($allthemes as $key => $theme) {
        $themesInfo[$key]['id']   = $theme->id;
        $themesInfo[$key]['name'] = urlencode($theme->name); //主题名

        $app = new App();
        $app->LoadInfoByXml('theme', $theme->id);
        $themesInfo[$key]['img']  = urlencode($app->GetScreenshot()); //截屏

        foreach ($app->GetCssFiles() as $k => $v) { //css
            $themesInfo[$key]['css'][] = $k;
        }

        if ($app->id === $clients[$clientId][0]) {
            $themesInfo[$key]['actived'] = 1;
            $themesInfo[$key]['style']   = $clients[$clientId][1];
        } else {
            $themesInfo[$key]['actived'] = 0;
            $themesInfo[$key]['style']   = '';
        }
    }

    echo urldecode(json_encode($themesInfo));
} elseif ('set' === $action) { //设置主题
    $clientId              = GetVars('cid', 'GET');
    $clients[$clientId][0] = GetVars('theme', 'GET');
    $clients[$clientId][1] = GetVars('style', 'GET');

    $zbp->Config('MultiThemes')->clients = $clients;
    $zbp->SaveConfig('MultiThemes');

    $result = BuildTemplate_MuitiTheme($clients[$clientId][0], $clients[$clientId][1]);

    if ($result) {
        echo jsonResponse(true, '主题设置成功！');
    } else {
        echo jsonResponse(false, '主题编译失败!');
    }
} elseif ('config' === $action) { //插件配置
    $fields = json_decode(GetVars('fields', 'GET'));
    $config = $zbp->Config('MultiThemes')->config;
    if ($fields->thismode) {
        $zbp->Config('MultiThemes')->config = array(
            $fields->mode, //是否启用客户端UserAgent检测
            isset($fields->dand) ? true : false, //是否启用安卓Android客户端检测
            isset($fields->dios) ? true : false,  //是否启用苹果iOS客户端检测
        );
    } else {
        $zbp->Config('MultiThemes')->tabname = array(
            $fields->tabname0,
            $fields->tabname1,
            $fields->tabname2,
            $fields->tabname3,
            $fields->tabname4,
        );

        $zbp->Config('MultiThemes')->config = array(
            $fields->mode, //是否启用客户端UserAgent检测
            $config[1], //不用设置
            $config[2],  //不用设置
        );
    }

    if ($zbp->SaveConfig('MultiThemes')) {
        echo jsonResponse(true, '设置保存成功！');
    } else {
        echo jsonResponse(true, '设置保存失败！');
    }
}

/**
 * 编译模板
 *
 * @param string $theme
 *
 * @return bool
 */
function BuildTemplate_MuitiTheme($theme)
{
    global $zbp;

    if (file_exists($zbp->usersdir.'cache/compiled/'.$theme.'/')) {
        return true;
    }

    $zbp->template = $zbp->PrepareTemplate($theme);
    $zbp->BuildTemplate();

    if (file_exists($zbp->template->GetPath())) {
        return true;
    }

    return false;
}

/**
 * JSON返回
 * 避免中文转码 Unicode
 * 兼容 PHP5.3～PHP7.
 *
 * @param bool   $status  状态
 * @param string $message 消息
 *
 * @return string
 */
function jsonResponse($status, $message)
{
    $message = htmlspecialchars($message);
    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        $str = json_encode(array($status, $message));

        return preg_replace_callback(
            '#\\\u([0-9a-f]{4})#i',
            function ($matchs) {
                return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
            },
            $str
        );
    }

    return json_encode(array($status, $message), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
}
