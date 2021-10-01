<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_bbs_info', array(
'title'       => 'LightSNS_'.__('论坛信息','jinsom'),
'classname'   => 'jinsom-widget-bbs-info',
'description' => __('此小工具必须只能放在论坛页面或者帖子页面','jinsom'),
'fields'      => array(

array(
'type'    => 'content',
'content' => '<p>'.__('注意','jinsom').'：<font style="color:#f00">'.__('这个小工具必须使用在论坛页面的侧栏或帖子页面的侧栏','jinsom').'</font></p>',
),

)
));


if(!function_exists('jinsom_widget_bbs_info')){
function jinsom_widget_bbs_info($args,$instance){
echo $args['before_widget'];
global $bbs_id;
if(is_category()||is_single()){
?>
<div class="jinsom-widget-bbs-info-avatar">
<a href="<?php echo get_category_link($bbs_id);?>"><?php echo jinsom_get_bbs_avatar($bbs_id,0);?></a>
</div>
<div class="jinsom-widget-bbs-info-name"><?php echo '<a href="'.get_category_link($bbs_id).'">'.get_category($bbs_id)->name.'</a>';?></div>
<div class="jinsom-widget-bbs-info-number">
<li><span><?php _e('今日','jinsom');?></span> <em><?php  echo (int)get_term_meta($bbs_id,'today_publish',true);?></em></li>
<li><span><?php _e('帖子','jinsom');?></span> <em><?php  echo jinsom_get_bbs_post($bbs_id);?></em></li>
<li><span><?php _e('关注','jinsom');?></span> <em><?php echo jinsom_get_bbs_like_number($bbs_id);?></em></li>
</div>
<?php 
}else{
echo __('此小工具必须只能放在论坛页面或者帖子页面','jinsom');
}
echo $args['after_widget'];
}
}
