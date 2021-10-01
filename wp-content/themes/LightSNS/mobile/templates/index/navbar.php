<div class="left">
<?php 
//头部左侧
$user_id=$current_user->ID;
$verify=jinsom_verify($user_id);
if($data['header_left_type']=='avatar'){
if(is_user_logged_in()){
$left_link=do_shortcode($data['header_left_link']);
$left_html=$avatar.$verify;
}else{
$left_link='javascript:myApp.loginScreen();';
$left_html=$avatar;
}
}else if($data['header_left_type']=='search'){
$left_link=$theme_url.'/mobile/templates/page/search.php';
$left_html='<i class="jinsom-icon jinsom-sousuo1"></i>';
}else if($data['header_left_type']=='custom'){
$left_link=do_shortcode($data['header_left_link']);
$left_html=do_shortcode($data['header_left_custom']);
}else{
$left_link='';
$left_html='';
}
echo '<a href="'.$left_link.'" class="link icon-only">'.$left_html.'</a>';
?>
</div>
<div class="center">
<?php echo do_shortcode($data['header_name']);?>
<?php if($data['jinsom_mobile_tab_type']=='notice'){echo '<i style="vertical-align:-0.5vw;" class="jinsom-icon jinsom-qingchu" onclick="jinsom_clear_notice()"></i>';}?>
</div>
<div class="right">
<?php 
//头部右侧
if($data['header_right_type']=='avatar'){
if(is_user_logged_in()){
$right_link=do_shortcode($data['header_right_link']);
$right_html=$avatar.$verify;
}else{
$right_link='javascript:myApp.loginScreen();';
$right_html=$avatar;
}
}else if($data['header_right_type']=='search'){
$right_link=$theme_url.'/mobile/templates/page/search.php';
$right_html='<i class="jinsom-icon jinsom-sousuo1"></i>';
}else if($data['header_right_type']=='custom'){
$right_link=do_shortcode($data['header_right_link']);
$right_html=do_shortcode($data['header_right_custom']);
}else{
$right_link='';
$right_html='';
}
echo '<a href="'.$right_link.'" class="link icon-only">'.$right_html.'</a>';
?>
</div>