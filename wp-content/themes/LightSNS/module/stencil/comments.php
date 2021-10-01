<?php 
$comment_post_credit=jinsom_get_option('jinsom_comment_post_credit');
$credit_name=jinsom_get_option('jinsom_credit_name');
?>
<div class="jinsom-comment-form">
<?php if(!is_user_logged_in()){?>
<div class="jinsom-bbs-no-power" style="padding:0;margin-bottom:20px;">
<div class="tips"><p><?php _e('请登录之后再进行评论','jinsom');?></p><div class="btn opacity" onclick="jinsom_pop_login_style()"><?php _e('登录','jinsom');?></div></div>
</div>
<?php }else{?>
<div class="jinsom-comment-textarea clear">
<textarea id="jinsom-comment-form-<?php echo $post_id;?>" class="jinsom-post-comments"></textarea>
<?php if(comments_open()||(!comments_open()&&(jinsom_is_admin($user_id)||$user_id==$author_id))||get_post_type($post_id)=='goods'){?>

<span class="jinsom-single-expression-btn" onclick="jinsom_smile(this,'normal','')">
<i class="jinsom-icon expression jinsom-weixiao-"></i>
</span>
<?php if($author_id!=$user_id&&get_post_type($post_id)=='post'){?>
<span class="redbag" onclick="jinsom_reward_form(<?php echo $post_id;?>,'post');"><i class="jinsom-icon jinsom-hongbao"></i></span>

<?php if(jinsom_get_option('jinsom_gift_on_off')&&jinsom_get_option('jinsom_gift_add')){?>
<span class="gift" onclick="jinsom_send_gift_form(<?php echo $author_id;?>,<?php echo $post_id;?>);"><i class="jinsom-icon jinsom-liwu1"></i></span>
<?php }?>

<?php }?>
<!-- <span id="jinsom-words-comment-upload"><i class="jinsom-icon jinsom-tupian2"></i></span> -->



<?php if($jinsom_machine_verify_on_off&&in_array("comment",$jinsom_machine_verify_use_for)&&!jinsom_is_admin($user_id)){?>
<div class="jinsom-comments-btn opacity" id="comment-<?php echo $post_id;?>"><?php _e('回复','jinsom');?><?php if($comment_post_credit<0){echo '（'.$comment_post_credit.$credit_name.'）';}?></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('comment-<?php echo $post_id;?>'),'<?php echo $jinsom_machine_verify_appid;?>',function(res){
if(res.ret === 0){jinsom_comment_posts(<?php echo $post_id;?>,$('#comment-<?php echo $post_id;?>'),res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<div class="jinsom-comments-btn opacity"  onclick='jinsom_comment_posts(<?php echo $post_id;?>,this,"","");'><?php _e('回复','jinsom');?><?php if($comment_post_credit<0){echo '（'.$comment_post_credit.$credit_name.'）';}?></div>
<?php }?>


<?php }else{?>
<div class="jinsom-stop-comment-tips"><?php _e('该内容已关闭回复','jinsom');?></div>
<?php }?>
</div>   
<?php }?>
 <div class="jinsom-post-comment-list">
<?php 


$comment_not_arr=array();
$current_comment_id='';


//回复内容里面的 把当前评论置顶
if(isset($_GET['comment_id'])&&is_numeric($_GET['comment_id'])){
$current_comment_id=(int)$_GET['comment_id'];
array_push($comment_not_arr,$current_comment_id);
$comments=get_comments('comment__in='.$current_comment_id);
foreach ($comments as $comment_datas) {
require('comments-templates.php');//评论模版
}
}


$comment_up_id=(int)get_post_meta($post_id,'up-comment',true);//置顶的评论ID
if($comment_up_id&&$comment_up_id!=$current_comment_id){
array_push($comment_not_arr,$comment_up_id);
$comments=get_comments('comment__in='.$comment_up_id);
foreach ($comments as $comment_datas) {
require('comments-templates.php');//评论模版
}
}



if(is_single()||is_page()){
$nums=20;
}else{
$nums=3 ; 
}
$com_count_a = get_comments_number($post_id);//评论总数
$com_count_b=$com_count_a;

$args = array(
'status'=>'approve',
'type' => 'comment',
'karma'=>0,
'comment__not_in'=>$comment_not_arr,
'no_found_rows' =>false,
'number' => $nums,
'post_id' => $post_id
);
$comment_data = get_comments($args);
if (!empty($comment_data) ) { 
foreach ($comment_data as $comment_datas) {
require('comments-templates.php');//评论模版
}
}
 ?>
</div>
<?php

if((is_single()||is_page())&&$com_count_b>20){
echo '<div class="jinsom-post-comment-more" page="2" onclick="jinsom_more_comment('.$post_id.',this)">'.__('加载更多评论','jinsom').'</div>';	
}


if(!is_single()&&!is_page()&&$com_count_b>3){ ?>
<div class="jinsom-more-comment"><a href="<?php echo $permalink; ?>" target="_blank"><?php _e('查看更多','jinsom');?></a></div>
<?php } ?>

</div>
