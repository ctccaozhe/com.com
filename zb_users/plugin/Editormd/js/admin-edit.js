/**
 * Editormd
 *
 * 后台编辑界面
 * 编辑器启动脚本
 *
 * @author chrishyze <chrishyze@163.com>
 */

// 内容编辑器对象
window.ContentEditor = null;
// 摘要编辑器对象
window.IntroEditor = null;

// 覆写默认的编辑器初始化函数
window.editor_init = () => { };

$(function () {
  /**
   * 更新提示
   */
  const updateNotify = () => {
    // 构建更新弹窗
    let phpAlertHtml = '';
    if (window.EDITORMD.phpAlert) {
      phpAlertHtml = '<p style="color:red">【重要提示】<br>您的 PHP 版本过低，Editormd 未来的更新将不再支持 PHP 5.6 以下的版本，请及时升级 PHP，强烈建议使用 PHP 7.2 及以上版本！</p><br>';
    }
    let updateLog = '';
    window.EDITORMD.updateLog.forEach((log) => {
      updateLog += `<p>● ${log}</p>`;
    });
    const $modalHtml = $(`<div id="editormd-dialog" title="Editormd ${window.EDITORMD.pluginConfig.version} 更新提示" style="display:none;z-index:9999;">${phpAlertHtml}<p><strong>【更新内容】</strong></p>${updateLog}</div>`);
    $('body').append($modalHtml);
    $('body').css('overflow', 'hidden');
    $modalHtml.dialog({
      width: 500,
      modal: true,
      resizable: false,
      beforeClose() {
        $(this).dialog('destroy');
        $('body').css('overflow', 'auto');
      },
      buttons: [{
        text: '确认（此版本不再提示）',
        click() {
          $(this).dialog('close');
          $.get(`${window.EDITORMD.homeUrl}php/config.php?action=offnotify&csrfToken=${window.EDITORMD.csrfToken}`);
        },
      }],
    });
  }

  // 重新将 textarea 内容初始化为 markdown
  $('textarea#editor_content').val(window.EDITORMD.contentMarkdown);
  $('textarea#editor_intro').val(window.EDITORMD.introMarkdown);

  // 编辑器上方的动态主题下拉选择框和配置按钮
  if (window.EDITORMD.editorConfig.dynamictheme) {
    $("span#msg").html(`<span id="theme-select">动态主题：<select id="editormd-theme-select"><option selected="selected" value="">选择工具栏主题</option></select>&emsp;<select id="editor-area-theme-select"><option selected="selected" value="">选择编辑器主题</option></select>&emsp;<select id="preview-area-theme-select"><option selected="selected" value="">选择实时预览主题</option></select></span><a href="${window.EDITORMD.homeUrl}main.php#tabs=editor" style="border:solid 1px rgb(221,221,221);padding:4px 10px;margin-left:15px;">设 置</a>`);
  } else {
    $("span#msg").html(`<a href="${window.EDITORMD.homeUrl}main.php#tabs=editor" style="border:solid 1px rgb(221,221,221);padding:4px 10px;position:relative;bottom:5px;float:right;">设 置</a>`);
  }

  /**
   * 内容编辑器初始化, 用于支持编辑器 API js 接口（editor_api）
   *
   * @param {object} obj 内容编辑器对象
   */
  const contentEditorInit = function (obj) {
    // 编辑器对象
    window.editor_api.editor.content.obj = obj;
    /**
     * 获取编辑器所有内容
     * @return {string} 编辑器当前内容
     */
    window.editor_api.editor.content.get = function () { return this.obj.getValue() };
    /**
     * 替换编辑器的内容
     * @param {string} str 要替换的内容
     * @return
     */
    window.editor_api.editor.content.put = function (str) { return this.obj.setValue(str) };
    /**
     * 让编辑器获得尾部焦点
     * @return
     */
    window.editor_api.editor.content.focus = function () { return this.obj.focus() };
    /**
     * 在光标处插入内容
     * @param {string} str 要插入的内容
     */
    window.editor_api.editor.content.insert = function (str) { return this.obj.insertValue(str) };
    // 原内容
    window.sContent = obj.getValue();
  }

  /**
   * 摘要编辑器初始化, 用于支持编辑器 API js 接口（editor_api）
   *
   * @param {object} obj 摘要编辑器对象
   */
  const introEditorInit = function (obj) {
    window.editor_api.editor.intro.obj = obj;
    window.editor_api.editor.intro.get = function () { return this.obj.getValue() };
    window.editor_api.editor.intro.put = function (str) { return this.obj.setValue(str) };
    window.editor_api.editor.intro.focus = function () { return this.obj.focus() };
    window.editor_api.editor.intro.insert = function (str) { return this.obj.insertValue(str) };
    window.sIntro = obj.getValue();
  }

  // 将主题配置储存至浏览器本地
  if (!window.localStorage.hasOwnProperty('theme')) {
    window.localStorage['theme'] = window.EDITORMD.editorConfig.toolbartheme;
  }
  if (!window.localStorage.hasOwnProperty('editorTheme')) {
    window.localStorage['editorTheme'] = window.EDITORMD.editorConfig.editortheme;
  }
  if (!window.localStorage.hasOwnProperty('previewTheme')) {
    window.localStorage['previewTheme'] = window.EDITORMD.editorConfig.previewtheme;
  }

  /**
   * Editor.md 动态切换主题
   * @param {string} id
   * @param {array} themes
   * @param {string} lsKey
   * @param {object} callback
   */
  const themeSelect = function (id, themes, lsKey, callback) {
    const select = $("#" + id);

    for (let i = 0, len = themes.length; i < len; i++) {
      const selected = (window.localStorage[lsKey] == themes[i]) ? ' selected="selected"' : '';
      select.append(`<option value="${themes[i]}" ${selected}>${themes[i]}</option>`);
    }

    select.bind('change', function () {
      const theme = $(this).val();
      if (theme === '') {
        alert('theme == ""');
        return false;
      }
      window.localStorage[lsKey] = theme;
      callback(select, theme);
    });

    return select;
  };

  // 切换工具栏主题
  themeSelect("editormd-theme-select", window.editormd.themes, "theme", function ($this, theme) {
    ContentEditor.setTheme(theme);
    IntroEditor.setTheme(theme);
  });
  // 切换编辑区域主题
  themeSelect("editor-area-theme-select", window.editormd.editorThemes, "editorTheme", function ($this, theme) {
    ContentEditor.setCodeMirrorTheme(theme);
    IntroEditor.setCodeMirrorTheme(theme);
  });
  // 切换预览区域主题
  themeSelect("preview-area-theme-select", window.editormd.previewThemes, "previewTheme", function ($this, theme) {
    ContentEditor.setPreviewTheme(theme);
    IntroEditor.setPreviewTheme(theme);
  });

  // 自定义 Emoji 的 url 路径
  window.editormd.emoji = {
    path: `${window.EDITORMD.homeUrl}images/github-emojis/`,
    ext: '.png'
  };

  // 自定义 Katex 地址
  window.editormd.katexURL = {
    js: window.EDITORMD.editorConfig.texurl,
    css: window.EDITORMD.editorConfig.texurl
  };

  // 初始化内容编辑器
  window.ContentEditor = window.editormd('carea', {
    width: '100%',
    height: 640,
    autoLoadModules: false,  // Manually load modules
    path: `${window.EDITORMD.homeUrl}lib/`,
    toolbarIcons: function () {
      return window.editormd.toolbarModes['full']; // full, simple, mini
    },
    theme: window.localStorage['theme'],
    previewTheme: window.localStorage['previewTheme'],
    editorTheme: window.localStorage['editorTheme'],
    codeFold: true,
    syncScrolling: window.EDITORMD.editorConfig.scrolling == 1
      ? 'single'
      : window.EDITORMD.editorConfig.scrolling == 2 ? true : false,
    saveHTMLToTextarea: true,    // 保存 HTML 到 Textarea
    searchReplace: true,
    autoHeight: window.EDITORMD.editorConfig.autoheight,
    watch: window.EDITORMD.editorConfig.preview,  // 实时预览
    // HTML 标签解析
    htmlDecode: window.EDITORMD.editorConfig.htmldecode == 1
      ? true
      : window.EDITORMD.editorConfig.htmldecode == 2
        ? window.EDITORMD.editorConfig.htmlfilter
        : false,
    emoji: window.EDITORMD.editorConfig.emoji,
    taskList: window.EDITORMD.editorConfig.tasklist,  // Github Flavored Markdown 任务列表
    toc: window.EDITORMD.editorConfig.tocm,
    tocm: window.EDITORMD.editorConfig.tocm,         // Using [TOCM]
    tex: window.EDITORMD.editorConfig.katex,                   // 科学公式TeX语言支持，默认关闭
    flowChart: window.EDITORMD.editorConfig.flowchart,             // 流程图支持，默认关闭
    sequenceDiagram: window.EDITORMD.editorConfig.sdiagram,       // 时序/序列图支持，默认关闭,
    imageUpload: true,
    imageFormats: ['jpg', 'jpeg', 'gif', 'png', 'bmp', 'webp'],
    imageUploadURL: `${window.EDITORMD.homeUrl}php/upload.php`,
    crossDomainUpload: false,
    autoFocus: false,
    onload: function () {
      contentEditorInit(this);
      if (window.EDITORMD.pluginConfig.notify) {
        updateNotify();
      }
    },
    onfullscreen: function () {
      this.editor.css('margin-top', '0');
    },
    onfullscreenExit: function () {
      this.editor.css('margin-top', '5px');
    }
  });

  // 摘要编辑器
  const $intro = $('#tarea');

  /* 新摘要显示逻辑 */
  // 显示摘要区块
  $('#divIntro').show();
  // 清除摘要提示内容
  $('#insertintro').html('');
  // 指示符和生成按钮
  $('#theader').append(`<span id="GenIntro">生成摘要</span><span id="GenIntroHintBtn">?</span> <span id="GenIntroHint">点击“摘要”，切换摘要编辑器的显示状态（可在Editormd配置中设定默认状态）；点击“生成摘要”，将会提取正文中首条分隔符以上的内容将作为摘要。（更多详情请查看Editormd帮助）</span>`);

  // 动态生成摘要编辑器
  window.IntroEditor = window.editormd('tarea', {
    width: '100%',
    height: 300,
    autoLoadModules: false,  // Manually load modules
    path: `${window.EDITORMD.homeUrl}lib/`,
    saveHTMLToTextarea: true,
    onload: function () {
      this.config({
        toolbarIcons: function () {
          return ['undo', 'redo', '|', 'bold', 'del', 'italic', '|', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6', '|', 'list-ul', 'list-ol', '|', 'watch'];
        }
      });
      introEditorInit(this);
      $('#emdLoadError').hide();
      // 根据用户设置隐藏摘要编辑器
      if (!window.EDITORMD.editorConfig.intro) {
        $intro.hide();
      }
    }
  });

  /**
   * 使摘要编辑器获得焦点
   */
  const focusIntro = () => {
    // 滚动动画
    $('html, body').animate({ scrollTop: $('#divIntro').offset().top }, 'fast');
    window.editor_api.editor.intro.focus();
  };
  // 点击显示/隐藏摘要编辑器
  $('#theader > .editinputname').click(function () {
    if ($intro.is(':visible')) {
      $intro.hide();
    } else {
      $intro.show();
      focusIntro();
    }
  });
  // 点击生成摘要
  $('#GenIntro').click(function () {
    if (!$intro.is(':visible')) {
      $intro.show();
    }
    const s = ContentEditor.getValue();
    let hrIndex = s.indexOf('------------');
    if (hrIndex == -1) {
      // 若没有横线，则截取前 250 个字符
      hrIndex = 250;
    }
    IntroEditor.setValue(s.substr(0, hrIndex));
    focusIntro();
  });
  // 隐藏摘要
  $('#GenIntroHintBtn').click(function () {
    $('#GenIntroHint').toggle();
  });

  //保存 HTML 源码
  $('form#edit').submit(function (e) {
    $('textarea[name="carea-html-code"]').val(ContentEditor.getHTML());
    if (IntroEditor != undefined) {
      $('textarea[name="tarea-html-code"]').val(IntroEditor.getHTML());
    }
  });

  /**
   * 粘贴上传文件
   * @author zsx
   */
  const pasteWatcher = setInterval(() => {
    if (window.ContentEditor) {
      window.ContentEditor.cm.getWrapperElement().addEventListener('paste', e => {
        if (e.clipboardData && e.clipboardData.items[0].type.indexOf('image') > -1) {
          const file = e.clipboardData.items[0].getAsFile();
          const form = new FormData();
          form.append('editormd-image-file', file);
          // const blob = new Blob([file], {type: file.type});
          // form.append('editormd-image-file', blob, '');
          $.ajax({
            type: 'post',
            contentType: false,
            processData: false,
            url: `${window.EDITORMD.homeUrl}php/upload.php?guid=${(new Date).getTime()}`,
            data: form,
            success: s => {
              s = JSON.parse(s);
              ContentEditor.cm.replaceSelection('![](' + s.url + ')');
            }
          })
        }
      });

      window.clearInterval(pasteWatcher);
    }
  }, 1000);
});
