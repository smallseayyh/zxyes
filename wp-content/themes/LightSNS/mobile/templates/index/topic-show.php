<?php echo $topic_data['jinsom_topic_mobile_header_ad'];?>
<div class="jinsom-topic-show-form clear">
<?php if($jinsom_topic_add){?>
<div class="left">
<?php 
$i=1;
foreach ($jinsom_topic_add as $data) {
if($i==1){
echo '<li class="on">'.$data['title'].'</li>';
}else{
echo '<li>'.$data['title'].'</li>';
}	
$i++;
}
?>
</div>
<div class="right">

<?php 
$j=1;
foreach ($jinsom_topic_add as $data) {
$type=$data['jinsom_topic_add_type'];
if($j==1){
echo '<ul class="on">';
}else{
echo '<ul>';
}

echo '<div class="html">'.do_shortcode($data['mobile_html']).'</div>';//自定义html

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

echo '
<li>
<a href="'.jinsom_mobile_topic_id_url($topic_id).'" class="link">
<div class="avatarimg">'.jinsom_get_bbs_avatar($topic_id,1).'</div>
<div class="info">
<div class="name">'.$topic_data->name.'</div>
<div class="number">
<span>'.__('内容','jinsom').'<i>'.$topic_data->count.'</i></span>
<span>'.__('浏览','jinsom').'<i>'.jinsom_views_show($topic_views).'</i></span>
</div>
</div>
</a>
'.jinsom_topic_like_btn($user_id,$topic_id).'
</li>';

$k++;
}


}else{
echo jinsom_empty();
}
}else if($type=='views'||$type=='content'){

if($type=='views'){//按浏览量排序
$terms_args=array(
'number'=>$data['number'],
'taxonomy'=>'post_tag',
'meta_key'=>'topic_views',
'orderby'=>'meta_value_num',
'order'=>'DESC'
);
}else{//按内容数量排序
$terms_args=array(
'number'=>$data['number'],
'taxonomy'=>'post_tag',
'orderby'=>'count',
'order'=>'DESC'
);	
}


$tag_arr=get_terms($terms_args);
if(!empty($tag_arr)){
foreach ($tag_arr as $tag) {
$topic_id=$tag->term_id;
$topic_data=get_term_by('id',$topic_id,'post_tag');
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);

echo '
<li>
<a href="'.jinsom_mobile_topic_id_url($topic_id).'" class="link">
<div class="avatarimg">'.jinsom_get_bbs_avatar($topic_id,1).'</div>
<div class="info">
<div class="name">'.$topic_data->name.'</div>
<div class="number">
<span>'.__('内容','jinsom').'<i>'.$topic_data->count.'</i></span>
<span>'.__('浏览','jinsom').'<i>'.jinsom_views_show($topic_views).'</i></span>
</div>
</div>
</a>
'.jinsom_topic_like_btn($user_id,$topic_id).'
</li>';

}


}else{
echo jinsom_empty();
}
}
echo '</ul>';
$j++;
}



?>



</div>

<?php }else{?>
当前页面还没有添加数据，请在WordPress后台-页面-所有页面-找到当前页面-进行配置设置数据
<?php }?>

</div>