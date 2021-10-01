<?php 
require( '../../../../../../wp-load.php' );
$require_url=get_template_directory();
$user_id=$current_user->ID;
$page=(int)$_POST['page'];
if(!$page){$page=1;}
$cat_id=$_POST['cat_id'];
$sort=strip_tags($_POST['sort']);
$list_style=strip_tags($_POST['list_style']);
$price_type=strip_tags($_POST['price_type']);
$price=strip_tags($_POST['price']);
$search=strip_tags($_POST['search']);
$number=10;
$offset=($page-1)*$number;
$args = array(
'post_type' => 'goods',
'showposts' =>$number,
'post_status' => 'publish',
'offset' => $offset,
);

if($cat_id&&$cat_id!='all'){
$args['tax_query']=array(
array(
'taxonomy' => 'shop',
'field' => 'id',
'terms' => explode(",",$cat_id)
)
);
}

//排序
if($sort=='rand'){
$args['orderby']='rand';
}else if($sort=='comment'){//好评率
$args['meta_key']='star_percent';
$args['orderby']='meta_value_num';
}else if($sort=='buy'){//销量
$args['meta_key']='buy_number';
$args['orderby']='meta_value_num';
}else if($sort=='price_min'||$sort=='price_max'){//价格低到高
$args['meta_key']='goods_price';
$args['orderby']='meta_value_num';
if($sort=='price_min'){
$args['order']='ASC';	
}
}

$meta_query_arr=array();

//价格范围
if($price){
$price_arr=explode("-",$price);
if(count($price_arr)==2){
$meta_query_arr['price']=array(
'key' => 'goods_price',
'value' =>$price_arr,
'compare' => 'BETWEEN',
'type' => 'NUMERIC',
);
}
}

//价格类型
if($price_type){
if($price_type=='rmb'||$price_type=='credit'){
$meta_query_arr['price_type']=array(
'key' => 'goods_price_type',
'value' =>$price_type,
);
}
}

$args['meta_query']=$meta_query_arr;


if($search){
$args['s']=$search;
}
$args['ignore_sticky_posts']=1;
$args['no_found_rows']=true;
// print_r($args);
query_posts($args); 
if(have_posts()){
$i=0;
while(have_posts()):the_post();
if($list_style=='waterfall'){
$waterfall=1;
}else{
$waterfall=0;
}
require($require_url.'/post/goods.php');
$i++;
endwhile;
}else{
if($page>1){
echo 0;
}else{
echo jinsom_empty();
}	
}