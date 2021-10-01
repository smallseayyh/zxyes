<?php 
//商品评价
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$post_id=(int)$_GET['post_id'];
?>
<div data-page="goods-star" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left"><a href="#" class="back link icon-only"><i class="jinsom-icon jinsom-fanhui2"></i></a></div>
<div class="center sliding"><?php _e('全部评价','jinsom');?></div>
<div class="right"><a href="#" class="link icon-only"></a></div>
</div>
</div>



<div class="page-content jinsom-goods-star-list-content infinite-scroll" data-distance="500">

<?php 
$number=30;
require($require_url.'/mobile/templates/page/shop/goods-star-template.php');
?>

</div>

</div>   

