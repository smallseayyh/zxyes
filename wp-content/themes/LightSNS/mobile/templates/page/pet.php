<?php 
//宠物乐园
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$author_id=(int)$_GET['author_id'];

if($author_id!=$user_id&&$author_id){
$user_id=$author_id;
$class='other';
}else{
$class='mine';
}

$jinsom_pet_nest_add=jinsom_get_option('jinsom_pet_nest_add');
$nest_number=count($jinsom_pet_nest_add);//窝数
global $wpdb;
$table_name=$wpdb->prefix.'jin_pet';
$pet_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id';");

$jinsom_pet_nest_img=jinsom_get_option('jinsom_pet_nest_img');
?>
<div data-page="pet-<?php echo $class;?>" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php 
if($author_id!=$current_user->ID&&$author_id){
echo get_user_meta($author_id,'nickname',true);
}else{
echo __('我的宠物','jinsom');
}

;?></div>
<div class="right">
<?php if(!$author_id){?>
<a href="<?php echo get_template_directory_uri();?>/mobile/templates/page/pet-list.php" class="link icon-only"><?php _e('大厅','jinsom');?></a>
<?php }else{?>
<a href="#" class="link icon-only"></a>
<?php }?>
</div>
</div>
</div>

<div class="page-content jinsom-pet-content <?php echo $class;?>">
<div class="jinsom-pet-header">
<?php echo do_shortcode(jinsom_get_option('jinsom_pet_header_html'));?>
</div>
<div class="jinsom-pet-nest-list clear">
<?php 
// print_r($test);
// echo $pet_data[1]->nest_name;
if($jinsom_pet_nest_add){
$i=0;
foreach ($jinsom_pet_nest_add as $data) {
$type=$data['jinsom_pet_nest_type'];
$name=$data['name'];
if($type=='credit'){
$user_pet_nest=get_user_meta($user_id,'pet_nest',true);
if($user_pet_nest){
$user_pet_nest_arr=explode(",",$user_pet_nest);
if(in_array($name,$user_pet_nest_arr)){
$status=1;
}else{
$status=0;
}
}else{
$status=0;
}
}else if($type=='vip'){
if(is_vip($user_id)){
$status=1;	
}else{
$status=0;
}
}else if($type=='exp'){
$user_exp=(int)get_user_meta($user_id,'exp',true);
if($user_exp>=$data['jinsom_pet_nest_type_exp']){
$status=1;	
}else{
$status=0;
}
}else if($type=='vip_number'){
$user_vip_number=(int)get_user_meta($user_id,'vip_number',true);
if($user_vip_number>=$data['jinsom_pet_nest_type_vip_number']){
$status=1;	
}else{
$status=0;
}
}else if($type=='charm'){
$user_charm=(int)get_user_meta($user_id,'charm',true);
if($user_charm>=$data['jinsom_pet_nest_type_charm']){
$status=1;	
}else{
$status=0;
}
}else if($type=='visitor'){
$user_visitor=(int)get_user_meta($user_id,'visitor',true);
if($user_visitor>=$data['jinsom_pet_nest_type_visitor']){
$status=1;	
}else{
$status=0;
}
}else{//free
$status=1;	
}


if($status==0){

if($author_id!=$current_user->ID&&$author_id){
$link='';
}else{
$link=get_template_directory_uri().'/mobile/templates/page/pet-nest.php?number='.$i;
}

echo '
<li class="gray">
<a href="'.$link.'" class="link">
<div class="animal"></div>
<div class="nest">
<img src="'.$jinsom_pet_nest_img.'">
<p class="no">'.__('未解锁','jinsom').'</p>
</div>
</a></li>';	
}else{

//$pet_data=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' and name='$name';");
$animal_img='';

if($author_id!=$current_user->ID&&$author_id){
$link='';
}else{
$link=get_template_directory_uri().'/mobile/templates/page/pet-nest.php?number='.$i;
}

if($author_id!=$current_user->ID&&$author_id){
$hatch_status='<p class="green">'.__('未孵化','jinsom').'</p>';
}else{
$hatch_status='<p class="green">'.__('可孵化','jinsom').'</p>';	
}


for ($a=0; $a <$nest_number ; $a++) {
if($pet_data[$a]->nest_name==$name){
$hatch_time=(int)$pet_data[$a]->hatch_time;//孵化时间：秒
if($pet_data[$a]->time+$hatch_time<time()){//孵化好了
$animal_img='<img class="pet_img" src="'.$pet_data[$a]->pet_img.'">';
$hatch_status='<p class="green">'.__('孵化完成','jinsom').'</p>';
if($author_id!=$current_user->ID&&$author_id){
// $hatch_status='<p class="green">'.__('可捕获','jinsom').'</p>';
$link=get_template_directory_uri().'/mobile/templates/page/pet-nest.php?number='.$i.'&author_id='.$author_id;
}else{

$link=get_template_directory_uri().'/mobile/templates/page/pet-nest.php?number='.$i;	
}




}else{
$animal_img='<img class="egg_img" src="'.$pet_data[$a]->egg_img.'">';
$k=($pet_data[$a]->time+$hatch_time)-time();
$hatch_status='<p>'.__('孵化中','jinsom').' '.jinsom_get_second_h_m($k).'</p>';

if($author_id!=$current_user->ID&&$author_id){
$link='';
}else{
$link=get_template_directory_uri().'/mobile/templates/page/pet-nest.php?number='.$i;
}

}
}
}



echo '
<li>
<a href="'.$link.'" class="link">
<div class="animal">'.$animal_img.'</div>
<div class="nest">
<img src="'.$jinsom_pet_nest_img.'">
<p>'.$hatch_status.'</p>
</div>
</a></li>';
}

$i++;
}


}else{
echo jinsom_empty('还没有配置数据，请到主题配置-页面模块-宠物乐园进行配置');
}
?>
</div>

<?php 

//记录
if($jinsom_pet_nest_add){
$table_name_note=$wpdb->prefix.'jin_pet_note';
$pet_note_data=$wpdb->get_results("SELECT * FROM $table_name_note WHERE type='steal' and user_id=$user_id order by time DESC limit 10;");
if($pet_note_data){
?>
<div class="jinsom-pet-mine-note">
<div class="title"><?php _e('捕获记录','jinsom');?></div>
<div class="content">
<?php 
foreach ($pet_note_data as $data) {
$author_id=$data->author_id;
$author_link='<a href="'.get_template_directory_uri().'/mobile/templates/page/pet.php?author_id='.$author_id.'" class="link">'.get_user_meta($author_id,'nickname',true).'</a>';
$user_link='<a href="" class="link">'.get_user_meta($user_id,'nickname',true).'</a>';
if(!isset($_GET['author_id'])){
echo '<li>你的 ['.$data->pet_name.'] 被 '.$author_link.' 捕获了 <span>'.jinsom_timeago($data->time).'</span></li>';
}else{
echo '<li> '.$user_link.' 的 ['.$data->pet_name.'] 被 '.$author_link.' 捕获了 <span>'.jinsom_timeago($data->time).'</span></li>';	
}


}
?>
</div>
</div>
<?php }

}?>

<?php 
echo do_shortcode(jinsom_get_option('jinsom_pet_footer_html'));
jinsom_upadte_user_online_time();//更新在线状态
?>

</div>
</div>        