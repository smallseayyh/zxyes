<?php 
//马甲切换
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="majia" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">马甲切换</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-follower-content">
<div class="jinsom-chat-user-list majia follower list-block">
<?php 

if(jinsom_get_option('jinsom_majia_on_off')){
$majia_user_id=jinsom_get_option('jinsom_majia_user_id');
$majia_arr=explode(",",$majia_user_id);
if(in_array($user_id,$majia_arr)||current_user_can('level_10')){

if($majia_arr){
foreach ($majia_arr as $data){
if($data==$user_id){
$status='<div class="follow has"><i class="jinsom-icon jinsom-qiehuanyonghu"></i> 使用中</div>';
}else{
$status='<div onclick="jinsom_exchange_majia('.$data.')" class="follow no"><i class="jinsom-icon jinsom-qiehuanyonghu"></i> 切换</div>';
}
$user_info=get_userdata($data);
$desc=get_user_meta($data,'description',true);
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
$black='';
$danger='';
$xuncha='';
$admin='';
if(jinsom_is_black($author_id)){
$black='<span class="jinsom-mark black-user" style="background:#000">'.__('黑名单','jinsom').'</span>';
}
if($user_info->user_power==4){
$danger='<span class="jinsom-mark danger-user" style="background:#000">'.__('风险用户','jinsom').'</span>';
}
if($user_info->user_power==3){
$xuncha='<span class="jinsom-mark xuncha" style="background:#000">'.__('巡查员','jinsom').'</span>';
}
if(jinsom_is_admin($data)){
if(current_user_can('level_10')){
$admin='<span class="jinsom-mark admin" style="background:#000">'.__('超级管理','jinsom').'</span>';
}else{
$admin='<span class="jinsom-mark admin" style="background:#000">'.__('网站管理','jinsom').'</span>';
}
}
echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.jinsom_mobile_author_url($data).'" class="link">
'.jinsom_avatar($data,'40',avatar_type($data)).jinsom_verify($data).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.jinsom_mobile_author_url($data).'" class="link">
<div class="name">'.jinsom_nickname($data).jinsom_vip($data).$admin.$danger.$black.$xuncha.'</div>
<div class="desc">'.$desc.'</div>
</a>
</div>
</div>
'.$status.'
</div>
</li>';
}
}

}}


?>
</div>

</div>
</div>        