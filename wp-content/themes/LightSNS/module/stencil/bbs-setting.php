<?php 
//论坛设置
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$bbs_name=jinsom_get_option('jinsom_bbs_name');//论坛名称
$credit_name=jinsom_get_option('jinsom_credit_name');
$custom_sidebar_number=(int)jinsom_get_option('jinsom_custom_sidebar_number');//小工具数量

//论坛id
$bbs_id=$_POST['bbs_id'];
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_a_arr=explode(",",$admin_a);

if(jinsom_is_admin($user_id)||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){//管理员、网站管理、大版主
//论坛描述
$desc=get_term_meta($bbs_id,'desc',true);
//外观布局
$bbs_type=get_term_meta($bbs_id,'bbs_type',true);//类型
$layout_sidebar=get_term_meta($bbs_id,'layout_sidebar',true);
if($layout_sidebar!='no'){$layout_sidebar=(int)$layout_sidebar;}
$single_sidebar=get_term_meta($bbs_id,'single_sidebar',true);
if($single_sidebar!='no'){$single_sidebar=(int)$single_sidebar;}

$bbs_list_style=(int)get_term_meta($bbs_id,'bbs_list_style',true);
$bbs_css=get_term_meta($bbs_id,'bbs_css',true);
$bbs_mobile_css=get_term_meta($bbs_id,'bbs_mobile_css',true);
$bbs_single_nav=get_term_meta($bbs_id,'bbs_single_nav',true);//面包屑

//发帖权限
$bbs_power=(int)get_term_meta($bbs_id,'bbs_power',true);
$publish_power_exp=(int)get_term_meta($bbs_id,'publish_power_exp',true);
$publish_power_honor=get_term_meta($bbs_id,'publish_power_honor',true);
$publish_power_verify=get_term_meta($bbs_id,'publish_power_verify',true);

//回帖权限
$bbs_comment_power=(int)get_term_meta($bbs_id,'bbs_comment_power',true);
$bbs_comment_power_exp=(int)get_term_meta($bbs_id,'bbs_comment_power_exp',true);
$bbs_comment_power_honor=get_term_meta($bbs_id,'bbs_comment_power_honor',true);
$bbs_comment_power_verify=get_term_meta($bbs_id,'bbs_comment_power_verify',true);


//访问权限
$bbs_visit_power=(int)get_term_meta($bbs_id,'bbs_visit_power',true);
$bbs_visit_pass=get_term_meta($bbs_id,'bbs_visit_pass',true);
$bbs_visit_exp=(int)get_term_meta($bbs_id,'bbs_visit_exp',true);
$bbs_visit_user=get_term_meta($bbs_id,'bbs_visit_user',true);
$bbs_visit_honor=get_term_meta($bbs_id,'bbs_visit_honor',true);
$bbs_visit_verify=get_term_meta($bbs_id,'bbs_visit_verify',true);
$visit_power_pay_price=get_term_meta($bbs_id,'visit_power_pay_price',true);
$visit_power_pay_user_id=get_term_meta($bbs_id,'visit_power_pay_user_id',true);
$visit_power_pay_user_list=get_term_meta($bbs_id,'visit_power_pay_user_list',true);//已经支付的用户

$normal=get_term_meta($bbs_id,'bbs_normal',true);
$pay_see=get_term_meta($bbs_id,'bbs_pay_see',true);
$comment_see=get_term_meta($bbs_id,'bbs_comment_see',true);
$vip_see=get_term_meta($bbs_id,'bbs_vip_see',true);
$login_see=get_term_meta($bbs_id,'bbs_login_see',true);
$password_see=get_term_meta($bbs_id,'bbs_password_see',true);
$follow_bbs_see=get_term_meta($bbs_id,'bbs_follow_bbs_see',true);
$follow_user_see=get_term_meta($bbs_id,'bbs_follow_user_see',true);
$vote=get_term_meta($bbs_id,'bbs_vote',true);
$answer=get_term_meta($bbs_id,'bbs_answer',true);
$group_im=get_term_meta($bbs_id,'bbs_group_im',true);
$activity=get_term_meta($bbs_id,'bbs_activity',true);
$pending=get_term_meta($bbs_id,'pending',true);//审核功能

//发布选项
$publish_images=get_term_meta($bbs_id,'publish_images',true);
$publish_files=get_term_meta($bbs_id,'publish_files',true);
$publish_comment_status=get_term_meta($bbs_id,'publish_comment_status',true);
$publish_comment_private=get_term_meta($bbs_id,'publish_comment_private',true);
$publish_default_topic=get_term_meta($bbs_id,'publish_default_topic',true);
$publish_field=get_term_meta($bbs_id,'publish_field',true);//发布字段


$post_target=get_term_meta($bbs_id,'bbs_post_target',true);
$post_child_commend=get_term_meta($bbs_id,'bbs_post_child_commend',true);
$child_show_bbs=get_term_meta($bbs_id,'child_show_bbs',true);//子论坛显示子版块

//广告
$ad_header=get_term_meta($bbs_id,'bbs_ad_header',true);
$ad_footer=get_term_meta($bbs_id,'bbs_ad_footer',true);
$ad_single_header=get_term_meta($bbs_id,'ad_single_header',true);
$ad_single_footer=get_term_meta($bbs_id,'ad_single_footer',true);

//发布头部自定义区域
$publish_page_header_html=get_term_meta($bbs_id,'publish_page_header_html',true);
$mobile_publish_footer_html=get_term_meta($bbs_id,'mobile_publish_footer_html',true);


$showposts=get_term_meta($bbs_id,'bbs_showposts',true);
$credit_post_number=(int)get_term_meta($bbs_id,'bbs_credit_post_number',true);
$exp_post_number=(int)get_term_meta($bbs_id,'bbs_exp_post_number',true);
$credit_reply_number=(int)get_term_meta($bbs_id,'bbs_credit_reply_number',true);
$exp_reply_number=(int)get_term_meta($bbs_id,'bbs_exp_reply_number',true);
$last_reply_time=get_term_meta($bbs_id,'bbs_last_reply_time',true);

//seo
$bbs_seo_title=get_term_meta($bbs_id,'bbs_seo_title',true);
$bbs_seo_desc=get_term_meta($bbs_id,'bbs_seo_desc',true);
$bbs_seo_keywords=get_term_meta($bbs_id,'bbs_seo_keywords',true);

if(empty($showposts)){$showposts=20;}
if(empty($last_reply_time)){$last_reply_time=9999;}


//菜单
$enabled_menu=get_term_meta($bbs_id,'enabled_menu',true);
$disabled_menu=get_term_meta($bbs_id,'disabled_menu',true);
$enabled_menu_arr=explode(",",$enabled_menu);
$disabled_menu_arr=explode(",",$disabled_menu);
$custom_menu_name_1=get_term_meta($bbs_id,'custom_menu_name_1',true);
$custom_menu_topic_1=get_term_meta($bbs_id,'custom_menu_topic_1',true);
$custom_menu_name_2=get_term_meta($bbs_id,'custom_menu_name_2',true);
$custom_menu_topic_2=get_term_meta($bbs_id,'custom_menu_topic_2',true);
$custom_menu_name_3=get_term_meta($bbs_id,'custom_menu_name_3',true);
$custom_menu_topic_3=get_term_meta($bbs_id,'custom_menu_topic_3',true);
$custom_menu_name_4=get_term_meta($bbs_id,'custom_menu_name_4',true);
$custom_menu_topic_4=get_term_meta($bbs_id,'custom_menu_topic_4',true);
$custom_menu_name_5=get_term_meta($bbs_id,'custom_menu_name_5',true);
$custom_menu_topic_5=get_term_meta($bbs_id,'custom_menu_topic_5',true);


//默认展示数据类型
$data_type=get_term_meta($bbs_id,'data_type',true);


//发表按钮
$publish_text=get_term_meta($bbs_id,'publish_text',true);
if(!$publish_text){$publish_text='发表';}
$activity_text=get_term_meta($bbs_id,'activity_text',true);
if(!$activity_text){$activity_text='我要报名';}

//大小版主名称
$admin_a_name=get_term_meta($bbs_id,'admin_a_name',true);
if(!$admin_a_name){$admin_a_name='大版主';}
$admin_b_name=get_term_meta($bbs_id,'admin_b_name',true);
if(!$admin_b_name){$admin_b_name='小版主';}
?>
<div class="jinsom-bbs-setting-form">

<form class="layui-form" id="jinsom-bbs-setting-form">


<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<li class="layui-this">基本</li>
<li>菜单</li>
<li>外观</li>
<li>权限</li>
<li>功能</li>
<li>发表</li>
<li>帖子</li>
<li>SEO</li>
<li>广告</li>
</ul>
<div class="layui-tab-content" style="padding:0;padding-top:30px;">
<div class="layui-tab-item layui-show">


<div class="jinsom-bbs-child-setting-avatar">
<?php echo jinsom_get_bbs_avatar($bbs_id,0);?>
<span>点击上传头像</span>
</div>	

<div class="layui-form-item">
<label class="layui-form-label"><?php echo $bbs_name;?>头像</label>
<div class="layui-input-block">
<input type="text" name="avatar" placeholder="https:// 正方形即可" class="jinsom-bbs-avatar-input layui-input" value="<?php echo get_term_meta($bbs_id,'bbs_avatar',true);?>">
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label">电脑端封面</label>
<div class="layui-input-block">
<input type="text" name="bg" placeholder="https:// 高度180px 宽度为你自己设置的全站宽度" class="layui-input" value="<?php echo get_term_meta($bbs_id,'bbs_bg',true);?>">
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">手机端封面</label>
<div class="layui-input-block">
<input type="text" name="bg_mobile" placeholder="https:// 尺寸自适应" class="layui-input" value="<?php echo get_term_meta($bbs_id,'bbs_mobile_bg',true);?>">
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php echo $bbs_name;?>说明</label>
<div class="layui-input-block">
<textarea placeholder="建议50字内" class="layui-textarea" name="desc" ><?php echo $desc; ?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php echo $bbs_name;?>公告</label>
<div class="layui-input-block">
<textarea placeholder="当为空时则不开启公告，支持html，比如可以设置颜色、图标、a链接等" class="layui-textarea" name="notice"><?php echo get_term_meta($bbs_id,'bbs_notice',true);?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">电脑端版权</label>
<div class="layui-input-block">
<textarea placeholder="如果留空则使用电脑端<?php echo $bbs_name;?>全局的版权，否则以当前<?php echo $bbs_name;?>设置为准，支持短代码和html代码" class="layui-textarea" name="copyright"><?php echo get_term_meta($bbs_id,'copyright',true);?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">移动端版权</label>
<div class="layui-input-block">
<textarea placeholder="如果留空则使用移动端<?php echo $bbs_name;?>全局的版权，否则以当前<?php echo $bbs_name;?>设置为准，支持短代码和html代码" class="layui-textarea" name="mobile_copyright"><?php echo get_term_meta($bbs_id,'mobile_copyright',true);?></textarea>
</div>
</div>

</div>

<div class="layui-tab-item">

<div class="layui-form-item jinsom-bbs-menu-setting">

<div class="jinsom-bbs-menu-setting-box">
<div class="title">开启的选项</div>
<ul id="jinsom-bbs-menu-setting-1" class="connectedSortable">
<?php 
if($enabled_menu){
if($enabled_menu_arr&&$enabled_menu!='empty'){
foreach ($enabled_menu_arr as $data) {
if($data=='comment'){
$name=__('回复','jinsom');
}else if($data=='new'){
$name=__('最新','jinsom');
}else if($data=='nice'){
$name=__('精华','jinsom');
}else if($data=='pay'){
$name=__('付费','jinsom');	
}else if($data=='answer'){
$name=__('问答','jinsom');
}else if($data=='ok'){
$name=__('已解决','jinsom');
}else if($data=='no'){
$name=__('未解决','jinsom');	
}else if($data=='vote'){
$name=__('投票','jinsom');
}else if($data=='activity'){
$name=__('活动','jinsom');	
}else if($data=='custom_1'){
$name=__('自定义-1','jinsom');	
}else if($data=='custom_2'){
$name=__('自定义-2','jinsom');
}else if($data=='custom_3'){
$name=__('自定义-3','jinsom');	
}else if($data=='custom_4'){
$name=__('自定义-4','jinsom');
}else if($data=='custom_5'){
$name=__('自定义-5','jinsom');
}
echo '<li data="'.$data.'">'.$name.'</li>';
}
}
}else{
echo '
<li data="comment">回复</li>
<li data="new">最新</li>
<li data="nice">精华</li>';
}
?>
</ul>
</div>

<div class="jinsom-bbs-menu-setting-box">
<div class="title">关闭的选项</div>
<ul id="jinsom-bbs-menu-setting-2" class="connectedSortable">
<?php if($disabled_menu){
if($disabled_menu_arr&&$disabled_menu!='empty'){
foreach ($disabled_menu_arr as $data) {
if($data=='comment'){
$name=__('回复','jinsom');
}else if($data=='new'){
$name=__('最新','jinsom');
}else if($data=='nice'){
$name=__('精华','jinsom');
}else if($data=='pay'){
$name=__('付费','jinsom');	
}else if($data=='answer'){
$name=__('问答','jinsom');
}else if($data=='ok'){
$name=__('已解决','jinsom');
}else if($data=='no'){
$name=__('未解决','jinsom');	
}else if($data=='vote'){
$name=__('投票','jinsom');
}else if($data=='activity'){
$name=__('活动','jinsom');	
}else if($data=='custom_1'){
$name=__('自定义-1','jinsom');	
}else if($data=='custom_2'){
$name=__('自定义-2','jinsom');
}else if($data=='custom_3'){
$name=__('自定义-3','jinsom');	
}else if($data=='custom_4'){
$name=__('自定义-4','jinsom');
}else if($data=='custom_5'){
$name=__('自定义-5','jinsom');
}
echo '<li data="'.$data.'">'.$name.'</li>';
}
}
}else{
echo '
<li data="pay">付费</li>
<li data="answer">问答</li>
<li data="ok">已解决</li>
<li data="no">未解决</li>
<li data="vote">投票</li>
<li data="activity">活动</li>
<li data="custom_1">自定义-1</li>
<li data="custom_2">自定义-2</li>
<li data="custom_3">自定义-3</li>
<li data="custom_4">自定义-4</li>
<li data="custom_5">自定义-5</li>';
}
?>
</ul>
</div>

</div>


<script type='text/javascript' src='/wp-includes/js/jquery/ui/core.min.js'></script>

<?php if($GLOBALS['wp_version']<5.6){?>
<script type='text/javascript' src='/wp-includes/js/jquery/ui/widget.min.js'></script>
<?php }?>

<script type='text/javascript' src='/wp-includes/js/jquery/ui/mouse.min.js'></script>
<script type='text/javascript' src='/wp-includes/js/jquery/ui/sortable.min.js'></script>
<script type="text/javascript">
$(function(){
$("#jinsom-bbs-menu-setting-1,#jinsom-bbs-menu-setting-2" ).sortable({
connectWith: ".connectedSortable",
}).disableSelection();
});
</script>

<div class="layui-tab" style="margin-top: 30px;">
<ul class="layui-tab-title">
<li class="layui-this">自定义-1</li>
<li>自定义-2</li>
<li>自定义-3</li>
<li>自定义-4</li>
<li>自定义-5</li>
</ul>
<div class="layui-tab-content">

<div class="layui-tab-item layui-show">
<div class="layui-inline">
<label class="layui-form-label">菜单名称</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_name_1" class="layui-input" value="<?php echo $custom_menu_name_1;?>" style="width: 200px;" placeholder="自定义-1菜单名称">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">调用的话题</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_topic_1" class="layui-input" value="<?php echo $custom_menu_topic_1;?>" style="width: 200px;" placeholder="多个话题ID请用英文逗号隔开">
</div>
</div>
</div>


<div class="layui-tab-item">
<div class="layui-inline">
<label class="layui-form-label">菜单名称</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_name_2" class="layui-input" value="<?php echo $custom_menu_name_2;?>" style="width: 200px;" placeholder="自定义-2菜单名称">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">调用的话题</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_topic_2" class="layui-input" value="<?php echo $custom_menu_topic_2;?>" style="width: 200px;" placeholder="多个话题ID请用英文逗号隔开">
</div>
</div>
</div>


<div class="layui-tab-item">
<div class="layui-inline">
<label class="layui-form-label">菜单名称</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_name_3" class="layui-input" value="<?php echo $custom_menu_name_3;?>" style="width: 200px;" placeholder="自定义-3菜单名称">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">调用的话题</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_topic_3" class="layui-input" value="<?php echo $custom_menu_topic_3;?>" style="width: 200px;" placeholder="多个话题ID请用英文逗号隔开">
</div>
</div>
</div>


<div class="layui-tab-item">
<div class="layui-inline">
<label class="layui-form-label">菜单名称</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_name_4" class="layui-input" value="<?php echo $custom_menu_name_4;?>" style="width: 200px;" placeholder="自定义-4菜单名称">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">调用的话题</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_topic_4" class="layui-input" value="<?php echo $custom_menu_topic_4;?>" style="width: 200px;" placeholder="多个话题ID请用英文逗号隔开">
</div>
</div>
</div>


<div class="layui-tab-item">
<div class="layui-inline">
<label class="layui-form-label">菜单名称</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_name_5" class="layui-input" value="<?php echo $custom_menu_name_5;?>" style="width: 200px;" placeholder="自定义-5菜单名称">
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">调用的话题</label>
<div class="layui-input-block">
<input type="text" name="custom_menu_topic_5" class="layui-input" value="<?php echo $custom_menu_topic_5;?>" style="width: 200px;" placeholder="多个话题ID请用英文逗号隔开">
</div>
</div>
</div>


</div>
</div>


</div>


<div class="layui-tab-item">


<div class="layui-form-item">
<label class="layui-form-label"><?php echo $bbs_name;?>侧栏</label>
<div class="layui-input-inline">
<select name="layout-sidebar">
<option value="no" <?php if($layout_sidebar=='no'){echo 'selected="selected"';}?>>不使用侧栏</option>
<?php for ($i=1; $i <= $custom_sidebar_number; $i++) {?>
<option value="<?php echo $i;?>" <?php if($layout_sidebar===$i){echo 'selected="selected"';}?>>自定义小工具-<?php echo $i;?></option>
<?php }?>
</select>
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label">帖子页侧栏</label>
<div class="layui-input-inline">
<select name="single_sidebar">
<option value="no" <?php if($single_sidebar=='no'){echo 'selected="selected"';}?>>不使用侧栏</option>
<?php for ($i=1; $i <= $custom_sidebar_number; $i++) {?>
<option value="<?php echo $i;?>" <?php if($single_sidebar===$i){echo 'selected="selected"';}?>>自定义小工具-<?php echo $i;?></option>
<?php }?>
</select>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">内页模版</label>
<div class="layui-input-inline">
<select name="bbs_type" lay-filter="bbs_type">
<option value="default" <?php if($bbs_type=='default'){echo 'selected="selected"';}?>>默认模版</option>
<option value="download" <?php if($bbs_type=='download'){echo 'selected="selected"';}?>>下载类模版</option>
</select>
</div>
</div>

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">面包屑</label>
<div class="layui-input-block">
<input type="checkbox" name="bbs_single_nav" lay-skin="switch" lay-text="开启|关闭" <?php if($bbs_single_nav){ echo ' checked';} ?>>
<span style="color: #999;font-size: 12px;vertical-align: -5px;margin-left: 10px;">开启之后，将在帖子内页的顶部显示面包屑导航</span>
</div>
</div>  
</div>

<div class="layui-form-item">
<label class="layui-form-label">列表样式</label>
<div class="layui-input-inline">
<select name="list-style">
<option value="0" <?php if($bbs_list_style==0){echo 'selected="selected"';}?>>简约列表</option>
<option value="1" <?php if($bbs_list_style==1){echo 'selected="selected"';}?>>图文列表</option>
<option value="2" <?php if($bbs_list_style==2){echo 'selected="selected"';}?>>格子列表</option>
<option value="3" <?php if($bbs_list_style==3){echo 'selected="selected"';}?>>瀑布流</option>
<option value="4" <?php if($bbs_list_style==4){echo 'selected="selected"';}?>>格子商品</option>
</select>
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label">电脑端css</label>
<div class="layui-input-block">
<textarea class="layui-textarea" name="css" style="height: 350px;"><?php echo $bbs_css;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">移动端css</label>
<div class="layui-input-block">
<textarea class="layui-textarea" name="mobile_css" style="height: 350px;"><?php echo $bbs_mobile_css;?></textarea>
</div>
</div>

</div>


<div class="layui-tab-item">

<div class="layui-form-item">
<label class="layui-form-label">访问权限</label>
<div class="layui-input-inline">
<select name="visit_power" lay-filter="visit_power_form" id="visit_power_form">
<option value="0" <?php if($bbs_visit_power==0){echo 'selected="selected"';}?>>所有用户</option>
<option value="8" <?php if($bbs_visit_power==8){echo 'selected="selected"';}?>>登录用户</option>
<option value="1" <?php if($bbs_visit_power==1){echo 'selected="selected"';}?>>VIP用户</option>
<option value="3" <?php if($bbs_visit_power==3){echo 'selected="selected"';}?>>管理团队</option>
<option value="2" <?php if($bbs_visit_power==2){echo 'selected="selected"';}?>>所有认证用户</option>
<option value="4" <?php if($bbs_visit_power==4){echo 'selected="selected"';}?>>所有头衔用户</option>
<option value="5" <?php if($bbs_visit_power==5){echo 'selected="selected"';}?>>输入密码</option>
<option value="6" <?php if($bbs_visit_power==6){echo 'selected="selected"';}?>>满足经验的用户</option>
<option value="7" <?php if($bbs_visit_power==7){echo 'selected="selected"';}?>>指定用户</option>
<option value="9" <?php if($bbs_visit_power==9){echo 'selected="selected"';}?>>指定头衔</option>
<option value="10" <?php if($bbs_visit_power==10){echo 'selected="selected"';}?>>指定认证类型</option>
<option value="11" <?php if($bbs_visit_power==11){echo 'selected="selected"';}?>>付费</option>
</select>
</div>
<div class="layui-form-mid layui-word-aux"><font style="color:#f00;">IM权限与<?php echo $bbs_name;?>访问权限是一致的</font></div>
</div>

<div class="layui-form-item" id="jinsom-visit-power-pass" <?php if($bbs_visit_power!=5){echo 'style="display: none;"';}?>>
<label class="layui-form-label">访问密码</label>
<div class="layui-input-inline">
<input type="text" name="visit_power_pass"  autocomplete="off" class="layui-input" value="<?php echo $bbs_visit_pass;?>">
</div>
<?php if(jinsom_is_admin($user_id)){?>
<span class="opacity" onclick="jinsom_delete_bbs_visit_password(<?php echo $bbs_id;?>)">清除密码</span><i>（清除之后，之前输入密码的用户需要重新输入）</i>
<?php }?>
</div>

<div class="layui-form-item" id="jinsom-visit-power-exp" <?php if($bbs_visit_power!=6){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定经验</label>
<div class="layui-input-inline">
<input type="number" name="visit_power_exp" class="layui-input" value="<?php echo $bbs_visit_exp;?>">
</div>
</div>

<div class="layui-form-item" id="jinsom-visit-power-user" <?php if($bbs_visit_power!=7){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定用户</label>
<div class="layui-input-block">
<textarea placeholder="请输入用户ID，用英文逗号隔开" class="layui-textarea" name="visit_power_user"><?php echo $bbs_visit_user;?></textarea>
</div>
</div>

<div class="layui-form-item" id="jinsom-visit-power-honor" <?php if($bbs_visit_power!=9){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定头衔</label>
<div class="layui-input-block">
<textarea placeholder="支持多个头衔，请用英文逗号隔开" class="layui-textarea" name="visit_power_honor"><?php echo $bbs_visit_honor;?></textarea>
</div>
</div>

<div class="layui-form-item" id="jinsom-visit-power-verify" <?php if($bbs_visit_power!=10){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定认证</label>
<div class="layui-input-block">
<textarea placeholder="支持多个认证类型，请用英文逗号隔开" class="layui-textarea" name="visit_power_verify"><?php echo $bbs_visit_verify;?></textarea>
</div>
</div>


<div id="jinsom-visit-power-pay" <?php if($bbs_visit_power!=11){echo 'style="display: none;"';}?>>
<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">-访问售价</label>
<div class="layui-input-block">
<input type="number" name="visit_power_pay_price" placeholder="用户访问需要花费的<?php echo $credit_name;?>" class="layui-input" value="<?php echo $visit_power_pay_price;?>" style="width: 190px;">
</div>
</div>

<div class="layui-inline">
<label class="layui-form-label">收入用户ID</label>
<div class="layui-input-block">
<input type="number" name="visit_power_pay_user_id" placeholder="谁收入<?php echo $credit_name;?>，留空则官方" class="layui-input" value="<?php echo $visit_power_pay_user_id;?>" style="width: 190px;">
</div>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">-已付费用户</label>
<div class="layui-input-block">
<textarea placeholder="多个用户，请用英文逗号隔开" class="layui-textarea" name="visit_power_pay_user_list"><?php echo $visit_power_pay_user_list;?></textarea>
</div>
</div>

</div>



<div class="layui-form-item">
<label class="layui-form-label">发帖权限</label>
<div class="layui-input-inline">
<select name="power" lay-filter="power_form" id="power_form">
<option value="0" <?php if($bbs_power==0){echo 'selected="selected"';}?>>登录用户</option>
<option value="1" <?php if($bbs_power==1){echo 'selected="selected"';}?>>VIP用户</option>
<option value="2" <?php if($bbs_power==2){echo 'selected="selected"';}?>>所有认证用户</option>
<option value="3" <?php if($bbs_power==3){echo 'selected="selected"';}?>>管理团队</option>
<option value="4" <?php if($bbs_power==4){echo 'selected="selected"';}?>>关注<?php echo $bbs_name;?>的用户</option>
<option value="5" <?php if($bbs_power==5){echo 'selected="selected"';}?>>所有头衔用户</option>
<option value="6" <?php if($bbs_power==6){echo 'selected="selected"';}?>>满足经验的用户</option>
<option value="7" <?php if($bbs_power==7){echo 'selected="selected"';}?>>指定头衔</option>
<option value="8" <?php if($bbs_power==8){echo 'selected="selected"';}?>>指定认证类型</option>
</select>
</div>
</div>


<div class="layui-form-item" id="jinsom-publish-power-lv" <?php if($bbs_power!=6){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定经验</label>
<div class="layui-input-inline">
<input type="number" name="publish_power_exp" class="layui-input" value="<?php echo $publish_power_exp;?>">
</div>
<div class="layui-form-mid layui-word-aux">当用户经验大于或等于此经验值才可以发帖</div>
</div>

<div class="layui-form-item" id="jinsom-publish-power-honor" <?php if($bbs_power!=7){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定头衔</label>
<div class="layui-input-block">
<textarea placeholder="支持多个头衔，请用英文逗号隔开" class="layui-textarea" name="publish_power_honor"><?php echo $publish_power_honor;?></textarea>
</div>
</div>

<div class="layui-form-item" id="jinsom-publish-power-verify" <?php if($bbs_power!=8){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定认证</label>
<div class="layui-input-block">
<textarea placeholder="支持多个认证类型，请用英文逗号隔开" class="layui-textarea" name="publish_power_verify"><?php echo $publish_power_verify;?></textarea>
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label">回帖权限</label>
<div class="layui-input-inline">
<select name="bbs_comment_power" lay-filter="comment_power" id="jinsom-bbs-comment-power">
<option value="0" <?php if($bbs_comment_power==0){echo 'selected="selected"';}?>>登录用户</option>
<option value="1" <?php if($bbs_comment_power==1){echo 'selected="selected"';}?>>VIP用户</option>
<option value="2" <?php if($bbs_comment_power==2){echo 'selected="selected"';}?>>所有认证用户</option>
<option value="3" <?php if($bbs_comment_power==3){echo 'selected="selected"';}?>>管理团队</option>
<option value="4" <?php if($bbs_comment_power==4){echo 'selected="selected"';}?>>关注<?php echo $bbs_name;?>的用户</option>
<option value="5" <?php if($bbs_comment_power==5){echo 'selected="selected"';}?>>所有头衔用户</option>
<option value="6" <?php if($bbs_comment_power==6){echo 'selected="selected"';}?>>满足经验的用户</option>
<option value="7" <?php if($bbs_comment_power==7){echo 'selected="selected"';}?>>指定头衔</option>
<option value="8" <?php if($bbs_comment_power==8){echo 'selected="selected"';}?>>指定认证类型</option>
</select>
</div>
</div>


<div class="layui-form-item" id="jinsom-bbs-comment-power-lv" <?php if($bbs_comment_power!=6){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定经验</label>
<div class="layui-input-inline">
<input type="number" name="bbs_comment_power_exp" class="layui-input" value="<?php echo $bbs_comment_power_exp;?>">
</div>
<div class="layui-form-mid layui-word-aux">当用户经验大于或等于此经验值才可以发帖</div>
</div>

<div class="layui-form-item" id="jinsom-bbs-comment-power-honor" <?php if($bbs_comment_power!=7){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定头衔</label>
<div class="layui-input-block">
<textarea placeholder="支持多个头衔，请用英文逗号隔开" class="layui-textarea" name="bbs_comment_power_honor"><?php echo $bbs_comment_power_honor;?></textarea>
</div>
</div>

<div class="layui-form-item" id="jinsom-bbs-comment-power-verify" <?php if($bbs_comment_power!=8){echo 'style="display: none;"';}?>>
<label class="layui-form-label">-指定认证</label>
<div class="layui-input-block">
<textarea placeholder="支持多个认证类型，请用英文逗号隔开" class="layui-textarea" name="bbs_comment_power_verify"><?php echo $bbs_comment_power_verify;?></textarea>
</div>
</div>



<?php if(jinsom_is_admin($user_id)){?>
<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">大版主名称</label>
<div class="layui-input-block">
<input type="text" name="admin_a_name" class="layui-input" value="<?php echo $admin_a_name;?>" style="width: 190px;">
</div>
</div>

<div class="layui-inline">
<label class="layui-form-label">小版主名称</label>
<div class="layui-input-block">
<input type="text" name="admin_b_name" class="layui-input" value="<?php echo $admin_b_name;?>" style="width: 190px;">
</div>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php echo $bbs_name;?>大版主</label>
<div class="layui-input-block">
<textarea placeholder="大版主：请输入用户ID，用英文逗号隔开【权限仅限于所在<?php echo $bbs_name;?>：设置<?php echo $bbs_name;?>信息(积分经验除外)、删帖、删评论、加精、置顶、驳回、编辑】" class="layui-textarea" name="admin_a"><?php echo get_term_meta($bbs_id,'bbs_admin_a',true);?></textarea>
</div>
</div>
<?php }?>

<div class="layui-form-item">
<label class="layui-form-label"><?php echo $bbs_name;?>小版主</label>
<div class="layui-input-block">
<textarea placeholder="小版主：请输入用户ID，用英文逗号隔开【权限仅限于所在<?php echo $bbs_name;?>：加精、置顶、驳回、删评论】" class="layui-textarea" name="admin_b"><?php echo get_term_meta($bbs_id,'bbs_admin_b',true);?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php echo $bbs_name;?>黑名单</label>
<div class="layui-input-block">
<textarea placeholder="请输入用户ID，用英文逗号隔开，备注：这里的黑名单仅仅针对该<?php echo $bbs_name;?>，不是全站黑名单，该<?php echo $bbs_name;?>黑名单用户不能在此<?php echo $bbs_name;?>进行发布或回复操作" class="layui-textarea" name="blacklist"><?php echo get_term_meta($bbs_id,'bbs_blacklist',true);?></textarea>
</div>
</div>

</div>



<div class="layui-tab-item">

<div class="layui-form-item">

<div class="layui-inline">
<label class="layui-form-label">普通内容</label>
<div class="layui-input-block">
<input type="checkbox" name="normal" lay-skin="switch" lay-text="开启|关闭" <?php if($normal){ echo ' checked';} ?>>
</div>
</div>


<div class="layui-inline">
<label class="layui-form-label">付费内容</label>
<div class="layui-input-block">
<input type="checkbox" name="pay_see" lay-skin="switch" lay-text="开启|关闭" <?php if($pay_see){ echo ' checked';} ?>>
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">回复可见</label>
<div class="layui-input-block">
<input type="checkbox" name="comment_see" lay-skin="switch" lay-text="开启|关闭" <?php if($comment_see){ echo ' checked';} ?>>
</div>
</div>
<div class="layui-inline">
<label class="layui-form-label">VIP可见</label>
<div class="layui-input-block">
<input type="checkbox" name="vip_see" lay-skin="switch" lay-text="开启|关闭" <?php if($vip_see){ echo ' checked';} ?>>
</div>
</div>


</div><!-- 分行 -->

<div class="layui-form-item">


<div class="layui-inline">
<label class="layui-form-label">问答悬赏</label>
<div class="layui-input-block">
<input type="checkbox" name="answer" lay-skin="switch" lay-text="开启|关闭" <?php if($answer){ echo ' checked';} ?>>
</div>
</div>

<div class="layui-inline">
<label class="layui-form-label">登录可见</label>
<div class="layui-input-block">
<input type="checkbox" name="login_see" lay-skin="switch" lay-text="开启|关闭" <?php if($login_see){ echo ' checked';} ?>>
</div>
</div>


<div class="layui-inline">
<label class="layui-form-label">投票</label>
<div class="layui-input-block">
<input type="checkbox" name="vote" lay-skin="switch" lay-text="开启|关闭" <?php if($vote){ echo ' checked';} ?>>
</div>
</div>

<div class="layui-inline">
<label class="layui-form-label">活动</label>
<div class="layui-input-block">
<input type="checkbox" name="activity" lay-skin="switch" lay-text="开启|关闭" <?php if($activity){ echo ' checked';} ?>>
</div>
</div> 




</div><!-- 分行 -->

<div class="layui-form-item">

<div class="layui-inline">
<label class="layui-form-label">群组聊天</label>
<div class="layui-input-block">
<input type="checkbox" name="group_im" lay-skin="switch" lay-text="开启|关闭" <?php if($group_im){ echo ' checked';} ?>>
</div>
</div>  

<div class="layui-inline">
<label class="layui-form-label">审核功能</label>
<div class="layui-input-block">
<input type="checkbox" name="pending" lay-skin="switch" lay-text="开启|关闭" <?php if($pending){ echo ' checked';} ?>>
</div>
</div>  





</div>

</div>


<div class="layui-tab-item">

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label">发表按钮</label>
<div class="layui-input-block">
<input type="text" name="publish_text" placeholder="发表" class="layui-input" value="<?php echo $publish_text;?>" style="width: 200px;">
</div>
</div>

<div class="layui-inline">
<label class="layui-form-label">报名按钮</label>
<div class="layui-input-block">
<input type="text" name="activity_text" placeholder="我要报名" class="layui-input" value="<?php echo $activity_text;?>" style="width: 200px;">
</div>
</div>
</div>

<div class="layui-form-item">

<div class="layui-inline">
<label class="layui-form-label">图片上传</label>
<div class="layui-input-block">
<input type="checkbox" name="publish_images" lay-skin="switch" lay-text="开启|关闭" <?php if($publish_images){ echo ' checked';} ?>>
</div>
</div> 

<div class="layui-inline">
<label class="layui-form-label">附件上传</label>
<div class="layui-input-block">
<input type="checkbox" name="publish_files" lay-skin="switch" lay-text="开启|关闭" <?php if($publish_files){ echo ' checked';} ?>>
</div>
</div> 

<div class="layui-inline">
<label class="layui-form-label">禁止回复</label>
<div class="layui-input-block">
<input type="checkbox" name="publish_comment_status" lay-skin="switch" lay-text="开启|关闭" <?php if($publish_comment_status){ echo ' checked';} ?>>
</div>
</div>  

<div class="layui-inline">
<label class="layui-form-label">回复隐私</label>
<div class="layui-input-block">
<input type="checkbox" name="publish_comment_private" lay-skin="switch" lay-text="开启|关闭" <?php if($publish_comment_private){ echo ' checked';} ?>>
</div>
</div> 

</div>

<?php if(jinsom_is_admin($user_id)){?>

<div class="layui-form-item">
<label class="layui-form-label">发表字段</label>
<div class="layui-input-block">
<textarea placeholder="名称|输入框类型|字段key值,名称|输入框类型|字段key值,名称|输入框类型|字段key值" class="layui-textarea" name="publish_field"><?php echo $publish_field;?></textarea>
</div>
<div class="layui-form-mid layui-word-aux" style="margin-left: 110px;margin-bottom: 20px;">输入框类型有：text、link、number、select、textarea，点击查看<a href="https://q.jinsom.cn/34432.html" target="_blank" style="color: #f00;">《配置教程》</a></div>
</div>

<?php }?>


<div class="layui-form-item">
<label class="layui-form-label">发表预设话题</label>
<div class="layui-input-block">
<textarea placeholder="用户发表帖子的时候会自动选择预设的话题，多个话题请用英文逗号隔开，记得是英文逗号！！！" class="layui-textarea" name="publish_default_topic"><?php echo $publish_default_topic;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">【电脑端】发布页面头部自定义区</label>
<div class="layui-input-block">
<textarea placeholder="一般用于提示发表注意事项" class="layui-textarea" name="publish_page_header_html" style="height:350px;"><?php echo $publish_page_header_html;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">【移动端】发布页面底部自定义区</label>
<div class="layui-input-block">
<textarea placeholder="一般用于提示发表注意事项，支持短代码和html" class="layui-textarea" name="mobile_publish_footer_html" style="height:350px;"><?php echo $mobile_publish_footer_html;?></textarea>
</div>
</div>

</div>



<div class="layui-tab-item">


<div class="layui-form-item">
<label class="layui-form-label">新窗口打开</label>
<div class="layui-input-inline">
<input type="checkbox" name="post_target" lay-skin="switch" lay-text="开启|关闭" <?php if($post_target){ echo ' checked';} ?>>
</div>
<div class="layui-form-mid layui-word-aux"><?php echo $bbs_name;?>列表的帖子点击是否新窗口打开</div>
</div>



<div class="layui-form-item">
<label class="layui-form-label">子<?php echo $bbs_name;?>置顶</label>
<div class="layui-input-inline">
<input type="checkbox" name="post_child_commend" lay-skin="switch" lay-text="开启|关闭" <?php if($post_child_commend){ echo ' checked';} ?>>
</div>
<div class="layui-form-mid layui-word-aux">开启之后，子<?php echo $bbs_name;?>也显示置顶帖子</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php echo $bbs_name;?>子板块</label>
<div class="layui-input-inline">
<input type="checkbox" name="child_show_bbs" lay-skin="switch" lay-text="开启|关闭" <?php if($child_show_bbs){ echo ' checked';} ?>>
</div>
<div class="layui-form-mid layui-word-aux">开启之后，<?php echo $bbs_name;?>子版块也显示子版块列表</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">帖子数量</label>
<div class="layui-input-inline">
<input type="number" name="showposts" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo $showposts;?>">
</div>
<div class="layui-form-mid layui-word-aux"><?php echo $bbs_name;?>列表显示帖子数量，默认显示20条</div>
</div>

<?php if(jinsom_is_admin($user_id)){?>
<div class="layui-form-item">
<label class="layui-form-label">发帖积分</label>
<div class="layui-input-inline">
<input type="number" name="credit_post_number" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo $credit_post_number;?>">
</div>
<div class="layui-form-mid layui-word-aux">每次发帖可获得<?php echo $credit_name;?>，负数则需要对应的<?php echo $credit_name;?>才可以发帖</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">回帖积分</label>
<div class="layui-input-inline">
<input type="number" name="credit_reply_number" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo $credit_reply_number;?>">
</div>
<div class="layui-form-mid layui-word-aux">每次回帖可获得<?php echo $credit_name;?>，负数则需要对应的<?php echo $credit_name;?>才可以回帖</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label">发帖经验</label>
<div class="layui-input-inline">
<input type="number" name="exe_post_number" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo $exp_post_number;?>">
</div>
<div class="layui-form-mid layui-word-aux">每次发帖可获得经验，不能为负数或0</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">回帖经验</label>
<div class="layui-input-inline">
<input type="number" name="exe_reply_number" lay-verify="required" autocomplete="off" class="layui-input" value="<?php echo $exp_reply_number;?>">
</div>
<div class="layui-form-mid layui-word-aux">每次回帖可获得经验，不能为负数或0</div>
</div>
<?php }?>



<div class="layui-form-item">
<label class="layui-form-label">防挖坟时间</label>
<div class="layui-input-inline">
<input type="number" name="last_reply_time" lay-verify="required" placeholder="请输入天数" autocomplete="off" class="layui-input" value="<?php echo $last_reply_time;?>">
</div>
<div class="layui-form-mid layui-word-aux">【天数】帖子超过这个时间之后将无法进行回复</div>
</div>


</div>

<div class="layui-tab-item">
<div class="layui-form-item">
<label class="layui-form-label">SEO标题</label>
<div class="layui-input-block">
<textarea  class="layui-textarea" name="bbs_seo_title" placeholder="留空则使用默认"><?php echo $bbs_seo_title;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">SEO描述</label>
<div class="layui-input-block">
<textarea  class="layui-textarea" name="bbs_seo_desc"><?php echo $bbs_seo_desc;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">SEO关键词</label>
<div class="layui-input-block">
<textarea class="layui-textarea" name="bbs_seo_keywords" placeholder="建议使用英文逗号隔开"><?php echo $bbs_seo_keywords;?></textarea>
</div>
</div>	
</div>



<div class="layui-tab-item">
<div class="layui-form-item">
<label class="layui-form-label">列表顶部</label>
<div class="layui-input-block">
<textarea placeholder="请输入广告代码，支持html" class="layui-textarea" name="ad_header" style="height:350px;"><?php echo $ad_header;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">列表底部</label>
<div class="layui-input-block">
<textarea placeholder="请输入广告代码，支持html" class="layui-textarea" name="ad_footer" style="height:350px;"><?php echo $ad_footer;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">帖子顶部</label>
<div class="layui-input-block">
<textarea placeholder="请输入广告代码，支持html" class="layui-textarea" name="ad_single_header" style="height:350px;"><?php echo $ad_single_header;?></textarea>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label">帖子底部</label>
<div class="layui-input-block">
<textarea placeholder="请输入广告代码，支持html" class="layui-textarea" name="ad_single_footer" style="height:350px;"><?php echo $ad_single_footer;?></textarea>
</div>
</div>

</div>




</div>
</div>


















<input type="hidden" value="<?php echo $bbs_id;?>" name="bbs_id"></input>
</form>

</div>
<?php }//判断管理员权限 ?>