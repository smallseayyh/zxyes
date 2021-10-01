<?php 
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
global $wpdb;
$table_name=$wpdb->prefix.'jin_pet';
$pet_data=$wpdb->get_results("SELECT * FROM $table_name where user_id=$user_id limit 50;");
?>
<div data-page="select-pet" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">我的宠物</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>


<div class="page-content jinsom-publish-select-pet">
<?php 
if($pet_data){
foreach ($pet_data as $data) {
echo '<li onclick="jinsom_publish_add_application_pet(this)"><img src="'.$data->pet_img.'"><p>'.$data->pet_name.'</p></li>';
}
}else{
echo jinsom_empty(__('你还没有孵化宠物，赶紧去孵化一个吧！','jinsom'));
}
;?>

</div>
</div>   

