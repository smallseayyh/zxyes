
<div class="jinsom-post-words jinsom-post-<?php echo $post_id;?>"">
<?php require( get_template_directory() . '/mobile/templates/post/info.php' );?>
<div class="content">
<?php if(jinsom_get_option('jinsom_bbs_no_power_show_title_on_off')){?>
<h1><?php the_title();?></h1>
<?php }?>
<div class="jinsom-bbs-no-power-tips list">
<?php 
$bbs_visit_exp=(int)get_term_meta($bbs_id,'bbs_visit_exp',true);
$visit_power_pay_price=(int)get_term_meta($bbs_id,'visit_power_pay_price',true);
if($bbs_visit_power==1){?>
<i class="jinsom-icon jinsom-niming"></i> <?php _e('该内容只允许VIP用户查看','jinsom');?>
<?php }else if($bbs_visit_power==2){?>
<i class="jinsom-icon jinsom-niming"></i> <?php _e('该内容只允许认证用户查看','jinsom');?>
<?php }else if($bbs_visit_power==3){?>
<i class="jinsom-icon jinsom-niming"></i> <?php _e('该内容只允许管理团队查看','jinsom');?>
<?php }else if($bbs_visit_power==4){?>
<i class="jinsom-icon jinsom-niming"></i> <?php _e('该内容只允许拥有头衔的用户查看','jinsom');?>
<?php }else if($bbs_visit_power==6){?>
<i class="jinsom-icon jinsom-niming"></i> <?php echo sprintf(__( '该内容只允许经验值大于<m>%s</m>的用户查看','jinsom'),$bbs_visit_exp);?>
<?php }else if($bbs_visit_power==7){?>
<i class="jinsom-icon jinsom-niming"></i> <?php _e('该内容只允许指定的用户查看','jinsom');?>
<?php }else if($bbs_visit_power==8){?>
<i class="jinsom-icon jinsom-niming"></i> <?php _e('该内容只允许登录的用户查看','jinsom');?>
<?php }else if($bbs_visit_power==9){?>
<i class="jinsom-icon jinsom-niming"></i> <?php _e('该内容只允许指定头衔的用户查看','jinsom');?>
<?php }else if($bbs_visit_power==10){?>
<i class="jinsom-icon jinsom-niming"></i> <?php _e('该内容只允许指定认证类型的用户查看','jinsom');?>
<?php }else if($bbs_visit_power==11){?>
<i class="jinsom-icon jinsom-niming"></i> 该<?php echo jinsom_get_option('jinsom_bbs_name');?>需要支付<m><?php echo $visit_power_pay_price;?></m><?php echo jinsom_get_option('jinsom_credit_name');?>才允许查看
<?php }else{?>
<i class="jinsom-icon jinsom-niming"></i> 该<?php echo jinsom_get_option('jinsom_bbs_name');?>已加密，需要密码才允许查看
<?php }?>
</div>
</div>
<div class="footer">
<a><i class="jinsom-icon jinsom-xihuan2 like"></i> <span class="like_num"><?php echo jinsom_count_post(0,$post_id);?></span></a>
<a class="link comment"><i class="jinsom-icon jinsom-pinglun2 comment"></i> <span class="comment_number"><?php echo get_comments_number($post_id); ?></span></a>
<a class="link views"><i class="jinsom-icon jinsom-liulan1 views"></i> <?php echo jinsom_views_show($post_views);?></a>
<a href="#" class="link more" onclick="jinsom_post_more_form(<?php echo $post_id;?>,<?php echo (int)$bbs_id;?>,'no')"><i class="jinsom-icon jinsom-gengduo2"></i></a>
</div>
</div>