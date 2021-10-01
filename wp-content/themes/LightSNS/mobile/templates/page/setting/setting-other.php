<?php 
require( '../../../../../../../wp-load.php');
$author_id=(int)$_GET['author_id'];	
$user_id=$current_user->ID;
$user_info = get_userdata($author_id);
$desc=$user_info->description;
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
$city=$user_info->city;
$birthday=$user_info->birthday;

$gender=$user_info->gender;
if($gender!='男生'&&$gender!='女生'){
$gender=__('保密','jinsom');	
}


$user_honor=$user_info->user_honor;
if(empty($user_honor)){
$use_honor=__('无','jinsom');
}else{
$use_honor=$user_info->use_honor;//当前使用的头衔
if(empty($use_honor)){
$honor_arr=explode(",",$user_honor);
update_user_meta($author_id,'use_honor',$honor_arr[0]);
$use_honor=$honor_arr[0];
}
}
?>
<div data-page="setting" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('详细资料','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content" data="<?php echo $author_id;?>">


<div class="jinsom-setting-box">

<li class="avatarimg">
<a href="#" class="link">
<span class="title"><?php _e('头像','jinsom');?></span>	
<span class="value"><?php echo jinsom_avatar($author_id,'80',avatar_type($author_id));?></span>
</a>
</li>

<li>
<a href="#" class="link">
<span class="title"><?php _e('用户ID','jinsom');?></span>	
<span class="value"><?php echo $author_id;?></span>
</a>
</li>

<li>
<a href="#" class="link">
<span class="title"><?php _e('昵称','jinsom');?></span>	
<span class="value"><?php echo $user_info->nickname;?></span>
</a>
</li>



<li class="code" onclick="jinsom_show_user_code(<?php echo $author_id;?>)">
<a href="#" class="link">
<span class="title"><?php _e('二维码','jinsom');?></span>	
<span class="value"><i class="jinsom-icon jinsom-erweima"></i></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>


</div>

<div class="jinsom-setting-box">

<li class="honor">
<a href="#" class="link">
<span class="title"><?php _e('头衔','jinsom');?></span>	
<span class="value"><?php echo $use_honor;?></span>
</a>
</li>



<li class="gender">
<a href="#" class="link">
<span class="title"><?php _e('性别','jinsom');?></span>	
<span class="value"><?php echo $gender;?></span>
</a>
</li>

<?php if($birthday){?>
<li class="birthday">
<a href="#" class="link">
<span class="title"><?php _e('生日','jinsom');?></span>	
<span class="value"><?php echo $user_info->birthday;?></span></a>
</li>
<?php }?>

<?php if($city){?>
<li>
<a href="#" class="link">
<span class="title"><?php _e('位置','jinsom');?></span>	
<span class="value"><?php echo $city;?></span>
</a>
</li>
<?php }?>


<li class="charm">
<a href="#" class="link">
<span class="title"><?php _e('魅力值','jinsom');?></span>	
<span class="value"><?php echo (int)$user_info->charm;?></span>
</a>
</li>

<li class="desc">
<a href="#" class="link">
<span class="title"><?php _e('个人说明','jinsom');?></span>	
<span class="value"><?php echo $desc;?></span>
</a>
</li>

</div>

<?php 
//自定义资料字段
$jinsom_member_profile_setting_add=jinsom_get_option('jinsom_member_profile_setting_add');
if($jinsom_member_profile_setting_add){
echo '<div class="jinsom-setting-box">';
foreach ($jinsom_member_profile_setting_add as $data) {
$power=$data['jinsom_member_profile_setting_power'];
$value=get_user_meta($author_id,$data['value'],true);
if($power=='vip'){
if(!is_vip($user_id)&&!jinsom_is_admin($user_id)){
$value='<font style="color:#f00;">VIP用户才能查看</font>';	
}
}else if($power=='verify'){
if(!get_user_meta($user_id,'verify',true)&&!jinsom_is_admin($user_id)){
$value='<font style="color:#f00;">认证用户才能查看</font>';	
}
}

if(!$value){
$value=__('未填写','jinsom');
}

if($power!='privacy'){
echo '
<li>
<a href="#" class="link">
<span class="title">'.$data['name'].'</span>	
<span class="value">'.$value.'</span>
</a>
</li>
';
}
}
echo '</div>';
}
?>





</div>
</div>        