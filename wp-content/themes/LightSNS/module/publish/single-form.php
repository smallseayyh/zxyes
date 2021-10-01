<!-- 发表文章页面 -->
<?php
$user_id=$current_user->ID;
$topic_name=$_GET['topic_name'];
$city=get_user_meta($user_id,'city',true);
if (!is_user_logged_in()) {
echo '<div class="jinsom-no-power-publish-tips">你还没登录！</div>';
exit;
}

$credit_name=jinsom_get_option('jinsom_credit_name');
$publish_credit = (int)jinsom_get_option('jinsom_publish_post_credit');//每次发表动态可获得的金币
jinsom_update_ip($user_id);//更新定位
$pending=jinsom_get_option('jinsom_publish_single_pending');
if($pending&&!jinsom_is_admin($user_id)){
$publish_text='提交审核';
}else{
$publish_text='发表';
}
?>
<form class="jinsom-publish-single-form layui-form pop">

<?php echo jinsom_get_option('jinsom_publish_single_header_html');?>

<input type="text" class="jinsom-single-title" name="title" placeholder="标题">
<script id="jinsom-single-edior" type="text/plain" name="content"></script>

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
<span class="power" onclick="jinsom_publish_power_form('single')"><i class="jinsom-icon jinsom-gongkai1" title="公开" data="0"></i></span> 
<span class="comment" onclick="jinsom_publish_comment_status(this)"><i class="jinsom-icon ok jinsom-quxiaojinzhi-" title="允许评论"></i></span>
<?php if(!get_user_meta($user_id,'publish_city',true)&&jinsom_get_option('jinsom_location_on_off')!='no'){?>
<span class="city" onclick="jinsom_publish_city(this)"><i class="jinsom-icon jinsom-xiazai19"></i> <m><?php echo $city;?></m></span>
<input type="hidden" id="jinsom-pop-city" name="city" value="<?php echo $city;?>">
<?php }?>
</div>

<input type="hidden" id="jinsom-pop-power" name="power" value="0">
<input type="hidden" id="jinsom-pop-comment-status" name="comment-status" value="open">
<div class="jinsom-publish-single-info-content">
<!-- 权限 -->
<div class="jinsom-publish-words-power single"></div>


<!-- 隐藏内容 -->	
<div class="layui-form-item" id="jinsom-bbs-publish-hide-content" style="display: none;margin-top: 15px;">
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
autoFloatEnabled: false,
initialFrameHeight:$(window).height()-220,
});
ue_single_pay.ready(function() {
ue_single_pay.execCommand('drafts');
});  
</script>  

<div class="jinsom-bbs-edior-footer-bar pay">
<span id="jinsom-single-pay-upload"><i class="fa fa-picture-o"></i></span>
<?php if(jinsom_get_option('jinsom_publish_single_upload_file')){?>
<span onclick="jinsom_upload_file_form(3);"><i class="jinsom-icon jinsom-fujian1"></i></span> 
<?php }?>
<span class="jinsom-ue-edior-smile pay" onclick="jinsom_smile(this,'ue','ue_single_pay')">
<i class="jinsom-icon jinsom-weixiao-"></i>
</span>   
</div>
</div><!-- 隐藏内容结束 -->


<!-- 话题列表 -->
<div class="jinsom-publish-words-topic single pop">
<?php if(!empty($topic_name)){echo '<span data="'.$topic_name.'">#'.$topic_name.'#</span>';}?>
</div>

<div class="jinsom-publish-words-btn single">
<div class="cancel opacity">取消</div>


<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<div class="publish opacity" id="publish-4"><?php echo $publish_text;if($publish_credit<0&&!$pending){echo '（'.$publish_credit.$credit_name.'）';}?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('publish-4'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_publish_single(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div class="publish opacity" onclick="jinsom_publish_single('','')"><?php echo $publish_text;if($publish_credit<0&&!$pending){echo '（'.$publish_credit.$credit_name.'）';}?></div>
<?php }?>


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
autoFloatEnabled: false,
initialFrameHeight:$(window).height()-220,
});  
ue_single.ready(function() {
ue_single.execCommand('drafts');
});  

//移除已经选择的话题
$(".jinsom-publish-words-topic.single").on("click","span",function(){
$(this).remove();
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
