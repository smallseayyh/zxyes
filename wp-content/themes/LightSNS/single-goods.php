<?php 
if(wp_is_mobile()){
require($require_url.'/mobile/index.php');
// header("Location:/");
}else{
get_header();
$user_id=$current_user->ID;
$post_id=get_the_ID();
$goods_data=get_post_meta($post_id,'goods_data',true);

$cover_img_add=$goods_data['jinsom_shop_goods_img_add'];
if($cover_img_add){//新版封面
$cover_one=$cover_img_add[0]['img'];//第一张封面
$cover_number=count($cover_img_add);//封面总数
}else{//旧版封面
$cover=$goods_data['jinsom_shop_goods_img'];
$cover_arr=explode(',',$cover);
$cover_src_one=wp_get_attachment_image_src($cover_arr[0],'full');
$cover_one=$cover_src_one[0];//第一张封面
$cover_number=count($cover_arr);//封面总数
}

$select_add=$goods_data['jinsom_shop_goods_select_add'];//属性套餐
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐
$info_add=$goods_data['jinsom_shop_goods_info_add'];//商品属性
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$price_type=$goods_data['jinsom_shop_goods_price_type'];
update_post_meta($post_id,'goods_price_type',$price_type);

$jinsom_shop_comment_on_off=$goods_data['jinsom_shop_comment_on_off'];//是否开启评论模块
$jinsom_shop_related_goods_on_off=$goods_data['jinsom_shop_related_goods_on_off'];//是否开启相关商品模块


if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$price=$goods_data['jinsom_shop_goods_price'];
$price_discount=$goods_data['jinsom_shop_goods_price_discount'];
}else{
$price=$select_change_price_add[0]['value_add'][0]['price'];
$price_discount=$select_change_price_add[0]['value_add'][0]['price_discount'];
}



if($price_discount){
$price_show='<c>'.$price_discount.'</c>';
if($price_type=='rmb'||$goods_type=='d'){
$price_discount_show='<n><d>￥'.$price.'</d>'.__('元','jinsom').'</n>';
}else{
$price_discount_show='<n><d>'.$price.'</d>'.jinsom_get_option('jinsom_credit_name').'</n>';	
}
update_post_meta($post_id,'goods_price',$price_discount);
}else{
$price_show='<c>'.$price.'</c>';
$price_discount_show='';
update_post_meta($post_id,'goods_price',$price);
}

//价格类型
if($price_type=='rmb'||$goods_type=='d'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}


//权限
$power=$goods_data['jinsom_shop_goods_power'];
if($power=='vip'){
$power_html='<span class="power">'.__('VIP专享','jinsom').'</span>';
}else if($power=='verify'){
$power_html='<span class="power">'.__('认证专享','jinsom').'</span>';	
}else if($power=='new'){
$power_html='<span class="power">'.__('新人专享','jinsom').'</span>';	
}else if($power=='charm'){
$power_html='<span class="power">'.__('指定魅力值','jinsom').'</span>';	
}else if($power=='exp'){
$power_html='<span class="power">'.__('指定经验值','jinsom').'</span>';	
}else if($power=='vip_number'){
$power_html='<span class="power">'.__('指定成长值','jinsom').'</span>';	
}else{
$power_html='';	
}
$ico=$goods_data['jinsom_shop_goods_ico'];//标志
if($ico){
$ico='<span class="ico">'.$ico.'</span>';
}else{
$ico='';
}

//客服
$jinsom_shop_kefu_type=jinsom_get_option('jinsom_shop_kefu_type');
$jinsom_shop_goods_kefu_type=$goods_data['jinsom_shop_kefu_type'];//每个商品单独的客服
update_post_meta($post_id,'buy_number',$goods_data['buy_number']);
?>
<div class="jinsom-main-goods-single">
<div class="jinsom-main-content clear">

<?php echo do_shortcode(jinsom_get_option('jinsom_shop_single_header_html'));//头部自定义区域?>

<div class="jinsom-goods-single-main">
<div class="jinsom-goods-single-header">
<?php if(current_user_can('level_10')){?>
<a class="setting" href="/wp-admin/post.php?post=<?php echo $post_id;?>&action=edit" target="_blank"><i class="jinsom-icon jinsom-shezhi"></i></a>
<?php }?>
<div class="left">
<div class="views"><div class="mark"><?php echo $power_html.$ico;?></div><img src="<?php echo $cover_one;?>"></div>
<?php if($cover_number>1){?>
<div class="list">
<?php 
if($cover_img_add){//新版封面
$i=0;
foreach ($cover_img_add as $data) {
if($i>3){break;}
if($i==0){$on='class="on"';}else{$on='';}
echo '<li '.$on.' title="'.$data['name'].'"><img src="'.$data['img'].'"></li>';
$i++;
}
}else{//旧版封面
for ($i=0; $i < $cover_number; $i++) { 
if($i>3){break;}
if($i==0){$on='class="on"';}else{$on='';}
$cover_src=wp_get_attachment_image_src($cover_arr[$i],'full');
echo '<li '.$on.'><img src="'.$cover_src[0].'"></li>';
}
}
?>
</div>
<?php }?>
</div>
<div class="right">
<h1 class="title"><?php the_title();?></h1>
<div class="subtitle"><?php echo $goods_data['jinsom_shop_goods_subtitle'];?></div>
<div class="info">
<div class="price-info">

<div class="number-info"><div class="like"><?php echo jinsom_count_post(0,$post_id);?><p><?php _e('累计收藏','jinsom');?></p></div><div class="buy"><?php echo (int)$goods_data['buy_number'];?><p><?php _e('累计销量','jinsom');?></p></div></div>

<li class="price"><span><?php _e('售价','jinsom');?></span><span><?php echo $price_icon.$price_show.$price_discount_show;?></span></li>
<li class="server"><span><?php _e('服务','jinsom');?></span>
<span>
<n><i class="jinsom-icon jinsom-wancheng"></i> <?php _e('官方自营','jinsom');?></n>
<n><i class="jinsom-icon jinsom-wancheng"></i> <?php _e('正品保障','jinsom');?></n>
<n><i class="jinsom-icon jinsom-wancheng"></i> <?php _e('极速发货','jinsom');?></n>
</span>
</li>
</div>

<?php if($select_add&&$goods_type!='a'&&$goods_type!='d'){?>
<div class="select">
<?php 
foreach ($select_add as $datas){?>
<li><span><?php echo $datas['name'];?></span>
<?php 
if($datas['value_add']){
$n=0;
foreach ($datas['value_add'] as $data){
if($n==0){$on='class="on"';}else{$on='';}
echo '<n '.$on.'>'.$data['value'].'</n>';
$n++;
}
}
?>
</li>
<?php }?>
</div>
<?php }?>

<?php if($select_change_price_add&&$goods_type!='a'&&$goods_type!='d'){?>
<div class="select-price">
<?php 
foreach ($select_change_price_add as $datas){?>
<li><span><?php echo $datas['name'];?></span>
<?php 
if($datas['value_add']){
$n=0;
foreach ($datas['value_add'] as $data){
if($n==0){$on='class="on"';}else{$on='';}
echo '<n '.$on.' price="'.$data['price'].'" price_discount="'.$data['price_discount'].'">'.$data['title'].'</n>';
$n++;
}
}
?>
</li>
<?php }?>
</div>
<?php }?>

<li class="number"><span><?php _e('数量','jinsom');?></span>
<span>
<i class="jinsom-icon jinsom-jianhao on"></i>
<input type="text" id="jinsom-goods-number" value="1" onkeyup='this.value=this.value.replace(/\D/gi,"")' maxlength="4">
<i class="jinsom-icon jinsom-hao"></i></span>
</li>
</div>

<div class="btn">
<?php 
if($goods_data['jinsom_shop_buy_text']){
$buy_text=$goods_data['jinsom_shop_buy_text'];
}else{
$buy_text=__('立即购买','jinsom');
}


if($goods_type!='d'){
if($power=='no'){
echo '<div class="buy no opacity">'.__('准备开售','jinsom').'</div>';
}else if($power=='stop'){
echo '<div class="buy no opacity">'.__('已下架','jinsom').'</div>';
}else{
echo '<div class="buy opacity" onclick="jinsom_goods_order_confirmation_form('.$post_id.')">'.$buy_text.'</div>';
}
}else{//淘宝客
echo '<div class="buy opacity" onclick="jinsom_post_link(this)" data="'.$goods_data['jinsom_shop_goods_taobaoke_url'].'">'.$buy_text.'</div>';
}
?>

<?php if(jinsom_is_collect($user_id,'goods',$post_id,'')){?>
<div class="like had" onclick="jinsom_collect(<?php echo $post_id;?>,'goods',this)"><i class="jinsom-icon jinsom-shoucang"></i><p><?php _e('已收藏','jinsom');?></p></div>
<?php }else{?>
<div class="like" onclick="jinsom_collect(<?php echo $post_id;?>,'goods',this)"><i class="jinsom-icon jinsom-shoucang1"></i><p><?php _e('收藏','jinsom');?></p></div>
<?php }?>


<?php if($jinsom_shop_goods_kefu_type=='im'){?>
<div class="contact" onclick="jinsom_open_user_chat(<?php echo $goods_data['jinsom_shop_kefu_im_user_id'];?>,this)"><i class="jinsom-icon jinsom-kefu"></i><span><?php _e('客服','jinsom');?></span></div>
<?php }else if($jinsom_shop_goods_kefu_type=='qq'){?>
<div class="contact" onclick="jinsom_post_link(this)" data="http://wpa.qq.com/msgrd?v=3&uin=<?php echo $goods_data['jinsom_shop_kefu_qq'];?>&site=qq&menu=yes"><i class="jinsom-icon jinsom-kefu"></i><span><?php _e('客服','jinsom');?></span></div>
<?php }else if($jinsom_shop_goods_kefu_type=='link'){?>
<div class="contact" onclick="jinsom_post_link(this)" data="<?php echo $goods_data['jinsom_shop_kefu_link'];?>"><i class="jinsom-icon jinsom-kefu"></i><span><?php _e('客服','jinsom');?></span></div>
<?php }else{?>
<?php if($jinsom_shop_kefu_type=='im'){?>
<div class="contact" onclick="jinsom_open_user_chat(<?php echo jinsom_get_option('jinsom_shop_kefu_im_user_id');?>,this)"><i class="jinsom-icon jinsom-kefu"></i><span><?php _e('客服','jinsom');?></span></div>
<?php }else if($jinsom_shop_kefu_type=='qq'){?>
<div class="contact" onclick="jinsom_post_link(this)" data="http://wpa.qq.com/msgrd?v=3&uin=<?php echo jinsom_get_option('jinsom_shop_kefu_qq');?>&site=qq&menu=yes"><i class="jinsom-icon jinsom-kefu"></i><span><?php _e('客服','jinsom');?></span></div>
<?php }else if($jinsom_shop_kefu_type=='link'){?>
<div class="contact" onclick="jinsom_post_link(this)" data="<?php echo jinsom_get_option('jinsom_shop_kefu_link');?>"><i class="jinsom-icon jinsom-kefu"></i><span><?php _e('客服','jinsom');?></span></div>
<?php }?>
<?php }?>



<div class="share" onclick="jinsom_reprint_form(<?php echo $post_id;?>);"><i class="jinsom-icon jinsom-tuiguang"></i><span><?php _e('分享','jinsom');?></span></div>
</div>


</div>
</div>

<?php if(!$jinsom_shop_related_goods_on_off){//开启相关商品?>
<div class="jinsom-goods-single-commend-list">
<div class="menu">
<li class="on"><?php _e('相关商品','jinsom');?></li>
<div class="right">
<?php 
$post_terms_arr=wp_get_post_terms($post_id,array('shop'));
$category_arr=array();
for ($i=0; $i <count($post_terms_arr);$i++){ 
array_push($category_arr,$post_terms_arr[$i]->term_id);
echo '<li><a href="'.get_category_link($post_terms_arr[$i]->term_id).'" target="_blank"><i></i>'.$post_terms_arr[$i]->name.'</a></li>';
}
?>
</div>
</div>

<div class="jinsom-shop-content clear">
<?php 
$style='two';
$args = array(
'post_type' => 'goods',
'showposts' => 5,
'post_status' => 'publish',
'post__not_in'=>array($post_id),
'orderby'   => 'rand', 
);
$args['tax_query']=array(
array(
'taxonomy' => 'shop',
'field' => 'id',
'terms' => $category_arr
)
);
query_posts($args);
if(have_posts()){
while(have_posts()):the_post();
require($require_url.'/post/goods.php');
endwhile;
wp_reset_query();
}else{
echo jinsom_empty(__('没有相关商品','jinsom'));	
}
?>
</div>
</div>
<?php }?>

<div class="jinsom-goods-single-content">
<div class="menu">
<li class="on"><?php _e('商品详情','jinsom');?></li>
<?php if($goods_type!='d'){?>
<li><?php _e('商品评价','jinsom');?></li>
<?php }?>

<?php if($jinsom_shop_comment_on_off){?>
<li><?php _e('商品讨论','jinsom');?></li>
<?php }?>

<?php if($info_add&&$goods_type!='d'){?>
<li><?php _e('商品属性','jinsom');?></li>
<?php }?>
</div>

<div class="list">
<div class="content clear">
<?php 
$post_id=get_the_ID();
echo do_shortcode(get_the_content('',false,$post_id));
?>
</div>

<?php if($goods_type!='d'){?>
<div class="content" style="display: none;">
<?php require($require_url.'/module/stencil/comments-goods.php');//引人评价模块  ?>
</div>
<?php }?>


<?php if($jinsom_shop_comment_on_off){?>
<div class="content" style="display: none;">
<?php 
//人机验证
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');
require($require_url.'/module/stencil/comments.php');//引人评论模块 
?>
</div>
<?php }?>

<?php if($info_add&&$goods_type!='d'){?>
<div class="content info clear" style="display: none;">
<?php 
foreach ($info_add as $data) {
echo '<li><span>'.$data['name'].'：</span>'.$data['value'].'</li>';
}
?>
</div>
<?php }?>


</div>

</div>

</div>

<?php echo do_shortcode(jinsom_get_option('jinsom_shop_single_footer_html'));//底部自定义区域?>


</div>
</div>
<script type="text/javascript">
$('.jinsom-goods-single-header .left .list li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$(this).parent().prev().children().attr('src',$(this).children().attr('src'));
});
$('.jinsom-goods-single-header .right .select li n').click(function(){//属性套餐
$(this).addClass('on').siblings().removeClass('on');
});


$('.jinsom-goods-single-header .right .select-price li n').click(function(){//价格套餐
$(this).addClass('on').siblings().removeClass('on');
price=$(this).attr('price');
price_discount=$(this).attr('price_discount');
if(price_discount){
price=price_discount;
$('.jinsom-goods-single-header .right li.price span:last-child n').show();
$('.jinsom-goods-single-header .right li.price span:last-child n d').html($(this).attr('price'))
}else{
$('.jinsom-goods-single-header .right li.price span:last-child n').hide();
}
$('.jinsom-goods-single-header .right li.price span:last-child c').html(price);

});

$('.jinsom-goods-single-content .menu li').click(function(){//内容选项卡
$(this).addClass('on').siblings().removeClass('on');
$(this).parent().next().children().eq($(this).index()).show().siblings().hide();
});
$('.jinsom-goods-single-header .right li.number span:last-child i.jinsom-jianhao').click(function(){//减号
if(!$(this).hasClass('on')){
number=parseInt($('#jinsom-goods-number').val());
if(number>2){
$('#jinsom-goods-number').val(number-1);
}else{
$('#jinsom-goods-number').val(1);	
$(this).addClass('on');
}
}
});
$('.jinsom-goods-single-header .right li.number span:last-child i.jinsom-hao').click(function(){//加号
number=parseInt($('#jinsom-goods-number').val());
$('#jinsom-goods-number').val(number+1);
if((number+1)>1){
$(this).siblings('.jinsom-jianhao').removeClass('on');
}
if((number+1)>99){
$('#jinsom-goods-number').val(99);
}
});
</script>
<?php get_footer();?>


<?php }//电脑端?>