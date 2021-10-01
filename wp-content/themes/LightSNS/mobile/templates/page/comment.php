<?php 
require( '../../../../../../wp-load.php');
$post_id=$_GET['post_id'];
$user_id=$current_user->ID;
if(isset($_GET['comment_see'])){
$reload=1;
}else{
$reload=0;
}
?>
<div data-page="comment-post" class="page no-tabbar comment-post">

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
<a class="link icon-only" id="comment-1" reload=<?php echo $reload;?>><?php _e('回复','jinsom');?></a>
<?php }else{?>
<a class="link icon-only" onclick="jinsom_comment(<?php echo $post_id;?>,<?php echo $reload;?>,'','')"><?php _e('回复','jinsom');?></a>
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
<!-- <input id="jinsom-aite-user-input" type="text" placeholder="输入要@的好友昵称" oninput="jinsom_pop_aite_user_search()" maxlength="15"> -->
<div class="list aite">
</div>
</div>
</div>



</div>       