<?php 
require( '../../../../../../wp-load.php');
$post_id=(int)$_GET['post_id'];
$type=strip_tags($_GET['type']);
if($type=='post'){
$author_id=jinsom_get_user_id_post($post_id);
}else if($type=='live'){
$live_data=get_post_meta($post_id,'video_live_page_data',true);
$author_id=$live_data['jinsom_video_live_user_id'];
}else{
$author_id=jinsom_get_comments_author_id($post_id);	
}
$jinsom_reward_number_add = jinsom_get_option('jinsom_reward_number_add');
?>
<div data-page="reward" class="page no-tabbar setting">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('打赏','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-reward-content">
<div class="jinsom-reward-bg"></div>
<div class="avatarimg"><?php echo jinsom_avatar($author_id,'40',avatar_type($author_id));?></div>
<div class="name"><?php echo jinsom_nickname($author_id);?></div>
<div class="number clear">
<?php if($jinsom_reward_number_add){
foreach ($jinsom_reward_number_add as $data) {
echo '<li onclick=\'jinsom_reward('.$post_id.',"'.$type.'",this)\' data="'.$data['number'].'">'.$data['number'].'<span class="jinsom-icon jinsom-jinbi"></span></li>';
}
}?>
</div>
</div>
</div>        