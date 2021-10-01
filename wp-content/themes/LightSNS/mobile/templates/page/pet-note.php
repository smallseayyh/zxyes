<?php 
//全部记录
require( '../../../../../../wp-load.php');
global $wpdb;
$table_name_note=$wpdb->prefix.'jin_pet_note';
$pet_note_data=$wpdb->get_results("SELECT * FROM $table_name_note order by time DESC limit 50;");
?>
<div data-page="pet-note" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('动态记录','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content">
<?php  if($pet_note_data){?>
<div class="jinsom-pet-mine-note all">
<div class="content">
<?php 
foreach ($pet_note_data as $data) {
$type=$data->type;
$author_id=$data->author_id;
$user_id=$data->user_id;


//if($type=='sell'||$type=='deblocking'||$type=='buy'){
$user_link=' <a href="'.get_template_directory_uri().'/mobile/templates/page/pet.php?author_id='.$user_id.'" class="link">'.get_user_meta($user_id,'nickname',true).'</a> ';
//}
if($type=='sell'){
echo '<li>'.$user_link.'出售了 ['.$data->pet_name.'] <span>'.jinsom_timeago($data->time).'</span></li>';	
}else if($type=='deblocking'){
echo '<li>'.$user_link.'解锁了新宠物窝<span>'.jinsom_timeago($data->time).'</span></li>';	
}else if($type=='buy'){
echo '<li>'.$user_link.'正在孵化 ['.$data->pet_name.'] <span>'.jinsom_timeago($data->time).'</span></li>';	
}else if($type=='steal'){
$author_link=' <a href="'.get_template_directory_uri().'/mobile/templates/page/pet.php?author_id='.$author_id.'" class="link">'.get_user_meta($author_id,'nickname',true).'</a> ';
echo '<li>'.$author_link.'捕获了'.$user_link.'的 ['.$data->pet_name.'] <span>'.jinsom_timeago($data->time).'</span></li>';	
}


}
?>
</div>
</div>
<?php 
}else{
echo jinsom_empty('还没有数据');
}

?>


</div>
</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>