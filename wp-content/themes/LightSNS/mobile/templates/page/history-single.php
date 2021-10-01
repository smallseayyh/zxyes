<?php 
//历史浏览
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
?>
<div data-page="history-single" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('历史浏览','jinsom');?></div>
<div class="right">
<?php if($_COOKIE['history_single']){?>
<a href="#" onclick="jinsom_history_single_clear(this)" class="link icon-only"><?php _e('清空','jinsom');?></a>
<?php }else{?>
<a href="#" class="link icon-only"></a>
<?php }?>
</div>
</div>
</div>

<div class="page-content jinsom-history-single-content">

<?php 
$number=30;
$history_single=$_COOKIE['history_single'];
$history_single_arr=explode(",",$history_single);
$history_single_arr=array_reverse($history_single_arr);
$args = array(
'showposts' => $number,
'post_status' => 'publish',
);
if($history_single){
$args['post__in']=$history_single_arr;
$args['ignore_sticky_posts']=1;
$args['no_found_rows']=true;
$args['orderby']='post__in';
}else{
$args['cat']=array(999999);
}

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
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>