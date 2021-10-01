<?php 
//推荐的论坛
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
?>
<div data-page="bbs-commend" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">推荐<?php echo jinsom_get_option('jinsom_bbs_name');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-bbs-like-content commend">

<?php 
$jinsom_bbs_commend_list=jinsom_get_option('jinsom_bbs_commend_list');
if($jinsom_bbs_commend_list){
$find_bbs_id_arr=explode(",",$jinsom_bbs_commend_list);
echo '<div class="jinsom-chat-user-list bbs-commend list-block">';
foreach ($find_bbs_id_arr as $find_bbs_id) {

echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.jinsom_mobile_bbs_url($find_bbs_id).'" class="link">
'.jinsom_get_bbs_avatar($find_bbs_id,0).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.jinsom_mobile_bbs_url($find_bbs_id).'" class="link">
<div class="name">'.get_category($find_bbs_id)->name.'</div>
<div class="desc">'.strip_tags(get_term_meta($find_bbs_id,'desc',true)).'</div>
</a>
</div>
</div>
'.jinsom_bbs_like_btn($user_id,$find_bbs_id).'
</div>
</li>
';

}
echo '</div>';
}



?>
</div>

</div>
</div>        