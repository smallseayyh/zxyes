<?php 
//邮件通知提醒设置
require( '../../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$author_id=$_GET['author_id'];	
}else{
$author_id=$user_id;
}

if($author_id==$user_id){
jinsom_update_ip($author_id);//更新定位
}


$system_notice=get_user_meta($author_id,'system_notice',true);
$user_notice=get_user_meta($author_id,'user_notice',true);
$comment_notice=get_user_meta($author_id,'comment_notice',true);
if($system_notice){
$system_notice_text='开启';
}else{
$system_notice_text='关闭';
}

if($user_notice){
$user_notice_text='开启';
}else{
$user_notice_text='关闭';
}

if($comment_notice){
$comment_notice_text='开启';
}else{
$comment_notice_text='关闭';
}

?>
<div data-page="setting-more" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('邮件通知','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content">



<div class="jinsom-setting-box">

<li class="select" data="system_notice">
<a href="#" class="link">
<select>
<option value ="1" <?php if($system_notice) echo 'selected = "selected"'; ?>><?php _e('开启','jinsom');?></option>
<option value ="" <?php if(!$system_notice) echo 'selected = "selected"'; ?>><?php _e('关闭','jinsom');?></option>
</select>
<span class="title"><?php _e('系统消息','jinsom');?></span>	
<span class="value"><?php echo $system_notice_text;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="select" data="user_notice">
<a href="#" class="link">
<select>
<option value ="1" <?php if($user_notice) echo 'selected = "selected"'; ?>><?php _e('开启','jinsom');?></option>
<option value ="" <?php if(!$user_notice) echo 'selected = "selected"'; ?>><?php _e('关闭','jinsom');?></option>
</select>
<span class="title"><?php _e('用户消息','jinsom');?></span>	
<span class="value"><?php echo $user_notice_text;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>

<li class="select" data="comment_notice">
<a href="#" class="link">
<select>
<option value ="1" <?php if($comment_notice) echo 'selected = "selected"'; ?>><?php _e('开启','jinsom');?></option>
<option value ="" <?php if(!$comment_notice) echo 'selected = "selected"'; ?>><?php _e('关闭','jinsom');?></option>
</select>
<span class="title"><?php _e('评论艾特','jinsom');?></span>	
<span class="value"><?php echo $comment_notice_text;?></span>
<span class="a"><i class="jinsom-icon jinsom-huaban"></i></span>
</a>
</li>


</div>



</div>       

