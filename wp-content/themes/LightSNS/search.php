<?php 
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');
}else{

$user_id=$current_user->ID;
$require_url=get_template_directory();
$keywords=get_search_query();

//历史搜索
if(isset($_COOKIE['history-search'])){
$history_search=$_COOKIE['history-search'];
$history_search_arr=explode(",",$history_search);

if(in_array($keywords,$history_search_arr)){
$key=array_search($keywords,$history_search_arr);
array_splice($history_search_arr,$key,1);
}else{
if(count($history_search_arr)>10){
array_pop($history_search_arr);
}
}
array_push($history_search_arr,$keywords);
setcookie("history-search",implode(",",$history_search_arr),time()+3600*24*30*12,'/');	
}else{
setcookie("history-search",$keywords,time()+3600*24*30*12,'/');	
}



get_header();

if(jinsom_get_option('jinsom_search_login_on_off')&&!is_user_logged_in()){
echo '<div class="jinsom-login-search-tips">'.__('请登录之后再进行搜索！','jinsom').'</div>';
}else{


?>
<!-- 主内容 -->
<div class="jinsom-main-content search clear">
<?php require($require_url.'/sidebar/sidebar-search.php');?>
<div class="jinsom-content-left"><!-- 左侧 -->

<div class="jinsom-search-main">
<div class="jinsom-search-header">
<input placeholder="<?php _e('搜索你感兴趣的内容','jinsom');?>" value="<?php echo $keywords;?>" id="jinsom-search-val">
<span class="opacity jinsom-sousuo1 jinsom-icon"></span>
</div>	

<?php 
$jinsom_search_menu = jinsom_get_option('jinsom_search_menu_a');
$enabled=$jinsom_search_menu['enabled'];
if($enabled){
echo '<div class="jinsom-search-tab">';
echo '<li class="on" onclick=\'jinsom_ajax_search("all",this)\'>'.__('全部','jinsom').'</li>'; 
foreach($enabled as $x=>$x_value) {
switch($x){
case 'user': 
echo '<li onclick=\'jinsom_ajax_search("user",this)\'>'.__('用户','jinsom').'</li>';    
break;
case 'bbs':    
echo '<li onclick=\'jinsom_ajax_search("bbs",this)\'>'.__('帖子','jinsom').'</li>'; 
break; 
case 'words':    
echo '<li onclick=\'jinsom_ajax_search("words",this)\'>'.__('动态','jinsom').'</li>'; 
break;
case 'music':    
echo '<li onclick=\'jinsom_ajax_search("music",this)\'>'.__('音乐','jinsom').'</li>'; 
break;
case 'single': 
echo '<li onclick=\'jinsom_ajax_search("single",this)\'>'.__('文章','jinsom').'</li>'; 
break;
case 'video': 
echo '<li onclick=\'jinsom_ajax_search("video",this)\'>'.__('视频','jinsom').'</li>'; 
break;
case 'forum':    
echo '<li onclick=\'jinsom_ajax_search("forum",this)\'>'.jinsom_get_option('jinsom_bbs_name').'</li>'; 
break; 
case 'topic':    
echo '<li onclick=\'jinsom_ajax_search("topic",this)\'>'.jinsom_get_option('jinsom_topic_name').'</li>'; 
break; 
}}

echo '</div>';
}
?>





<div class="jinsom-search-content">


<?php 
//相关用户
if(!empty($keywords)){//不为空
$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'count_total'=>false,
'meta_value' => $keywords,//搜昵称
'meta_compare' =>'LIKE',
'orderby' => 'ID',
'order' =>'ASC',
'number'  => 12
));
if (!empty($user_query->results)){
?>
<div class="jinsom-search-user-list clear">
<h1><?php _e('用户','jinsom');?></h1>

<?php foreach ($user_query->results as $user){?>
<li class="clear">
<div class="avatarimg">
<a href="<?php echo jinsom_userlink($user->ID);?>" target="_blank">
<?php echo jinsom_vip_icon($user->ID).jinsom_avatar($user->ID,'60', avatar_type($user->ID)).jinsom_verify($user->ID);?>
</a>
</div>
<div class="info">
<p><a href="<?php echo jinsom_userlink($user->ID);?>" target="_blank"><?php echo jinsom_nickname($user->ID);?></a></p>
<p><span><?php _e('关注','jinsom');?>：<m><?php echo jinsom_following_count($user->ID); ?></m></span>
<span><?php _e('粉丝','jinsom');?>：<m><?php echo jinsom_follower_count($user->ID); ?></m></span>
</p>
</div>
</li>
<?php }?>

</div>
<?php }}?>

<?php 
if($keywords!=''){
$bbs_args=array(
'number'=>8,
'taxonomy'=>'category',//论坛
'search'=>$keywords,
'hide_empty'=>false,
'exclude' =>jinsom_get_option('jinsom_search_bbs_hide'),
);
$bbs_args['no_found_rows']=true;
$bbs_arr=get_terms($bbs_args);
if($bbs_arr){	

echo '<div class="jinsom-bbs-cat-list clear">';
echo '<h1>'.jinsom_get_option('jinsom_bbs_name').'</h1><div class="content">';
foreach ($bbs_arr as $bbs){
$bbs_id=$bbs->term_id;
echo '
<li>
<div class="left">
<a href="'.get_category_link($bbs_id).'">
'.jinsom_get_bbs_avatar($bbs_id,0).'
</a>
</div>
<div class="right">
<div class="name"><a href="'.get_category_link($bbs_id).'">'.$bbs->name.'<span>'.jinsom_get_bbs_post($bbs_id).'</span></a></div>
<div class="desc">'.get_term_meta($bbs_id,'desc',true).'</div>
</div>
</li>';
}
echo '</div></div>';
}
}
?>

<?php 
if($keywords!=''){
$topic_args=array(
'number'=>12,
'taxonomy'=>'post_tag',//话题
'search'=>$keywords,
'hide_empty'=>false,
'orderby' =>'count',
'order' =>'DESC'
);
$topic_args['no_found_rows']=true;
$topic_arr=get_terms($topic_args);
if($topic_arr){	
echo '<div class="jinsom-single-topic-list clear">';
echo '<h1>'.jinsom_get_option('jinsom_topic_name').'</h1>';
foreach ($topic_arr as $topic){
$topic_id=$topic->term_id;
echo '<a href="'.get_tag_link($topic_id).'" title="'.$topic->name.'" class="opacity">'.jinsom_get_bbs_avatar($topic_id,1).'<span>'.$topic->name.'</span></a>';
}
echo '</div>';
}
}
?>



<?php 
$paged = max( 1, get_query_var('page') );
$number = get_option('posts_per_page', 10);
$offset = ($paged-1)*$number;
$args = array(
'post_status' =>'publish',
'post_type' => 'post',
'showposts' => $number,
's' => $keywords,
'category__not_in'=>jinsom_get_option('jinsom_search_bbs_hide')
);
if(!empty($keywords)){
$args['no_found_rows']=true;
query_posts($args);
if (have_posts()) {
while ( have_posts() ) : the_post();
require($require_url.'/post/post-list.php');	
endwhile;
echo '<div class="jinsom-more-posts" data="2" type="all" onclick="jinsom_more_search(this);">'.__('加载更多','jinsom').'</div>';	
}else{
echo jinsom_empty();	
}

}else{
echo jinsom_empty();	
}





//记录搜索词
if($keywords){
if(!current_user_can('level_10')){
global $wpdb;
$table_name=$wpdb->prefix.'jin_search_note';
$ip = $_SERVER['REMOTE_ADDR'];
$time=current_time('mysql');
$keywords=htmlspecialchars(strip_tags($keywords));
if($keywords){
$search_name=__('全站搜索','jinsom');
$wpdb->query( "INSERT INTO $table_name (content,user_id,type,ip,search_time) VALUES ('$keywords','$user_id','$search_name','$ip','$time')");
}
}
}
?>
</div>
</div>


</div><!-- 左侧结束 -->

</div>

<script type="text/javascript">
//提交搜索
$(".jinsom-search-header span").click(function(){
search_val =$.trim($(".jinsom-search-header input").val());
if(search_val==''){
layer.msg('<?php _e('搜索的内容不能为空！','jinsom');?>');
return false;
}
window.location.href=jinsom.home_url+'/?s='+search_val;
});

// 回车搜索
$(".jinsom-search-header input").keypress(function(e) {  
if(e.which == 13) {  
search_val =$.trim($(".jinsom-search-header input").val());
if(search_val==''){
layer.msg('<?php _e('搜索的内容不能为空！','jinsom');?>');
return false;
}
window.location.href=jinsom.home_url+'/?s='+search_val;
}  
}); 
</script>
<?php 
}//登录搜索
get_footer();?>
<?php }?>
