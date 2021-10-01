<?php 
$topic_data=wp_get_post_tags($post_id);
if($topic_data){
echo '<div class="jinsom-single-topic-list clear">';
foreach($topic_data as $topic_datas){
$topic_id=$topic_datas->term_id;
echo '<li><a href="'.jinsom_mobile_topic_id_url($topic_id).'" class="link">'.jinsom_get_bbs_avatar($topic_id,1).'<span>'.$topic_datas->name.'</span></a></li>';
}
echo '</div>';
}
?>