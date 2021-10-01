<?php $title_color=get_post_meta($post_id,'title_color',true);//标题颜色 ?>
<div class="jinsom-bbs-list-default jinsom-post-<?php echo $post_id;?>">
<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link">
<div class="avatarimg"><?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?><?php echo jinsom_verify($author_id);?></div>
<div class="info">
<h2 <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>><?php if($commend_post){echo '<span class="commend">精</span>';}?><?php the_title();?></h2>
<div class="user">
<span class="name"><?php echo jinsom_nickname($author_id);?></span>
<span class="time"><?php echo jinsom_timeago(get_the_time('Y-m-d G:i:s'));?></span>
<span class="number"><i class="jinsom-icon jinsom-liaotian"></i> <?php echo get_comments_number($post_id); ?></span>
</div>
</div>
</a>
</div>