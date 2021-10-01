<?php 
require( '../../../../../wp-load.php' );
//获取发表表单
$user_id=$current_user->ID;
$type=$_POST['type'];
$topic_name=$_POST['topic_name'];
$city=get_user_meta($user_id,'city',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
$publish_credit = (int)jinsom_get_option('jinsom_publish_post_credit');//每次发表动态可获得的金币
jinsom_update_ip($user_id);//更新定位
$pending=jinsom_get_option('jinsom_publish_music_pending');
if($pending&&!jinsom_is_admin($user_id)){
$publish_text='提交审核';
}else{
$publish_text='发表';
}
if($type=='music'){?>
<form class="jinsom-publish-words-form pop" id="jinsom-upload-music-form" method="post" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/module/upload/music.php">
<div class="content">
<textarea id="jinsom-pop-content" name="content"></textarea>
<!-- 表情 -->
<span class="jinsom-single-expression-btn publish">
<i class="jinsom-icon expression jinsom-weixiao-" onclick="jinsom_smile(this,'normal','')"></i>
</span>
<!-- 工具栏 -->
<div class="jinsom-publish-words-bar">
<span class="title" onclick="jinsom_publish_show_title(this)"><?php _e('标题','jinsom');?></span>
<span class="topic" onclick="jinsom_publish_topic_form()"><i class="jinsom-icon jinsom-huati"></i></span>
<span class="power" onclick="jinsom_publish_power_form('music')"><i class="jinsom-icon jinsom-gongkai1" title="<?php _e('公开','jinsom');?>" data="0"></i></span>
<span class="comment" onclick="jinsom_publish_comment_status(this)"><i class="jinsom-icon ok jinsom-quxiaojinzhi-" title="<?php _e('允许评论','jinsom');?>"></i></span>
<?php if(!get_user_meta($user_id,'publish_city',true)&&jinsom_get_option('jinsom_location_on_off')!='no'){?>
<span class="city" onclick="jinsom_publish_city(this)"><i class="jinsom-icon jinsom-xiazai19"></i> <m><?php echo $city;?></m></span>
<input type="hidden" id="jinsom-pop-city" name="city" value="<?php echo $city;?>">
<?php }?>
</div>
</div>


<!-- 权限模块 -->
<div class="jinsom-publish-words-power layui-form">
</div>

<!-- 音乐模块 -->
<div class="jinsom-music-progress">
<span class="jinsom-music-bar"></span>
<span class="jinsom-music-percent">0%</span>
</div>

<div class="jinsom-publish-set-music">
<div class="jinsom-publish-set-music-input">
<input type="text" id="jinsom-music-url" name="music-url" placeholder="<?php _e('贴入mp3后缀的地址或本地上传','jinsom');?>">
</div>
<div class="jinsom-publish-set-music-upload"><i class="jinsom-icon jinsom-shangchuan"></i> <?php _e('本地上传','jinsom');?>
<input id="jinsom-upload-music" type="file" name="file" title="<?php _e('点击上传音乐','jinsom');?>">
</div>	
</div>



<!-- 话题列表 -->
<div class="jinsom-publish-words-topic pop">
<?php if(!empty($topic_name)){echo '<span data="'.$topic_name.'">#'.$topic_name.'#</span>';}?>	
</div>


<input type="hidden" id="jinsom-pop-power" name="power" value="0">
<input type="hidden" id="jinsom-pop-comment-status" name="comment-status" value="open">
<div class="jinsom-publish-words-btn">
<div class="cancel opacity"><?php _e('取消','jinsom');?></div>


<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<div class="publish opacity" id="publish-3"><?php echo $publish_text;if($publish_credit<0&&!$pending){echo '（'.$publish_credit.$credit_name.'）';}?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('publish-3'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_publish_music(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div class="publish opacity" onclick="jinsom_publish_music('','')"><?php echo $publish_text;if($publish_credit<0&&!$pending){echo '（'.$publish_credit.$credit_name.'）';}?></div>
<?php }?>


</div>


</form>
<?php }