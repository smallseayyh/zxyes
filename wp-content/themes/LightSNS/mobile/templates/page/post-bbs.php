<?php 
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$credit_name=jinsom_get_option('jinsom_credit_name');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];

$more_type='single';//用于区分当前页面是列表页面还是内页

//获取帖子的最父级分类id
$category_a = get_the_category($post_id);
$bbs_id_a=$category_a[0]->term_id;
$bbs_id_b=$category_a[1]->term_id;
if(count($category_a)>1){
$category=get_category($category_a[0]->term_id);
$cat_parents=$category->parent;
if($cat_parents==0){//判断该分类是否有父级
$bbs_id=$category_a[0]->term_id;
$child_cat_id=$category_a[1]->term_id; 
}else{
$bbs_id=$category_a[1]->term_id;
$child_cat_id=$category_a[0]->term_id;     
}
}else{
$bbs_id=$category_a[0]->term_id; 
$child_cat_id=$category_a[0]->term_id;  
}

$bbs_type=get_term_meta($bbs_id,'bbs_type',true);
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_b=get_term_meta($bbs_id,'bbs_admin_b',true);
$admin_a_arr=explode(",",$admin_a);
$admin_b_arr=explode(",",$admin_b);

if (is_user_logged_in()) {
$is_bbs_admin=(jinsom_is_admin($user_id)||in_array($user_id,$admin_a_arr)||in_array($user_id,$admin_b_arr))?1:0;
}else{
$is_bbs_admin=0;
}

$bbs_visit_power=get_term_meta($bbs_id,'bbs_visit_power',true);
if($bbs_visit_power==1){
if(!is_vip($user_id)&&!$is_bbs_admin){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1; 
}
}else if($bbs_visit_power==2){
$user_verify=get_user_meta($user_id, 'verify', true );
if(!$user_verify&&!$is_bbs_admin){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1;   
}
}else if($bbs_visit_power==3){
if(!$is_bbs_admin){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1;  
}
}else if($bbs_visit_power==4){
$user_honor=get_user_meta($user_id,'user_honor',true);
if(!$user_honor&&!$is_bbs_admin){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1; 
}
}else if($bbs_visit_power==5){
//先判断用户是否已经输入密码
if(!jinsom_is_bbs_visit_pass($bbs_id,$user_id)&&!$is_bbs_admin){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1; 
}
}else if($bbs_visit_power==6){//满足经验的用户
$bbs_visit_exp=(int)get_term_meta($bbs_id,'bbs_visit_exp',true);
$current_user_lv=jinsom_get_user_exp($user_id);//当前用户经验
if($current_user_lv<$bbs_visit_exp&&!$is_bbs_admin){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1; 
}
}else if($bbs_visit_power==7){//指定用户
$bbs_visit_user=get_term_meta($bbs_id,'bbs_visit_user',true);
$bbs_visit_user_arr=explode(",",$bbs_visit_user);
if(!in_array($user_id,$bbs_visit_user_arr)&&!$is_bbs_admin){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1; 
}
}else if($bbs_visit_power==8){//登录用户
if(!is_user_logged_in()){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1;
}
}else if($bbs_visit_power==9){//指定头衔
if(!$is_bbs_admin){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor!=''){
$bbs_visit_honor=get_term_meta($bbs_id,'bbs_visit_honor',true);
$bbs_visit_honor_arr=explode(",",$bbs_visit_honor);
$user_honor_arr=explode(",",$user_honor);
if(!array_intersect($bbs_visit_honor_arr,$user_honor_arr)){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1;
}
}else{
$bbs_visit_power=0;
}
}else{
$bbs_visit_power=1;
}
}else if($bbs_visit_power==10){//指定认证类型
if(!$is_bbs_admin){
$user_verify_type=jinsom_verify_type($user_id);
if($user_verify_type!=''){
$bbs_visit_verify=get_term_meta($bbs_id,'bbs_visit_verify',true);
$bbs_visit_verify_arr=explode(",",$bbs_visit_verify);
if(!in_array($user_verify_type,$bbs_visit_verify_arr)){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1;
}
}else{
$bbs_visit_power=0;
}
}else{
$bbs_visit_power=1;
}
}else if($bbs_visit_power==11){//付费访问
if(!$is_bbs_admin){
$bbs_pay_user_list=get_term_meta($bbs_id,'visit_power_pay_user_list',true);
$bbs_pay_user_list_arr=explode(",",$bbs_pay_user_list);
if($bbs_pay_user_list){
if(!in_array($user_id,$bbs_pay_user_list_arr)){
$bbs_visit_power=0;
}else{
$bbs_visit_power=1;
}
}else{
$bbs_visit_power=0;
}
}else{
$bbs_visit_power=1;
}
}else{//有权限的情况
$bbs_visit_power=1; 
} 



$post_type=get_post_meta($post_id,'post_type',true);
$answer_adopt=(int)get_post_meta($post_id,'answer_adopt',true);
$bbs_name=get_category($child_cat_id)->name;
$post_data = get_post($post_id, ARRAY_A);
$author_id=$post_data['post_author'];
$content=do_shortcode(convert_smilies(wpautop(jinsom_autolink(jinsom_add_lightbox_content($post_data['post_content'],$post_id)))));
$title=$post_data['post_title'];
$post_date=$post_data['post_date'];
$reprint_times=(int)get_post_meta($post_id,'reprint_times',true);
//更新内容浏览量
$post_views=(int)get_post_meta($post_id,'post_views',true);
update_post_meta($post_id,'post_views',$post_views+1);

$comments_number=get_comments_number($post_id);
$credit_reply_number=get_term_meta($bbs_id,'bbs_credit_reply_number',true);
$activity_text=get_term_meta($bbs_id,'activity_text',true);

//置顶、推荐
$commend_post=get_post_meta($post_id,'jinsom_commend',true);
$sticky_post=is_sticky($post_id);

$title_color=get_post_meta($post_id,'title_color',true);//标题颜色


$post_status=get_post_status($post_id);//内容状态


$copyright=get_term_meta($bbs_id,'mobile_copyright',true);
if(!$copyright){
$copyright=jinsom_get_option('jinsom_mobile_bbs_copyright_info');
}
?>
<div data-page="post-single" class="page no-tabbar post-bbs">

<?php 
//移动端自定义css
echo '
<style type="text/css">
'.get_term_meta($bbs_id,'bbs_mobile_css',true).'
</style>'
?>

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="link icon-only">
<span class="avatarimg">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?>
<?php echo jinsom_verify($author_id);?>
</span>
<span class="name"><?php echo get_user_meta($author_id,'nickname',true);?></span>
</a>
</div>
<div class="right">
	
<?php if(is_user_logged_in()){?>
<a href="#" class="link icon-only">
<?php if($user_id!=$author_id){echo jinsom_mobile_follow_button($user_id,$author_id);}?>
</a>
<?php }else{?>
<a href="#" class="link icon-only jinsom-login-avatar open-login-screen">
<?php if($user_id!=$author_id){echo jinsom_mobile_follow_button($user_id,$author_id);}?>
</a>
<?php }?>

</div>
</div>
</div>


<?php if($bbs_visit_power&&$post_status!='pending'&&$post_status!='draft'&&$bbs_type!='download'){?>
<div class="toolbar">
<div class="toolbar-inner">
<div class="jinsom-post-words-tool">

<?php if(is_user_logged_in()){?>
<?php if(comments_open($post_id)||(!comments_open($post_id)&&(jinsom_is_admin($user_id)||$user_id==$author_id))){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/comment-bbs.php?post_id=<?php echo $post_id;?>&bbs_id=<?php echo $bbs_id;?>" class="link">
<i class="jinsom-icon jinsom-fabu7"></i> <?php _e('写评论','jinsom');?><?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?>
</a>
<?php }else{?>
<a><?php _e('该内容已关闭回复','jinsom');?></a>	
<?php }}else{?>
<a onclick="myApp.loginScreen();"><i class="jinsom-icon jinsom-fabu7"></i> <?php _e('写评论','jinsom');?><?php if($credit_reply_number<0){echo '（'.$credit_reply_number.$credit_name.'）';}?></a>
<?php }?>


<span>
<a class="link comment"><i class="jinsom-icon jinsom-pinglun2 comment"><?php if($comments_number){?><m><?php echo $comments_number;?></m><?php }?></i></a>

<?php if(jinsom_is_collect($user_id,'post',$post_id,'')){?>
<a class="link collect collect-post-<?php echo $post_id;?>" onclick="jinsom_collect(<?php echo $post_id;?>,'post',this)"><i class="jinsom-icon jinsom-shoucang"></i></a>
<?php }else{?>
<a class="link collect collect-post-<?php echo $post_id;?>" onclick="jinsom_collect(<?php echo $post_id;?>,'post',this)"><i class="jinsom-icon jinsom-shoucang1"></i></a>
<?php }?>

<?php if($user_id!=$author_id){?>
<a href="<?php echo $theme_url;?>/mobile/templates/page/reward.php?post_id=<?php echo $post_id;?>&type=post" class="link reward">
<i class="jinsom-icon jinsom-hongbao1"></i></a>

<?php if(jinsom_get_option('jinsom_gift_on_off')&&jinsom_get_option('jinsom_gift_add')){?>
<a class="link gift" onclick="jinsom_send_gift_page(<?php echo $author_id;?>,<?php echo $post_id;?>)"><i class="jinsom-icon jinsom-liwu2"></i></a>
<?php }?>

<?php }?>
<a class="link" onclick="jinsom_post_more_form(<?php echo $post_id;?>,0,'single')"><i class="jinsom-icon jinsom-zhuanfa"></i></a>
</span>

</div>
</div>
</div>
<?php }?>

<div class="page-content keep-toolbar-on-scroll infinite-scroll jinsom-page-single-content jinsom-page-single-content-<?php echo $post_id;?>" data-distance="800">
<?php if($bbs_visit_power){?>

<?php 
if($post_status=='pending'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('该内容处于审核中状态','jinsom').'</div>';
}else if($post_status=='draft'){
echo '<div class="jinsom-no-power-tips"><i class="fa fa-warning"></i> '.__('内容处于驳回状态，需重新编辑进行提交审核','jinsom').'</div>';	
}
?>

<div class="jinsom-single jinsom-post-<?php echo $post_id;?>">
<h1 <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>>
<?php 
if($sticky_post){echo '<span class="sticky"></span>';}
if($commend_post){echo '<span class="commend"></span>';}
?>
<?php echo $title;?>
<?php echo jinsom_mobile_bbs_post_type($post_id);?>
</h1>

<div class="jinsom-single-author-info">
<span class="name"><?php _e('浏览','jinsom');?> <?php echo $post_views;?></span>
<span class="dot">•</span>
<span class="from"><?php echo jinsom_post_from($post_id);?></span>
<span class="dot">•</span>
<span class="time"><?php echo date('Y-m-d H:i',strtotime($post_date));?></span>
</div>

<?php jinsom_title_bottom_hook();//标题下方的钩子 ?>

<div class="jinsom-post-single-content"><?php echo $content;?></div>

<?php 

$publish_field=get_term_meta($bbs_id,'publish_field',true);//发布字段
if($publish_field||$bbs_type=='download'){
echo '<div class="jinsom-bbs-single-custom-field">';
if($publish_field){
$publish_field_arr=explode(",",$publish_field);
foreach ($publish_field_arr as $data) {
$key_arr=explode("|",$data);
if($key_arr){
if($key_arr[1]=='link'){
echo '<li class="'.$key_arr[1].'"><label>'.$key_arr[0].'：</label><a href="'.get_post_meta($post_id,$key_arr[2],true).'" target="_blank">'.get_post_meta($post_id,$key_arr[2],true).'</a></li>';
}else{
echo '<li class="'.$key_arr[1].'"><label>'.$key_arr[0].'：</label>'.wpautop(convert_smilies(get_post_meta($post_id,$key_arr[2],true))).'</li>';
}
}
}
}
if($bbs_type=='download'){
echo '<li class="text"><label>下载次数：</label><p>'.(int)get_post_meta($post_id,'download_times',true).'</p></li>';
}
echo '</div>';
}

if($bbs_type!='download'){
$post_hide_cnt=get_post_meta($post_id,'post_price_cnt',true);
if($post_type=='pay_see'){//付费帖子
if($user_id!=$author_id&&!jinsom_get_pay_result($user_id,$post_id)&&!jinsom_is_admin($user_id)){//没有权限
echo '
<div class="jinsom-tips jinsom-tips-'.$post_id.'">
<p><i class="jinsom-icon jinsom-niming"></i> '.__('隐藏内容需要付费才可以看见','jinsom').'</p>';
echo '<div class="jinsom-btn opacity" onclick=\'jinsom_buy_post_form('.$post_id.')\'>'.__('购买','jinsom').'</div>';
echo '</div>';
}else{//有权限
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_add_lightbox_content($post_hide_cnt,$post_id))).'</div>'; 
}
}else if($post_type=='vip_see'){

if($user_id!=$author_id&&!is_vip($user_id)&&!jinsom_is_admin($user_id)){//没有权限
echo'<div class="jinsom-tips jinsom-tips-'.$post_id.'">
<p><i class="jinsom-icon jinsom-niming"></i> '.__('隐藏内容需要开通VIP才可以看见','jinsom').'</p>';
echo '<div class="jinsom-btn opacity" onclick="jinsom_recharge_vip_type_form()">'.__('开通','jinsom').'</div>';
echo '</div>';

}else{//有权限
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_add_lightbox_content($post_hide_cnt,$post_id))).'</div>'; 
}

}else if($post_type=='comment_see'){//回复可见

if($user_id!=$author_id&&!jinsom_is_comment($user_id,$post_id)&&!jinsom_is_admin($user_id)){//判断是否作者自己 
echo '<div class="jinsom-tips jinsom-comment-can-see jinsom-tips-'.$post_id.'">
<p><i class="jinsom-icon jinsom-niming"></i> '.__('隐藏内容需要回复才可以看见','jinsom').'</p>';

if (is_user_logged_in()){
echo '<div class="jinsom-btn opacity"><a href="'.$theme_url.'/mobile/templates/page/comment-bbs.php?post_id='.$post_id.'&bbs_id='.$bbs_id.'" class="link">'.__('回复','jinsom').'</a></div>';
}else{
echo '<div class="jinsom-btn open-login-screen opacity">'.__('回复','jinsom').'</div>'; 
}
echo '</div>';

}else{//是作者自己
echo '<div class="jinsom-hide-content">'.do_shortcode(convert_smilies(jinsom_add_lightbox_content($post_hide_cnt,$post_id))).'</div>'; 
}

}else if($post_type=='login_see'){//登录可见

if(!is_user_logged_in()){
echo '<div class="jinsom-tips jinsom-tips-'.$post_id.'">
<p><i class="jinsom-icon jinsom-niming"></i> '.__('隐藏内容需要登录才可以看见','jinsom').'</p>
<div class="jinsom-btn open-login-screen opacity">'.__('登录','jinsom').'</div>
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
<div class="jinsom-bbs-vote-text had">'.$vote_arr[$i].'<span>（'.sprintf(__( '%s票','jinsom'),$vote_arr[$i+1]).'）</span></div>
<div class="jinsom-bbs-vote-progress">
<div class="jinsom-bbs-vote-progress-bar" style="width:'.$percent.'%"></div>
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
<li><input type="checkbox" lay-skin="primary" value="'.$a.'">
<div class="jinsom-bbs-vote-text">'.$vote_arr[$i].'</div></li>';

$a++;
}

echo '
<div class="jinsom-bbs-vote-info">
<span>'.__('投票结束时间','jinsom').'：<i>'.$vote_time.'</i></span>
<span>'.__('允许选择项数','jinsom').'：<i>'.$vote_times.'</i></span>
<span>'.__('当前总投票数','jinsom').'：<i>'.$vote_arr[$vote_data_num-1].'</i></span>
</div>';

echo '<div class="jinsom-btn opacity" onclick="jinsom_bbs_vote('.$post_id.');">'.__('马上投票看结果','jinsom').'</div>';


}

}else{//投票结束的情况


for ($i=0; $i < ($vote_data_num-1)  ; $i+=2) { 
if($vote_arr[$vote_data_num-1]!=0){
$percent=($vote_arr[$i+1]/$vote_arr[$vote_data_num-1])*100;  
}else{
$percent=0;
}

echo '
<div class="jinsom-bbs-vote-text had">'.$vote_arr[$i].'<span>（'.sprintf(__( '%s票','jinsom'),$vote_arr[$i+1]).'）</span></div>
<div class="jinsom-bbs-vote-progress">
<div class="jinsom-bbs-vote-progress-bar" style="width:'.$percent.'%"></div>
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



global $wpdb;
$table_name = $wpdb->prefix . 'jin_vote';
$vote_data=$wpdb->get_results("SELECT user_id FROM $table_name WHERE post_id='$post_id' ORDER BY ID DESC limit 100;");
if($vote_data){
echo '<div class="jinsom-post-vote-list"><div class="title">'.__('已参与用户','jinsom').'</div><div class="content clear">';
foreach ($vote_data as $data) {
echo '<li><a href="'.jinsom_mobile_author_url($data->user_id).'" class="link">'.jinsom_avatar($data->user_id,'40',avatar_type($data->user_id)).'</a></li>';
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
<p><?php _e('需要费用','jinsom');?>：<span><?php echo $activity_price.$credit_name;?></span></p>
<?php }?>
<p><?php _e('参与人数','jinsom');?>：<span><?php echo $activity_number;?></span></p>
<p><?php _e('结束时间','jinsom');?>：<span><?php echo $activity_time;?></span></p>
</div>
<?php 
if(time()<=strtotime($activity_time)){
if(!jinsom_is_join_activity($user_id,$post_id)){
?>

<?php if(is_user_logged_in()){?>
<div class="jinsom-bbs-activity-btn opacity" onclick="myApp.getCurrentView().router.load({url:jinsom.theme_url+'/mobile/templates/page/activity-form.php?post_id=<?php echo $post_id;?>'});"><?php echo $activity_text;?></div>
<?php }else{?>
<div class="jinsom-bbs-activity-btn opacity open-login-screen"><?php echo $activity_text;?></div>
<?php }?>

<?php }else{?>
<div class="jinsom-bbs-activity-btn no"><?php _e('你已经参与','jinsom');?></div>
<?php }}else{?>
<div class="jinsom-bbs-activity-btn no"><?php _e('已经结束','jinsom');?></div>
<?php }?>

</div>
<?php }?>

<?php }else{//判断是否属于 下载类型?>
<?php 
$download_data=get_post_meta($post_id,'download_data',true);
$download_data_arr=explode(",",$download_data);
$download_arr=explode("|",$download_data_arr[0]);
$download_url=$download_arr[0];
$download_pass_a=$download_arr[1];
$download_pass_b=$download_arr[2];
if(!$download_pass_a){$download_pass_a='无';}
if(!$download_pass_b){$download_pass_b='无';}
$tips='';
$pass='
<div class="info">
<span>提取密码：<i>'.$download_pass_a.'</i></span>
<span>解压密码：<i>'.$download_pass_b.'</i></span>
</div>';

if(count($download_data_arr)>1){
$pass.='<div class="download-more"><div class="content">';

$i=1;
foreach ($download_data_arr as $data) {
$arr=explode("|",$data);
$pass_a=$arr[1];
$pass_b=$arr[2];
if($pass_a==''){$pass_a='无';}
if($pass_b==''){$pass_b='无';}
if($i!=1){
$pass.='<li>
<div class="top">
<div class="name">备用下载'.($i-1).'</div>
<div class="url"><a href="'.$arr[0].'" target="_blank" onclick="jinsom_download_times('.$post_id.')">下载</a></div>
</div>
<div class="pass">
<span>提取密码：<i>'.$pass_a.'</i></span>
<span>解压密码：<i>'.$pass_b.'</i></span>
</div>
</li>';
}
$i++;
}


$pass.='</div></div>';
}


$download_btn='<a href="'.$download_url.'" class="btn" target="_blank" onclick="jinsom_download_times('.$post_id.')">'.__('立即下载','jinsom').'</a>';

if($post_type=='pay_see'){//付费帖子
if($user_id!=$author_id&&!jinsom_get_pay_result($user_id,$post_id)&&!jinsom_is_admin($user_id)){//没有权限
$tips='你没有权限，需要付费购买才可以进行下载';
$pass='';
$download_btn='<a class="btn" onclick=\'jinsom_buy_post_form('.$post_id.')\'>'.__('立即下载','jinsom').'</a>';
}
}else if($post_type=='vip_see'){
if($user_id!=$author_id&&!is_vip($user_id)&&!jinsom_is_admin($user_id)){//没有权限
$tips='你没有权限，需要VIP用户才可以进行下载';
$pass='';
$download_btn='<a class="btn" onclick=\'jinsom_recharge_vip_type_form()\'>'.__('立即下载','jinsom').'</a>';
}
}else if($post_type=='login_see'){//登录可见
if(!is_user_logged_in()){
$tips='你没有权限，需要登录才可以进行下载';
$pass='';
$download_btn='<a class="btn open-login-screen">'.__('立即下载','jinsom').'</a>';
}
}
?>
<div class="jinsom-bbs-download-box jinsom-bbs-download-box-<?php echo $post_id;?>">
<?php if($tips){echo '<div class="tips">'.$tips.'</div>';}?>
<?php echo $download_btn;?>
<?php echo $pass;?>
</div>
<?php }?>

<!-- 话题 -->
<?php require( get_template_directory() . '/mobile/templates/post/topic-list.php' );?>

<?php 
if($copyright){
echo '<div class="jinsom-single-copyright-info">'.do_shortcode($copyright).'</div>';
}


jinsom_single_content_end_hook();//自定义内容结束钩子


require( get_template_directory() . '/mobile/templates/post/bar.php' );

jinsom_mobile_post_like_list($post_id);//喜欢列表

?>

</div>


<div class="jinsom-single-bbs-info">
<div class="title clear">
<div class="left">所属<?php echo jinsom_get_option('jinsom_bbs_name');?></div>
<div class="right"><?php echo jinsom_bbs_like_btn($user_id,$bbs_id);?></div>
</div>
<div class="content">
<a class="link" href="#" onclick="jinsom_is_page_repeat('bbs','<?php echo jinsom_mobile_bbs_url($child_cat_id);?>')">
<div class="avatarimg"><?php echo jinsom_get_bbs_avatar($child_cat_id,0);?></div>
<div class="info">
<div class="name"><?php echo get_category($child_cat_id)->name;?></div>
<div class="number">
<span><?php echo jinsom_get_bbs_like_number($bbs_id);?><?php _e('成员','jinsom');?></span>
<span><?php echo jinsom_get_bbs_post($child_cat_id);?><?php _e('内容','jinsom');?></span>
</div>
</div>
<div class="go">进入<?php echo jinsom_get_option('jinsom_bbs_name');?></div>
</a>
</div>
<div class="desc"><?php echo get_term_meta($child_cat_id,'desc',true);?></div>
</div>

<?php 
if($post_status!='pending'&&$post_status!='draft'&&$bbs_type!='download'){
require( get_template_directory().'/mobile/templates/post/comment.php');
}
?>


</div>

<?php }else{ 
$publish_bbs_id=$bbs_id;
require( get_template_directory() . '/mobile/templates/post/page-no-power.php' );}?>


</div>        