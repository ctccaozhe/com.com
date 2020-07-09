/**
 * 多主题后台管理脚本
 *
 * @author  心扬 <chrishyze@163.com>
 */

//模块化加载layui
layui.use(['layer', 'form', 'element'], function () {
    var layer = layui.layer;
    var form = layui.form;
    var element = layui.element;
    var cid; // 客户端ID
    var layid; // 标签ID

    /**
     * 获取主题列表
     * @param {number} cid 客户端识别码
     * @param {string} layout tab对应内容ID
     */
    MULTITHEME.FUNCTIONS.getThemes = function (cid, layout) {
        //$(layout).html('<div id="loadingicon" style="text-align:center"><i class="layui-icon layui-icon layui-anim layui-anim-rotate layui-anim-loop" style="font-size: 30px;">&#xe63d;</i></div>');
        $(layout).html('<div class="mt-loading-theme"><i class="layui-icon layui-icon-loading layui-anim layui-anim-rotate layui-anim-loop"></i></div>');
        $.get(MULTITHEME.HOME_URL + 'zb_users/plugin/MultiThemes/setting.php?action=get&cid=' + cid + '&csrfToken=' + MULTITHEME.CSRF_TOKEN, function (result) {
            if (!result[0]) {
                $(layout).html('<div class="mt-loading-theme">获取主题失败！' + result[1] + '</div>');
                return;
            }
            $(layout).html('');
            $.each(result, function (index, content) {
                var styles;
                for (x in content.css) {
                    if (content.css[x] == content.style) {
                        styles += '<option value="' + content.css[x] + '" selected="selected">' + content.css[x] + '</option>';
                    } else {
                        styles += '<option value="' + content.css[x] + '">' + content.css[x] + '</option>';
                    }
                }

                var actived = content.actived ? '<button class="layui-btn layui-btn-radius layui-btn-danger" onclick="MULTITHEME.FUNCTIONS.setTheme(' + cid + ',\'' + content.id + '\')"><i class="layui-icon" style="font-size:2rem;color:#FFFFFF">&#xe605;</i>已启用，点击更换样式</button>' : '<button class="layui-btn layui-btn-radius layui-btn-normal" onclick="MULTITHEME.FUNCTIONS.setTheme(' + cid + ',\'' + content.id + '\')">点击启用主题</button>';

                var card = '<div class="card"><div class="card-image"><img src="' + content.img + '"/></div><div class="card-header">' + content.name + '</div><div class="card-body"><label>样式</label><select name="' + content.id + '">' + styles + '</select></div><div class="card-footer">' + actived + '</div></div>';

                $(layout).append(card);
            });
        });
    }

    /**
     * 设置主题
     * @param {number} cid 客户端识别码
     * @param {string} 主题ID
     */
    MULTITHEME.FUNCTIONS.setTheme = function (cid, theme) {
        var style = $("select[name='" + theme + "']").val();
        $.get(MULTITHEME.HOME_URL + 'zb_users/plugin/MultiThemes/setting.php?action=set&cid=' + cid + '&theme=' + theme + '&style=' + style + '&csrfToken=' + MULTITHEME.CSRF_TOKEN, function (result) {
            layer.open({
                title: '操作提示',
                content: result[1],
                shadeClose: true,
                time: 3500,
                success: function (layero, index) {
                    document.querySelectorAll('.layui-layer-shade')[0].style.opacity = '0.75';
                    MULTITHEME.FUNCTIONS.getThemes(cid, 'div#' + layid);
                },
                yes: function (index, layero) {
                    layer.close(index);
                }
            });
        });
    }

    // 预加载主题
    MULTITHEME.FUNCTIONS.getThemes(1, 'div#mt_pc');
    MULTITHEME.FUNCTIONS.getThemes(2, 'div#mt_mobi');
    MULTITHEME.FUNCTIONS.getThemes(3, 'div#mt_tablet');
    MULTITHEME.FUNCTIONS.getThemes(4, 'div#mt_android');
    MULTITHEME.FUNCTIONS.getThemes(5, 'div#mt_ios');

    //获取hash来切换选项卡，假设当前地址的hash为lay-id对应的值
    layid = location.hash.replace(/^#tabs=/, '');
    element.tabChange('tabs', layid);

    //初始化客户端ID
    cid = $("[lay-id='" + layid + "']").attr("client-id");

    //监听Tab切换，以改变地址hash值
    element.on('tab(tabs)', function () {
        if (cid < 6) {
            MULTITHEME.FUNCTIONS.getThemes(cid, 'div#' + layid);
        }
        location.hash = 'tabs=' + this.getAttribute('lay-id');
        //更新ID
        cid = this.getAttribute('client-id');
        layid = this.getAttribute('lay-id');
    });

    //监听提交
    form.on('submit(formSubmit)', function (data) {
        $.get(MULTITHEME.HOME_URL + 'zb_users/plugin/MultiThemes/setting.php?action=config&fields=' + JSON.stringify(data.field) + '&csrfToken=' + MULTITHEME.CSRF_TOKEN, function (result) {
            layer.open({
                title: '操作提示',
                content: result[1],
                shadeClose: true,
                time: 3500,
                yes: function (index, layero) {
                    location.reload();
                }
            });
        });

        return false;
    });
});
