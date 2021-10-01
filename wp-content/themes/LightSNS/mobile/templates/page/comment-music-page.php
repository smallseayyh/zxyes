<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$comments_number=get_comments_number($post_id);
$comment_post_credit = jinsom_get_option('jinsom_comment_post_credit');
?>
<div data-page="comment-list-music" class="page no-tabbar comment-post">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('评论','jinsom');?></div>
<div class="right">
<a class="link icon-only"></a>
</div>
</div>
</div>

<?php require($require_url.'/mobile/templates/post/comment-toolbar.php');?>

<div class="page-content" style="background: #fff;" id="jinsom-comment-child-page">


<?php require( get_template_directory() . '/mobile/templates/post/comment.php' );?>


</div>
</div>        