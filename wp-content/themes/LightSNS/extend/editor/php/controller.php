<?php
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
require( '../../../../../../wp-load.php' );
date_default_timezone_set(get_option('timezone_string'));
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");

$CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("config.json")), true);
$action = $_GET['action'];

switch ($action) {
case 'config':
// if(isset($CONFIG['imageUrlPrefix'])){
// $CONFIG['imageUrlPrefix']=home_url();
// }
echo json_encode($CONFIG);
break;

/* 截图 */
case 'uploadimage':
require("../../../module/upload/screenshot.php");
break;

/* 抓取远程文件 */
// case 'catchimage':
// $result = include("action_crawler.php");
// break;

}


// echo $result;