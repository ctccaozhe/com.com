<?php
/**
 * Editor.md for Z-BlogPHP
 *
 * 插件嵌入页.
 *
 * @author 心扬 <chrishyze@163.com>
 */

// 注册插件
RegisterPlugin('Editormd', 'ActivePlugin_Editormd');

/**
 *  挂载插件接口.
 */
function ActivePlugin_Editormd()
{
    global $zbp;

    //接口：文章编辑页加载前处理内容，输出位置在<head>尾部
    Add_Filter_Plugin('Filter_Plugin_Edit_Begin', 'EditHead_Editormd');

    //接口：文章编辑页加载前处理内容，输出位置在<body>尾部
    Add_Filter_Plugin('Filter_Plugin_Edit_End', 'EditBody_Editormd');

    //1号输出接口，在内容文本框下方，用于存放Editor.md 转换的 HTML 源码，以及加载提示
    Add_Filter_Plugin('Filter_Plugin_Edit_Response', 'Response1_Editormd');

    //处理文章页模板接口
    Add_Filter_Plugin('Filter_Plugin_ViewPost_Template', 'ExtraSupport_Editormd');

    //接口：提交文章数据接管
    Add_Filter_Plugin('Filter_Plugin_PostArticle_Core', 'PostData_Editormd');

    //接口：提交文章数据接管
    Add_Filter_Plugin('Filter_Plugin_PostPage_Core', 'PostData_Editormd');

    //更新逻辑
    if (!$zbp->Config('Editormd')->HasKey('plugin')) { // v2.86之前版本
        ResetConfig_Editormd(true);
    } else { // v2.86及以后的版本
        if (json_decode($zbp->Config('Editormd')->plugin)->version < 2.92) {
            UpdateConfig_Editormd();
        }
    }
}

/**
 * 文章编辑页面 <head> 尾部
 * 引入 Editor.md 文件.
 */
function EditHead_Editormd()
{
    global $zbp;
    $editorConfig = json_decode($zbp->Config('Editormd')->editor, true);

    // 手动预加载模块样式表
    echo '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Editormd/lib/codemirror/codemirror.min.css">';
    echo '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Editormd/lib/codemirror/addon/dialog/dialog.css">';
    echo '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Editormd/lib/codemirror/addon/search/matchesonscrollbar.css">';
    // KaTeX
    if ($editorConfig['katex']) {
        echo '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Editormd/lib/katex/katex.min.css">';
    }
    echo '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Editormd/css/editormd.min.css">';

    // 后台编辑页面样式
    echo '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Editormd/css/admin-edit.css">';
    // 用户自定义样式
    if ($editorConfig['editorstyle']) {
        echo '<style>'.$editorConfig['editorstyle'].'</style>';
    }
}

/**
 * 文章编辑页面 <body> 尾部
 * 配置和启动 Editor.md.
 */
function EditBody_Editormd()
{
    global $zbp;

    $editorConfigJson = $zbp->Config('Editormd')->editor;
    $editorConfig = json_decode($editorConfigJson, true);

    // textarea 中的 markdown 源码
    $contentMarkdown = '';
    $introMarkdown = '';

    // 判断 URL 是否包含 id，若包含则为重新编辑文章，否则为新建文章
    if (GetVars('id', 'GET')) {
        $article = new \Post();
        $article->LoadInfoByID((int) GetVars('id', 'GET'));

        // 判断是否为 Editormd 创建的文章
        if (null === $article->Metas->md_content) {
            // 非 Editormd 创建的文章，需要先将 HTML 转为 markdown
            include_once __DIR__.'/vendor/autoload.php';
            $converter = new \League\HTMLToMarkdown\HtmlConverter();
            // 正文 markdown
            try {
                $contentMarkdown = $converter->convert($article->Content);
            } catch (Exception $e) {
                $contentMarkdown = '';
            }
            // 摘要 markdown
            try {
                $introMarkdown = $converter->convert($article->Intro);
            } catch (Exception $e) {
                $introMarkdown = '';
            }
        } else {
            // Editormd 创建或编辑过的文章
            $contentMarkdown = $article->Metas->md_content;
            $introMarkdown   = $article->Metas->md_intro;
        }
    }

    $phpAlert = version_compare(PHP_VERSION, '5.6.0', '<') ? 1 : 0;

    // Editor.md 配置
    echo '<!-- Editormd Config -->
<textarea id="editormdContentMd">'.$contentMarkdown.'</textarea>
<textarea id="editormdIntroMd">'.$introMarkdown.'</textarea>
<script>
window.EDITORMD = {
    homeUrl: "'.$zbp->host.'zb_users/plugin/Editormd/",
    csrfToken: "'.$zbp->GetCSRFToken('Editormd').'",
    editorConfig: '.$editorConfigJson.',
    pluginConfig: '.$zbp->Config('Editormd')->plugin.',
    phpAlert: '.$phpAlert.',
    updateLog: ["优化编辑器加载", "升级 KaTeX 至 v0.11.1", "其他代码优化"],
    contentMarkdown: document.getElementById("editormdContentMd").value,
    introMarkdown: document.getElementById("editormdIntroMd").value
};</script>';

    // 手动预加载编辑器模块
    echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/codemirror/codemirror.min.js"></script>';
    echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/codemirror/addons.min.js"></script>';
    echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/codemirror/modes.min.js"></script>';
    echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/marked.min.js"></script>';

    echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/prettify.min.js"></script>';

    // 流程图
    if ($editorConfig['flowchart']) {
        echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/raphael.min.js"></script>';
        echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/underscore.min.js"></script>';
        echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/sequence-diagram.min.js"></script>';
        echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/flowchart.min.js"></script>';
        echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/jquery.flowchart.min.js"></script>';
    }

    // KaTeX
    if ($editorConfig['katex']) {
        echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/katex/katex.min.js"></script>';
    }

    // 编辑器核心
    echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/editormd.min.js"></script>';

    // 启动脚本
    echo '<script src="'.$zbp->host.'zb_users/plugin/Editormd/js/admin-edit.js"></script>';
}

/**
 * 在内容文本框下方插入，
 * 用于存放 Editor.md 转换的 HTML 源码、更新提示
 * 以及加载提示，每次加载成功后会将此内容隐藏.
 */
function Response1_Editormd()
{
    global $zbp;

    echo '
    <textarea class="editormd-html-textarea" name="carea-html-code"></textarea>
    <textarea class="editormd-html-textarea" name="tarea-html-code"></textarea>
    <div id="emdLoadError">
        <div style="font-size: 20px">Editormd 编辑器启动中……</div>
        <div style="color: #646464">如果这条消息一直显示，说明启动失败，请<a href="'.$zbp->host.'zb_users/plugin/Editormd/main.php#tabs=help" target="_blank" style="text-decoration:underline">点击此处查看解决方案</a></div>
    </div>';
}

/**
 * 前台扩展语言支持.
 *
 * @param object $template
 */
function ExtraSupport_Editormd(&$template)
{
    global $zbp,
        $action,
        $mip_start;  // 官方 MIP 插件全局变量

    //搜索页直接返回
    if ('search' == $action) {
        return;
    }

    $plugin  = json_decode($zbp->Config('Editormd')->plugin);
    $article = $template->GetTags('article');

    if (empty($article->Content)) {
        $article->Content = '<!-- Editormd: Content is empty! -->';

        return;
    }

    $doc = new \DOMDocument('1.0', 'UTF-8');
    libxml_use_internal_errors(true);
    $doc->loadHTML($article->Content);
    $xpath = new \DOMXPath($doc);

    // 检测当前主题是否启用了官方MIP插件依赖，并兼容第三方MIP主题
    if ($mip_start || $plugin->mipsupport) {
        if (false !== strpos($article, '[TOC]') || false !== strpos($article, '[TOCM]')) {
            $titlesHtml = '<div class="emd-toc"><div class="emd-toc-title">内容导航</div>';
            $headings    = $xpath->query('//h1 | //h2 | //h3 | //h4 | //h5 | //h6');
            foreach ($headings as $head) {
                $titlesHtml .= '<div class="emd-toc-item emd-toc-h'.substr($head->tagName, 1).'"><a href="#'.trim($head->textContent).'">'.$head->textContent.'</a></div>';
            }
            $titlesHtml .= '</div>';
            $zbp->header .= '<link rel="stylesheet" type="text/css" href="'.str_replace('mip/', '', substr($zbp->host, 5)).'zb_users/plugin/Editormd/css/mipsupport.css">';
            $article->Content = str_replace('[TOCM]', '', str_replace('[TOC]', '', $article->Content));
            $article->Content = $titlesHtml.$article->Content;
        }

        return;
    }

    //配置项
    $editor = json_decode($zbp->Config('Editormd')->editor);

    if (1 == $editor->htmldecode) {
        $editor->htmldecode = 'htmlDecode: true';
    } elseif (2 == $editor->htmldecode) {
        $editor->htmldecode = 'htmlDecode: "'.$editor->htmlfilter.'"';
    } else {
        $editor->htmldecode = 'htmlDecode: false';
    }

    // 扩展功能支持
    if ($editor->extras && null !== $article->Metas->md_content) {
        // 自带扩展样式引入
        if ($plugin->extstyle) {
            $zbp->header .= '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Editormd/css/editormd.preview.min.css">';
        }
        // 用户自定义扩展样式引入
        $zbp->header = $zbp->header.'<style>.editormd-html-preview{width:100%;margin:0;padding:0;}'.$plugin->cextstyle.'</style>';
        // 替换文章内容为 Markdown 原文
        $article->Content = '<div id="editormdContent" style="overflow-y:hidden"><textarea id="editormdTextarea" style="display:none;">'.$article->Metas->md_content.'</textarea></div>';
        // 动态渲染脚本
        $zbp->footer .= '<script src="'.$zbp->host.'zb_users/plugin/Editormd/editormd.min.js"></script>
        <script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/marked.min.js"></script>
        <script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/prettify.min.js"></script>
        <script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/raphael.min.js"></script>';
        if ($editor->sdiagram) {
            $zbp->footer .= '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/underscore.min.js"></script>
            <script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/sequence-diagram.min.js"></script>';
        }
        if ($editor->flowchart) {
            $zbp->footer .= '<script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/flowchart.min.js"></script>
            <script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/jquery.flowchart.min.js"></script>';
        }
        $zbp->footer .= '<script>editormd.emoji={path: "'.$zbp->host.'zb_users/plugin/Editormd/images/github-emojis/",ext:".png"};';
        if ($editor->katex) {
            $zbp->footer .= 'editormd.katexURL={js:"'.$editor->texurl.'",css:"'.$editor->texurl.'"};';
        }
        $zbp->footer .= '$(function(){
            editormd.markdownToHTML("editormdContent", {
                emoji           : '.$editor->emoji.',
                toc             : '.$editor->tocm.',
                tocm            : '.$editor->tocm.',
                taskList        : '.$editor->tasklist.',
                tex             : '.$editor->katex.',
                flowChart       : '.$editor->flowchart.',
                sequenceDiagram : '.$editor->sdiagram.',
                '.$editor->htmldecode.'
            });
        });</script>';
    } elseif ($zbp->option['ZC_SYNTAXHIGHLIGHTER_ENABLE'] &&
        $xpath->query('//pre')->length > 0 &&
        'off' != $plugin->codetheme
    ) {
        //使用插件自带代码高亮
        $codetheme = 'prettifylight';
        $linenums  = '';
        if (false !== stristr($plugin->codetheme, 'dark')) {
            $codetheme = 'prettifymonokai';
        }
        if (false !== stristr($plugin->codetheme, '0')) {
            $linenums = '<style>pre ol.linenums,pre ol.linenums li{list-style:none !important;margin-left:0 !important;}</style>';
        }
        $zbp->header .= '<link rel="stylesheet" href="'.$zbp->host.'zb_users/plugin/Editormd/css/'.$codetheme.'.css"><script src="'.$zbp->host.'zb_users/plugin/Editormd/lib/prettify.min.js"></script>'.$linenums;
        $zbp->footer .= '<script>$(function(){ $("pre").addClass("prettyprint linenums"); prettyPrint(); });</script>';
    }
}

/**
 * 文章内容提交处理.
 *
 * @param object $article
 */
function PostData_Editormd(&$article)
{
    // 保存原始markdown数据至扩展元数据
    $article->Metas->md_content = $article->Content;
    $article->Metas->md_intro   = $article->Intro;

    //获取正文的HTML源码
    $article->Content = $_POST['carea-html-code'];

    //获取摘要的HTML源码
    if (empty($_POST['tarea-html-code'])) {
        // 处理系统自动生成的摘要
        include_once __DIR__.'/vendor/autoload.php';
        $Parsedown      = new \Parsedown();
        $article->Intro = $Parsedown->text($article->Intro);
        // 检查分隔符
        if (preg_match('/<hr.*?>/i', $article->Intro)) {
            $introArr      = preg_split('/<hr.*?>/i', $article->Intro);
            $article->Intro = $introArr[0];
        }
        // 将<!--autointro-->标记放到最后
        if (false !== stripos($_POST['Intro'], '<!--autointro-->')) {
            $article->Intro = str_replace('<!--autointro-->', '', $article->Intro);
            $article->Intro = $article->Intro.'<!--autointro-->';
        }
    } else {
        $article->Intro = $_POST['tarea-html-code'];
    }
    $article->Save();
}

/**
 * 更新配置
 *
 * @return void
 */
function UpdateConfig_Editormd()
{
    global $zbp;

    // 获取旧配置
    $editor            = json_decode($zbp->Config('Editormd')->editor, true);
    $plugin            = json_decode($zbp->Config('Editormd')->plugin, true);
    $plugin['version'] = 2.92;
    $plugin['notify']  = 1;

    // v2.88 新增
    if (!array_key_exists('extstyle', $plugin)) {
        $plugin['extstyle'] = 1;
    }
    if (!array_key_exists('cextstyle', $plugin)) {
        $plugin['cextstyle'] = '';
    }
    if (!array_key_exists('intro', $editor)) {
        $editor['intro'] = 0;
    }

    // v2.91 新增
    if (!array_key_exists('editorstyle', $editor)) {
        $editor['editorstyle'] = '/* 编辑区域 */.editormd .CodeMirror pre {font-size: 16px;} /* 预览区域 */.editormd-preview-container p {font-size: 16px;}';
    }

    $zbp->Config('Editormd')->editor = json_encode($editor);
    $zbp->Config('Editormd')->plugin = json_encode($plugin);

    $zbp->SaveConfig('Editormd');
}

/**
 * 重置设置
 *
 * @param boolean $del 是否删除已有配置
 */
function ResetConfig_Editormd($del = false)
{
    global $zbp;

    if ($del) {
        $zbp->DelConfig('Editormd');
    }

    // 编辑器配置
    $zbp->Config('Editormd')->editor = json_encode(array(
        'toolbartheme' => 'default',  // 工具栏主题设置
        'editortheme'  => 'default',  // 编辑区主题设置
        'previewtheme' => 'default',  // 预览区主题设置
        'preview'      => 1,     // 实时预览
        'autoheight'   => 0,    // 编辑器自动长高
        'scrolling'    => 2,     // 编辑器滚动，0禁用，1单向，2双向
        'dynamictheme' => 1,     // 动态主题
        'emoji'        => 0,    // emoji 配置
        'editorstyle'  => '/* 编辑区域 */.editormd .CodeMirror pre {font-size: 16px;} /* 预览区域 */.editormd-preview-container p {font-size: 16px;}',   // 编辑页面附加样式
        'extras'       => 0,    // 扩展支持
        'htmldecode'   => 0,    // HTML 解析，0关闭，1开启，2规则
        'tocm'         => 0,    // TOCM 列表
        'tasklist'     => 0,    // GFM 任务列表
        'flowchart'    => 0,    // 流程图
        'katex'        => 0,    // Tex 科学公式语言
        'sdiagram'     => 0,    // 时序图/序列图
        'htmlfilter'   => 'style,script,iframe|on*', // HTML 解析过滤标签
        'texurl'       => $zbp->host.'zb_users/plugin/Editormd/lib/katex/katex.min',  // Katex路径
        'intro'        => 0,  // 是否显示摘要编辑器
    ));

    // 插件配置
    $zbp->Config('Editormd')->plugin = json_encode(array(
        'version'       => 2.92, //版本号
        'notify'        => 1, //更新提示
        'keepconfig'    => 1, //卸载时保留配置
        'mipsupport'    => 0,    // 兼容第三方 MIP 主题
        'codetheme'     => 'light_0',  // 前台代码主题
        'keepmeta'      => 1,  // 默认保存扩展元数据
        'extstyle'      => 1,  // 自带扩展样式
        'cextstyle'     => '',  // 自定义扩展样式
    ));

    $zbp->SaveConfig('Editormd');
}

/**
 * 插件安装激活时执行函数.
 */
function InstallPlugin_Editormd()
{
    global $zbp;

    // 若不存在配置则初始化配置
    if (!$zbp->HasConfig('Editormd')) {
        ResetConfig_Editormd(false);
    }
}

/**
 * 插件卸载时执行函数
 */
function UninstallPlugin_Editormd()
{
    global $zbp;

    // 删除配置
    if (!json_decode($zbp->Config('Editormd')->plugin)->keepconfig) {
        $zbp->DelConfig('Editormd');
    }
}
