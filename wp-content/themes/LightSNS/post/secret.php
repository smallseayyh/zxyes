<?php 
$post_id=get_the_ID();
$content=get_the_content();
$time=jinsom_timeago(get_the_time('Y-m-d H:i:s'));
$color=get_post_meta($post_id,'secret_color',true);
$secret_name=get_post_meta($post_id,'secret_name',true);
$secret_avatar=get_post_meta($post_id,'secret_avatar',true);
$nice_num=(int)get_post_meta($post_id,'nice_num',true);
$nice=get_post_meta($post_id,'nice',true);
if($nice){
$nice_arr=explode(",",$nice);
if(in_array($user_id,$nice_arr)){
$nice_status=1;
}else{
$nice_status=0;	
}
}else{
$nice_status=0;
}

$topic_arr=wp_get_post_tags($post_id);
if($topic_arr){
foreach($topic_arr as $data){
$topic='#'.$data->name;
}
}else{
$topic='';
}



$link=get_template_directory_uri().'/mobile/templates/page/post-secret.php?post_id='.$post_id;
?>
<li class="box" id="jinsom-secret-<?php echo $post_id;?>">
<div class="left">
<div class="avatarimg"><img src="<?php echo $secret_avatar;?>" class="avatar"></div>
<div class="name"><?php echo $secret_name;?></div>
<div class="time"><?php echo $topic;?></div>
</div>
<div class="right">
<div class="content" style="background:<?php echo $color;?>;">
<a href="<?php echo $link;?>" class="link">
<?php 
$content_number=mb_strlen($content,'utf-8');
if($content_number>200){
echo convert_smilies(mb_substr($content,0,200,'utf-8')).'......';	
}else{
echo convert_smilies($content);
}
?>
</a>
</div>
<div class="bar">
<?php if($nice_status){?>
<span class="had"><i class="jinsom-icon jinsom-youzan"></i> <n><?php echo $nice_num;?></n></span>
<?php }else{?>
<span onclick="jinsom_like_secret(<?php echo $post_id;?>,this)"><i class="jinsom-icon jinsom-youzan"></i> <n><?php echo $nice_num;?></n></span>
<?php }?>	
<span><a href="<?php echo $link;?>" class="link"><i class="jinsom-icon jinsom-xiaoxizhongxin"></i> <?php comments_number('0','1','%'); ?></a></span>	
</div>
</div>
</li>