<?php  /* Template Name:公共底部 */  ?>
		<div id="divBottom">
			<h4 id="BlogPowerBy">Powered By <?php  echo $zblogphphtml;  ?></h4>
			<h3 id="BlogCopyRight"><?php  echo $copyright;  ?></h3>
		</div><div class="clear"></div>
	</div><div class="clear"></div>
	</div><div class="clear"></div>
</div>
<?php  echo $footer;  ?><div title="要回到上边么？" class="acgTop fade"></div><script src="https://cz.caozhexxgweb.cn/zb_users/plugin/acgTop/common/script.js?v=2020-05-20"></script><div id="landlord">
  <div class="message"></div>
  <canvas id="live2d" width="500" height="560" class="live2d"></canvas>
  <!--
  <div class="live_talk_input_body">
    <div class="live_talk_input_name_body">
      <input name="name" type="text" class="live_talk_name white_input" id="AIuserName" autocomplete="off"
        placeholder="你的名字" />
    </div>
    <div class="live_talk_input_text_body">
      <input name="talk" type="text" class="live_talk_talk white_input" id="AIuserText" autocomplete="off"
        placeholder="要和我聊什么呀?" />
      <button type="button" class="live_talk_send_btn" id="talk_send">
        发送
      </button>
    </div>
  </div>
  -->
  <input name="live_talk" id="live_talk" value="1" type="hidden" />
  <div class="live_ico_box">
    <div class="live_ico_item type_info" id="showInfoBtn"></div>
    <div class="live_ico_item type_talk" id="showTalkBtn"></div>
    <div class="live_ico_item type_music" id="musicButton"></div>
    <div class="live_ico_item type_youdu" id="youduButton"></div>
    <div class="live_ico_item type_quit" id="hideButton"></div>
    <input name="live_statu_val" id="live_statu_val" value="0" type="hidden" />
    
    <input id="duType" value="douqilai,l2d_caihong" type="hidden" />
  </div>
</div>
<div id="open_live2d">召唤伊斯特瓦尔</div>
<script>
    var message_Path = 'https://cz.caozhexxgweb.cn/zb_users/plugin/live2d2/usr/';
    // var model_Name = 'nep';
    var model_Path = 'https://cz.caozhexxgweb.cn/zb_users/plugin/live2d2/var/model/nep/';
    var model_textures = ["nep.1024\/texture_00.png","nep.1024\/texture_01.png","nep.1024\/texture_02.png"]; // 贴图数组
    var home_Path = 'https://cz.caozhexxgweb.cn/';  // 此处修改为你的域名，必须带斜杠
    var talkAPI = "";
</script>
<script src="https://cz.caozhexxgweb.cn/zb_users/plugin/live2d2/var/js/live2d.js?v=2020-06-07"></script>
<script src="https://cz.caozhexxgweb.cn/zb_users/plugin/live2d2/var/js/message.js?v=2020-06-07"></script>
</body>
</html>