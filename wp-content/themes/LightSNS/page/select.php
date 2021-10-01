<?php
/*
Template Name:筛选页面
*/

if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');  	
}else{
get_header();
$post_id=get_the_ID();
$select_option=get_post_meta($post_id,'page_select_option',true);
if(!$select_option){
echo jinsom_empty('请在新建页面的时候配置当前页面的数据！');exit();
}
$select_type=$select_option['jinsom_page_select_type'];
$menu_on_off=$select_option['jinsom_page_select_menu_on_off'];
$color=$select_option['jinsom_page_select_color'];
$line_number=$select_option['jinsom_page_select_line_number'];
$list_gutter=$select_option['jinsom_page_select_list_gutter'];
$list_style=$select_option['jinsom_page_select_list_style'];
$waterfall_on_off=$select_option['jinsom_page_select_waterfall_on_off'];
$bg_height=$select_option['jinsom_page_select_list_bg_height'];
$bbs_add=$select_option['jinsom_page_select_bbs_add'];
$topic_add=$select_option['jinsom_page_select_topic_add'];
$field_add=$select_option['jinsom_page_select_field_add'];
$sort_add=$select_option['jinsom_page_select_sort_add'];
if($select_type=='bbs'){
$power_add=$select_option['jinsom_page_select_power_add'];
}else{
$power_add=$select_option['jinsom_page_select_words_power_add'];    
}

$num=($line_number-1)*$list_gutter;
?>
<style type="text/css">
.jinsom-page-select-header-box .bbs li.on, .jinsom-page-select-header-box .bbs li:hover,.jinsom-page-select-menu a:hover {
    color:<?php echo $color;?>;
}
.jinsom-page-select-sort li.on, .jinsom-page-select-sort li:hover {
    border: 1px solid <?php echo $color;?>;
    color: <?php echo $color;?>;
}
.jinsom-page-select-header-box .topic li.on, .jinsom-page-select-header-box .topic li:hover {
    background-color:<?php echo $color;?>;
}
.jinsom-page-select-post-list li {
    width: calc((100% - <?php echo $num;?>px)/<?php echo $line_number;?>);
    margin-right: <?php echo $list_gutter;?>px;
    margin-bottom: <?php echo $list_gutter;?>px;
}
<?php if($line_number!=4){?>
.jinsom-page-select-post-list li:nth-child(4n) {
    margin-right: <?php echo $list_gutter;?>px;
}
<?php }?>
.jinsom-page-select-post-list li:nth-child(<?php echo $line_number;?>n) {
    margin-right: 0;
}

<?php if(!$waterfall_on_off){?>
.jinsom-page-select-post-list li .bg {
    height: <?php echo $bg_height;?>px;
}
<?php }else{?>
.jinsom-page-select-post-list li{
    margin-right: 0;
}
.jinsom-page-select-post-list li .bg {
    height: auto;
}
.jinsom-page-select-post-list .jinsom-load-post {
    position: relative;
    margin-top: 80px;
}
.jinsom-page-select-post-list .jinsom-loading-post {
    position: absolute;
    z-index: 1;
    top: -60px;
    left: 50%;
    margin-left: -40px;
}
.jinsom-page-select-post-list .jinsom-more-posts {
    bottom: 0;
    position: absolute;
    left: 50%;
    margin-left: -60px;
}
<?php }?>

</style>


<div class="jinsom-main-content jinsom-page-select-content jinsom-page-select-content-<?php echo $post_id;?>">

<?php echo do_shortcode($select_option['jinsom_page_select_header_html']);?>

<?php if($menu_on_off){?>
<div class="jinsom-page-select-menu"><a href="<?php echo home_url();?>"><?php echo jinsom_get_option('jinsom_site_name');?></a> > <a href="<?php echo the_permalink();?>"><?php the_title();?></a></div>
<?php }?>

<div class="jinsom-page-select-header-box">

<?php if(current_user_can('level_10')){?>
<div class="admin" onclick="jinsom_post_link(this)" data="/wp-admin/post.php?post=<?php echo $post_id;?>&action=edit"><i class="jinsom-icon jinsom-shezhi"></i></div>
<?php }?>

<?php 
if($select_type=='bbs'&&$bbs_add){
$bbs_i=0;
$bbs_id=strip_tags($_GET['bbs_id']);
echo '<div class="bbs">';
foreach ($bbs_add as $data) {
if((!$bbs_id&&$bbs_i==0)||($bbs_id==$data['bbs_id'])){
$on='on';
}else{
$on='';
}
echo '<li class="'.$on.'" data="'.$data['bbs_id'].'">'.$data['name'].'</li>';
$bbs_i++;
}
echo '</div>';

//隐藏的板块话题
echo '<div class="bbs-topic-hidden">';
foreach ($bbs_add as $data) {
if($data['jinsom_page_select_bbs_topic_add']){
echo '<yyy>';
foreach ($data['jinsom_page_select_bbs_topic_add'] as $data_) {
echo '
<div class="topic topic-select bbs-topic clear">
<label>'.$data_['name'].'：</label>
<ul>
<li class="on" data="all">全部</li>';
if($data_['bbs_topic_add']){
foreach ($data_['bbs_topic_add'] as $topic) {
echo '<li data="'.$topic['topic_id'].'">'.$topic['topic_name'].'</li>';
}
}
echo '</ul></div>';
}
echo '</yyy>';
}else{
echo '<yyy></yyy>';
}
}
echo '</div>';



}
?>

<div class="topic-list">
<?php 
//筛选板块的不同话题
if($bbs_add[0]&&$bbs_add[0]['jinsom_page_select_bbs_topic_add']){
foreach ($bbs_add[0]['jinsom_page_select_bbs_topic_add'] as $data_) {
echo '
<div class="topic topic-select bbs-topic clear">
<label>'.$data_['name'].'：</label>
<ul>
<li class="on" data="all">全部</li>';
if($data_['bbs_topic_add']){
foreach ($data_['bbs_topic_add'] as $topic) {
echo '<li data="'.$topic['topic_id'].'">'.$topic['topic_name'].'</li>';
}
}
echo '</ul></div>';
}
}


if($topic_add){
$topic_i=0;
$topic_id=strip_tags($_GET['topic_id']);
$topic_id_arr=explode(",",$topic_id);
foreach ($topic_add as $data) {
if($topic_id_arr[$topic_i]=='all'||!$topic_id||(!is_numeric($topic_id_arr[$topic_i])&&!strpos($topic_id_arr[$topic_i],'|'))){
$on='on';
}else{
$on='';
}

echo '
<div class="topic topic-select clear" data="topic_'.$topic_i.'">
<label>'.$data['name'].'：</label>
<ul>
<li class="'.$on.'" data="all">全部</li>';
if($data['topic_add']){
foreach ($data['topic_add'] as $topic) {
if($topic_id_arr[$topic_i]==$topic['topic_id']){
$on='on';
}else{
$on='';
}
echo '<li class="'.$on.'" data="'.$topic['topic_id'].'">'.$topic['topic_name'].'</li>';
}
}
echo '</ul></div>';
$topic_i++;
}
}
?>

<?php 
if($field_add){
$field_i=0;
$field=strip_tags($_GET['field']);
$field_arr=explode(",",$field);
foreach ($field_add as $data) {
if($field_arr[$field_i]=='all'||!$field||!strpos($field_arr[$field_i],'|')){
$on='on';
}else{
$on='';
}

echo '
<div class="topic field-select clear" data="field_'.$field_i.'">
<label>'.$data['name'].'：</label>
<ul>
<li class="'.$on.'" data="all">全部</li>';
if($data['field_add']){
foreach ($data['field_add'] as $fields) {
$field_value=$fields['field_key'].'|'.$fields['relation'].'|'.$fields['field_value'].'|'.$fields['field_value_type'];
if($field_arr[$field_i]==$field_value){
$on='on';
}else{
$on='';
}
echo '<li class="'.$on.'" data="'.$field_value.'">'.$fields['field_name'].'</li>';
}
}
echo '</ul></div>';
$field_i++;
}
}
?>

<?php 
$power=strip_tags($_GET['power']);

if($select_type=='bbs'){
$power_if=$power!='free'&&$power!='pay'&&$power!='vip'&&$power!='comment'&&$power!='login'&&$power!='answer'&&$power!='vote'&&$power!='activity'&&$power!='answer_ok'&&$power!='answer_no';
}else{
$power_if=$power!='free'&&$power!='pay'&&$power!='vip'&&$power!='password'&&$power!='login';
}

if($power_if){
$on='on';
}else{
$on='';
}
if($power_add){
?>
<div class="topic power clear">
<label>类型：</label>
<ul>
<li class="<?php echo $on;?>" data="all">全部</li>
<?php 
foreach ($power_add as $data) {
if($power==$data['power_type']){
$on='on';
}else{
$on='';
}
echo '<li class="'.$on.'" data="'.$data['power_type'].'">'.$data['name'].'</li>';
}
?>
</ul>
</div>
<?php }?>

<?php if(isset($_GET['search'])&&strip_tags($_GET['search'])!=''){?>
<div class="topic search clear">
<label>关键词：</label>
<ul>
<li><?php echo strip_tags($_GET['search']);?><i class="jinsom-icon jinsom-guanbi"></i></li>
</ul>
</div>
<?php }?>

</div>

</div>

<?php 
if($sort_add){
$sort=strip_tags($_GET['sort']);
echo '<div class="jinsom-page-select-sort">';
$i=1;
foreach ($sort_add as $data) {
if($sort==$data['sort_type']){
$on='on';
}else{
if($i==1&&$sort!='new'&&$sort!='comment_count'&&$sort!='views'&&$sort!='rand'&&$sort!='last_comment'&&$sort!='commend'){
$on='on';
}else{
$on='';
}
}
echo '<li class="'.$on.'" data="'.$data['sort_type'].'">'.$data['name'].'</li>';
$i++;
}
echo '</div>';
}
?>

<div class="jinsom-page-select-post-list <?php if($waterfall_on_off){echo 'waterfall';}?> <?php echo $list_style;?> clear" list-style="<?php echo $list_style;?>" post_id="<?php echo $post_id;?>" data-distance="200">

</div>


<script>

//点击论坛板块切换
$('.jinsom-page-select-header-box .bbs li').click(function(){
$('.topic-list .bbs-topic').remove();
$('.jinsom-page-select-header-box .topic-list').prepend($('.bbs-topic-hidden').children('yyy').eq($(this).index()).html());
});


//切换
$('.jinsom-page-select-content').on('click','.jinsom-page-select-header-box li,.jinsom-page-select-sort li',function(event){
$(this).addClass('on').siblings().removeClass('on');
jinsom_page_select_submit_form('load',this);
});
$(".jinsom-page-select-header-box .search li").unbind();
$('.jinsom-page-select-header-box .search li').click(function(){
$(this).parents('.search').remove();
jinsom_page_select_submit_form('load',this);
});

//筛选
function jinsom_page_select_submit_form(type,obj){
url='';

//论坛
if($('.jinsom-page-select-header-box .bbs li.on').length>0){
url+='bbs_id='+$('.jinsom-page-select-header-box .bbs li.on').attr('data');	
}
//话题
if($('.jinsom-page-select-header-box .topic-list .topic-select').length>0){
topic_select_i=0;
topic_str='';
$('.jinsom-page-select-header-box .topic-list .topic-select').each(function(){
topic_id=$(this).find('.on').attr('data');
if(topic_id==undefined){
topic_id='all';
$('.jinsom-page-select-header-box .topic-list .topic-select').eq(topic_select_i).find('[data=all]').addClass('on');
}
topic_str+=topic_id+',';
topic_select_i++;
});
if(topic_str){
topic_str=topic_str.substring(0,topic_str.lastIndexOf(','));
url+='&topic_id='+topic_str;	
}
}

//字段
if($('.jinsom-page-select-header-box .field-select').length>0){
field_select_i=0;
field_str='';
$('.jinsom-page-select-header-box .field-select').each(function(){
field=$(this).find('.on').attr('data');
if(field==undefined){
field='all';
$('.jinsom-page-select-header-box .field-select').eq(field_select_i).find('[data=all]').addClass('on');
}
field_str+=field+',';
field_select_i++;
});
if(field_str){
field_str=field_str.substring(0,field_str.lastIndexOf(','));
url+='&field='+field_str;    
}
}

//权限
if($('.jinsom-page-select-header-box .power li.on').length>0){
url+='&power='+$('.jinsom-page-select-header-box .power li.on').attr('data');	
}
//排序
if($('.jinsom-page-select-sort li.on').length>0){
url+='&sort='+$('.jinsom-page-select-sort li.on').attr('data');	
}

//关键词
if($('.jinsom-page-select-header-box .topic.search').length>0){
url+='&search='+$('.jinsom-page-select-header-box .search.topic li').text(); 
}

//列表布局
list_style=$('.jinsom-page-select-post-list').attr('list-style');

//页面ID
post_id=$('.jinsom-page-select-post-list').attr('post_id');
url+='&post_id='+post_id;	

page=1;
if(type=='more'){
page=parseInt($(obj).attr('page'));
}
url+='&page='+page;

if(type!='more'){
history.pushState('','','?'+url);
$('.jinsom-page-select-post-list').prepend(jinsom.loading_post);
}else{
$(obj).html(jinsom.loading_post);
}

$.ajax({
type:"POST",
url:jinsom.jinsom_ajax_url+"/data/select.php",
data:url,
success: function(msg){

if(type!='more'){
$('.jinsom-page-select-post-list').html(msg);
}else{
// $('.jinsom-load-post').remove();
if(msg==0){
layer.msg('没有更多内容！');
$(obj).remove();
}else{
$(obj).before(msg);
$(obj).text('加载更多');
page=page+1;
$(obj).attr('page',page);
}
}

//渲染瀑布流
<?php if($waterfall_on_off){?>
var grid=$('.jinsom-page-select-post-list').masonry({
itemSelector:'li',
gutter:<?php echo $list_gutter;?>,
});
grid.masonry('reloadItems'); 
grid.imagesLoaded().progress( function() {
grid.masonry('layout');
});    
<?php }?>

}//success


});

}

jinsom_page_select_submit_form('load',this);
</script>





</div>
<?php get_footer();?>
<?php }?>