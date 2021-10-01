<?php 
//商品详情页面
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;

$rand=$_GET['rand'];
$post_id=$_GET['post_id'];
$post_data = get_post($post_id, ARRAY_A);
// $content=do_shortcode(convert_smilies(wpautop(jinsom_autolink($post_data['post_content'],$post_id))));
$title=$post_data['post_title'];
	

$goods_data=get_post_meta($post_id,'goods_data',true);
$this_select_add=$goods_data['jinsom_shop_goods_select_add'];//属性套餐
$this_select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐
$cover_img_add=$goods_data['jinsom_shop_goods_img_add'];
$this_buy_number=(int)$goods_data['buy_number'];
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

if(!$cover_one){
$cover_one=get_template_directory_uri().'/images/default-cover.jpg';	
}


$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐
$info_add=$goods_data['jinsom_shop_goods_info_add'];//商品属性
$this_goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$this_goods_buy_user_info_add=$goods_data['jinsom_shop_goods_buy_user_info_add'];//下单信息
$this_buy_info_tips=$goods_data['jinsom_shop_goods_buy_info_tips'];//备注提示
$this_buy_tips=$goods_data['jinsom_shop_goods_buy_tips'];//提醒文字

$price_type=$goods_data['jinsom_shop_goods_price_type'];
update_post_meta($post_id,'goods_price_type',$price_type);

if($this_goods_type=='a'||$this_goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$price=$goods_data['jinsom_shop_goods_price'];
$price_discount=$goods_data['jinsom_shop_goods_price_discount'];
}else{
$price=$select_change_price_add[0]['value_add'][0]['price'];
$price_discount=$select_change_price_add[0]['value_add'][0]['price_discount'];
}



if($price_discount){
$this_price_show='<c>'.$price_discount.'</c>';
if($price_type=='rmb'||$this_goods_type=='d'){
$this_price_discount_show='<n><d>￥'.$price.'</d>'.__('元','jinsom').'</n>';
}else{
$this_price_discount_show='<n><d>'.$price.'</d>'.jinsom_get_option('jinsom_credit_name').'</n>';	
}
update_post_meta($post_id,'goods_price',$price_discount);
}else{
$this_price_show='<c>'.$price.'</c>';
$this_price_discount_show='';
update_post_meta($post_id,'goods_price',$price);
}

//价格类型
if($price_type=='rmb'||$this_goods_type=='d'){
$this_price_icon='<t class="yuan">￥</t>';
}else{
$this_price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
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
<div data-page="post-goods" class="page no-tabbar post-goods">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('商品详情','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only" onclick="layer.open({content:'暂未开启！',skin:'msg',time:2});"><i class="jinsom-icon jinsom-goumai"></i></a>
</div>
</div>
</div>

<div class="toolbar toolbar-bottom jinsom-goods-toolbar">
<div class="toolbar-inner">

<?php if($jinsom_shop_goods_kefu_type=='im'){?>
<li class="kefu" goods="<?php echo $post_id;?>" onclick="jinsom_open_user_chat(<?php echo $goods_data['jinsom_shop_kefu_im_user_id'];?>,this)"><i class="jinsom-icon jinsom-kefu"></i><p><?php _e('客服','jinsom');?></p></li>
<?php }else if($jinsom_shop_goods_kefu_type=='qq'){?>
<li class="kefu" onclick="window.open('http://wpa.qq.com/msgrd?v=3&uin=<?php echo $goods_data['jinsom_shop_kefu_qq'];?>&site=qq&menu=yes')"><i class="jinsom-icon jinsom-kefu"></i><p><?php _e('客服','jinsom');?></p></li>
<?php }else if($jinsom_shop_goods_kefu_type=='link'){?>
<li class="kefu" onclick="window.open('<?php echo $goods_data['jinsom_shop_kefu_link'];?>')"><i class="jinsom-icon jinsom-kefu"></i><p><?php _e('客服','jinsom');?></p></li>
<?php }else{?>
<?php if($jinsom_shop_kefu_type=='im'){?>
<li class="kefu" goods="<?php echo $post_id;?>" onclick="jinsom_open_user_chat(<?php echo jinsom_get_option('jinsom_shop_kefu_im_user_id');?>,this)"><i class="jinsom-icon jinsom-kefu"></i><p><?php _e('客服','jinsom');?></p></li>
<?php }else if($jinsom_shop_kefu_type=='qq'){?>
<li class="kefu" onclick="window.open('http://wpa.qq.com/msgrd?v=3&uin=<?php echo jinsom_get_option('jinsom_shop_kefu_qq');?>&site=qq&menu=yes')"><i class="jinsom-icon jinsom-kefu"></i><p><?php _e('客服','jinsom');?></p></li>
<?php }else if($jinsom_shop_kefu_type=='link'){?>
<li class="kefu" onclick="window.open('<?php echo jinsom_get_option('jinsom_shop_kefu_link');?>')"><i class="jinsom-icon jinsom-kefu"></i><p><?php _e('客服','jinsom');?></p></li>
<?php }?>
<?php }?>


<?php if(jinsom_is_collect($user_id,'goods',$post_id,'')){?>
<li class="collect collect-post-<?php echo $post_id;?>" onclick="jinsom_collect(<?php echo $post_id;?>,'goods',this)"><i class="jinsom-icon jinsom-shoucang"></i><p><?php _e('已收藏','jinsom');?></p></li>
<?php }else{?>
<li class="collect collect-post-<?php echo $post_id;?>" onclick="jinsom_collect(<?php echo $post_id;?>,'goods',this)"><i class="jinsom-icon jinsom-shoucang1"></i><p><?php _e('收藏','jinsom');?></p></li>
<?php }?>

<li class="btn">
<?php if($power!='no'&&$power!='stop'&&$this_goods_type!='d'){?>
<span class="car" onclick="jinsom_shop_select_form(<?php echo $post_id;?>,'car')"><?php _e('加入购物车','jinsom');?></span>
<?php }?>

<?php if($this_goods_type!='d'){?>
<?php if($power=='no'){?>
<span class="buy no"><?php _e('准备开售','jinsom');?></span>
<?php }else if($power=='stop'){?>
<span class="buy no"><?php _e('已下架','jinsom');?></span>
<?php }else{?>
<span class="buy" onclick="jinsom_shop_select_form(<?php echo $post_id;?>,'buy')"><?php _e('立即购买','jinsom');?></span>
<?php }?>
<?php }else{?>
<span class="buy" onclick="window.open('<?php echo $goods_data['jinsom_shop_goods_taobaoke_url'];?>')"><?php _e('立即购买','jinsom');?></span>
<?php }?>
</li>
</div>
</div>


<div class="page-content keep-toolbar-on-scroll infinite-scroll jinsom-page-goods-content jinsom-page-goods-content-<?php echo $post_id;?>" data-distance="800">



<div class="jinsom-mobile-goods-slider-content">
<div class="jinsom-mobile-slider owl-carousel" id="jinsom-goods-slider-<?php echo $rand;?>">
<?php 
if(!$cover_img_add&&!$cover){
echo '<a class="item" href="'.$cover_one.'" data-fancybox="gallery-goods-cover-'.$rand.'"><img src="'.$cover_one.'"></a>';
}else{
if($cover_img_add){//新版封面
foreach ($cover_img_add as $data) {
echo '<a class="item" href="'.$data['img'].'" data-fancybox="gallery-goods-cover-'.$rand.'"><img src="'.$data['img'].'"></a>';
}
}else{//旧版封面
for ($i=0; $i < $cover_number; $i++) { 
$cover_src=wp_get_attachment_image_src($cover_arr[$i],'full');
echo '<a class="item" href="'.$cover_src[0].'" data-fancybox="gallery-goods-cover-'.$rand.'"><img src="'.$cover_src[0].'"></a>';
}
}
}
?>
</div>
<div class="slider-counter"></div>
</div>

<div class="jinsom-goods-single-content">
<div class="price"><?php echo $this_price_icon.$this_price_show.$this_price_discount_show;?></div>
<div class="title"><?php echo $power_html.$ico;?><?php echo $title;?></div>
<div class="buy-number"><span><?php _e('已售','jinsom');?> <m><?php echo $this_buy_number;?></m></span><span class="from"><?php _e('商品来自官方自营','jinsom');?></span></div>
<div class="server">
<span><i class="jinsom-icon jinsom-wancheng"></i> <?php _e('官方自营','jinsom');?></span>
<span><i class="jinsom-icon jinsom-wancheng"></i> <?php _e('正品保障','jinsom');?></span>
<span><i class="jinsom-icon jinsom-wancheng"></i> <?php _e('极速发货','jinsom');?></span>
</div>

</div>

<?php //评价
$number=3;
require($require_url.'/mobile/templates/page/shop/goods-star-template.php');
?>

<?php if(!$jinsom_shop_related_goods_on_off){//开启相关商品?>
<div class="jinsom-goods-single-box related">
<div class="title"><?php _e('猜你喜欢','jinsom');?>
<div class="right">
<?php 
$post_terms_arr=wp_get_post_terms($post_id,array('shop'));
$category_arr=array();
for ($i=0; $i <count($post_terms_arr);$i++){ 
array_push($category_arr,$post_terms_arr[$i]->term_id);
echo '<span><i></i>'.$post_terms_arr[$i]->name.'</span>';
}
?>
</div>
</div>
<div class="content clear">
<?php 
$style='two';
$args = array(
'post_type' => 'goods',
'showposts' => 6,
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

<div class="jinsom-goods-single-box">
<div class="title">
<li class="on"><?php _e('商品详情','jinsom');?></li>
<?php if($info_add&&$this_goods_type!='d'){?>
<li><?php _e('商品属性','jinsom');?></li>
<?php }?>
</div>
<div class="content">
<ul>
<?php 
$post_id=$_GET['post_id'];
echo do_shortcode(get_the_content('',false,$post_id));
?>
</ul>
<?php if($info_add&&$this_goods_type!='d'){?>
<ul>
<?php 
foreach ($info_add as $data) {
echo '<li><span>'.$data['name'].'：</span>'.$data['value'].'</li>';
}
?>
</ul>
<?php }?>	
</div>
</div>


<!-- 套餐选择 -->
<div class="jinsom-shop-select-form-<?php echo $post_id;?>" style="display: none;">
<div class="jinsom-shop-select-form-main">
<div class="close" onclick="layer.closeAll()"><i class="jinsom-icon jinsom-guanbi"></i></div>
<div class="jinsom-shop-select-header">
<div class="cover"><img src="<?php echo $cover_one;?>"></div>
<div class="info">
<div class="price"><?php echo $this_price_icon.$this_price_show.$this_price_discount_show;?></div>
<div class="count"><?php _e('已售','jinsom');?> <m><?php echo $this_buy_number;?></m></div>
</div>
</div>
<div class="jinsom-shop-select-content jinsom-shop-select-content-<?php echo $post_id;?>">
<?php if($this_select_add&&$this_goods_type!='a'&&$this_goods_type!='d'){?>
<div class="select-box select">
<?php 
foreach ($this_select_add as $datas){?>
<div class="list">
<div class="title"><?php echo $datas['name'];?></div>
<div class="content">
<?php 
if($datas['value_add']){
$n=0;
foreach ($datas['value_add'] as $data){
if($n==0){$on='class="on"';}else{$on='';}
echo '<li '.$on.'>'.$data['value'].'</li>';
$n++;
}
}
?>
</div>
</div>
<?php }?>
</div>
<?php }?>

<?php if($this_select_change_price_add&&$this_goods_type!='a'&&$this_goods_type!='d'){?>
<div class="select-box price">
<?php 
foreach ($this_select_change_price_add as $datas){?>
<div class="list">
<div class="title"><?php echo $datas['name'];?></div>
<div class="content">
<?php 
if($datas['value_add']){
$n=0;
foreach ($datas['value_add'] as $data){
if($n==0){$on='class="on"';}else{$on='';}
echo '<li '.$on.' price="'.$data['price'].'" price_discount="'.$data['price_discount'].'">'.$data['title'].'</li>';
$n++;
}
}
?>
</div>
</div>
<?php }?>
</div>
<?php }?>

<?php 
if($this_buy_tips){
echo '<div class="tips">'.$this_buy_tips.'</div>';
}
?>


<?php if($this_goods_type=='b'&&$this_goods_buy_user_info_add){?>
<div class="select-box pass-info">
<div class="title"><?php _e('下单信息','jinsom');?></div>
<div class="content">
<div class="list clear">
<?php 
foreach ($this_goods_buy_user_info_add as $data) {
echo '<li><span>'.$data['name'].'</span><input type="text"></li>';
}
?>
</div>
</div>
</div>
<?php }?>

<?php if($this_goods_type=='c'){?>
<div class="select-box marks">
<div class="title"><?php _e('备注信息','jinsom');?></div>
<div class="content">
<textarea id="jinsom-goods-marks" placeholder="<?php echo $this_buy_info_tips;?>"></textarea>
</div>
</div>
<?php }?>


<div class="select-box number"><span><?php _e('购买数量','jinsom');?></span>
<span class="right">
<i class="jinsom-icon jinsom-jianhao on"></i>
<input type="text" id="jinsom-goods-number" value="1" onkeyup='this.value=this.value.replace(/\D/gi,"")' maxlength="4">
<i class="jinsom-icon jinsom-hao"></i></span>
</div>
</div>
<div class="btn">
<span class="car" onclick="layer.open({content:'暂未开启！',skin:'msg',time:2});"><?php _e('确定','jinsom');?></span>
<span class="buy" onclick="jinsom_shop_buy(<?php echo $post_id;?>,this)"><?php _e('确定','jinsom');?></span>
</div>
</div>
</div>

</div>
</div>        