<?php 
//挑战
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$id=(int)$_GET['id'];
$credit_name=jinsom_get_option('jinsom_credit_name');
global $wpdb;
$table_name=$wpdb->prefix.'jin_challenge';
$challenge_data=$wpdb->get_results("SELECT * FROM $table_name WHERE ID=$id LIMIT 1;");
$c_user_id=$challenge_data[0]->user_id;
$type=$challenge_data[0]->type;
if($type=='a'){
$type_text=__('石头剪刀布','jinsom');
}else{
$type_text=__('数字比大小','jinsom');	
}
$c_nickname=get_user_meta($c_user_id,'nickname',true);
$price=(int)$challenge_data[0]->price;
$description=$challenge_data[0]->description;
$challenge_user_id=$challenge_data[0]->challenge_user_id;
$c_user_id=$challenge_data[0]->user_id;

$shitou_arr=array('石头','剪刀','布');
shuffle($shitou_arr);
?>
<div data-page="challenge-join" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">参与挑战</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>



</div>
</div>

<div class="page-content jinsom-challenge-content join hide-navbar-on-scroll infinite-scroll" data-distance="500">
<div class="user-info">
<div class="avatarimg"><a href="<?php echo jinsom_mobile_author_url($c_user_id);?>" class="link"><?php echo jinsom_avatar($c_user_id,'40',avatar_type($c_user_id)).jinsom_verify($c_user_id);?></a></div>
</div>
<div class="type-desc"><?php echo $c_nickname;?>发起的挑战<p><?php echo $type_text;?></p></div>
<div class="description"> “<?php echo $description;?>”</div>
<div class="price"><m><?php echo $price;?></m><?php echo $credit_name;?></div>

<?php if(!$challenge_user_id){?>
<?php if($type=='b'){?>
<div class="tips">参与之后，系统自动从0-100随机选一个数字，如果你的数字比对方大则你赢！</div>
<?php }?>
<?php if($type=='a'&&$c_user_id!=$user_id){?>
<div class="shitou">
<?php 
$i=0;
foreach ($shitou_arr as $data){
if($i==0){
$on='on';
}else{
$on='';
}
if($data=='石头'){
echo '<li class="'.$on.'"><i class="jinsom-icon jinsom-shitou1"></i><p>石头</p></li>';
}else if($data=='剪刀'){
echo '<li class="'.$on.'"><i class="jinsom-icon jinsom-jiandao1"></i><p>剪刀</p></li>';
}else{
echo '<li class="'.$on.'"><i class="jinsom-icon jinsom-bu"></i><p>布</p></li>';
}
$i++;
}
?>

</div>
<?php }?>

<?php if($c_user_id!=$user_id){?>
<div class="btn" onclick="jinsom_challenge_join(<?php echo $challenge_data[0]->ID;?>)">参与挑战</div>
<?php }?>

<?php }else{
$a_value=$challenge_data[0]->user_value;
$b_value=$challenge_data[0]->challenge_user_value;
$aa='<font style="color:#4caf50;">胜利</font>';
$bb='<font style="color:#f00;">失败</font>';
$cc='<font style="color:#2196f3;">平局</font>';
if($type=='a'){
	
if($a_value=='石头'){
if($b_value=='石头'){
$a_result=$cc;
$b_result=$cc;
}else if($b_value=='剪刀'){
$a_result=$aa;
$b_result=$bb;
}else if($b_value=='布'){
$a_result=$bb;
$b_result=$aa;
}
}else if($a_value=='剪刀'){
if($b_value=='石头'){
$a_result=$bb;
$b_result=$aa;
}else if($b_value=='剪刀'){
$a_result=$cc;
$b_result=$cc;
}else if($b_value=='布'){
$a_result=$aa;
$b_result=$bb;
}
}else{
if($b_value=='石头'){
$a_result=$aa;
$b_result=$bb;
}else if($b_value=='剪刀'){
$a_result=$bb;
$b_result=$aa;
}else if($b_value=='布'){
$a_result=$cc;
$b_result=$cc;
}
}


}else{
if($a_value>$b_value){
$a_result=$aa;
$b_result=$bb;
}else if($a_value<$b_value){
$a_result=$bb;
$b_result=$aa;	
}else{
$a_result=$cc;
$b_result=$cc;
}
}


?>
<div class="result">
<div class="a">
<a href="<?php echo jinsom_mobile_author_url($c_user_id);?>" class="link">
<?php echo jinsom_avatar($c_user_id,'40',avatar_type($c_user_id)).jinsom_verify($c_user_id);?>
</a>
<p><?php echo $a_result.'<br>'.$a_value;?></p>
</div>
<div class="vs"><i class="jinsom-icon jinsom-VS"></i></div>
<div class="b">
<a href="<?php echo jinsom_mobile_author_url($challenge_user_id);?>" class="link">
<?php echo jinsom_avatar($challenge_user_id,'40',avatar_type($challenge_user_id)).jinsom_verify($challenge_user_id);?>
</a>
<p><?php echo $b_result.'<br>'.$b_value;?></p>
</div>
</div>
<?php }?>

</div>
</div>        