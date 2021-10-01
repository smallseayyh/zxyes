<!-- 编辑文章页面 -->
<?php
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$post_data=get_post($post_id,ARRAY_A);
$title=$post_data['post_title'];
$content=$post_data['post_content'];
$post_power=(int)get_post_meta($post_id,'post_power',true);
$post_type=get_post_meta($post_id,'post_type',true);
$hide_content=get_post_meta($post_id,'pay_cnt',true);//隐藏的内容
$price=get_post_meta($post_id,'post_price',true);//售价
$password=get_post_meta($post_id,'post_password',true);//密码	
$topics = wp_get_post_tags($post_id);
$author_id=jinsom_get_user_id_post($post_id);
if(!jinsom_is_admin($user_id)&&$user_id!=$author_id){
exit();
}
if($post_type!='single'){//不是文章类型
exit();
}

$pending=jinsom_get_option('jinsom_publish_single_pending');
if($pending&&!jinsom_is_admin($user_id)){
$publish_text='提交审核';
}else{
$publish_text='更新';
}
?>
<form class="jinsom-publish-single-form layui-form pop">
<input type="text" class="jinsom-single-title" name="title" placeholder="标题" value="<?php echo $title;?>">

<script id="jinsom-single-edior" type="text/plain" name="content"></script>
<div id="jinsom-editor-single-content" style="display: none;"><?php echo $content;?></div>

<!-- 编辑器栏 -->
<div class="jinsom-single-edior-footer-bar">
<span id="jinsom-single-upload"><i class="fa fa-picture-o"></i></span> 
<?php if(jinsom_get_option('jinsom_publish_single_upload_file')){?>
<span onclick="jinsom_upload_file_form(4);"><i class="jinsom-icon jinsom-fujian1"></i></span> 
<?php }?>
<span class="jinsom-ue-edior-smile" onclick="jinsom_smile(this,'ue','ue_single')">
<i class="jinsom-icon jinsom-weixiao-"></i>
</span> 
<span class="topic" onclick="jinsom_publish_topic_form()"><i class="jinsom-icon jinsom-huati"></i></span>
<span class="power" onclick="jinsom_publish_power_form('single')">
<?php 
if($post_power==1){
echo '<i class="jinsom-icon jinsom-fufei" title="付费内容" data="1"></i>';	
}else if($post_power==2){
echo '<i class="jinsom-icon jinsom-mima" title="密码内容" data="2"></i>';	
}else if($post_power==3){
echo '<i class="jinsom-icon jinsom-biyan" title="私密内容" data="3"></i>';	
}else if($post_power==4){
echo '<i class="jinsom-icon jinsom-vip-type" title="VIP可见" data="4"></i>';	
}else if($post_power==5){
echo '<i class="jinsom-icon jinsom-denglu" title="登录可见" data="5"></i>';	
}else if($post_power==6){
echo '<i class="jinsom-icon jinsom-pinglun2" title="'.__('回复可见','jinsom').'"></i>';
}else if($post_power==7){
echo '<i class="jinsom-icon jinsom-dagou" title="'.__('认证可见','jinsom').'"></i>';
}else if($post_power==8){
echo '<i class="jinsom-icon jinsom-qunzu" title="'.__('粉丝可见','jinsom').'"></i>';
}else{
echo '<i class="jinsom-icon jinsom-gongkai1" title="公开内容" data="0"></i>';	
}
?>
</span>
<span class="comment" onclick="jinsom_publish_comment_status(this)">
<?php if(comments_open($post_id)){?>
<i class="jinsom-icon ok jinsom-quxiaojinzhi-" title="允许评论"></i>
<?php }else{?>
<i class="jinsom-icon no jinsom-jinzhipinglun-" title="禁止评论"></i>
<?php }?>
</span>
</div>

<input type="hidden" id="jinsom-pop-power" name="power" value="<?php echo $post_power;?>">
<input type="hidden" id="jinsom-pop-comment-status" name="comment-status" value="<?php if(comments_open($post_id)){echo 'open';}else{echo 'closed';}?>">

<div class="jinsom-publish-single-info-content">
<!-- 权限 -->
<div class="jinsom-publish-words-power single">
<?php 
$display='display:none;';
if($post_power==1||$post_power==2||$post_power==4||$post_power==5||$post_power==6||$post_power==7||$post_power==8){
$display='display:block;';
?>
<div class="jinsom-publish-words-power-content">
<?php if($post_power==1){?>
<input placeholder="设置售价" type="number" class="price" name="price" value="<?php echo $price;?>"> <i><?php echo jinsom_get_option('jinsom_credit_name');?></i>
<?php }?>
<?php if($post_power==2){?>
<input placeholder="设置密码" type="text" class="password" name="password" maxlength="20" value="<?php echo $password;?>">
<?php }?>
</div>
<?php }?>
</div>

<div id="jinsom-single-hide-content" style="display:none;"><?php echo $hide_content;?></div>

<!-- 隐藏内容 -->	
<div class="layui-form-item" id="jinsom-bbs-publish-hide-content" style="<?php echo $display;?>margin-top: 15px;">
<div class="title">隐藏内容</div>
<script id="jinsom-bbs-hide-content" name="hide-content" type="text/plain"></script> 
<script type="text/javascript">
window.ue_single_pay = UE.getEditor('jinsom-bbs-hide-content', {
toolbars: [
[
<?php jinsom_get_edior('bbs_pay');?>
'fullscreen', //全屏
]
],
autoHeightEnabled: false,
// autoFloatEnabled: true,
initialFrameHeight:$(window).height()-220,
});
ue_single_pay.ready(function() {
ue_single_pay.setContent($('#jinsom-single-hide-content').html());
}); 
</script>  

<div class="jinsom-bbs-edior-footer-bar pay">
<span id="jinsom-bbs-pay-upload"><i class="fa fa-picture-o"></i></span> 
<span onclick="jinsom_upload_file_form(3);"><i class="jinsom-icon jinsom-fujian1"></i></span> 
<span class="jinsom-ue-edior-smile pay" onclick="jinsom_smile(this,'ue','ue_single_pay')">
<i class="jinsom-icon jinsom-weixiao-"></i>
</span>   
</div>
</div><!-- 隐藏内容结束 -->

<!-- 话题列表 -->
<div class="jinsom-publish-words-topic single pop">
<?php 
if($topics){
foreach ($topics as $topic) {
echo '<span data="'.$topic->name.'">#'.$topic->name.'#</span>';
}
}
?>
</div>

<div class="jinsom-publish-words-btn single">
<div class="cancel opacity">取消</div>
<div class="publish opacity" onclick="jinsom_editor_single(<?php echo $post_id;?>)"><?php echo $publish_text;?></div>
</div>

</div>

</form>





<script type="text/javascript">
window.ue_single = UE.getEditor('jinsom-single-edior', {
toolbars: [
[
<?php jinsom_get_edior('single');?>
'fullscreen', //全屏
]
],
autoHeightEnabled: false,
// autoFloatEnabled: true,
initialFrameHeight:$(window).height()-220,
});  
ue_single.ready(function() {
ue_single.setContent($('#jinsom-editor-single-content').html());
});  

//移除已经选择的话题
$(".jinsom-publish-words-topic").on("click","span",function(){
$(this).remove();
if($('.jinsom-publish-words-topic span').length==0){
$('.jinsom-publish-words-bar .topic').removeClass('on');	
}
}); 

//关闭发表窗口
$(".jinsom-publish-words-btn.single .cancel").click(function(){
layer.confirm('你确定要离开吗？', {
btnAlign: 'c',
btn: ['确定','取消']
}, function(index){
layer.close(index);
layer.load(1);
$(window).unbind('beforeunload');
history.go(-1)//返回上一页
});
});

//绑定beforeunload事件
$(window).bind('beforeunload',function(){
return '您输入的内容尚未保存，确定离开此页面吗？';
});


 </script>
