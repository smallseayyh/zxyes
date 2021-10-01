<?php 
//页面模块
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;



$post_id=$_GET['post_id'];
$post_data = get_post($post_id, ARRAY_A);
$author_id=$post_data['post_author'];
$content=do_shortcode(convert_smilies(wpautop($post_data['post_content'],$post_id)));
$title=$post_data['post_title'];
$post_date=$post_data['post_date'];
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);
$reprint_post_id=get_post_meta($post_id,'reprint_post_id',true);	

$post_price=get_post_meta($post_id,'post_price',true);
$post_power=get_post_meta($post_id,'post_power',true);
$hide_content=get_post_meta($post_id,'pay_cnt',true);//隐藏内容
$post_img=get_post_meta($post_id,'post_img',true);

//更新内容浏览量
$post_views=(int)get_post_meta($post_id,'post_views',true);
update_post_meta($post_id,'post_views',($post_views+1));

$comments_number=get_comments_number($post_id);
$comment_post_credit = jinsom_get_option('jinsom_comment_post_credit');
?>
<div data-page="post-single" class="page no-tabbar post-page">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $title;?></div>
<div class="right">
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="link icon-only">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
<?php echo jinsom_verify($author_id);?>
</a>
</div>
</div>
</div>

<?php require($require_url.'/mobile/templates/post/comment-toolbar.php');?>

<div class="page-content keep-toolbar-on-scroll infinite-scroll jinsom-page-single-content jinsom-page-single-content-<?php echo $post_id;?>" data-distance="200">

<div class="jinsom-single jinsom-post-<?php echo $post_id;?>">

<div class="jinsom-post-single-content">
<?php echo $content;?>
</div>


</div>

<?php require( get_template_directory() . '/mobile/templates/post/comment.php' );?>


</div>
</div>        