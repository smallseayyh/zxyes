<?php 
//加载更多商城订单
require( '../../../../../../wp-load.php' );
$user_id=$current_user->ID;
if(!current_user_can('level_10')){exit();}
$page=$_POST['page'];
$number=$_POST['number'];
$offset=($page-1)*$number;
global $wpdb;
$table_name=$wpdb->prefix.'jin_shop_order';

//查询全部订单
if(isset($_POST['type'])&&$_POST['type']=='a'){;	
$data_a=$wpdb->get_results("SELECT * FROM $table_name ORDER BY time desc limit $offset,$number;");
require('../stencil/shop-order-html.php');
}

//待发货订单
if(isset($_POST['type'])&&$_POST['type']=='b'){;	
$data_a=$wpdb->get_results("SELECT * FROM $table_name where status=1 ORDER BY time desc limit $offset,$number;");
require('../stencil/shop-order-html.php');
}

//未付款订单
if(isset($_POST['type'])&&$_POST['type']=='c'){;	
$data_a=$wpdb->get_results("SELECT * FROM $table_name where status=0 ORDER BY time desc limit $offset,$number;");
require('../stencil/shop-order-html.php');
}

//待评论订单
if(isset($_POST['type'])&&$_POST['type']=='d'){;	
$data_a=$wpdb->get_results("SELECT * FROM $table_name where status=2 ORDER BY time desc limit $offset,$number;");
require('../stencil/shop-order-html.php');
}


//已完成订单
if(isset($_POST['type'])&&$_POST['type']=='e'){;	
$data_a=$wpdb->get_results("SELECT * FROM $table_name where status=3 ORDER BY time desc limit $offset,$number;");
require('../stencil/shop-order-html.php');
}