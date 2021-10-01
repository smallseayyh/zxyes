<?php 
//兑换商城
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=(int)$_POST['page'];
$category_ids=strip_tags($_POST['data']);
$load_type=strip_tags($_POST['load_type']);
$style=strip_tags($_POST['style']);
$number=(int)$_POST['number'];
$offset = ($page-1)*$number;

$args = array(
'post_type' => 'goods',
'showposts' =>$number,
'offset' => $offset ,
'post_status' => 'publish',
);
if($category_ids){
$category_ids_arr=explode(",",$category_ids);
$args['tax_query']=array(
array(
'taxonomy' => 'shop',
'field' => 'id',
'terms' => $category_ids_arr
)
);
}

query_posts($args);
if(have_posts()){
$i=0;
while(have_posts()):the_post();
require($require_url.'/post/goods.php');
$i++;
endwhile;
if($i==$number&&$load_type=='menu'){
echo '<div class="jinsom-more-posts opacity" page="2" onclick="jinsom_shop_data_more(this)">'.__('加载更多','jinsom').'</div>';
}
}else{
if($load_type=='menu'){
echo jinsom_empty();	
}else{
echo 0;
}
}