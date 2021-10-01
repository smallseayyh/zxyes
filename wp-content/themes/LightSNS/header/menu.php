<!-- 菜单 -->
<?php 
$jinsom_logo = jinsom_get_option('jinsom_logo');
$search_on_off = jinsom_get_option('jinsom_header_search_on_off');
$notice_on_off = jinsom_get_option('jinsom_header_notice_on_off');
$publish_on_off = jinsom_get_option('jinsom_header_publish_on_off');
$header_vip_ico_on_off = jinsom_get_option('jinsom_header_vip_ico_on_off');

if(empty($jinsom_logo)){
$jinsom_logo='';
}else{
$jinsom_logo='style="background-image: url('.$jinsom_logo.');"';
}

?>

<?php if(jinsom_get_option('jinsom_header_type')=='custom'){
echo do_shortcode(jinsom_get_option('jinsom_header_custom_html'));
}else{
?>
<div class="jinsom-header">
<?php echo do_shortcode(jinsom_get_option('jinsom_header_nav_html'));?>
<div class="jinsom-header-content clear">
<div class="logo"><a href="<?php echo home_url();?>" <?php echo $jinsom_logo;?>><?php echo jinsom_get_option('jinsom_site_name');?></a></div>
<?php wp_nav_menu( array( 'theme_location' => 'header-menu','container_class'  => 'jinsom-menu','menu_class' => 'clear', 'fallback_cb' => 'default_menu' ) ); ?> 

<div class="jinsom-header-right">
<?php if($search_on_off){?><li class="search"><i class="jinsom-icon jinsom-sousuo1"></i></li><?php }?>
<?php 
$jinsom_languages_add=jinsom_get_option('jinsom_languages_add');
if(!is_user_logged_in()&&jinsom_get_option('jinsom_languages_on_off')){?>
<li class="language"><i class="jinsom-icon jinsom-yuyan1"></i>
<?php 
if($jinsom_languages_add){
echo '<ul>';
foreach ($jinsom_languages_add as $data) {
if(get_locale()==$data['code']){$on='class="on"';}else{$on='';}
echo '<li '.$on.' onclick=\'jinsom_change_language(this,"'.$data['code'].'")\'>'.$data['name'].'</li>';
}
echo '</ul>';
}?>
</li>
<?php }?>
<?php if(is_user_logged_in()){?>
<?php if($notice_on_off){?>    
<li id="jinsom-notice" class="jinsom-notice">
<?php if(jinsom_get_all_tips_number()>0){ echo '<span class="number"></span>';} ?>
<i class="jinsom-icon jinsom-tongzhi1"></i>
<ul>
<div class="jinsom-notice-title clear">
<li class="on">
<i class="jinsom-icon jinsom-caidan"></i>
</li>
<li>
<?php if(jinsom_get_follow_tips_number()>0){?>
<span class="tip"></span>
<?php }?>
<i class="jinsom-icon jinsom-guanzhu3"></i>
</li>
<li>
<?php if(jinsom_get_like_tips_number()>0){?>
<span class="tip"></span>
<?php }?>
<i class="jinsom-icon jinsom-guanzhu4"></i>
</li>
</div>
<div class="jinsom-notice-content">
<div class="a"></div>
<div class="b"></div>
<div class="c"></div>
</div>
<div class="jinsom-notice-bottom clear">
<?php if(jinsom_get_option('jinsom_email_style')!='close'&&jinsom_get_option('jinsom_mail_notice_on_off')){//如果开启了邮件?>
<span onclick="jinsom_emali_notice_form()"><i class="jinsom-icon jinsom-shezhi"></i> <?php _e('提醒设置','jinsom');?></span>
<?php }?>
<span onclick="jinsom_delete_notice();"><?php _e('全部清空','jinsom');?></span>
</div>
</ul>
</li>
<?php }?>

<?php 
$jinsom_publish_add=jinsom_get_option('jinsom_publish_add');
if($publish_on_off&&$jinsom_publish_add){
if(count($jinsom_publish_add)==1){
echo '<li class="publish" onclick=\'jinsom_publish_power("'.$jinsom_publish_add[0]['jinsom_publish_add_type'].'","")\'><i class="jinsom-icon jinsom-fabiao1"></i></li>';
}else{
echo '<li class="publish" onclick=\'jinsom_publish_type_form("")\'><i class="jinsom-icon jinsom-fabiao1"></i></li>';	
}
?>
<?php }?>

<li class="jinsom-header-menu-avatar">
<?php echo jinsom_avatar($user_id,'30',avatar_type($user_id) );?>
<p><?php echo get_user_meta($user_id,'nickname',true);?></p>
<?php if(is_vip($user_id)&&$header_vip_ico_on_off){echo '<span>VIP</span>';}?>
<i class="jinsom-icon jinsom-xiangxia2"></i>


<?php 
$jinsom_header_avatar_menu_add=jinsom_get_option('jinsom_header_avatar_menu_add');
if($jinsom_header_avatar_menu_add){
echo '<ul>';
foreach($jinsom_header_avatar_menu_add as $data) {
$type=$data['jinsom_header_avatar_menu_type'];
$name=$data['name'];
if($type=='home'){
$link=jinsom_userlink($user_id);
}else if($type=='mywallet'){
$link='javascript:jinsom_mywallet_form('.$user_id.')';
}else if($type=='vip'){
$link='javascript:jinsom_recharge_vip_form()';
$name_arr=explode('|',$name);
if(is_vip($user_id)){
$name=$name_arr[1];
}else{
$name=$name_arr[0];	
}
}else if($type=='order'){
$link='javascript:jinsom_goods_order_form()';
}else if($type=='content'){
$link='javascript:jinsom_content_management_form()';
}else if($type=='key-recharge'){
$link="javascript:jinsom_keypay_form('".$name."')";
}else if($type=='password'){
$name_arr=explode('|',$name);
if(is_author()&&$user_id!=$author_id&&jinsom_is_admin($user_id)){
$link='javascript:jinsom_update_password_form('.$author_id.')';
$name=$name_arr[1];
}else{
$link='javascript:jinsom_update_password_form('.$user_id.')';
$name=$name_arr[0];	
}
}else if($type=='loginout'){
$link='javascript:jinsom_login_out()';
}

if($type=='majia'){
$majia_user_id=jinsom_get_option('jinsom_majia_user_id');
if($majia_user_id){
$majia_arr=explode(",",$majia_user_id);
if(in_array($user_id,$majia_arr)||current_user_can('level_10')){
echo '<li class="'.$type.'"><a href="javascript:jinsom_majia_form()"><m>'.$name.'</m></a></li>'; 
}
}
}else if($type=='admin'){
if(current_user_can('level_10')){
echo '<li class="'.$type.'"><a href="/wp-admin/admin.php?page=jinsom" target="_blank">'.$name.'</a></li>';
}
}else if($type=='language'){
echo '<li class="'.$type.'"><a><i class="jinsom-icon jinsom-fanhui2"></i>'.$name.'：'.jinsom_get_language_name();
if($jinsom_languages_add){
echo '<ul>';
foreach ($jinsom_languages_add as $l_data) {
if(get_locale()==$l_data['code']){$on='class="on"';}else{$on='';}
echo '<li '.$on.' onclick=\'jinsom_change_language(this,"'.$l_data['code'].'")\'>'.$l_data['name'].'</li>';
}
echo '</ul>';
}
echo '</a></li>';
}else if($type=='custom'){
echo do_shortcode($data['custom']);
}else{
echo '<li class="'.$type.'"><a href="'.$link.'"><m>'.$name.'</m></a></li>'; 
} 

}//foreach

echo '</ul>';
}else{
echo '<ul><li><a href="/wp-admin/admin.php?page=jinsom" target="_blank">请前往后台设置</a></li></ul>';
}
?>



</li>
<?php 
}else{
if($header_login_on_off){
echo '<li class="login opacity" onclick="jinsom_pop_login_style();">'.__('登录','jinsom').'</li>';
echo '<li class="reg opacity" onclick=\'jinsom_login_form("注册帐号","reg-style",400)\'>'.__('注册','jinsom').'</li>';
}}?>

</div>
</div>
</div><!-- jinsom-header -->
<?php 
}?>



<div class="jinsom-menu-fixed"></div>
<script type="text/javascript">
$('.jinsom-menu-fixed').css('padding-top',$('.jinsom-header').height());
</script>