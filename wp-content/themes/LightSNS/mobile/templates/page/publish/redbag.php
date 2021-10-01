<?php 
//发红包
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$redbag_img_add=jinsom_get_option('jinsom_redbag_img_add');
?>
<div data-page="publish-redbag" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"></div>
<div class="right">

<a href="#" class="link icon-only"></a>

</div>
</div>
</div>


<div class="page-content">

<div class="jinsom-publish-redbag-form">
<div class="credit">
<label><?php echo jinsom_get_option('jinsom_credit_name');?></label>
<li>
<input type="number" value="<?php echo jinsom_get_option('jinsom_redbag_min');?>" id="jinsom-publish-redbag-credit">
</li>
</div>
<div class="credit">
<label><?php _e('个数','jinsom');?></label>
<li>
<input type="number" value="10" id="jinsom-publish-redbag-number">
</li>
</div>
<div class="type">
<li class="on" data="rand" title="<?php _e('所有人领取的金额都是随机的','jinsom');?>"><?php _e('随机','jinsom');?></li>
<li data="average" title="<?php _e('红包总金额等于金额*个数','jinsom');?>"><?php _e('平均','jinsom');?></li>
<li data="comment" title="<?php _e('用户领取红包后会自动回复红包贺语','jinsom');?>"><?php _e('队形','jinsom');?></li>
<li data="follow" title="<?php _e('用户需要关注作者才可以领取红包','jinsom');?>"><?php _e('关注','jinsom');?></li>
</div>
<div class="tips"><?php _e('所有人领取的金额都是随机的','jinsom');?></div>
<textarea placeholder="<?php _e('贺语','jinsom');?>" id="jinsom-publish-redbag-content"><?php echo jinsom_get_option('jinsom_redbag_default_desc');?></textarea>
<?php if($redbag_img_add){?>
<div class="img-list">
<li class="on"><p>默认</p></li>
<?php 
foreach ($redbag_img_add as $data) {
if($data['vip']){
$vip='<span>vip专属</span>';
}else{
$vip='';
}
echo '<li style="background-image:url('.$data['img'].')">'.$vip.'<p>'.$data['name'].'</p></li>';
}
?>
</div>
<?php }?>
<div class="btn opacity" onclick="jinsom_publish_redbag()"><?php _e('发出去','jinsom');?></div>
</div>

</div>

</div>   

