<?php 
//黑名单
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$blacklist=get_user_meta($user_id,'blacklist',true);//得到字符串  
$black_arr=explode(",",$blacklist);//转为数组
?>
<div data-page="follower" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('黑名单','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-follower-content">
<div class="jinsom-chat-user-list follower blacklist list-block">
<?php 
if($blacklist){
foreach ($black_arr as $data) {
$desc=get_user_meta($data,'description',true);
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
echo '
<li>
<div class="item-content">
<div class="item-media">
<a href="'.jinsom_mobile_author_url($data).'" class="link">
'.jinsom_avatar($data,'40',avatar_type($data)).jinsom_verify($data).'
</a>
</div>
<div class="item-inner">
<div class="item-title">
<a href="'.jinsom_mobile_author_url($data).'" class="link">
<div class="name">'.jinsom_nickname($data).jinsom_vip($data).'</div>
<div class="desc">'.$desc.'</div>
</a>
</div>
</div>
<div onclick=\'jinsom_add_blacklist("remove",'.$data.',this)\' class="remove-blacklist item-after follow had">取消拉黑</div>
</div>
</li>
';


}

}else{
echo jinsom_empty(__('没有任何用户','jinsom'));
}

?>
</div>

</div>
</div>        