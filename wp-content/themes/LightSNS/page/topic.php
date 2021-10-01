<?php
/*
Template Name:话题中心
*/
?>
<?php 
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');	
}else{

get_header();

$post_id=get_the_ID();
$user_id = $current_user->ID;
$topic_data=get_post_meta($post_id,'topic_show_page_data',true);

if(!$topic_data){
echo jinsom_empty('请在新建页面的时候配置当前页面的数据！');exit();
}


$jinsom_bbs_slider_add = $topic_data['jinsom_bbs_slider_add'];
$jinsom_topic_add = $topic_data['jinsom_topic_add'];
$jinsom_topic_header_ad = $topic_data['jinsom_topic_header_ad'];
$jinsom_topic_footer_ad = $topic_data['jinsom_topic_footer_ad'];
?>
<!-- 主内容 -->
<div class="jinsom-topic-page">
<?php echo do_shortcode($jinsom_topic_header_ad);?>
<div class="jinsom-main-content single clear">
<div class="jinsom-show-topic-content">

<?php if($jinsom_topic_add){?>	
<div class="layui-tab layui-tab-brief">
<?php if(count($jinsom_topic_add)>1){?>
<ul class="layui-tab-title">
<?php 
$i=1;
foreach ($jinsom_topic_add as $data) {
if($i==1){
echo '<li class="layui-this">'.$data['title'].'</li>';
}else{
echo '<li>'.$data['title'].'</li>';
}	
$i++;
}
?>
</ul>
<?php }?>
<div class="layui-tab-content jinsom-topic-list">
<?php 
$j=1;
foreach ($jinsom_topic_add as $data) {
$type=$data['jinsom_topic_add_type'];
if($j==1){
echo '<div class="layui-tab-item layui-show clear">';
}else{
echo '<div class="layui-tab-item">';
}

echo '<div class="html">'.do_shortcode($data['pc_html']).'</div>';//自定义html

if($type=='add'||$type=='follow'){
if($type=='add'){//手动添加数据
$data_arr=explode(",",$data['data']);
$number=1000;
}else{//用户关注的话题
$data_arr=jinsom_get_user_follow_topic_id($user_id);	
$number=$data['number'];
}

if($data_arr){
$k=1;
foreach ($data_arr as $topic_id) {
if($k>$number){break;}
$topic_data=get_term_by('id',$topic_id,'post_tag');
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
if(jinsom_is_topic_like($user_id,$topic_id)){
$follow='<div class="follow had opacity" onclick="jinsom_topic_like('.$topic_id.',this)"><i class="jinsom-icon jinsom-yiguanzhu"></i> '.__('已关注','jinsom').'</div>';
}else{
$follow='<div class="follow opacity" onclick="jinsom_topic_like('.$topic_id.',this)"><i class="jinsom-icon jinsom-guanzhu"></i> '.__('关 注','jinsom').'</div>';
}


echo '
<li>
<a href="'.get_tag_link($topic_id).'" target="_blank">
<div class="images">'.jinsom_get_bbs_avatar($topic_id,1).'</div>
<div class="name">'.$topic_data->name.'</div>
<div class="desc">'.get_term_meta($topic_id,'topic_desc',true).'</div>
</a>
'.$follow.'
<hr>
<div class="info">
<div class="item"><span>'.$topic_data->count.'</span><p>'.__('内容','jinsom').'</p></div>
<div class="item"><span>'.jinsom_topic_like_number($topic_id).'</span><p>'.__('关注','jinsom').'</p></div>
<div class="item"><span>'.jinsom_views_show($topic_views).'</span><p>'.__('浏览','jinsom').'</p></div>
</div>
</li>';
$k++;
}


}
}else if($type=='views'||$type=='content'){

if($type=='views'){//按浏览量排序
$terms_args=array(
'number'=>$data['number'],
'taxonomy'=>'post_tag',
'meta_key'=>'topic_views',
'orderby'=>'meta_value_num',
'hide_empty'=>false,
'order'=>'DESC'
);
}else{//按内容数量排序
$terms_args=array(
'number'=>$data['number'],
'taxonomy'=>'post_tag',
'orderby'=>'count',
'hide_empty'=>false,
'order'=>'DESC'
);	
}


$tag_arr=get_terms($terms_args);
if(!empty($tag_arr)){
foreach ($tag_arr as $tag) {
$topic_id=$tag->term_id;
$topic_data=get_term_by('id',$topic_id,'post_tag');
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
if(jinsom_is_topic_like($user_id,$topic_id)){
$follow='<div class="follow had opacity" onclick="jinsom_topic_like('.$topic_id.',this)"><i class="jinsom-icon jinsom-yiguanzhu"></i> '.__('已 关','jinsom').'</div>';
}else{
$follow='<div class="follow opacity" onclick="jinsom_topic_like('.$topic_id.',this)"><i class="jinsom-icon jinsom-guanzhu"></i> '.__('关 注','jinsom').'</div>';
}

$desc=get_term_meta($topic_id,'topic_desc',true);
if(!$desc){$desc=jinsom_get_option('jinsom_topic_default_desc');}

echo '
<li>
<a href="'.get_tag_link($topic_id).'" target="_blank">
<div class="images">'.jinsom_get_bbs_avatar($topic_id,1).'</div>
<div class="name">'.$topic_data->name.'</div>
<div class="desc">'.$desc.'</div>
</a>
'.$follow.'
<hr>
<div class="info">
<div class="item"><span>'.$topic_data->count.'</span><p>'.__('内容','jinsom').'</p></div>
<div class="item"><span>'.jinsom_topic_like_number($topic_id).'</span><p>'.__('关注','jinsom').'</p></div>
<div class="item"><span>'.jinsom_views_show($topic_views).'</span><p>'.__('浏览','jinsom').'</p></div>
</div>
</li>';

}


}else{
echo jinsom_empty();
}
}
echo '</div>';
$j++;
}



?>
</div>
</div>
<?php }?>
</div> 

</div>
<?php echo do_shortcode($jinsom_topic_footer_ad);?>
</div>
</div>

<?php get_footer(); ?>

<?php }//判断是否移动端?>