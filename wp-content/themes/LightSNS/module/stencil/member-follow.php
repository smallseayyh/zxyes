<?php 
require( '../../../../../wp-load.php' );
$author_id=(int)$_POST['author_id'];
$user_info=get_userdata($author_id);;
$user_id=$current_user->ID;
$bbs_name=jinsom_get_option('jinsom_bbs_name');
$topic_name=jinsom_get_option('jinsom_topic_name');
global $wpdb;
$follow_table_name=$wpdb->prefix.'jin_follow';

$group_im=get_term_meta($bbs_id,'bbs_group_im',true);
?>

<style type="text/css">
.jinsom-follow-page .layui-tab-content {
    padding: 0;
}
.jinsom-follow-page .layui-tab-title {
    display: flex;
}
.jinsom-follow-page .layui-tab-title li {
    flex: 1;
}
.jinsom-follow-page .layui-tab {
    margin-top: 0;
    margin-bottom: 0;
}
.jinsom-member-page-follow-list{
	margin-top:10px;
}
.jinsom-member-page-follow-list li {
    background-color: #fff;
    width: calc((100% - 20px)/3);
    float: left;
    margin-right: 10px;
    margin-bottom: 10px;
    transition: all .3s ease;
    border-radius: var(--jinsom-border-radius);
    position: relative;
    padding: 20px 0 20px 0;
    box-sizing: border-box;
    display: flex;
    border: 1px solid #f1f1f1;
}
.jinsom-member-page-follow-list li:nth-child(3n) {
    margin-right: 0px;
}
.jinsom-member-page-follow-list li:before {
    width: 0;
    height: 100%;
    border-width: 2px 0px 2px 0px;
    top: 0px;
    left: 0px;
    content: '';
    border-style: solid;
    position: absolute;
    box-sizing: border-box;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
}
.jinsom-member-page-follow-list li:after {
    width: 100%;
    height: 0;
    border-width: 0 2px 0 2px;
    top: 0px;
    left: 0px;
    content: '';
    border-style: solid;
    position: absolute;
    box-sizing: border-box;
    -webkit-transition: all 0.3s;
    transition: all 0.3s;
}
.jinsom-member-page-follow-list li:hover:before {
    width: 100%;
}
.jinsom-member-page-follow-list li:hover:after {
    height: 100%;
}
.jinsom-member-page-follow-list li .avatarimg {
    position: relative;
    z-index: 1;
    padding: 10px;
}
.jinsom-member-page-follow-list li .avatarimg a {
    position: relative;
    display: inline-block;
}
.jinsom-member-page-follow-list li .avatarimg img {
    width: 50px;
    height: 50px;
    border-radius: 100%;
    border: 1px solid #f1f1f1;
    box-sizing: border-box;
}
.jinsom-member-page-follow-list li .info {
    width: calc(100% - 80px);
    position: relative;
    z-index: 1;
}
.jinsom-member-page-follow-list li .name {
    height: 20px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}
.jinsom-member-page-follow-list li .mark {
    margin: 5px 0;
    height: 19px;
    overflow: hidden;
}
.jinsom-member-page-follow-list li .mark>span {
    margin-left: 0;
    margin-right: 5px;
    border-radius: 2px;
}
.jinsom-member-page-follow-list li .desc {
    color: #999;
    margin-bottom: 10px;
    font-size: 12px;
    line-height: 16px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    height: 16px;
}
.jinsom-member-page-follow-list li .number {
    font-size: 12px;
    margin: 5px 0;
    color: #666;
}
.jinsom-member-page-follow-list li .number span {
    margin-right: 10px;
}
.jinsom-member-page-follow-list li .number i {
    font-style: normal;
}
.jinsom-member-page-follow-list li:before,.jinsom-member-page-follow-list li:after {
    border-color: var(--jinsom-color);
}
.jinsom-member-page-follow-list li .btn>span,.jinsom-member-page-follow-list li .btn .visit {
    border-radius: 2px;
    font-size: 12px;
    color: #fff;
    padding: 4px 8px;
    cursor: pointer;
    border: none;
    margin-right: 8px;
    float: left;
}
.jinsom-member-page-follow-list li .btn .follow {
    background-color: var(--jinsom-color);
}
.jinsom-member-page-follow-list li .btn .follow.had {
    background-color: #ccc;
}
.jinsom-member-page-follow-list li .btn .chat {
    background-color: #2196f3;
    opacity: 0.6;
}
.jinsom-member-page-follow-list li .btn .chat:hover {
    opacity: 1;
}
.jinsom-member-page-follow-list li .btn .visit {
    background-color: var(--jinsom-color);
    opacity: 0.6;
}
.jinsom-member-page-follow-list li .btn .visit:hover {
    opacity: 1;
}
.jinsom-member-page-follow-list li .btn .visit i {
    vertical-align: -1px;
}
.jinsom-member-page-follow-list li .btn .del {
    background-color: #ccc;
}
.jinsom-member-page-follow-list li .btn .del:hover {
    background-color: #999;
}
.jinsom-member-page-follow-list li .btn .del i {
    font-size: 12px;
}
.jinsom-member-page-follow-list .more {
    text-align: center;
    cursor: pointer;
    padding: 8px 20px;
    border-radius: 2px;
    background-color: #bbb;
    color: #fff;
    width: 120px;
    margin: 20px auto 20px;
    box-sizing: border-box;
    clear: both;
}
</style>

<div class="jinsom-page jinsom-follow-page" author_id="<?php echo $author_id;?>">
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<li class="layui-this">关注</li>
<li>粉丝</li>
<li><?php echo $bbs_name;?></li>
<li><?php echo $topic_name;?></li>
<?php
if($user_id==$author_id||jinsom_is_admin($user_id)){?>
<li>黑名单</li>
<?php }?>
</ul>
<div class="layui-tab-content">

<!-- 关注 -->
<div class="layui-tab-item layui-show">
<div class="jinsom-member-page-follow-list clear">
<?php 
$follow_data=$wpdb->get_results("SELECT * FROM $follow_table_name WHERE follow_user_id = $author_id and follow_status <>0 ORDER BY follow_time DESC limit 30");
if($follow_data){
foreach ($follow_data as $data){
$follow_id=$data->user_id;
$desc=get_user_meta($follow_id,'description',true);
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
echo '<li>
<div class="avatarimg"><a href="'.jinsom_userlink($follow_id).'" target="_blank">'.jinsom_avatar($follow_id,'30',avatar_type($follow_id)).jinsom_verify($follow_id).'</a></div>
<div class="info">
<div class="name">'.jinsom_nickname_link($follow_id).'</div>
<div class="mark">'.jinsom_lv($follow_id).jinsom_vip($follow_id).jinsom_honor($follow_id).'</div>
<div class="desc">'.$desc.'</div>
<div class="btn">
'.jinsom_follow_button_home($follow_id).'
<span onclick="jinsom_open_user_chat('.$follow_id.',this)" class="chat"><i class="jinsom-icon jinsom-liaotian"></i> 聊天</span>
</div>
</div>
</li>';
}	
if(count($follow_data)==30){
echo '<div class="clear"></div><div class="more" type="following" page=2>加载更多</div>';
}
}else{
echo jinsom_empty('没有任何关注');
}
?>

</div>
</div>

<!-- 粉丝 -->
<div class="layui-tab-item">
<div class="jinsom-member-page-follow-list clear">
<?php
$follower_data=$wpdb->get_results("SELECT * FROM $follow_table_name WHERE  user_id='$author_id' AND follow_status IN(1,2)  ORDER BY follow_time DESC limit 30;");
if($follower_data){
foreach ($follower_data as $data) {
$follow_id=$data->follow_user_id;
$desc=get_user_meta($follow_id,'description',true);
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}
echo '<li>
<div class="avatarimg"><a href="'.jinsom_userlink($follow_id).'" target="_blank">'.jinsom_avatar($follow_id,'30',avatar_type($follow_id)).jinsom_verify($follow_id).'</a></div>
<div class="info">
<div class="name">'.jinsom_nickname_link($follow_id).'</div>
<div class="mark">'.jinsom_lv($follow_id).jinsom_vip($follow_id).jinsom_honor($follow_id).'</div>
<div class="desc">'.$desc.'</div>
<div class="btn">
'.jinsom_follow_button_home($follow_id).'
<span onclick="jinsom_open_user_chat('.$follow_id.',this)" class="chat"><i class="jinsom-icon jinsom-liaotian"></i> 聊天</span>
</div>
</div>
</li>';
}
if(count($follower_data)==30){
echo '<div class="clear"></div><div class="more" type="follower" page=2>加载更多</div>';
}
}else{
echo jinsom_empty('没有任何粉丝');
}
?>
</div>
</div>

<!-- 论坛 -->
<div class="layui-tab-item">
<div class="jinsom-member-page-follow-list clear">
<?php 
$follow_bbs_arr=jinsom_get_user_follow_bbs_id($author_id);
if($follow_bbs_arr){
foreach ($follow_bbs_arr as $bbs_id) {
echo '<li>
<div class="avatarimg"><a href="'.get_category_link($bbs_id).'" target="_blank">'.jinsom_get_bbs_avatar($bbs_id,0).'</a></div>
<div class="info">
<div class="name"><a href="'.get_category_link($bbs_id).'" target="_blank">'.get_category($bbs_id)->name.'</a></div>
<div class="number"><span>内容：<i>'.jinsom_get_bbs_post($bbs_id).'</i></span><span>关注：<i>'.jinsom_get_bbs_like_number($bbs_id).'</i></span></div>
<div class="desc">'.strip_tags(get_term_meta($bbs_id,'desc',true)).'</div>
<div class="btn">
'.jinsom_bbs_like_btn($user_id,$bbs_id).'
<a href="'.get_category_link($bbs_id).'" target="_blank" class="visit">访问'.$bbs_name.' <i class="jinsom-icon jinsom-huaban"></i></a>
</div>
</div>
</li>';
}
}else{
echo jinsom_empty('没有关注任何'.$bbs_name);
}
?>
</div>
</div>

<!-- 话题 -->
<div class="layui-tab-item">
<div class="jinsom-member-page-follow-list clear">
<?php 
$follow_follow_arr=jinsom_get_user_follow_topic_id($author_id);
if($follow_follow_arr){
foreach ($follow_follow_arr as $topic_id) {
$topic_data=get_term_by('id',$topic_id,'post_tag');
$desc=get_term_meta($topic_id,'topic_desc',true);
if(!$desc){$desc=jinsom_get_option('jinsom_topic_default_desc');}
echo '<li>
<div class="avatarimg"><a href="'.get_category_link($topic_id).'" target="_blank">'.jinsom_get_bbs_avatar($topic_id,1).'</a></div>
<div class="info">
<div class="name"><a href="'.get_category_link($topic_id).'" target="_blank">#'.$topic_data->name.'#</a></div>
<div class="number"><span>内容：<i>'.$topic_data->count.'</i></span><span>关注：<i>'.jinsom_topic_like_number($topic_id).'</i></span></div>
<div class="desc">'.strip_tags($desc).'</div>
<div class="btn">
'.jinsom_topic_like_btn($user_id,$topic_id).'
<a href="'.get_category_link($topic_id).'" target="_blank" class="visit">访问'.$topic_name.' <i class="jinsom-icon jinsom-huaban"></i></a>
</div>
</div>
</li>';
}
}else{
echo jinsom_empty('没有关注任何'.$topic_name);
}
?>
</div>
</div>


<!-- 黑名单 -->
<?php if($user_id==$author_id||jinsom_is_admin($user_id)){?>
<div class="layui-tab-item">
<div class="jinsom-member-page-follow-list clear">
<?php
$blacklist=get_user_meta($author_id,'blacklist',true);//得到字符串  
$black_arr=explode(",",$blacklist);//转为数组
if($blacklist){
foreach ($black_arr as $blacklist_user_id){
$desc=get_user_meta($blacklist_user_id,'description',true);
if(!$desc){$desc=jinsom_get_option('jinsom_user_default_desc_b');}

echo '<li>
<div class="avatarimg"><a href="'.jinsom_userlink($blacklist_user_id).'" target="_blank">'.jinsom_avatar($blacklist_user_id,'30',avatar_type($blacklist_user_id)).jinsom_verify($blacklist_user_id).'</a></div>
<div class="info">
<div class="name">'.jinsom_nickname_link($blacklist_user_id).'</div>
<div class="mark">'.jinsom_lv($blacklist_user_id).jinsom_vip($blacklist_user_id).jinsom_honor($blacklist_user_id).'</div>
<div class="desc">'.$desc.'</div>
<div class="btn">
<span onclick=\'jinsom_add_blacklist("remove",'.$blacklist_user_id.',this)\' class="del"><i class="jinsom-icon jinsom-guanbi"></i> 取消拉黑</span>
</div>
</div>
</li>';

}
}else{
echo jinsom_empty('没有任何用户');
}

?>
</div>
</div>
<?php }?>

</div>
</div>

</div>



<script type="text/javascript">
$('.jinsom-member-page-follow-list .more').click(function(){
type=$(this).attr('type');
page=parseInt($(this).attr('page'));
user_id=$('.jinsom-member-main').attr('data');
$(this).hide().after(jinsom.loading_post);
obj=$(this);
$.ajax({
type: "POST",
url:  jinsom.mobile_ajax_url+"/user/follower.php",
data: {page:page,user_id:user_id,type:type,number:30},
success: function(msg){
if(msg.code!=0){  
html='';
for (var i = msg.data.length - 1; i >= 0; i--){
html+='\
<li>\
<div class="avatarimg"><a href="'+msg.data[i].link+'" target="_blank">'+msg.data[i].avatar+msg.data[i].verify+'</a></div>\
<div class="info">\
<div class="name">'+msg.data[i].nickname_link+'</div>\
<div class="mark">'+msg.data[i].mark+'</div>\
<div class="desc">'+msg.data[i].desc+'</div>\
<div class="btn">\
'+msg.data[i].follow+'\
<span onclick="jinsom_open_user_chat('+msg.data[i].author_id+',this)" class="chat"><i class="jinsom-icon jinsom-liaotian"></i> 聊天</span>\
</div>\
</div>\
</li>';
}
obj.prev().before(html);
page=page+1;
obj.show().attr('page',page);
}
$('.jinsom-load-post').remove();
}
});
});
</script>

