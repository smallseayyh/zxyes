<?php 
//加载更多挑战
require( '../../../../../../wp-load.php' );
$require_url=get_template_directory();
$user_id=$current_user->ID;
$credit_name=jinsom_get_option('jinsom_credit_name');
$page=(int)$_POST['page'];
$type=strip_tags($_POST['type']);


if(!$page){$page=1;}
$number=20;
$offset=($page-1)*$number;

global $wpdb;
$table_name=$wpdb->prefix.'jin_challenge';

if($type=='ing'){
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name where challenge_user_id=0 ORDER BY ID DESC LIMIT $offset,$number;");
}else if($type=='end'){
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name where challenge_user_id!=0 ORDER BY ID DESC LIMIT $offset,$number;");
}else if($type=='mine'){
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name where user_id==$user_id ORDER BY ID DESC LIMIT $offset,$number;");
}else if($type=='mine-join'){
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name where challenge_user_id==$user_id ORDER BY ID DESC LIMIT $offset,$number;");
}else{
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name ORDER BY ID DESC LIMIT $offset,$number;");
}

if($challenge_data){

foreach ($challenge_data as $data){
$type=$data->type;
$challenge_user_id=$data->challenge_user_id;
$price=(int)$data->price;
if($type=='a'){
$type_text=__('石头剪刀布','jinsom');
}else{
$type_text=__('数字比大小','jinsom');	
}
$c_user_id=$data->user_id;
if($challenge_user_id){
$btn='<a href="'.$theme_url.'/mobile/templates/page/challenge-join.php?id='.$data->ID.'" class="link no">'.__('已结束','jinsom').'</a>';
}else{
$btn='<a href="'.$theme_url.'/mobile/templates/page/challenge-join.php?id='.$data->ID.'" class="link">'.__('挑战','jinsom').'</a>';
}
echo '<li id="jinsom-challenge-'.$data->ID.'">
<div class="avatarimg"><a href="'.jinsom_mobile_author_url($c_user_id).'" class="link">'.jinsom_avatar($c_user_id,'40',avatar_type($c_user_id)).jinsom_verify($c_user_id).'</a></div>	
<div class="info"><a href="'.$theme_url.'/mobile/templates/page/challenge-join.php?id='.$data->ID.'" class="link"><div class="name">'.__('发起挑战','jinsom').'：<span>'.$type_text.'</span></div><div class="desc">'.$data->description.'</div></a></div>
<div class="number">
<div class="price">'.$price.$credit_name.'</div>
<div class="btn">'.$btn.'</div>
</div>
</li>';
}


}else{
if($_POST['page']==1){
echo jinsom_empty('还没有人发起挑战！');
}else{
echo 0;
}
}
