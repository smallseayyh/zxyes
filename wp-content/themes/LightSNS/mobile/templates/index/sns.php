<!-- SNS首页-Tab -->
<div id="jinsom-view-sns-<?php echo $index_i;?>" class="view tab <?php echo $active;?>" data-page="view-sns">

<div class="navbar jinsom-home-navbar">
<div class="navbar-inner">
<?php require(get_template_directory().'/mobile/templates/index/navbar.php');?>
<?php 
//sns菜单
$type='';
$jinsom_sns_home_menu_add=jinsom_get_option('jinsom_sns_home_menu_add');
if($jinsom_sns_home_menu_add){
if(count($jinsom_sns_home_menu_add)==1){
echo '<style>.jinsom-home-sns-subnavbar{display:none;}.jinsom-sns-page-content.home {padding-top: 0;}</style>';
}

if(count($jinsom_sns_home_menu_add)>=1){
// $type=$jinsom_sns_home_menu_add[0]['jinsom_sns_home_menu_type'];
echo '<div class="subnavbar jinsom-home-sns-subnavbar">';
echo '<div class="jinsom-home-menu sns clear">';
$i=1;
foreach($jinsom_sns_home_menu_add as $data){
if(!$data['in_pc']){
if($i==1){$on='class="on"';}else{$on='';}
$sns_menu_type=$data['jinsom_sns_home_menu_type'];
if($data['waterfall']){$waterfall=1;}else{$waterfall=0;}
$waterfall='waterfall="'.$waterfall.'"';
if(!$type){$type=$sns_menu_type;}//获取第一个有效的类型
if($sns_menu_type=='custom-link'){
echo '<li type="link" '.$on.' onclick=\'jinsom_post_link(this)\' data="'.$data['link'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='custom-bbs'){
echo '<li type="'.$sns_menu_type.'" '.$on.' '.$waterfall.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['bbs_id'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='custom-topic'){
echo '<li type="'.$sns_menu_type.'" '.$on.' '.$waterfall.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['topic_id'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='hot'||$sns_menu_type=='rand'){
echo '<li type="'.$sns_menu_type.'" '.$on.' '.$waterfall.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['time'].'">'.$data['name'].'</li>';
}else{
echo '<li type="'.$sns_menu_type.'" '.$on.' '.$waterfall.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\'>'.$data['name'].'</li>'; 
}
$i++;
}else{
echo '<li style="display:none;"></li>';
}
}
echo '</div>';
echo '<i class="jinsom-icon jinsom-paixu2" onclick="jinsom_sort()"></i>';
echo '</div>';
}
}else{
$type='all';
echo '<style>.jinsom-sns-page-content .pull-to-refresh-layer{position: absolute;}</style>';
}
?>

</div>
</div>

<div class="pages navbar-through">
<div data-page="jinsom-home-page" class="page">
<div class="jinsom-sns-page-content page-content home pull-to-refresh-content infinite-scroll <?php echo $hide_navbar_class;?> <?php echo $hide_toolbar_class;?>" data-distance="500"><!-- 内容区 -->
<?php require(get_template_directory().'/mobile/templates/index/sns-show.php');?>
</div>
</div>
</div>

</div>


<script type="text/javascript">
jinsom_index_sns_js_load();//需要执行的js
<?php if(jinsom_get_option('jinsom_mobile_sns_slider_add')){?>
$('#jinsom-sns-slider').owlCarousel({
items: 1,
margin:15,
<?php if(jinsom_get_option('jinsom_mobile_sns_slider_autoplay')){?>
autoplay:true,
autoplayTimeout:5000,
<?php }?>
loop: true,
});
<?php }?>
</script>