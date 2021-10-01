<?php 
//SNS首页-内页
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="sns" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left"><a href="#" class="back link icon-only"><i class="jinsom-icon jinsom-fanhui2"></i></a></div>
<div class="center sliding"><?php echo do_shortcode(jinsom_get_option('jinsom_mobile_home_header_name'));?></div>
<div class="right"><a href="<?php echo get_template_directory_uri();?>/mobile/templates/page/search.php" class="link icon-only"><i class="jinsom-icon jinsom-sousuo1"></i></a>
</div>


<?php 
//sns菜单
$jinsom_sns_home_menu_add=jinsom_get_option('jinsom_sns_home_menu_add');
if($jinsom_sns_home_menu_add){
if(count($jinsom_sns_home_menu_add)>=1){
$type=$jinsom_sns_home_menu_add[0]['jinsom_sns_home_menu_type'];
echo '<div class="subnavbar">';
echo '<div class="jinsom-home-menu clear">';
$i=1;
foreach($jinsom_sns_home_menu_add as $data){
if(!$data['in_pc']){
if($i==1){$on='class="on"';}else{$on='';}
$sns_menu_type=$data['jinsom_sns_home_menu_type'];
if($sns_menu_type=='custom-link'){
echo '<li type="link" '.$on.' onclick=\'jinsom_post_link(this)\' data="'.$data['link'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='custom-bbs'){
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['bbs_id'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='custom-topic'){
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['topic_id'].'">'.$data['name'].'</li>';
}else if($sns_menu_type=='hot'){
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\' data="'.$data['time'].'">'.$data['name'].'</li>';
}else{
echo '<li type="'.$sns_menu_type.'" '.$on.' onclick=\'jinsom_post("'.$sns_menu_type.'","ajax",this)\'>'.$data['name'].'</li>'; 
}
$i++;
}else{
echo '<li style="display:none;"></li>';
}
}
echo '</div>';
echo '<i class="jinsom-icon jinsom-paixu" onclick="jinsom_sort()"></i>';
echo '</div>';
}
}else{
$type='all';
echo '<style>.jinsom-sns-page-content .pull-to-refresh-layer{position: absolute;}</style>';
}
?>

</div>
</div>

<div class="jinsom-sns-page-content page-content home pull-to-refresh-content infinite-scroll hide-navbar-on-scroll hide-tabbar-on-scroll" data-distance="200">
<?php require(get_template_directory().'/mobile/templates/index/sns-show.php');?>
</div>

</div>
</div>        