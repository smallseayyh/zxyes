<?php $title_color=get_post_meta($post_id,'title_color',true);//标题颜色 ?>
<div class="jinsom-bbs-list-lattice jinsom-post-<?php echo $post_id;?>">
<?php if($commend_post){echo '<span class="commend">精</span>';}?>
<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link">
<?php echo jinsom_get_bbs_img(get_the_content(),$post_id,1);?>
<div class="content">
<div class="title" <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>><?php the_title();?></div>
<div class="footer clear">
<span class="time"><?php echo (int)get_post_meta($post_id,'post_price',true).jinsom_get_option('jinsom_credit_name');?></span>
<span class="views"><i class="jinsom-icon jinsom-goumai2"></i> <?php echo (int)get_post_meta($post_id,'buy_times',true);?></span>
</div>
</div>
</a>
</div>