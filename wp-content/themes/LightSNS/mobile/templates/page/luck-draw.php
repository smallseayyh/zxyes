<?php 
//幸运抽奖
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$credit=(int)get_user_meta($user_id,'credit',true);
$credit_name=jinsom_get_option('jinsom_credit_name');
$post_id=(int)$_GET['post_id'];
$luckdraw_data=get_post_meta($post_id,'page_luckdraw_option',true);
$jinsom_luck_gift_number=$luckdraw_data['jinsom_luck_gift_number'];//每次抽取需要花费的金币
$jinsom_luck_gift_add=$luckdraw_data['jinsom_luck_gift_add'];
$jinsom_luck_gift_default_cover=$luckdraw_data['jinsom_luck_gift_default_cover'];
?>
<div data-page="luck-draw" class="page no-tabbar toolbar-fixed">


<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $luckdraw_data['jinsom_page_header_name'];?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-luck-draw-content">

<div class='jinsom-luck-gift'>

<div class="jinsom-luck-draw-current-credit">我的<?php echo $credit_name;?>：<span><?php echo $credit;?></span></div>

<div class="jinsom-luck-draw-cover open-popup" data-popup=".jinsom-show-luck-gift-popup">
<div class="img" style="background-image: url(<?php echo $jinsom_luck_gift_default_cover;?>);">
<div class="name"><?php _e('点击查看所有奖品','jinsom');?></div>
</div>
</div>


<div class="jinsom-luck-draw-btn">
<span onclick="jinsom_luck_start(<?php echo $post_id;?>,'<?php echo $jinsom_luck_gift_number.$credit_name;?>',this)"><?php _e('开始抽奖','jinsom');?> (<?php echo $jinsom_luck_gift_number.$credit_name;?>)</span>
</div>

<div class="jinsom-luck-draw-list">
<div class="title">
<li class="on"><?php _e('我的奖品','jinsom');?></li>
<li><?php _e('最新抽奖','jinsom');?></li>
</div>
<div class="content">
<ul class="a">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_luck_draw';
$luck_data= $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' ORDER BY ID DESC LIMIT 30;");
if($luck_data){

foreach ($luck_data as $data) {
echo '<li><span class="img" style="background-image:url('.$data->img.')"></span><span class="name">'.$data->name.'</span></li>';
}

}else{
echo jinsom_empty(__('暂没有抽奖记录','jinsom'));
}
?>
</ul>
<ul style="display: none;" class="b">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_luck_draw';
$luck_data= $wpdb->get_results("SELECT * FROM $table_name WHERE user_id!='$user_id' ORDER BY ID DESC LIMIT 30;");
if($luck_data){

foreach ($luck_data as $data) {
echo '<li><a class="link" href="'.jinsom_mobile_author_url($data->user_id).'"><span class="img" style="background-image:url('.jinsom_avatar_url($data->user_id,avatar_type($data->user_id)).')">'.jinsom_verify($data->user_id).'</span><span class="name">'.__('抽到了','jinsom').' <m>'.$data->name.'</m></span></a><span class="right">'.jinsom_timeago($data->time).'</span></li>';
}

}else{
echo jinsom_empty(__('暂没有抽奖记录','jinsom'));
}
?>
</ul>
</div>
</div>


<div class="jinsom-luck-draw-info">
<div class="title"><?php _e('规则说明','jinsom');?></div>
<?php echo do_shortcode($luckdraw_data['jinsom_luck_gift_info_html']);?>
</div>

</div>


<div class="popup jinsom-show-luck-gift-popup">
<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="link icon-only close-popup"><i class="jinsom-icon jinsom-xiangxia2"></i></a>
</div>
<div class="center"><?php _e('全部奖励','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>
<div class="page-content jinsom-luck-gift-list-content">

<?php 
echo '<div class="jinsom-luck-gift-list clear">';
if($jinsom_luck_gift_add){
echo '<div class="tips">*'.__('所有奖品每次抽取的概率是相同的','jinsom').'*</div>';
foreach ($jinsom_luck_gift_add as $data) {

$type=$data['jinsom_luck_gift_add_type'];
if($type=='头衔'){
$number=$data['honor_name'];
}else{
$number=$data['number'];
}
if($type=='空'){
$name=__('脸黑*没有奖励','jinsom');
}else if($type=='custom'||$type=='nickname'||$type=='签到天数'){
$name=$data['name'].'*'.$number;
}else if($type=='faka'){
$name=$data['name'];
}else if($type=='金币'){
$name=jinsom_get_option('jinsom_credit_name').'*'.$number;
}else{
$name=$type.'*'.$number;
}

echo '<li><img style="border-color:'.$data['color'].'" src="'.$data['images'].'"><p>'.$name.'</p></li>';
}
}else{
echo jinsom_empty(__('暂没有设置任何奖品','jinsom'));
}
echo '</div>';
?>

</div>
</div>

</div>
</div>
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>