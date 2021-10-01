<?php
//赠送礼物
require( '../../../../../wp-load.php' );
$author_id=(int)$_POST['author_id'];
$post_id=(int)$_POST['post_id'];
$user_id=$current_user->ID;
$credit=(int)get_user_meta($user_id,'credit',true);
$jinsom_gift_add = jinsom_get_option('jinsom_gift_add');
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<div class="jinsom-send-gift-content">
<div class="jinsom-send-gift-form clear">
<?php 
if($jinsom_gift_add){
foreach ($jinsom_gift_add as $data) {

if($data['vip']){
$vip='<m>'.__('VIP专属','jinsom').'</m>';
}else{
$vip='';
}

if($data['m']>0){
$m='+'.$data['m'].__('魅力值','jinsom');
}else{
$m=$data['m'].__('魅力值','jinsom');	
}
echo '<li>
'.$vip.'
<n><i class="jinsom-icon jinsom-dui"></i></n>
<div class="top">
<div class="icon"><img src="'.$data['images'].'"></div>
<div class="name">'.$data['title'].'</div>
</div>
<div class="bottom" data="'.$m.'">'.$data['credit'].$credit_name.'</div>
</li>';
}
}else{
echo '请在后台-主题设置-礼物模块 添加礼物数据！';
}

?>
</div>

<div class="credit">
<div class="left">
我的<?php echo $credit_name;?><i class="jinsom-icon jinsom-jinbi"></i><span><?php echo $credit;?></span>
</div>
<div class="right"></div>

</div>

<div class="jinsom-send-gift-btn opacity" onclick="jinsom_send_gift(<?php echo $author_id;?>,<?php echo $post_id;?>)"><?php _e('赠送礼物','jinsom');?></div>

</div>



<script type="text/javascript">
$('.jinsom-send-gift-form li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$('.jinsom-send-gift-content .credit .right').html($(this).children('.bottom').attr('data'));
});

</script>