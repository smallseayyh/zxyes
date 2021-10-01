<?php
/*
Template Name:直播
*/
$post_id=get_the_ID();
$user_id=$current_user->ID;
$post_views=(int)get_post_meta($post_id,'post_views',true);//浏览量
update_post_meta($post_id,'post_views',$post_views+1);//更新内容浏览量

$live_data=get_post_meta($post_id,'video_live_page_data',true);
$live_url=$live_data['jinsom_video_live_url'];//直播地址
$live_img=$live_data['jinsom_video_live_img'];//直播封面
$live_user_id=$live_data['jinsom_video_live_user_id'];//直播用户ID
$live_views_number=(int)$live_data['jinsom_video_live_views_number'];//直播观看人数
$live_images_upload_on_off=$live_data['jinsom_video_live_images_upload_on_off'];//直播互动评论是否允许上传图片
$live_reward_on_off=$live_data['jinsom_video_live_reward_on_off'];
$post_views=$post_views*$live_views_number;//最终观看人数
$jinsom_video_live_jingcai_add=$live_data['jinsom_video_live_jingcai_add'];//精彩瞬间
$comment_number=get_comments_number($post_id);

$extend = pathinfo($live_url);
$extend = strtolower($extend["extension"]);//文件后缀
if($extend=='m3u8'){
$player='HlsJsPlayer';
}else if($extend=='flv'){
$player='FlvJsPlayer';
}else{
$player='Player';	
}

if(wp_is_mobile()){
require(get_template_directory().'/mobile/index.php');	
}else{
get_header();







?>
<style type="text/css">
#jinsom-plugin-barrage {
    display: none;
}
.jinsom-video-live-content {
    background-color: #202223;
}
.jinsom-main-content.live {
    width: 1200px;
    padding-top: 30px;
}
.jinsom-live-page-header {
    display: flex;
}
.jinsom-live-page-header>.left {
    width: 70%;
    margin-right: 10px;
    display: flex;
    flex-flow: column;
}
.jinsom-live-page-header>.right {
    flex: 1;
    border-radius: var(--jinsom-border-radius);
    display: flex;
    flex-flow: column;
}
.jinsom-live-page-header>.left .xgplayer {
    flex: 1;
    width: 100% !important;
}
.jinsom-live-page-header .xgplayer-poster,.jinsom-live-page-header video,.jinsom-live-page-header .xgplayer,.jinsom-live-page-header .xgplayer-controls,.jinsom-live-page-header .xgplayer-replay{
	border-radius: var(--jinsom-border-radius);
}
.jinsom-live-page-header>.left .bottom {
    background-color: #333;
    margin-top: 10px;
    padding: 10px;
    border-radius: var(--jinsom-border-radius);
    color: #999;
    box-sizing: border-box;
    position: relative;
}
.jinsom-live-page-header>.left .bottom .toolbar span {
    margin-right: 15px;
    cursor: pointer;
    font-size: 16px;
}
.jinsom-live-page-header>.right>.header {
    text-align: center;
    padding: 10px;
    font-size: 16px;
    color: #999;
    border-radius: var(--jinsom-border-radius);
    background-color: #333;
    margin-bottom: 10px;
}
.jinsom-live-page-header>.right>.content {
    flex: 1;
    background-color: #333;
    border-radius: var(--jinsom-border-radius);
    padding: 10px 0px 10px 10px;
}
.jinsom-live-page-header>.right .footer {
    background-color: #333;
    margin-top: 10px;
    border-radius: var(--jinsom-border-radius);
    padding: 10px;
}
.jinsom-live-page-header>.right .footer textarea {
    width: 100%;
    padding: 10px;
    box-sizing: border-box;
    background-color: #222;
    border: none;
    border-radius: var(--jinsom-border-radius);
    height: 80px;
    color: #999;
}
.jinsom-live-page-header>.right .footer .toolbar {
    margin: 0;
    margin-top: 10px;
    text-align: right;
    height: 22px;
    float: inherit;
    cursor: default;
}
.jinsom-live-page-header>.right .footer .toolbar .jinsom-smile-form {
    right: 25px;
    left: inherit;
    text-align: left;
}
.jinsom-live-page-header>.right .footer .toolbar>span {
    margin-left: 15px;
    position: relative;
    cursor: pointer;
}
.jinsom-live-page-header>.right .footer .toolbar .images i {
    font-size: 18px;
    vertical-align: -2px;
    color: #999;
}
.jinsom-live-page-header>.right .footer .toolbar .smile i {
    font-size: 20px;
    vertical-align: -4px;
    color: #999;
}
.jinsom-live-page-header>.right .footer .toolbar>span.redbag i {
    font-size: 20px;
    vertical-align: -4px;
    color: #999;
}
.jinsom-live-page-header>.right .footer .toolbar span:hover i {
    color: #fff;
}
.jinsom-live-page-header>.right .footer .toolbar .btn {
    background-color: #2196F3;
    color: #fff;
    font-size: 12px;
    padding: 4px 12px;
    cursor: pointer;
    border-radius: 2px;
}
.jinsom-live-page-header>.left .bottom .title {
    font-size: 22px;
    color: #f1f1f1;
    margin-bottom: 10px;
}
.jinsom-live-page-header>.left .bottom .title i {
    font-size: 24px;
    margin-right: 3px;
}
.jinsom-live-page-header>.left .bottom .userinfo {
    position: absolute;
    right: 20px;
    top: 20px;
    display: flex;
    align-items: center;
}
.jinsom-live-page-header>.left .bottom .userinfo .avatarimg img {
    border-radius: 100%;
    margin-right: 5px;
    width: 38px;
    height: 38px;
}
.jinsom-live-page-header>.left .bottom .userinfo .nickname a {
    color: #999;
}
.jinsom-live-page-header>.left .bottom .userinfo .follow-btn>span {
    background-color: #2196F3;
    color: #fff;
    font-size: 12px;
    padding: 4px 10px;
    cursor: pointer;
    border-radius: 2px;
    margin-left: 15px;
}
.jinsom-live-page-header>.left .bottom .userinfo .follow-btn>span.has {
    background-color: #666;
}


.jinsom-live-comment-list {
    height: 412px;
    overflow-y: auto;
    padding-right: 10px;
    overflow-x: hidden;
}
.jinsom-live-comment-list li {
    display: flex;
    margin-bottom: 20px;
}
.jinsom-live-comment-list li>.left {
    margin-right: 10px;
}
.jinsom-live-comment-list li>.left>a {
    display: inline-block;
    width: 40px;
    height: 40px;
    position: relative;
}
.jinsom-live-comment-list li .left img {
    width: 40px;
    height: 40px;
    border-radius: 100%;
}
.jinsom-live-comment-list li>.right .name {
    color: #999;
    margin-bottom: 10px;
}
.jinsom-live-comment-list li>.right .content {
    display: inline-block;
    background-color: #414141;
    padding: 6px 8px;
    color: #ccc;
    line-height: 24px;
    border-radius: 4px;
    word-break: break-all;
}
.jinsom-live-comment-list li>.right .content img {
    max-width: 100%;
    border-radius: var(--jinsom-border-radius);
}
.jinsom-live-comment-list::-webkit-scrollbar {
    width: 3px;
    background-color: #f00;
}
.jinsom-live-comment-list::-webkit-scrollbar-thumb {
    border-radius: 0;
    background-color: #2196F3;
}


.jinsom-live-page-content {
    margin-top: 10px;
    background-color: #333;
    border-radius: var(--jinsom-border-radius);
    padding: 20px;
}
.jinsom-live-page-content .header {
    text-align: center;
    display: flex;
    justify-content: center;
}
.jinsom-live-page-content .header li {
    margin: 0 40px;
    font-size: 20px;
    color: #ddd;
    position: relative;
    cursor: pointer;
}
.jinsom-live-page-content .content {
    background-color: #222;
    border-radius: var(--jinsom-border-radius);
    padding: 20px;
    margin-top: 30px;
    color: #999;
    min-height: 300px;
}
.jinsom-live-page-content .content ul {
    display: none;
}
.jinsom-live-page-content .content ul:first-child {
    display: block;
}
.jinsom-live-page-content .header li.on:after,.jinsom-live-page-content .header li:hover:after {
    content: '';
    width: 40px;
    height: 3px;
    background-color: #2196F3;
    display: block;
    margin-top: 6px;
    border-radius: 10px;
    margin-left: -20px;
    position: absolute;
    left: 50%;
}
.jinsom-no-video-live {
    flex: 1;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    font-size: 20px;
    color: #999;
    background-color: #000;
    border-radius: var(--jinsom-border-radius);
}
.jinsom-no-video-live i {
    font-size: 50px;
    margin-right: 10px;
}
.jinsom-live-page-content .content ul.jingcai li {
    float: left;
    width: calc((100% - 40px)/5);
    margin-bottom: 10px;
    margin-right: 10px;
    cursor: pointer;
    border: 1px solid rgba(33, 150, 243, 0);
    border-radius: 4px 4px 0 0;
    box-sizing: border-box;
}
.jinsom-live-page-content .content ul.jingcai li.on {
    border: 1px solid #2196F3;
}
.jinsom-live-page-content .content ul.jingcai li img {
    width: 100%;
    height: 150px;
    border-radius: 4px 4px 0 0;
    object-fit: cover;
}
.jinsom-live-page-content .content ul.jingcai li p {
    padding: 5px;
    box-sizing: border-box;
    width: 100%;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}
.jinsom-live-page-content .content ul.jingcai li:nth-child(5n) {
    margin-right: 0;
}
.jinsom-live-page-header>.left .reward-list {
    margin-bottom: 10px;
}
.jinsom-live-page-header>.left .reward-list .title {
    height: 46px;
    display: flex;
    align-items: center;
    font-size: 20px;
    margin-right: 15px;
    color: #FFC107;
    font-weight: bold;
}
.jinsom-live-page-header>.left .reward-list li {
    float: left;
    margin-right: 15px;
    margin-bottom: 10px;
    text-align: center;
    position: relative;
}
.jinsom-live-page-header>.left .reward-list li img {
    width: 46px;
    height: 46px;
    border-radius: 100%;
    border: 1px solid #fff;
    box-sizing: border-box;
}
.jinsom-live-page-header>.left .reward-list li p {
    color: #fff;
    font-size: 12px;
    position: absolute;
    bottom: -5px;
    background-color: #2196f3;
    border-radius: 20px;
    text-align: center;
    margin: auto;
    width: 74%;
    left: 50%;
    margin-left: -37%;
    line-height: initial;
}
.jinsom-live-page-header>.left .reward-list li:hover img {
    border-color: #2196F3;
}
.jinsom-live-page-header>.left .reward-list li:nth-child(13) {
    margin-right: 0;
}
</style>

<div class="jinsom-video-live-content" count="<?php echo $comment_number;?>">
<div class="jinsom-main-content live">
<div class="jinsom-live-page-header">
<div class="left">
<div class="reward-list clear">
<li class="title"><?php _e('打赏榜','jinsom');?></li>
<?php 
global $wpdb;
$table_name=$wpdb->prefix.'jin_notice';
$datas=$wpdb->get_results("SELECT user_id,sum(remark) as reward_number FROM $table_name WHERE post_id='$post_id' GROUP BY user_id order by reward_number DESC limit 12");
if($datas){
foreach ($datas as $data){
$reward_user_id=$data->user_id;
echo '<li><a href="'.jinsom_userlink($reward_user_id).'" target="_blank">'.jinsom_avatar($reward_user_id,'40',avatar_type($reward_user_id)).'</a><p>'.jinsom_views_show($data->reward_number).'</p></li>';
}
}

?>
</div>

<?php if($live_url){?>
<div id="jinsom-video-live"></div>
<?php }else{?>
<div class="jinsom-no-video-live"><i class="jinsom-icon jinsom-huabanfuben"></i> <?php _e('直播还没有开始，请耐心等待~','jinsom');?></div>
<?php }?>
<div class="bottom">
<div class="title"><i class="jinsom-icon jinsom-shipin1"></i> <?php the_title();?></div>
<div class="toolbar">
<span><i class="jinsom-icon jinsom-my_light"></i> <?php echo sprintf(__( '%s人气','jinsom'),jinsom_views_show($post_views));?></span>
<span onclick="jinsom_popop_share_code('<?php echo home_url(add_query_arg(array()));?>','<?php _e('手机观看','jinsom');?>','')"><i class="jinsom-icon jinsom-erweima"></i> <?php _e('手机观看','jinsom');?></span>
</div>
<div class="userinfo">
<span class="avatarimg"><?php echo jinsom_avatar($live_user_id,'40',avatar_type($live_user_id));?></span>
<span class="nickname"><?php echo jinsom_nickname_link($live_user_id);?></span>
<span class="follow-btn"><?php echo jinsom_follow_button_home($live_user_id);?></span>
</div>
</div>
</div>
<div class="right">
<div class="header"><?php _e('评论互动','jinsom');?><span> (<?php echo $comment_number;?><?php _e('人','jinsom');?>)</span></div>
<div class="content">
<div class="jinsom-live-comment-list">	

<?php 
$args = array(
'status'=>'approve',
'type' => 'comment',
'karma'=>0,
'no_found_rows' =>false,
'number' =>150,
'orderby' => 'comment_date',
'post_id' => $post_id
);
$comment_data = get_comments($args);
if(!empty($comment_data)){ 
foreach (array_reverse($comment_data) as $comment_datas) {
$comment_id=$comment_datas->comment_ID;
$comment_user_id = $comment_datas->user_id;
$comment_content = $comment_datas->comment_content;
echo '
<li>
<div class="left"><a href="'.jinsom_userlink($comment_user_id).'" target="_blank">'.jinsom_avatar($comment_user_id,'40',avatar_type($comment_user_id)).jinsom_verify($comment_user_id).'</a></div>
<div class="right">
<div class="name">'.jinsom_nickname($comment_user_id).jinsom_lv($comment_user_id).jinsom_vip($comment_user_id).jinsom_honor($comment_user_id).'</div>
<div class="content">'.convert_smilies($comment_content).'</div>
</div>
</li>
';
}
}else{
echo jinsom_empty(__('还没有人进行互动','jinsom'));	
}
?>
</div>
</div>
<div class="footer">
<textarea id="jinsom-live-comment-content" placeholder="<?php echo $live_data['jinsom_video_live_comment_placeholder'];?>"></textarea>	
<div class="toolbar jinsom-single-expression-btn">
<?php if($live_images_upload_on_off){?>
<span class="images" id="jinsom-live-commnet-images"><i class="jinsom-icon jinsom-tupian2"></i></span>
<?php }?>
<?php if($live_reward_on_off){?>
<span class="redbag" onclick="jinsom_reward_form(<?php echo $post_id;?>,'live');"><i class="jinsom-icon jinsom-hongbao"></i></span>
<?php }?>
<span class="smile" onclick="jinsom_smile(this,'normal','')"><i class="jinsom-icon jinsom-weixiao-"></i></span>
<span class="btn opacity" onclick="jinsom_comment_live(<?php echo $post_id;?>)"><?php _e('发送','jinsom');?></span>
</div>
</div>
</div>

</div>

<?php echo do_shortcode($live_data['jinsom_video_live_title_bottom_html']);?>

<div class="jinsom-live-page-content">
<div class="header">
<li class="on"><?php echo $live_data['jinsom_video_live_tab_desc_name'];?></li>
<?php if($jinsom_video_live_jingcai_add){?>
<li><?php echo $live_data['jinsom_video_live_tab_jingcai_name'];?></li>
<?php }?>
</div>	
<div class="content">
<ul>
<?php 
while(have_posts()):the_post();
the_content();
echo '<div class="clear"></div>';
endwhile;
?>
</ul>
<?php if($jinsom_video_live_jingcai_add){?>
<ul class="jingcai clear">
<?php 
foreach ($jinsom_video_live_jingcai_add as $data){
echo '<li onclick=\'jinsom_live_jingcai_video_play("'.$data['url'].'",this)\'><img src="'.$data['cover'].'" class="opacity"><p>'.$data['title'].'</p></li>';
}
?>


</ul>
<?php }?>
</div>
</div>





</div>
</div>

<script>
$('.jinsom-live-comment-list').scrollTop($('.jinsom-live-comment-list')[0].scrollHeight);//互动评论向下啦

<?php if($live_url){?>
window.player = new <?php echo $player;?>({
id: "jinsom-video-live",
url: "<?php echo $live_url;?>",
poster: "<?php echo $live_img;?>",
playsinline: true,
fluid: true,
danmu: {
area: {
start:0, //区域顶部到播放器顶部所占播放器高度的比例
end:1//区域底部到播放器顶部所占播放器高度的比例
},
closeDefaultBtn: false,
defaultOff:false
}
});
<?php }?>

//菜单
$('.jinsom-live-page-content .header li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$(this).parent().next().children('ul').eq($(this).index()).show().siblings().hide();
});


//评论互动
function jinsom_comment_live(post_id){
content=$.trim($('#jinsom-live-comment-content').val());
layer.load(1);
$.ajax({
type:"POST",
dataType:'json',
url:jinsom.jinsom_ajax_url+"/action/comment-live.php",
data: {content:content,post_id:post_id},
success: function(msg) {
layer.closeAll('loading');
if(msg.code==1){//成功
$('#jinsom-live-comment-content').val('');
comment_html='\
<li>\
<div class="left">'+msg.avatar+'</div>\
<div class="right">\
<div class="name">'+msg.nickname+'</div>\
<div class="content">'+msg.content+'</div>\
</div>\
</li>\
';
if($('.jinsom-live-comment-list .jinsom-empty-page').length>0){
$('.jinsom-live-comment-list').html(comment_html);
}else{
$('.jinsom-live-comment-list').append(comment_html);
}
$('.jinsom-live-comment-list').scrollTop($('.jinsom-live-comment-list')[0].scrollHeight);
count=parseInt($('.jinsom-video-live-content').attr('count'));
$('.jinsom-video-live-content').attr('count',count+1);
if(ajax_get_live_comment){ajax_get_live_comment.abort();}
jinsom_ajax_get_live_comment();

if(msg.content_a){
window.player.danmu.sendComment({//发送弹幕
duration: 15000,
id: msg.id,
start: 0,
txt: msg.content_a,
style: {
color: '#fff',
fontSize: '18px',
border: 'solid 1px #fff',
borderRadius: '50px',
padding: '5px 11px',
backgroundColor: 'rgba(255, 255, 255, 0.1)'
}
});
}

}else if(msg.code==2){//没有绑定手机号
layer.msg(msg.msg);
function d(){jinsom_update_phone_form(msg.user_id);}setTimeout(d,2000);
}else{
layer.msg(msg.msg);
}
},
});
}


// 回车搜索
$("#jinsom-live-comment-content").keypress(function(e){  
if(e.which == 13){  
e.cancelBubble=true;
e.preventDefault();
e.stopPropagation();
jinsom_comment_live(<?php echo $post_id;?>);
}  
}); 


//上传
layui.use(['upload'], function(){
var upload = layui.upload;

//文章上传图片
upload.render({
elem:'#jinsom-live-commnet-images',
url: jinsom.jinsom_ajax_url+'/upload/live-comment.php',
multiple:true,
accept:'file',
data:{
post_id:<?php echo $post_id;?>
},
before: function(obj){
$('#jinsom-live-commnet-images').html('<i class="fa fa-spin fa-refresh">');
},
done:function(res,index,upload){
if(res.code == 1){
$('.jinsom-live-comment-list').append('\
<li>\
<div class="left">'+res.avatar+'</div>\
<div class="right">\
<div class="name">'+res.nickname+'</div>\
<div class="content"><a href="'+res.file_url+'" data-fancybox="gallery" data-no-instant><img src="'+res.file_url+'"></div>\
</div>\
</li>\
');
$('.jinsom-live-comment-list').scrollTop($('.jinsom-live-comment-list')[0].scrollHeight);
// $(".jinsom-live-comment-list li>.right .content").on('load',function(){
// $('.jinsom-live-comment-list').scrollTop($('.jinsom-live-comment-list')[0].scrollHeight);
// });
count=parseInt($('.jinsom-video-live-content').attr('count'));
$('.jinsom-video-live-content').attr('count',count+1);
if(ajax_get_live_comment){ajax_get_live_comment.abort();}
jinsom_ajax_get_live_comment();

}else{
layer.msg(res.msg);	
}
},
allDone: function(obj){
$('#jinsom-live-commnet-images').html('<i class="jinsom-icon jinsom-tupian2"></i>');
},
error: function(index, upload){
layer.msg('上传失败！');
$('#jinsom-live-commnet-images').html('<i class="jinsom-icon jinsom-tupian2"></i>');
}
});
});

//精彩视频切换
function jinsom_live_jingcai_video_play(url,obj){
$(obj).addClass('on').siblings().removeClass('on');
if($('.jinsom-no-video-live').length>0){
$('.jinsom-no-video-live').before('<div id="jinsom-video-live"></div>');
$('.jinsom-no-video-live').remove();
}
$('#jinsom-video-live').empty();
video_type=jinsom_video_type(url);
new window[video_type]({
id: 'jinsom-video-live',
url: url,
autoplay: true,
playsinline: true,
fluid: true,
});
}

//实时获取弹幕
function jinsom_ajax_get_live_comment(){
count=parseInt($('.jinsom-video-live-content').attr('count'));
ajax_get_live_comment=$.ajax({
type: "POST",
url:jinsom.module_url+"/action/live-comment-ajax.php",
timeout:30000,
dataType:'json',
data: {count:count,post_id:<?php echo $post_id;?>},
success: function(msg){
if(msg.code==2){
$('.jinsom-live-comment-list').append(msg.msg);
$('.jinsom-live-comment-list').scrollTop($('.jinsom-live-comment-list')[0].scrollHeight);
$('.jinsom-video-live-content').attr('count',msg.count);

// console.log(msg.danmu[0]);
for (var i = 0; i < msg.danmu.length; i++) {
player.danmu.sendComment({//发送弹幕
duration: 15000,
id: msg.id,
start: 0,
txt: msg.danmu[i],
style: {
color: '#fff',
fontSize: '18px',
border: 'solid 1px #fff',
borderRadius: '50px',
padding: '5px 11px',
backgroundColor: 'rgba(255, 255, 255, 0.1)'
}
});
}



jinsom_ajax_get_live_comment();
}else if(msg.code==3){//异常
}else{
jinsom_ajax_get_live_comment();	
}
},
error:function(XMLHttpRequest,textStatus,errorThrown){ 
if(textStatus=="timeout"){ 
jinsom_ajax_get_live_comment();
} 
} 
});	
}
jinsom_ajax_get_live_comment();


</script>

<?php get_footer();?>
<?php }?>