<?php 
//移动端筛选
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$post_id=$_GET['post_id'];
$search=strip_tags($_GET['search']);
if($search=='false'){$search='';}
$select_option=get_post_meta($post_id,'page_select_option',true);
$select_type=$select_option['jinsom_page_select_type'];
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
?>
<div data-page="select" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="center sliding jinsom-select-navbar-center">
<form id="jinsom-select-search-form" action="">
<i class="jinsom-icon jinsom-sousuo1"></i><input id="jinsom-select-input" class="jinsom-select-input" type="search" placeholder="请输入关键词" value="<?php echo $search;?>">
</form>
</div>
<div class="right" style="margin-left:0;width:13vw;"><a href="#" class="back link icon-only">返回</a></div>
<div class="subnavbar">
<div class="jinsom-select-subnavbar-list">
<?php if($bbs_add&&$select_type=='bbs'){?>
<div class="bbs"><span><?php echo $bbs_add[0]['name'];?></span> <i class="jinsom-icon jinsom-lower-triangle"></i>
<div class="list">
<ul class="clear">
<?php 
$bbs_i=0;
foreach ($bbs_add as $data) {
if($bbs_i==0){
$on='on';
}else{
$on='';
}
echo '<li class="'.$on.'" data="'.$data['bbs_id'].'">'.$data['name'].'</li>';
$bbs_i++;
}
?>
</ul>
</div>
</div>

<?php 
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
?>
<?php }?>
<?php if($sort_add){?>
<div class="sort"><span><?php echo $sort_add[0]['name'];?></span> <i class="jinsom-icon jinsom-lower-triangle"></i>
<div class="list">
<ul class="clear">
<?php 
$sort_i=0;
foreach ($sort_add as $data) {
if($sort_i==0){
$on='on';
}else{
$on='';
}
echo '<li class="'.$on.'" data="'.$data['sort_type'].'">'.$data['name'].'</li>';
$sort_i++;
}
?>
</ul>
</div>
</div>
<?php }?>
<div class="more">筛选 <i class="jinsom-icon jinsom-yousanjiao"></i></div>
</div>
</div>
</div>
</div>

<div class="page-content infinite-scroll jinsom-select-content <?php if($waterfall_on_off){echo 'waterfall';}?> <?php echo $list_style;?>" post_id="<?php echo $post_id;?>" page="2" data-distance="800">
<div class="jinsom-page-select-post-list clear"></div>
</div>
<div style="height: 2vw;display: none;" id="jinsom-waterfull-margin"></div>
<div class="jinsom-select-more-content" style="display: none;">
<div class="jinsom-select-left-more-content">
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
foreach ($topic_add as $data) {
echo '
<div class="topic topic-select clear" data="topic_'.$topic_i.'">
<label>'.$data['name'].'</label>
<ul>
<li class="on" data="all">全部</li>';
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
foreach ($field_add as $data) {
echo '
<div class="topic field-select clear" data="field_'.$field_i.'">
<label>'.$data['name'].'</label>
<ul>
<li class="on" data="all">全部</li>';
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

<?php if($power_add){?>
<div class="topic power clear">
<label>类型</label>
<ul>
<li class="on" data="all">全部</li>
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
</div>
</div>
</div>
      