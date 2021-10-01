<?php 
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$bbs_id=$_GET['bbs_id'];
$bbs_name=get_category($bbs_id)->name;
?>
<div data-page="chat-group" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo $bbs_name;?></div>
<div class="right">
<a href="#" class="link icon-only"><?php echo jinsom_get_bbs_avatar($bbs_id,0);?></a>
</div>
</div>
</div>


<div class="toolbar jinsom-chat-toolbar messagebar messagebar-init" data-max-height="100">
<div class="jinsom-msg-tips" onclick="$(this).hide().html('底部');$('.jinsom-chat-group-list-content').scrollTop($('.jinsom-chat-group-list-content')[0].scrollHeight);">底部</div>
<div class="toolbar-inner">
<li class="image"><i class="jinsom-icon jinsom-tupian"></i>
<form id="jinsom-im-upload-form" enctype="multipart/form-data">
<input name="file" type="file" accept="image/*" id="im-file">
<input type="hidden" name="bbs_id" value="<?php echo $bbs_id;?>">
</form>
</li>
<li class="smile" onclick="jinsom_smile_form(this)"><i class="jinsom-icon jinsom-qinggan"></i></li>
<div class="hidden-smile" style="display: none;"><?php echo jinsom_get_expression('im',0);?></div>
<li class="input">
<textarea id="jinsom-msg-content"></textarea>
<span onclick="jinsom_send_msg_group(<?php echo $bbs_id;?>)"><i class="jinsom-icon jinsom-fasong"></i></span>
</li>
</div>
</div>  

<div class="page-content jinsom-chat-group-list-content">
<div class="jinsom-chat-group-list" count="<?php echo jinsom_get_chat_group_msg_count($bbs_id);?>">
<?php 
$get_msg=jinsom_get_group_msg($bbs_id,0,50);
$msg='';
foreach (array_reverse($get_msg) as $get_msgs) {
$type=$get_msgs->type;
$time1=strtotime($get_msgs->msg_time);
$time2=time();
$time3=$time2-$time1;
if($time3<300){
$chat_time=date('H:i:s',$time1);
}elseif($time3>=300&&$time3<60*60*24){
$chat_time=date('H:i:s',$time1);	
}else{
$chat_time=date('Y/m/d H:i:s',$time1);			
}

if($type==1){
echo '<p class="jinsom-chat-message-list-time">'.$chat_time.'</p>';

if($get_msgs->user_id==$user_id){
echo '
<li class="myself">
<div class="jinsom-chat-message-list-user-info avatarimg-'.$user_id.'">
'.jinsom_avatar($user_id, '50' , avatar_type($user_id) ).'
</div>
<div class="jinsom-chat-message-list-content">'.wpautop(jinsom_autolink(convert_smilies($get_msgs->content))).'</div>
</li>';
}else{
echo '
<li>
<div class="jinsom-chat-message-list-user-info avatarimg-'.$get_msgs->user_id.'">
<a href="'.jinsom_mobile_author_url($get_msgs->user_id).'" class="link">
'.jinsom_avatar($get_msgs->user_id, '50' , avatar_type($get_msgs->user_id)).jinsom_verify($get_msgs->user_id).'
<span class="name">'.jinsom_nickname($get_msgs->user_id).jinsom_lv($get_msgs->user_id).jinsom_vip($get_msgs->user_id).jinsom_honor($get_msgs->user_id).'</span>
</a>
</div>
<div class="jinsom-chat-message-list-content">'.wpautop(jinsom_autolink(convert_smilies($get_msgs->content))).'</div>
</li>
';
}

}else if($type==2){
echo '<p class="jinsom-chat-message-tips"><span>'.jinsom_nickname($get_msgs->user_id).' 加入了群聊</span></p>';	
}else if($type==3){
echo '<p class="jinsom-chat-message-tips"><span>'.jinsom_nickname($get_msgs->user_id).' 退出了群聊</span></p>';	
}


}

?>

</div>



<div class="popover jinsom-chat-tap-popover">
<div class="popover-angle"></div>
<div class="popover-inner">
<div class="jinsom-chat-tap-tool">
<a class="item-link close-popover"><i></i><p>复制</p></a>
<a class="item-link close-popover"><i></i><p>回复</p></a>
<a class="item-link close-popover"><i></i><p>撤回</p></a>
</div>
</div>
</div>


</div>
</div>        