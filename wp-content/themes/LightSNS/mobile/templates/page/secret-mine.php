<?php 
//我的秘密
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$args = array(
'post_type' =>'secret',
'post_status'=>'publish',
'author'=>$user_id,
);	
$args['no_found_rows']=true;
$args['showposts']=10;
query_posts($args);
?>
<div data-page="secret-mine" class="page no-tabbar">
<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">我的</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>
<div class="page-content jinsom-secret-mine-content infinite-scroll" data-distance="200">

<div class="jinsom-post-secret-list mine">
<?php 
if($user_id){
if(have_posts()){
while (have_posts()):the_post();
require(get_template_directory().'/post/secret.php');
endwhile;
}else{
echo jinsom_empty();	
}
}else{
echo jinsom_empty('你还没有登录');	
}
?>

</div>

</div>
</div>        