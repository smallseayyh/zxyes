<?php 
//经验记录
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="exp-note" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('经验记录','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-recharge-note-content">
<div class="jinsom-chat-user-list recharge-note list-block" page="2" type="exp">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_exp_note';
$credit_data = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' ORDER BY time desc limit 50;");
if($credit_data){
foreach ($credit_data as $data) {
if($data->type=='add'){
$a='<font style="color:#2196f3">+'.$data->number.'</font>';
}else{
$a='<font style="color:#333">-'.$data->number.'</font>';	
}
echo '
<li>
<div class="item-content">
<div class="item-media">
<span style="background-color: #2196f3;"><i class="jinsom-icon jinsom-jingyan"></i></span></span>
</div>
<div class="item-inner">
<div class="item-title">
<div class="name">'.$data->content.'</div>
<div class="desc">'.$data->time.'</div>
</div>
</div>
<div class="item-after exp">'.$a.'</div>
</div>
</li>
';
}
echo '<div class="jinsom-empty-page">'.__('只展示前50条记录','jinsom').'</div>';
}else{
echo jinsom_empty();
}

?>

</div>
</div>
</div>        