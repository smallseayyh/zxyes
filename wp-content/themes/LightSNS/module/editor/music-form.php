<?php 
require( '../../../../../wp-load.php' );
//音乐编辑表单
$user_id=$current_user->ID;
$post_id=$_POST['post_id'];
$post_data=get_post($post_id,ARRAY_A);
$title=$post_data['post_title'];
$content=$post_data['post_content'];
$post_img=get_post_meta($post_id,'post_img',true);
$post_thum=get_post_meta($post_id,'post_thum',true);
$post_power=(int)get_post_meta($post_id,'post_power',true);
$music_url=get_post_meta($post_id,'music_url',true);//音乐地址
$power_download=get_post_meta($post_id,'power_download',true);//是否允许下载
$price=get_post_meta($post_id,'post_price',true);//售价
$password=get_post_meta($post_id,'post_password',true);//密码	
$topics = wp_get_post_tags($post_id);
$pending=jinsom_get_option('jinsom_publish_music_pending');
if($pending&&!jinsom_is_admin($user_id)){
$publish_text='提交审核';
}else{
$publish_text='更新';
}
?>
<form class="jinsom-publish-words-form pop" id="jinsom-upload-music-form" method="post" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/module/upload/music.php">
<?php if(!empty($title)){echo '<input id="jinsom-pop-title" placeholder="'.__('标题','jinsom').'" name="title" value="'.$title.'">';}?>
<div class="content">
<textarea id="jinsom-pop-content" name="content"><?php echo strip_tags($content);?></textarea>
<!-- 表情 -->
<span class="jinsom-single-expression-btn publish">
<i class="jinsom-icon expression jinsom-weixiao-" onclick="jinsom_smile(this,'normal','')"></i>
</span>
<!-- 工具栏 -->
<div class="jinsom-publish-words-bar">
<span class="title <?php if(!empty($title)){echo 'on';}?>" onclick="jinsom_publish_show_title(this)"><?php _e('标题','jinsom');?></span>
<span class="topic <?php if($topics){echo 'on';}?>" onclick="jinsom_publish_topic_form()"><i class="jinsom-icon jinsom-huati"></i></span>
<span class="power" onclick="jinsom_publish_power_form('music')">
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
<?php 
if($post_power==1||$post_power==2||$post_power==4||$post_power==5||$post_power==6||$post_power==7||$post_power==8){?>
<div class="jinsom-publish-words-power-content">
<?php if($post_power==1){?>
<input placeholder="设置售价" type="number" class="price" name="price" value="<?php echo $price;?>"> <i style="margin-right:20px;"><?php echo jinsom_get_option('jinsom_credit_name');?></i>
<?php }?>
<?php if($post_power==2){?>
<input placeholder="设置密码" type="text" class="password" name="password" maxlength="20" value="<?php echo $password;?>" style="margin-right:20px;">
<?php }?>
<span><input type="checkbox" lay-skin="switch" lay-text="开|关" name="power-download" <?php if($power_download){echo 'checked=""';}?>><i>开启后，音频允许下载</i></span>
</div>
<?php }?>
</div>

<!-- 音乐模块 -->
<div class="jinsom-music-progress">
<span class="jinsom-music-bar"></span>
<span class="jinsom-music-percent">0%</span>
</div>

<div class="jinsom-publish-set-music">
<div class="jinsom-publish-set-music-input">
<input type="text" id="jinsom-music-url" name="music-url" placeholder="贴入mp3后缀的地址或本地上传，不支持网易云、QQ音乐等" value="<?php echo $music_url;?>">
</div>
<div class="jinsom-publish-set-music-upload"><i class="jinsom-icon jinsom-shangchuan"></i> 本地上传
<input id="jinsom-upload-music" type="file" name="file" title="点击上传音乐">
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


<input type="hidden" id="jinsom-pop-power" name="power" value="<?php echo $post_power;?>">
<input type="hidden" id="jinsom-pop-comment-status" name="comment-status" value="<?php if(comments_open($post_id)){echo 'open';}else{echo 'closed';}?>">
<div class="jinsom-publish-words-btn">
<div class="cancel opacity">取消</div>
<div class="publish opacity" onclick="jinsom_editor_music(<?php echo $post_id;?>)"><?php echo $publish_text;?></div>
</div>


</form>