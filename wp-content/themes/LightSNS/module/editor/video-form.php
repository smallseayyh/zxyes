<?php 
require( '../../../../../wp-load.php' );
//编辑视频表单
$user_id=$current_user->ID;
$type=$_POST['type'];
$post_id=$_POST['post_id'];
$post_data=get_post($post_id,ARRAY_A);
$title=$post_data['post_title'];
$content=$post_data['post_content'];
$video_url=get_post_meta($post_id,'video_url',true);//视频地址
$video_img=get_post_meta($post_id,'video_img',true);//视频封面
$video_time=get_post_meta($post_id,'video_time',true);//视频时长
$video_lists=get_post_meta($post_id,'video_lists',true);//视频集数
$post_power=(int)get_post_meta($post_id,'post_power',true);
$price=get_post_meta($post_id,'post_price',true);//售价
$password=get_post_meta($post_id,'post_password',true);//密码	
$topics = wp_get_post_tags($post_id);
$pending=jinsom_get_option('jinsom_publish_video_pending');
if($pending&&!jinsom_is_admin($user_id)){
$publish_text='提交审核';
}else{
$publish_text='更新';
}
?>
<form class="jinsom-publish-words-form pop">
<?php if(!empty($title)){echo '<input id="jinsom-pop-title" placeholder="标题" name="title" value="'.$title.'">';}?>
<div class="content">
<textarea id="jinsom-pop-content" name="content"><?php echo strip_tags($content);?></textarea>
<!-- 表情 -->
<span class="jinsom-single-expression-btn publish">
<i class="jinsom-icon expression jinsom-weixiao-" onclick="jinsom_smile(this,'normal','')"></i>
</span>
<!-- 工具栏 -->
<div class="jinsom-publish-words-bar">
<span class="title <?php if(!empty($title)){echo 'on';}?>" onclick="jinsom_publish_show_title(this)">标题</span>
<span class="topic <?php if($topics){echo 'on';}?>" onclick="jinsom_publish_topic_form()"><i class="jinsom-icon jinsom-huati"></i></span>
<span class="power" onclick="jinsom_publish_power_form('video')">
<?php 
if($post_power==1){
echo '<i class="jinsom-icon jinsom-fufei" title="付费内容" data="1"></i>';	
}else if($post_power==2){
echo '<i class="jinsom-icon jinsom-mima" title="密码内容" data="2"></i>';	
}else if($post_power==3){
echo '<i class="jinsom-icon jinsom-biyan" title="私密内容" data="3"></i>';	
}else if($post_power==4){
echo '<i class="jinsom-icon jinsom-vip-type" title="VIP可见" data="4"></i>';	
}else if($post_power==5){
echo '<i class="jinsom-icon jinsom-denglu" title="登录可见" data="5"></i>';	
}else if($post_power==6){
echo '<i class="jinsom-icon jinsom-pinglun2" title="'.__('回复可见','jinsom').'"></i>';
}else if($post_power==7){
echo '<i class="jinsom-icon jinsom-dagou" title="'.__('认证可见','jinsom').'"></i>';
}else if($post_power==8){
echo '<i class="jinsom-icon jinsom-qunzu" title="'.__('粉丝可见','jinsom').'"></i>';
}else{
echo '<i class="jinsom-icon jinsom-gongkai1" title="公开内容" data="0"></i>';	
}
?>
</span>
<span class="comment" onclick="jinsom_publish_comment_status(this)">
<?php if(comments_open($post_id)){?>
<i class="jinsom-icon ok jinsom-quxiaojinzhi-" title="允许评论"></i>
<?php }else{?>
<i class="jinsom-icon no jinsom-jinzhipinglun-" title="禁止评论"></i>
<?php }?>
</span>
</div>
</div>


<!-- 权限模块 -->
<div class="jinsom-publish-words-power layui-form">
<div class="jinsom-publish-words-power-content">
<?php if($post_power==1){?>
<input placeholder="设置售价" type="number" class="price" name="price" value="<?php echo $price;?>"> <i><?php echo jinsom_get_option('jinsom_credit_name');?></i>
<?php }?>
<?php if($post_power==2){?>
<input placeholder="设置密码" type="text" class="password" name="password" maxlength="20" value="<?php echo $password;?>">
<?php }?>
</div>
</div>

<input type="hidden" id="jinsom-video-time" name="video-time" value="<?php echo $video_time;?>">
<input type="hidden" id="jinsom-pop-power" name="power" value="<?php echo $post_power;?>">
<input type="hidden" id="jinsom-pop-comment-status" name="comment-status" value="<?php if(comments_open($post_id)){echo 'open';}else{echo 'closed';}?>">

<?php if(jinsom_is_admin($user_id)){?>
<div class="jinsom-publish-video-lists">
<textarea placeholder="集数：仅管理员可以设置，格式：第一集|文章ID,第二集|文章ID,第三集|文章ID  注意：是用英文逗号隔开，每个视频只能在一个合集里引用。" name="video_lists"><?php echo $video_lists;?></textarea>
</div>
<?php }?>

</form>

<!-- 视频模块 -->
<div class="jinsom-video-progress">
<span class="jinsom-video-bar"></span>
<span class="jinsom-video-percent">0%</span>
</div>

<div class="jinsom-publish-set-video">
<div class="jinsom-publish-set-video-input">
<input type="text" id="jinsom-video-url" name="video-url" placeholder="仅支持MP4，m3u8，flv，mov格式" value="<?php echo $video_url;?>">
</div>
<div class="jinsom-publish-set-video-upload"><i class="jinsom-icon jinsom-shangchuan"></i> 上传视频
<form id="jinsom-upload-video-form" method="post" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/module/upload/video.php">
<input id="jinsom-upload-video" type="file" name="file" title="点击上传视频">
</form>
</div>	
</div>

<!-- 视频封面 -->
<div class="jinsom-publish-set-video-img">
<div class="jinsom-publish-set-video-img-input">
<input type="text" id="jinsom-video-img-url" name="video-img-url" placeholder="请上传视频封面" value="<?php echo $video_img;?>">
</div>
<div class="jinsom-publish-set-video-img-upload"><i class="jinsom-icon jinsom-shangchuan"></i> 上传封面
</div>	
</div>







<!-- 话题列表 -->
<div class="jinsom-publish-words-topic pop">
<?php 
if($topics){
foreach ($topics as $topic) {
echo '<span data="'.$topic->name.'">#'.$topic->name.'#</span>';
}
}
?>
</div>


<div class="jinsom-publish-words-btn">
<div class="cancel opacity">取消</div>
<div class="publish opacity" onclick="jinsom_editor_video(<?php echo $post_id;?>)"><?php echo $publish_text;?></div>
</div>

