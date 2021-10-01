<?php 
require( '../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$author_id=$_GET['author_id'];
$user_info = get_userdata($user_id);
?>
<div data-page="chat-one" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo strip_tags(jinsom_nickname_link($author_id));?></div>
<div class="right">
<a data-popover=".jinsom-chat-popover" class="link icon-only open-popover"><i class="jinsom-icon jinsom-gengduo2" style="font-size: 8vw;"></i></a>
</div>
</div>
</div>


<div class="toolbar jinsom-chat-toolbar messagebar messagebar-init" data-max-height="100">
<div class="jinsom-msg-tips" onclick="$(this).hide().html('底部');$('.jinsom-chat-list-content').scrollTop($('.jinsom-chat-list-content')[0].scrollHeight);">底部</div>
<div class="toolbar-inner">
<li class="image"><i class="jinsom-icon jinsom-tupian"></i>
<form id="jinsom-im-upload-form" enctype="multipart/form-data">
<input name="file" type="file" accept="image/*" id="im-file">
<input type="hidden" name="author_id" value="<?php echo $author_id;?>">
</form>
</li>
<li class="smile" onclick="jinsom_smile_form(this)"><i class="jinsom-icon jinsom-qinggan"></i></li>
<div class="hidden-smile" style="display: none;"><?php echo jinsom_get_expression('im',0);?></div>
<li class="input">
<textarea id="jinsom-msg-content"></textarea>
<span onclick="jinsom_send_msg(<?php echo $author_id;?>)"><i class="jinsom-icon jinsom-fasong"></i></span>
</li>
</div>
</div> 

<div class="page-content jinsom-chat-list-content">
<div class="jinsom-chat-list" count="<?php echo jinsom_get_chat_msg_count($user_id,$author_id);?>">
<?php 
$msg_data=jinsom_get_msg($user_id,$author_id,0,20);
foreach (array_reverse($msg_data) as $data) {
$time1=strtotime($data->msg_date);
$time2=time();
$time3=$time2-$time1;
if($time3<300){
$chat_time=date('H:i:s',$time1);
}elseif($time3>=300&&$time3<60*60*24){
$chat_time=date('H:i:s',$time1);	
}else{
$chat_time=date('Y/m/d H:i:s',$time1);			
}

echo '<p class="jinsom-chat-message-list-time">'.$chat_time.'</p>';
if($data->from_id==$user_id){

echo '
<li class="myself">
<div class="jinsom-chat-message-list-user-info avatarimg-'.$user_id.'">
'.jinsom_avatar($user_id,'50',avatar_type($user_id) ).'
</div>
<div class="jinsom-chat-message-list-content">'.do_shortcode(wpautop(jinsom_autolink(convert_smilies($data->msg_content)))).'</div>
</li>';
}else{
echo '
<li>
<div class="jinsom-chat-message-list-user-info avatarimg-'.$author_id.'">
<a href="'.jinsom_mobile_author_url($author_id).'" class="link">
'.jinsom_avatar($author_id,'50',avatar_type($author_id) ).jinsom_verify($author_id).'
</a>
</div>
<div class="jinsom-chat-message-list-content">'.do_shortcode(wpautop(jinsom_autolink(convert_smilies($data->msg_content)))).'</div>
</li>
';
}
}
//将未读的消息设置为已经读取
jinsom_update_had_read_msg($author_id);

if(isset($_GET['goods'])&&$_GET['goods']){
$post_id=(int)$_GET['goods'];
echo '<div class="jinsom-chat-goods-card"><div class="top back">';
$goods_data=get_post_meta($post_id,'goods_data',true);
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$price_type=$goods_data['jinsom_shop_goods_price_type'];
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐
if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$pay_price=(int)$goods_data['jinsom_shop_goods_price'];
$price_discount=(int)$goods_data['jinsom_shop_goods_price_discount'];
}else{
$pay_price=(int)$select_change_price_add[0]['value_add'][0]['price'];
$price_discount=(int)$select_change_price_add[0]['value_add'][0]['price_discount'];
}
if($price_discount){
$pay_price=$price_discount;
}
$cover=$goods_data['jinsom_shop_goods_img'];
$cover_img_add=$goods_data['jinsom_shop_goods_img_add'];
if($cover_img_add){
$cover_one=$cover_img_add[0]['img'];
}else{
if($cover){
$cover_arr=explode(',',$cover);
$cover_src_one=wp_get_attachment_image_src($cover_arr[0],'full');
$cover_one=$cover_src_one[0];//第一张封面
}
}
if($price_type=='rmb'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}

echo '<img src="'.$cover_one.'"><span class="title">'.get_the_title($post_id).'</span><span class="price">'.$price_icon.$pay_price.'</span>';

echo '</div><div class="btn" onclick="jinsom_send_msg_goods('.$post_id.','.$author_id.',this)">发送链接</div></div>';
}



?>

</div>


<div class="popover jinsom-chat-popover">
<div class="popover-angle"></div>
<div class="popover-inner">
<div class="list-block">
<ul>
<?php if(jinsom_is_blacklist($user_id,$author_id)){?>
<li><a href="#" class="list-button item-link close-popover link" onclick="jinsom_add_blacklist('remove',<?php echo $author_id;?>,this)">取消拉黑</a></li> 
<?php }else{?>
<li><a href="#" class="list-button item-link close-popover link" onclick="jinsom_add_blacklist('add',<?php echo $author_id;?>,this)">拉黑</a></li>  
<?php }?>
<li><a href="#" class="list-button item-link close-popover link" onclick="layer.open({content:'暂未开启',skin:'msg',time:2});">举报</a></li> 
</ul>
</div>
</div>
</div>

<!-- <div class="popover jinsom-chat-tap-popover">
<div class="popover-angle"></div>
<div class="popover-inner">
<div class="jinsom-chat-tap-tool">
<a class="item-link close-popover link"><i></i><p>复制</p></a>
<a class="item-link close-popover link"><i></i><p>回复</p></a>
<a class="item-link close-popover link"><i></i><p>撤回</p></a>
</div>
</div>
</div> -->


</div>
</div>        