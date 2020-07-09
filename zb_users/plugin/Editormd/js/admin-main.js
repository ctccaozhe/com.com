/**
 * Editormd
 *
 * 后台设置页面脚本
 *
 * @author chrishyze <chrishyze@163.com>
 */

$(function () {
  let testEditor = null;

  /**
   * 动态加载编辑器
   */
  const loadEditormd = function () {
    editormd.emoji = {
      path: `${window.EDITORMD.homeUrl}/images/github-emojis/`,
      ext: '.png'
    };
    $.get(`${window.EDITORMD.homeUrl}/preview.md`, function (md) {
      testEditor = editormd('test-editormd', {
        width: '100%',
        height: 640,
        tocm: true,
        markdown: md,
        codeFold: true,
        emoji: true,
        imageUpload: false,
        theme: window.localStorage.theme ? window.localStorage.theme : window.EDITORMD.editorConfig.toolbartheme,
        previewTheme: window.localStorage.previewTheme ? window.localStorage.previewTheme : window.EDITORMD.editorConfig.previewtheme,
        editorTheme: window.localStorage.editorTheme ? window.localStorage.editorTheme : window.EDITORMD.editorConfig.editortheme,
        path: `${window.EDITORMD.homeUrl}/lib/`
      });
    });
  }

  //编辑器主题切换
  const themeSelect = function (id, themes, lsKey, callback) {
    const select = $('#' + id);

    for (let i = 0; i < themes.length; i++) {
      const selected = window.localStorage[lsKey] == themes[i] ? ' selected="selected"' : '';
      select.append(`<option value="${themes[i]}"${selected}>${themes[i]}</option>`);
    }
    select.bind('change', function () {
      const theme = $(this).val();

      if (theme === '') {
        alert('theme == \'\'');
        return false;
      }

      window.localStorage[lsKey] = theme;
      callback(select, theme);
    });

    return select;
  }

  window.localStorage['theme'] = window.EDITORMD.editorConfig.toolbartheme;
  window.localStorage['editorTheme'] = window.EDITORMD.editorConfig.editortheme;
  window.localStorage['previewTheme'] = window.EDITORMD.editorConfig.previewtheme;

  themeSelect('editormd-theme-select', editormd.themes, 'theme', function ($this, theme) {
    testEditor.setTheme(theme);
  });
  themeSelect('editor-area-theme-select', editormd.editorThemes, 'editorTheme', function ($this, theme) {
    testEditor.setCodeMirrorTheme(theme);
  });
  themeSelect('preview-area-theme-select', editormd.previewThemes, 'previewTheme', function ($this, theme) {
    testEditor.setPreviewTheme(theme);
  });

  // 模块化加载 layui
  layui.use(['layer', 'form', 'element'], function () {
    const layer = layui.layer;
    const form = layui.form;
    const element = layui.element;

    // 获取 hash 来切换选项卡
    const layid = location.hash.replace(/^#tabs=/, '');
    element.tabChange('tabs', layid);

    // 监听 Tab 切换，以改变地址 hash 值
    element.on('tab(tabs)', function () {
      location.hash = 'tabs=' + this.getAttribute('lay-id');
      if (testEditor === null) {
        loadEditormd();
      }
    });

    if (layid === 'preview') {
      loadEditormd();
    }

    // 监听 HTML 解析切换
    form.on('select(htmldecode)', function (data) {
      if (data.value === '2') {
        $('#htmlFilterRule').show();
      } else {
        $('#htmlFilterRule').hide();
      }
    });

    // 监听扩展功能切换
    form.on('select(extras)', function (data) {
      if (data.value === '1') {
        $('#editormdExtras').show();
      } else {
        $('#editormdExtras').hide();
      }
    });

    // 重置设置
    form.on('submit(reset)', function (data) {
      layer.confirm('请谨慎操作！是否重置所有设置？', {
        btn: ['确认', '取消'],
        yes: function (index) {
          layer.close(index);
          $.get(`${window.EDITORMD.homeUrl}/php/config.php?action=reset&csrfToken=${window.EDITORMD.csrfToken}`, function (data) {
            layer.open({
              title: '操作提示',
              content: data[1],
              shadeClose: true,
              yes: function (idx) {
                layer.close(idx);
                location.replace(`${window.EDITORMD.homeUrl}/main.php#tabs=plugin`);
                location.reload();
              }
            });
          });
        },
        btn2: function (index) {
          layer.close(index);
        }
      });

      return false;
    });

    // 编辑器配置提交
    form.on('submit(editor)', function (data) {
      $.post(`${window.EDITORMD.homeUrl}/php/config.php?csrfToken=${window.EDITORMD.csrfToken}`, data.field, function (result) {
        layer.open({
          title: '操作提示',
          content: result[1],
          shadeClose: true,
          yes: function (index) {
            layer.close(index);
            location.replace('main.php#tabs=editor');
            location.reload();
          }
        });
      });

      return false;
    });

    // 主题配置提交
    form.on('submit(themes)', function (data) {
      console.log(data)
      $.post(`${window.EDITORMD.homeUrl}/php/config.php?csrfToken=${window.EDITORMD.csrfToken}`, $(data.form).serializeArray(), function (result) {
        layer.open({
          title: '操作提示',
          content: result[1],
          shadeClose: true,
          yes: function (index) {
            layer.close(index);
          }
        });
      });

      return false;
    });

    // 插件配置提交
    form.on('submit(plugin)', function (data) {
      console.log(data)
      $.post(`${window.EDITORMD.homeUrl}/php/config.php?csrfToken=${window.EDITORMD.csrfToken}`, data.field, function (result) {
        layer.open({
          title: '操作提示',
          content: result[1],
          shadeClose: true,
          yes: function (index) {
            layer.close(index);
          }
        });
      });

      return false;
    });

    // 帮助提示
    const tipsMessage = {
      'i#dynamic-tip': '为编辑器添加动态主题菜单栏',
      'i#emoji-tip': 'Github emoji 表情图标功能，详情查看帮助说明',
      'i#ah-tip': '编辑区域的高度随内容的增长而增长',
      'i#pre-tip': '右侧预览区域的默认开关，同时也可以在编辑器的工具栏中设定',
      'i#scroll-tip': '两边：预览区域(右侧)与编辑区域(左侧)同时滚动<br>单边：只有右侧跟随左侧滚动<br>禁用：两侧都不跟随滚动',
      'i#ct-tip': '为前台代码块定义样式，注意：仅在关闭编辑器扩展时生效！',
      'i#mip-tip': '开启后可以兼容第三方MIP主题<br>如果使用了依赖官方MIP插件的MIP主题，请关闭兼容<br>详情查看帮助说明',
      'i#hd-tip': '开启后可以解析编辑器中的 HTML 标签<br>详情查看帮助说明',
      'i#hf-tip': 'HTML 解析过滤的标签，可以为空',
      'i#ext-tip': '扩展功能，包括TOC目录、任务列表、科学公式、流程图、时序图等功能<br>详情查看帮助说明',
      'i#tex-tip': 'Katex科学公式，详情查看帮助说明',
      'i#theme-tip': '保存主题后，即使关闭了动态主题，设置的主题仍然生效',
      'i#keep-tip': '是否在停用或卸载插件后仍保留配置',
      'i#exts-tip': '是否在启用扩展功能后在前台引入编辑器自带的样式<br>（editormd.preview.min.css）',
      'i#intro-tip': '是否在加载编辑页面之后就显示摘要编辑器'
    };
    for (const key in tipsMessage) {
      $(key).mouseover(function () {
        const tip = layer.tips(tipsMessage[key], this, { anim: 5, time: 0, isOutAnim: false, maxWidth: 500 });
        $(this).mouseleave(function () {
          layer.close(tip);
        });
      });
    }

    // 重置 HTML 解析过滤标签
    $('i#hf-reset').mouseover(function () {
      const tip = layer.tips('点击恢复默认', this, { anim: 5, time: 0, isOutAnim: false, maxWidth: 500 });
      $(this).click(function () {
        $('input[name="htmlfilter"]').val('style,script,iframe|on*');
      });
      $(this).mouseleave(function () {
        layer.close(tip);
      });
    });

    // 赞赏码
    $('button#donation').click(function () {
      layer.open({
        type: 1,
        title: '以下为微信赞赏码，请打开微信扫一扫',
        maxWidth: 460,
        content: $('#donation_layer')
      });
    });
  });
});
