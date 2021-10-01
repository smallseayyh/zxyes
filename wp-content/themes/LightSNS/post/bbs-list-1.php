<?php 
//简约
$post_id=get_the_ID();
$author_id=get_the_author_meta('ID');
$post_views=(int)get_post_meta($post_id,'post_views',true);
$post_from=get_post_meta($post_id,'post_from',true);
$post_type=get_post_meta($post_id,'post_type',true);
$title_color=get_post_meta($post_id,'title_color',true);//标题颜色
?>
<div class="jinsom-bbs-list-1" id="jinsom-bbs-post-<?php echo $post_id;?>">
<div class="left clear">
<a href="<?php echo jinsom_userlink($author_id);?>" target="_blank">
<?php echo jinsom_vip_icon($author_id);?>
<?php echo jinsom_avatar($author_id, '50' , avatar_type($author_id) );?>
<?php echo jinsom_verify($author_id);?>
</a>
</div>

<div class="right">

<h2>
<a href="<?php the_permalink(); ?>" <?php if($post_target){echo 'target="_blank"';}?> <?php if($title_color){echo 'style="color:'.$title_color.'"';}?>>
<?php 
if($cat_parents==0){//子论坛不显示
$category_list = get_the_category();
if(count($category_list)>1){
if($category_list[0]->term_id==$bbs_id){//判断当前文章的第一个分类id是否等于当前论坛的父级分类id
$list_cat_id=$category_list[1]->term_id;
$list_cat_name=$category_list[1]->cat_name;
}else{
$list_cat_id=$category_list[0]->term_id;
$list_cat_name=$category_list[0]->cat_name;  
}
}else{
$list_cat_id=$category_list[0]->term_id;
$list_cat_name=$category_list[0]->cat_name;
}

echo '<span class="cat-item-'.$list_cat_id.'">'.$list_cat_name.'<i></i></span>'; 
}

the_title();
?>
</a>
</h2>
<span class="mark">
<?php 
//有图帖子
if(preg_match("/<img.*>/",get_the_content())){ 
echo '<span class="jinsom-bbs-post-type-img"><i class="jinsom-icon jinsom-tupian2"></i></span>';
}
echo jinsom_bbs_post_type($post_id);
?>
</span>

<div class="num"><i class="jinsom-icon jinsom-pinglun2"></i> <?php comments_number('0','1','%'); ?></div>
<div class="jinsom-bbs-list-1-info clear">
<div class="jinsom-bbs-list-1-info-left">
<span>
<i class="jinsom-icon jinsom-my_light"></i> <?php echo jinsom_nickname_link($author_id);?>
</span>
<span><?php echo jinsom_timeago(get_the_time('Y-m-d G:i:s'));?></span>
<span><i class="jinsom-icon jinsom-liulan1"></i> <?php  echo jinsom_views_show($post_views); ?></span>
<span>来自 <?php if($post_from=='mobile'){echo '手机端';}else{echo '电脑端'; }?></span>
</div>
<div class="jinsom-bbs-list-1-info-right">
<?php $comment_args=array(
'post_id' => $post_id,
'orderby' => 'comment_date',
'order' => 'DESC',
'number' => 1,
'status' => 'approve'
);
$bbs_comment_info=get_comments($comment_args);
foreach ($bbs_comment_info as $bbs_comment_infos) {
echo '<span><i class="jinsom-icon jinsom-xiaoxizhongxin"></i> '.jinsom_nickname_link($bbs_comment_infos->user_id).'</span>';
echo ' <span>'.jinsom_timeago(get_the_time($bbs_comment_infos->comment_date)).'</span>';
}
?>
</div>
</div><!-- jinsom-bbs-list-1-info -->
</div>
</div>