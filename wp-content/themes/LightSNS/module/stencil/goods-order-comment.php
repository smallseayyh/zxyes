<?php
//商品评价
require( '../../../../../wp-load.php' );
$post_id=(int)$_POST['post_id'];
$trade_no=strip_tags($_POST['trade_no']);
?>
<div class="jinsom-goods-order-comment-content">
<div class="star">
<div id="jinsom-goods-order-comment-star"></div>
</div>

<div class="jinsom-goods-order-comment-textarea clear">
<textarea id="jinsom-goods-order-comment-val" placeholder="<?php _e('来评价一下商品吧~','jinsom');?>"></textarea>
<span class="jinsom-single-expression-btn" onclick="jinsom_smile(this,'normal','')">
<i class="jinsom-icon expression jinsom-weixiao-"></i>
</span>
<span class="video-upload" onclick="layer.msg('暂未开启！')"><i class="jinsom-icon jinsom-shipin1"></i></span>
<span class="img-upload" onclick="layer.msg('暂未开启！')"><i class="jinsom-icon jinsom-tupian2"></i></span>
</div>
<div class="btn opacity" onclick="jinsom_goods_order_comment(<?php echo $post_id;?>,'<?php echo $trade_no;?>');"><?php _e('确定提交','jinsom');?></div>

</div>