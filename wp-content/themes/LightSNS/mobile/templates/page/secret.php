<?php 
//树洞
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$args = array(
'post_type' =>'secret',
'post_status'=>'publish',
);	
$args['no_found_rows']=true;
$args['showposts']=10;
query_posts($args);
?>
<div data-page="secret" class="page no-tabbar">
<div class="jinsom-bbs-publish-icon" onclick="jinsom_publish_power('secret',0,'')"><i class="jinsom-icon jinsom-fabiao-"></i></div>
<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo jinsom_get_option('jinsom_secret_header_name');?></div>
<div class="right">
<a href="<?php echo get_template_directory_uri();?>/mobile/templates/page/secret-mine.php" class="link icon-only">我的</a>
</div>
<div class="subnavbar">
<div class="jinsom-secret-menu">
<li class="on" data="new" onclick="jinsom_secret_post('new','ajax',this)">最新</li>
<li data="hot" onclick="jinsom_secret_post('hot','ajax',this)">热门</li>
<li data="rand" onclick="jinsom_secret_post('rand','ajax',this)">穿越</li>
</div>
</div>
</div>
</div>
<div class="page-content jinsom-secret-content pull-to-refresh-content infinite-scroll" data-distance="200">
<div class="pull-to-refresh-layer">
<div class="pull-to-refresh-arrow"><i class="jinsom-icon jinsom-xialashuaxin"></i></div>
</div>

<div class="jinsom-post-secret-list">
<?php 
if(have_posts()){
while (have_posts()):the_post();
require(get_template_directory().'/post/secret.php');
endwhile;
}else{
echo jinsom_empty();	
}
?>

</div>

</div>
</div>        