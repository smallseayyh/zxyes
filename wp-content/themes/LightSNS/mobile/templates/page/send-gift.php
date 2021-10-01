<?php 
//赠送礼物
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$author_id=(int)$_GET['author_id'];
$post_id=(int)$_GET['post_id'];
$credit=(int)get_user_meta($user_id,'credit',true);
$jinsom_gift_add=jinsom_get_option('jinsom_gift_add');
$theme_url=get_template_directory_uri();
?>
<div data-page="send-gift" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('赠送礼物','jinsom');?></div>
<div class="right">
<a href="<?php echo $theme_url;?>/mobile/templates/page/my-gift.php?author_id=<?php echo $author_id;?>" class="link icon-only"><i class="jinsom-icon jinsom-gengduo2" style="font-size: 8vw;"></i></a>
</div>
</div>
</div>

<div class="toolbar toolbar-bottom jinsom-send-gift-toolbar">
<div class="toolbar-inner">
<span class="credit"><i class="jinsom-icon jinsom-jinbi"></i> <?php echo $credit;?></span>
<span class="send" onclick="jinsom_send_gift(<?php echo $author_id;?>,<?php echo $post_id;?>)"><i></i><m><?php _e('赠送','jinsom');?></m></span>
</div>
</div>

<div class="page-content">
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
<div class="bottom" data="'.$m.'">'.$data['credit'].jinsom_get_option('jinsom_credit_name').'</div>
</li>';
}
}else{
echo '请在后台-主题设置-礼物模块 添加礼物数据！';
}

?>
</div>
</div>

</div>
</div>        