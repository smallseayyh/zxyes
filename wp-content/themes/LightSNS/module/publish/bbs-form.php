<?php 
$user_id=$current_user->ID;
$bbs_id=(int)$_GET['bbs_id'];
$credit_name=jinsom_get_option('jinsom_credit_name');

$bbs_parents=get_category($bbs_id)->parent;
if($bbs_parents==0){
$child_bbs_id=0;
}else{
$child_bbs_id=$bbs_id;
$bbs_id=$bbs_parents;
}



$topic_name=$_GET['topic_name'];
$city=get_user_meta($user_id,'city',true);
$theme_url=get_template_directory_uri();
$bbs_type=get_term_meta($bbs_id,'bbs_type',true);//类型

//权限
$bbs_power=get_term_meta($bbs_id,'bbs_power',true);
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$bbs_blacklist=get_term_meta($bbs_id,'bbs_blacklist',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$bbs_blacklist_arr=explode(",",$bbs_blacklist); 

//功能
$normal=get_term_meta($bbs_id,'bbs_normal',true);
$pay_see=get_term_meta($bbs_id,'bbs_pay_see',true);
$comment_see=get_term_meta($bbs_id,'bbs_comment_see',true);
$vip_see=get_term_meta($bbs_id,'bbs_vip_see',true);
$login_see=get_term_meta($bbs_id,'bbs_login_see',true);
$vote=get_term_meta($bbs_id,'bbs_vote',true);
$answer=get_term_meta($bbs_id,'bbs_answer',true);
$activity=get_term_meta($bbs_id,'bbs_activity',true);

//发布选项
$publish_images=get_term_meta($bbs_id,'publish_images',true);
$publish_files=get_term_meta($bbs_id,'publish_files',true);
$publish_comment_status=get_term_meta($bbs_id,'publish_comment_status',true);
$publish_comment_private=get_term_meta($bbs_id,'publish_comment_private',true);



//金币价格范围
$credit=get_user_meta($user_id,'credit',true);
$answer_price_mini=(int)jinsom_get_option('jinsom_answer_price_mini');//悬赏金额最小值
$answer_price_max=(int)jinsom_get_option('jinsom_answer_price_max');//悬赏金额最大值
$credit_post_number=get_term_meta($bbs_id,'bbs_credit_post_number',true);
//$exp_post_number=get_term_meta($bbs_id,'bbs_exp_post_number',true);

//售价范围
$price_mini = (int)jinsom_get_option('jinsom_bbs_pay_price_mini');
$price_max = (int)jinsom_get_option('jinsom_bbs_pay_price_max');

$categories_child = get_categories("child_of=".$bbs_id."&orderby=description&order=ASC&hide_empty=0");//返回子分类的数组

if(is_user_logged_in()){
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}


//发表按钮
$pending=get_term_meta($bbs_id,'pending',true);
if($pending&&!jinsom_is_admin($user_id)&&!in_array($user_id,$admin_a_arr)){
$publish_text='提交审核';
}else{
$publish_text=get_term_meta($bbs_id,'publish_text',true);
if(!$publish_text){$publish_text='发表';}
}


if(in_array($user_id,$bbs_blacklist_arr)||!is_user_logged_in()){
exit();	
}



//发帖权限
$nopower_tips='<div class="jinsom-no-power-publish-tips">'.__('你没有发表权限！','jinsom').'</div>';
if($bbs_power==1){//vip才能发帖
if(!is_vip($user_id)&&!$is_bbs_admin){
echo $nopower_tips;
exit();
}
}else if($bbs_power==2){//认证用户才能发帖
$user_verify=get_user_meta($user_id, 'verify', true );
if(!$user_verify&&!$is_bbs_admin){
echo $nopower_tips;
exit();
}
}else if($bbs_power==3){//管理团队才可以发帖
if(!$is_bbs_admin){
echo $nopower_tips;
exit();
}
}else if($bbs_power==4){//关注本论坛才可以发帖
if(!jinsom_is_bbs_like($user_id,$bbs_id)&&!$is_bbs_admin){
echo $nopower_tips;
exit();
}
}else if($bbs_power==5){//有头衔的用户
if(!get_user_meta($user_id,'user_honor',true)&&!$is_bbs_admin){
echo $nopower_tips;
exit();
}
}else if($bbs_power==6){//指定经验用户才能发帖
$publish_power_exp=(int)get_term_meta($bbs_id,'publish_power_exp',true);
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
if($current_user_lv<$publish_power_exp&&!$is_bbs_admin){//当前用户等级是否大于或等于指定的等级
echo $nopower_tips;
exit();
}
}else if($bbs_power==7){//指定头衔
if(!$is_bbs_admin){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$publish_power_honor=get_term_meta($bbs_id,'publish_power_honor',true);
$publish_power_honor_arr=explode(",",$publish_power_honor);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($publish_power_honor_arr,$user_honor_arr)){	
echo $nopower_tips;
exit();
}
}else{
echo $nopower_tips;
exit();
}
}
}else if($bbs_power==8){//指定认证类型
if(!$is_bbs_admin){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$publish_power_verify=get_term_meta($bbs_id,'publish_power_verify',true);
$publish_power_verify_arr=explode(",",$publish_power_verify);
if(!in_array($user_verify_type,$publish_power_verify_arr)){
echo $nopower_tips;
exit();
}
}else{
echo $nopower_tips;
exit();
}
}
}else if($bbs_power==0){
if(!get_term_meta($bbs_id,'bbs_showposts',true)){
echo '<div class="jinsom-no-power-publish-tips">新创建的论坛，请在论坛前台设置-点击保存设置进行初始化！<br>（论坛页面-头部背景封面右上角-小齿轮）</div>';
exit();
}
}

jinsom_update_ip($user_id);//更新定位
?>


<!-- 论坛发布开始 -->
<div class="jinsom-editor-bbs-content">
<form id="jinsom-bbs-publish-form" class="pop">
<?php echo get_term_meta($bbs_id,'publish_page_header_html',true);?>

<div class="title">
<input type="text" name="title" class="jinsom-bbs-title" placeholder="请输入标题">
<?php if($is_bbs_admin){?>
<div id="jinsom-publish-bbs-title-color" class="color"></div>
<?php }?>
</div>

<div class="jinsom-bbs-edior clear">
<script id="jinsom-bbs-edior" type="text/plain" name="content"></script>
<script type="text/javascript">
window.ue = UE.getEditor('jinsom-bbs-edior', {
toolbars: [
[
<?php jinsom_get_edior('bbs');?>
'fullscreen', //全屏
]
],
autoHeightEnabled: false,
// autoFloatEnabled: true,
initialFrameHeight:$(window).height()-220,
});   
ue.ready(function() {
ue.execCommand('drafts');
});  
</script>
</div>
 
<!-- 编辑器栏 -->
<div class="jinsom-bbs-edior-footer-bar">
<?php if($publish_images){?>
<span id="jinsom-bbs-upload"><i class="fa fa-picture-o"></i></span> 
<?php }?>
<?php if($publish_files){?>
<span onclick="jinsom_upload_file_form(1)"><i class="jinsom-icon jinsom-fujian1"></i></span> 
<?php }?>
<span class="jinsom-ue-edior-smile" onclick="jinsom_smile(this,'ue','ue')">
<i class="jinsom-icon jinsom-weixiao-"></i>
</span> 
<span class="topic" onclick="jinsom_publish_topic_form()"><i class="jinsom-icon jinsom-huati"></i></span> 
<?php if(!get_user_meta($user_id,'publish_city',true)&&jinsom_get_option('jinsom_location_on_off')!='no'){?>
<span class="city" onclick="jinsom_publish_city(this)"><i class="jinsom-icon jinsom-xiazai19"></i> <m><?php echo $city;?></m></span>
<input type="hidden" id="jinsom-pop-city" name="city" value="<?php echo $city;?>">
<?php }?>
</div>




<!-- 发帖选项表单开始 -->
<div class="jinsom-bbs-publish-info layui-form" lay-filter="jinsom-bbs-publish">

<!-- 话题列表 -->
<div class="jinsom-publish-words-topic bbs pop">
<?php 
$publish_default_topic=get_term_meta($bbs_id,'publish_default_topic',true);
if(!empty($topic_name)){
echo '<span data="'.$topic_name.'">#'.$topic_name.'#</span>';
}else if($publish_default_topic){
$publish_default_topic_arr=explode(",",$publish_default_topic);
if($publish_default_topic_arr){
foreach ($publish_default_topic_arr as $topic_name) {
echo '<span data="'.$topic_name.'">#'.$topic_name.'#</span>';
}
}
}
?>
</div>



<?php if(($publish_comment_private||$publish_comment_status)&&$bbs_type!='download'){//评论状态||回复隐私?>
<div class="layui-form-item">
<?php if($publish_comment_status){//评论状态?>
<div class="layui-inline" style=""> 
<label class="layui-form-label">禁止回复</label>
<div class="layui-input-inline">
<input type="checkbox" name="comment-status" lay-skin="switch" lay-text="开|关">
</div>
</div>
<?php }//评论状态?>

<?php if($publish_comment_private){//回复隐私?>
<div class="layui-inline" style=""> 
<label class="layui-form-label">回复隐私</label>
<div class="layui-input-inline" style="width: 70px;">
<input type="checkbox" name="comment-private" lay-skin="switch" lay-text="开|关">
</div>
<div class="layui-form-mid layui-word-aux">回复内容仅作者可见</div>
</div>
<?php }//回复隐私?>
</div>
<?php }//评论状态||回复隐私?>


<div class="layui-form-item">

<!-- 帖子分类 -->
<?php if($cat_parents==0&&!empty($categories_child)){?>
<div class="layui-inline" style=""> 
<label class="layui-form-label">内容分类</label>
<div class="layui-input-inline">
<select name="category" id="jinsom-bbs-category">
<?php
$check='';
if($child_bbs_id==0){
echo '<option value="">请选择分类</option>';
}
foreach($categories_child as $category_child_a) {
if($category_child_a->cat_ID==$child_bbs_id){
$check='selected=""';	
}else{
$check='';	
}
echo '<option '.$check.' value ="'.$category_child_a->cat_ID.'">'.$category_child_a->cat_name.'</option>';}
?>
</select>
</div>
</div>
<?php }?>

<!-- 帖子类型 -->
<?php if($pay_see||$answer||$vip_see||$login_see||$comment_see||$activity||$vote){?>
<div class="layui-inline" style=""> 
<label class="layui-form-label">内容类型</label>
<div class="layui-input-inline">
<select name="post-type" lay-filter="jinsom-post-type" id="jinsom-bbs-type">
<?php if($normal){?>
<option value="normal">普通内容</option>
<?php }?>
<?php if($pay_see){?>
<option value="pay_see">付费内容</option>
<?php }?>
<?php if($answer&&$bbs_type!='download'){?>
<option value="answer">问答悬赏</option>
<?php }?>
<?php if($vip_see){?>
<option value="vip_see">VIP 可见</option>
<?php }?>
<?php if($login_see){?>
<option value="login_see">登录可见</option>
<?php }?>
<?php if($comment_see&&$bbs_type!='download'){?>
<option value="comment_see">回复可见</option>
<?php }?>
<?php if($activity&&$bbs_type!='download'){?>
<option value="activity">活动内容</option>
<?php }?>
<?php if($vote&&$bbs_type!='download'){?>
<option value="vote">投票内容</option>
<?php }?>
</select>
</div>
</div>
<?php }?>

</div>


<?php if($bbs_type=='download'){?>
<div class="jinsom-bbs-download-form">
<div class="li">
<div class="layui-form-item">
<label class="layui-form-label">下载地址</label>
<div class="layui-input-block">
<input placeholder="https://" type="text" class="layui-input download-url" style="width: 510px;">
</div>
</div>

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">提取密码</label>
<div class="layui-input-inline">
<input type="text" class="layui-input download-pass-a">
</div>
</div>

<div class="layui-inline">
<label class="layui-form-label">解压密码</label>
<div class="layui-input-inline">
<input type="text" class="layui-input download-pass-b">
</div>
</div>
</div>
</div>

<div class="jinsom-bbs-download-add"><span><i class="jinsom-icon jinsom-fabu8"></i> 新增下载地址</span></div>

</div>
<?php }?>

<?php 
//自定义发表字段
$publish_field=get_term_meta($bbs_id,'publish_field',true);
if($publish_field){
$publish_field_arr=explode(",",$publish_field);
foreach ($publish_field_arr as $data) {
$key_arr=explode("|",$data);
if($key_arr){
if($key_arr[1]=='textarea'){
$input='<textarea class="layui-textarea" name="'.$key_arr[2].'" style="height:100px;"></textarea>';
}else if($key_arr[1]=='select'){
$select_arr=explode("##",$key_arr[3]);
$select_str='';
for ($i=0; $i<count($select_arr); $i++) {
$select_str.='<option value="'.$select_arr[$i].'">'.$select_arr[$i].'</option>';
} 
$input='<select name="'.$key_arr[2].'">'.$select_str.'</select>';
}else{
if($key_arr[1]=='number'){
$input_type='number';
}else{
$input_type='text';	
}
$input='<input type="'.$input_type.'" name="'.$key_arr[2].'" class="layui-input">';
}
echo '
<div class="layui-form-item">
<label class="layui-form-label">'.$key_arr[0].'</label>
<div class="layui-input-block" style="width:510px;">'.$input.'</div>
</div>';
}

}

}
?>



<!-- 问答悬赏 -->
<?php if($answer&&$bbs_type!='download'){?>
<div class="layui-form-item" id="jinsom-bbs-publish-answer" style="display: none;">
<label class="layui-form-label">悬赏金额</label>
<div class="layui-input-inline">
<input placeholder="<?php echo $answer_price_mini;?>-<?php echo $answer_price_max;?><?php echo $credit_name;?>" type="number" name="answer-price"  class="layui-input" id="jinsom-bbs-answer-price">
</div>
<div class="layui-form-mid layui-word-aux">后面可以追加，你当前<?php echo $credit_name;?>为：<font style="color: #f00;"><?php echo $credit;?></font></div>
</div>
<?php }//问答悬赏?>



<?php if($pay_see||$login_see||$vip_see||$comment_see){//登录可见、VIP可见、评论可见、付费可见?>

<!-- 隐藏内容 -->
<?php if($bbs_type!='download'){?>
<div class="layui-form-item" id="jinsom-bbs-publish-hide-content" style="display: none;">
<label class="layui-form-label">隐藏内容</label>
<div class="layui-input-block">
<script id="jinsom-bbs-hide-content" name="hide-content" type="text/plain"></script> 
<script type="text/javascript">
window.ue_pay = UE.getEditor('jinsom-bbs-hide-content', {
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
ue_pay.ready(function() {
ue_pay.execCommand('drafts');
});  
</script>  


<div class="jinsom-bbs-edior-footer-bar pay">
<?php if($publish_images){?>
<span id="jinsom-bbs-pay-upload"><i class="fa fa-picture-o"></i></span> 
<?php }?>
<?php if($publish_files){?>
<span onclick="jinsom_upload_file_form(2);"><i class="jinsom-icon jinsom-fujian1"></i></span> 
<?php }?>
<span class="jinsom-ue-edior-smile pay" onclick="jinsom_smile(this,'ue','ue_pay')">
<i class="jinsom-icon jinsom-weixiao-"></i>
</span>   
</div>

</div>
</div><!-- 隐藏内容结束 -->
<?php }?>

<!-- 付费可见 -->
<?php if($pay_see){?>
<div id="jinsom-bbs-publish-price" style="display: none;">
<div class="layui-form-item">
<label class="layui-form-label">设置售价</label>
<div class="layui-input-inline">
<input placeholder="<?php echo $price_mini;?>-<?php echo $price_max;?>" type="number" name="price" class="layui-input" id="jinsom-bbs-price">
</div>
<div class="layui-form-mid layui-word-aux"><?php echo $credit_name;?></div>
</div>
</div>
<?php }//付费可见?>


<?php }//登录可见、VIP可见、评论可见、付费可见?>

<?php if($vote&&$bbs_type!='download'){//投票项 ?>
<div id="jinsom-bbs-publish-vote" style="display: none;">

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">可投项数</label>
<div class="layui-input-inline">
<input type="number" class="layui-input" name="vote-times" value="1" id="jinsom-vote-times">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">结束时间</label>
<div class="layui-input-inline">
<input type="text" class="layui-input" name="vote-time" value="<?php echo date('Y-m-d H:i:s',strtotime("+2 days"));?>" id="jinsom-vote-time">
</div>
</div>
</div> 

<div class="jinsom-bbs-publish-vote-list">
<div class="layui-form-item">
<label class="layui-form-label">投票项1</label>
<div class="layui-input-inline" style="width: 510px;">
<input type="text" class="layui-input">
</div>
</div> 
<div class="layui-form-item">
<label class="layui-form-label">投票项2</label>
<div class="layui-input-inline" style="width: 510px;">
<input type="text" class="layui-input">
</div>
<span class="add" data="2" onclick="jinsom_bbs_vote_add(this);">增加</span>
</div>
</div>
</div>
<?php }?>



<?php if($activity&&$bbs_type!='download'){//活动帖子 ?>
<div id="jinsom-bbs-publish-activity" style="display: none;">

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">报名费用</label>
<div class="layui-input-inline">
<input type="number" class="layui-input"  value="0" id="jinsom-activity-price" name="activity-price">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">结束时间</label>
<div class="layui-input-inline">
<input type="text" class="layui-input" name="activity-time" value="<?php echo date('Y-m-d H:i:s',strtotime("+2 days"));?>" id="jinsom-activity-time">
</div>
</div>
</div>

<p style="text-align:left;margin:20px;color:#999;">
<span style="color: #eee;">——————————</span>
以下选项是用户报名的时候需要填写的表单
<span style="color: #eee;">——————————</span>
</p>
<div class="jinsom-bbs-publish-activity-list">

<div class="layui-form-item">
<div class="layui-inline"> 
<label class="layui-form-label">选项类型</label>
<div class="layui-input-inline">
<select>
<option value="text">文本框</option>
<option value="number">数字框</option>
<option value="textarea">多行文本框</option>
<option value="upload">上传模块</option>
</select>
</div>
</div>

<div class="layui-inline"> 
<label class="layui-form-label">选项名称</label>
<div class="layui-input-inline">
<input type="text" class="layui-input activity_type_name" value="姓名">
</div>
<span class="add" onclick="jinsom_bbs_activity_add(this);">增加选项</span>
</div>
</div> 

</div>

</div>
<?php }?>

</div><!-- 发帖选项表单结束 -->


<input type="hidden" name="bbs_child_id" value="0">
<input type="hidden" name="bbs_id" value="<?php echo $bbs_id;?>">
<?php if($is_bbs_admin){?>
<input type="hidden" name="title_color">
<?php }?>



<div class="jinsom-publish-words-btn single">
<div class="cancel opacity">取消</div>

<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<div id="jinsom-bbs-publish-btn" class="publish opacity"><?php echo $publish_text;?><?php if($credit_post_number<0&&!$pending){echo '（'.$credit_post_number.$credit_name.'）';}?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('jinsom-bbs-publish-btn'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_publish_bbs_post(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div id="jinsom-bbs-publish-btn" onclick="jinsom_publish_bbs_post('','')" class="publish opacity"><?php echo $publish_text;?><?php if($credit_post_number<0&&!$pending){echo '（'.$credit_post_number.$credit_name.'）';}?></div>
<?php }?>

</div>


</form>
</div>



<script type="text/javascript">

<?php if($is_bbs_admin){?>//标题颜色
layui.use('colorpicker', function(){
var colorpicker = layui.colorpicker;
colorpicker.render({
elem: '#jinsom-publish-bbs-title-color',
color: '#333',
predefine: true,
done: function(color){
$('[name="title_color"]').val(color);
}
});
});
<?php }?>

post_type=$('#jinsom-bbs-type').val();
if(post_type=='pay_see'){
$('#jinsom-bbs-publish-price').show();  
}
if(post_type=='pay_see'||post_type=='vip_see'||post_type=='login_see'||post_type=='comment_see'){
$('#jinsom-bbs-publish-hide-content').show();  
}

<?php if($bbs_type!='download'){?>
if(post_type=='answer'){
$('#jinsom-bbs-publish-answer').show();  
}
if(post_type=='vote'){
$('#jinsom-bbs-publish-vote').show();  
}
if(post_type=='activity'){
$('#jinsom-bbs-publish-activity').show();  
}

<?php }?>

<?php if($bbs_type!='download'&&($activity||$vote)){?>

<?php if($vote){?>
var vote_arr=[];
function jinsom_bbs_vote_add(obj){
var vote_num=parseInt($(obj).attr('data'))+1;
$(obj).next('span').remove();
$(obj).parents('.layui-form-item').after('<div class="layui-form-item"><label class="layui-form-label">投票项'+vote_num+'</label><div class="layui-input-inline" style="width: 510px;"><input type="text" class="layui-input"></div><span class="add" data="'+vote_num+'" onclick="jinsom_bbs_vote_add(this);">增加</span><span class="del" data="'+vote_num+'" onclick="jinsom_bbs_vote_del(this);">删除</span></div>');
$(obj).remove();
}
function jinsom_bbs_vote_del(obj){
var vote_list=$(obj).parent('.layui-form-item').index();
var vote_num=parseInt($(obj).attr('data'))-1;

if(vote_list==2){
$(obj).parent('.layui-form-item').prev().append('<span class="add" data="'+vote_num+'" onclick="jinsom_bbs_vote_add(this);">增加</span>');
}else{
$(obj).parent('.layui-form-item').prev().append('<span class="add" data="'+vote_num+'" onclick="jinsom_bbs_vote_add(this);">增加</span><span class="del" data="'+vote_num+'" onclick="jinsom_bbs_vote_del(this);">删除</span>');
}

$(obj).parent('.layui-form-item').remove();
}

<?php }?>


<?php if($activity){?>
function jinsom_bbs_activity_add(obj){
$(obj).next('span').remove();
$(obj).parents('.layui-form-item').after('<div class="layui-form-item">\
<div class="layui-inline" style=""> \
<label class="layui-form-label">选项类型</label>\
<div class="layui-input-inline">\
<select>\
<option value="text">文本框</option>\
<option value="number">数字框</option>\
<option value="textarea">多行文本框</option>\
<option value="upload">上传模块</option>\
</select>\
</div>\
</div>\
<div class="layui-inline" style=""> \
<label class="layui-form-label">选项名称</label>\
<div class="layui-input-inline">\
<input type="text" class="layui-input activity_type_name">\
</div>\
<span class="add" onclick="jinsom_bbs_activity_add(this);">增加选项</span>\
<span class="del" onclick="jinsom_bbs_activity_del(this);">删除</span>\
</div>\
</div> ');
$(obj).remove();

layui.use('form', function(){
var form = layui.form;
form.render('select', 'jinsom-bbs-publish');
});
}

function jinsom_bbs_activity_del(obj){
var activity_list=$(obj).parents('.layui-form-item').index();


if(activity_list==1){
$(obj).parents('.layui-form-item').prev().append('<span class="add"  onclick="jinsom_bbs_activity_add(this);">增加选项</span>');
}else{
$(obj).parents('.layui-form-item').prev().append('<span class="add" onclick="jinsom_bbs_activity_add(this);">增加选项</span><span class="del" onclick="jinsom_bbs_activity_del(this);">删除</span>');
}

$(obj).parents('.layui-form-item').remove();
}

<?php }?>

<?php }//下载类结束?>



layui.use(['form', 'layer','laydate'], function () {
var form = layui.form;
var layer =layui.layer;
var laydate = layui.laydate;//时间
laydate.render({elem: '#jinsom-vote-time',type: 'datetime'});
laydate.render({elem: '#jinsom-activity-time',type: 'datetime'});
form.on('select(jinsom-post-type)', function(data){
if(data.value=='pay_see'){//付费可见
$('#jinsom-bbs-publish-hide-content,#jinsom-bbs-publish-price').show();
$('#jinsom-bbs-publish-vote,#jinsom-bbs-publish-answer,#jinsom-bbs-publish-activity').hide();
}else if(data.value=='vote'){//投票
$('#jinsom-bbs-publish-vote').show();
$('#jinsom-bbs-publish-hide-content,#jinsom-bbs-publish-price,#jinsom-bbs-publish-answer,#jinsom-bbs-publish-activity').hide();
}else if(data.value=='activity'){//活动帖子
$('#jinsom-bbs-publish-activity').show();
$('#jinsom-bbs-publish-vote,#jinsom-bbs-publish-hide-content,#jinsom-bbs-publish-price,#jinsom-bbs-publish-answer').hide();
}else if(data.value=='vip_see'){//VIP可见
$('#jinsom-bbs-publish-hide-content').show();
$('#jinsom-bbs-publish-vote,#jinsom-bbs-publish-price,#jinsom-bbs-publish-answer,#jinsom-bbs-publish-activity').hide();
}else if(data.value=='login_see'){//登录可见
$('#jinsom-bbs-publish-hide-content').show();
$('#jinsom-bbs-publish-vote,#jinsom-bbs-publish-price,#jinsom-bbs-publish-answer,#jinsom-bbs-publish-activity').hide();
}else if(data.value=='comment_see'){//评论可见
$('#jinsom-bbs-publish-hide-content').show();
$('#jinsom-bbs-publish-vote,#jinsom-bbs-publish-price,#jinsom-bbs-publish-answer,#jinsom-bbs-publish-activity').hide();
}else if(data.value=='answer'){
$('#jinsom-bbs-publish-answer').show();
$('#jinsom-bbs-publish-vote,#jinsom-bbs-publish-price,#jinsom-bbs-publish-hide-content,#jinsom-bbs-publish-activity').hide();
}else{//普通帖子
$('#jinsom-bbs-publish-hide-content,#jinsom-bbs-publish-price,#jinsom-bbs-publish-vote,#jinsom-bbs-publish-answer,#jinsom-bbs-publish-activity').hide();
}

});  

          
});



//移除已经选择的话题
$(".jinsom-publish-words-topic.bbs").on("click","span",function(){
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


<?php if($bbs_type=='download'){?>
$(".jinsom-bbs-download-add span").click(function(){
add=$('.jinsom-bbs-download-form .li').html();
$(this).parent().before('<div class="li"><i class="jinsom-icon jinsom-guanbi"></i>'+add+'</div>');
}); 
$('.jinsom-bbs-download-form').on('click','.li>i',function(){
$(this).parent().remove();
});
<?php }?>

</script>
