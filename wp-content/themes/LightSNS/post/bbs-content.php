<?php 
$credit_name=jinsom_get_option('jinsom_credit_name');//金币名称
$answer_adopt=(int)get_post_meta($post_id,'answer_adopt',true);
$author_id=get_the_author_meta('ID');
$post_from=get_post_meta($post_id,'post_from',true);

//人机验证
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');

//更新帖子浏览量
$post_views=(int)get_post_meta($post_id,'post_views',true);
update_post_meta($post_id,'post_views',($post_views+1));


//论坛信息
$bbs_blacklist=get_term_meta($bbs_id,'bbs_blacklist',true);
$credit_reply_number=get_term_meta($bbs_id,'bbs_credit_reply_number',true);
$exp_reply_number=get_term_meta($bbs_id,'bbs_exp_reply_number',true);


//发布选项
$publish_images=get_term_meta($bbs_id,'publish_images',true);
$publish_files=get_term_meta($bbs_id,'publish_files',true);
$activity_text=get_term_meta($bbs_id,'activity_text',true);

//广告
$ad_single_header=get_term_meta($bbs_id,'ad_single_header',true);
$ad_single_footer=get_term_meta($bbs_id,'ad_single_footer',true);
$bbs_blacklist_arr=explode(",",$bbs_blacklist); 


//获取评论数（不含二级）
$comment_floor_args = array(
'post_id' => $post_id,
'parent'=> 0,
'status' =>'approve',
'count'=> true,
);
$comment_floor = get_comments($comment_floor_args);

$jinsom_jinbi_comments_bbs_times = jinsom_get_option('jinsom_jinbi_comments_bbs_times');
$bbs_comment_times = get_user_meta($user_id, 'bbs_comment_times', true );
$post_hide_cnt=get_post_meta($post_id,'post_price_cnt',true);
$post_price=get_post_meta($post_id,'post_price',true);



//楼主标志
if($author_id==$user_id){$landlord='<div class="landlord"></div>';}else{$landlord='';}

//版主名称
$admin_a_name=get_term_meta($bbs_id,'admin_a_name',true);
$admin_b_name=get_term_meta($bbs_id,'admin_b_name',true);

$title_color=get_post_meta($post_id,'title_color',true);//标题颜色

$copyright=get_term_meta($bbs_id,'copyright',true);
if(!$copyright){
$copyright=jinsom_get_option('jinsom_bbs_copyright_info');
}
?>




<div class="jinsom-bbs-single-header" data="<?php echo $bbs_id;?>">
<div class="jinsom-bbs-single-header-info">
<span class="avatar">
<a href="<?php echo get_category_link($child_cat_id);?>"><?php echo jinsom_get_bbs_avatar($child_cat_id,0);?></a>
</span>
<span class="name">
<a href="<?php echo get_category_link($child_cat_id);?>"><?php echo get_category($child_cat_id)->name; ?> </a>
</span>
<?php echo jinsom_bbs_like_btn($user_id,$bbs_id);?>
<span class="jinsom-bbs-follow-info">
<span>关注：<m class="num"><?php echo jinsom_get_bbs_like_number($bbs_id);?></m></span>
<span>内容：<m><?php echo jinsom_get_bbs_post($bbs_id);?></m></span>
</span>
</div>

<div class="jinsom-bbs-single-title clear">
<h1 title="<?php echo get_the_title();?>" <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>><?php the_title();?></h1>
<span class="mark">
<?php 
//有图帖子
if(preg_match("/<img.*>/",get_the_content())){ 
echo '<span class="jinsom-bbs-post-type-img"><i class="jinsom-icon jinsom-tupian2"></i></span>';
}
echo jinsom_bbs_post_type($post_id);
?>
</span>
<span class="do">
<i class="jinsom-icon jinsom-xiangxia2"></i>
<div class="jinsom-bbs-post-setting">
<li onclick="jinsom_post_link(this);" data="<?php echo jinsom_userlink($author_id);?>">查看作者</li>

<?php if($post_status=='publish'){?>

<?php if($user_id!=$author_id){?>
<li class="redbag" onclick="jinsom_reward_form(<?php echo $post_id;?>,'post');">打赏作者</li>
<?php }?>

<?php if(jinsom_is_admin($user_id)){?>
<?php if(is_sticky()){?>
<li class="up" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'sticky-all',this)">取消全局</li>
<?php }else{?>
<li class="up" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'sticky-all',this)">全局置顶</li>
<?php }?>
<?php }?>

<?php if($is_bbs_admin||get_user_meta($user_id,'user_power',true)==3){?>

<?php if($is_bbs_admin){?>

<?php if(get_post_meta($post_id,'jinsom_sticky',true)){?>
<li class="bbs-up" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'sticky-bbs',this)">取消版顶</li>
<?php }else{?>
<li class="bbs-up" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'sticky-bbs',this)">板块置顶</li>
<?php }?>

<?php if(get_post_meta($post_id,'jinsom_commend',true)){?>
<li class="nice" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'commend-bbs',this)">取消加精</li>
<?php }else{?>
<li class="nice" onclick="jinsom_sticky(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'commend-bbs',this)">加精帖子</li>
<?php }?>


<?php echo '<li onclick=\'jinsom_change_bbs_form('.$post_id.','.$bbs_id.')\'>移动板块</li>';?>
<?php }?>

<li onclick="jinsom_content_management_refuse(<?php echo $post_id;?>,<?php echo $bbs_id;?>,1,this)">驳回内容</li>


<?php }?>
<?php }?>

<?php if($user_id){?>
<?php if($user_id==$author_id){?>
<?php if((int)get_user_meta($user_id,'sticky',true)==$post_id){?>
<li class="memnber-up" onclick="jinsom_sticky(<?php echo $post_id;?>,0,'sticky-member',this)">取消主页</li>
<?php }else{?>
<li class="memnber-up" onclick="jinsom_sticky(<?php echo $post_id;?>,0,'sticky-member',this)">主页置顶</li>
<?php }?>
<?php }else{?>
<?php 
if(jinsom_is_blacklist($user_id,$author_id)){
echo '<li onclick=\'jinsom_add_blacklist("remove",'.$author_id.',this)\'>取消拉黑</li>';	
}else{
echo '<li onclick=\'jinsom_add_blacklist("add",'.$author_id.',this)\'>拉黑名单</li>';	
}
?>
<?php }?>
<?php }?>

<?php 
if($user_id==$author_id||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)||jinsom_is_admin_x($user_id)){
$edit_time=(int)jinsom_get_option('jinsom_edit_time_max');
$single_time=strtotime(get_the_time('Y-m-d H:i:s'));
if(time()-$single_time<=60*60*$edit_time||jinsom_is_admin($user_id)||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){
echo '<li onclick=\'jinsom_editor_form("bbs",'.$post_id.')\'>编辑内容</li>';
}
?>


<li class="delete" onclick="jinsom_delete_bbs_post(<?php echo $post_id;?>,<?php echo $bbs_id;?>,this)">删除帖子</li>
<?php }?>
</div>
</span>
</div>

</div>


<?php jinsom_title_bottom_hook();//标题下方的钩子 ?>
<?php echo do_shortcode($ad_single_header);//头部广告?>

<?php if(get_term_meta($bbs_id,'bbs_single_nav',true)){?>
<div class="jinsom-bbs-content-header-nav">
当前位置：
<span><a href="<?php echo home_url();?>"><?php echo jinsom_get_option('jinsom_site_name');?></a> ></span>
<span><a href="<?php echo get_category_link($bbs_id);?>"><?php echo get_category($bbs_id)->name;?></a> ></span>
<?php if($cat_parents===0||$cat_parents!=''){?>
<span><a href="<?php echo get_category_link($child_cat_id);?>"><?php echo get_category($child_cat_id)->name;?></a> ></span>
<?php }?>
正文
</div>
<?php }?>

<?php 
if(get_post_status()=='pending'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('该内容处于审核中状态','jinsom').'</div>';
}else if(get_post_status()=='draft'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('内容处于驳回状态，需重新编辑进行提交审核','jinsom').'</div>';	
}
?>


<div class="jinsom-bbs-single-box main clear">

<?php //左侧工具栏
if($post_status=='publish'){
$jinsom_sidebar_bbs_toolbar_sorter = jinsom_get_option('jinsom_sidebar_bbs_toolbar_sorter_a');
$siderbarr_bbs_toolbar_enabled=$jinsom_sidebar_bbs_toolbar_sorter['enabled'];
if($siderbarr_bbs_toolbar_enabled){
echo '<div class="jinsom-single-left-bar">';
foreach($siderbarr_bbs_toolbar_enabled as $x=>$x_value) {
switch($x){
case 'like': //喜欢
if(jinsom_is_like_post($post_id,$user_id)){
echo '<li onclick="jinsom_single_sidebar_like('.$post_id.',this)" class="jinsom-had-like"><i class="jinsom-icon jinsom-xihuan1"></i></li>';
}else{
echo '<li onclick="jinsom_single_sidebar_like('.$post_id.',this)" class="jinsom-no-like"><i class="jinsom-icon jinsom-xihuan2"></i></li>';
}
break;
case 'comment':  //评论  
$comments_number=get_comments_number($post_id);
if($comments_number){
$comments_number_tips='<span>'.$comments_number.'</span>';
}else{
$comments_number_tips='';
}
echo '<li class="comment" onclick=\'$("html").animate({scrollTop:$("#jinsom-comment-dom").offset().top},500);ue.focus();\'><i class="jinsom-icon jinsom-pinglun2">'.$comments_number_tips.'</i></li>';
break; 
case 'reward'://打赏    
if($user_id!=$author_id){
echo '<li class="redbag" onclick=\'jinsom_reward_form('.$post_id.',"post")\'><i class="jinsom-icon jinsom-hongbao"></i></li>';
}
break;
case 'gift'://礼物   
if($user_id!=$author_id&&jinsom_get_option('jinsom_gift_on_off')&&jinsom_get_option('jinsom_gift_add')){
echo '<li class="gift" onclick=\'jinsom_send_gift_form('.$author_id.','.$post_id.')\'><i class="jinsom-icon jinsom-liwu1"></i></li>';
}
break;
case 'reprint':    //转发
echo '<li onclick="jinsom_reprint_form('.$post_id.')"><i class="jinsom-icon jinsom-zhuanzai"></i></li>'; 
break;
case 'back': //返回论坛
echo '<li class="bbs-avatar" title="返回'.get_category($child_cat_id)->name.'"><a href="'.get_category_link($child_cat_id).'">'.jinsom_get_bbs_avatar($child_cat_id,0).'</a></li>'; 
break;
case 'auto': //自动目录
echo '<li id="jinsom-single-title-list"><i class="jinsom-icon jinsom-mulu1"></i><div class="jinsom-single-title-list-content"><ul></ul></div></li>'; 
break;
}}

echo '</div>';
}
}
?>

<div class="left">
<div class="landlord"></div>
<div class="avatar">
<a href="<?php echo jinsom_userlink($author_id);?>" target="_blank">
<?php echo jinsom_vip_icon($author_id);?>
<?php echo jinsom_avatar($author_id, '50' , avatar_type($author_id) );?>
<?php echo jinsom_verify($author_id);?>
</a>
</div>

<div class="name"><?php echo jinsom_nickname_link($author_id);?></div>


<div class="info">
<?php if(in_array($author_id,$admin_a_arr)||in_array($author_id,$admin_b_arr)){?>
<div class="admin">
<?php 
if(in_array($author_id,$admin_a_arr)){
echo '<span class="jinsom-icon jinsom-guanliyuan1 a"><i>'.$admin_a_name.'</i></span>';
}else{
echo '<span class="jinsom-icon jinsom-guanliyuan1 b"><i>'.$admin_b_name.'</i></span>'; 
}
?>
</div> 
<?php }?>
<div class="lv"><?php echo jinsom_lv($author_id);?></div>
<div class="vip"><?php echo jinsom_vip($author_id);?></div>
<div class="honor"><?php echo jinsom_honor($author_id);?></div>
</div>

</div><!-- left -->


<div class="right">
<div class="jinsom-bbs-single-content">
<?php 
echo do_shortcode(convert_smilies(wpautop(jinsom_autolink(jinsom_add_lightbox_content(get_the_content(),$post_id)))));//内容

$publish_field=get_term_meta($bbs_id,'publish_field',true);//发布字段
if($publish_field){
echo '<div class="jinsom-bbs-single-custom-field clear">';
$publish_field_arr=explode(",",$publish_field);
foreach ($publish_field_arr as $data) {
$key_arr=explode("|",$data);
if($key_arr){
echo '<li class="'.$key_arr[1].'"><label>'.$key_arr[0].'：</label>'.wpautop(convert_smilies(get_post_meta($post_id,$key_arr[2],true))).'</li>';
}
}
echo '</div>';
}

if($post_type=='pay_see'){//付费帖子
if($user_id!=$author_id&&!jinsom_get_pay_result($user_id,$post_id)&&!$is_bbs_admin){//没有权限
echo '
<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> '.__('隐藏内容需要付费才可以看见','jinsom').'</p>';
echo '<div class="jinsom-btn opacity" onclick=\'jinsom_show_pay_form('.$post_id.')\'>'.__('马上购买','jinsom').'</div>';
echo '</div>';
}else{//有权限
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_add_lightbox_content($post_hide_cnt,$post_id))).'</div>'; 
}
}else if($post_type=='vip_see'){//VIP可见

if($user_id!=$author_id&&!is_vip($user_id)&&!$is_bbs_admin){//没有权限
echo'<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> '.__('隐藏内容需要开通VIP才可以看见','jinsom').'</p>';
echo '<div class="jinsom-btn opacity" onclick="jinsom_recharge_vip_form()">'.__('开通VIP','jinsom').'</div>';
echo '</div>';

}else{//有权限
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_add_lightbox_content($post_hide_cnt,$post_id))).'</div>'; 
}

}else if($post_type=='comment_see'){//回复可见

if($user_id!=$author_id&&!jinsom_is_comment($user_id,$post_id)&&!$is_bbs_admin){//判断是否作者自己 
echo '<div class="jinsom-tips jinsom-comment-can-see">
<p><i class="jinsom-icon jinsom-niming"></i> '.__('隐藏内容需要回复可以看见','jinsom').'</p>';

if (is_user_logged_in()) { ?>
<div class="jinsom-btn opacity" onclick="$('html,body').animate({scrollTop:$('#jinsom-comment-dom').offset().top}, 800);ue.focus();">回复</div>
<?php }else{
echo '<div class="jinsom-btn opacity" onclick="jinsom_pop_login_style();">'.__('回复','jinsom').'</div>'; 
}
echo '</div>';

}else{//是作者自己
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_add_lightbox_content($post_hide_cnt,$post_id))).'</div>'; 
}

}else if($post_type=='login_see'){//登录可见

if(!is_user_logged_in()){
echo '<div class="jinsom-tips">
<p><i class="jinsom-icon jinsom-niming"></i> '.__('隐藏内容需要登录才可以看见','jinsom').'</p>
<div class="jinsom-btn opacity" onclick="jinsom_pop_login_style();">'.__('登录','jinsom').'</div>
</div>';
}else{
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_add_lightbox_content($post_hide_cnt,$post_id))).'</div>';
}


}else if($post_type=='vote'){
$vote_data=get_post_meta($post_id,'vote_data',true);
$vote_times=get_post_meta($post_id,'vote_times',true);
$vote_time=get_post_meta($post_id,'vote_time',true);
$vote_arr=explode(",",$vote_data);
$vote_data_num=count($vote_arr);

echo '<div class="jinsom-bbs-vote-form layui-form">';
$a=1;


if(time()<=strtotime($vote_time)){


if(jinsom_is_vote($user_id,$post_id)){//已经投票了
for ($i=0; $i < ($vote_data_num-1)  ; $i+=2) { 
if($vote_arr[$vote_data_num-1]!=0){
$percent=($vote_arr[$i+1]/$vote_arr[$vote_data_num-1])*100;  
}else{
$percent=0;
}

echo '
<div class="jinsom-bbs-vote-text had">'.$vote_arr[$i].'<span>（'.$vote_arr[$i+1].'票）</span></div>
<div class="layui-progress layui-progress-big">
<div class="layui-progress-bar" lay-percent="'.$percent.'%"></div>
</div>';

$a++;
}

echo '
<div class="jinsom-bbs-vote-info">
<span>'.__('投票结束时间','jinsom').'：<i>'.$vote_time.'</i></span>
<span>'.__('允许选择项数','jinsom').'：<i>'.$vote_times.'</i></span>
<span>'.__('当前总投票数','jinsom').'：<i>'.$vote_arr[$vote_data_num-1].'</i></span>
</div>';

echo '<div class="jinsom-btn no">'.__('你已经投票','jinsom').'</div>';



}else{//没有投票

for ($i=0; $i < ($vote_data_num-1)  ; $i+=2) { 
if($vote_arr[$vote_data_num-1]!=0){
$percent=($vote_arr[$i+1]/$vote_arr[$vote_data_num-1])*100;  
}else{
$percent=0;
}
echo '
<input type="checkbox" lay-skin="primary" value="'.$a.'">
<div class="jinsom-bbs-vote-text">'.$vote_arr[$i].'</div>';

$a++;
}

echo '
<div class="jinsom-bbs-vote-info">
<span>'.__('投票结束时间','jinsom').'：<i>'.$vote_time.'</i></span>
<span>'.__('允许选择项数','jinsom').'：<i>'.$vote_times.'</i></span>
<span>'.__('当前总投票数','jinsom').'：<i>'.$vote_arr[$vote_data_num-1].'</i></span>
</div>';

echo '<div class="jinsom-btn opacity" onclick="jinsom_bbs_vote('.$post_id.');">'.__('投票看结果','jinsom').'</div>';


}

}else{//投票结束的情况


for ($i=0; $i < ($vote_data_num-1)  ; $i+=2) { 
if($vote_arr[$vote_data_num-1]!=0){
$percent=($vote_arr[$i+1]/$vote_arr[$vote_data_num-1])*100;  
}else{
$percent=0;
}

echo '
<div class="jinsom-bbs-vote-text had">'.$vote_arr[$i].'<span>（'.$vote_arr[$i+1].'票）</span></div>
<div class="layui-progress layui-progress-big">
<div class="layui-progress-bar" lay-percent="'.$percent.'%"></div>
</div>';

$a++;
}

echo '
<div class="jinsom-bbs-vote-info">
<span>'.__('投票结束时间','jinsom').'：<i>'.$vote_time.'</i></span>
<span>'.__('允许选择项数','jinsom').'：<i>'.$vote_times.'</i></span>
<span>'.__('当前总投票数','jinsom').'：<i>'.$vote_arr[$vote_data_num-1].'</i></span>
</div>';

echo '<div class="jinsom-btn no">'.__('投票已结束','jinsom').'</div>';  


}





//echo '总票数：'.$vote_arr[$vote_data_num-1];


global $wpdb;
$table_name = $wpdb->prefix . 'jin_vote';
$vote_data=$wpdb->get_results("SELECT user_id FROM $table_name WHERE post_id='$post_id' ORDER BY ID DESC limit 100;");
if($vote_data){
echo '<div class="jinsom-post-vote-list"><div class="title">'.__('已参与用户','jinsom').'</div><div class="content clear">';
foreach ($vote_data as $data) {
echo '<li><a href="'.jinsom_userlink($data->user_id).'" target="_blank">'.jinsom_avatar($data->user_id,'40',avatar_type($data->user_id)).'</a></li>';
}
echo '</div></div>';
}

echo '</div>';

}else if($post_type=='activity'){
$activity_time=get_post_meta($post_id,'activity_time',true);
$activity_price=(int)get_post_meta($post_id,'activity_price',true);
$activity_number=get_post_meta($post_id,'activity',true);
if($activity_number){
$activity_number_arr=explode(",",$activity_number);
$activity_number=count($activity_number_arr);//报名人数
}else{
$activity_number=0;	
}
?>
<div class="jinsom-bbs-activity-content">
<div class="jinsom-bbs-activity-info">
<?php if($activity_price){?>
<p>需要费用：<span><?php echo $activity_price.$credit_name;?></span></p>
<?php }?>
<p>参与人数：<span><?php echo $activity_number;?></span></p>
<p>结束时间：<span><?php echo $activity_time;?></span></p>
</div>
<?php 
if(time()<=strtotime($activity_time)){
if(!jinsom_is_join_activity($user_id,$post_id)){
?>
<div class="jinsom-bbs-activity-btn opacity" onclick="jinsom_activity_form(<?php echo $post_id;?>)"><?php echo $activity_text;?></div>
<?php }else{?>
<div class="jinsom-bbs-activity-btn no">你已经参与</div>
<?php }}else{?>
<div class="jinsom-bbs-activity-btn no">已经结束</div>
<?php }?>

</div>
<?php }?>
<script type="text/javascript">
layui.use(['form'], function(){
var form = layui.form;
});  
</script>


</div>

<!-- 话题 -->
<div class="jinsom-single-topic-list clear">
<?php 
$tags=wp_get_post_tags($post_id);
$i=1;
foreach($tags as $tag){
$tag_link=get_tag_link($tag->term_id);
if($i<=10){
echo '<a href="'.$tag_link.'" title="'.$tag->name.'" class="opacity">'.jinsom_get_bbs_avatar($tag->term_id,1).'<span>'.$tag->name.'</span></a>';
}
$i++;
}
?>
</div>

<?php 
if($copyright){
echo '<div class="jinsom-bbs-copyright-info">'.do_shortcode($copyright).'</div>';
}
?>

<?php jinsom_single_content_end_hook();//自定义内容结束钩子?>

<div class="jinsom-bbs-single-footer">
<?php 
$post_city=get_post_meta($post_id,'city',true);
if($post_city){
?>
<span class="jinsom-post-city"><i class="jinsom-icon jinsom-xiazai19"></i> <?php echo $post_city;?></span>
<?php }?>
<?php if($user_id==$author_id||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)||jinsom_is_admin_x($user_id)){//作者、大版主、网站管理?>
<span class="delete" onclick="jinsom_delete_bbs_post(<?php echo $post_id;?>,<?php echo $bbs_id;?>,this);">删除</span>
<?php if($post_type=='answer'&& !$answer_adopt){?>
<span class="add" onclick="jinsom_add_bbs_answer_number(<?php echo $post_id;?>);">追加悬赏</span>
<?php }?>
<?php }?>
<?php 
if($user_id!=$author_id){
if(jinsom_is_blacklist($user_id,$author_id)){
echo '<span onclick=\'jinsom_add_blacklist("remove",'.$author_id.',this)\'>取消拉黑</span>';	
}else{
echo '<span onclick=\'jinsom_add_blacklist("add",'.$author_id.',this)\'>拉黑</span>';	
}
}
?>
<span title="<?php echo get_the_time('Y-m-d H:i:s');?>"><?php echo jinsom_timeago(get_the_time('Y-m-d H:i:s'));?></span>
<span><?php if($post_from=='mobile'){echo '手机端';}else{echo '电脑端'; }?></span>
<span>阅读： <?php  if(empty($post_views)){echo 0;}else{echo jinsom_views_show($post_views);} ?></span>
<span>1楼</span>
<span class="comment" onclick="$('html,body').animate({scrollTop:$('#jinsom-comment-dom').offset().top}, 800);ue.focus();">回复</span>
</div>

</div><!-- right -->
</div><!-- jinsom-bbs-single-box -->

<?php if($post_status=='publish'){?>
<div class="jinsom-bbs-comment-list">
<?php
$comment_not_arr=array();
$current_comment_id='';

//回复内容里面的 把当前评论置顶
if(isset($_GET['comment_id'])&&is_numeric($_GET['comment_id'])){
$current_comment_id=(int)$_GET['comment_id'];
$comment_floor--;
array_push($comment_not_arr,$current_comment_id);
$comments=get_comments('comment__in='.$current_comment_id);
require(get_template_directory().'/post/bbs-comment-1.php');//引人回帖模版
}


$comment_up_id=(int)get_post_meta($post_id,'up-comment',true);//置顶的评论ID
if($comment_up_id&&$comment_up_id!=$current_comment_id){
$comment_floor--;
array_push($comment_not_arr,$comment_up_id);
$comments=get_comments('comment__in='.$comment_up_id);
require(get_template_directory().'/post/bbs-comment-1.php');//引人回帖模版
}

$answer_adopt=(int)get_post_meta($post_id,'answer_adopt',true);//采纳楼层
if($answer_adopt&&$answer_adopt!=$current_comment_id){
$comment_floor--;
array_push($comment_not_arr,$answer_adopt);
$comments=get_comments('comment__in='.$answer_adopt);
require(get_template_directory().'/post/bbs-comment-1.php');//引人回帖模版
}



$number=10;
if($comment_floor>0){
$args = array(
'number' => $number,
'post_id' => $post_id, 
'comment__not_in' =>$comment_not_arr,//不显示已经采纳的楼层
'status' =>'approve',
'parent'=>0,
'orderby' => 'comment_ID',
'order' => 'ASC',
);
$comments=get_comments($args);
require(get_template_directory().'/post/bbs-comment-1.php');//引人回帖模版
}//评论循环结束
?>

</div><!--jinsom-bbs-comment-list -->

<?php if($comment_floor>10){?>
<div id="jinsom-bbs-comment-list-page-b" class="jinsom-bbs-comment-list-page"></div>
<script>
layui.use('laypage', function(){
var laypage = layui.laypage;
laypage.render({
elem:'jinsom-bbs-comment-list-page-b',
count:<?php echo $comment_floor;?>,
// hash:'page',
curr: location.hash.replace('#!page=',''),
layout:['count','page','skip'],
jump: function(obj,first){
if(!first){
jinsom_ajax_comment(<?php echo $post_id;?>,obj.limit,obj.curr);
}
}
});
});
</script>
<?php }?>


<?php echo do_shortcode($ad_single_footer);//底部广告?>


<div id="jinsom-comment-dom"></div>
<?php if(is_user_logged_in()&&!in_array($user_id,$bbs_blacklist_arr)&&(comments_open()||(!comments_open()&&(jinsom_is_admin($user_id)||$user_id==$author_id)))){?>
<div class="jinsom-bbs-edior comment clear">

<script id="jinsom-bbs-comment-edior" name="content" type="text/plain"></script>
<?php if(is_user_logged_in()&&!in_array($user_id,$bbs_blacklist_arr)&&(comments_open()||(!comments_open()&&(jinsom_is_admin($user_id)||$user_id==$author_id)))){?>
<div class="jinsom-bbs-edior-footer-bar comment">
<?php if($publish_images){?>
<span id="jinsom-bbs-comment-upload"><i class="fa fa-picture-o"></i></span> 
<?php }?>
<?php if($publish_files){?>
<span onclick="jinsom_upload_file_form(1)"><i class="jinsom-icon jinsom-fujian1"></i></span> 
<?php }?> 
<span class="jinsom-ue-edior-smile" onclick="jinsom_smile(this,'ue','ue')"><i class="jinsom-icon jinsom-weixiao-"></i></span> 
<?php if($user_id!=$author_id){?>
<span class="redbag" onclick="jinsom_reward_form(<?php echo $post_id;?>,'post');"><i class="jinsom-icon jinsom-hongbao"></i></span>

<?php if(jinsom_get_option('jinsom_gift_on_off')&&jinsom_get_option('jinsom_gift_add')){?>
<span class="gift" onclick="jinsom_send_gift_form(<?php echo $author_id;?>,<?php echo $post_id;?>);"><i class="jinsom-icon jinsom-liwu1"></i></span> 
<?php }?>

<?php }?>

<?php
$jinsom_quick_reoly = jinsom_get_option('jinsom_bbs_quick_reply_add');
if(!empty($jinsom_quick_reoly)){
echo '<div class="right">';
foreach ($jinsom_quick_reoly as $data) {
echo '<m class="opacity" onclick=\'ue.execCommand("inserthtml","'.$data['content'].'")\'>'.$data['title'].'</m>';
}
echo '</div>';
}
?>
</div>
<?php }?>
<script type="text/javascript">
window.ue = UE.getEditor('jinsom-bbs-comment-edior', {
toolbars: [[<?php jinsom_get_edior('bbs_comment');?>'fullscreen',]],
autoHeightEnabled: false,
// autoFloatEnabled: false,
initialFrameHeight:280,
});
ue.ready(function() {
ue.execCommand('drafts');
});  
</script>
</div>  




<?php if($jinsom_machine_verify_on_off&&in_array("comment",$jinsom_machine_verify_use_for)&&!jinsom_is_admin($user_id)){?>
<div class="jinsom-comments-btn opacity" id="comment-a">回 复<?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('comment-a'),'<?php echo $jinsom_machine_verify_appid;?>',function(res){
if(res.ret === 0){jinsom_bbs_comment(<?php echo $post_id;?>,<?php echo $bbs_id;?>,res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div class="jinsom-comments-btn opacity" onclick="jinsom_bbs_comment(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'','')">回 复<?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?></div>
<?php }?>


<?php }else{?>
<div class="jinsom-bbs-no-power">
<?php if(!is_user_logged_in()){
echo '<div class="tips"><p>'.__('请登录之后再进行评论','jinsom').'</p><div class="btn opacity" onclick="jinsom_pop_login_style()">'.__('登录','jinsom').'</div></div>';
}else{
if(in_array($user_id,$bbs_blacklist_arr)&&!jinsom_is_admin($user_id)){
echo '<div class="tips">你是该'.jinsom_get_option('jinsom_bbs_name').'黑名单用户，不能在此发帖或回复！</div>';  
}else if(!comments_open()&&!jinsom_is_admin($user_id)&&$user_id!=$author_id){
echo '<div class="tips">'.__('该帖子已关闭回复','jinsom').'</div>';	
}  
}
?>
</div>
<?php }?>



<!-- 自动目录 -->
<script type="text/javascript">

if($('.jinsom-bbs-single-content').children('h2').length>0||$('.jinsom-bbs-single-content').children('h3').length>0||$('.jinsom-bbs-single-content').children('h4').length>0) {
$('#jinsom-single-title-list').show();
}
$(".jinsom-bbs-single-content").find("h2,h3,h4").each(function(i,item){
var tag = $(item).get(0).nodeName.toLowerCase();
$(item).attr("id","wow"+i);
$(".jinsom-single-title-list-content ul").append('<li class="jinsom-single-title-'+tag+' jinsom-single-title-link" link="#wow'+i+'">'+$(this).text()+'</li>');
});
$(".jinsom-single-title-link").click(function(){
$("html,body").animate({scrollTop: $($(this).attr("link")).offset().top}, 600);
})

</script>

<?php }?><!-- 是否审核/被驳回 -->