<?php
//打赏界面
require( '../../../../../wp-load.php' );
$reward_min=(int)jinsom_get_option('jinsom_reward_rand_min');
$reward_max=(int)jinsom_get_option('jinsom_reward_rand_max');
$reward_number=rand($reward_min,$reward_max);
if(isset($_POST['post_id'])){
$post_id=(int)$_POST['post_id'];
$type=strip_tags($_POST['type']);
if($type=='post'){
$author_id=jinsom_get_user_id_post($post_id);
}else if($type=='live'){
$live_data=get_post_meta($post_id,'video_live_page_data',true);
$author_id=$live_data['jinsom_video_live_user_id'];
}else{
$author_id=jinsom_get_comments_author_id($post_id);	
}
?>
<div class="jinsom-reward-content">
<div class="jinsom-reward-close"><i class="jinsom-icon jinsom-guanbi"></i></div>
<div class="jinsom-reward-avatar"><?php echo jinsom_avatar($author_id, '40' , avatar_type($author_id) );?></div>
<div class="jinsom-reward-name"><?php echo get_user_meta($author_id,'nickname',true);?></div>
<div class="jinsom-reward-money">
<span>
<input type="hidden" id="jinsom-reward-number" value="<?php echo $reward_number;?>">
<m><?php echo $reward_number;?></m>
</span>
<i><?php echo jinsom_get_option('jinsom_credit_name');?></i>
</div>
<div class="jinsom-reward-edior" onclick="jinsom_reward_edior(<?php echo $reward_number;?>);"><?php _e('修改金额','jinsom');?></div>
<div class="jinsom-reward-btn" onclick="jinsom_reward(<?php echo $post_id;?>,'<?php echo $type;?>');"><?php _e('打赏','jinsom');?></div>
</div>
<?php }
