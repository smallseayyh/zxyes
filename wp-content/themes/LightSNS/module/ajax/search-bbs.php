<?php 
require( '../../../../../wp-load.php' );
//论坛搜索帖子

if(jinsom_get_option('jinsom_search_login_on_off')&&!is_user_logged_in()){
echo jinsom_empty(__('请登录之后再进行搜索！','jinsom'));
exit();
}

if(isset($_POST['page'])){
$page=$_POST['page'];
$bbs_id=$_POST['bbs_id'];
$current_bbs_id=$_POST['bbs_id'];//当前论坛id
$category=get_category($bbs_id);
$cat_parents=$category->parent;
$content=htmlspecialchars(strip_tags($_POST['content']));
//论坛信息
if($cat_parents>0){
$bbs_id=$cat_parents;	
}
$showposts=get_term_meta($bbs_id,'bbs_showposts',true);
if(empty($showposts)){$showposts=10;}
$post_target=get_term_meta($bbs_id,'bbs_post_target',true);
$bbs_list_style=(int)get_term_meta($bbs_id,'bbs_list_style',true);


//记录搜索词
if($content){
if(!current_user_can('level_10')){
global $wpdb;
$table_name=$wpdb->prefix.'jin_search_note';
$ip = $_SERVER['REMOTE_ADDR'];
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (content,user_id,type,ip,search_time) VALUES ('$content','$user_id','电脑论坛','$ip','$time')");
}
}

$offset = ($page-1)*$showposts;
$args = array(
'post_status' =>'publish',
'showposts' => $showposts,
'offset' => $offset ,
's' => $content,
'cat'=>$current_bbs_id,
'post_parent'=>999999999
);
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
$author_name=jinsom_nickname_link($author_id);
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
<div class="info clear">
<span><?php echo jinsom_avatar($author_id, '40' , avatar_type($author_id) );?> <?php echo $author_name;?></span>	
<span><i class="jinsom-icon jinsom-pinglun2"></i> <?php comments_number('0','1','%'); ?></span>	
<span><i class="jinsom-icon jinsom-liulan1"></i> <?php echo jinsom_views_show($post_views); ?></span>
</div>
</a>
</li>
<?php }else if($bbs_list_style==4){?>
<li>
<div class="mark">
<?php echo jinsom_bbs_post_type($post_id);?>	
</div>
<a href="<?php the_permalink(); ?>" <?php if($post_target){echo 'target="_blank"';}?>>
<?php echo jinsom_get_bbs_img(get_the_content(),$post_id,1);?>
<h2><?php the_title();?></h2>
<div class="info clear">
<?php 
echo '<span><m class="jinsom-icon jinsom-jinbi"></m>'.(int)get_post_meta($post_id,'post_price',true).'</span><span>'.$buy_times.'人已购买</span>';
?>	
</div>
</a>
</li>
<?php }else if($bbs_list_style==1){
require( get_template_directory() . '/post/bbs-list-2.php' );		
}else if($bbs_list_style==0){
require( get_template_directory() . '/post/bbs-list-1.php' );		
}


endwhile;
}else{
if($page==1){
echo jinsom_empty();	
}else{
echo 0;	
}
}

$more='';
if($bbs_list_style==0||$bbs_list_style==1){
$more='default';
}
$page=$_POST['page'];
if (have_posts()&&$page==1) {//有内容的时候显示
echo '<div class="jinsom-more-posts jinsom-bb-search-more '.$more.'" data="2" onclick=\'jinsom_ajax_bbs_search_more(this)\'>加载更多</div>';
}


}