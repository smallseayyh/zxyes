<?php 
//发红包
require( '../../../../../wp-load.php' );
$redbag_img_add=jinsom_get_option('jinsom_redbag_img_add');
?>
<div class="jinsom-publish-redbag-form">
<div class="title">发红包</div>
<div class="credit">
<label><?php echo jinsom_get_option('jinsom_credit_name');?></label>
<li>
<input type="number" value="<?php echo jinsom_get_option('jinsom_redbag_min');?>" id="jinsom-publish-redbag-credit">
</li>
</div>
<div class="credit">
<label>个数</label>
<li>
<input type="number" value="10" id="jinsom-publish-redbag-number" placeholder="不能超过100个">
</li>
</div>
<div class="type">
<li class="on" data="rand" title="所有人领取的金额都是随机的">随机</li>
<li data="average" title="红包总金额等于金额*个数">平均</li>
<li data="comment" title="用户领取红包后会自动回复红包贺语">队形</li>
<li data="follow" title="用户需要关注作者才可以领取红包">关注</li>
</div>
<div class="tips">所有人领取的金额都是随机的</div>
<textarea placeholder="贺语" id="jinsom-publish-redbag-content"><?php echo jinsom_get_option('jinsom_redbag_default_desc');?></textarea>

<?php if($redbag_img_add){?>
<div class="img-list clear">
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

<div class="btn opacity" onclick="jinsom_publish_redbag()">发出去</div>
</div>

<script type="text/javascript">
$('.jinsom-publish-redbag-form .type li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$('.jinsom-publish-redbag-form .tips').html($(this).attr('title'));
});
</script>