<?php if(!defined( 'ABSPATH')){die;}
LightSNS::createWidget( 'jinsom_widget_share', array(
'title'       => 'LightSNS_'.__('推广、分享模块','jinsom'),
'classname'   => 'jinsom-widget-share',
'description' => __('侧栏显示分享小工具','jinsom'),
'fields'      => array(

array(
'id'      => 'title',
'type'    => 'text',
'title'   => __('标题','jinsom'),
'desc'   => __('留空则不显示标题','jinsom'),
),

)
));


if(!function_exists('jinsom_widget_share')){
function jinsom_widget_share($args,$instance){
$current_user = wp_get_current_user();
$user_id=$current_user->ID;
$credit= (int)get_user_meta($user_id,'credit',true);
$invite_number=(int)get_user_meta($user_id,'invite_number',true);
$referral_link_name = jinsom_get_option('jinsom_referral_link_name');
$url=home_url(add_query_arg(array()));
if($user_id){
if(strpos($url,'?')){
$url=$url.'&'.$referral_link_name.'='.$user_id;
}else{
$url=$url.'?'.$referral_link_name.'='.$user_id;  
}
}
$referral_times=(int)get_user_meta($user_id,'referral_times',true);

// $url=jinsom_share_url($url);

echo $args['before_widget'];
if (!empty($instance['title'])){
echo $args['before_title'] . apply_filters('widget_title',$instance['title']).$args['after_title'];
}
?>
<?php if(is_user_logged_in()){?>
<div class="header">
<i class="jinsom-icon jinsom-yiwen2" onclick="jinsom_referral_info()"></i>
<span>我的<?php echo jinsom_get_option('jinsom_credit_name');?>：<m><?php echo $credit;?></m></span>
<span><?php _e('已推广人数','jinsom');?>：<m><?php echo $invite_number;?></m></span>
</div>
<?php }?>

<div class="content">

<?php if(is_user_logged_in()){?>	
<div class="link clear">
<p><?php _e('本页链接','jinsom');?>：</p>
<div class="list">
<span title="<?php echo $url;?>" id="jinsom-sidebar-share-link"><?php echo $url;?></span>
</div>
<n data-clipboard-target="#jinsom-sidebar-share-link" id="jinsom-copy-share-link"><?php _e('复制','jinsom');?></n> 
</div> 
<?php }else{?>
<div class="link clear">
<p><?php _e('本页链接','jinsom');?>：</p>
<div class="list">
<span title="<?php echo $url;?>" id="jinsom-sidebar-share-link"><?php echo $url;?></span>
</div>
<n data-clipboard-target="#jinsom-sidebar-share-link" id="jinsom-copy-share-link"><?php _e('复制','jinsom');?></n> 
</div> 
<?php }?>


<div class="social clear">
<p><?php _e('其他平台分享','jinsom');?>：</p>
<div class="list clear">
<li onclick="jinsom_popop_share_code('<?php echo $url;?>','分享到微信','打开微信扫一扫')"><i class="jinsom-icon jinsom-pengyouquan"></i></li> 
<li onclick="jinsom_sidebar_share_qzone()"><i class="jinsom-icon jinsom-qqkongjian2"></i></li> 
<li onclick="jinsom_popop_share_code('<?php echo $url;?>','分享到QQ','打开QQ扫一扫')"><i class="jinsom-icon jinsom-qq"></i></li> 
<li onclick="jinsom_sidebar_share_weibo()"><i class="jinsom-icon jinsom-weibo"></i></li> 
</div>  
</div> 
</div>
<?php if(is_user_logged_in()){?>
<div class="footer"><?php _e('今日推广链接有效点击次数','jinsom');?>：<span><?php echo $referral_times;?></span></div>
<?php }?>
<?php 
echo $args['after_widget'];
}
}

