<!-- 编辑帖子页面 -->
<?php
$user_id=$current_user->ID;
$post_id=(int)$_GET['post_id'];
$author_id=jinsom_get_user_id_post($post_id);
$topics=wp_get_post_tags($post_id);
$credit_name=jinsom_get_option('jinsom_credit_name');

//获取论坛id
$category_a = get_the_category($post_id);
$bbs_id_a=$category_a[0]->term_id;
$bbs_id_b=$category_a[1]->term_id;
if(count($category_a)>1){//有两个分类


$category=get_category($category_a[0]->term_id);
$cat_parents=$category->parent;//获取其中一个分类的父级
if($cat_parents==0){//判断该分类是否有父级
$bbs_id=$category_a[0]->term_id;//如果本身是父级，那么直接返回他的分类id
}else{
$bbs_id=$category_a[1]->term_id;//否则返回另一个分类的id   
}
}else{
$bbs_id=$category_a[0]->term_id;//如果只有一个分类，那么直接反馈第一个分类的分类id
}

//判断第0个分类===获取当前内容 在哪个子论坛。（前提是有子论坛）
if($category_a[0]->term_id==$bbs_id){
$bbs_child_name=$category_a[1]->cat_name;//子论坛名称
$bbs_child_id=$category_a[1]->cat_ID;//子论坛id
}else{
$bbs_child_name=$category_a[0]->cat_name;
$bbs_child_id=$category_a[0]->cat_ID;//子论坛id
}

// print_r($category_a[0]);
// echo $category_a[1]->cat_ID;

//权限
$bbs_type=get_term_meta($bbs_id,'bbs_type',true);//类型
$bbs_power=get_term_meta($bbs_id,'bbs_power',true);
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$bbs_blacklist=get_term_meta($bbs_id,'bbs_blacklist',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);
$bbs_blacklist_arr=explode(",",$bbs_blacklist); 


if(is_user_logged_in()){
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

//功能
$normal=get_term_meta($bbs_id,'bbs_normal',true);
$pay_see=get_term_meta($bbs_id,'bbs_pay_see',true);
$comment_see=get_term_meta($bbs_id,'bbs_comment_see',true);
$vip_see=get_term_meta($bbs_id,'bbs_vip_see',true);
$login_see=get_term_meta($bbs_id,'bbs_login_see',true);
$password_see=get_term_meta($bbs_id,'bbs_password_see',true);
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
//售价范围
$price_mini = (int)jinsom_get_option('jinsom_bbs_pay_price_mini');
$price_max = (int)jinsom_get_option('jinsom_bbs_pay_price_max');

$categories_child = get_categories("child_of=".$bbs_id."&orderby=description&order=ASC&hide_empty=0");//返回子分类的数组


$post_data=get_post($post_id,ARRAY_A);
$title=$post_data['post_title'];
$content=$post_data['post_content'];
$hide_content=get_post_meta($post_id,'post_price_cnt',true);

$post_type=get_post_meta($post_id,'post_type',true);
$price=get_post_meta($post_id,'post_price',true);//售价
$comment_private=get_post_meta($post_id,'comment_private',true);//回复隐私
$answer_number=get_post_meta($post_id,'answer_number',true);//悬赏金额

if(!is_user_logged_in()||in_array($user_id,$bbs_blacklist_arr)){
exit();	
}

if(!jinsom_is_admin($user_id)&&$user_id!=$author_id&&!in_array($user_id,$admin_a_arr)){//没有权限
exit();
}

if(!is_bbs_post($post_id)){//不是帖子类型
exit();	
}


//发表按钮
if(get_term_meta($bbs_id,'pending',true)&&!jinsom_is_admin($user_id)&&!in_array($user_id,$admin_a_arr)){
$publish_text='提交审核';
}else{
$publish_text='更新内容';
}

$title_color=get_post_meta($post_id,'title_color',true);//标题颜色

if($bbs_type=='download'){
function jinsom_download_publish_tlp($a,$b,$c,$close){
if($close){
$close='<i class="jinsom-icon jinsom-guanbi"></i>';
}else{
$close='';
}
return '<div class="li">'.$close.'
<div class="layui-form-item">
<label class="layui-form-label">下载地址</label>
<div class="layui-input-block">
<input placeholder="https://" type="text" class="layui-input download-url" style="width: 510px;" value="'.$a.'">
</div>
</div>
<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">提取密码</label>
<div class="layui-input-inline">
<input type="text" class="layui-input download-pass-a" value="'.$b.'">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">解压密码</label>
<div class="layui-input-inline">
<input type="text" class="layui-input download-pass-b" value="'.$c.'">
</div>
</div>
</div>
</div>';
}
}
?>

<div class="jinsom-editor-bbs-content">
<form id="jinsom-bbs-publish-form" class="pop">

<div class="title">
<input type="text" name="title" class="jinsom-bbs-title" placeholder="请输入标题" value="<?php echo $title;?>">
<?php if($is_bbs_admin){?>
<div id="jinsom-publish-bbs-title-color" class="color"></div>
<?php }?>
</div>

<div class="jinsom-bbs-edior clear">
<script id="jinsom-bbs-edior" type="text/plain" name="content"></script>
<div id="jinsom-editor-bbs-content" style="display: none;"><?php echo $content;?></div>
<script type="text/javascript">
window.ue = UE.getEditor('jinsom-bbs-edior', {
toolbars: [
[
<?php jinsom_get_edior('bbs');?>
'fullscreen', //全屏
]
],
autoHeightEnabled: true,
autoFloatEnabled: true,
initialFrameHeight:$(window).height()-220,
});   
ue.ready(function() {
ue.setContent($('#jinsom-editor-bbs-content').html());
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
</div>




<!-- 发帖选项表单开始 -->
<div class="jinsom-bbs-publish-info layui-form" lay-filter="jinsom-bbs-publish">

<!-- 话题列表 -->
<div class="jinsom-publish-words-topic bbs pop">
<?php 
if($topics){
foreach ($topics as $topic) {
echo '<span data="'.$topic->name.'">#'.$topic->name.'#</span>';
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
<input type="checkbox" name="comment-status" lay-skin="switch" lay-text="开|关" <?php if(!comments_open($post_id)){echo 'checked';}?>>
</div>
</div>
<?php }//评论状态?>

<?php if($publish_comment_private){//回复隐私?>
<div class="layui-inline" style=""> 
<label class="layui-form-label">回复隐私</label>
<div class="layui-input-inline" style="width: 70px;">
<input type="checkbox" name="comment-private" lay-skin="switch" lay-text="开|关" <?php if($comment_private){echo 'checked';}?>>
</div>
<div class="layui-form-mid layui-word-aux">回复内容仅作者可见</div>
</div>
<?php }//回复隐私?>
</div>
<?php }//评论状态||回复隐私?>


<div class="layui-form-item">

<!-- 帖子分类 -->
<?php if(!empty($categories_child)){?>
<div class="layui-inline" style=""> 
<label class="layui-form-label">内容分类</label>
<div class="layui-input-inline">
<select name="category" id="jinsom-bbs-category">
<?php
foreach($categories_child as $category_child_a) {
if($bbs_child_name==$category_child_a->cat_name){$checked='selected="selected"';}else{$checked='';}
echo '<option value ="'.$category_child_a->cat_ID.'" '.$checked.'>'.$category_child_a->cat_name.'</option>';}?>
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
<option value="pay_see" <?php if($post_type=='pay_see'){echo 'selected="selected"';}?>>付费内容</option>
<?php }?>
<?php if($answer&&$bbs_type!='download'){?>
<option value="answer" <?php if($post_type=='answer'){echo 'selected="selected"';}?>>问答悬赏</option>
<?php }?>
<?php if($vip_see){?>
<option value="vip_see" <?php if($post_type=='vip_see'){echo 'selected="selected"';}?>>VIP 可见</option>
<?php }?>
<?php if($login_see){?>
<option value="login_see" <?php if($post_type=='login_see'){echo 'selected="selected"';}?>>登录可见</option>
<?php }?>
<?php if($comment_see&&$bbs_type!='download'){?>
<option value="comment_see" <?php if($post_type=='comment_see'){echo 'selected="selected"';}?>>回复可见</option>
<?php }?>
<?php if($vote&&$bbs_type!='download'){?>
<option value="vote" <?php if($post_type=='vote'){echo 'selected="selected"';}?>>投票</option>
<?php }?>
<?php if($activity&&$bbs_type!='download'){?>
<option value="activity" <?php if($post_type=='activity'){echo 'selected="selected"';}?>>活动</option>
<?php }?>
</select>
</div>
</div>
<?php }?>

</div>



<?php if($bbs_type=='download'){
$download_data=get_post_meta($post_id,'download_data',true);
?>
<div class="jinsom-bbs-download-form">
<?php 
if($download_data){
$download_data_arr=explode(",",$download_data);
if($download_data_arr){
$i=0;
foreach ($download_data_arr as $data) {
$arr=explode("|",$data);
if(count($arr)==3&&jinsom_trimall($arr[0])!=''){
echo jinsom_download_publish_tlp($arr[0],$arr[1],$arr[2],$i);
}
$i++;
}
}else{
echo jinsom_download_publish_tlp('','','',1);
}
}else{
echo jinsom_download_publish_tlp('','','',1);
}
?>
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
$value=get_post_meta($post_id,$key_arr[2],true);
if($key_arr){
if($key_arr[1]=='textarea'){
$input='<textarea class="layui-textarea" name="'.$key_arr[2].'" style="height:100px;">'.$value.'</textarea>';
}else if($key_arr[1]=='select'){
$select_arr=explode("##",$key_arr[3]);
$select_str='';
for ($i=0; $i<count($select_arr); $i++) {
if($value==$select_arr[$i]){
$had='selected=""';
}else{
$had='';
}
$select_str.='<option value="'.$select_arr[$i].'" '.$had.'>'.$select_arr[$i].'</option>';
} 
$input='<select name="'.$key_arr[2].'">'.$select_str.'</select>';
}else{
if($key_arr[1]=='number'){
$input_type='number';
}else{
$input_type='text';	
}
$input='<input type="'.$input_type.'" name="'.$key_arr[2].'" class="layui-input"  value="'.$value.'">';
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
<?php if($answer){?>
<?php if(get_post_meta($post_id,'post_type',true)!='answer'){?>
<div class="layui-form-item" id="jinsom-bbs-publish-answer" style="display: none;">
<label class="layui-form-label">悬赏金额</label>
<div class="layui-input-inline">
<input placeholder="<?php echo $answer_price_mini;?>-<?php echo $answer_price_max;?><?php echo $credit_name;?>" type="number" name="answer-price"  class="layui-input" id="jinsom-bbs-answer-price" value="<?php echo $answer_number;?>">
</div>
<div class="layui-form-mid layui-word-aux">后面可以追加，你当前<?php echo $credit_name;?>为：<font style="color: #f00;"><?php echo $credit;?></font></div>
</div>
<?php }else{?>
<div class="layui-form-item" id="jinsom-bbs-publish-answer" style="display: none;">
<label class="layui-form-label">悬赏金额</label>
<div class="layui-input-inline">
<input type="text" disabled  class="layui-input" value="<?php echo $answer_number;?>" style="cursor: not-allowed;">
</div>
<div class="layui-form-mid layui-word-aux">不能编辑金额，只能在详情页面追加悬赏金额</div>
</div>
<?php }?>
<?php }//问答悬赏?>



<?php if($pay_see||$login_see||$vip_see||$comment_see){//登录可见、VIP可见、评论可见、付费可见?>

<!-- 隐藏内容 -->	
<?php if($bbs_type!='download'){?>
<div class="layui-form-item" id="jinsom-bbs-publish-hide-content" style="display: none;">
<label class="layui-form-label">隐藏内容</label>
<div class="layui-input-block">
<script id="jinsom-bbs-hide-content" name="hide-content" type="text/plain"></script> 
<div id="jinsom-editor-bbs-pay-content" style="display: none;"><?php echo $hide_content;?></div>
<script type="text/javascript">
window.ue_pay = UE.getEditor('jinsom-bbs-hide-content', {
toolbars: [
[
<?php jinsom_get_edior('bbs_pay');?>
'fullscreen', //全屏
]
],
autoHeightEnabled: true,
autoFloatEnabled: true,
initialFrameHeight:$(window).height()-220,
});
ue_pay.ready(function() {
ue_pay.setContent($('#jinsom-editor-bbs-pay-content').html());
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
<div class="layui-form-item" id="jinsom-bbs-publish-price" style="display: none;">
<label class="layui-form-label">设置售价</label>
<div class="layui-input-inline">
<input placeholder="<?php echo $price_mini;?>-<?php echo $price_max;?>" type="number" name="price" class="layui-input" id="jinsom-bbs-price" value="<?php echo $price;?>">
</div>
<div class="layui-form-mid layui-word-aux"><?php echo $credit_name;?></div>
</div>
<?php }//付费可见?>


<?php }//登录可见、VIP可见、评论可见、付费可见?>

<?php 
if($vote&&$bbs_type!='download'){//投票项
$vote_times=get_post_meta($post_id,'vote_times',true);
$vote_time=get_post_meta($post_id,'vote_time',true);
$vote_data=get_post_meta($post_id,'vote_data',true);
$vote_data_arr=explode(",",$vote_data);
array_pop($vote_data_arr);//删除最后一个数组
$count=count($vote_data_arr);
$last=$count/2+1;
?>
<div id="jinsom-bbs-publish-vote" style="display: none;">

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">可投项数</label>
<div class="layui-input-inline">
<input type="number" class="layui-input" name="vote-times" id="jinsom-vote-times" value="<?php echo $vote_times;?>">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">结束时间</label>
<div class="layui-input-inline">
<input type="text" class="layui-input" name="vote-time" value="<?php echo $vote_time;?>" id="jinsom-vote-time">
</div>
</div>
</div> 

<div class="jinsom-bbs-publish-vote-list">
<?php 
for ($i=0; $i < $count; $i+=2) { 
$number=($i+2)/2;
echo '
<div class="layui-form-item">
<label class="layui-form-label">投票项'.$number.'</label>
<div class="layui-input-inline" style="width: 400px;">
<input type="text" class="layui-input" value="'.$vote_data_arr[$i].'" disabled style="cursor:not-allowed;">
</div>
</div> ';
}

?>


</div>
</div>
<?php }?>



<?php 
if($activity&&$bbs_type!='download'){//活动帖子
$activity_time=get_post_meta($post_id,'activity_time',true);
$activity_price=get_post_meta($post_id,'activity_price',true);
$activity_data=get_post_meta($post_id,'activity_data',true);
$activity_data_arr=explode(",",$activity_data);

?>
<div id="jinsom-bbs-publish-activity" style="display: none;">

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">报名费用</label>
<div class="layui-input-inline">
<input type="number" class="layui-input" id="jinsom-activity-price" name="activity-price" value="<?php echo $activity_price;?>">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">结束时间</label>
<div class="layui-input-inline">
<input type="text" class="layui-input" name="activity-time" value="<?php echo $activity_time;?>" id="jinsom-activity-time">
</div>
</div>
</div>

<p style="text-align:left;margin:20px;color:#999;">
<span style="color: #eee;">——————————</span>
以下选项是用户报名的时候需要填写的表单
<span style="color: #eee;">——————————</span>
</p>
<div class="jinsom-bbs-publish-activity-list">

<?php 
for ($i=0; $i < count($activity_data_arr); $i+=2) { ?>
<div class="layui-form-item">
<div class="layui-inline"> 
<label class="layui-form-label">选项类型</label>
<div class="layui-input-inline">
<select>
<option value="text" <?php if($activity_data_arr[$i]=='text'){echo 'selected="selected"';};?>>文本框</option>
<option value="number" <?php if($activity_data_arr[$i]=='number'){echo 'selected="selected"';};?>>数字框</option>
<option value="textarea" <?php if($activity_data_arr[$i]=='textarea'){echo 'selected="selected"';};?>>多行文本框</option>
<option value="upload" <?php if($activity_data_arr[$i]=='upload'){echo 'selected="selected"';};?>>上传模块</option>
</select>
</div>
</div>
<div class="layui-inline"> 
<label class="layui-form-label">选项名称</label>
<div class="layui-input-inline">
<input type="text" class="layui-input activity_type_name" value="<?php echo $activity_data_arr[$i+1];?>">
</div>
</div>
</div>
<?php }?>

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
<input type="text" class="layui-input activity_type_name">
</div>
<span class="add" onclick="jinsom_bbs_activity_add(this);">增加选项</span>
<span class="del" onclick="jinsom_bbs_activity_del(this);">删除</span>
</div>
</div> 

</div>

</div>
<?php }?>


</div><!-- 发帖选项表单结束 -->

<input type="hidden" name="bbs_child_id" value="<?php echo $bbs_child_id;?>">
<input type="hidden" name="bbs_id" value="<?php echo $bbs_id;?>">
<?php if($is_bbs_admin){?>
<input type="hidden" name="title_color" value="<?php echo $title_color;?>">
<?php }?>


<div class="jinsom-publish-words-btn single">
<div class="cancel opacity">取消</div>
<div id="jinsom-bbs-publish-btn" onclick="jinsom_editor_bbs_post(<?php echo $post_id;?>)" class="publish opacity"><?php echo $publish_text;?></div>
</div>


</form>

</div><!-- 帖子编辑区域 -->


<script type="text/javascript">
<?php if($is_bbs_admin){?>//标题颜色
layui.use('colorpicker', function(){
var colorpicker = layui.colorpicker;
colorpicker.render({
elem: '#jinsom-publish-bbs-title-color',
color: '<?php echo $title_color;?>',
predefine: true,
done: function(color){
$('[name="title_color"]').val(color);
}
});
});
<?php }?>


post_type=$('#jinsom-bbs-type').val();
if(post_type=='answer'){
$('#jinsom-bbs-publish-answer').show();  
}
if(post_type=='pay_see'){
$('#jinsom-bbs-publish-price').show();  
}
if(post_type=='vote'){
$('#jinsom-bbs-publish-vote').show();  
}
if(post_type=='activity'){
$('#jinsom-bbs-publish-activity').show();  
}
if(post_type=='pay_see'||post_type=='vip_see'||post_type=='login_see'||post_type=='comment_see'){
$('#jinsom-bbs-publish-hide-content').show();  
}

<?php if($bbs_type!='download'&&($activity||$vote)){?>




<?php if($activity){?>
function jinsom_bbs_activity_add(obj){
$(obj).next('span').remove();
$(obj).remove();
$('.jinsom-bbs-publish-activity-list').append('<div class="layui-form-item">\
<div class="layui-inline" style=""> \
<label class="layui-form-label">选项类型</label>\
<div class="layui-input-inline" style="margin-right: -5px;width: 120px;">\
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
<div class="layui-input-inline" style="width: 160px;">\
<input type="text" class="layui-input activity_type_name">\
</div>\
<span class="add" onclick="jinsom_bbs_activity_add(this);">增加选项</span>\
<span class="del" onclick="jinsom_bbs_activity_del(this);">删除</span>\
</div>\
</div> ');

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