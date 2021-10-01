<?php
//选择佩戴头衔
require( '../../../../../wp-load.php' );
if(jinsom_is_admin($current_user->ID)){
$user_id=$_POST['user_id'];
}else{
$user_id=$current_user->ID;
}
$user_honor=get_user_meta($user_id,'user_honor',true);
$use_honor=get_user_meta($user_id,'use_honor',true);//用户当前使用的勋章
?>
<div class="jinsom-user_honor-select-form">
<?php if($user_honor!=''){?>
<div class="tips"><?php _e('请从下面选择你要使用的头衔','jinsom');?></div>
<div class="list clear">
<?php 
$honor_arr=explode(",",$user_honor);
foreach ($honor_arr as $data) {
if($use_honor==$data){$on='class="on"';}else{$on='';}
echo '<li '.$on.'>'.$data.'</li>';
}
?>	
</div>
<div class="btn opacity" onclick="jinsom_use_honor(<?php echo $user_id;?>)"><?php _e('使用这个头衔','jinsom');?></div>
<?php }else{?>
<div class="nodata">
<?php _e('还没有获得头衔，多为社区作贡献将获得头衔','jinsom');?>
</div>
<?php }?>

</div>
