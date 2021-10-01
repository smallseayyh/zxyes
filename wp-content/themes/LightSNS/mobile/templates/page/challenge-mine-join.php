<?php 
//挑战-我参与的挑战
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
$credit_name=jinsom_get_option('jinsom_credit_name');
global $wpdb;
$table_name=$wpdb->prefix.'jin_challenge';
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name where challenge_user_id=$user_id ORDER BY ID DESC LIMIT 50;");
?>
<div data-page="challenge" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">我参与的挑战</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-challenge-content mine hide-navbar-on-scroll infinite-scroll">

<div class="jinsom-challenge-post-list mine-join" type="mine-join">
<?php 
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
echo jinsom_empty('你还没有参与别人的挑战！');
}
?>


</div>
</div>
</div>        