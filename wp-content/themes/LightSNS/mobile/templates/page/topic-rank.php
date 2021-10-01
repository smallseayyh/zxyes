<?php 
//话题排行榜
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="topic-rank" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">热门<?php echo jinsom_get_option('jinsom_topic_name');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content">
<div class="jinsom-topic-rank-list">
<?php 
$terms_args=array(
'number'=>30,
'taxonomy'=>'post_tag',//话题
'meta_key'=>'topic_views',
'orderby'=>'meta_value_num',
'order'=>'DESC'
);
$tag_arr=get_terms($terms_args);
if(!empty($tag_arr)){
$i=1;
foreach ($tag_arr as $tag) {
if($i==1){
$number='a';
}else if($i==2){
$number='b';
}else if($i==3){
$number='c';
}else{
$number='';	
}
$topic_id=$tag->term_id;
$topic_data=get_term_by('id',$topic_id,'post_tag');
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
$topic_desc=get_term_meta($topic_id,'topic_desc',true);
echo '
<li class="'.$number.'" title="'.$tag->name.'">
<div class="rank"><span>'.$i.'</span></div>
<a href="'.jinsom_mobile_topic_id_url($topic_id).'" class="link">
<div class="avatarimg">'.jinsom_get_bbs_avatar($topic_id,1).'</div>
<div class="info">
<div class="name"># '.$tag->name.' #</div>
<div class="number"><span>'.$topic_data->count.__('内容','jinsom').'</span><span>'.jinsom_views_show($topic_views).' '.__('浏览','jinsom').'</span></div>
</div>
</a>
'.jinsom_topic_like_btn($user_id,$topic_id).'
</li>';
$i++;
}
}
?>

</div>
</div>

</div>
</div>        