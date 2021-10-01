<?php 
require( '../../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$author_id=$_GET['author_id'];	
}else{
$author_id=$user_id;
}

if($author_id==$user_id){
jinsom_update_ip($author_id);//更新定位
}
$user_info=get_userdata($author_id);
$gender=$user_info->gender;
if($gender!='男生'&&$gender!='女生'){
$gender=__('保密','jinsom');	
}


$user_honor=$user_info->user_honor;
if(empty($user_honor)){
$use_honor='';
}else{
$use_honor=$user_info->use_honor;//当前使用的头衔
if(empty($use_honor)){
$honor_arr=explode(",",$user_honor);
update_user_meta($author_id,'use_honor',$honor_arr[0]);
$use_honor=$honor_arr[0];
}
}


//自动定位
if($user_info->city_lock=='lock'){
$city_lock='关闭';	
}else{
$city_lock='开启';
}

//发布位置
if($user_info->publish_city){
$publish_city='关闭';	
}else{
$publish_city='开启';
}

//免打扰
if(!$user_info->im_privacy){
$im_privacy='关闭';	
}else{
$im_privacy='开启';
}

//隐藏喜欢
if(!$user_info->hide_like){
$hide_like='关闭';	
}else{
$hide_like='开启';
}

//隐藏购买
if(!$user_info->hide_buy){
$hide_buy='关闭';	
}else{
$hide_buy='开启';
}

?>
<div data-page="setting-more" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('更多设置','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content">

<div class="jinsom-setting-box">

<li class="honor">
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-honor.php?author_id=<?php echo $author_id;?>" class="link">
<span class="title"><?php _e('头衔称号','jinsom');?></span>	
<span class="value"><?php echo $use_honor;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>


<li class="select" data="gender">
<a href="#" class="link">
<select>
<option value ="<?php _e('女生','jinsom');?>" <?php if($gender=='女生') echo 'selected = "selected"'; ?>><?php _e('女生','jinsom');?></option>
<option value ="<?php _e('男生','jinsom');?>" <?php if($gender=='男生') echo 'selected = "selected"'; ?>><?php _e('男生','jinsom');?></option>
<option value ="<?php _e('保密','jinsom');?>" <?php if($gender!='女生'&&$gender!='男生') echo 'selected = "selected"'; ?>><?php _e('保密','jinsom');?></option>
</select>
<span class="title"><?php _e('性别','jinsom');?></span>	
<span class="value"><?php echo $gender;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="birthday">
<input type="date" id="jinsom-setting-time" value="<?php echo $user_info->birthday;?>">
<a href="#" class="link">
<span class="title"><?php _e('生日','jinsom');?></span>	
<span class="value"><?php echo $user_info->birthday;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li>
<a href="#" class="link">
<span class="title"><?php _e('位置','jinsom');?></span>	
<span class="value"><?php echo $user_info->city;?></span>
</a>
</li>

<li>
<a href="#" class="link">
<span class="title"><?php _e('认证信息','jinsom');?></span>	
<span class="value"><?php echo $user_info->verify_info;?></span>
</a>
</li>

<?php 
$jinsom_languages_add=jinsom_get_option('jinsom_languages_add');
if(jinsom_get_option('jinsom_languages_on_off')){?>
<li class="language">
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-language.php" class="link">
<span class="title"><?php _e('语言','jinsom');?></span>	
<span class="value"><?php echo jinsom_get_language_name();?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>

<li class="desc">
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-desc.php?author_id=<?php echo $author_id;?>" class="link">
<span class="title"><?php _e('个人说明','jinsom');?></span>	
<span class="value"><?php echo $user_info->description;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

</div>

<div class="jinsom-setting-box">

<?php if(jinsom_get_option('jinsom_email_style')!='close'&&jinsom_get_option('jinsom_mail_notice_on_off')){?>
<li class="email-notice">
<a href="<?php echo $theme_url;?>/mobile/templates/page/setting/setting-email-notice.php?author_id=<?php echo $author_id;?>" class="link">
<span class="title"><?php _e('邮件通知','jinsom');?></span>	
<span class="value"></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
<?php }?>

<li class="select" data="city_lock">
<a href="#" class="link">
<select>
<option value ="lock" <?php if($city_lock=='关闭') echo 'selected = "selected"'; ?>><?php _e('关闭','jinsom');?></option>
<option value ="" <?php if($city_lock=='开启') echo 'selected = "selected"'; ?>><?php _e('开启','jinsom');?></option>
</select>
<span class="title"><?php _e('城市位置自动定位','jinsom');?></span>	
<span class="value"><?php echo $city_lock;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="select" data="publish_city">
<a href="#" class="link">
<select>
<option value ="1" <?php if($publish_city=='关闭') echo 'selected = "selected"'; ?>><?php _e('关闭','jinsom');?></option>
<option value ="" <?php if($publish_city=='开启') echo 'selected = "selected"'; ?>><?php _e('开启','jinsom');?></option>
</select>
<span class="title"><?php _e('发布内容显示位置','jinsom');?></span>	
<span class="value"><?php echo $publish_city;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="select" data="im_privacy">
<a href="#" class="link">
<select>
<option value ="" <?php if($im_privacy=='关闭') echo 'selected = "selected"'; ?>><?php _e('关闭','jinsom');?></option>
<option value ="1" <?php if($im_privacy=='开启') echo 'selected = "selected"'; ?>><?php _e('开启','jinsom');?></option>
</select>
<span class="title"><?php _e('聊天免打扰模式','jinsom');?></span>	
<span class="value"><?php echo $im_privacy;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="select" data="hide_like">
<a href="#" class="link">
<select>
<option value ="" <?php if($hide_like=='关闭') echo 'selected = "selected"'; ?>><?php _e('关闭','jinsom');?></option>
<option value ="1" <?php if($hide_like=='开启') echo 'selected = "selected"'; ?>><?php _e('开启','jinsom');?></option>
</select>
<span class="title"><?php _e('主页隐藏我的喜欢','jinsom');?></span>	
<span class="value"><?php echo $hide_like;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="select" data="hide_buy">
<a href="#" class="link">
<select>
<option value ="" <?php if($hide_buy=='关闭') echo 'selected = "selected"'; ?>><?php _e('关闭','jinsom');?></option>
<option value ="1" <?php if($hide_buy=='开启') echo 'selected = "selected"'; ?>><?php _e('开启','jinsom');?></option>
</select>
<span class="title"><?php _e('主页隐藏我的购买','jinsom');?></span>	
<span class="value"><?php echo $hide_buy;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

</div>


<?php 
//自定义资料字段
$jinsom_member_profile_setting_add=jinsom_get_option('jinsom_member_profile_setting_add');
if($jinsom_member_profile_setting_add){
echo '<div class="jinsom-setting-box">';
foreach ($jinsom_member_profile_setting_add as $data) {
echo '
<li onclick=\'jinsom_update_profile('.$author_id.',"'.$data['value'].'",this,"user")\'>
<a href="#" class="link">
<span class="title">'.$data['name'].'</span>	
<span class="value">'.get_user_meta($author_id,$data['value'],true).'</span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>
';
}
echo '</div>';
}
?>


<div class="jinsom-setting-box">

<li>
<a href="#" class="link">
<span class="title"><?php _e('补签卡','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->sign_card;?><?php _e('张','jinsom');?></span>
</a>
</li>

<li>
<a href="#" class="link">
<span class="title"><?php _e('改名卡','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->nickname_card;?><?php _e('张','jinsom');?></span>
</a>
</li>


</div>

</div>       

