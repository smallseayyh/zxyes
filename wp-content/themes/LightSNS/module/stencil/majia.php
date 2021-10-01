<?php
//切换马甲
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(jinsom_get_option('jinsom_majia_on_off')){
$majia_user_id=jinsom_get_option('jinsom_majia_user_id');
$majia_arr=explode(",",$majia_user_id);
if(in_array($user_id,$majia_arr)||current_user_can('level_10')){
?>
<div class="jinsom-majia-form-content clear">
<?php 
if($majia_arr){
foreach ($majia_arr as $data){
if($data==$user_id){$status='<n>'.__('使用中','jinsom').'</n>';}else{$status='';}
echo '<li onclick="jinsom_exchange_majia('.$data.')"><div class="avatarimg">'.$status.jinsom_avatar($data,'30',avatar_type($data)).jinsom_verify($data).'</div><p>'.jinsom_nickname($data).'</p></li>';
}
}
?>

</div>
<?php }}?>