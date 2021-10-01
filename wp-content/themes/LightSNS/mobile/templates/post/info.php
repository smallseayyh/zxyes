<div class="header clear">
<div class="avatars">
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="link">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
<?php echo jinsom_verify($author_id);?>
</a>
</div>
<div class="info">
<div class="name">
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="link">
<?php echo jinsom_nickname($author_id);?>
<?php echo jinsom_lv($author_id);?>
<?php echo jinsom_vip($author_id);?>
<?php echo jinsom_honor($author_id);?>
</a>
</div>
<div class="other">
<span class="time">
<?php
$time_a=time ()- ( 1  *  24  *  60  *  60 );
if(get_the_time('Y-m-d')==date('Y-m-d',time())){
echo get_the_time('H:i');
}else if(get_the_time('Y-m-d')==date('Y-m-d',$time_a)){
echo __('昨天','jinsom').' '.get_the_time('H:i');
}else{
echo get_the_time('m-d H:i');
}
?>
</span>
<?php echo jinsom_post_from($post_id);?>
<span class="type">
<?php 
if($is_bbs_post){//帖子
echo '<a href="'.jinsom_mobile_bbs_url($child_cat_id).'" class="link">'.$child_name.'</a>';
}else{//非帖子
$post_power=get_post_meta($post_id,'post_power',true);
$reprint=get_post_meta($post_id,'reprint_post_id',true);
if($reprint){
echo __('转发','jinsom');
}else{
if($post_power==1){
echo __('付费内容','jinsom');
}else if($post_power==2){
echo __('密码内容','jinsom');
}else if($post_power==3){
echo __('私密内容','jinsom');
}else if($post_power==4){
echo 'VIP';
}else if($post_power==5){
echo __('登录可见','jinsom');
}else if($post_power==6){
echo __('回复可见','jinsom');
}else if($post_power==7){
echo __('认证可见','jinsom');
}else if($post_power==8){
echo __('粉丝可见','jinsom');
}else{
echo __('公开内容','jinsom');
}
}
}
?>
</span>
</div>
</div>
</div>