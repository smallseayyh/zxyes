<?php
global $current_user;
$user_id=$current_user->ID;
$jinsom_sidebar_toolbar_sorter = jinsom_get_option('jinsom_sidebar_toolbar_sorter_e');

//右侧工具栏
$siderbar_toolbar_enabled=$jinsom_sidebar_toolbar_sorter['enabled'];
if($siderbar_toolbar_enabled){
echo '<div class="jinsom-right-bar">';
foreach($siderbar_toolbar_enabled as $x=>$x_value) {
switch($x){
case 'sort': //排序
if(is_home()){
if(isset($_COOKIE["sort"])&&$_COOKIE["sort"]=='rand'){
echo '<li class="sort sort-rand"><span class="title">'.__('随机排序','jinsom').'</span><i class="jinsom-icon jinsom-suijibofang"></i></li>'; 
}else if(isset($_COOKIE["sort"])&&$_COOKIE["sort"]=='comment_count'){
echo '<li class="sort sort-hot"><span class="title">'.__('热门查看','jinsom').'</span><i class="jinsom-icon jinsom-huo"></i></li>'; 
}else{
echo '<li class="sort sort-normal"><span class="title">'.__('顺序查看','jinsom').'</span><i class="jinsom-icon jinsom-shunxu-"></i></li>'; 
}
}
break;
case 'publish': //发布
$jinsom_publish_add=jinsom_get_option('jinsom_publish_add');
if($jinsom_publish_add){
if(count($jinsom_publish_add)==1){
echo '<li class="'.$x.'" onclick=\'jinsom_publish_power("'.$jinsom_publish_add[0]['jinsom_publish_add_type'].'","")\'><span class="title">'.__('发表内容','jinsom').'</span><i class="jinsom-icon jinsom-fabiao1"></i></li>';
}else{
echo '<li class="'.$x.'" onclick="jinsom_publish_type_form();"><span class="title">'.__('发表内容','jinsom').'</span><i class="jinsom-icon jinsom-fabiao1"></i></li>';	
}
}
break;
case 'chat':  //聊天  
if(is_user_logged_in()){
$jinsom_chat_notice=jinsom_get_my_all_unread_msg();
$system_notice=jinsom_site_notice_number($user_id);
$jinsom_chat_notice=$jinsom_chat_notice+$system_notice;
if($jinsom_chat_notice){$jinsom_chat_notice='<span class="number">'.$jinsom_chat_notice.'</span>';}else{$jinsom_chat_notice='';}
echo '<li class="jinsom-right-bar-im"><span class="title">'.__('聊天','jinsom').'</span>'.$jinsom_chat_notice.'<i class="jinsom-icon jinsom-liaotian"></i></li>';
}
break; 
case 'setting'://偏好设置    
if(!is_author()&&!is_tax()){
echo '<li class="'.$x.'" onclick="jinsom_preference_setting();"><span class="title">'.__('偏好设置','jinsom').'</span><i class="jinsom-icon jinsom-huanfu"></i></li>';
}

break;
case 'top':    //返回顶部
echo '<li class="totop" style="display:none;"><span class="title">'.__('返回顶部','jinsom').'</span><i class="jinsom-icon jinsom-totop"></i></li>'; 
break;
case 'bottom': //到底部
echo '<li class="tobottom"><span class="title">'.__('到底部','jinsom').'</span><i class="jinsom-icon jinsom-xiangxia2"></i></li>'; 
break;
case 'task': //任务
if(is_user_logged_in()){
echo '<li class="'.$x.'" onclick=\'jinsom_task_form()\'><span class="title">'.__('做任务','jinsom').'</span><i class="jinsom-icon jinsom-renwu1"></i></li>'; 
}else{
echo '<li class="'.$x.'" onclick=\'jinsom_pop_login_style()\'><span class="title">'.__('做任务','jinsom').'</span><i class="jinsom-icon jinsom-renwu1"></i></li>'; 	
}
break;
case 'now': //实时动态
echo '<li class="'.$x.'" onclick=\'jinsom_open_now()\'><span class="title">'.__('实时动态','jinsom').'</span><i class="jinsom-icon jinsom-shandianpeisong"></i></li>'; 
break;
case 'search': //搜索
echo '<li class="'.$x.'"><span class="title">'.__('搜索','jinsom').'</span><i class="jinsom-icon jinsom-sousuo1"></i></li>'; 
break;
case 'language': //多语言
echo '<li class="'.$x.'"><i class="jinsom-icon jinsom-yuyan1"></i>';
$jinsom_languages_add=jinsom_get_option('jinsom_languages_add');
if($jinsom_languages_add){
echo '<ul>';
foreach ($jinsom_languages_add as $data) {
if(get_locale()==$data['code']){$on='class="on"';}else{$on='';}
echo '<li '.$on.' onclick=\'jinsom_change_language(this,"'.$data['code'].'")\'>'.$data['name'].'</li>';
}
echo '</ul>';
}
echo '</li>'; 
break;
case 'custom_a': //自定义1
echo jinsom_get_option('jinsom_sidebar_toolbar_sorter_custom_a'); 
break;
case 'custom_b': //自定义2
echo jinsom_get_option('jinsom_sidebar_toolbar_sorter_custom_b'); 
break;
case 'custom_c': //自定义3
echo jinsom_get_option('jinsom_sidebar_toolbar_sorter_custom_c'); 
break;
case 'notice': //消息提醒
$follow_tips='';
$like_tips='';
$reg_mail='';
$all_tips='';
if(jinsom_get_follow_tips_number()>0){
$follow_tips='<span class="tip"></span>';
}
if(jinsom_get_like_tips_number()>0){
$like_tips='<span class="tip"></span>';
}
if(jinsom_get_option('jinsom_email_style')!='close'&&jinsom_get_option('jinsom_mail_notice_on_off')){
$reg_mail='<span onclick="jinsom_emali_notice_form()"><i class="jinsom-icon jinsom-shezhi"></i> '.__('提醒设置','jinsom').'</span>';
}
$jinsom_get_all_tips_number=jinsom_get_all_tips_number();
if($jinsom_get_all_tips_number){$all_tips='<span class="number">'.$jinsom_get_all_tips_number.'</span>';}
echo '<li class="jinsom-notice"><span class="title"></span>'.$all_tips.'<i class="jinsom-icon jinsom-tongzhi1"></i>
<ul>
<div class="jinsom-notice-title clear">
<li class="on">
<i class="jinsom-icon jinsom-caidan"></i>
</li>
<li>
'.$follow_tips.'
<i class="jinsom-icon jinsom-guanzhu3"></i>
</li>
<li>
'.$like_tips.'
<i class="jinsom-icon jinsom-guanzhu4"></i>
</li>
</div>
<div class="jinsom-notice-content">
<div class="a"></div>
<div class="b"></div>
<div class="c"></div>
</div>
<div class="jinsom-notice-bottom clear">
'.$reg_mail.'
<span onclick="jinsom_delete_notice();">'.__('全部清空','jinsom').'</span>
</div>
</ul>
</li>'; 
break;
}}

echo '</div>';
}
?>


<!-- 偏好设置 -->
<?php if(!is_author()&&$siderbar_toolbar_enabled&&array_key_exists('setting',$siderbar_toolbar_enabled)){?>
<div class="jinsom-preference-setting">
<div class="jinsom-preference-header">
<div class="jinsom-preference-content clear">
<?php
$post_style = jinsom_get_option('jinsom_post_list_type');
$layout_style = jinsom_get_option('jinsom_index_default_style');
$space_style = jinsom_get_option('jinsom_post_space_default_style');
$sidebar_style = jinsom_get_option('jinsom_sidebar_style');
?>

<?php if(!is_category()&&!(is_single()&&is_bbs_post(get_the_ID()))&&!is_page()){?>
<span class="toggle single-column">
<?php 
echo __('单栏布局','jinsom');
if(empty($_COOKIE["layout-style"])){
if($layout_style=='layout-single'){
echo '<i class="fa fa-toggle-on"></i>';
}else{
echo '<i class="fa fa-toggle-off"></i>';
}
}else{
if($_COOKIE["layout-style"]=='layout-single.css'){
echo '<i class="fa fa-toggle-on"></i>';
}else{
echo '<i class="fa fa-toggle-off"></i>';
}
}
?>
</span>
<?php }?>

<?php if(is_bbs_post(get_the_ID())&&is_single()){?>
<span class="toggle post-space">
<?php 
echo __('帖子间隔','jinsom');
if(empty($_COOKIE["space-style"])){
if($space_style=='bbs-post-space-on'){
echo '<i class="fa fa-toggle-on"></i>';
}else{
echo '<i class="fa fa-toggle-off"></i>';
}
}else{
if($_COOKIE["space-style"]=='bbs-post-space-on.css'){
echo '<i class="fa fa-toggle-on"></i>';
}else{
echo '<i class="fa fa-toggle-off"></i>';
}
}
?>
</span>
<?php }?>
<?php if(is_home()||is_author()||is_tag()||is_search()){?>
<span class="toggle post-style">
<?php 
if(empty($_COOKIE["post-style"])){
if($post_style=='post-style-block'){
$a=__('矩状','jinsom');
}else{
$a=__('时光轴','jinsom');
}
echo __('列表样式','jinsom').'：<n>'.$a.'</n>';
}else{
if($_COOKIE["post-style"]=='post-style-block.css'){
$b=__('矩状','jinsom');
}else{
$b=__('时光轴','jinsom');
}
echo __('列表样式','jinsom').'：<n>'.$b.'</n>';
}

?>
</span>
<?php }?>

<span class="toggle sidebar-style">
<?php 
if(empty($_COOKIE["sidebar-style"])){
if($sidebar_style=='sidebar-style-right.css'){
$a=__('右','jinsom');
}else{
$a=__('左','jinsom');
}
echo __('侧栏位置','jinsom').'：<n>'.$a.'</n>';
}else{
if($_COOKIE["sidebar-style"]=='sidebar-style-left.css'){
$a=__('左','jinsom');
}else{
$a=__('右','jinsom');
}
echo __('侧栏位置','jinsom').'：<n>'.$a.'</n>';
}
?>
</span>

<span class="close" onclick="jinsom_preference_setting()"><i class="jinsom-icon jinsom-guanbi"></i></span>
</div>
 </div>
<div class="jinsom-preference-list clear"></div>
</div>
<?php }?>


<!-- 底部 -->
<?php 
if(!is_author()){
if(get_option('LightSNS_Module_pc/footer')){
require($_SERVER['DOCUMENT_ROOT'].'/wp-content/module/pc/footer/index.php');
}else{
$jinsom_footer_type = jinsom_get_option('jinsom_footer_type');
$jinsom_footer_tab = jinsom_get_option('jinsom_footer_tab');
if($jinsom_footer_tab){
if($jinsom_footer_type=='default'){//默认
$jinsom_footer_sorter = jinsom_get_option('jinsom_footer_sorter');//底部模块
$jinsom_footer_bg = jinsom_get_option('jinsom_footer_bg');//背景颜色
?>
<div class="jinsom-footer" style="background-color: <?php echo $jinsom_footer_bg;?>">
<?php 
if(!empty($jinsom_footer_sorter)){
$enabled=$jinsom_footer_sorter['enabled'];
if($enabled){
echo '<div class="jinsom-footer-top clear">';
foreach($enabled as $x=>$x_value) {
switch($x){
case 'logo': 
$jinsom_footer_logo = $jinsom_footer_tab['jinsom_footer_logo'];
if(empty($jinsom_footer_logo)){$jinsom_footer_logo=get_template_directory_uri().'/images/logo.png';}
echo '
<li class="logo">
<img src="'.$jinsom_footer_logo.'">
<div>'.$jinsom_footer_tab['jinsom_footer_logo_text'].'</div>
</li>';  
break;
case 'menu1': 
$jinsom_footer_menu1_add = $jinsom_footer_tab['jinsom_footer_menu1_add'];
echo '<li><div class="title">'.$jinsom_footer_tab['jinsom_footer_menu1_title'].'</div><ul>';
if($jinsom_footer_menu1_add){
foreach ($jinsom_footer_menu1_add as $data) {
$target=$data['target'];
$nofollow=$data['nofollow'];
if($target){$target='target="_blank"';}else{$target='';}
if($nofollow){$nofollow='rel="nofollow"';}else{$nofollow='';}
echo '<li><a href="'.$data['link'].'" '.$target.' '.$nofollow.'>'.$data['title'].'</a></li>';
}
}
echo '</ul></li>';  
break;
case 'menu2': 
$jinsom_footer_menu2_add = $jinsom_footer_tab['jinsom_footer_menu2_add'];
echo '<li><div class="title">'.$jinsom_footer_tab['jinsom_footer_menu2_title'].'</div><ul>';
if($jinsom_footer_menu2_add){
foreach ($jinsom_footer_menu2_add as $data) {
$target=$data['target'];
$nofollow=$data['nofollow'];
if($target){$target='target="_blank"';}else{$target='';}
if($nofollow){$nofollow='rel="nofollow"';}else{$nofollow='';}
echo '<li><a href="'.$data['link'].'" '.$target.' '.$nofollow.'>'.$data['title'].'</a></li>';
}
}
echo '</ul></li>';  
break;
case 'menu3': 
$jinsom_footer_menu3_add = $jinsom_footer_tab['jinsom_footer_menu3_add'];
echo '<li><div class="title">'.$jinsom_footer_tab['jinsom_footer_menu3_title'].'</div><ul>';
if($jinsom_footer_menu3_add){
foreach ($jinsom_footer_menu3_add as $data) {
$target=$data['target'];
$nofollow=$data['nofollow'];
if($target){$target='target="_blank"';}else{$target='';}
if($nofollow){$nofollow='rel="nofollow"';}else{$nofollow='';}
echo '<li><a href="'.$data['link'].'" '.$target.' '.$nofollow.'>'.$data['title'].'</a></li>';
}
}
echo '</ul></li>';  
break;
case 'code1':    
$jinsom_footer_code1_title = $jinsom_footer_tab['jinsom_footer_code1_title'];
$jinsom_footer_code1 = $jinsom_footer_tab['jinsom_footer_code1'];
if(empty($jinsom_footer_code1)){$jinsom_footer_code1=get_template_directory_uri().'/images/logo.png';}
echo '
<li>
<div class="title">'.$jinsom_footer_code1_title.'</div>
<div class="code"><img src="'.$jinsom_footer_code1.'" alt="'.$jinsom_footer_code1_title.'"></div>
</li>
';  
break;
case 'code2':    
$jinsom_footer_code2_title = $jinsom_footer_tab['jinsom_footer_code2_title'];
$jinsom_footer_code2 = $jinsom_footer_tab['jinsom_footer_code2'];
if(empty($jinsom_footer_code2)){$jinsom_footer_code2=get_template_directory_uri().'/images/logo.png';}
echo '
<li>
<div class="title">'.$jinsom_footer_code2_title.'</div>
<div class="code"><img src="'.$jinsom_footer_code2.'" alt="'.$jinsom_footer_code2_title.'"></div>
</li>
';  
break;
}}
echo '</div>';
}
}
//底部版权
$jinsom_footer_bottom_text = jinsom_get_option('jinsom_footer_bottom_text');
if(!empty($jinsom_footer_bottom_text)){
echo '<div class="jinsom-footer-bottom">'.do_shortcode($jinsom_footer_bottom_text).'</div>';
}
?>
</div>
<?php }else{//自定义底部
echo do_shortcode(jinsom_get_option('jinsom_footer_custom_text'));
}?>
<?php }?>

<?php }//个人中心不显示底部?>
<?php }//开发者地址?>



<!-- 弹窗搜索 -->
<?php 
$jinsom_mobile_search_post_hot_on_off = jinsom_get_option('jinsom_mobile_search_post_hot_on_off');
$jinsom_mobile_search_post_hot_title = jinsom_get_option('jinsom_mobile_search_post_hot_title');
$jinsom_mobile_search_post_hot_add = jinsom_get_option('jinsom_mobile_search_post_hot_add');
?>
<div class="jinsom-pop-search <?php echo get_user_meta(1,'v',true);?>">
<i class="jinsom-icon close jinsom-guanbi"></i>
<div class="jinsom-pop-search-container">
<div class="jinsom-pop-search-content">
<input type="text" placeholder="<?php _e('搜索你感兴趣的内容','jinsom');?>">
<span class="opacity jinsom-sousuo1 jinsom-icon"></span>
</div>


<?php if($_COOKIE['history-search']){?>
<div class="jinsom-pop-search-hot history" style="margin-bottom:20px;">
<p><i style="font-size: 16px;" class="jinsom-icon jinsom-lishi"></i> 历史搜索
<span class="right"><i class="jinsom-icon jinsom-shanchu" onclick="jinsom_history_search_clear()"></i></span>
</p> 
<div class="jinsom-pop-search-hot-list">
<?php 
$history_search_arr=explode(",",$_COOKIE['history-search']);
foreach (array_reverse($history_search_arr) as $data) {
echo '<a href="/?s='.$data.'">'.$data.'</a>';
}
?>
</div>
</div>
<?php }?>

<?php if($jinsom_mobile_search_post_hot_on_off){?>
<div class="jinsom-pop-search-hot">
<p><?php echo $jinsom_mobile_search_post_hot_title;?></p> 
<div class="jinsom-pop-search-hot-list">
<?php 
if($jinsom_mobile_search_post_hot_add){
foreach ($jinsom_mobile_search_post_hot_add as $hot) {
echo '<a href="/?s='.$hot['title'].'">'.$hot['title'].'</a>';
}
}
?>
</div>
</div>
<?php }?>

<?php if(jinsom_get_option('jinsom_mobile_search_hot_bbs_on_off')){
$jinsom_mobile_search_hot_bbs_list=jinsom_get_option('jinsom_mobile_search_hot_bbs_list');
$hot_bbs_arr=explode(",",jinsom_get_option('jinsom_mobile_search_hot_bbs_list'));
?>
<div class="jinsom-pop-search-bbs">
<div class="title"><?php echo jinsom_get_option('jinsom_mobile_search_hot_bbs_title');?></div>
<div class="list clear">
<?php 
if($jinsom_mobile_search_hot_bbs_list){
foreach ($hot_bbs_arr as $data) {
echo '<li><a href="'.get_category_link($data).'" target="_blank">'.jinsom_get_bbs_avatar($data,0).'<p>'.get_category($data)->name.'</p></a></li>';
}
}
?>
</div>
</div>
<?php }?>

<?php if(jinsom_get_option('jinsom_mobile_search_hot_topic_on_off')){
$jinsom_mobile_search_hot_topic_list=jinsom_get_option('jinsom_mobile_search_hot_topic_list');
$hot_topic_arr=explode(",",jinsom_get_option('jinsom_mobile_search_hot_topic_list'));
?>
<div class="jinsom-pop-search-topic">
<div class="title"><?php echo jinsom_get_option('jinsom_mobile_search_hot_topic_title');?></div>
<div class="list clear">
<?php 
if($jinsom_mobile_search_hot_topic_list){
foreach ($hot_topic_arr as $data) {
$topic_data=get_term_by('id',$data,'post_tag');
echo '
<li>
<a href="'.get_tag_link($data).'"  target="_blank">
<div class="shadow"></div>
<img src="'.jinsom_topic_bg($data).'">
<p>#'.$topic_data->name.'#</p>
</a>
</li>';
}
}
?>
</div>
</div>
<?php }?>


</div> 
</div> 



<?php wp_footer();?>
<script>
<?php if(is_single()){?>
SyntaxHighlighter.all();//代码高亮
<?php }?>
</script>
<!-- 自定义js文件 -->
<?php echo jinsom_get_option('jinsom_footer_custom_code');?>


<?php
//引人IM
if(is_user_logged_in()){
require( get_template_directory() . '/module/stencil/chat.php' );//IM模块
}

//更新在线状态
jinsom_upadte_user_online_time();
;?>

<?php if(jinsom_get_option('jinsom_im_music')){?>
<audio id="jinsom-im-music" style="display: none;"><source src="<?php echo jinsom_get_option('jinsom_im_music');?>"></audio>
<?php }?>

<div style="display: none;">
<?php 
$statistics_type=jinsom_get_option('jinsom_statistics_type');
$cnzz_id=jinsom_get_option('jinsom_statistics_cnzz_id');
if($statistics_type=='cnzz'){?>
<script type="text/javascript" src="https://s95.cnzz.com/z_stat.php?id=<?php echo $cnzz_id;?>&web_id=<?php echo $cnzz_id;?>"></script>
<?php }else if($statistics_type=='baidu'){?>
<script>var _hmt = _hmt || [];(function() {var hm = document.createElement("script");hm.src = "https://hm.baidu.com/hm.js?<?php echo jinsom_get_option('jinsom_statistics_baidu_id');?>";var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(hm, s);})();
</script>
<?php }?>
</div>

<div class="jinsom-bottom"></div>
<?php if(jinsom_get_option('jinsom_instantclick_on_off')&&(!jinsom_get_option('jinsom_login_on_off')||jinsom_get_option('jinsom_login_on_off')&&is_user_logged_in())){?>
<script type="text/javascript" src="https://cdn.jsdelivr.net/gh/jinsom/LightSNS-CDN@1.6.41.25/assets/js/instantclick.min.js"></script>
<script data-no-instant>InstantClick.init();</script>
<?php }?>


<div class="jinsom-now">
<div class="refresh" title="<?php _e('刷新','jinsom');?>" onclick="jinsom_refresh_now()"><i class="jinsom-icon jinsom-shuaxin"></i></div>
<div class="close" title="<?php _e('关闭','jinsom');?>" onclick="jinsom_close_now()"><i class="jinsom-icon jinsom-bangzhujinru"></i></div>
<div class="jinsom-now-content" page="2">
</div>
</div>

<?php jinsom_body_end_hook();?>

<!-- <?php echo get_num_queries().__('查询','jinsom'); ?>-<?php timer_stop(7);echo __('秒','jinsom'); ?> -->
</body>
</html>