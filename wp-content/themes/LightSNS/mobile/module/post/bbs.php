<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
$page=(int)$_POST['page'];
if(!isset($_POST['page'])){$page=1;}
$type=strip_tags($_POST['type']);
$topic=strip_tags($_POST['topic']);
$bbs_id=(int)$_POST['bbs_id'];
$category=get_category($bbs_id);
$cat_parents=$category->parent;
if($cat_parents>0){
$showposts=(int)get_term_meta($cat_parents,'bbs_showposts',true);	
}else{
$showposts=(int)get_term_meta($bbs_id,'bbs_showposts',true);	
}
if(!$showposts){$showposts=20;}

$load_type='bbs';

$offset=($page-1)*$showposts;

if($type=='comment'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'no_found_rows'=>true,
'offset' => $offset ,
'meta_key' => 'last_comment_time',
'orderby'   => 'meta_value_num', 
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='new'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'no_found_rows'=>true,
'offset' => $offset ,
'cat'=>$bbs_id,
'post_parent'=>999999999
);	
}else if($type=='nice'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'no_found_rows'=>true,
'offset' => $offset ,
'cat'=>$bbs_id,
'meta_key' => 'jinsom_commend',
'post_parent'=>999999999
);		
}else if($type=='pay'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'no_found_rows'=>true,
'offset' => $offset ,
'meta_key' => 'post_price',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='answer'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'no_found_rows'=>true,
'offset' => $offset ,
'meta_key' => 'answer_number',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='ok'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'no_found_rows'=>true,
'offset' => $offset ,
'meta_key' => 'answer_adopt',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='no'){
$args = array(
'post_status' =>'publish',
'no_found_rows'=>true,
'showposts' => $showposts,
'offset' => $offset ,
'cat'=>$bbs_id,
'post_parent'=>999999999,
'meta_query' => array(
array(
'key' => 'answer_adopt',
'compare' =>'NOT EXISTS'
),
array(
'key' => 'answer_number',
)
)
);
}else if($type=='vote'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'no_found_rows'=>true,
'offset' => $offset ,
'meta_key' => 'vote_data',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='activity'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'no_found_rows'=>true,
'offset' => $offset ,
'meta_key' => 'activity_data',
'cat'=>$bbs_id,
'post_parent'=>999999999
);
}else if($type=='custom_1'||$type=='custom_2'||$type=='custom_3'||$type=='custom_4'||$type=='custom_5'){//自定义模块
$topic_arr=explode(",",$topic);
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'cat'=>$bbs_id,
'tag__in' =>$topic_arr,
'post_parent'=>999999999
);
}


$args['ignore_sticky_posts']=1;
query_posts($args);
if (have_posts()){
while (have_posts()):the_post();
require(get_template_directory().'/mobile/templates/post/bbs-power.php');
endwhile;
}else{
echo 0;
}
