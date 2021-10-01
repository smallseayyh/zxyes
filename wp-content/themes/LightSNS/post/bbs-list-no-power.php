<div class="jinsom-post-user-info bbs">
<div class="jinsom-post-user-info-avatar" user-data="<?php echo $author_id; ?>">
<a href="<?php echo jinsom_userlink($author_id);?>" style="display: inline-block;">
<?php echo jinsom_vip_icon($author_id);?>
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
<?php echo jinsom_verify($author_id);?>
</a>
<div class="jinsom-user-info-card"></div>
</div>

<div class="jinsom-post-user-info-name"><?php echo jinsom_nickname_link($author_id);?></div>
</div><!-- 作者信息 -->

<?php if(jinsom_get_option('jinsom_bbs_no_power_show_title_on_off')){?>
<h2 class="single">
<a href="<?php echo $permalink; ?>" target="_blank"><?php echo $title; ?></a>
</h2>
<?php }?>

<div class="jinsom-post-single-content b">


<div class="jinsom-post-single-excerp no">
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

<div class="jinsom-post-single-bar single">
<li class="author">
<a href="<?php echo jinsom_userlink($author_id);?>" target="_blank">
<?php echo jinsom_avatar($author_id, '40' , avatar_type($author_id) );?>
<?php echo jinsom_verify($author_id);?>
</a>
<?php echo jinsom_nickname_link($author_id);?>
</li>

<li><a href="<?php echo $permalink; ?>" target="_blank"><i class="jinsom-icon jinsom-pinglun2"></i> <?php comments_number('0','1','%'); ?></a></li>

<li><i class="jinsom-icon jinsom-liulan1"></i> <span><?php echo jinsom_views_show($post_views);?></span></li>

<li>
<i class="jinsom-icon jinsom-fenlei1"></i> <span><a href="<?php echo get_category_link($child_cat_id);?>"><?php echo $child_name;?></a></span>
</li>

<li class="right">
<?php
$time_a=time ()- ( 1  *  24  *  60  *  60 );
if(get_the_time('Y-m-d')==date('Y-m-d',time())){
$time= '今天 '.get_the_time('H:i');
}else if(get_the_time('Y-m-d')==date('Y-m-d',$time_a)){
$time= '昨天 '.get_the_time('H:i');
}else{
$time= get_the_time('m-d H:i');
}
echo $time;?> 
<?php echo jinsom_post_from($post_id);//来自
?>
</li>

</div>

</div>