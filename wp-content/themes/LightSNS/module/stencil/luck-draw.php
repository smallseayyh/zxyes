<?php 
//查看所有抽奖奖品
require( '../../../../../wp-load.php' );
$post_id=(int)$_POST['post_id'];
$luckdraw_data=get_post_meta($post_id,'page_luckdraw_option',true);
if(!$luckdraw_data){
echo jinsom_empty('请在新建页面的时候配置当前页面的数据！');exit();
}
$jinsom_luck_gift_add=$luckdraw_data['jinsom_luck_gift_add'];
echo '<div class="jinsom-luck-gift-list">';
if($jinsom_luck_gift_add){
echo '<div class="tips">*所有奖品每次抽取的概率是相同的*</div>';
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

echo '<li><img style="border-color:'.$data['color'].'" src="'.$data['images'].'"><p title="'.$name.'">'.$name.'</p></li>';
}
}else{
echo jinsom_empty('暂没有设置任何奖品！');
}
echo '</div>';
