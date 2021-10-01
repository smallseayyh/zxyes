<?php 
require( '../../../../../wp-load.php' );
//论坛列表加载更多帖子
$user_id=$current_user->ID;
if(isset($_POST['page'])){
$page=(int)$_POST['page'];
$bbs_id=(int)$_POST['bbs_id'];
$current_bbs_id=(int)$_POST['bbs_id'];//当前论坛id
$category=get_category($bbs_id);
$cat_parents=$category->parent;
$type=strip_tags($_POST['type']);
$topic=strip_tags($_POST['topic']);
//论坛信息
if($cat_parents>0){
$bbs_id=$cat_parents;	
}
$showposts=get_term_meta($bbs_id,'bbs_showposts',true);
if(empty($showposts)){$showposts=10;}
$post_target=get_term_meta($bbs_id,'bbs_post_target',true);
$bbs_list_style=(int)get_term_meta($bbs_id,'bbs_list_style',true);


$offset = ($page-1)*$showposts;
$all = jinsom_get_bbs_post($bbs_id);//父级板块总帖子数
if($type=='comment'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'meta_key' => 'last_comment_time',
'orderby'   => 'meta_value_num', 
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
}else if($type=='new'){//按最新发帖排序
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
}else if($type=='nice'){//加精的帖子
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'meta_key' => 'jinsom_commend',
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
}else if($type=='pay'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'meta_key' => 'post_price',
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
}else if($type=='answer'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'meta_key' => 'answer_number',
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
}else if($type=='ok'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'meta_key' => 'answer_adopt',
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
}else if($type=='no'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'cat'=>$current_bbs_id,
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
'offset' => $offset ,
'meta_key' => 'vote_data',
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
}else if($type=='activity'){
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'meta_key' => 'activity_data',
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
}else if($type=='custom_1'||$type=='custom_2'||$type=='custom_3'||$type=='custom_4'||$type=='custom_5'){//自定义模块
$topic_arr=explode(",",$topic);
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
'cat'=>$current_bbs_id,
'tag__in' =>$topic_arr,
'post_parent'=>999999999
);
}

$args['no_found_rows']=true;

query_posts($args);
if (have_posts()) {
while ( have_posts() ) : the_post();
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');
$post_views=(int)get_post_meta($post_id,'post_views',true);
$post_from=get_post_meta($post_id,'post_from',true);
$post_type=get_post_meta($post_id,'post_type',true);
$buy_times=(int)get_post_meta($post_id,'buy_times',true);
?>
<?php if($bbs_list_style==2||$bbs_list_style==3){?>
<li class="grid">
<div class="mark">
<?php echo jinsom_bbs_post_type($post_id);?>	
</div>
<a href="<?php the_permalink(); ?>" <?php if($post_target){echo 'target="_blank"';}?>>
<?php 
if($bbs_list_style==2){
echo jinsom_get_bbs_img(get_the_content(),$post_id,1);//格子模式
}else{
echo jinsom_get_bbs_img(get_the_content(),$post_id,2);	//瀑布流模式
}

?>
<h2><?php the_title();?></h2>
</a>
<div class="info clear">
<span><a href="<?php echo jinsom_userlink($author_id);?>" target="_blank"><?php echo jinsom_avatar($author_id,'40',avatar_type($author_id) );?> <m><?php echo jinsom_nickname($author_id);?></m></a></span>	
<?php if(jinsom_is_like_post($post_id,$user_id)){?>
<span class="jinsom-had-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan1"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</span>
<?php }else{?>
<span class="jinsom-no-like" onclick='jinsom_like_posts(<?php echo $post_id;?>,this);'>
<i class="jinsom-icon jinsom-xihuan2"></i> <span><?php echo jinsom_count_post(0,$post_id);?></span>
</span>
<?php }?>
</div>
</li>
<?php }else if($bbs_list_style==4){?>
<li>
<div class="mark">
<?php echo jinsom_bbs_post_type($post_id);?>		
</div>
<a href="<?php the_permalink(); ?>" <?php if($post_target){echo 'target="_blank"';}?>>
<?php echo jinsom_get_bbs_img(get_the_content(),$post_id,1);?>
<h2><?php the_title();?></h2>
</a>
<div class="info clear">
<?php 
echo '<span><m class="jinsom-icon jinsom-jinbi"></m>'.(int)get_post_meta($post_id,'post_price',true).'</span><span><i class="jinsom-icon jinsom-goumai2"></i> '.$buy_times.'</span>';?>	
</div>
</li>
<?php }else if($bbs_list_style==1){
require( get_template_directory() . '/post/bbs-list-2.php' );		
}else if($bbs_list_style==0){
require( get_template_directory() . '/post/bbs-list-1.php' );		
}


endwhile;
}else{
if($_POST['page']>1){
echo 0;
}else{
echo jinsom_empty();	
}
}

$more='';
if($bbs_list_style==0||$bbs_list_style==1){
$more='default';
}

if (have_posts()&&$_POST['page']==1){//有内容的时候显示

echo '<div class="jinsom-more-posts '.$more.'" data="2" onclick=\'jinsom_ajax_bbs(this,"'.$type.'")\'>加载更多</div>';

}


}