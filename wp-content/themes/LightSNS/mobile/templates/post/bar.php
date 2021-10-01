<?php 
$application_type=get_post_meta($post_id,'application-type',true);
$application_value=get_post_meta($post_id,'application-value',true);
if($application_type){
echo '<div class="jinsom-post-application-show">';
if($application_type=='url'){
if(strstr($application_value,home_url())){
echo '<a href="'.$application_value.'" target="_blank" class="url" rel="nofollow"><i class="jinsom-icon jinsom-tuiguang"></i> <span>'.$application_value.'</span></a>';
}else{
echo '<a href="'.get_template_directory_uri().'/mobile/templates/page/url.php?link='.$application_value.'" class="url link" rel="nofollow"><i class="jinsom-icon jinsom-tuiguang"></i> <span>'.$application_value.'</span></a>';
}

}else if($application_type=='shop'){
echo '<a href="'.jinsom_mobile_post_url($application_value).'" class="link shop">';
$goods_data=get_post_meta($application_value,'goods_data',true);
$goods_type=$goods_data['jinsom_shop_goods_type'];//商品类型
$price_type=$goods_data['jinsom_shop_goods_price_type'];
$select_change_price_add=$goods_data['jinsom_shop_goods_select_change_price_add'];//价格套餐
if($goods_type=='a'||$goods_type=='d'||!$select_change_price_add){//本站虚拟、淘宝客、没有添加价格套餐
$pay_price=(int)$goods_data['jinsom_shop_goods_price'];
$price_discount=(int)$goods_data['jinsom_shop_goods_price_discount'];
}else{
$pay_price=(int)$select_change_price_add[0]['value_add'][0]['price'];
$price_discount=(int)$select_change_price_add[0]['value_add'][0]['price_discount'];
}
if($price_discount){
$pay_price=$price_discount;
}
$cover=$goods_data['jinsom_shop_goods_img'];
$cover_img_add=$goods_data['jinsom_shop_goods_img_add'];
if($cover_img_add){
$cover_one=$cover_img_add[0]['img'];
}else{
if($cover){
$cover_arr=explode(',',$cover);
$cover_src_one=wp_get_attachment_image_src($cover_arr[0],'full');
$cover_one=$cover_src_one[0];//第一张封面
}
}
if($price_type=='rmb'){
$price_icon='<t class="yuan">￥</t>';
}else{
$price_icon='<m class="jinsom-icon jinsom-jinbi"></m>';	
}

echo '<img src="'.$cover_one.'"><span class="title">'.get_the_title($application_value).'</span><span class="price">'.$price_icon.$pay_price.'</span>';

echo '</a>';
}else if($application_type=='challenge'){
echo '<a href="'.get_template_directory_uri().'/mobile/templates/page/challenge-mine.php?author_id='.$author_id.'" class="link challenge"><i class="jinsom-icon jinsom-jirou"></i> '.__('赶紧来挑战我吧！','jinsom').'</a>';
}else if($application_type=='pet'){
$pet_arr=explode(",",$application_value);
if(count($pet_arr)==2){
$pet_name=$pet_arr[0];
$pet_img=$pet_arr[1];
echo '<a href="'.get_template_directory_uri().'/mobile/templates/page/pet.php?author_id='.$author_id.'" class="link pet"><img src="'.$pet_img.'"> '.$pet_name.'-'.__('赶紧来看看我的新宠物吧！','jinsom').'</a>';
}
}
echo '</div>';
}
?>

<div class="jinsom-city-topic-bar clear">
<?php 
$post_city=get_post_meta($post_id,'city',true);
if($post_city){
?>
<div class="jinsom-post-city"><i class="jinsom-icon jinsom-xiazai19"></i> <?php echo $post_city;?></div>
<?php }?>
<?php 
if($more_type!='single'){
$topic_data=wp_get_post_tags($post_id);
if($topic_data){
echo '<div class="topic-list clear">';
$i=1;
foreach($topic_data as $topic_datas){
$topic_id=$topic_datas->term_id;
if($i<=3){
if($i==1){echo '<span># </span>';}
echo '<a href="'.jinsom_mobile_topic_id_url($topic_id).'" class="link">'.$topic_datas->name.'</a>';
}
$i++;
}
echo '</div>';
}
}

 ?>
</div>
<div class="footer">

<?php if($post_type=='single'){?>
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="user link"><?php echo jinsom_avatar($author_id,'20',avatar_type($author_id));?> <?php echo jinsom_nickname($author_id);?></a>
<?php }?>

<?php if(jinsom_is_like_post($post_id,$user_id)){?>
<a onclick="jinsom_like(<?php echo $post_id;?>,this)"><i class="jinsom-icon jinsom-xihuan1 like had"></i> <span class="like_num"><?php echo jinsom_count_post(0,$post_id);?></span></a>
<?php }else{?> 
<a onclick="jinsom_like(<?php echo $post_id;?>,this)"><i class="jinsom-icon jinsom-xihuan2 like"></i> <span class="like_num"><?php echo jinsom_count_post(0,$post_id);?></span></a>
<?php }?>

<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link comment"><i class="jinsom-icon jinsom-pinglun2 comment"></i> <span class="comment_number"><?php echo get_comments_number($post_id); ?></span></a>


<?php if($post_type!='single'){?>
<a href="<?php echo jinsom_mobile_post_url($post_id);?>" class="link views"><i class="jinsom-icon jinsom-liulan1 views"></i> <?php echo jinsom_views_show($post_views);?></a>
<?php }?>

<?php if(is_bbs_post($post_id)){?>
<a href="#" class="link more" onclick="jinsom_post_more_form(<?php echo $post_id;?>,<?php echo (int)$bbs_id;?>,'<?php echo $more_type;?>')"><i class="jinsom-icon jinsom-gengduo2"></i></a>
<?php }else{?>
<a href="#" class="link more" onclick="jinsom_post_more_form(<?php echo $post_id;?>,0,'<?php echo $more_type;?>')"><i class="jinsom-icon jinsom-gengduo2"></i></a>	
<?php }?>


</div>
<?php if($more_type!='single'&&get_comments_number($post_id)&&jinsom_get_option('jinsom_mobile_post_list_comment_list')&&!get_post_meta($post_id,'comment_private',true)){?>
<div class="jinsom-post-list-comment <?php echo $post_type?>">
<div class="jinsom-post-list-comment-content">
<?php 
$comment_up_id=(int)get_post_meta($post_id,'up-comment',true);//置顶的评论ID
if(!$comment_up_id){$comment_up_id=9999999999;}
$comment_data = get_comments('comment__in='.$comment_up_id);
if (!empty($comment_data) ) { 
foreach ($comment_data as $data) {
$comment_user_id=$data->user_id;
$comment_content=$data->comment_content;
$comment_content = preg_replace("/\[file[^]]+\]/", "[附件]",$comment_content);
$comment_content = preg_replace("/\[video[^]]+\]/", "[视频]",$comment_content);
$comment_content = preg_replace("/\[music[^]]+\]/", "[音乐]",$comment_content);
$comment_content = preg_replace("/<img[^>]*src\s*=\s*[\"|\']?\s*([^>\"\'\s]*)(\">|\"\/>)/i", "[图片]",$comment_content);
echo '<li>'.jinsom_nickname_link($comment_user_id).'：<m>置顶</m><span><a href="'.jinsom_mobile_post_url($post_id).'" class="link">'.convert_smilies(strip_tags($comment_content)).'</a></span></li>';
}
}


$comment_data=get_comments('status=approve&type=comment&no_found_rows=false&number=3&comment__not_in='.$comment_up_id.'&post_id='.$post_id);
if(!empty($comment_data)){ 
foreach ($comment_data as $data){
$comment_user_id=$data->user_id;
$comment_content=$data->comment_content;
$comment_content = preg_replace("/\[file[^]]+\]/", "[附件]",$comment_content);
$comment_content = preg_replace("/\[video[^]]+\]/", "[视频]",$comment_content);
$comment_content = preg_replace("/\[music[^]]+\]/", "[音乐]",$comment_content);
$comment_content = preg_replace("/<img[^>]*src\s*=\s*[\"|\']?\s*([^>\"\'\s]*)(\">|\"\/>)/i", "[图片]",$comment_content);
echo '<li>'.jinsom_nickname_link($comment_user_id).'：<span>'.convert_smilies(jinsom_autolink(strip_tags($comment_content))).'</span></li>';
}
}
?>
</div>
</div>
<?php }?>