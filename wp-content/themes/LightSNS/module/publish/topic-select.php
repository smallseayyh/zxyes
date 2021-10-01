<?php
//发表内容选择话题表单
require( '../../../../../wp-load.php' );
if($_POST['type']){
$type=$_POST['type'];
}else{
$type='';
}
?>
<div class="jinsom-publish-topic-main">
<div class="jinsom-publish-topic-header">
<input placeholder="新建或搜索<?php echo jinsom_get_option('jinsom_topic_name');?>" oninput="jinsom_pop_topic_search()" maxlength="30">
</div>
<div class="jinsom-publish-topic-content clear">
<?php
$args=array(
'number'=>8,
'taxonomy'=>'post_tag',//话题
'meta_key'=>'topic_views',
'orderby'=>'meta_value_num',
'hide_empty'=>false,
'order'=>'DESC'
);
$tag_arr=get_terms($args);
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
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
echo '<li onclick=\'jinsom_pop_topic_select(this,"'.$type.'")\' class="'.$number.'" data="'.$tag->name.'"># '.$tag->name.' #<span><i class="jinsom-icon hot jinsom-huo"></i> '.$topic_views.'</span></li>';
$i++;
}
}
?>
</div>
</div>
