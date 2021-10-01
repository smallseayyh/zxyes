<?php 
//树洞
require( '../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;

$more_type='single';//用于区分当前页面是列表页面还是内页
$post_id=$_GET['post_id'];
$post_data = get_post($post_id, ARRAY_A);
$author_id=$post_data['post_author'];
$post_date=$post_data['post_date'];
$content=convert_smilies(wpautop($post_data['post_content']));
$color=get_post_meta($post_id,'secret_color',true);
$secret_name=get_post_meta($post_id,'secret_name',true);
$secret_avatar=get_post_meta($post_id,'secret_avatar',true);
$nice_num=(int)get_post_meta($post_id,'nice_num',true);
$nice=get_post_meta($post_id,'nice',true);
if($nice){
$nice_arr=explode(",",$nice);
if(in_array($user_id,$nice_arr)){
$nice_status=1;
}else{
$nice_status=0;	
}
}else{
$nice_status=0;
}

$topic_arr=wp_get_post_tags($post_id);
if($topic_arr){
foreach($topic_arr as $data){
$topic='#'.$data->name;
}
}else{
$topic='';
}

?>
<div data-page="post-secret" class="page no-tabbar post-secret">

<div class="navbar">
<div class="navbar-inner">
<div class="left"><a href="#" class="back link icon-only"><i class="jinsom-icon jinsom-fanhui2"></i></a></div>
<div class="center sliding">详情</div>
<div class="right"><a href="#" class="link icon-only"></a></div>
</div>
</div>

<div class="page-content jinsom-single-secret-content">
<div class="jinsom-post-secret-list single" data="<?php echo $post_id;?>">
<?php if($post_data){?>

<li class="box">
<div class="left">
<div class="avatarimg"><img src="<?php echo $secret_avatar;?>" class="avatar"></div>
<div class="name"><?php echo $secret_name;?></div>
<div class="time"><?php echo $topic;?></div>
</div>
<div class="right">
<div class="content" style="background:<?php echo $color;?>;">
<a href="#" class="link">
<?php echo $content;?>
</a>
</div>
</div>
</li>

<?php if($nice_status){?>
<div class="jinsom-secret-single-nice had">
<n><?php echo $nice_num;?></n>
<p><i class="jinsom-icon jinsom-youzan"></i></p>
</div>
<?php }else{?>
<div class="jinsom-secret-single-nice" onclick="jinsom_like_secret(<?php echo $post_id;?>,this)">
<n><?php echo $nice_num;?></n>
<p><i class="jinsom-icon jinsom-youzan"></i></p>
</div>
<?php }?>

<?php if(jinsom_is_admin($user_id)||$user_id==$author_id){?>
<div class="jinsom-secret-info-box">
<div class="author">
<a href="<?php echo jinsom_mobile_author_url($author_id);?>" class="link">
<?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?><span><?php echo jinsom_nickname($author_id);?></span>
</a>
</div>
<div class="delete" onclick="jinsom_delete_secret(<?php echo $post_id;?>)"><i class="jinsom-icon jinsom-shanchu"></i><span>删除</span></div>
</div>
<?php }?>

<div class="jinsom-secret-comment-box">
<div class="jinsom-secret-comment-btn"><div class="text"><i class="jinsom-icon jinsom-xiaoxizhongxin"></i> 来唠叨几句吧...</div></div>
<div class="jinsom-secret-comment-list">
<?php 
$args = array(
'status'=>'approve',
'no_found_rows' =>false,
'number' => 100,
'post_id' => $post_id
);
$comment_data = get_comments($args);
if (!empty($comment_data) ) { 
foreach ($comment_data as $data) {
$comment_id=$data->comment_ID;
$comment_content=$data->comment_content;
$comment_time = $data->comment_date;
$secret_name=get_comment_meta($comment_id,'secret_name',true);
$secret_avatar=get_comment_meta($comment_id,'secret_avatar',true);
$comment_user_id=$data->user_id;//评论用户id
?>
<li id="jinsom-secret-comment-<?php echo $comment_id;?>">
<div class="left">
<div class="avatarimg"><img src="<?php echo $secret_avatar;?>" class="avatar"></div>
<div class="name"><?php echo $secret_name;?></div>
</div>
<div class="right">
<div class="content">
<a><?php echo convert_smilies($comment_content);?></a>
<div class="after"></div>
</div>
<div class="bar">
<?php if(jinsom_is_admin($user_id)){?>
<span class="author"><?php echo jinsom_nickname_link($comment_user_id);?></span>
<?php }?>
<?php if(jinsom_is_admin($user_id)||$user_id==$author_id||$user_id==$comment_user_id){?>
<span class="delete" onclick="jinsom_secret_comment_delete(<?php echo $comment_id;?>)">删除</span>
<?php }?>	

<span class="time"><?php echo jinsom_timeago($comment_time);?></span>	
</div>
</div>
</li>
<?php }
}

?>



</div>
</div>
<?php }else{
echo jinsom_empty('不存在该数据或已经被删除！');
}?>

</div>
</div>
</div>        