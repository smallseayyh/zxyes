<?php 
//宠物窝
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$author_id=(int)$_GET['author_id'];

if($author_id!=$user_id&&$author_id){
$user_id=$author_id;
$class='other';
}else{
$class='mine';
}


$jinsom_pet_nest_add=jinsom_get_option('jinsom_pet_nest_add');
$credit_name=jinsom_get_option('jinsom_credit_name');
$number=(int)$_GET['number'];
$name=$jinsom_pet_nest_add[$number]['name'];
$type=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type'];//窝的类型
global $wpdb;
$table_name=$wpdb->prefix.'jin_pet';
$pet_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' and nest_name='$name' limit 1;");

$jinsom_pet_nest_img=jinsom_get_option('jinsom_pet_nest_img');
?>
<div data-page="pet-nest-<?php echo $class;?>" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $pet_data[0]->pet_name;?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-pet-nest-content">
<div class="jinsom-pet-nest-list single <?php echo $class;?>">
<?php 


if($type=='credit'){
$user_pet_nest=get_user_meta($user_id,'pet_nest',true);
if($user_pet_nest){
$user_pet_nest_arr=explode(",",$user_pet_nest);
if(in_array($name,$user_pet_nest_arr)){
$status=1;
}else{
$status=0;
$info='
<div class="jinsom-pet-nest-btn" onclick="jinsom_pet_buy_nest('.$number.',this)">'.__('解锁','jinsom').' (-'.$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_credit'].$credit_name.')</div>';
}
}else{
$status=0;
$info='
<div class="jinsom-pet-nest-btn" onclick="jinsom_pet_buy_nest('.$number.',this)">'.__('解锁','jinsom').' (-'.$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_credit'].$credit_name.')</div>';
}
}else if($type=='vip'){
if(is_vip($user_id)){
$status=1;	
}else{
$status=0;
$info='
<div class="jinsom-pet-nest-btn no" onclick="jinsom_recharge_vip_type_form()">'.__('开通会员','jinsom').'</div>
<div class="jinsom-pet-nest-no-power-info">'.__('需要成为VIP会员才能解锁','jinsom').'</div>';
}
}else if($type=='exp'){
$user_exp=(int)get_user_meta($user_id,'exp',true);
if($user_exp>=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_exp']){
$status=1;	
}else{
$status=0;
$info='<div class="jinsom-pet-nest-no-power-info">'.sprintf(__( '需要经验值达到%s才能解锁','jinsom'),$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_exp']).'</div>';
}
}else if($type=='vip_number'){
$user_vip_number=(int)get_user_meta($user_id,'vip_number',true);
if($user_vip_number>=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_vip_number']){
$status=1;	
}else{
$status=0;
$info='<div class="jinsom-pet-nest-no-power-info">'.sprintf(__( '需要成长值达到%s才能解锁','jinsom'),$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_vip_number']).'</div>';
}
}else if($type=='charm'){
$user_charm=(int)get_user_meta($user_id,'charm',true);
if($user_charm>=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_charm']){
$status=1;	
}else{
$status=0;
$info='<div class="jinsom-pet-nest-no-power-info">'.sprintf(__( '需要魅力值达到%s才能解锁','jinsom'),$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_charm']).'</div>';
}
}else if($type=='visitor'){
$user_visitor=(int)get_user_meta($user_id,'visitor',true);
if($user_visitor>=$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_visitor']){
$status=1;	
}else{
$status=0;
$info='<div class="jinsom-pet-nest-no-power-info">'.sprintf(__( '需要人气值达到%s才能解锁','jinsom'),$jinsom_pet_nest_add[$number]['jinsom_pet_nest_type_visitor']).'</div>';
}
}else{//free
$status=1;	
}


if($status==0){//未解锁
echo '
<li class="gray">
<div class="animal"></div>
<div class="nest">
<img src="'.$jinsom_pet_nest_img.'">
</div>
</li>
'.$info;	
}else{

if($pet_data){

// print_r($pet_data);
// echo $pet_data->time;

$animal_img='';
$hatch_status='';
$hatch_time=(int)$pet_data[0]->hatch_time;//孵化时间：秒
$is_hatch=$pet_data[0]->time+$hatch_time;

if($is_hatch<time()){//孵化好了
$animal_img='<img class="pet_img" src="'.$pet_data[0]->pet_img.'">';
}else{
$animal_img='<img class="egg_img" src="'.$pet_data[0]->egg_img.'">';
$k=($pet_data[0]->time+$hatch_time)-time();
$hatch_status='<p>'.__('孵化中','jinsom').' '.jinsom_get_second_h_m($k).'</p>';
}


echo '
<li>
<div class="animal">'.$animal_img.'</div>
<div class="nest">
<img src="'.$jinsom_pet_nest_img.'">
<p>'.$hatch_status.'</p>
</div>
</li>';

if($is_hatch<time()&&!$author_id){


if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("pet",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($current_user->ID)){
echo '<div class="jinsom-pet-nest-btn" id="pet-1" data-id="'.$pet_data[0]->ID.'" data-number="'.$number.'">'.__('出售','jinsom').'  (+'.$pet_data[0]->price.$credit_name.')</div>';
}else{
echo '<div class="jinsom-pet-nest-btn" onclick=\'jinsom_pet_sell('.$pet_data[0]->ID.','.$number.',this,"","")\'>'.__('出售','jinsom').'  (+'.$pet_data[0]->price.$credit_name.')</div>';
}
}

if($is_hatch<time()&&$author_id){
if($is_hatch+$pet_data[0]->protect_time>time()){
$protect_time=$is_hatch+$pet_data[0]->protect_time-time();
echo '<div class="jinsom-pet-nest-protect-time">'.__('保护期','jinsom').' '.$protect_time.__('秒','jinsom').'</div>';
echo '<div class="jinsom-pet-nest-btn no">'.__('捕获','jinsom').'  (+'.$pet_data[0]->price.$credit_name.')</div>';
}else{

if(jinsom_get_option('jinsom_pet_steal_type')=='all'){
$price='  (+'.$pet_data[0]->price.$credit_name.')';
}else if(jinsom_get_option('jinsom_pet_steal_type')=='half'){
$price='  (+'.(int)($pet_data[0]->price/2).$credit_name.')';
}else{
$price='';
}

if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("pet",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($current_user->ID)){
echo '<div class="jinsom-pet-nest-btn" id="pet-1" data-id="'.$pet_data[0]->ID.'" data-number="'.$number.'">'.__('捕获','jinsom').$price.'</div>';
}else{
echo '<div class="jinsom-pet-nest-btn" onclick=\'jinsom_pet_steal('.$pet_data[0]->ID.','.$number.',this,"","")\'>'.__('捕获','jinsom').$price.'</div>';
}





}
}


}else{


if(!$author_id){
echo '
<li>
<div class="animal"></div>
<div class="nest">
<img src="'.$jinsom_pet_nest_img.'">
</div>
</li>
<div class="jinsom-pet-nest-btn" onclick="jinsom_pet_store('.$number.')">'.__('孵化','jinsom').'</div>
';
}else{
echo '
<li>
<div class="animal"></div>
<div class="nest">
<img src="'.$jinsom_pet_nest_img.'">
</div>
</li>
';
echo '<div class="jinsom-pet-nest-protect-time">'.__('该宠物已经被出售~','jinsom').'</div>';	
}



}

}



?>
</div>
</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>