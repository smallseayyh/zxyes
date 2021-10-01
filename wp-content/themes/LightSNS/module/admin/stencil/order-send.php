<?php 
//订单发货
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$trade_no=(int)$_POST['trade_no'];
?>
<div class="jinsom-goods-order-send-form-content">
<textarea id="jinsom-goods-order-send-content" placeholder="<?php _e('请备注你发货的物流信息或者虚拟帐号信息等等，发货成功之后，系统将把此处内容通过IM私发给用户','jinsom');?>"></textarea>	
<div class="tips"><?php _e('发货之后，订单相当于已经完成，无需用户再次确定','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_goods_order_send(<?php echo $trade_no;?>)"><?php _e('确定发货','jinsom');?></div>
</div>

