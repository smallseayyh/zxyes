<?php
//动态
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$author_id=get_the_author_meta('ID');
$post_id=get_the_ID();
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);
$post_img=get_post_meta($post_id,'post_img',true);
$post_power=get_post_meta($post_id,'post_power',true);
$music_url=get_post_meta($post_id,'music_url',true);

$rand=rand(1000000,99999999);

$content=strip_tags(jinsom_get_post_content($post_id));
$content_number=mb_strlen($content,'utf-8');
$fold_number = jinsom_get_option('jinsom_mobile_content_more_fold_number');

?>
<div class="jinsom-post-words music jinsom-post-<?php echo $post_id;?> jinsom-post-author-<?php echo $author_id;?>">
<?php require( get_template_directory() . '/mobile/templates/post/info.php' );?>
<div class="content <?php if($content_number>$fold_number){ echo 'hidden';} ?>">
<?php if(get_the_title()){?>
<h1><a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link"><?php the_title();?></a></h1>
<?php }?>


<?php if($post_power==1){//付费 
$pay_result=jinsom_get_pay_result($user_id,$post_id);//是否付费
if($pay_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经付费的用户
?>
<div onclick="jinsom_buy_post_form(<?php echo $post_id;?>)" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>
<?php }else{?>
<div onclick="jinsom_play_music(<?php echo $post_id;?>,this)" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>
<?php }
}else if($post_power==2){//密码
$password_result=jinsom_get_password_result($user_id,$post_id);//是否输入密码
if($password_result==0&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、已经输入密码的人
?>
<div onclick="layer.open({content:'暂未开启！',skin:'msg',time:2});" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>
<?php }else{?>
<div onclick="jinsom_play_music(<?php echo $post_id;?>,this)" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>

<?php }}else if($post_power==4){//VIP
if(!is_vip($user_id)&&$author_id!=$user_id&&!jinsom_is_admin($user_id)){//非管理团队、作者本人、VIP用户
?>
<div onclick="jinsom_recharge_vip_type_form()" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>
<?php }else{?>
<div onclick="jinsom_play_music(<?php echo $post_id;?>,this)" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>

<?php }}else if($post_power==5){//登录
if(!is_user_logged_in()){//没有登录的用户
?>
<div class="jinsom-music-voice open-login-screen jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>
<?php }else{?>
<div onclick="jinsom_play_music(<?php echo $post_id;?>,this)" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>

<?php }}else{?>
<div onclick="jinsom_play_music(<?php echo $post_id;?>,this)" class="jinsom-music-voice jinsom-music-voice-<?php echo $post_id;?>"><i class="jinsom-icon jinsom-yuyin1"> </i>点击播放</div>
<?php }?>

<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link">
<?php echo do_shortcode(convert_smilies(jinsom_autolink(wpautop(get_the_content()))));?>
</a>
</div>

<?php if($content_number>$fold_number){echo"<div class='jinsom-post-read-more' onclick='jinsom_moren_content(this)''>查看全文</div>";}?>
<?php require( get_template_directory() . '/mobile/templates/post/bar.php' );?>
</div>

