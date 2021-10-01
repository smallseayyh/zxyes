<?php 
//普通页面模版

update_post_meta($post_id,'post_views',$post_views+1);//更新内容浏览量
require($require_url.'/post/info.php' );//引入头部信息

echo '<h1>'.$title.'</h1>';//标题

?>


<div class="jinsom-post-content">

<?php echo do_shortcode(convert_smilies(wpautop($content_source)));?>

</div>
<?php 
require($require_url.'/post/bar.php' );
jinsom_post_like_list($post_id);//喜欢列表
