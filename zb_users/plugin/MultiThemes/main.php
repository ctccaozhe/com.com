<?php
/**
* 多主题插件配置页
*
* MultiThemes for Z-BlogPHP
*
* @author  心扬 <chrishyze@163.com>
*/

// 系统初始化
require_once __DIR__.'/../../../zb_system/function/c_system_base.php';
// 后台初始化
require_once __DIR__.'/../../../zb_system/function/c_system_admin.php';
// 加载系统
$zbp->Load();

// 检测权限
if (!$zbp->CheckRights('root')) {
    json_msg(false, '没有访问权限!');
    die();
}
// 检测主题/插件启用状态
if (!$zbp->CheckPlugin('MultiThemes')) {
    json_msg(false, '插件未启用!');
    die();
}

// 后台<head>
require_once __DIR__.'/../../../zb_system/admin/admin_header.php';
// 后台顶部
require_once __DIR__.'/../../../zb_system/admin/admin_top.php';

// 获取客户端主题配置
$clients = $zbp->Config('MultiThemes')->clients;
// 获取插件配置
$config  = $zbp->Config('MultiThemes')->config;

$tabname = array();
if ($config[0]) { // 插件模式：设备检测模式
    $tabname = array(
        '<i class="mt-icon mt-icon-monitor"></i>PC桌面',
        '<i class="mt-icon mt-icon-phone"></i>手机',
        '<i class="mt-icon mt-icon-tablet"></i>平板',
        '<i class="mt-icon mt-icon-android"></i>安卓Android',
        '<i class="mt-icon mt-icon-ios"></i>苹果iOS',
    );
} else { // 插件模式：纯主题模式
    $tabname = $zbp->Config('MultiThemes')->tabname; // 获取tab名称
}
?>

<style>
    @import url('<?php echo $zbp->host; ?>zb_users/plugin/MultiThemes/node_modules/layui-src/dist/css/layui.css');
    @import url('<?php echo $zbp->host; ?>zb_users/plugin/MultiThemes/css/admin-main.css');
</style>

<div id="divMain">
    <div class="layui-tab" lay-filter="tabs">
        <div class="multitheme-logo"></div>
        <ul class="layui-tab-title" style="height:auto">
            <li class="layui-this" client-id="1" lay-id="mt_pc"><?php echo $tabname[0]; ?></li>
            <li client-id="2" lay-id="mt_mobi"><?php echo $tabname[1]; ?></li>
            <li client-id="3" lay-id="mt_tablet"><?php echo $tabname[2]; ?></li>
            <?php if ($config[1] || !$config[0]) : ?>
            <li client-id="4" lay-id="mt_android"><?php echo $tabname[3]; ?></li>
            <?php endif; if ($config[2] || !$config[0]) : ?>
            <li client-id="5" lay-id="mt_ios"><?php echo $tabname[4]; ?></li>
            <?php endif; ?>
            <li client-id="8" lay-id="mt_setting"><i class="mt-icon mt-icon-setting"></i>设置</li>
            <li client-id="9" lay-id="mt_help"><i class="mt-icon mt-icon-help"></i>帮助说明</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item layui-show" id="mt_pc"></div>
            <div class="layui-tab-item" id="mt_mobi"></div>
            <div class="layui-tab-item" id="mt_tablet"></div>
            <?php if ($config[1] || !$config[0]) : ?>
            <div class="layui-tab-item" id="mt_android"></div>
            <?php endif; if ($config[2] || !$config[0]) : ?>
            <div class="layui-tab-item" id="mt_ios"></div>
            <?php endif; ?>
            <div class="layui-tab-item" id="mt_setting">
                <form class="layui-form" action="">
                    <div class="layui-form-item">
                        <label class="layui-form-label">模式选择</label>
                        <div class="layui-input-block">
                            <input type="radio"  lay-filter="mode1" name="mode" value="1" title="设备检测模式" <?php echo ($config[0]) ? 'checked' : ''; ?>>
                            <input type="radio"  lay-filter="mode0" name="mode" value="0" title="纯主题模式" <?php echo (!$config[0]) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                    <?php if ($config[0]) : ?>
                    <input type="hidden" name="thismode" value="1">
                    <div class="layui-form-item">
                        <label class="layui-form-label">安卓Android系统检测</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="dand" lay-skin="switch" lay-text="开启|关闭" <?php echo ($config[1]) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">苹果iOS系统检测</label>
                        <div class="layui-input-block">
                            <input type="checkbox" name="dios" lay-skin="switch" lay-text="开启|关闭" <?php echo ($config[2]) ? 'checked' : ''; ?>>
                        </div>
                    </div>
                    <?php else : ?>
                    <input type="hidden" name="thismode" value="0">
                    <div class="layui-form-item">
                        <label class="layui-form-label">多主题选项卡名称</label>
                        <div class="layui-input-inline">
                            <input type="text" name="tabname0" lay-verify="pass" placeholder="主题一" autocomplete="off" class="layui-input" value="<?php echo $tabname[0]; ?>">
                        </div>
                        <div class="layui-form-mid layui-word-aux">主题一</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"> </label>
                        <div class="layui-input-inline">
                            <input type="text" name="tabname1" lay-verify="pass" placeholder="主题二" autocomplete="off" class="layui-input" value="<?php echo $tabname[1]; ?>">
                        </div>
                        <div class="layui-form-mid layui-word-aux">主题二</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"> </label>
                        <div class="layui-input-inline">
                            <input type="text" name="tabname2" lay-verify="pass" placeholder="主题三" autocomplete="off" class="layui-input" value="<?php echo $tabname[2]; ?>">
                        </div>
                        <div class="layui-form-mid layui-word-aux">主题三</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"> </label>
                        <div class="layui-input-inline">
                            <input type="text" name="tabname3" lay-verify="pass" placeholder="主题四" autocomplete="off" class="layui-input" value="<?php echo $tabname[3]; ?>">
                        </div>
                        <div class="layui-form-mid layui-word-aux">主题四</div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"> </label>
                        <div class="layui-input-inline">
                            <input type="text" name="tabname4" lay-verify="pass" placeholder="主题五" autocomplete="off" class="layui-input" value="<?php echo $tabname[4]; ?>">
                        </div>
                        <div class="layui-form-mid layui-word-aux">主题五</div>
                    </div>
                    <?php endif;?>
                    <div class="layui-form-item">
                        <div class="layui-input-block">
                            <button class="layui-btn layui-btn-normal" lay-submit lay-filter="formSubmit">保存配置</button>
                        </div>
                    </div>
                    <blockquote class="layui-elem-quote layui-quote-nm">* 模式选择请参看帮助说明。<br>* 系统检测开关不影响纯主题模式。</blockquote>
                </form>
            </div>
            <div class="layui-tab-item" id="mt_help">
                <fieldset class="layui-elem-field">
                    <legend>简介</legend>
                    <div class="layui-field-box">
                        <p>多主题插件是一款前台插件，可以根据用户所使用的客户端自动切换到对应的主题，实现类似“手机版网站”的效果；也可以实现一个网站多个主题的效果。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>基本使用</legend>
                    <div class="layui-field-box">
                        <p>点开所要设置的客户端/主题选项卡，在主题列表中点击启用想要启用的主题即可。</p>
                        <p>对于已经启用的主题，可以更换样式。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>模式选择</legend>
                    <div class="layui-field-box">
                        <p>多主题有两种模式：</p>
                        <p>1. 设备检测模式（默认模式）。即打开设备检测功能，可以根据设备切换到对应的主题。</p>
                        <p>2. 纯主题模式。关闭设备检测功能（简单来说就是手机访问不再自动转到手机对应的主题，以此类推），用于纯粹的主题手动切换，例如，可以给网站分别设置桌面PC、移动手机、WebApp、微信端等不同主题。具体请看下文“前台手动切换主题”。</p>
                        <p>两种模式都遵循插件逻辑，请参看下文“插件逻辑”。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>客户端类型</legend>
                    <div class="layui-field-box">
                        <p>设备检测模式下，多主题插件将客户端分为五类：桌面（大屏）、手机（小屏）、平板（中屏）、安卓Android、苹果iOS，后面两类可以选择性关闭。</p>
                        <table class="layui-table">
                            <thead>
                                <tr>
                                    <th>客户端</th>
                                    <th>对应主题(纯主题模式)</th>
                                    <th>识别码</th>
                                    <th>优先级</th>
                                    <th>对应的操作系统或设备(设备检测模式)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>桌面（默认）</td>
                                    <td>主题一（默认）</td>
                                    <td>1</td>
                                    <td>最低</td>
                                    <td>搭载Windows、macOS、Linux等系统的PC电脑、笔记本设备</td>
                                </tr>
                                <tr>
                                    <td>手机</td>
                                    <td>主题二</td>
                                    <td>2</td>
                                    <td>中等</td>
                                    <td>搭载Android、iOS、Windows等系统的移动手机设备</td>
                                </tr>
                                <tr>
                                    <td>平板</td>
                                    <td>主题三</td>
                                    <td>3</td>
                                    <td>中等</td>
                                    <td>搭载Android、iOS、Windows等系统的平板设备</td>
                                </tr>
                                <tr>
                                    <td>安卓Android</td>
                                    <td>主题四</td>
                                    <td>4</td>
                                    <td>最高</td>
                                    <td>搭载安卓系统的任何设备</td>
                                </tr>
                                <tr>
                                    <td>苹果iOS</td>
                                    <td>主题五</td>
                                    <td>5</td>
                                    <td>最高</td>
                                    <td>搭载iOS系统的任何设备</td>
                                </tr>
                            </tbody>
                        </table>
                        <p>* 本插件主要通过 User Agent 来判断客户端类型。</p>
                        <p>* 本插件已支持市面上绝大多数设备和系统，还有部分操作系统未列出。</p>
                        <p>* 高优先级的会将低优先级的覆盖，例如iPad，既是平板，又是iOS系统，但由于系统判断优先级最高，故最终该设备被标记为iOS系统，而不是平板（前提是开启了iOS系统检测）。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>前台手动切换主题</legend>
                    <div class="layui-field-box">
                        <p>在前台任意页面可以通过 URL 参数<span style="color:#FF0000"> mtid </span>主动切换到对应ID的客户端主题。客户端ID请参照上面表格中的识别码。</p>
                        <p>示例：</p>
                        <p>http(s)://example.com/?<span style="color:#FF0000">mtid=0</span>（桌面）</p>
                        <p>http(s)://example.com/?<span style="color:#FF0000">mtid=1</span>（手机）</p>
                        <p>http(s)://example.com/?<span style="color:#FF0000">mtid=2</span>（平板）</p>
                        <p>http(s)://example.com/?id=18&<span style="color:#FF0000">mtid=2</span>（在文章页面切换）</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>插件逻辑</legend>
                    <div class="layui-field-box">
                        <p>插件优先检测 URL 的 mtid 参数，如果参数符合，则自动切换到参数指定的客户端主题，同时设置名为 mtid 的 cookie（有效期为一个月），将当前用户标记为对应客户端。</p>
                        <p>如果不存在 URL 参数，则检测用户的 cookie ，根据 cookie 来确定客户端。</p>
                        <p>如果 URL 参数和 cookie 都不存在，则检测判断用户的客户端类型，并切换主题，同时用 cookie 标记。</p>
                        <p>如果以上条件都不符合，则默认为PC桌面端。</p>
                        <p>因此，一旦用户访问过网站且启用了 cookie ，用户就被标记为对应的客户端，即使用户使用的是手机，在主动切换为桌面PC端后，就会一直是PC端，除非通过 URL 手动切换或者清除 cookie。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>关于</legend>
                    <div class="layui-field-box">
                        <p style="text-align:center"><img src="<?php echo $zbp->host; ?>zb_users/plugin/MultiThemes/logo.png" alt="多主题logo"></p>
                        <p style="text-align:center">多主题 (MultiThemes) v<?php $app = new App;
                        $app->LoadInfoByXml('plugin', 'MultiThemes');
                        echo $app->version; ?></p>
                        <p>插件作者：心扬</p>
                        <p>联系方式：chrishyze@163.com</p>
                        <p>界面及Logo设计：Argis沫</p>
                        <p>开源库：<a href="http://mobiledetect.net" target="_blank">Mobile Detect</a> (<a href="https://github.com/serbanghita/Mobile-Detect/blob/master/LICENSE.txt" target="_blank">MIT License</a>)</p>
                    </div>
                </fieldset>
            </div>
        </div>
    </div>
</div>

<script>
var MULTITHEME = {
    HOME_URL: '<?php echo $zbp->host; ?>',
    CSRF_TOKEN: '<?php echo $zbp->GetCSRFToken('MultiThemes'); ?>',
    FUNCTIONS: {}
};
</script>
<script src="<?php echo $zbp->host; ?>zb_users/plugin/MultiThemes/node_modules/layui-src/dist/layui.js"></script>
<script src="<?php echo $zbp->host; ?>zb_users/plugin/MultiThemes/js/admin-main.js"></script>

<?php
// 后台页面尾部
require_once __DIR__.'/../../../zb_system/admin/admin_footer.php';
// 系统运行信息
RunTime();
