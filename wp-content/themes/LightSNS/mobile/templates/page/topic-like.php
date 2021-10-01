<?php 
//我的关注的话题
require( '../../../../../../wp-load.php');
$user_id=$current_user->ID;
$theme_url=get_template_directory_uri();
?>
<div data-page="topic-like" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">关注的<?php echo jinsom_get_option('jinsom_topic_name');?></div>
<div class="right">
<a href="<?php echo $theme_url;?>/mobile/templates/page/topic-rank.php" class="link icon-only"><?php _e('热门','jinsom');?></a>
</div>
</div>
</div>

<div class="page-content jinsom-bbs-like-content">

<?php 
global $wpdb;
$table_name = $wpdb->prefix . 'jin_topic_like';
$bbs_data = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id ORDER BY ID DESC limit 100;");
if($bbs_data){
echo '<div class="jinsom-bbs-like">';
foreach ($bbs_data as $data) {
$topic_id=$data->topic_id;
$topic_data=get_term_by('id',$topic_id,'post_tag');
$topic_name=$topic_data->name;

echo '
<li>
<a href="'.jinsom_mobile_topic_id_url($topic_id).'" class="link">
<div class="img">
'.jinsom_get_bbs_avatar($topic_id,1).'
</div>
<div class="name">'.$topic_name.'</div>
</a>
</li>';


}
echo '</div>';
}else{
echo jinsom_empty();
}


?>
</div>



</div>
</div>        