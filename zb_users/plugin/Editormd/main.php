<?php
/**
 * Editor.md for Z-BlogPHP
 *
 * 插件配置页.
 *
 * @author 心扬 <chrishyze@163.com>
 */

//系统初始化
require_once __DIR__.'/../../../zb_system/function/c_system_base.php';
//后台初始化
require_once __DIR__.'/../../../zb_system/function/c_system_admin.php';
//加载系统
$zbp->Load();
//检测权限
if (!$zbp->CheckRights('root')) {
    $zbp->ShowError(6);
    die();
}
//检测主题/插件启用状态
if (!$zbp->CheckPlugin('Editormd')) {
    $zbp->ShowError(48);
    die();
}
//后台<head>
require_once __DIR__.'/../../../zb_system/admin/admin_header.php';
//后台顶部
require_once __DIR__.'/../../../zb_system/admin/admin_top.php';

// 配置项
$editor = json_decode($zbp->Config('Editormd')->editor);
$plugin = json_decode($zbp->Config('Editormd')->plugin);
?>

<style>
@import url('<?php echo $zbp->host; ?>zb_users/plugin/Editormd/css/editormd.min.css');
@import url('<?php echo $zbp->host; ?>zb_users/plugin/Editormd/lib/layui/css/layui.css');
@import url('<?php echo $zbp->host; ?>zb_users/plugin/Editormd/css/admin-main.css');
</style>
<div id="divMain">
    <div class="layui-tab layui-tab-brief" lay-filter="tabs">
        <div class="editormd-logo"></div>
        <ul class="layui-tab-title" style="height:auto">
            <li lay-id="editor">编辑器功能</li>
            <li lay-id="preview">编辑器预览</li>
            <li lay-id="plugin">插件设置</li>
            <li lay-id="help">帮助说明</li>
            <li lay-id="about">关于</li>
        </ul>
        <div class="layui-tab-content">
            <div class="layui-tab-item">
                <form action="" class="layui-form">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>基础功能</legend>
                        <div class="layui-field-box">
                            <div class="layui-form-item">
                                <label class="layui-form-label">动态主题</label>
                                <div class="layui-input-inline">
                                    <select name="dynamictheme" id="dynamictheme">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo $editor->dynamictheme ? 'selected' : ''; ?>>开启</option>
                                        <option value="0" <?php echo !$editor->dynamictheme ? 'selected' : ''; ?>>关闭</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="dynamic-tip">&#xe60b;</i></div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">emoji 表情</label>
                                <div class="layui-input-inline">
                                    <select name="emoji" id="emoji">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo $editor->emoji ? 'selected' : ''; ?>>开启</option>
                                        <option value="0" <?php echo !$editor->emoji ? 'selected' : ''; ?>>关闭</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="emoji-tip">&#xe60b;</i></div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">实时预览</label>
                                <div class="layui-input-inline">
                                    <select id="preview" name="preview">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo $editor->preview ? 'selected' : ''; ?>>开启</option>
                                        <option value="0" <?php echo !$editor->preview ? 'selected' : ''; ?>>关闭</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="pre-tip">&#xe60b;</i></div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">自动长高</label>
                                <div class="layui-input-inline">
                                    <select name="autoheight" id="autoheight">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo $editor->autoheight ? 'selected' : ''; ?>>开启</option>
                                        <option value="0" <?php echo !$editor->autoheight ? 'selected' : ''; ?>>关闭</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="ah-tip">&#xe60b;</i></div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">同步滚动</label>
                                <div class="layui-input-inline">
                                    <select name="scrolling" id="scrolling">
                                        <option value="">请选择</option>
                                        <option value="2" <?php echo 2 == $editor->scrolling ? 'selected' : ''; ?>>两边</option>
                                        <option value="1" <?php echo 1 == $editor->scrolling ? 'selected' : ''; ?>>单边</option>
                                        <option value="0" <?php echo !$editor->scrolling ? 'selected' : ''; ?>>禁用</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="scroll-tip">&#xe60b;</i></div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">摘要编辑器</label>
                                <div class="layui-input-inline">
                                    <select name="intro" id="intro">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo 1 == $editor->intro ? 'selected' : ''; ?>>显示</option>
                                        <option value="0" <?php echo !$editor->intro ? 'selected' : ''; ?>>不显示</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="intro-tip">&#xe60b;</i></div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>显示效果</legend>
                        <div class="layui-field-box">
                            <div class="layui-form-item">
                                <label class="layui-form-label">编辑页面样式</label>
                                <div class="layui-input-block" style="margin-left:15px">
                                    <textarea name="editorstyle" placeholder="自定义在启用扩展功能后的前台样式，如不需要可留空" rows="5" class="layui-textarea"><?php echo $editor->editorstyle; ?></textarea>
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>扩展功能</legend>
                        <div class="layui-field-box">
                            <div class="layui-form-item">
                                <label class="layui-form-label">HTML 解析</label>
                                <div class="layui-input-inline">
                                    <select id="htmldecode" name="htmldecode" lay-filter="htmldecode">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo 1 == $editor->htmldecode ? 'selected' : ''; ?>>开启，全部解析</option>
                                        <option value="2" <?php echo 2 == $editor->htmldecode ? 'selected' : ''; ?>>开启，过滤标签</option>
                                        <option value="0" <?php echo !$editor->htmldecode ? 'selected' : ''; ?>>关闭</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="hd-tip">&#xe60b;</i></div>
                            </div>

                            <div class="layui-form-item" id="htmlFilterRule" style="display:<?php echo 2 == $editor->htmldecode ? 'block' : 'none';?>">
                                <label class="layui-form-label">过滤标签</label>
                                <div class="layui-input-inline">
                                    <input type="text" name="htmlfilter" placeholder="" autocomplete="off" class="layui-input" value="<?php echo $editor->htmlfilter; ?>">
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="hf-tip">&#xe60b;</i> <i class="layui-icon" style="font-size:28px" id="hf-reset">&#xe669;</i></div>
                            </div>

                            <div class="layui-form-item">
                                <label class="layui-form-label">编辑器扩展</label>
                                <div class="layui-input-inline">
                                    <select id="extras" name="extras" lay-filter="extras">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo $editor->extras ? 'selected' : ''; ?>>开启</option>
                                        <option value="0" <?php echo !$editor->extras ? 'selected' : ''; ?>>关闭</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="ext-tip">&#xe60b;</i></div>
                            </div>

                            <div id="editormdExtras" style="display:<?php echo $editor->extras ? 'block' : 'none';?>">
                                <div class="layui-form-item">
                                    <label class="layui-form-label">ToC/ToCM 目录</label>
                                    <div class="layui-input-inline">
                                        <select id="tocm" name="tocm">
                                            <option value="">请选择</option>
                                            <option value="1" <?php echo $editor->tocm ? 'selected' : ''; ?>>开启</option>
                                            <option value="0" <?php echo !$editor->tocm ? 'selected' : ''; ?>>关闭</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">GFM 任务列表</label>
                                    <div class="layui-input-inline">
                                        <select id="tasklist" name="tasklist">
                                            <option value="">请选择</option>
                                            <option value="1" <?php echo $editor->tasklist ? 'selected' : ''; ?>>开启</option>
                                            <option value="0" <?php echo !$editor->tasklist ? 'selected' : ''; ?>>关闭</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">流程图</label>
                                    <div class="layui-input-inline">
                                        <select id="flowchart" name="flowchart">
                                            <option value="">请选择</option>
                                            <option value="1" <?php echo $editor->flowchart ? 'selected' : ''; ?>>开启</option>
                                            <option value="0" <?php echo !$editor->flowchart ? 'selected' : ''; ?>>关闭</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="layui-form-item">
                                    <label class="layui-form-label">Tex 科学公式</label>
                                    <div class="layui-input-inline">
                                        <select id="katex" name="katex">
                                            <option value="">请选择</option>
                                            <option value="1" <?php echo $editor->katex ? 'selected' : ''; ?>>开启</option>
                                            <option value="0" <?php echo !$editor->katex ? 'selected' : ''; ?>>关闭</option>
                                        </select>
                                    </div>
                                    <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="tex-tip">&#xe60b;</i></div>
                                </div>
                                <div class="layui-form-item">
                                    <label class="layui-form-label">时序图/序列图</label>
                                    <div class="layui-input-inline">
                                        <select id="sdiagram" name="sdiagram">
                                            <option value="">请选择</option>
                                            <option value="1" <?php echo $editor->sdiagram ? 'selected' : ''; ?>>开启</option>
                                            <option value="0" <?php echo !$editor->sdiagram ? 'selected' : ''; ?>>关闭</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="type" value="editor">
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <button class="layui-btn" lay-submit lay-filter="editor">立即提交</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="layui-tab-item">
                <form action="" class="theme">
                    <div id="theme-select">
                        <label for="editormd-theme-select">
                            工具栏：
                        </label>
                        <select id="editormd-theme-select" name="toolbartheme">
                            <option selected value="">选择工具栏主题</option>
                        </select>
                            &emsp;&emsp;
                        <label for="editor-area-theme-select">
                            编辑区域：
                        </label>
                        <select id="editor-area-theme-select" name="editortheme">
                            <option selected value="">选择编辑器主题</option>
                        </select>
                            &emsp;&emsp;
                        <label for="preview-area-theme-select">
                            预览区域：
                        </label>
                        <select id="preview-area-theme-select" name="previewtheme">
                            <option selected value="">选择实时预览主题</option>
                        </select>
                    </div>
                    <input type="hidden" name="type" value="editor">
                    <div class="layui-input-inline">
                        <button class="layui-btn" lay-submit lay-filter="themes">保存主题</button>
                        <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="theme-tip">&#xe60b;</i></div>
                    </div>
                    <div class="theme-tips">（如果显示不正常，刷新页面即可）</div>
                </form>

                <div style="width:100%">
                    <div id="test-editormd"></div>
                </div>
            </div>
            <div class="layui-tab-item">
                <form action="" class="layui-form">
                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>前台内容显示</legend>
                        <div class="layui-field-box">
                            <div class="layui-form-item">
                                <label class="layui-form-label">第三方MIP主题</label>
                                <div class="layui-input-inline">
                                    <select id="mipsupport" name="mipsupport">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo $plugin->mipsupport ? 'selected' : ''; ?>>开启兼容</option>
                                        <option value="0" <?php echo !$plugin->mipsupport ? 'selected' : ''; ?>>关闭兼容</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="mip-tip">&#xe60b;</i></div>
                            </div>
                            <?php if ($zbp->option['ZC_SYNTAXHIGHLIGHTER_ENABLE']) : ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label">前台代码块高亮</label>
                                <div class="layui-input-inline">
                                    <select id="editormd-codetheme-select" name="codetheme">
                                        <option value="">请选择</option>
                                        <option value="light_0" <?php echo ('light_0' == $plugin->codetheme) ? 'selected' : ''; ?>>明亮（不显示行数）</option>
                                        <option value="light_1" <?php echo ('light_1' == $plugin->codetheme) ? 'selected' : ''; ?>>明亮（显示行数）</option>
                                        <option value="dark_0" <?php echo ('dark_0' == $plugin->codetheme) ? 'selected' : ''; ?>>黑暗（不显示行数）</option>
                                        <option value="dark_1" <?php echo ('dark_1' == $plugin->codetheme) ? 'selected' : ''; ?>>黑暗（显示行数）</option>
                                        <option value="off" <?php echo ('off' == $plugin->codetheme) ? 'selected' : ''; ?>>关闭</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="ct-tip">&#xe60b;</i></div>
                            </div>
                            <?php else : ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label">前台代码高亮</label>
                                <div class="layui-input-inline">
                                    <div class="layui-form-mid layui-word-aux">“代码高亮”功能已关闭，请到“<a href="<?php echo $zbp->host; ?>zb_system/admin/index.php?act=SettingMng#tab2"> 网站设置 --> 全局设置 </a>”中开启</div>
                                </div>
                            </div>
                            <?php endif;?>
                            <?php if ($editor->extras) : ?>
                            <div class="layui-form-item">
                                <label class="layui-form-label">自带扩展样式</label>
                                <div class="layui-input-inline">
                                    <select id="extstyle" name="extstyle">
                                        <option value="">请选择</option>
                                        <option value="0" <?php echo !$plugin->extstyle ? 'selected' : ''; ?>>关闭</option>
                                        <option value="1" <?php echo $plugin->extstyle ? 'selected' : ''; ?>>启用</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="exts-tip">&#xe60b;</i></div>
                            </div>
                            <div class="layui-form-item">
                                <label class="layui-form-label">自定义扩展样式</label>
                                <div class="layui-input-block" style="margin-left:15px">
                                    <textarea name="cextstyle" placeholder="自定义在启用扩展功能后的前台样式，如不需要可留空" rows="5" class="layui-textarea"><?php echo $plugin->cextstyle; ?></textarea>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </fieldset>

                    <fieldset class="layui-elem-field layui-field-title">
                        <legend>其他</legend>
                        <div class="layui-field-box">
                            <div class="layui-form-item">
                                <label class="layui-form-label">保留配置</label>
                                <div class="layui-input-inline">
                                    <select id="keepconfig" name="keepconfig">
                                        <option value="">请选择</option>
                                        <option value="1" <?php echo $plugin->keepconfig ? 'selected' : ''; ?>>开启</option>
                                        <option value="0" <?php echo !$plugin->keepconfig ? 'selected' : ''; ?>>关闭</option>
                                    </select>
                                </div>
                                <div class="layui-form-mid layui-word-aux"><i class="layui-icon" style="font-size:30px" id="keep-tip">&#xe60b;</i></div>
                            </div>
                        </div>
                    </fieldset>
                    <input type="hidden" name="type" value="plugin">
                    <div class="layui-form-item">
                        <div class="layui-input-inline">
                            <button class="layui-btn" lay-submit lay-filter="plugin">立即提交</button>
                            <button class="layui-btn layui-btn-sm" style="background-color: #FF5722" lay-submit lay-filter="reset">重置配置</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="layui-tab-item">
                <fieldset class="layui-elem-field">
                    <legend>启动失败解决方案</legend>
                    <div class="layui-field-box">
                        <p>若出现启动失败的情况，或其他一些问题，可以先通过以下操作尝试解决：</p><br>
                        <p>● 方案一：在插件管理页面“停用”后再“启用”本插件。</p><br>
                        <p>如果问题仍未解决：</p>
                        <p>● 方案二：在插件管理页面删除本插件，再从应用中心重新安装。</p><br>
                        <p>如果问题仍未解决：</p>
                        <p>● 方案三：暂时将博客主题恢复为默认主题，排查是否与主题发生冲突。</p><br>
                        <p>如果问题仍未解决：</p>
                        <p>● 方案四：暂时将可能影响到后台编辑页面的插件停用，排查是否与插件发生冲突。</p><br>
                        <p>若以上操作后仍启动失败，请及时将问题反馈给开发者。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>调查问卷</legend>
                    <div class="layui-field-box">
                        <p>（建议填写）我们提供了一份调查问卷，希望您能抽空<a href="https://www.wjx.cn/jq/44377302.aspx" target="_blank" class="underline-link">点击此处填写</a>，帮助我们不断优化插件的功能和体验。</p>
                        <hr class="layui-bg-blue">
                        <p>（选填）v2.88 更新中，在启用扩展功能状态下，我们移除了原有的 HTML 文章内容，以缩小网页体积，并完全通过 Javascript 实时渲染 Markdown 源码来生成 HTML 文章内容。</p>
                        <p>由于百度蜘蛛等搜索引擎爬虫不会执行网页中的 Javascript 脚本，因此在启用扩展功能后，爬虫获取到的只是 Markdown 源码，有可能会对 SEO 效果产生一些影响。</p>
                        <p>如果您在平时<strong>启用了扩展功能</strong>，并关注网站的 SEO ，请在<strong>使用 Editormd 一段时间后</strong>，<a href="https://www.wjx.cn/jq/44382659.aspx" target="_blank" class="underline-link">点击完成这份问卷</a>，帮助我们完善 Editormd 功能。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>第三方 MIP 主题兼容说明</legend>
                    <div class="layui-field-box">
                        <p>插件默认支持 ZBLOG 官方 MIP 插件，如果使用了依赖 ZBLOG 官方 MIP 插件标准的主题，则可以关闭兼容，插件会自动适配。</p>
                        <p>如果使用不依赖 ZBLOG 官方 MIP 插件的第三方主题，请启用兼容。</p>
                        <p>如何辨别主题是否使用了 ZBLOG 官方 MIP 插件依赖？如果主题要求同时安装启用名为“MIP支持插件”（作者：zsx）的插件，则表示该主题依赖 ZBLOG 官方 MIP 插件。</p>
                        <hr class="layui-bg-red">
                        <p>注意：由于 MIP 的限制，在对应的 MIP 主题下，所有的编辑器扩展（包括ToC目录、GFM任务列表等）都不被支持，请知悉。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>HTML 解析说明</legend>
                    <div class="layui-field-box">
                        <p>HTML 解析功能可以解析 Markdown 原文中包含的 HTML 标签。</p>
                        <p>如果为“开启,全部解析”状态，则默认解析所有标签。</p>
                        <p>也可以选择“开启,全部解析”来过滤指定标签及属性的解析，提高安全性。</p>
                        <p>请参考：<a href="https://pandao.github.io/editor.md/examples/html-tags-decode.html" target="_blank" class="underline-link">https://pandao.github.io/editor.md/examples/html-tags-decode.html</a></p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>编辑器扩展功能说明</legend>
                    <div class="layui-field-box">
                        <p>编辑器扩展可以实现 ToC/ToCM 目录、Github Flavored Markdown(GFM) 任务列表、Tex 科学公式语言、流程图、时序图/序列图等功能。</p>
                        <p>开启编辑器扩展后，前台文章页面还将额外输出原始的 Markdown 内容，然后通过 Editor.md 的“HTML预览”这一 Javascript 组件将内容动态转化为 HTML 文档。该操作将一定程度地导前致台页面体积增大，拖慢网页的展现速度。</p>
                        <p>关闭编辑器扩展功能后，原有的相关扩展内容也将无法正常展示，开启后恢复正常。</p>
                        <hr class="layui-bg-red">
                        <h3>ToC/ToCM 目录</h3>
                        <p>使用示例：</p>
                        <p>在需要显示目录的地方插入“ [TOC] ”</p>
                        <p>在需要显示下拉菜单目录的地方插入“ [TOCM] ”</p>
                        <p>插件将自动检测文档中的标题并在顶部显示目录。</p>
                        <p>请参考：<a href="https://pandao.github.io/editor.md/examples/toc.html" target="_blank" class="underline-link">https://pandao.github.io/editor.md/examples/toc.html</a></p>
                        <hr class="layui-bg-blue">
                        <h3>GFM 任务列表</h3>
                        <p>使用示例：</p>
                        <p>- [] 待办列表1</p>
                        <p>- [x] 待办列表2</p>
                        <p>- [x] 待办列表3</p>
                        <p>  &emsp;  - [x] 待办子列表3-1</p>
                        <p>  &emsp;  - [x] 待办子列表3-2</p>
                        <p>其中[x]表示勾选。</p>
                        <p>请参考：<a href="https://pandao.github.io/editor.md/examples/task-lists.html" target="_blank" class="underline-link">https://pandao.github.io/editor.md/examples/task-lists.html</a></p>
                        <hr class="layui-bg-green">
                        <h3>Tex 科学公式语言 (TeX/LaTeX)</h3>
                        <p>支持通过 TeX/LaTeX 来展示各类科学、数学公式。</p>
                        <p>插件使用的是本地文件，而非 CloudFlare 的 CDN。</p>
                        <p>请参考：<a href="https://pandao.github.io/editor.md/examples/katex.html" target="_blank" class="underline-link">https://pandao.github.io/editor.md/examples/katex.html</a></p>
                        <hr class="layui-bg-orange">
                        <h3>流程图</h3>
                        <p>通过矢量绘图实现简单的流程图展示。</p>
                        <p>请参考：<a href="https://pandao.github.io/editor.md/examples/flowchart.html" target="_blank" class="underline-link">https://pandao.github.io/editor.md/examples/flowchart.html</a></p>
                        <hr class="layui-bg-gray">
                        <h3>时序图/序列图</h3>
                        <p>动态展现时序图/序列图。</p>
                        <p>请参考：<a href="https://pandao.github.io/editor.md/examples/sequence-diagram.html" target="_blank" class="underline-link">https://pandao.github.io/editor.md/examples/sequence-diagram.html</a></p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>Emoji 表情说明</legend>
                    <div class="layui-field-box">
                        <p>如果想在文章中使用 Font Awesome 图标，则需要同时开启“编辑器扩展”功能。如果只使用 Github Emoji 和 Twemoji ，则只需要单独开启 emoji 就能正常使用，无需再开启“编辑器扩展”（当然，开启也行）。</p>
                        <p>Github Emoji 表情图标和 Font Awesome 字体图标均使用本地文件，Twemoji 使用 maxcdn 的文件。</p>
                        <p>如果删除本插件，原有的 Github Emoji 和 Font Awesome 将无法展示，重新安装插件可恢复正常。</p>
                        <p>Emoji 在摘要当中无法正常显示，所以请手动编辑不含 Emoji 的摘要。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>粘贴图片上传说明</legend>
                    <div class="layui-field-box">
                        <p>本插件支持剪贴板图片粘贴上传，即通过截图工具（如QQ截图、Snipaste）截图并复制到剪贴板、或者通过键盘上的 PrintScrn 按键截图的图片，不支持从文件管理器复制粘贴（因为复制的只是路径，不是文件）。</p>
                        <p>复制好图片后，在编辑框内直接粘贴即可自动上传显示。</p>
                        <p>支持最新的Chrome（包括360极速浏览器等等）、Firefox、Opera、Edge浏览器，不支持IE。</p>
                        <p>感谢  <a href="https://www.zsxsoft.com/" target="_blank" class="underline-link">@zsx</a> 提供的代码。</p>
                    </div>
                </fieldset>

                <fieldset class="layui-elem-field">
                    <legend>摘要编辑器显示变动</legend>
                    <div class="layui-field-box">
                        <p>自 v2.88 Editormd 采用了新的摘要编辑器显示机制。</p>
                        <p>点击“摘要”两个字可以切换摘要编辑器的显示状态，此时摘要编辑器中内容为空，方便用户自定义摘要内容。</p>
                        <p>原先的“自动生成摘要”单独显示为“生成摘要”按钮，点击“生成摘要”按钮，将会提取正文中首条分隔符以上的内容将作为摘要，若不存在分隔符，则提取正文前 250 个字符作为摘要。</p>
                        <p>如果不对摘要内容进行任何改动，按照 Z-BlogPHP 的默认机制：若正文不存在分隔符，则摘要为空；若正文中存在分隔符，则自动将正文中首条分隔符以上的内容作为摘要内容。</p>
                        <p>总的来说，功能与之前没有差别，仅仅是显示上的差异。</p>
                    </div>
                </fieldset>
            </div>
            <div class="layui-tab-item">
                <div class="copyright">
                    <p><img src="<?php echo $zbp->host; ?>zb_users/plugin/Editormd/logo.png" alt="logo"></p>
                    <p>Editormd v<?php $app = new \App();
                    $app->LoadInfoByXml('plugin', 'Editormd');
                    echo $app->version;?></p>
                    <br><br>
                    <p>插件作者：心扬</p>
                    <p>联系方式：chrishyze@163.com</p>
                    <p>欢迎通过邮件反馈BUG或建议，感谢您的支持！</p>
                    <br><br>
                    <p>开源项目</p>
                    <p><a href="https://github.com/pandao/editor.md" target="_blank" class="underline-link">pandao/editor.md</a> (The MIT License)</p>
                    <p><a href="https://github.com/thephpleague/html-to-markdown" target="_blank" class="underline-link">league/html-to-markdown</a> (The MIT License)</p>
                    <p><a href="https://github.com/erusev/parsedown" target="_blank" class="underline-link">erusev/parsedown</a> (The MIT License)</p>
                    <p><a href="https://github.com/sentsin/layui" target="_blank" class="underline-link">sentsin/layui</a> (The MIT License)</p>
                    <br><br>
                    <p><a class="layui-btn layui-btn-normal" href="<?php echo $zbp->host; ?>zb_users/plugin/AppCentre/main.php?auth=2ffbff0a-1207-4362-89fb-d9a780125e0a" style="color: #FFFFFF">开发者的其他作品</a> <button id="donation" class="layui-btn layui-btn-danger">请我喝咖啡（捐赠）</button></p>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="donation_layer">
    <img src="<?php echo $zbp->host; ?>zb_users/plugin/Editormd/images/wxzanshang.jpg" alt="" width="450px" height="450px">
</div>

<script src="<?php echo $zbp->host; ?>zb_users/plugin/Editormd/editormd.min.js"></script>
<script src="<?php echo $zbp->host; ?>zb_users/plugin/Editormd/lib/layui/layui.js"></script>
<script>
// Editormd 后台全局统一变量
window.EDITORMD = {
    homeUrl: "<?php echo $zbp->host; ?>zb_users/plugin/Editormd",
    csrfToken: "<?php echo $zbp->GetCSRFToken('Editormd'); ?>",
    editorConfig: <?php echo $zbp->Config('Editormd')->editor; ?>,
    pluginConfig: <?php echo $zbp->Config('Editormd')->plugin; ?>
};
</script>
<script src="<?php echo $zbp->host; ?>zb_users/plugin/Editormd/js/admin-main.js"></script>

<?php
require_once __DIR__.'/../../../zb_system/admin/admin_footer.php';
RunTime();
