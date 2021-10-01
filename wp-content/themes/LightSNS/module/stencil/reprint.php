<?php
//弹窗转发
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(isset($_POST['post_id'])){
$post_id=$_POST['post_id'];
$post_power=get_post_meta($post_id,'post_power',true);
$reprint_post_id=get_post_meta($post_id,'reprint_post_id',true);
$site_name = jinsom_get_option('jinsom_site_name');
?>
<div class="jinsom-reprint-content">
<?php if($post_power==0&&jinsom_get_option('jinsom_publish_reprint_on_off')&&get_post_type($post_id)=='post'){//公开 ?>
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title reprint">
<li class="layui-this"><?php _e('第三方分享','jinsom');?></li>
<li><?php _e('转发到主页','jinsom');?></li>
</ul>

<div class="layui-tab-content">


<div class="layui-tab-item layui-show">
<?php 
$referral_link_name = jinsom_get_option('jinsom_referral_link_name');
$url=get_the_permalink($post_id);
if(strpos($url,'?')){
$url=$url.'&'.$referral_link_name.'='.$user_id;
}else{
$url=$url.'?'.$referral_link_name.'='.$user_id;  
}
//$url=jinsom_share_url($url);
$title=get_the_title($post_id);
if(!$title){
$title=$site_name;
}

?>

<div class="jinsom-widget-share">
<div class="content">
<div class="link clear" style="border: none;">
<p><?php _e('本页推广链接','jinsom');?>：</p>
<div class="list">
<span title="<?php echo $url;?>" id="jinsom-single-share-link"><?php echo $url;?></span>
</div>
<n data-clipboard-target="#jinsom-single-share-link" id="jinsom-copy-share-link-single"><?php _e('复制','jinsom');?></n> 
</div> 
<div class="social clear">
<p><?php _e('其他平台分享','jinsom');?>：</p>
<div class="list clear">
<li onclick="jinsom_popop_share_code('<?php echo $url;?>','分享到微信','打开微信扫一扫')"><i class="jinsom-icon jinsom-pengyouquan"></i></li> 
<li><a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=<?php echo $title;?>&url=<?php echo $url;?>" target="_blank"><i class="jinsom-icon jinsom-qqkongjian2"></i></a></li> 
<li onclick="jinsom_popop_share_code('<?php echo $url;?>','分享到QQ','打开QQ扫一扫')"><i class="jinsom-icon jinsom-qq"></i></li> 
<li><a href="http://service.weibo.com/share/share.php?title=<?php echo $title;?>&url=<?php echo $url;?>" target="_blank"><i class="jinsom-icon jinsom-weibo"></i></a></li> 
</div>  
</div> 
</div>

</div>
</div>


<div class="layui-tab-item">
<div class="jinsom-reprint-textarea">
<textarea id="jinsom-reprint-value" placeholder="<?php _e('想说点什么','jinsom');?>" <?php if(!comments_open($post_id)){echo 'style="height:130px;"';}?>></textarea>
<span class="jinsom-single-expression-btn">
<i class="jinsom-icon expression jinsom-weixiao-" onclick="jinsom_smile(this,'normal','')"></i>
</span>
</div>

<div class="layui-form jinsom-reprint-check">
<?php if(comments_open($post_id)){?>
<p><input type="checkbox" lay-skin="primary" title="<?php _e('评论给当前内容','jinsom');?>" checked="" id="jinsom-reprint-check-a"></p>
<?php }?>
<?php if($reprint_post_id){ ?>
<p><input type="checkbox" lay-skin="primary" title="<?php _e('评论给原文','jinsom');?>" checked="" id="jinsom-reprint-check-b"></p>
<?php }?>
</div>

<?php if($reprint_post_id){//二级分享 ?>
<div class="jinsom-reprint-btn opacity" onclick="jinsom_reprint_again(<?php echo $post_id;?>);"><?php _e('转发到我的主页','jinsom');?></div>
<?php }else{?>
<div class="jinsom-reprint-btn opacity" onclick="jinsom_reprint(<?php echo $post_id;?>);"><?php _e('转发到主页','jinsom');?></div>
<?php }?>
</div>






</div>
</div>

<?php }else{?>
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title reprint">
<li class="layui-this"><?php _e('第三方分享','jinsom');?></li>
</ul>

<div class="layui-tab-content">
<div class="layui-tab-item layui-show">
<?php 
$referral_link_name = jinsom_get_option('jinsom_referral_link_name');
$url=get_the_permalink($post_id);
if(strpos($url,'?')){
$url=$url.'&'.$referral_link_name.'='.$user_id;
}else{
$url=$url.'?'.$referral_link_name.'='.$user_id;  
}
//$url=jinsom_share_url($url);
$title=get_the_title($post_id);
if(!$title){
$title=$site_name;
}

?>

<div class="jinsom-widget-share">
<div class="content">
<div class="link clear" style="border: none;">
<p><?php _e('本页推广链接','jinsom');?>：</p>
<div class="list">
<span title="<?php echo $url;?>" id="jinsom-single-share-link"><?php echo $url;?></span>
</div>
<n data-clipboard-target="#jinsom-single-share-link" id="jinsom-copy-share-link-single"><?php _e('复制','jinsom');?></n> 
</div> 
<div class="social clear">
<p><?php _e('其他平台分享','jinsom');?>：</p>
<div class="list clear">
<li onclick="jinsom_popop_share_code('<?php echo $url;?>','分享到微信','打开微信扫一扫')"><i class="jinsom-icon jinsom-pengyouquan"></i></li> 
<li><a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?title=<?php echo $title;?>&url=<?php echo $url;?>" target="_blank"><i class="jinsom-icon jinsom-qqkongjian2"></i></a></li> 
<li onclick="jinsom_popop_share_code('<?php echo $url;?>','分享到QQ','打开QQ扫一扫')"><i class="jinsom-icon jinsom-qq"></i></li> 
<li><a href="http://service.weibo.com/share/share.php?title=<?php echo $title;?>&url=<?php echo $url;?>" target="_blank"><i class="jinsom-icon jinsom-weibo"></i></a></li> 
</div>  
</div> 
</div>

</div>
</div>
</div>
</div>

<?php }?>

</div>
<?php }?>