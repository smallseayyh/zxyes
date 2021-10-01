<?php 
require( '../../../../../../wp-load.php');
$post_id=$_GET['post_id'];
$bbs_id=$_GET['bbs_id'];
$user_id=$current_user->ID;
?>
<div data-page="comment-bbs-post" class="page no-tabbar comment-bbs-post">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('回复内容','jinsom');?></div>
<div class="right">

<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("comment",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<a class="link icon-only" id="comment-2"><?php _e('回复','jinsom');?></a>
<?php }else{?>
<a class="link icon-only" onclick="jinsom_bbs_comment(<?php echo $post_id;?>,<?php echo $bbs_id;?>,'','')"><?php _e('回复','jinsom');?></a>
<?php }?>

</div>
</div>
</div>

<div class="page-content jinsom-comment-content" style="background: #fff;" id="jinsom-comment-page">

<?php
$jinsom_quick_reoly = jinsom_get_option('jinsom_bbs_quick_reply_add');
if(!empty($jinsom_quick_reoly)){
echo '<div class="jinsom-bbs-comment-quick clear">';
foreach ($jinsom_quick_reoly as $data) {
echo '<li class="opacity" onclick=\'jinsom_quick_reply("'.$data['content'].'",this)\'>'.$data['title'].'</li>';
}
echo '</div>';
}
?>
<div class="jinsom-comment-content-main">
<textarea class="resizable jinsom-smile-textarea" id="jinsom-comment-content-<?php echo $post_id;?>" placeholder="<?php _e('随便说点什么吧','jinsom');?>"></textarea>
<i class="jinsom-icon jinsom-aite2 aite open-popup" data-popup=".jinsom-publish-aite-popup"></i>
<i onclick="jinsom_smile_form(this)" class="smile jinsom-icon expression jinsom-weixiao-"></i>
<div class="hidden-smile" style="display: none;"><?php echo jinsom_get_expression(2,0);?></div>
</div>
<div class="jinsom-comment-image jinsom-publish-words-form">
<div class="images clear">
<ul id="jinsom-publish-images-list"></ul>
<div class="add"><i class="jinsom-icon jinsom-tupian2"></i><span class="preloader preloader-white"></span><input id="file" type="file" accept="image/*" multiple="multiple"></div>
</div>
</div>
<?php 
$comment_time=(int)get_term_meta($bbs_id,'bbs_last_reply_time',true);
$post_time=get_the_time('Y-m-d H:i:s',$post_id);
$time_a=(time()-strtotime($post_time))/86400;
if($time_a>$comment_time&&!jinsom_is_admin($user_id)){
echo '<div class="jinsom-mobile-bbs-comment-tips">'.sprintf(__( '该内容已超过%s天，不允许再进行回复！','jinsom'),$comment_time).'</div>';
}
?>

</div>

<!-- @列表 -->
<div class="popup jinsom-publish-aite-popup">
<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="link icon-only close-popup"><i class="jinsom-icon jinsom-xiangxia2"></i></a>
</div>
<div class="center">@好友</div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
<div class="subnavbar">
<input type="text" placeholder="输入要@的用户昵称" oninput="jinsom_pop_aite_user_search()" maxlength="15" id="jinsom-aite-user-input">
</div>
</div>
</div>
<div class="page-content jinsom-publish-aite-form" style="padding-top: 12vw;">
<div class="list aite">
</div>
</div>
</div>

</div>        