<?php 
//用户列表
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;

global $wpdb;
$table_name=$wpdb->prefix.'jin_pet';
$pet_data=$wpdb->get_results("SELECT user_id FROM $table_name group by user_id limit 100;");
?>
<div data-page="pet-list" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('宠物大厅','jinsom');?></div>
<div class="right">
<a href="<?php echo get_template_directory_uri();?>/mobile/templates/page/pet-note.php" class="link icon-only"><?php _e('动态','jinsom');?></a>
</div>
</div>
</div>

<div class="page-content jinsom-pet-list-content">
<div class="jinsom-pet-user-list">

<?php 
if($wpdb->get_results("SELECT user_id FROM $table_name WHERE user_id=$user_id limit 1;")){
if($pet_data){
echo '<div class="jinsom-chat-user-list visitor pet list-block">';
foreach ($pet_data as $data) {
$pet_user_id=$data->user_id;
$pet_times=(int)get_user_meta($pet_user_id,'pet_times',true);//孵化次数
if($pet_user_id!=$user_id){
$user_id=$data->user_id;
$link=get_template_directory_uri().'/mobile/templates/page/pet.php?author_id='.$pet_user_id;
$time=time();
if($wpdb->get_results("SELECT * FROM $table_name WHERE (hatch_time+time+protect_time)<$time and user_id=$pet_user_id;")){
$hand='<i class="egg"><img src="'.jinsom_get_option('jinsom_pet_steal_img').'"></i>';
}else{
$hand='';
}


echo '
<li onclick=\'$(this).children("i").remove();\'>
'.$hand.'
<div class="item-content">
<div class="item-media">
<a href="'.$link.'" class="link">
'.jinsom_avatar($pet_user_id,'40',avatar_type($pet_user_id)).jinsom_verify($pet_user_id).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.$link.'" class="link">
<div class="name">'.jinsom_nickname($pet_user_id).jinsom_sex($pet_user_id).jinsom_vip($pet_user_id).'</div>
<div class="desc">累计孵化'.$pet_times.'次</div>
</a>
</div>
</div>
</div>
</li>';



}

}
echo '</div>';

}else{
echo jinsom_empty('还没有数据');
}

}else{
echo jinsom_empty('嗨，你窝里还是空的呢！<br>请先孵化一颗蛋再来狩猎其他人的吧！');
}

?>


</div>
</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>