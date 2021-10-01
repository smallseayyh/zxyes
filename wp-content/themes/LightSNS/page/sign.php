<?php
/*
Template Name:签到页面
*/
if(wp_is_mobile()){
require(get_template_directory() . '/mobile/index.php');    
}else{
get_header();
$user_id=$current_user->ID;
$sign_c=(int)get_user_meta($user_id,'sign_c',true);//累计签到天数
$sign_card=(int)get_user_meta($user_id,'sign_card',true);//补签卡
$jinsom_get_sign_card_url=jinsom_get_option('jinsom_get_sign_card_url');

global $wpdb;
$table_name=$wpdb->prefix.'jin_sign';
$sign_data=$wpdb->get_results("SELECT strtotime FROM $table_name WHERE user_id='$user_id' ORDER BY date DESC limit 31;");


$month_day=$wpdb->get_results("SELECT * FROM $table_name WHERE user_id = $user_id AND ( DATE_FORMAT(date,'%Y%m')=DATE_FORMAT(CURDATE(),'%Y%m') )  GROUP BY date limit 31;");
$month_day=count($month_day);



$today=date('Y-m-d',time());
$jinsom_sign_page_rank_user_number=jinsom_get_option('jinsom_sign_page_rank_user_number');//展示的人数
$new_data=$wpdb->get_results("SELECT * FROM $table_name WHERE date='$today' ORDER BY strtotime;");




?>
<style type="text/css">
.jinsom-sign-page-box {
    background-color: #fff;
    padding: 20px;
    border-radius: var(--jinsom-border-radius);
    margin-bottom: 10px;
}
.jinsom-sign-page-header {
    margin-bottom: 20px;
}
.jinsom-sign-page-header .date {
    float: left;
    font-size: 18px;
    color: #555;
}
.jinsom-sign-page-header .date i {
    font-size: 24px;
    vertical-align: -2px;
    color: var(--jinsom-color);
    margin-right: 5px;
}
.jinsom-sign-page-header .one {
    float: right;
    font-size: 16px;
}
.jinsom-sign-page-header .one a {
    color: #2196F3;
    text-decoration: underline;
}
.jinsom-sign-page-content table {
    border: none;
}

.jinsom-sign-page-content thead:after {
    content: '-';
    display: block;
    line-height: 0;
    color: transparent;
}
.jinsom-sign-page-content thead td:first-child span {
    border-radius: 10px 0 0 0;
}
.jinsom-sign-page-content thead td:last-child span {
    border-radius: 0 10px 0 0;
}
.jinsom-sign-page-content thead td {
    position: relative;
    height: 50px;
    border: none;
}
.jinsom-sign-page-content thead td span {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #ccc;
    color: #fff;
    font-size: 18px;
}
.jinsom-sign-page-content tbody td {
    width: calc(100%/7);
    border: 4px solid #fff;
    padding: 0;
    padding-top: calc(100%/7);
    position: relative;
    border-radius: 4px;
}
.jinsom-sign-page-content tbody td span {
    position: absolute;
    width: 100%;
    height: 100%;
    background-color: #f5f5f5;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    top: 0;
    color: #878787;
    font-size: 30px;
    border: 1px solid #fff;
    box-sizing: border-box;
}
.jinsom-sign-page-content tbody td.no-sign span,.jinsom-sign-page-content tbody td.had-sign span {
    cursor: pointer;
}
.jinsom-sign-page-content tbody td.no-sign span:hover {
    border-color:#5fb878;
}
.jinsom-sign-page-content tbody td.had-sign span {
    background-color: #ddd;
}
.jinsom-sign-page-content tbody td.had-sign span i {
    position: absolute;
    bottom: 10px;
    font-size: 60px;
    color: #26ae3c;
    opacity: 0.8;
}
.jinsom-sign-page-content tbody td.no span m {
    position: absolute;
    bottom: 12px;
    background-color: #5fb878;
    color: #fff;
    padding: 4px 10px;
    border-radius: 4px;
    font-size: 12px;
}
.jinsom-sign-page-content tbody td.today span {
    border-color: #5fb878;
}


.jinsom-sign-page-btn {
    line-height: 40px;
    background-color: var(--jinsom-color);
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    text-align: center;
    border-radius: 4px;
}
.jinsom-sign-page-btn i {
    font-size: 22px;
    margin-right: 8px;
    vertical-align: -2px;
}
.jinsom-sign-page-box.sign-btn .layui-elem-field {
    border-radius: 4px;
    margin: 0;
    margin-top: 20px;
}
.jinsom-sign-page-box.sign-btn legend {
    margin: 0 auto;
    font-size: 14px;
    font-weight: inherit;
}
.jinsom-sign-page-box.sign-btn .layui-field-box {
    margin: 10px 15px 15px 15px;
    background-color: #f4f4f4;
    border-radius: 4px;
}
.jinsom-sign-page-all-days,.jinsom-sign-page-month-days {
    text-align: center;
    margin-bottom: 20px;
    font-size: 16px;
}
.jinsom-sign-page-all-days span,.jinsom-sign-page-month-days span {
    color: #f00;
    margin: 0 5px;
}
.jinsom-sign-page-box.sign-btn .layui-field-box li {
    padding-left: 10px;
    border-left: 2px solid var(--jinsom-color);
    margin-bottom: 15px;
}
.jinsom-sign-page-box.sign-btn .layui-field-box li:last-child {
    margin-bottom: 0;
}
.jinsom-sign-page-box.month li {
    margin-bottom: 20px;
    display: flex;
}
.jinsom-sign-page-box.month li .img {
    flex: 1;
    color: #555;
}
.jinsom-sign-page-box.month li .btn {
    background-color: var(--jinsom-color);
    color: #fff;
    border-radius: 4px;
    cursor: pointer;
    height: 30px;
    line-height: 30px;
    margin-top: 5px;
    width: 70px;
    text-align: center;
}
.jinsom-sign-page-box.month li img {
    width: 40px;
    height: 40px;
    object-fit: contain;
    margin-right: 10px;
    cursor: pointer;
}
.jinsom-sign-page-box.month li.shake img {
    animation-name: shake-base;
    animation-duration: 2500ms;
    animation-iteration-count: infinite;
}
.jinsom-sign-page-box.month li .btn.had {
    background-color: #ccc;
}
.jinsom-sign-page-box.rank .layui-tab-item li {
    width: calc((100% - 30px)/4);
    margin-top: 10px;
    margin-right: 10px;
}
.jinsom-sign-page-box.rank .layui-tab-item li .rank {
    margin-right: 5px;
}
.jinsom-sign-page-box.rank .jinsom-ranking-page-bottom li .info .number {
    font-size: 13px;
    margin-top: 3px;
}
.jinsom-sign-page-btn.had {
    background-color: #ccc;
}
.jinsom-sign-page-box.rank .layui-tab-content {
    padding: 10px 0 0 0;
}
.jinsom-sign-page-box.rank .layui-tab {
    margin: 0;
}
.jinsom-sign-page-box.sign-card .left {
    float: left;
    position: relative;
    padding-left: 35px;
}
.jinsom-sign-page-box.sign-card .right {
    float: right;
}
.jinsom-sign-page-box.sign-card .right a {
    color: #2196F3;
}
.jinsom-sign-page-box.sign-card i.jinsom-jianqu {
    font-size: 30px;
    position: absolute;
    left: 0;
    top: -10px;
    color: #FF5722;
}
</style>
<div class="jinsom-main-content sign-page clear">
<div class="jinsom-content-left">

<div class="jinsom-sign-page-box">	
<div class="jinsom-sign-page-header clear">
<div class="date"><i class="jinsom-icon jinsom-qiandao2"></i> <?php echo date('m月d日');?></div>	
<div class="one">
<?php 
if($new_data){
$a=1;
foreach ($new_data as $data){
if($a>1){break;}
echo __('今日首签','jinsom').'：'.jinsom_nickname_link($data->user_id);
$a++;
}
}
?>
</div>
</div>	
<div class="jinsom-sign-page-content">
<table border="1px" cellpadding="0" cellspacing="0">
<thead>
<tr class="tou">
<td><span><?php _e('周日','jinsom');?></span></td>
<td><span><?php _e('周一','jinsom');?></span></td>
<td><span><?php _e('周二','jinsom');?></span></td>
<td><span><?php _e('周三','jinsom');?></span></td>
<td><span><?php _e('周四','jinsom');?></span></td>
<td><span><?php _e('周五','jinsom');?></span></td>
<td><span><?php _e('周六','jinsom');?></span></td>
</tr>
</thead>
<tbody id="jinsom-sign-body">
</tbody>
</table>
</div>
</div>

<div class="jinsom-sign-page-box rank">
<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<li class="layui-this"><?php _e('今日签到','jinsom');?> (<?php echo count($new_data);?><?php _e('人','jinsom');?>)</li>
<li><?php _e('累计签到','jinsom');?></li>
</ul>
<div class="layui-tab-content">
<div class="layui-tab-item layui-show jinsom-ranking-page-bottom clear">
<?php 
if($new_data){
$rank=1;
foreach ($new_data as $data){
if($rank>$jinsom_sign_page_rank_user_number){break;}
$sign_user_id=$data->user_id;
$sign_time=date('Y-m-d H:i:s',$data->strtotime);
echo '
<li title="'.$sign_time.'">
<a href="'.jinsom_userlink($sign_user_id).'" target="_blank">
<div class="rank">'.$rank.'</div>
<div class="avatarimg">'.jinsom_vip_icon($sign_user_id).jinsom_avatar($sign_user_id,'40',avatar_type($sign_user_id)).jinsom_verify($sign_user_id).'</div>
<div class="info">
<div class="name">'.jinsom_nickname($sign_user_id).'</div>	
<div class="number">'.__('今天','jinsom').date('H:i',$data->strtotime).'</div>
</div>
</a>
</li>
';
$rank++;
}

}else{
echo jinsom_empty();
}
?>


</div>
<div class="layui-tab-item jinsom-ranking-page-bottom clear">
<?php 
$user_query_b = new WP_User_Query( array ( 
'orderby' => 'meta_value_num',
'order' => 'DESC',
'count_total'=>false,
'meta_key' => 'sign_c',
'number'=>$jinsom_sign_page_rank_user_number,
));
if (!empty($user_query_b->results)){
$rank=1;
foreach ($user_query_b->results as $user){
$sign_user_id=$user->ID;
$number=(int)get_user_meta($sign_user_id,'sign_c',true);
echo '
<li>
<a href="'.jinsom_userlink($sign_user_id).'" target="_blank">
<div class="rank">'.$rank.'</div>
<div class="avatarimg">'.jinsom_vip_icon($sign_user_id).jinsom_avatar($sign_user_id,'40',avatar_type($sign_user_id)).jinsom_verify($sign_user_id).'</div>
<div class="info">
<div class="name">'.jinsom_nickname($sign_user_id).'</div>	
<div class="number">'.$number.__('天','jinsom').'</div>
</div>
</a>
</li>
';
$rank++;

}
}else{
echo jinsom_empty();
}
?>



</div>
</div>
</div> 
</div>


<?php 
if(jinsom_get_option('jinsom_sign_page_footer_info')){//签到自定义区域
echo '<div class="jinsom-sign-page-box info">'.do_shortcode(jinsom_get_option('jinsom_sign_page_footer_info')).'</div>';
}
?>

</div>	
<div class="jinsom-content-right">
<div class="jinsom-sign-page-box sign-btn">
<div class="jinsom-sign-page-all-days"><?php _e('累计签到','jinsom');?><span><?php echo $sign_c;?></span><?php _e('天','jinsom');?></div>

<?php 
if(is_user_logged_in()){
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
$jinsom_machine_verify_appid=jinsom_get_option('jinsom_machine_verify_appid');
if(!jinsom_is_sign($user_id,date('Y-m-d',time()))){
if($jinsom_machine_verify_on_off&&in_array("sign",$jinsom_machine_verify_use_for)&&!jinsom_is_admin($user_id)){?>
<div class="jinsom-sign-page-btn opacity" id="sign-1"><i class="jinsom-icon jinsom-qiandao3"></i><span><?php _e('点击签到','jinsom');?></span></div>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('sign-1'),'<?php echo $jinsom_machine_verify_appid;?>',function(res){
if(res.ret === 0){jinsom_sign(res.ticket,res.randstr,document.getElementById('sign-1'));}
});
</script>
<?php }else{?>
<div class="jinsom-sign-page-btn opacity" onclick="jinsom_sign('','',this)"><i class="jinsom-icon jinsom-qiandao3"></i><span><?php _e('点击签到','jinsom');?></span></div>
<?php }?>


<?php }else{?>
<div class="jinsom-sign-page-btn had opacity"><?php _e('今日已签到','jinsom');?></div>
<?php }?>
<?php }else{?>
<div class="jinsom-sign-page-btn opacity" onclick="jinsom_pop_login_style()"><i class="jinsom-icon jinsom-qiandao3"></i><span><?php _e('点击签到','jinsom');?></span></div>
<?php }?>

<?php 
$jinsom_sign_add=jinsom_get_option('jinsom_sign_add');
if($jinsom_sign_add){
$day=date('d',time());
foreach ($jinsom_sign_add as $data) {
if($data['day']==$day){
if($data['sign_reward_data']){

echo '
<fieldset class="layui-elem-field">
<legend>'.__('今日签到奖励','jinsom').'</legend>
<div class="layui-field-box">';

foreach ($data['sign_reward_data'] as $reward_data) {
$reward_type=$reward_data['sign_reward_type'];
$reward_number=$reward_data['sign_reward_number'];
if($reward_type=='credit'){
$reward_type=jinsom_get_option('jinsom_credit_name');
}else if($reward_type=='exp'){
$reward_type=__('经验值','jinsom');
}else if($reward_type=='sign'){
$reward_type=__('补签卡','jinsom');
}else if($reward_type=='vip'){
$reward_type=__('VIP天数','jinsom');
}else if($reward_type=='vip_number'){
$reward_type=__('成长值','jinsom');
}else if($reward_type=='nickname'){
$reward_type=__('改名卡','jinsom');
}else if($reward_type=='charm'){
$reward_type=__('魅力值','jinsom');
}else if($reward_type=='honor'){
$reward_type=__('头衔','jinsom');
$reward_number=$reward_data['sign_reward_honor'];
}

echo '<li>'.$reward_type.' * '.$reward_number.'</li>';


}
echo '</div></fieldset>';


}

}
}

}
?>
</div>

<div class="jinsom-sign-page-box sign-card clear">
<div class="left"><i class="jinsom-icon jinsom-jianqu"></i> <?php _e('我的补签卡','jinsom');?>：<?php echo sprintf(__( '%s张','jinsom'),$sign_card);?></div>
<?php if($jinsom_get_sign_card_url){?>
<div class="right"><a href="<?php echo $jinsom_get_sign_card_url;?>" target="_blank"><?php _e('获取','jinsom');?></a></div>
<?php }?>
</div>

<div class="jinsom-sign-page-box month">
<div class="jinsom-sign-page-month-days"><?php _e('本月签到','jinsom');?><span><?php echo $month_day;?></span><?php _e('天','jinsom');?></div>
<div class="content">
<?php 
$jinsom_sign_treasure_add=jinsom_get_option('jinsom_sign_treasure_add');
if($jinsom_sign_treasure_add){
$i=0;
foreach ($jinsom_sign_treasure_add as $data){
$day=$data['day'];
$had=jinsom_is_task($user_id,date('Y-m',time()).'_'.$day);
if($month_day>=$day&&!$had){
$dou='shake';
}else{
$dou='';
}


if($had){
echo '<li class="'.$dou.'">
<div class="img"><img src="'.$data['img'].'" onclick="jinsom_sign_treasure_form('.$i.')"><span>'.$day.__('天','jinsom').'</span></div>	
<div class="btn opacity had">'.__('已领取','jinsom').'</div>
</li>';
}else{
echo '<li class="'.$dou.'">
<div class="img"><img src="'.$data['img'].'" onclick="jinsom_sign_treasure_form('.$i.')"><span>'.$day.__('天','jinsom').'</span></div>	
<div class="btn opacity" onclick="jinsom_sign_treasure('.$i.',this)">'.__('领取','jinsom').'</div>
</li>';	
}

$i++;
}
}
?>
</div>
</div>

<?php 
$jinsom_sign_page_sidebar_html=jinsom_get_option('jinsom_sign_page_sidebar_html');
if($jinsom_sign_page_sidebar_html){
echo '<div class="jinsom-sign-page-box custom">'.do_shortcode($jinsom_sign_page_sidebar_html).'</div>';
}
?>



</div>
</div>

<script type="text/javascript">
var $$_ = function(id) {
return "string" == typeof id ? document.getElementById(id) : id;
};
var Class = {
create: function() {
return function() {
this.initialize.apply(this, arguments);
}
}
}
Object.extend = function(destination, source) {
for(var property in source) {
destination[property] = source[property];
}
return destination;
}
var Calendar = Class.create();
Calendar.prototype = {
initialize: function(container, options) {
this.Container = $$_(container); //容器(table结构)
this.Days = []; //日期对象列表
this.SetOptions(options);
this.Year = this.options.Year;
this.Month = this.options.Month;
this.qdDay = this.options.qdDay;
this.Draw();
},
//设置默认属性
SetOptions: function(options) {
this.options = { //默认值
Year: new Date().getFullYear(), //显示年
Month: new Date().getMonth() + 1, //显示月
qdDay: null,
};
Object.extend(this.options, options || {});
},
//画日历
Draw: function() {
//签到日期
var day = this.qdDay;
//日期列表
var arr = [];
//用当月第一天在一周中的日期值作为当月离第一天的天数
for(var i = 1, firstDay = new Date(this.Year, this.Month - 1, 1).getDay(); i <= firstDay; i++) {
arr.push("&nbsp;");
}
//用当月最后一天在一个月中的日期值作为当月的天数
for(var i = 1, monthDay = new Date(this.Year, this.Month, 0).getDate(); i <= monthDay; i++) {
arr.push(i);
}
var frag = document.createDocumentFragment();
this.Days = [];
while(arr.length > 0) {
//每个星期插入一个tr
var row = document.createElement("tr");
//每个星期有7天
for(var i = 1; i <= 7; i++) {
var cell = document.createElement("td");

cell.innerHTML = "<span>&nbsp;</span>";
if(arr.length > 0) {
var d = arr.shift();
cell.innerHTML = "<span>" + d + "</span>";
if(d > 0 && day.length) {
cell.className = "no-sign";	
$(cell).attr('onclick','jinsom_sign_add_form('+d+')');
$(cell).attr('id','jinsom-sign-day-'+d);
a=0;
for(var ii = 0; ii < day.length; ii++) {
this.Days[d] = cell;
had_sign=this.IsSame(new Date(this.Year, this.Month - 1, d), day[ii]);//是否签到
if(had_sign) {
cell.className = "had-sign";
$(cell).children('span').html(d+'<i class="jinsom-icon jinsom-dagou"></i>');
a=1;
}
// console.log(1);

}

if(d <new Date().getDate()&&!a){
$(cell).addClass('no').children('span').html(d+'<m><?php _e('点击补签','jinsom');?></m>');
}

if(d ==new Date().getDate()){
$(cell).addClass('today');
}

if(d >new Date().getDate()){
$(cell).addClass('in');
}




}
}
row.appendChild(cell);
}
frag.appendChild(row);
}
//先清空内容再插入(ie的table不能用innerHTML)
while(this.Container.hasChildNodes()) {
this.Container.removeChild(this.Container.firstChild);
}
this.Container.appendChild(frag);
},
//是否签到
IsSame: function(d1, d2) {
d2 = new Date(d2 * 1000);
return(d1.getFullYear() == d2.getFullYear() && d1.getMonth() == d2.getMonth() && d1.getDate() == d2.getDate());
},
};


var hadsign = new Array(); //已签到的数组
hadsign[0] = "765189111";
<?php 
if($sign_data){
$i=1;
foreach ($sign_data as $data) {
echo 'hadsign['.$i.'] = "'.$data->strtotime.'";';
$i++;
}
}
?>
var cale = new Calendar("jinsom-sign-body", {
qdDay: hadsign,
});
</script>




<?php get_footer();?>
<?php }?>