<?php 
//收藏
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$jinsom_collect_menu = jinsom_get_option('jinsom_collect_menu');
$enabled=$jinsom_collect_menu['enabled'];

if($enabled){
$first_menu=key($enabled);	
}else{
$first_menu='all';
}
?>
<div data-page="collect" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('我的收藏','jinsom');?></div>
<div class="right">
<a href="<?php echo get_template_directory_uri();?>/mobile/templates/page/collect-img.php" class="link icon-only"><?php _e('图片','jinsom');?></a>
</div>

<?php if($enabled){?>
<div class="subnavbar">
<?php 
echo '<div class="jinsom-collect-tab jinsom-home-menu">';
foreach($enabled as $x=>$x_value) {
switch($x){
case 'all':    
if(key($enabled)==$x){$on='class="on"';}else{$on='';}
echo '<li type="'.$x.'" '.$on.'  onclick=\'jinsom_collect_post("'.$x.'",this)\'>'.__('全部','jinsom').'</li>'; 
break; 
case 'bbs':  
if(key($enabled)==$x){$on='class="on"';}else{$on='';}  
echo '<li type="'.$x.'" '.$on.'  onclick=\'jinsom_collect_post("'.$x.'",this)\'>'.__('帖子','jinsom').'</li>'; 
break; 
case 'words': 
if(key($enabled)==$x){$on='class="on"';}else{$on='';}   
echo '<li type="'.$x.'" '.$on.'  onclick=\'jinsom_collect_post("'.$x.'",this)\'>'.__('动态','jinsom').'</li>'; 
break;
case 'music':  
if(key($enabled)==$x){$on='class="on"';}else{$on='';}  
echo '<li type="'.$x.'" '.$on.'  onclick=\'jinsom_collect_post("'.$x.'",this)\'>'.__('音乐','jinsom').'</li>'; 
break;
case 'single': 
if(key($enabled)==$x){$on='class="on"';}else{$on='';}
echo '<li type="'.$x.'" '.$on.'  onclick=\'jinsom_collect_post("'.$x.'",this)\'>'.__('文章','jinsom').'</li>'; 
break;
case 'video': 
if(key($enabled)==$x){$on='class="on"';}else{$on='';}
echo '<li type="'.$x.'" '.$on.'  onclick=\'jinsom_collect_post("'.$x.'",this)\'>'.__('视频','jinsom').'</li>'; 
break;
case 'goods':    
if(key($enabled)==$x){$on='class="on"';}else{$on='';}
echo '<li type="'.$x.'" '.$on.'  onclick=\'jinsom_collect_post("'.$x.'",this)\'>'.__('商品','jinsom').'</li>'; 
break; 
case 'redbag':    
if(key($enabled)==$x){$on='class="on"';}else{$on='';}
echo '<li type="'.$x.'" '.$on.'  onclick=\'jinsom_collect_post("'.$x.'",this)\'>'.__('红包','jinsom').'</li>'; 
break; 
}}

echo '</div>';
?>
</div>
<?php }?>




</div>
</div>

<div class="page-content jinsom-collect-content hide-navbar-on-scroll infinite-scroll" data-distance="200">
<div class="jinsom-post-list">
<?php 
$number=get_option('posts_per_page',10);//显示数量
if($first_menu=='all'){
$args = array(
'showposts' => $number,
'post_status' => 'publish',
);
}else if($first_menu=='words'||$first_menu=='music'||$first_menu=='video'||$first_menu=='single'||$first_menu=='redbag'){
$args = array(
'showposts' => $number,
'post_status' => 'publish',
'meta_key' => 'post_type',
'meta_value'=>$first_menu,
);
}else if($first_menu=='bbs'){
$args = array(
'showposts' => $number,
'post_parent'=>999999999,
'post_status' => 'publish',
);
}else if($first_menu=='goods'){
	
}

$jinsom_collect_post_arr=jinsom_collect_post_arr($user_id);
if(!$jinsom_collect_post_arr){
$args['cat']=array(999999);
}
$args['post__in']=$jinsom_collect_post_arr;
$args['ignore_sticky_posts']=1;
$args['no_found_rows']=true;
$args['orderby']='post__in';
query_posts($args);
if(have_posts()){
while(have_posts()) : the_post();
require(get_template_directory().'/mobile/templates/post/post-list.php' );	
endwhile;
}else{
echo jinsom_empty();	
}
?>



</div>
</div>
</div>        