<?php 
//宠物商店
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$number=(int)$_GET['number'];
$jinsom_pet_add=jinsom_get_option('jinsom_pet_add');
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<div data-page="pet-store" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('宠物商店','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-pet-store-content">
<div class="jinsom-pet-store-list">
<?php 
if($jinsom_pet_add){
$i=0;
foreach ($jinsom_pet_add as $data) {
if($data['vip']){
$vip='<span>VIP</span>';
}else{
$vip='';	
}
echo '
<li>
<div class="name">'.$data['name'].$vip.'</div>
<div class="content">
<div class="img">
<img class="egg" src="'.$data['img_egg'].'">
<img class="pet" src="'.$data['img_pet'].'">
</div>
<div class="select">
<div class="price">'.(int)$data['price_egg'].$credit_name.'</div>
<div class="btn" onclick=\'jinsom_pet_buy('.$number.','.$i.')\'>'.__('选择','jinsom').'</div>
</div>
</div>
<div class="desc">
<span>'.__('孵化时长','jinsom').'：'.$data['time'].__('分钟','jinsom').'</span>
<span>'.__('卖出价格','jinsom').'：'.(int)$data['price_pet'].$credit_name.'</span>
</div>
</li>';
$i++;
}
}else{
echo jinsom_empty('还没有宠物蛋出售！');
}
?>
</div>
</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>