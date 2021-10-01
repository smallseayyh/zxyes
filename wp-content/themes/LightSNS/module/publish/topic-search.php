<?php

require( '../../../../../wp-load.php' );
$key=strip_tags($_POST['key']);

//话题搜索
if(isset($_POST['type'])&&$_POST['type']=='search'){
$args=array(
'number'=>8,
'taxonomy'=>'post_tag',//话题
'search'=>$key,
'orderby' =>'count',
'hide_empty'=>false,
'order' =>'DESC'
);
$tag_arr=get_terms($args);
echo '<li onclick="jinsom_pop_topic_select(this)" class="new" data="'.$key.'">#'.$key.'#<p>点击使用此'.jinsom_get_option('jinsom_topic_name').'</p></li>';
if(!empty($tag_arr)){
foreach ($tag_arr as $tag) {
$topic_id=$tag->term_id;
$topic_views=(int)get_term_meta($topic_id,'topic_views',true);
echo '<li onclick="jinsom_pop_topic_select(this)" data="'.$tag->name.'"># '.$tag->name.' #<span><i class="jinsom-icon hot jinsom-huo"></i> '.$topic_views.'</span></li>';
}
}
}

//热门话题
if(isset($_POST['type'])&&$_POST['type']=='hot'){
$args=array(
'number'=>8,
'taxonomy'=>'post_tag',//话题
'meta_key'=>'topic_views',
'hide_empty'=>false,
'orderby'=>'meta_value_num',
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
echo '<li onclick="jinsom_pop_topic_select(this)" class="'.$number.'" data="'.$tag->name.'"># '.$tag->name.' #<span><i class="jinsom-icon hot jinsom-huo"></i> '.$topic_views.'</span></li>';
$i++;
}
}
}