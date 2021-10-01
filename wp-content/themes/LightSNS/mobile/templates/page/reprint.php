<?php 
require( '../../../../../../wp-load.php');
$post_id=$_GET['post_id'];
$user_id=$current_user->ID;
$reprint_post_id=get_post_meta($post_id,'reprint_post_id',true);
?>
<div data-page="reprint" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"></div>
<div class="right">

<a class="link icon-only" onclick="jinsom_reprint(<?php echo $post_id;?>)"><?php _e('转发','jinsom');?></a>

</div>
</div>
</div>

<div class="page-content" style="background: #fff;" id="jinsom-comment-page">


<textarea class="resizable" id="jinsom-reprint-value" placeholder="<?php _e('说点什么呢','jinsom');?>"></textarea>

<div class="jinsom-reprint-check">
<?php if(comments_open($post_id)){?>
<li><input type="checkbox"  id="jinsom-reprint-check-a" value="1" checked=""><span><?php _e('评论给当前内容','jinsom');?></span></li>
<?php }?>
<?php if($reprint_post_id){ ?>
<li><input type="checkbox"  id="jinsom-reprint-check-b" value="1" checked=""><span><?php _e('评论给原文','jinsom');?></span></li>
<?php }?>
</div>

</div>
</div>        