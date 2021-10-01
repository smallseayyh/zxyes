<?php 
//直播页面
require( '../../../../../../wp-load.php');
if($_GET['post_id']){
$post_id=(int)$_GET['post_id'];
$user_id=$current_user->ID;
$post_views=(int)get_post_meta($post_id,'post_views',true);//浏览量
update_post_meta($post_id,'post_views',$post_views+1);//更新内容浏览量
$live_data=get_post_meta($post_id,'video_live_page_data',true);
$live_url=$live_data['jinsom_video_live_url'];//直播地址
$live_img=$live_data['jinsom_video_live_img'];//直播封面
$live_user_id=$live_data['jinsom_video_live_user_id'];//直播用户ID
$live_views_number=(int)$live_data['jinsom_video_live_views_number'];//直播观看人数
$live_images_upload_on_off=$live_data['jinsom_video_live_images_upload_on_off'];//直播互动评论是否允许上传图片
$live_reward_on_off=$live_data['jinsom_video_live_reward_on_off'];
$post_views=$post_views*$live_views_number;//最终观看人数
$jinsom_video_live_jingcai_add=$live_data['jinsom_video_live_jingcai_add'];//精彩瞬间
$comment_number=get_comments_number($post_id);
}
?>
<div data-page="live" class="page no-tabbar setting no-navbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>


<div class="toolbar jinsom-live-toolbar messagebar messagebar-init" data-max-height="100">
<div class="toolbar-inner">
<?php if(!is_user_logged_in()){?>
<div class="jinsom-live-toolbar-nologin open-login-screen"></div>
<?php }?>
<i class="jinsom-icon jinsom-hongbao" onclick="jinsom_reward_form(<?php echo $post_id;?>,'live');"></i>
<textarea id="jinsom-live-comment-content" placeholder="<?php echo $live_data['jinsom_video_live_comment_placeholder'];?>"></textarea>
<span class="link btn" onclick="jinsom_comment_live(<?php echo $post_id;?>)"><?php _e('发送','jinsom');?></span>
</div>
</div>


<div class="page-content jinsom-live-content jinsom-live-content-<?php echo $post_id;?>" count="<?php echo $comment_number;?>" post_id="<?php echo $post_id;?>">
<div class="jinsom-live-page-header">
<div class="back"><i class="jinsom-icon jinsom-fanhui2"></i></div>
<div class="reward-list clear">
<?php 
global $wpdb;
$table_name=$wpdb->prefix.'jin_notice';
$datas=$wpdb->get_results("SELECT user_id,sum(remark) as reward_number FROM $table_name WHERE post_id='$post_id' GROUP BY user_id order by reward_number DESC limit 3");
if($datas){
foreach ($datas as $data){
$reward_user_id=$data->user_id;
echo '<li><a href="'.jinsom_mobile_author_url($reward_user_id).'" class="link">'.jinsom_avatar($reward_user_id,'40',avatar_type($reward_user_id)).'</a><p>'.jinsom_views_show($data->reward_number).'</p></li>';
}
}

?>
<li class="count"><?php echo jinsom_views_show($post_views);?> <?php _e('人气','jinsom');?></li>
</div>
<?php if($live_url){?>	
<div id="jinsom-video-live" data="<?php echo $live_url;?>" cover="<?php echo $live_img;?>"></div>
<?php }else{?>
<div class="jinsom-no-video-live"><i class="jinsom-icon jinsom-huabanfuben"></i> <?php _e('直播还没有开始，请耐心等待~','jinsom');?></div>
<?php }?>
<div class="jinsom-live-page-nav">
<li class="on comment"><?php _e('评论','jinsom');?><span> (<?php echo $comment_number;?><?php _e('人','jinsom');?>)</span></li>
<li><?php echo $live_data['jinsom_video_live_tab_desc_name'];?></li>
<?php if($jinsom_video_live_jingcai_add){?>
<li><?php echo $live_data['jinsom_video_live_tab_jingcai_name'];?></li>
<?php }?>
</div>
</div>
<div class="jinsom-live-page-nav-list">
<ul class="comment-list">
<?php 
$args = array(
'status'=>'approve',
'type' => 'comment',
'karma'=>0,
'no_found_rows' =>false,
'number' =>150,
'orderby' => 'comment_date',
'post_id' => $post_id
);
$comment_data = get_comments($args);
if(!empty($comment_data)){ 
foreach (array_reverse($comment_data) as $comment_datas) {
$comment_id=$comment_datas->comment_ID;
$comment_user_id = $comment_datas->user_id;
$comment_content = $comment_datas->comment_content;
echo '
<li>
<div class="left"><a href="'.jinsom_mobile_author_url($comment_user_id).'" class="link">'.jinsom_avatar($comment_user_id,'40',avatar_type($comment_user_id)).jinsom_verify($comment_user_id).'</a></div>
<div class="right">
<div class="name">'.jinsom_nickname($comment_user_id).jinsom_lv($comment_user_id).jinsom_vip($comment_user_id).jinsom_honor($comment_user_id).'</div>
<div class="content">'.convert_smilies($comment_content).'</div>
</div>
</li>
';
}
}else{
echo jinsom_empty(__('还没有人进行互动','jinsom'));	
}
?>
</ul>
<ul>
<?php echo do_shortcode(get_the_content('',false,$post_id));?>
</ul>
<?php if($jinsom_video_live_jingcai_add){?>
<ul class="jingcai clear">
<?php 
foreach ($jinsom_video_live_jingcai_add as $data){
echo '<li onclick=\'jinsom_live_jingcai_video_play("'.$data['url'].'",this)\'><img src="'.$data['cover'].'" class="opacity"><p>'.$data['title'].'</p></li>';
}
?>
</ul>
<?php }?>
</div>
</div>
</div>        
<?php jinsom_upadte_user_online_time();//更新在线状态 ?>