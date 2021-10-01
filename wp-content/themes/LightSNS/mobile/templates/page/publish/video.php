<?php 
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$city=get_user_meta($user_id,'city',true);
$topic=$_GET['topic'];
jinsom_update_ip($user_id);//更新定位
$application_add=jinsom_get_option('jinsom_publish_application_add');
?>
<div data-page="publish" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"></div>
<div class="right">

<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<a href="#" class="link icon-only" id="publish-video"><?php _e('发表','jinsom');?></a>
<?php }else{?>
<a href="#" class="link icon-only" onclick="jinsom_publish_music_video('video','','')"><?php _e('发表','jinsom');?></a>
<?php }?>

</div>
</div>
</div>



<div class="page-content jinsom-publish-words-form video">
<form id="jinsom-publish-form">
<div class="title"><input type="text" name="title" placeholder="<?php _e('标题','jinsom');?>"></div>
<div class="content">
<textarea name="content" placeholder="<?php echo __('来说点什么吧...','jinsom');?>" class="resizable jinsom-smile-textarea"></textarea>
<i onclick="jinsom_smile_form(this)" class="smile jinsom-icon expression jinsom-weixiao-"></i>
<div class="hidden-smile" style="display: none;"><?php echo jinsom_get_expression(2,0);?></div>
</div>

<?php if(!get_user_meta($user_id,'publish_city',true)&&jinsom_get_option('jinsom_location_on_off')!='no'){?>
<div class="city" onclick="jinsom_publish_city(this)"><i class="jinsom-icon jinsom-xiazai19"></i> <m><?php echo $city;?></m></div>
<input type="hidden" id="jinsom-pop-city" name="city" value="<?php echo $city;?>">
<?php }?>

<div class="power-content">
<input type="tel" name="price" class="price" placeholder="输入售价" style="display: none;">
<input type="text" name="password" class="password" placeholder="输入密码" class="" maxlength="20" style="display: none;">
</div>
<div class="topic clear">
<?php if($topic){ echo '<span onclick="$(this).remove();" data="'.$topic.'">#'.$topic.'#</span>';}?>
</div><!-- 话题列表 -->
<div class="tool">
<span onclick="$('.jinsom-publish-words-form .title').toggle();"><i class="jinsom-icon jinsom-biaoti"></i><p>标题</p></span>
<span onclick="jinsom_publish_power_form();" class="power"><i class="jinsom-icon jinsom-quanxian"></i><p>权限</p></span>
<span onclick="jinsom_publish_add_topic_form()"><i class="jinsom-icon jinsom-huati"></i><p><?php echo jinsom_get_option('jinsom_topic_name');?></p></span>
<span class="open-popup" data-popup=".jinsom-publish-aite-popup"><i class="jinsom-icon jinsom-aite2"></i><p>@好友</p></span>
<span onclick="jinsom_publish_select_comment_power(this)"><i class="jinsom-icon jinsom-quxiaojinzhi-"></i><p>评论</p></span>
</div>

<?php if($application_add){?>
<div class="add-application" onclick="jinsom_publish_add_application()">
<div class="left"><i class="jinsom-icon jinsom-yingyongkuai"></i> <span>添加应用</span></div>
<div class="right">商品、网址等 <i class="jinsom-icon jinsom-arrow-right"></i></div>
</div>
<?php }?>

<input type="hidden" id="jinsom-pop-power" name="power" value="0">
<input type="hidden" id="jinsom-pop-comment-status" name="comment-status" value="open">
<input type="text" id="jinsom-video-url" name="video-url" placeholder="仅支持MP4,m3u8,flv,mov格式">
<input type="hidden" id="jinsom-video-time" name="video-time" value="30">
<input type="hidden" id="jinsom-video-img-url" name="video-img-url">
<input type="hidden" id="jinsom-publish-application-type" name="application-type">
<input type="hidden" id="jinsom-publish-application-value" name="application-value">
</form>

<div class="jinsom-publish-video-demo">
<form id="jinsom-upload-video-form" method="post" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/module/upload/video.php">
<div class="jinsom-upload-video-btn">
<div class="percent"></div>
<div class="center">
<i class="jinsom-icon jinsom-shipin1"></i>
<p>选择一个视频 (不能超过<?php echo jinsom_get_option('jinsom_upload_publish_video_max');?>M)</p>
</div>
</div>
<input id="jinsom-upload-video" type="file" name="file" accept="video/*">
</form>

<div id="jinsom-publish-video-demo"></div>
<div class="jinsom-remove-video-toolbar">
<span class="read open-popup" data-popup=".jinsom-publish-video-set-cover">截取封面</span>
<span class="del">移除视频</span>
</div>

</div>

<?php echo do_shortcode(jinsom_get_option('jinsom_publish_mobile_footer_html'));?>



</div>

<!-- 设置视频封面 -->
<div class="popup jinsom-publish-video-set-cover">
<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="link icon-only close-popup"><i class="jinsom-icon jinsom-xiangxia2"></i></a>
</div>
<div class="center">封面预览</div>
<div class="right">
<a href="#" class="link icon-only close-popup" id="jinsom-publish-remove-video-cover">移除</a>
</div>
</div>
</div>
<div class="page-content jinsom-publish-video-set-cover-content">
</div>
</div>


<div class="jinsom-publish-power-list-form" style="display: none;">
<div class="jinsom-popup-half-header"><div class="title">内容权限</div><div class="close" onclick="layer.closeAll()"><i class="jinsom-icon jinsom-guanbi"></i></div></div>
<div class="jinsom-publish-power-list clear">
<li type="open" class="on">公开内容</li>
<?php 
$jinsom_publish_power=jinsom_get_option('jinsom_video_power_sorter_a');
$enabled=$jinsom_publish_power['enabled'];
foreach ($enabled as $key => $value) {
if($key=='pay'){
$data=1;
}else if($key=='password'){
$data=2;
}else if($key=='private'){
$data=3;
}else if($key=='vip'){
$data=4;
}else if($key=='login'){
$data=5;
}else if($key=='comment'){
$data=6;
}else if($key=='verify'){
$data=7;
}else if($key=='follow'){
$data=8;
}
echo '<li type="'.$key.'" data="'.$data.'">'.$value.'</li>';
}
?>
</div>
</div>
<?php require(get_template_directory().'/mobile/templates/page/publish/popup.php');?>
</div>   

