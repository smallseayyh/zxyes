<?php $jinsom_im_group_on_off=jinsom_get_option('jinsom_im_group_on_off');?>

<div class="jinsom-chat">
<div class="jinsom-chat-close-icon"><i class="jinsom-icon jinsom-zhixiang-youshang"></i></div>
<div class="jinsom-chat-clear-icon" onclick="jinsom_clear_im_notice()"><i class="jinsom-icon jinsom-qingchu"></i></div>
<div class="jinsom-chat-header">
<div class="jinsom-chat-header-user">
<i class="jinsom-icon jinsom-user"></i>
<span class="title"><?php _e('关注','jinsom');?></span>
</div>	
<?php if($jinsom_im_group_on_off){?>
<div class="jinsom-chat-header-group">
<i class="jinsom-icon jinsom-qunzu"></i>
<span class="title"><?php _e('群组','jinsom');?></span>
</div>
<?php }?>
<div class="jinsom-chat-header-recent on">
<i class="jinsom-icon jinsom-zuijin"></i>
<span class="title"><?php _e('会话','jinsom');?></span>
</div>
</div>	
<div class="jinsom-chat-search-box">
<input class="jinsom-chat-search-input" type="text" placeholder="<?php _e('搜索用户','jinsom');?>">
<i class="jinsom-icon jinsom-sousuo1"></i>
</div>
<div class="jinsom-chat-content">
<div class="jinsom-chat-content-user">

<div class="jinsom-chat-content-user-add">
<!-- <li>
<div class="jinsom-chat-content-user-add-icon"><i class="jinsom-icon jinsom-location"></i></div>	
<div class="jinsom-chat-content-user-add-info" onclick="jinsom_test()"><span>附近的人</span></div>	
</li> -->	
</div>
<div class="jinsom-chat-content-follow-user"></div>

</div>

<?php if($jinsom_im_group_on_off){?>
<div class="jinsom-chat-content-group"></div>
<?php }?>

<div class="jinsom-chat-content-recent">

<div class="jinsom-chat-content-recent-notice">
<li onclick="jinsom_system_notice_form(this)">
<div class="jinsom-chat-content-recent-notice-icon"><i class="jinsom-icon jinsom-tongzhi1"></i></div>	
<div class="jinsom-chat-content-recent-notice-info"><span><?php _e('通知消息','jinsom');?></span></div>	
<?php if($system_notice){echo'<span class="tips">'.$system_notice.'</span>';}?>
</li>	
</div>

<div class="jinsom-chat-content-recent-user"></div>
</div>

</div>
</div>


