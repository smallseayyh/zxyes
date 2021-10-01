<?php 
require( '../../../../../wp-load.php' );
//动态编辑表单
$user_id=$current_user->ID;
$post_id=$_POST['post_id'];
$post_data=get_post($post_id,ARRAY_A);
$title=$post_data['post_title'];
$content=$post_data['post_content'];
$post_img=get_post_meta($post_id,'post_img',true);
$post_thum=get_post_meta($post_id,'post_thum',true);
$post_power=(int)get_post_meta($post_id,'post_power',true);
$hide_content=get_post_meta($post_id,'pay_cnt',true);//隐藏的内容
$pay_img_on_off=get_post_meta($post_id,'pay_img_on_off',true);//没有权限是否也可以浏览图片
if(!$pay_img_on_off){$pay_img_on_off=0;}
if(!is_numeric($pay_img_on_off)){
$pay_img_on_off=count(explode(",",$post_img));
}
$price=get_post_meta($post_id,'post_price',true);//售价
$password=get_post_meta($post_id,'post_password',true);//密码	
$topics = wp_get_post_tags($post_id);
$pending=jinsom_get_option('jinsom_publish_words_pending');
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
<span title="添加图片" class="jinsom-publish-words-upload"><i class="jinsom-icon jinsom-tupian1"></i></span>
<span class="power" onclick="jinsom_publish_power_form('words')">
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

<!-- 图片列表 -->
<?php if($post_img){?>
<div class="jinsom-publish-words-image clear" style="display: block;">
<?php 
$img_arr=explode(",",$post_img);
$img_thum_arr=explode(",",$post_thum);
$i=0;
foreach ($img_arr as $img_arrs) {
echo '<li><a href="'.$img_arrs.'" data-fancybox="publish-gallery"><img src="'.$img_thum_arr[$i].'" class="img"></a><div class="bar"><i class="jinsom-icon jinsom-fanhui2" onclick="jinsom_img_left(this)"></i><i class="jinsom-icon jinsom-bangzhujinru" onclick="jinsom_img_right(this)"></i><i class="jinsom-icon jinsom-guanbi" onclick="jinsom_remove_publish_img(this)"></i></div></li>';
$i++;
}
?>
<span class="jinsom-upload-add-icon jinsom-publish-words-upload">+</span>
</div>
<?php }else{?>
<div class="jinsom-publish-words-image clear">
<span class="jinsom-upload-add-icon jinsom-publish-words-upload">+</span>
</div>
<?php }?>

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
<span class="img-power"><i>前</i><input type="number" class="number" name="power-see-img" value="<?php echo $pay_img_on_off;?>"><i>张图片免费</i></span>
<textarea placeholder="请输入隐藏内容" name="hide-content"><?php echo $hide_content;?></textarea>
</div>
<?php }?>
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
<div class="publish opacity" onclick="jinsom_editor_words(<?php echo $post_id;?>,this)"><?php echo $publish_text;?></div>
</div>


</form>