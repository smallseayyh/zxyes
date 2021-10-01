<?php 
//筛选数据
require( '../../../../../wp-load.php' );
// $require_url=get_template_directory();
$user_id=$current_user->ID;
$post_id=(int)$_POST['post_id'];
if(!$post_id){
echo jinsom_empty('数据不合法！');
exit;
}
$select_option=get_post_meta($post_id,'page_select_option',true);
$topic_relation=$select_option['jinsom_page_select_topic_relation'];
$number=(int)$select_option['jinsom_page_select_list_number'];
$list_style=$select_option['jinsom_page_select_list_style'];
$select_type=$select_option['jinsom_page_select_type'];
$gallery_on_off=$select_option['jinsom_page_select_gallery_on_off'];
$field_add=$select_option['jinsom_page_select_field_add'];
$img_style=$select_option['jinsom_page_select_img_style'];
$all_image_on_off=$select_option['jinsom_page_select_show_all_image_on_off'];

$page=(int)$_POST['page'];//页数
if(!$page){$page=1;}
$topic_id=strip_tags($_POST['topic_id']);
$bbs_id=strip_tags($_POST['bbs_id']);
$sort=strip_tags($_POST['sort']);
$power=strip_tags($_POST['power']);
$search=strip_tags($_POST['search']);
$field=strip_tags($_POST['field']);
$offset=($page-1)*$number;


$args=array(
'post_parent'=>999999999,
'showposts'=>$number,
'offset'=>$offset,
'no_found_rows'=>true,
'ignore_sticky_posts'=>1
);	

if($search!=''){

if(jinsom_get_option('jinsom_search_login_on_off')&&!is_user_logged_in()){
echo jinsom_empty('请登录之后再进行搜索！');
exit();	
}


$args['s']=$search;
}

if($select_type=='bbs'){//帖子
$args['post_parent']=999999999;
}else{//文章||视频||动态
$args['post_parent']=0;	
}


//论坛id
if($select_type=='bbs'&&$bbs_id){
if(is_numeric($bbs_id)&&$bbs_id){
$args['cat']=$bbs_id;	
}else{
$bbs_arr=explode(",",$bbs_id);
$bbs_arr_count=count($bbs_arr);
for ($i=0; $i < $bbs_arr_count; $i++) { 
if(!is_numeric($bbs_arr[$i])){
unset($bbs_arr[$i]);
}
}

if($bbs_arr){
$args['category__in']=$bbs_arr;
}

}
}

//话题
if($topic_id){
$topic_id=str_replace("|",",",$topic_id);
$topic_arr=explode(",",$topic_id);
$topic_arr_count=count($topic_arr);
for ($i=0; $i < $topic_arr_count; $i++) { 
if($topic_arr[$i]=='all'){
unset($topic_arr[$i]);
}
}
if($topic_arr){
if($topic_relation=='and'){
$args['tag__and']=$topic_arr;
}else{//or
$args['tag__in']=$topic_arr;	
}
}
}

// print_r($field_add);


$meta_query_arr=array();

//字段
if($field){
$field_arr=explode(",",$field);
$field_arr_count=count($field_arr);

for ($i=0; $i < $field_arr_count; $i++) { 
if($field_arr[$i]!='all'&&strpos($field_arr[$i],'|')){
$fields_arr=explode("|",$field_arr[$i]);
if(count($fields_arr)==4){//确保有4组才执行

if($fields_arr[0]){
$meta_query_arr['field_'.$i]['key']=$fields_arr[0];//写入key
}



//获取当前查询的字段的数组下标
$current_field_key=9999;//设置一个不可能的默认下标
for ($f=0; $f < count($field_add[$i]['field_add']); $f++) { 
if($field_add[$i]['field_add'][$f]['field_value']==$fields_arr[2]){
$current_field_key=$f;
}
}


if(($fields_arr[1]&&$fields_arr[2]&&$fields_arr[3]&&$field_add[$i]['field_add'][$current_field_key]['jinsom_page_select_field_type']=='compare')||($fields_arr[1]=='NOTEXISTS')){

if($fields_arr[1]=='NOTEXISTS'){
$fields_arr[1]='NOT EXISTS';
}else if($fields_arr[1]=='NOTLIKE'){
$fields_arr[1]='NOT LIKE';
}

$meta_query_arr['field_'.$i]['compare']=$fields_arr[1];


if(strpos($fields_arr[2],'-')){
$meta_query_arr['field_'.$i]['value']=explode("-",$fields_arr[2]);
}else{
$meta_query_arr['field_'.$i]['value']=$fields_arr[2];	
}
$meta_query_arr['field_'.$i]['type']=$fields_arr[3];
}


}
}
}
}

//权限
if($select_type=='bbs'){
if($power=='pay'){
$power='pay_see';
}else if($power=='vip'){
$power='vip_see';
}else if($power=='login'){
$power='login_see';
}else if($power=='comment'){
$power='comment_see';
}else if($power=='free'){
$power='normal';
}else if($power=='answer'||$power=='vote'||$power=='activity'||$power=='answer_ok'||$power=='answer_no'){
}else{
$power='all';
}


if($power!='all'&&$power!='answer_ok'&&$power!='answer_no'){
$meta_query_arr['post_type']=array(
'key' => 'post_type',
'value' => $power,
);
}

if($power=='answer_ok'||$power=='answer_no'){
$meta_query_arr['post_type']=array(
'key' => 'answer_adopt',
);	
if($power=='answer_no'){
$meta_query_arr['post_type']['compare']='NOT EXISTS';
$meta_query_arr['answer_number']['key']='answer_number';
}
}

}else{//文章||视频||动态

if($power=='pay'){
$power=1;
}else if($power=='vip'){
$power=4;
}else if($power=='login'){
$power=5;
}else if($power=='password'){
$power=2;
}else if($power=='free'){
$power=0;
}else{
$power='all';
}

if($power!='all'&&is_numeric($power)){
$meta_query_arr['post_power']=array(
'key' => 'post_power',
'value' => $power,
);
}

if($select_type!='all_a'){
$meta_query_arr['post_type']['key']='post_type';
$meta_query_arr['post_type']['value']=$select_type;
}else{//视频+文章
$meta_query_arr['post_type_a']['key']='post_type';
$meta_query_arr['post_type_a']['value']='single';
$meta_query_arr['post_type_b']['key']='post_type';
$meta_query_arr['post_type_b']['value']='video';
}

if($select_type=='words'){//筛选动态
$meta_query_arr['words']['key']='post_img';
}

}




//排序
if($sort=='comment_count'){
$args['orderby']='comment_count';	
}else if($sort=='last_comment'){
$meta_query_arr['last_comment_time']=array(
'key' => 'last_comment_time',
'type' => 'numeric',
);
$args['orderby']='last_comment_time';
}else if($sort=='views'){//浏览最多
$meta_query_arr['views']=array(
'key' => 'post_views',
'type' => 'numeric',
);
$args['orderby']='views';	
}else if($sort=='commend'){//推荐的
$meta_query_arr['commenda']=array(
'key' => 'jinsom_commend',
);	
// $args['meta_key']='jinsom_commend';
// $args['orderby']='date';
}else if($sort=='rand'){//随机
$args['orderby']='rand';	
}



$args['meta_query']=$meta_query_arr;

if($select_type=='all_a'){//视频+文章
$args['meta_query']['relation']='OR';
}

// print_r($args);

query_posts($args);
if(have_posts()){
while (have_posts()):the_post();
$post_id=get_the_ID();
$post_views=(int)get_post_meta($post_id,'post_views',true);//浏览量
$content=get_the_content();
$title=get_the_title();
if(!$title){
$title=strip_tags(mb_substr($content,0,30,'utf-8'));
}



if($all_image_on_off){//如果开启了 抽取内容的所有图片模式
preg_match_all("/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is",$content,$result);
$images_arr = $result[1];
$images_count=count($result[1]);
for ($i=0; $i < $images_count; $i++){ 
$img=$images_arr[$i];
require('select-post-templates.php');//筛选模版
}

}else{//普通模式

if(is_bbs_post($post_id)){//帖子
$img=jinsom_bbs_cover($content);
}else{
$post_type=get_post_meta($post_id,'post_type',true);
if($post_type=='words'){
$post_img=get_post_meta($post_id,'post_img',true);
$post_img_arr=explode(",",$post_img);
$img=$post_img_arr[0];	
}else if($post_type=='video'){
$img=jinsom_video_cover($post_id);	
}else{
$img=jinsom_single_cover($content);		
}
}



require('select-post-templates.php');//筛选模版
}

endwhile;
if($_POST['page']==1&&!wp_is_mobile()){
echo '<div class="jinsom-more-posts" page=2 onclick=\'jinsom_page_select_submit_form("more",this);\'>加载更多</div>';
}
}else{
if($_POST['page']>1){
echo 0;
}else{
echo jinsom_empty();
}	
}

