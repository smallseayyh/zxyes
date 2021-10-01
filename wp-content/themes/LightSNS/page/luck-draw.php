<?php
/*
Template Name:幸运抽奖
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');    
}else{
get_header();
$credit_name=jinsom_get_option('jinsom_credit_name');
$user_id=$current_user->ID;
$credit=(int)get_user_meta($user_id,'credit',true);
$post_id=get_the_ID();
$luckdraw_data=get_post_meta($post_id,'page_luckdraw_option',true);
if(!$luckdraw_data){
echo jinsom_empty('请在新建页面的时候配置当前页面的数据！');exit();
}

$jinsom_luck_gift_number=$luckdraw_data['jinsom_luck_gift_number'];//每次抽取需要花费的金币
$jinsom_luck_gift_add=$luckdraw_data['jinsom_luck_gift_add'];
$jinsom_luck_gift_default_cover=$luckdraw_data['jinsom_luck_gift_default_cover'];
?>

<style type="text/css">
::-webkit-scrollbar-track-piece {
background-color: inherit;
}
::-webkit-scrollbar-track {
background-color: #bbb !important;
}

.barrage{position: fixed;bottom:70px;right:-500px;display: inline-block;width: 500px;z-index: 99999}
.barrage_box{background-color: rgba(0,0,0,.5);padding-right: 8px; height: 40px;display: inline-block;border-radius: 25px;transition: all .3s;}
.barrage_box .portrait{ display: inline-block;margin-top: 4px; margin-left: 4px; width: 32px;height: 32px;border-radius: 50%;overflow: hidden;}
.barrage_box .portrait img{width: 100%;height: 100%;background-color: #fff;}
.barrage_box div.p a{ margin-right: 2px; font-size: 14px;color: #fff;line-height: 40px;margin-left: 10px; }
.barrage_box div.p a:hover{text-decoration: underline;}
.barrage_box .close{visibility: hidden;opacity: 0; text-align: center; width:25px;height: 25px;margin-left:10px;border-radius: 50%;background:rgba(255,255,255,.1);margin-top:8px;line-height: 24px;color: #aaa;}
.barrage_box:hover .close{visibility:visible;opacity: 1;cursor: pointer;}
.barrage_box .close i {font-size: 12px;}
.barrage_box .close a{display:block;}
.barrage_box .close .icon-close{font-size: 14px;color:rgba(255,255,255,.5);display: inline-block;margin-top: 5px; }
.barrage .z {float: left !important;}
.barrage  a{text-decoration:none;}

.jinsom-luck-gift {
    padding: 40px 0 60px;
}
.jinsom-luck-gift-content {
    min-height: 800px;
    margin: auto;
    background: #fff;
    display: flex;
    position: relative;
    width: var(--jinsom-width);
    padding: 20px;
    border-radius: var(--jinsom-border-radius);
}
.jinsom-luck-gift-left {
    flex: 3;
    margin-right: 20px;
}
.jinsom-luck-gift-right {
    flex: 1;
    border-left: 1px solid #f6f6f6;
}
.jinsom-luck-gift-left-cover .img {
    width: 200px;
    margin: auto;
    height: 200px;
    border: #f5ad18 4px solid;
    border-radius: 6px;
    background-size: cover;
    position: relative;
    cursor: pointer;
}
.jinsom-luck-gift-left-cover .img:hover {
    border: #F44336 4px solid;
    opacity: .8;
}
.jinsom-luck-gift-left-cover .img .name {
    position: absolute;
    bottom: 0;
    left: 0;
    line-height: 50px;
    background-color: rgba(0,0,0,.5);
    width: 100%;
    color: #fff;
    text-align: center;
}
.jinsom-luck-gift-left-cover {
    margin-top: 20px;
}
.jinsom-luck-gift-left-btn {
    display: flex;
    width: 80%;
    margin: 50px auto;
}
.jinsom-luck-gift-left-btn>span {
    flex: 1;
    text-align: center;
    background-color: #2ecc71;
    color: #fff;
    margin: 0 10px;
    padding: 10px;
    border-radius: 4px;
    cursor: pointer;
}
.jinsom-luck-gift-left-btn>span:hover {
    opacity: .8;
}
.jinsom-luck-gift-left-btn>span:nth-child(2) {
    background-color: #3498db;
}
.jinsom-luck-gift-left-btn>span:nth-child(3) {
    background-color: #9b59b6;
}
.jinsom-luck-gift-left-btn>span:nth-child(4) {
    background-color: #e74c3c;
}
.jinsom-luck-gift-title {
    text-align: center;
    line-height: 40px;
    position: relative;
    display: flex;
    padding: 0 15px;
}
.jinsom-luck-gift-title li {
    flex: 1;
    cursor: pointer;
    color: #ccc;
}
.jinsom-luck-gift-title li.on {
    color: #f5b43a;
    font-size: 16px;
    font-weight: bold;
}

.jinsom-luck-gift-title:before {
    position: absolute;
    content: "";
    top: 100%;
    left: 0;
    width: 100%;
    background: -webkit-linear-gradient(left, rgba(248,215,59,0), #f0d03a, rgba(248,215,59,0));
    height: 1px;
}
.jinsom-luck-gift-right-list {
    overflow-y: auto;
    box-sizing: border-box;
    padding: 15px 0;
    height: 760px;
}
.jinsom-luck-gift-right-list li {
    display: flex;
    width: 100%;
    align-items: center;
    padding: 10px 15px;
    box-sizing: border-box;
    cursor: pointer;
    border-radius: 4px;
}
.jinsom-luck-gift-right-list li:hover {
    background-color: #f8f8f8;
}
.jinsom-luck-gift-right-list li .img {
    height: 30px;
    width: 30px;
    border-radius: 5px;
    background-position: center;
    background-size: cover;
    margin-right: 10px;
    background-color: #fff;
    position: relative;
}
.jinsom-luck-gift-right-list .b li .name m {
    color: #f5b43a;
}
.jinsom-luck-gift-right-list .b li {
    color: #777;
}
.jinsom-luck-gift-list li {
    float: left;
    width: 100px;
    margin-right: 20px;
    margin-bottom: 20px;
}
.jinsom-luck-gift-list li:nth-child(5n+1) {
    margin-right: 0;
}
.jinsom-luck-gift-list li img {
    width: 100px;
    height: 100px;
    border-radius: 4px;
    border: 2px solid;
}
.jinsom-luck-gift-list li p {
    text-align: center;
    margin-top: 5px;
    font-size: 12px;
    height: 24px;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
}
.jinsom-luck-gift-list .tips {
    color: #f5b43a;
    text-align: center;
    margin-bottom: 20px;
}
.jinsom-current-credit {
    color: #333;
    position: absolute;
}
.jinsom-current-credit span {
    color: #fe7f3e;
    font-weight: bold;
}
.jinsom-luck-gift-left-info {
    border: 1px solid #f1f1f1;
    padding: 20px;
    border-radius: var(--jinsom-border-radius);
    box-sizing: border-box;
    color: #666;
    background-color: #f8f8f8;
}
.jinsom-luck-gift-left-info .title {
    font-weight: bold;
    margin-bottom: 15px;
}
.jinsom-luck-gift-left-info p {
    line-height: 30px;
}
.jinsom-luck-gift-right-list::-webkit-scrollbar {
    width: 2px;
}
.jinsom-luck-gift-right-list::-webkit-scrollbar-track-piece {
    background-color: #fff;
}
.jinsom-luck-gift-right-list::-webkit-scrollbar-thumb:vertical {
    background-color: #f5b43a;
}
</style>
</head>
<body>

<div class='jinsom-luck-gift'>


<div class='jinsom-luck-gift-content'>

<div class="jinsom-current-credit">我的<?php echo $credit_name;?>：<span><?php echo $credit;?></span></div>

<div class="jinsom-luck-gift-left">
<div class="jinsom-luck-gift-left-cover">
<div class="img" style="background-image: url(<?php echo $jinsom_luck_gift_default_cover;?>);" onclick="jinsom_show_luck_gift(<?php echo $post_id;?>)">
<div class="name"><?php _e('点击查看所有奖品','jinsom');?></div>
</div>
</div>
<div class="jinsom-luck-gift-left-btn">
<span onclick="jinsom_luck_start(<?php echo $post_id;?>,'难民',1,this)"><?php _e('难民 (抽1次)','jinsom');?></span>
<span onclick="jinsom_luck_start(<?php echo $post_id;?>,'平民',3,this)"><?php _e('平民 (抽3次)','jinsom');?></span>
<span onclick="jinsom_luck_start(<?php echo $post_id;?>,'土豪',5,this)"><?php _e('土豪 (抽5次)','jinsom');?></span>
<span onclick="jinsom_luck_start(<?php echo $post_id;?>,'神豪',10,this)"><?php _e('神豪 (抽10次)','jinsom');?></span>
</div>
<div class="jinsom-luck-gift-left-info">
<div class="title"><?php _e('规则说明','jinsom');?></div>
<?php echo do_shortcode($luckdraw_data['jinsom_luck_gift_info_html']);?>
</div>
</div>


<div class="jinsom-luck-gift-right">
<div class="jinsom-luck-gift-title">
<li class="on"><?php _e('我的奖品','jinsom');?></li>
<li><?php _e('最新抽奖','jinsom');?></li>
</div>
<ul class="jinsom-luck-gift-right-list">
<div class="a">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_luck_draw';
$luck_data= $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' ORDER BY ID DESC LIMIT 30;");
if($luck_data){

foreach ($luck_data as $data) {
echo '<li><span class="img" style="background-image:url('.$data->img.')"></span><span class="name">'.$data->name.'</span></li>';
}

}else{
echo jinsom_empty(__('暂没有抽奖记录','jinsom'));
}
?>
</div>
<div class="b" style="display: none;">
<?php 
global $wpdb;
$table_name = $wpdb->prefix.'jin_luck_draw';
$luck_data= $wpdb->get_results("SELECT * FROM $table_name WHERE user_id!='$user_id' ORDER BY ID DESC LIMIT 100;");
if($luck_data){

foreach ($luck_data as $data) {

if($data->time){
$time=jinsom_timeago($data->time);
}else{
$time='';
}

echo '<li onclick="jinsom_post_link(this)" title="'.$time.'" data="'.jinsom_userlink($data->user_id).'"><span title="'.get_user_meta($data->user_id,'nickname',true).'" class="img" style="background-image:url('.jinsom_avatar_url($data->user_id,avatar_type($data->user_id)).')">'.jinsom_verify($data->user_id).'</span><span class="name">'.__('抽到了','jinsom').' <m>'.$data->name.'</m></span></li>';
}

}else{
echo jinsom_empty(__('暂没有抽奖记录','jinsom'));
}
?>
</div>
</ul>
</div>


</div>
</div>


<script>
layui.use(['layer'], function(){
var layer = layui.layer;
});
<?php if($jinsom_luck_gift_add){?>

var gift_cover = new Array();
var gift_name = new Array();
<?php 
$a=0;
foreach ($jinsom_luck_gift_add as $data) {

$type=$data['jinsom_luck_gift_add_type'];
if($type=='头衔'){
$number=$data['honor_name'];
}else{
$number=$data['number'];
}

if($type=='空'){
$name=__('脸黑*没有奖励','jinsom');
}else if($type=='custom'||$type=='nickname'||$type=='签到天数'){
$name=$data['name'].'*'.$number;
}else if($type=='faka'){
$name=$data['name'];
}else{
$name=$type.'*'.$number;
}

echo 'gift_cover['.$a.'] = "'.$data['images'].'";';
echo 'gift_name['.$a.'] = "'.$name.'";';
$a++;
}

?>


var COVER = $('.jinsom-luck-gift-left-cover .img');
var NAME = $('.jinsom-luck-gift-left-cover .name');
var count = gift_cover.length-1;//总数
var runing = true;
var trigger = true;

//跳动奖品
function jinsom_luck_jump() {
num = Math.floor(Math.random() * count);
COVER.css('background-image','url('+gift_cover[num]+')');
NAME.html(gift_name[num]);
t = setTimeout(jinsom_luck_jump, 10);
}

// 停止跳动
function jinsom_luck_stop() {
clearInterval(t);
t = 0;
}


// 开始抽奖
function jinsom_luck_start(post_id,luck_name,Lotterynumber,obj) {
if(!jinsom.is_login){
jinsom_pop_login_style();   
return false;
}

$(obj).siblings().hide();
if (trigger) {
trigger = false;
var i = 0;
var a = 1;
$(obj).text('奖品抽取中...('+ Lotterynumber+')');	
end=Lotterynumber;//结束次数

stopTime = window.setInterval(function () {
if (runing) {
runing = false;
jinsom_luck_jump();
}else{
runing = true;

$.ajax({
type: "POST",
dataType:'json',
url:jinsom.jinsom_ajax_url+"/action/luck-draw.php",
data:{post_id:post_id},
success: function(msg){
jinsom_luck_stop();//ajax获取到数据才停止

if(msg.code==1){

//渲染金币
$('.jinsom-current-credit span').html(msg.credit);

//将后台获取的奖品渲染
COVER.css('background-image','url('+gift_cover[msg.rand]+')');
NAME.html(gift_name[msg.rand]);

if($('.jinsom-luck-gift-right-list .a .jinsom-empty-page').length>0){
$('.jinsom-luck-gift-right-list .a').empty();
}
$('.jinsom-luck-gift-right-list .a').prepend("<li><span class='img' style='background-image:url("+gift_cover[msg.rand]+")'></span><span class='name'>"+gift_name[msg.rand]+"</span></li>");


Lotterynumber--;
$(obj).text('奖品抽取中...('+ Lotterynumber+')');

if ( a == end ) {
$(obj).text(luck_name+" (抽"+end+"次)");
$(obj).siblings().show();
};
a++;

}else{
window.clearInterval(stopTime);
layer.msg(msg.msg);
trigger = true;
$(obj).text(luck_name+" (抽"+end+"次)");
$(obj).siblings().show();
COVER.css('background-image','url('+msg.cover+')');
NAME.html('点击查看所有奖品');
}

},
error:function(){
console.log('失败！');
jinsom_luck_stop();
}
});
i++;
if(i==end){
window.clearInterval(stopTime);
trigger = true;
};

}
},1500);
}
}

<?php }?>


//点击查看所有奖品
function jinsom_show_luck_gift(post_id){
layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/stencil/luck-draw.php",
data:{post_id:post_id},
success: function(msg){
layer.closeAll('loading');
layer.open({
title:false,
btn: false,
fixed: false,
offset: '50px',
resize:false,
area: ['630px', '500px'],
skin:'jinsom-show-luck-gift-content',
content: msg
})
}
});
}

//列表tab切换
$('.jinsom-luck-gift-title li').click(function(){
$(this).addClass('on').siblings().removeClass('on');
$(this).parent().next().children().eq($(this).index()).show().siblings().hide();
});


<?php if($luckdraw_data['jinsom_luck_gift_danmu_on_off']){?>

//弹幕
!function(a){a.fn.barrager=function(b){function m(){var b=a(window).width()+500;return b>k?(k+=1,a(e).css("margin-right",k),void 0):(a(e).remove(),!1)}var c,d,e,f,g,h,i,j,k,l;b=a.extend({close:!0,bottom:0,max:10,speed:6,color:"#fff",old_ie_color:"#000000"},b||{}),c=(new Date).getTime(),d="barrage_"+c,e="#"+d,f=a("<div class='barrage' id='"+d+"'></div>").appendTo(a(this)),g=a(window).height()-100,h=0==b.bottom?Math.floor(Math.random()*g+40):b.bottom,f.css("bottom",h+"px"),div_barrager_box=a("<div class='barrage_box cl'></div>").appendTo(f),b.img&&(div_barrager_box.append("<a class='portrait z' href='javascript:;'></a>"),i=a("<img src='' >").appendTo(e+" .barrage_box .portrait"),i.attr("src",b.img)),div_barrager_box.append(" <div class='z p'></div>"),b.close&&div_barrager_box.append(" <div class='close z'><i class='jinsom-icon jinsom-guanbi'></div>"),j=a("<a title='' href='' target='_blank'></a>").appendTo(e+" .barrage_box .p"),j.attr({href:b.href,id:b.id}).empty().append(b.info),navigator.userAgent.indexOf("MSIE 6.0")>0||navigator.userAgent.indexOf("MSIE 7.0")>0||navigator.userAgent.indexOf("MSIE 8.0")>0?j.css("color",b.old_ie_color):j.css("color",b.color),k=0,f.css("margin-right",k),l=setInterval(m,b.speed),div_barrager_box.mouseover(function(){clearInterval(l)}),div_barrager_box.mouseout(function(){l=setInterval(m,b.speed)}),a(e+".barrage .barrage_box .close").click(function(){a(e).remove()})},a.fn.barrager.removeAll=function(){a(".barrage").remove()}}(jQuery);


// $.ajaxSettings.async = false;
$.getJSON(jinsom.jinsom_ajax_url+"/action/luck-draw-danmu.php",function(data){
var looper_time=4*1000;
var items=data;
var total=data.length;
var run_once=true;
var index=0;
barrager();
function  barrager(){
if(run_once){
looper=setInterval(barrager,looper_time);                
run_once=false;
}
$('.jinsom-luck-gift').barrager(items[index]);
index++;
if(index == total){
clearInterval(looper);
return false;
}
}
});

<?php }?>

</script>


</div>

<?php get_footer();
}
?>
