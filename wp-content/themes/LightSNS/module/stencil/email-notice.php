<?php
//邮件通知 个性设置
require( '../../../../../wp-load.php' );
$user_id = $current_user->ID;
$system_notice=get_user_meta($user_id,'system_notice',true);
$user_notice=get_user_meta($user_id,'user_notice',true);
$comment_notice=get_user_meta($user_id,'comment_notice',true);
?>

<form class="jinsom-enail-notice-form layui-form">


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('系统消息','jinsom');?></label>
<div class="layui-input-block">
<input type="checkbox" <?php if($system_notice){echo 'checked=""';}?> lay-skin="switch" lay-filter="system" lay-text="开|关">
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('用户消息','jinsom');?></label>
<div class="layui-input-block">
<input type="checkbox" <?php if($user_notice){echo 'checked=""';}?> lay-skin="switch" lay-filter="user" lay-text="开|关">
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('评论艾特','jinsom');?></label>
<div class="layui-input-block">
<input type="checkbox" <?php if($comment_notice){echo 'checked=""';}?> lay-skin="switch" lay-filter="comment" lay-text="开|关">
</div>
</div>


<!-- <div class="tip">系统消息包含：置顶、采纳、加精、删除</div>
<div class="tip">用户消息包含：喜欢、关注、购买、转发、打赏、点赞、送礼物</div> -->


</form>