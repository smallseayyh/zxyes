<?php 
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$credit_name=jinsom_get_option('jinsom_credit_name');
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';
$date=date('Y-m-d');
$today_order = $wpdb->get_var("SELECT count(ID) FROM $table_name  WHERE status IN(1,2,3) and time like '$date%';");//今日付款订单
$today_rmb_order = (int)$wpdb->get_var("SELECT sum(pay_price) FROM $table_name  WHERE status IN(1,2,3) and time like '$date%' and price_type='rmb';");
$today_credit_order = (int)$wpdb->get_var("SELECT sum(pay_price) FROM $table_name  WHERE status IN(1,2,3) and time like '$date%' and price_type='credit';");
?>
<div class="jinsom-admin-shop-order-form">
<div class="jinsom-admin-shop-order-header">
<div class="left">
<li><span><?php echo $today_order;?><i><?php _e('单','jinsom');?></i></span><p><?php _e('今日有效订单数','jinsom');?></p></li>
<li><span><?php echo $today_rmb_order;?><i><?php _e('元','jinsom');?></i></span><p><?php _e('今日人民币交易','jinsom');?></p></li>
<li><span><?php echo $today_credit_order;?><i><?php echo $credit_name;?></i></span><p>今日<?php echo $credit_name;?>交易额</p></li>
</div>
<div class="right">
<li class="delete opacity" onclick="layer.msg('暂未开启！')"><?php _e('订单删除','jinsom');?></li>
<li class="export opacity" onclick="layer.msg('暂未开启！')"><?php _e('订单导出','jinsom');?></li>
</div>

</div>

<div class="jinsom-admin-shop-order-bottom">


<div class="layui-tab layui-tab-brief">
<ul class="layui-tab-title">
<li class="layui-this"><?php _e('全部订单','jinsom');?></li>
<li><?php _e('待发货订单','jinsom');?></li>
<li><?php _e('未付款订单','jinsom');?></li>
<li><?php _e('待评价订单','jinsom');?></li>
<li><?php _e('已完成订单','jinsom');?></li>
</ul>
<div class="layui-tab-content">

<div class="jinsom-goods-order-list status-a layui-tab-item layui-show">
<div class="content">
<?php //全部订单
$data_a=$wpdb->get_results("SELECT * FROM $table_name ORDER BY time desc limit 5;");
$data_a_count=$wpdb->get_var("SELECT count(ID) FROM $table_name;");
$count_a=$data_a_count;
require('shop-order-html.php');
?>
</div>
<div id="jinsom-goods-order-a-page" class="jinsom-mycredit-page"></div>
</div>

<div class="jinsom-goods-order-list status-b layui-tab-item">
<div class="content">
<?php //待发货
$data_a=$wpdb->get_results("SELECT * FROM $table_name where status=1 ORDER BY time desc limit 5;");
$data_a_count=$wpdb->get_var("SELECT count(ID) FROM $table_name where status=1;");
$count_b=$data_a_count;
require('shop-order-html.php');
?>
</div>
<div id="jinsom-goods-order-b-page" class="jinsom-mycredit-page"></div>
</div>

<div class="jinsom-goods-order-list status-c layui-tab-item">
<div class="content">
<?php //未付款
$data_a=$wpdb->get_results("SELECT * FROM $table_name where status=0 ORDER BY time desc limit 5;");
$data_a_count=$wpdb->get_var("SELECT count(ID) FROM $table_name where status=0;");
$count_c=$data_a_count;
require('shop-order-html.php');
?>
</div>
<div id="jinsom-goods-order-c-page" class="jinsom-mycredit-page"></div>
</div>

<div class="jinsom-goods-order-list status-d layui-tab-item">
<div class="content">
<?php //待评论
$data_a=$wpdb->get_results("SELECT * FROM $table_name where status=2 ORDER BY time desc limit 5;");
$data_a_count=$wpdb->get_var("SELECT count(ID) FROM $table_name where status=2;");
$count_d=$data_a_count;
require('shop-order-html.php');
?>
</div>
<div id="jinsom-goods-order-d-page" class="jinsom-mycredit-page"></div>
</div>

<div class="jinsom-goods-order-list status-e layui-tab-item">
<div class="content">
<?php //已完成
$data_a=$wpdb->get_results("SELECT * FROM $table_name where status=3 ORDER BY time desc limit 5;");
$data_a_count=$wpdb->get_var("SELECT count(ID) FROM $table_name where status=3;");
$count_e=$data_a_count;
require('shop-order-html.php');
?>
</div>
<div id="jinsom-goods-order-e-page" class="jinsom-mycredit-page"></div>
</div>


</div>
</div>


</div>
</div>



<script type="text/javascript">
layui.use('laypage', function(){
var laypage = layui.laypage;


<?php if($count_a>5){?>
laypage.render({//全部订单
elem: 'jinsom-goods-order-a-page',
theme:'#5fb878',
limit:5,
count: <?php echo $count_a;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-a .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/shop-order-more.php",
data:{page:page,number:number,type:'a'},
success: function(msg){
$('.jinsom-goods-order-list.status-a .content').html(msg);
}
});

}
}
});
<?php }?>


<?php if($count_b>5){?>
laypage.render({//待发货
elem: 'jinsom-goods-order-b-page',
theme:'#5fb878',
limit:5,
count: <?php echo $count_b;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-b .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/shop-order-more.php",
data:{page:page,number:number,type:'b'},
success: function(msg){
$('.jinsom-goods-order-list.status-b .content').html(msg);
}
});

}
}
});
<?php }?>


<?php if($count_b>5){?>
laypage.render({//未付款
elem: 'jinsom-goods-order-c-page',
theme:'#5fb878',
limit:5,
count: <?php echo $count_c;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-c .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/shop-order-more.php",
data:{page:page,number:number,type:'c'},
success: function(msg){
$('.jinsom-goods-order-list.status-c .content').html(msg);
}
});

}
}
});
<?php }?>


<?php if($count_d>5){?>
laypage.render({//待评价
elem: 'jinsom-goods-order-d-page',
theme:'#5fb878',
limit:5,
count: <?php echo $count_d;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-d .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/shop-order-more.php",
data:{page:page,number:number,type:'d'},
success: function(msg){
$('.jinsom-goods-order-list.status-d .content').html(msg);
}
});

}
}
});
<?php }?>


<?php if($count_e>5){?>
laypage.render({//已完成
elem: 'jinsom-goods-order-e-page',
theme:'#5fb878',
limit:5,
count: <?php echo $count_e;?>,
layout:['count','page','skip'],
jump: function(obj, first){
if(!first){//首次不执行
page=obj.curr;
number=obj.limit;

$('.jinsom-goods-order-list.status-e .content').html(jinsom.loading);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/admin/action/shop-order-more.php",
data:{page:page,number:number,type:'e'},
success: function(msg){
$('.jinsom-goods-order-list.status-e .content').html(msg);
}
});

}
}
});
<?php }?>






});
</script>