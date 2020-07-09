<?php
/**
* 多主题插件
*
* MultiThemes for Z-BlogPHP
*
* @author  心扬 <chrishyze@163.com>
*/

// 注册插件
RegisterPlugin('MultiThemes', 'ActivePlugin_MultiThemes');

/**
 * 插件激活事务
 * 一般用于挂载系统钩子
 */
function ActivePlugin_MultiThemes()
{
    global $zbp;

    /*
    * 检查冲突
    * 若冲突的插件已启用，则终止插件
    * 目前已知冲突的插件(ID)有：2themes,Pad，wthemes
    */
    if ($zbp->CheckPlugin('2themes') || $zbp->CheckPlugin('Pad') || $zbp->CheckPlugin('wthemes')) {
        return;
    }

    // ZBP类加载前钩子
    Add_Filter_Plugin('Filter_Plugin_Zbp_Load_Pre', 'ZbpLoadPre_MultiThemes');

    // 管理后台右上方菜单钩子
    Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu', 'TopMenu_MultiThemes');
}

/**
 * 在ZBP类加载前重置主题
 */
function ZbpLoadPre_MultiThemes()
{
    // 判断是否为前台
    if (false === stripos($GLOBALS['currenturl'], '/zb_')) {
        global $zbp;
        $clients = $zbp->Config('MultiThemes')->clients; // 获取客户端主题配置
        $config  = $zbp->Config('MultiThemes')->config;  // 获取插件配置

        if (is_numeric($multithemeId = GetVars('mtid', 'GET'))) { // 优先检测URL
            // 检测主题id值合法性
            if (!array_key_exists($multithemeId, $clients)) {
                $multithemeId = 1;
            } else {
                unset($_COOKIE['mtid']);
                setcookie('mtid', $multithemeId, time() + 60 * 60 * 24 * 30); // 写入COOKIE，默认保存一个月
            }
        } elseif ($multithemeId = GetVars('mtid', 'COOKIE')) { // 检测COOKIES
            if (!is_numeric($multithemeId) || !array_key_exists($multithemeId, $clients)) {
                $multithemeId = 1;
            }
        } elseif ($config[0]) { // 检测USER_AGENT
            $multithemeId = ClientDetector_MultiThemes($config[1], $config[2]);
            setcookie('mtid', $multithemeId, time() + 60 * 60 * 24 * 30);
        } else {
            $multithemeId = 1; // 默认PC
        }

        // 检查动态主题是否与系统默认主题相同
        if ($clients[$multithemeId][0] !== $zbp->Config('system')->ZC_BLOG_THEME) {
            // 重置主题
            $zbp->theme = $clients[$multithemeId][0];
            $zbp->style = $clients[$multithemeId][1];
            //$zbp->option['ZC_BLOG_THEME'] = $clients[$multithemeId][0];
            //$zbp->option['ZC_BLOG_CSS']   = $clients[$multithemeId][1];
            //$GLOBALS['blogtheme'] = $clients[$multithemeId][0];
            //$GLOBALS['blogstyle'] = $clients[$multithemeId][1];

            // 取消激活默认加载的主题
            // 数组第一个元素必是主题
            array_shift($GLOBALS['activedapps']);

            // 动态加载主题
            if (ZBP_SAFEMODE === false) {
                if (is_readable($fileBase = $GLOBALS['usersdir'].'theme/'.$zbp->theme.'/theme.xml')) {
                    $GLOBALS['activedapps'][] = $zbp->theme;
                }

                if (is_readable($fileBase = $GLOBALS['usersdir'].'theme/'.$zbp->theme.'/include.php')) {
                    include $fileBase;

                    //执行主题自定义函数
                    $funcName = $GLOBALS['plugins'][$zbp->theme];

                    if (function_exists($funcName)) {
                        $funcName();
                    }
                }
            }
        }
    }
}

/**
 * 客户端检测
 *
 * @param int $detectAndroid 是否检测安卓系统
 * @param int $detectIos     是否检测iOS系统
 *
 * @return int $clientId
 */
function ClientDetector_MultiThemes($detectAndroid, $detectIos)
{
    // Composer Autoload
    require_once __DIR__.'/vendor/autoload.php';
    $detect = new Mobile_Detect();

    $clientId = 1; // 默认PC

    if ($detect->isTablet()) {
        $clientId = 3;
    }
    if ($detect->isMobile()) {
        $clientId = 2;
    }
    if ($detectAndroid && $detect->isAndroidOS()) {
        $clientId = 4;
    }
    if ($detectIos && $detect->isiOS()) {
        $clientId = 5;
    }

    return $clientId;
}

/**
 * 管理页面右上方添加设置按钮
 *
 * @param array $topmenus 后台顶部菜单数组
 */
function TopMenu_MultiThemes(&$topmenus)
{
    global $zbp;
    $topmenus[] = MakeTopMenu('root', '多主题设置', $zbp->host.'zb_users/plugin/MultiThemes/main.php#tabs=mt_pc', '', '');
}

/**
 * 插件安装激活时执行函数
 */
function InstallPlugin_MultiThemes()
{
    global $zbp;

    $activedTheme = $zbp->Config('system')->ZC_BLOG_THEME;
    $activedStyle = $zbp->Config('system')->ZC_BLOG_CSS;

    // 初始化配置
    $zbp->Config('MultiThemes')->clients = array( //客户端主题配置
        1 => array($activedTheme, $activedStyle), //桌面pc （为了和主题名相匹配，数组键从1开始）
        2 => array('default', 'default'), //手机mobi
        3 => array('default', 'default'), //平板tablet
        4 => array('default', 'default'), //安卓Android
        5 => array('default', 'default'),  //苹果iOS
    );

    $zbp->Config('MultiThemes')->config = array( //插件配置
        0 => 1, //是否启用客户端UserAgent检测，默认为1启用(0关闭)
        1 => 1, //是否启用安卓Android客户端检测，默认为1启用(0关闭)
        2 => 1,  //是否启用苹果iOS客户端检测，默认为1启用(0关闭)
    );

    $zbp->Config('MultiThemes')->tabname = array( //配置页面tab名称
        0 => '主题一',
        1 => '主题二',
        2 => '主题三',
        3 => '主题四',
        4 => '主题五',
    );
    //保存配置
    $zbp->SaveConfig('MultiThemes');
}

/**
 * 插件卸载时执行函数
 */
function UninstallPlugin_MultiThemes()
{
    global $zbp;

    // 删除配置
    if ($zbp->HasConfig('MultiThemes')) {
        $zbp->DelConfig('MultiThemes');
    }
}
