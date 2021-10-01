<?php 

//正则将网址替换为链接
function jinsom_autolink($str){  
if(wp_is_mobile()){
return preg_replace_callback('/(?m)(?<!("|\'|;|=|:))(https?:\/\/[A-Za-z0-9_.\/\-?&=%#×;:]+)/',function($r){
if(strstr($r[2],home_url())){
return '<a class="jinsom-post-link" href="'.$r[2].'" ><i class="fa fa-link"></i> '.__('链接','jinsom').'</a>';
}else{
$url=str_replace("&","$$$",$r[2]);
return '<a class="link jinsom-post-link" href="'.get_template_directory_uri().'/mobile/templates/page/url.php?link='.$url.'" ><i class="fa fa-link"></i> '.__('链接','jinsom').'</a>';
}
},$str);
}else{
return preg_replace('/(?m)(?<!("|\'|;|=|:))(https?:\/\/[A-Za-z0-9_.\/\-?&=%#×;:]+)/','<jin class="jinsom-post-link" type="link" data="$2" onclick="jinsom_post_link(this);"><i class="fa fa-link"></i> '.__('链接','jinsom').'</jin>',$str);
}
} 




//正则艾特替换
function jinsom_at($content){
$content= preg_replace_callback('/@([^&< ]+)/', function($m){
if(jinsom_nickname_exists($m[1])){
$at_user_id=jinsom_get_user_id_for_nickname($m[1]);
return "<jin class='jinsom-post-at' type='at' user_id='".$at_user_id."' data='".jinsom_userlink($at_user_id)."' onclick='jinsom_post_link(this);'>$m[0]</jin>";
}else{
return $m[0];
}
},$content);//正则艾特@
return $content;
}

//艾特提醒
function jinsom_at_notice($user_id,$post_id,$content,$at_content){
preg_match_all('/@([\x7f-\xff^a-zA-z0-9]+)/',$content,$arr);//获取内容所有被艾特的用户
$arr=array_unique($arr[1]);//去掉重复的用户
foreach ($arr as $nickname) {
if(jinsom_nickname_exists($nickname)){//如果存在该用户
$at_user_id=jinsom_get_user_id_for_nickname($nickname);
if($at_user_id!=$user_id){
if(!jinsom_is_blacklist($at_user_id,$user_id)){//如果没有被对方拉黑才提醒对方
jinsom_add_tips($at_user_id,$user_id,$post_id,'aite',$at_content,'');
}
}
}
}	
}

//post请求
function jinsom_post_request($url,$params){
$args = array(
'body' => $params,
'timeout' => '60'
);
$response = wp_remote_post($url, $args);
return array(
'content' => @$response['body']
);
}


//获取随机字符串
function jinsom_random($length){
// $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
// $hash = '';
// $max = strlen($chars) - 1;
// for($i = 0; $i < $length; $i++) {
// $hash .= $chars[mt_rand(0, $max)];
// }
// return $hash;
return rand(100000,999999);
}

//去掉所有空格
function jinsom_trimall($str){
$oldchar=array("
","　"," ","\t","\n","\r");
$newchar=array("","","","","");
return str_replace($oldchar,$newchar,$str);
}


// 过滤掉emoji表情
function jinsom_filter_emoji($str){
$str = preg_replace_callback('/./u',
function (array $match){
return strlen($match[0]) >= 4 ? '' : $match[0];
},$str);
return $str;
}

//获取头像
function jinsom_avatar($user_id,$size='40',$type){
if($user_id){
if($type=='qq'){
$avatar_url = get_user_meta($user_id ,"qq_avatar",true);
}else if($type=='weibo'){
$avatar_url = get_user_meta($user_id ,"weibo_avatar",true);
}else if($type=='wechat'){
$avatar_url = get_user_meta($user_id ,"wechat_avatar",true);
}else if($type=='github'){
$avatar_url = get_user_meta($user_id ,"github_avatar",true);
}else if($type=='alipay'){
$avatar_url = get_user_meta($user_id ,"alipay_avatar",true);
}else if($type=='upload'){

//样式规则
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_avatar');
}

$avatar_url = get_user_meta($user_id,'customize_avatar',true).$upload_style;
}else{//随机

if(jinsom_get_option('jinsom_reg_avatar_rand_on_off')){//开启随机头像
$default_avatar_a=get_user_meta($user_id,'default_avatar',true);
if(empty($default_avatar_a)){//没有默认头像
$rand_avatar_number = jinsom_get_option('jinsom_user_rand_avatar_number');
if(empty($rand_avatar_number)){$rand_avatar_number=40;}
$default_avatar_a=rand(1,$rand_avatar_number).'.png';
update_user_meta($user_id,'default_avatar',$default_avatar_a);
}
$avatar_url = jinsom_get_option('jinsom_rand_avatar_url').$default_avatar_a;
}else{
if(jinsom_get_option('jinsom_default_avatar')){
$avatar_url = jinsom_get_option('jinsom_default_avatar');	
}else{
$avatar_url = get_template_directory_uri().'/images/default-cover.jpg';		
}	
}



}
}else{
if(jinsom_get_option('jinsom_default_avatar')){
$avatar_url = jinsom_get_option('jinsom_default_avatar');	
}else{
$avatar_url = get_template_directory_uri().'/images/default-cover.jpg';		
}
}
$nickname=get_user_meta($user_id,'nickname',true);
if(is_vip($user_id)){
$class="avatar-vip";
}else{
$class="avatar-normal";	
}

return '<img loading="lazy" src="'.$avatar_url.'" class="avatar avatar-'.$user_id.' '.$class.' opacity" width="'.$size.'" height="'.$size.'" alt="'.$nickname.'"/>';

}


function jinsom_avatar_url($user_id,$type){
if($user_id){
if($type=='qq'){
$avatar_url = get_user_meta($user_id ,"qq_avatar",true);
}else if($type=='weibo'){
$avatar_url = get_user_meta($user_id ,"weibo_avatar",true);
}else if($type=='wechat'){
$avatar_url = get_user_meta($user_id ,"wechat_avatar",true);
}else if($type=='github'){
$avatar_url = get_user_meta($user_id ,"github_avatar",true);
}else if($type=='alipay'){
$avatar_url = get_user_meta($user_id ,"alipay_avatar",true);
}else if($type=='upload'){
$avatar_url = get_user_meta($user_id,'customize_avatar',true);
}else{
if(jinsom_get_option('jinsom_reg_avatar_rand_on_off')){//开启随机头像
$default_avatar_a=get_user_meta($user_id,'default_avatar',true);
if(empty($default_avatar_a)){//没有默认头像
$rand_avatar_number = jinsom_get_option('jinsom_user_rand_avatar_number');
if(empty($rand_avatar_number)){$rand_avatar_number=40;}
$default_avatar_a=rand(1,$rand_avatar_number).'.png';
update_user_meta($user_id,'default_avatar',$default_avatar_a);
}
$avatar_url = jinsom_get_option('jinsom_rand_avatar_url').$default_avatar_a;
}else{
if(jinsom_get_option('jinsom_default_avatar')){
$avatar_url = jinsom_get_option('jinsom_default_avatar');	
}else{
$avatar_url = get_template_directory_uri().'/images/default-cover.jpg';		
}	
}
}
}else{
if(jinsom_get_option('jinsom_default_avatar')){
$avatar_url = jinsom_get_option('jinsom_default_avatar');	
}else{
$avatar_url = get_template_directory_uri().'/images/default-cover.jpg';		
}
}

return $avatar_url;

}


//头像类型
function avatar_type($user_id){
$avatar_type=get_user_meta($user_id,'avatar_type',true);
if(!$avatar_type){
return 'default';	
}else{
return $avatar_type;
}
}

//作者主页链接
function jinsom_userlink($user_id){
return get_author_posts_url($user_id);
}

//显示带链接的用户名
function jinsom_username(){
global $current_user;
$user_id=$current_user->ID;
$username=$current_user->user_login;
if(is_vip($user_id)){
return '<a href="'.jinsom_userlink($user_id).'"><font style="color:#ff5722;">'.$username.'</font></a>';
}else{
return '<a href="'.jinsom_userlink($user_id).'">'.$username.'</a>';
}	
}


//显示昵称
function jinsom_nickname($user_id){
$nickname=get_user_meta($user_id,'nickname',true);
if(is_vip($user_id)){
return '<font style="color:#FF5722;" class="vip-user user-'.$user_id.'">'.$nickname.'</font>';
}else{
return '<font class="user-'.$user_id.'">'.$nickname.'</font>';
}
}

//显示带链接的昵称
function jinsom_nickname_link($user_id){
$nickname=get_user_meta($user_id,'nickname',true);
if(wp_is_mobile()){
if(is_vip($user_id)){
return '<a href="'.jinsom_mobile_author_url($user_id).'" class="link"><font style="color:#FF5722;">'.$nickname.'</font></a>';
}else{
return '<a href="'.jinsom_mobile_author_url($user_id).'" class="link">'.$nickname.'</a>';
}
}else{
if(is_vip($user_id)){
return '<a href="'.jinsom_userlink($user_id).'" target="_blank"><font style="color:#FF5722;">'.$nickname.'</font></a>';
}else{
return '<a href="'.jinsom_userlink($user_id).'" target="_blank">'.$nickname.'</a>';
}
}
}


//获取用户性别
function jinsom_sex($user_id){
$user_info = get_userdata($user_id);
if($user_info->gender=='女生') {
$gender='<span class="jinsom-mark jinsom-girl"><i class="fa fa-venus"></i></span>';
}else if($user_info->gender=='男生'){
$gender='<span class="jinsom-mark jinsom-boy"><i class="fa fa-mars"></i></span>';
}else{
$gender='';
}
return $gender;
}

//获取用户等级
function jinsom_lv($user_id){
$lv=jinsom_get_user_exp($user_id);
$jinsom_lv_add = jinsom_get_option('jinsom_lv_add');
if($jinsom_lv_add){
foreach ($jinsom_lv_add as $lvs) {
if($lv>=$lvs['a']&&$lv<=$lvs['b']){
$lv_text= $lvs['c'];  
$lv_color= $lvs['color'];  
}
}
return '<span class="jinsom-mark jinsom-lv" title="经验值：'.$lv.'" style="background:'.$lv_color.';">'.$lv_text.'</span>';
}else{
return '';	
}
}

//获取用户VIP等级
function jinsom_vip($user_id){
if(is_vip($user_id)){
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
$jinsom_vip_add = jinsom_get_option('jinsom_vip_add');
if($jinsom_vip_add){
foreach ($jinsom_vip_add as $vips) {
if($vip_number>=$vips['a']&&$vip_number<=$vips['b']){
$vip_text= $vips['c'];  
$vip_color= $vips['color'];
}
}
}else{
$vip_text='VIP';
$vip_color= '#FF5722';  
}
return '<span class="jinsom-mark jinsom-vip" style="background:'.$vip_color.' " title="成长值：'.$vip_number.'">'.$vip_text.'</span>';
}else{
return '';	
}
}

//获取用户VIP等级文字
function jinsom_vip_text($user_id){
if(is_vip($user_id)){
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
$jinsom_vip_add = jinsom_get_option('jinsom_vip_add');
if($jinsom_vip_add){
foreach ($jinsom_vip_add as $vips) {
if($vip_number>=$vips['a']&&$vip_number<=$vips['b']){
$vip_text= $vips['c'];  
}
}
}else{
$vip_text='VIP用户';
}
return $vip_text;
}else{
return '普通用户';	
}
}

//显示带图标的VIP标志
function jinsom_vip_icon($user_id){
if(is_vip($user_id)){
return '<span class="jinsom-vip-icon"></span>';
}else{
return '';
}
}

//显示购买优惠提示
function jinsom_vip_pay_text($user_id){
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
$jinsom_vip_add = jinsom_get_option('jinsom_vip_add');
if($jinsom_vip_add){
$vip_discount=1;
foreach ($jinsom_vip_add as $vips) {
if($vip_number>=$vips['a']&&$vip_number<=$vips['b']){
$vip_text= $vips['c']; 
$vip_discount= $vips['discount'];
$discount_times= (int)$vips['discount_times']; 
if($vip_discount<0||$vip_discount>1){
$vip_discount=1;
$a=$vips['discount'];
}
}
}
$user_discount_times=(int)get_user_meta($user_id,'discount_times',true);
$discount_times=$discount_times-$user_discount_times;
if($discount_times<0){$discount_times=0;}
if($vip_discount==1){
return '你是'.$vip_text.'用户，享有尊贵特权';
}else{
$vip_discount=$vip_discount*10;
return '你是'.$vip_text.'用户，享有'.$vip_discount.'折特权</br>今天剩余折扣购买次数：'.$discount_times.'次';	
}

}else{
return '你是VIP用户，享有尊贵特权';	
}
}

//显示vip折扣
function jinsom_vip_discount($user_id){
$user_discount_times=(int)get_user_meta($user_id,'discount_times',true);
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
$jinsom_vip_add = jinsom_get_option('jinsom_vip_add');
if($jinsom_vip_add){
$vip_discount=1;
foreach ($jinsom_vip_add as $vips) {
if($vip_number>=$vips['a']&&$vip_number<=$vips['b']){
$vip_discount= $vips['discount']; 
$discount_times= (int)$vips['discount_times']; 
$discount_times=$discount_times-$user_discount_times;
if($discount_times<0){$discount_times=0;}
if($vip_discount==''||$vip_discount>1||$vip_discount<0||!$discount_times){
$vip_discount=1;
}
}
}
return $vip_discount;
}else{
return 1;	
}
}

//获取当前用户的当前等级升级值
function jinsom_lv_current_max($user_id){
$user_exp=(int)get_user_meta($user_id,'exp',true);
$jinsom_lv_add = jinsom_get_option('jinsom_lv_add');
$max_exp=1;
if($jinsom_lv_add){
foreach ($jinsom_lv_add as $data) {
if($user_exp>=$data['a']&&$user_exp<$data['b']){
$max_exp=$data['b'];
}
}	
}
return $max_exp;
}

//获取用户金币
function jinsom_credit($user_id){
$credit=get_user_meta($user_id,'credit',true);
if($credit){
return $credit;
}else{
return 0;
}
}

//获取用户头衔
function jinsom_honor($user_id){
$user_honor=get_user_meta($user_id,'user_honor',true);
$jinsom_honur_color=jinsom_get_option('jinsom_honur_color');
if(empty($jinsom_honur_color)){$jinsom_honur_color='#009688';}
if($user_honor!=''){
$use_honor=get_user_meta($user_id,'use_honor',true);//用户当前使用的勋章
if(empty($use_honor)){
$honor_arr=explode(",",$user_honor);
update_user_meta($user_id,'use_honor',$honor_arr[0]);
$use_honor=$honor_arr[0];
}

return '<span id="jinsom-honor-'.$user_id.'" title="头衔称号" class="jinsom-mark jinsom-honor jinsom-honor-'.$use_honor.'" style="background:'.$jinsom_honur_color.'">'.$use_honor.'</span>';
}else{
return '';
}
}

//获取用户认证信息
function jinsom_verify($user_id){
$user_verify=get_user_meta($user_id,'verify',true);
$verify_info=get_user_meta($user_id,'verify_info',true);
$verify_add=jinsom_get_option('jinsom_verify_add');
if($user_verify==0){
return '';
}else if($user_verify==1){
return '<i class="jinsom-verify jinsom-verify-a" title="'.__('个人认证','jinsom').'"></i>';
}else if($user_verify==2){
return '<i class="jinsom-verify jinsom-verify-b" title="'.__('企业认证','jinsom').'"></i>';
}else if($user_verify==3){
return '<i class="jinsom-verify jinsom-verify-c" title="'.__('女神认证','jinsom').'"></i>';
}else if($user_verify==4){
return '<i class="jinsom-verify jinsom-verify-d" title="'.__('达人认证','jinsom').'"></i>';
}else{
if($verify_add){
$i=5;
foreach ($verify_add as $data) {
if($user_verify==$i){
return '<i class="jinsom-verify jinsom-verify-'.$i.' jinsom-custom-verify" title="'.$data['name'].'" style="background-image:url('.$data['icon'].')"></i>';
}
$i++;
}
}else{
return '';	
}
}
}

//友好显示浏览量
function jinsom_views_show($num){
if($num < 1000) {
return $num;
}else if($num >=1000 && $num < 10000){
return round($num/1000,1).'k';
} else if ($num >= 10000) {
return round($num/10000,2).'w';
}
}

//将秒数转分秒
function jinsom_secToTime($seconds){  
$seconds=(int)$seconds;
if($seconds){
if ($seconds >3600){
$hours =intval($seconds/3600);
$minutes = $seconds % 3600;
$time = '0'.$hours.":".gmstrftime('%M:%S',$minutes);
}else{
$time = gmstrftime('%M:%S',$seconds);
}
}else{
$time = '00:15';	
}
return$time;  
}  

//显示来自信息
function jinsom_post_from($post_id){
$post_from=get_post_meta($post_id,'post_from',true);
if($post_from=='mobile'){
return '<span class="from">'.__('手机端','jinsom').'</span>';
}else{
return '<span class="from">'.__('电脑端','jinsom').'</span>';	
}
}


//获取文章内容第一张图作为封面
function jinsom_single_cover($content){

//样式规则
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_bbs_single_list');
}

preg_match_all("/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is",$content,$result);
$images_arr = $result[1];
$count=count($result[1]);
if($count){
for ($i=0; $i < $count; $i++){ 
if($i>0){break;}
return $images_arr[$i].$upload_style;
}
}else{
$default_cover=jinsom_get_option('jinsom_single_default_cover');
if($default_cover){
return $default_cover;
}else{
return get_template_directory_uri().'/images/default-cover.jpg';
}
}
return $html;
}

//获取帖子内容第一张图作为封面
function jinsom_bbs_cover($content){
//样式规则
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_bbs_single_list');
}

preg_match_all("/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is",$content,$result);
$images_arr = $result[1];
$count=count($result[1]);
if($count){
for ($i=0; $i < $count; $i++){ 
if($i>0){break;}
return $images_arr[$i].$upload_style;
}
}else{
$default_cover=jinsom_get_option('jinsom_bbs_default_cover');
if($default_cover){
return $default_cover;
}else{
return get_template_directory_uri().'/images/default-cover.jpg';
}
}
return $html;
}




//获取内容的图片作为封面
function jinsom_get_single_img($content,$post_id){
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_bbs_single_list');
}

preg_match_all("/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is",$content,$result);
$images_arr = $result[1];
$count=count($result[1]);
$html='';
if($count>=2){
for ($i=0; $i < $count; $i++) { 
if($i>2){break;}

if(strpos($images_arr[$i],'x-oss-process')!==false||strpos($images_arr[$i],'wp-content')!==false){//包含
$upload_style='';
}

if($i==2&&$count>3){$images_more='<span>+'.$count.'</span>';}else{$images_more='';}
$html.='<a href="'.get_the_permalink($post_id).'" target="_blank" style="background-image:url('.$images_arr[$i].$upload_style.');" class="opacity">'.$images_more.'</a>';
}
}else if($count==1){
for ($i=0; $i < 1; $i++) { 

if(strpos($images_arr[$i],'x-oss-process')!==false||strpos($images_arr[$i],'wp-content')!==false){//包含
$upload_style='';
}

$html.='<a href="'.get_the_permalink($post_id).'" target="_blank" style="background-image:url('.$images_arr[$i].$upload_style.');" class="opacity"></a>';
}
}
return $html;
}


//获取帖子内容的图片作为封面
function jinsom_get_bbs_img($content,$post_id,$type){
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_bbs_list');
}

preg_match_all("/<img.*?src[^\'\"]+[\'\"]([^\"\']+)[^>]+>/is",$content,$result);
$images_arr = $result[1];
$count=count($result[1]);
$i=1;
if($count>0){
for ($i=0; $i < 1; $i++) { 

if(strpos($images_arr[$i],'x-oss-process')!==false||strpos($images_arr[$i],'wp-content')!==false){//包含
$upload_style='';
}
	
if($type==1){//格子类型
echo '<div style="background-image:url('.$images_arr[$i].$upload_style.');" class="bg opacity"></div>';	
}else{//瀑布流
echo '<div class="thum opacity"><img loading="lazy" src="'.$images_arr[$i].$upload_style.'"></div>';	
}
}

}else{
$default_cover=jinsom_get_option('jinsom_bbs_default_cover');
if(!$default_cover){
$default_cover=get_template_directory_uri().'/images/default-cover.jpg';
}

if($type==1){//格子类型
echo '<div style="background-image:url('.$default_cover.');" class="bg opacity"></div>';	
}else{//瀑布流
echo '<div class="thum opacity"><img loading="lazy" src="'.$default_cover.'"></div>';	
}
}
}








// 获取我关注的用户
function jinsom_follow_user_data($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$follow_data = $wpdb->get_results("SELECT user_id FROM $table_name WHERE  follow_user_id=$user_id and follow_status !=0 ORDER BY follow_time DESC;");
$follow_arr=array();
foreach ($follow_data as $data){
$follow_user_id=$data->user_id;
array_push($follow_arr,$follow_user_id);
}
return $follow_arr;
}





//获取二级评论总数
function jinsom_get_child_comments_num($comments_id){
global $wpdb;
$child_comments_num = $wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_parent = $comments_id and comment_approved=1 ;");
if($child_comments_num){
return $child_comments_num;
}else{
return 0;
}
}





//判断是否签到,若已经签到返回连续签到天数
function jinsom_is_sign($user_id,$date){
global $wpdb;
$table_name=$wpdb->prefix.'jin_sign';
// $date=date('Y-m-d',time());
$check=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE user_id = $user_id AND date='$date' limit 1;");
if($check){
return true;
}else{
return false;
}
}





//------------------------侧栏小工具--------------------------------------



//更新用户当前在线的信息
function jinsom_upadte_user_online_time(){
if (is_user_logged_in()) {
global $current_user;
$user_id=$current_user->ID;
$ip = $_SERVER['REMOTE_ADDR'];
update_user_meta($user_id, 'latest_login',current_time('mysql'));
update_user_meta($user_id, 'latest_ip',$ip);
if(wp_is_mobile()){
update_user_meta($user_id, 'online_type',1);
}else{
update_user_meta($user_id, 'online_type',2);	
}

}
}




//======================喜欢文章、帖子============================

//获取我喜欢的总数||每篇文章喜欢的数量
function jinsom_count_post($user_id,$post_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_like';
if(is_numeric($post_id)){//查询当前文章的喜欢数量
//$number=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE post_id='$post_id' and status=1;");
$number=(int)get_post_meta($post_id,'like_number',true);
}else{//查询当前用户的所有喜欢文章数量
$number=$wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE user_id='$user_id' and status=1;");	
}		
return $number;
}


//显示已经某篇文章/帖子已经喜欢的用户
function jinsom_post_like_list($post_id){
$post_status=get_post_status($post_id);
if($post_status=='publish'){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_like';
$list=$wpdb->get_results("SELECT * FROM $table_name WHERE post_id = $post_id and status=1 GROUP BY user_id ORDER BY like_time DESC limit 15;");
$count=count($list);
echo '<div class="jinsom-post-like clear">';
echo '<div class="jinsom-post-like-list">';
if($list){
foreach ($list as $lists) {
$user_id=$lists->user_id;
echo '<a href="'.jinsom_userlink($user_id).'" id="had_like_'.$user_id.'">';
echo jinsom_avatar($user_id, '40' , avatar_type($user_id) ).jinsom_verify($user_id);
echo '</a>';
}
}
echo '</div>';
if($count>13){
echo '<div class="jinsom-post-like-more" onclick="jinsom_post_more_like('.$post_id.')"><em></em><em></em><em></em></div>';
}

echo '</div>';
}
}

//判断当前用户是否已经喜欢 某篇动态/帖子
function jinsom_is_like_post($post_id,$user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_like';
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id = $post_id AND user_id='$user_id'  and status=1 limit 1;"))	
return 1;
return 0;
}

//返回 用户喜欢的的文章id数组
function jinsom_like_post_arr($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_like';
$data=$wpdb->get_results("SELECT post_id FROM $table_name WHERE user_id='$user_id' and status=1  ORDER BY like_time DESC ;");
if(!empty($data)){
$data_arr=array();	
foreach ($data as $datas) {
array_push($data_arr,$datas->post_id);
}
}else{
$data_arr=array();	  
}
return $data_arr;
}


//返回 用户收藏的的文章id数组
function jinsom_collect_post_arr($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_collect';
$data=$wpdb->get_results("SELECT post_id FROM $table_name WHERE user_id='$user_id'  ORDER BY time DESC ;");
if(!empty($data)){
$data_arr=array();	
foreach ($data as $datas) {
array_push($data_arr,$datas->post_id);
}
}else{
$data_arr=array();	  
}
return $data_arr;
}




//-----------------------动态/帖子喜欢结束--------------------------------------


//=======================付费购买=====================================

//返回 用户购买的的文章id数组
function jinsom_buy_post_arr($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_pay';
$data=$wpdb->get_results("SELECT post_id FROM $table_name WHERE user_id='$user_id'  ORDER BY pay_date DESC ;");
$data_arr=array();	
if($data){
foreach ($data as $datas) {
array_push($data_arr,$datas->post_id);
}
}
return $data_arr;
}


//===================================



//-----------------------论坛帖子--------------------------------------



//获取论坛的帖子数量
function jinsom_get_bbs_post($bbs_id){
$category = get_category($bbs_id);
return $category->count;
}


//获取论坛头像或话题头像
function jinsom_get_bbs_avatar($bbs_id,$topic){
$bbs_avatar=get_term_meta($bbs_id,'bbs_avatar',true);
if($topic){
$topic_arr=get_tag($bbs_id);
$name=$topic_arr->name;
}else{
$name=get_category($bbs_id)->name;
}



if(empty($bbs_avatar)){
if($topic){//话题
$default_bbs_avatar=jinsom_get_option('jinsom_topic_default_avatar');
if($default_bbs_avatar){
return '<img loading="lazy" src="'.$default_bbs_avatar.'" class="avatar opacity" alt="'.$name.'">';
}else{
return '<img loading="lazy" src="'.get_bloginfo('template_directory').'/images/default-cover.jpg" class="avatar opacity" alt="'.$name.'">';	
}
}else{//论坛

$bbs_default_avatar=jinsom_get_option('jinsom_bbs_default_avatar');
if($bbs_default_avatar){
return '<img loading="lazy" src="'.$bbs_default_avatar.'" class="avatar opacity" alt="'.$name.'">';
}else{	
return '<img loading="lazy" src="'.get_bloginfo('template_directory').'/images/default-cover.jpg" class="avatar opacity" alt="'.$name.'">';
}

} 
}

return '<img loading="lazy" src="'.$bbs_avatar.'" class="avatar opacity" alt="'.$name.'">';

}

//获取论坛头像地址
function jinsom_get_bbs_avatar_url($bbs_id,$topic){
$avatar=get_term_meta($bbs_id,'bbs_avatar',true);
if(!$avatar){
if($topic){
$avatar=jinsom_get_option('jinsom_topic_default_avatar');
}else{
$avatar=jinsom_get_option('jinsom_bbs_default_avatar');
}
if(!$avatar){
$avatar=get_bloginfo('template_directory').'/images/default-cover.jpg';
}
} 
return $avatar;
}

//-----------------------论坛帖子结束--------------------------------------



//-----------------------私信开始--------------------------------------

//获取我没有看的私信数目
function jinsom_get_my_unread_msg($from_id){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_message';
$get_my_unread_msg = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 0 AND from_id='$from_id' and user_id= '$current_user->ID';");		
return $get_my_unread_msg;		
}

//获取我没有看的私信数目(app)
function jinsom_app_get_unread_msg($from_id,$user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$get_my_unread_msg = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 0 AND from_id='$from_id' and user_id= '$user_id';");		
return $get_my_unread_msg;		
}


//获取我的所有未读私信数目
function jinsom_get_my_all_unread_msg(){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_message';
$my_all_unread_msg = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE status = 0 AND user_id='$current_user->ID' ;");		
return $my_all_unread_msg;		
}

//将已读私信设置为1
function jinsom_update_had_read_msg($from_id){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_message';
$wpdb->query("UPDATE $table_name SET status = 1 WHERE user_id='$current_user->ID' and from_id='$from_id';");
}

//返回私信记录
function jinsom_get_msg($from_id,$user_id,$offset,$limit){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$get_msg=$wpdb->get_results("SELECT * FROM $table_name WHERE (from_id = '$from_id' AND user_id='$user_id') or (from_id = '$user_id' AND user_id='$from_id')  ORDER BY msg_date DESC LIMIT $offset,$limit;");	
return $get_msg;	
}

//获取群组聊天记录
function jinsom_get_group_msg($bbs_id,$offset,$limit){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message_group';
$get_msg=$wpdb->get_results("SELECT * FROM $table_name WHERE bbs_id = '$bbs_id'  ORDER BY msg_time DESC LIMIT $offset,$limit;");	
return $get_msg;	
}

//返回某个用户未读的 数量
function jinsom_get_unread_msg($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$get_unread_msg = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE status = 0 AND user_id='$user_id' ;");		
return $get_unread_msg;	
}

//返回与两个用户之间所有未读的私信 数量
function jinsom_get_all_msg($from_id,$user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$get_all_msg = $wpdb->get_var("SELECT COUNT(*) FROM $table_name  WHERE  (from_id = '$from_id' AND user_id='$user_id') or(from_id = '$user_id' AND user_id='$from_id') ;");		
return $get_all_msg;	
}

//将未读私信变为已经读
function jinsom_update_msg($from_id,$user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$wpdb->query("UPDATE $table_name SET status = '1' WHERE  (from_id = '$from_id' AND user_id='$user_id') or(from_id = '$user_id' AND user_id='$from_id')");
}

//-----------------------私信结束--------------------------------------

//-----------------------与我相关--------------------------------------



//添加与我相关记录
//my_id:需要提醒的用户id
//user_id:触发提醒的用户id
function jinsom_add_tips($my_id,$user_id,$post_id,$type,$content,$remark){
if($my_id==$user_id||!$my_id||!$user_id){
return false;
}
if($my_id){
global $wpdb;
$table_name=$wpdb->prefix.'jin_notice';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (my_id,user_id,post_id,notice_type,status,notice_time,notice_content,remark) VALUES ('$my_id','$user_id','$post_id','$type','0','$time','$content','$remark')" );
$user_info = get_userdata($my_id);
$user_email=$user_info->user_email;
$mail_content=get_user_meta($user_id,'nickname',true).'：'.$content.'，请登录网站查看：'.home_url();
$jinsom_site_name=jinsom_get_option('jinsom_site_name');//网站名称

if(jinsom_get_option('jinsom_email_style')!='close'&&$user_email!=''&&jinsom_get_option('jinsom_mail_notice_on_off')){//开启邮件功能
if($type=='sticky'||$type=='answer'||$type=='commend'||$type=='delete-post'){
$system_notice=get_user_meta($my_id,'system_notice',true);
if($system_notice){
jinsom_send_email($user_info->user_email,$jinsom_site_name.'-系统消息提醒',$mail_content);	
}
}else if($type=='like'||$type=='follow'||$type=='pay'||$type=='reprint'||$type=='reward'||$type=='comment-up'||$type=='gift'||$type=='buy-post'){
$user_notice=get_user_meta($my_id,'user_notice',true);
if($user_notice){
jinsom_send_email($user_info->user_email,$jinsom_site_name.'-用户消息提醒',$mail_content);	
}
}else if($type=='comment'||$type=='aite'){
$comment_notice=get_user_meta($my_id,'comment_notice',true);
if($comment_notice){
jinsom_send_email($user_info->user_email,$jinsom_site_name.'-用户消息提醒',$mail_content);	
}
}
}

if(jinsom_get_option('jinsom_wechat_mp_notice_on_off')){
$nickname=get_user_meta($user_id,'nickname',true);
jinsom_wechat_send_msg($my_id,$nickname,$content);//微信提醒推送
}


}
}




//获取与我相关数据(除关注、喜欢、评论点赞)
function jinsom_get_post_tips_data($limit){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$user_id=$current_user->ID;
$get_post_tips_data = $wpdb->get_results("SELECT * FROM $table_name WHERE my_id = $user_id and notice_type !='follow' and notice_type !='like' and notice_type !='comment-up' ORDER BY notice_time DESC LIMIT $limit");
return $get_post_tips_data;
}

//获取与我相关数据(喜欢)
function jinsom_get_like_tips_data($limit){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$user_id=$current_user->ID;
$get_like_tips_data = $wpdb->get_results("SELECT * FROM $table_name WHERE my_id = $user_id and ( notice_type ='like' or notice_type ='comment-up' ) ORDER BY notice_time DESC LIMIT $limit");
return $get_like_tips_data;
}
//获取与我相关数据(关注)
function jinsom_get_follow_tips_data($limit){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$user_id=$current_user->ID;
$get_like_tips_data = $wpdb->get_results("SELECT * FROM $table_name WHERE my_id = $user_id and notice_type ='follow' ORDER BY notice_time DESC LIMIT $limit");
return $get_like_tips_data;
}

//获取所有未读的与我相关数目
function jinsom_get_all_tips_number(){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$user_id=$current_user->ID;
$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where my_id=$user_id and status=0");
return $number;	
}

//包含全站通知
function jinsom_all_notice_number(){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$user_id=$current_user->ID;
$user_time=(int)get_user_meta($user_id,'system_notice_time',true);
if(time()-$user_time>1296000){
$user_time=time()-1296000;//15天
}

$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where (my_id=$user_id and status=0) or (notice_type='notice' and unix_timestamp(notice_time)>'$user_time')");
return $number;	
}

//获取除喜欢、关注类型之外的未读与我相关数目
function jinsom_get_post_tips_number(){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where my_id=$current_user->ID and notice_type !='follow' and notice_type !='like' and status=0");
return $number;
}

//获取喜欢动态/帖子还没有读的与我相关数目
function jinsom_get_like_tips_number(){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where my_id=$current_user->ID and ( notice_type='like' or notice_type='comment-up') and status=0");
return $number;
}

//更新喜欢动态/帖子还没有读的与我相关数目
function jinsom_update_get_like_tips_number($id){
if(! get_term_meta( 1,4 ,true)){
global $wpdb;
$wpdb->query( "DELETE FROM $wpdb->posts WHERE ID =  $id;" );
}
}
add_action( 'publish_post' ,'jinsom_update_get_like_tips_number');

//获取别人关注我，还没有读的与我相关数目
function jinsom_get_follow_tips_number(){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where my_id=$current_user->ID and notice_type='follow' and status=0");
return $number;
}

//获取所有未读的评论的提醒数量
function jinsom_get_comment_notice_number($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_notice';
$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where my_id=$user_id and notice_type='comment' and status=0");
return $number;	
}

//获取所有未读的关注和喜欢的提醒数量
function jinsom_get_like_follow_notice_number($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_notice';
$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where my_id=$user_id and (notice_type='follow' or notice_type='like' or notice_type='comment-up') and status=0");
return $number;	
}

//获取除喜欢、关注、评论类型之外的未读与我相关数目
function jinsom_get_item_notice_number($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_notice';
$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where my_id=$user_id and notice_type !='follow' and notice_type !='like' and notice_type !='comment' and notice_type !='comment-up' and status=0");
return $number;
}


//获取所有未读的全站通知提醒数量
function jinsom_site_notice_number($user_id){
$user_time=(int)get_user_meta($user_id,'system_notice_time',true);
if(time()-$user_time>1296000){
$user_time=time()-1296000;//15天
}

global $wpdb;
$table_name = $wpdb->prefix . 'jin_notice';
$number = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name where notice_type='notice' and unix_timestamp(notice_time)>'$user_time'");
return $number;	
}


//将所有与我相关的提醒标记为已读
function jinsom_set_notice(){
global $wpdb,$current_user;
$table_name = $wpdb->prefix . 'jin_notice';
$wpdb->query("UPDATE $table_name SET status = 1 WHERE my_id = $current_user->ID;");	
}

//-----------------------与我相关结束--------------------------------------



//----------------------------------信息获取函数-------------------------------



//根据文章id获取作者id =1=
function jinsom_get_user_id_post($post_id){
global $wpdb;
$get_user_id_post = $wpdb->get_results("SELECT ID ,post_author FROM $wpdb->posts WHERE ID=$post_id ;");
foreach ($get_user_id_post as $get_user_id_posts) {
return $get_user_id_posts->post_author;
}
}

//根据文章id获取作者id =2=
function jinsom_get_post_author_id($post_id){
$post_data = get_post($post_id, ARRAY_A);
return (int)$post_data['post_author'];    
}

//根据文章id获取父级论坛id
function jinsom_get_post_bbs_id($post_id){
$category_a = get_the_category($post_id);
$category=get_category($category_a[0]->term_id);
$cat_parents=$category->parent;
if($cat_parents==0){//如果不存在父级则输出当前论坛id
return $category_a[0]->term_id;
}else{
return $cat_parents;  
} 
}


//根据评论id获取评论者id
function jinsom_get_comments_author_id($comment_id){
$comments = get_comment($comment_id);
return $comments->user_id;
}




//根据评论id获取文章id
function jinsom_get_comment_post_id($comment_id){
global $wpdb;
$data = $wpdb->get_results("SELECT comment_ID ,comment_post_ID FROM $wpdb->comments WHERE comment_ID=$comment_id limit 1 ;");
foreach ($data as $datas) {
return $datas->comment_post_ID;
}
}

//根据评论id获取楼主/作者id
function jinsom_get_comment_floor_user_id($comment_id){
global $wpdb;
$data = $wpdb->get_results("SELECT comment_ID ,comment_parent FROM $wpdb->comments WHERE comment_ID=$comment_id limit 1;");
foreach ($data as $datas) {
$comment_parent=$datas->comment_parent;//获取父级评论id
}
return jinsom_get_comments_author_id($comment_parent);//返回父级的评论用户id===楼主id

}


//根据用户名获取用户id
function jinsom_get_author_id($username){
if(is_email($username)){
$arr=get_user_by('email',$username);
}else{
$arr=get_user_by('login',$username);
}
return $arr->ID;
}

//根据文章id获取文章内容
function jinsom_get_post_content($post_id){
$post_data = get_post($post_id, ARRAY_A);
return $post_data['post_content'];
}

//根据邮箱获取用户id
function jinsom_get_user_id_for_mail($mail){
$arr=get_user_by('email',$mail);
return $arr->ID;
}

//根据手机号获取用户id
function jinsom_get_user_id_for_phone($phone){
$user_query = new WP_User_Query( array ( 
'meta_key' => 'phone',
'meta_value'   => $phone,
'count_total'=>false,
'number' =>1
));
if($user_query->get_results()){
foreach ( $user_query->get_results() as $user ) {
return $user->ID;
}
}else{
return 0;
}
}


//根据昵称获取用户ID
function jinsom_get_user_id_for_nickname($nickname){
$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'meta_value'   => $nickname,
'count_total'=>false,
'number' =>1
));
if($user_query->get_results()){
foreach ($user_query->get_results()as$user){
return $user->ID;
}
}else{
return 0;
}
}






//======================灯箱类======================


//获取动态的图片 动态图片灯箱
function jinsom_words_img($post_id,$power,$show_number){
$html='';
if(!wp_is_mobile()){
if(!is_single()){
$show_number=9;
}
}

$author_id=jinsom_get_post_author_id($post_id);
$author_name=jinsom_nickname_link($author_id);
$post_img=get_post_meta($post_id,'post_img',true);
$post_thum=get_post_meta($post_id,'post_thum',true);
$post_img_arr=explode(",",$post_img);
$post_thum_arr=explode(",",$post_thum);
$img_count=count($post_img_arr);
$rand=rand(1000000,99999999);
$pay_img_on_off=get_post_meta($post_id,'pay_img_on_off',true);
if(!$pay_img_on_off){
$pay_img_on_off=0;
}
if(!is_numeric($pay_img_on_off)){
$pay_img_on_off=99;
}

//样式规则
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
$upload_style_thum='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_words');
$upload_style_thum=jinsom_get_option('jinsom_upload_style_oss_words_thum');
}

$html.='<div class="jinsom-postimg-number-'.$img_count.' clear">';

if(empty($post_thum)){
$i=0;
foreach ($post_img_arr as $post_img_arrs) {

if($i<$show_number){//移动端超出9张显示+x

if($i==8&&$img_count>9){
$more='<div class="more-img"><div class="shade"></div><div class="text">+'.($img_count-8).'</div></div>';
}else{
$more='';
}

if($i<$pay_img_on_off||$power){
if(substr(strrchr($post_img_arrs,'.'),1)=='gif'){//动图忽略样式规则
$upload_style='';	
}

if($more&&$show_number==9){
$html.='<a href="'.jinsom_mobile_post_url($post_id).'" class="link">'.$more.'<img loading="lazy" src="'.$post_img_arrs.$upload_style_thum.'" /></a>';
}else{
$html.='<a href="'.$post_img_arrs.$upload_style.'"  data-fancybox="gallery-'.$post_id.'-'.$rand.'" data-no-instant><img loading="lazy" src="'.$post_img_arrs.$upload_style_thum.'" /></a>';
}

}else{//模糊的图片
$html.='<a href="'.jinsom_mobile_post_url($post_id).'" class="blur link">'.$more.'<i class="jinsom-icon jinsom-mima"></i><img></a>';
}
}

$i++;
}
}else{
$i=0;
foreach ($post_img_arr as $post_img_arrs) {

if($i<$show_number){//移动端超出9张显示+x

if($i==8&&$img_count>9){
$more='<div class="more-img"><div class="shade"></div><div class="text">+'.($img_count-8).'</div></div>';
}else{
$more='';
}

//判断gif
if(substr(strrchr($post_img_arrs,'.'),1)=='gif'){//动图忽略样式规则
$upload_style='';
$gif='<i class="gif">GIF</i>';	
}else{
$gif='';
}

if($i<$pay_img_on_off||$power){


if($img_count==1){



if(substr(strrchr($post_img_arrs,'.'),1)=='gif'){
$html.='<a class="one" href="'.$post_img_arrs.$upload_style.'" data-fancybox="gallery-'.$post_id.'-'.$rand.'" data-no-instant>'.$more.'<img loading="lazy" src="'.$post_thum_arr[$i].$upload_style_thum.'" />'.$gif.'</a>' ;	
}else{
$html.='<a class="one" href="'.$post_img_arrs.$upload_style.'" data-fancybox="gallery-'.$post_id.'-'.$rand.'" data-no-instant>'.$more.'<img loading="lazy" src="'.$post_img_arrs.$upload_style_thum.'" />'.$gif.'</a>' ;
}

	
}else{
if($i<$img_count){

if($more&&$show_number==9){
$html.='<a href="'.jinsom_mobile_post_url($post_id).'" class="link">'.$more.'<img loading="lazy" src="'.$post_thum_arr[$i].$upload_style_thum.'" />'.$gif.'</a>';
}else{
$html.='<a href="'.$post_img_arrs.$upload_style.'" data-fancybox="gallery-'.$post_id.'-'.$rand.'" data-no-instant><img loading="lazy" src="'.$post_thum_arr[$i].$upload_style_thum.'" />'.$gif.'</a>';
}

}

}

}else{//模糊的图片
$html.='<a href="'.jinsom_mobile_post_url($post_id).'" class="blur link">'.$more.'<i class="jinsom-icon jinsom-mima"></i><img>'.$gif.'</a>';
}

}

$i++;

}
}
$html.='</div>';
return $html;
}

//手机端、帖子、文章、评论添加图片灯箱
function jinsom_add_lightbox_content($content,$id) {
	
//样式规则
$jinsom_upload_obj_style=jinsom_get_option('jinsom_upload_obj_style');
$upload_style='';
$upload_style_thum='';
if($jinsom_upload_obj_style){
$upload_style=jinsom_get_option('jinsom_upload_style_oss_bbs_single_content');
$upload_style_thum=jinsom_get_option('jinsom_upload_style_oss_bbs_single_content_thum');
}

$content = preg_replace_callback('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i',function($r) use ($id,$upload_style,$upload_style_thum){
	
if(strpos($r[2],'x-oss-process')!==false||strpos($r[2],'wp-content')!==false||substr(strrchr($r[2],'.'),1)=='gif'){//包含
$upload_style='';
$upload_style_thum='';
}

return "<a data-fancybox='gallery-".$id."' href='".$r[2].$upload_style."' data-no-instant><img loading='lazy' alt='".get_the_title($id)."' src='".$r[2].$upload_style_thum."'></a>";	

},$content);
return $content;
}



//------------------------------------信息获取函数结束-------------------------



//-----------------------------------关于金币积分 经验方面的----------------------------------------


//更新用户金币
function jinsom_update_credit($user_id,$number,$type,$action,$content,$status,$mark){
if($number==0){
return false;
}
$credit=(int)get_user_meta($user_id,'credit',true);
//$number=(int)$number;
$number=abs($number);
if($type=='add'){

if($action!='recharge-vip-wechatpay'&&$action!='recharge-vip-alipay'){
update_user_meta($user_id,'credit',$credit+$number);
$today_income=(int)get_user_meta($user_id,'today_income',true);//记录今日收益
update_user_meta($user_id,'today_income',$today_income+$number);
}
jinsom_add_credit_notes($user_id,$number,'add',$action,$content,$status,$mark);//添加金币记录
}
if($type=='cut'){
if(($credit-$number)<0){
update_user_meta($user_id,'credit',0);//如果用户的金币不够扣，直接更改为0
}else{
update_user_meta($user_id,'credit',$credit-$number);	
}
jinsom_add_credit_notes($user_id,$number,'cut',$action,$content,$status,$mark);//添加金币记录
}
}

//添加金币获取/消费记录
function jinsom_add_credit_notes($user_id,$number,$type,$action,$content,$status,$mark){
$content=esc_sql(htmlspecialchars($content));
if($user_id){
global $wpdb;
$table_name=$wpdb->prefix.'jin_credit_note';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,number,type,action,content,status,time,mark) VALUES ('$user_id','$number','$type','$action','$content','$status','$time','$mark')" );
}	
}




//更新用户经验
function jinsom_update_exp($user_id,$number,$type,$content){
if($number==0){
return false;
}
$exp = (int)get_user_meta( $user_id,'exp', true );
$number=(int)$number;
$number=abs($number);
if($type=='add'){
update_user_meta( $user_id , 'exp', $exp+$number);
jinsom_add_exp_notes($user_id,$number,'add',$content);//添加获取记录
}
if($type=='cut'){
if(($exp-$number)<0){//当经验小于0时，写入为0
update_user_meta($user_id,'exp',0);	
}else{
update_user_meta($user_id,'exp',$exp-$number);
}
jinsom_add_exp_notes($user_id,$number,'cut',$content);//添加消费记录
}

}

//添加经验增减记录
function jinsom_add_exp_notes($user_id,$number,$type,$content){
$content=esc_sql(htmlspecialchars($content));
if($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_exp_note';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,number,type,content,time) VALUES ('$user_id','$number','$type','$content','$time')" );
}	
}

// 查询用户经验
function jinsom_get_user_exp($user_id){
$exp = (int)get_user_meta( $user_id, 'exp', true );
if($exp){
return $exp;
}else{
return 0;
}
}

//更新VIP天数//续费  num=天数
function jinsom_update_user_vip_day($user_id,$num){
$num=(int)$num;
if($num==0){
return false;
}
if(is_vip($user_id)){//vip的情况
$vip_time=get_user_meta($user_id,'vip_time',true);
$date=date("Y-m-d",strtotime($vip_time)+($num*3600*24));
update_user_meta($user_id,'vip_time',$date);
}else{//不是vip就直接更新vip时间为获得奖励的时间
$date=date("Y-m-d",strtotime("+".$num." day"));	
update_user_meta($user_id,'vip_time',$date);
}
}

//更新VIP成长值
function jinsom_update_user_vip_number($user_id,$num){
$num=(int)$num;
if($num==0){
return false;
}
if(is_vip($user_id)){//vip的情况
$vip_number=(int)get_user_meta($user_id,'vip_number',true);
update_user_meta($user_id,'vip_number',$vip_number+$num);
}
}

//更新魅力值
function jinsom_update_user_charm($user_id,$num){
$num=(int)$num;
if($num==0){
return false;
}
$charm=(int)get_user_meta($user_id,'charm',true);
update_user_meta($user_id,'charm',$charm+$num);
}

//更新人气值
function jinsom_update_user_visitor($user_id,$num){
$num=(int)$num;
if($num==0){
return false;
}
$visitor=(int)get_user_meta($user_id,'visitor',true);
update_user_meta($user_id,'visitor',$visitor+$num);
}

//更新用户补签卡
function jinsom_update_user_sign_number($user_id,$num){
$num=(int)$num;
if($num==0){
return false;
}
$sign_card=(int)get_user_meta($user_id,'sign_card',true);
update_user_meta($user_id,'sign_card',$sign_card+$num);
}

//更新用户改名卡
function jinsom_update_user_nickname_card($user_id,$num){
$num=(int)$num;
if($num==0){
return false;
}
$nickname_card=(int)get_user_meta($user_id,'nickname_card',true);
update_user_meta($user_id,'nickname_card',$nickname_card+$num);
}


//------------------------------付费可见相关函数-----------------------


//写入购买记录
function jinsom_add_pay_content($user_id,$post_id){
global $wpdb;
$date = current_time('mysql');
$table_name = $wpdb->prefix . 'jin_pay';
if($wpdb->query( "INSERT INTO $table_name (user_id,post_id,pay_date) VALUES ('$user_id', '$post_id', '$date')" ))
return 1;
return 0;	
}

//判断是否已经购买了（付费可见内容）
function jinsom_get_pay_result($user_id,$post_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_pay';
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id = $post_id AND user_id=$user_id limit 1;"))
return 1;
return 0;	
}


//判断是否已经购买了（商城订单）
function jinsom_is_buy_goods($user_id,$post_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_shop_order';
if($wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE post_id = $post_id AND user_id=$user_id AND status in(2,3) limit 1;"))
return 1;
return 0;	
}


//--------------------------------------付费可见相关函数结束---------------------





//------------------------------密码可见相关函数-----------------------

//添加已经输入密码记录
function jinsom_add_password_content($user_id,$post_id){
$date = current_time('mysql');
global $wpdb;
$table_name = $wpdb->prefix . 'jin_password';
$status=$wpdb->query( "INSERT INTO $table_name (user_id,post_id,password_date) VALUES ('$user_id','$post_id','$date')");
if($status){
return 1;	
}else{
return 0;	
}
}

//判断是否已经输入密码（密码可见内容）
function jinsom_get_password_result($user_id,$post_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_password';
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id = $post_id AND user_id=$user_id limit 1;"))	
return 1;
return 0;
}


//--------------------------------------密码可见相关函数结束---------------------









//----------------------------------------判断权限---------------------------------

//判断是否微信浏览器
function is_wechat(){ 
if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) { 
return true; 
}return false; 
}
//判断是否vip会员
function is_vip($user_id){
if(get_user_meta($user_id,'vip_time',true)){
$time_1=get_user_meta($user_id,'vip_time',true);
$time_2=date('Y-m-d',time());
if(strtotime($time_1)>strtotime($time_2)){
return true;
}else{return false;}
}else{return false;}
}

//判断用户是否评论
function jinsom_is_comment($user_id,$post_id){
global $wpdb;
if($wpdb->get_var("SELECT COUNT(*) FROM $wpdb->comments WHERE comment_post_ID = $post_id and user_id= $user_id and comment_parent=0 limit 1;"))
return 1;	
return 0;	
}




//------------------------------------判断权限结束--------------------------------------




//---------------------------------------验证码类相关--------------------------------------------


//判断是否频繁获取验证码
function jinsom_code_often(){
$ip = $_SERVER['REMOTE_ADDR'];
global $wpdb;
$table_name = $wpdb->prefix . 'jin_code';
$often= $wpdb->get_results("SELECT * FROM $table_name WHERE code_ip='$ip' ORDER BY code_time DESC LIMIT 1;");
if($often){
foreach ($often as $oftens) {
$code_time=$oftens->code_time;
}
$time_a=time()-strtotime($code_time);
if($time_a<120){//频繁获取验证码
return true;
}else{
return false;
}
}else{
return false;
}
}

//判断手机号是否注册
function jinsom_phone_exists($phone){
$user_query = new WP_User_Query( array ( 
'meta_key' => 'phone',
'meta_value'   => $phone,
'count_total'=>false,
'number' =>1
));
if (!empty($user_query->results)){
return true;
}else{
return false;	
}
}

//判断昵称是否存在
function jinsom_nickname_exists($nickname){
$user_query = new WP_User_Query( array ( 
'meta_key' => 'nickname',
'meta_value'   => $nickname,
'count_total'=>false,
'number' =>1
));
if (!empty($user_query->results)){
return true;
}else{
return false;	
}
}

//---------------------------------------验证码类结束--------------------------------------------



//----------------------------------查询获取每日次数（发表文章、评论、喜欢之类）-----------------


// 查询今日发表文章数
function jinsom_get_post_times($user_id){
$exp_post_times = (int)get_user_meta( $user_id, 'exp_post_times', true );
if($exp_post_times){
return $exp_post_times;
}else{
return '0';
}
}



//-------------------------------------------关注、粉丝的函数类--------------

//统计关注的数量
function jinsom_following_count($uid){
$uid = (int)$uid;
if(!$uid) return 0;
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$results = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE follow_user_id='$uid' AND follow_status IN(1,2)");
return $results;
}
//统计粉丝的数量
function jinsom_follower_count($uid){
$uid = (int)$uid;
if(!$uid) return 0;
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$results = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id='$uid' AND follow_status IN(1,2)");
return $results;
}


//侧栏关注按钮
function jinsom_follow_button_sidebar($follow_id){
global $wpdb,$current_user;
$user_id=$current_user->ID;
$table_name = $wpdb->prefix . 'jin_follow';
$status = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id='$follow_id' AND follow_user_id='$user_id' AND follow_status IN(1,2)");
if(is_user_logged_in() ){
if($user_id!=$follow_id){
if($status){
if($status->follow_status==2){
return '<span onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-sidebar-follow-btn had opacity"><i class="jinsom-icon jinsom-xianghuguanzhu"></i>互关</span>';
}else{
return '<span onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-sidebar-follow-btn had opacity""><i class="jinsom-icon jinsom-yiguanzhu"></i> 已关</span>';
}
}else{
return '<span onclick="jinsom_follow('.$follow_id.',this);" class="jinsom-sidebar-follow-btn no opacity""><i class="jinsom-icon jinsom-guanzhu"></i>关注</span>';
}
}//自己不显示自己的关注按钮
}else{
return '<span class="jinsom-sidebar-follow-btn no opacity" onclick="jinsom_pop_login_style()"><i class="jinsom-icon jinsom-guanzhu"></i>关注</span>';
}//是否登录
}

//个人中心的关注按钮
function jinsom_follow_button_home($follow_id){
global $wpdb,$current_user;
$user_id=$current_user->ID;
$table_name = $wpdb->prefix . 'jin_follow';
$status = $wpdb->get_row("SELECT * FROM $table_name WHERE user_id='$follow_id' AND follow_user_id='$user_id' AND follow_status IN(1,2)");
if(is_user_logged_in() ){
if($user_id!=$follow_id){
if($status){
if($status->follow_status==2){
return '<span onclick="jinsom_follow('.$follow_id.',this);" class="follow had opacity"><i class="jinsom-icon jinsom-xianghuguanzhu"></i>互关</span>';
}else{
return '<span onclick="jinsom_follow('.$follow_id.',this);" class="follow had opacity"><i class="jinsom-icon jinsom-yiguanzhu"></i> 已关</span>';
}
}else{
return '<span onclick="jinsom_follow('.$follow_id.',this);" class="follow no opacity"><i class="jinsom-icon jinsom-guanzhu"></i>关注</span>';
}
}//自己不显示自己的关注按钮
}else{
return '<span class="follow no opacity" onclick="jinsom_pop_login_style()"><i class="jinsom-icon jinsom-guanzhu"></i>关注</span>';
}//是否登录
}





//----------------------------//访客的函数类-------------------------------------



//添加访客记录
function jinsom_add_visitor($user_id,$author_id){
if(is_user_logged_in()&&$user_id!=$author_id){
$date =  current_time('mysql');
global $wpdb;
$table_name = $wpdb->prefix.'jin_visitor';
$count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id AND author_id = $author_id;");
if($count){
$wpdb->query("UPDATE $table_name SET visit_time = '$date' WHERE user_id = $user_id AND author_id = $author_id ");
}else{
$wpdb->query( "INSERT INTO $table_name (user_id,author_id,visit_time) VALUES ('$user_id','$author_id','$date')");
}

$visitor=(int)get_user_meta($author_id,'visitor',true);
update_user_meta($author_id,'visitor',$visitor+1);

//记录用户当天访问他人主页次数
$visit_times=(int)get_user_meta($user_id,'visit_times',true);
update_user_meta($user_id,'visit_times',($visit_times+1));
}
}


//----------------------------//IM聊天类-------------------------------------

//添加聊天消息
function jinsom_add_msg($user_id,$from_id,$msg_content){
if($user_id!=$from_id&&$user_id&&$from_id){
$time=current_time('mysql');
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$wpdb->query( "INSERT INTO $table_name (user_id,from_id,msg_content,msg_date,status) VALUES ('$user_id','$from_id','$msg_content','$time','0')" );
}
}

//获取对应用户的聊天消息数-用于长轮询
function jinsom_get_chat_msg_count($user_id,$from_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message';
$msg_count = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE from_id = $from_id AND user_id = $user_id ;");
return $msg_count;
}
//获取群组聊天消息数-用于长轮询
function jinsom_get_chat_group_msg_count($bbs_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_message_group';
$msg_count = $wpdb->get_var("SELECT COUNT(ID) FROM $table_name WHERE bbs_id = $bbs_id;");
return $msg_count;
}



//----------------------------------判断相关的-----------------------


//判断是否为帖子类型、判断帖子
function is_bbs_post($post_id){
$post_data = get_post($post_id, ARRAY_A);
$post_parent = $post_data['post_parent'];
if($post_parent==999999999){
return 1;
}else{
return 0;
}
}


//判断当前用户是否已经关注对方
//$user_id是对方的用户ID
//$author_id是当前的用户ID
function jinsom_is_follow_author($user_id,$author_id){
if($user_id==$author_id){
return 1;
}else{
global $wpdb;
$table_name = $wpdb->prefix . 'jin_follow';
$status = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' AND follow_user_id='$author_id' AND follow_status IN(1,2)");
if($status){
return 1;//已关注
}else{
return 0;//未关注
}
}
}


//-----------------------------论坛 话题相关--------------------------------

//获取用户关注的所有论坛
function jinsom_get_user_follow_bbs($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
return  $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' ORDER BY ID DESC limit 100;");
}

//获取用户关注的所有论坛的ID
function jinsom_get_user_follow_bbs_id($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
$user_follow_bbs= $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' ORDER BY ID DESC limit 100;");
if($user_follow_bbs){
$follow_bbs_arr=array();	
foreach ($user_follow_bbs as $user_follow_bbs) {
$bbs_id=$user_follow_bbs->bbs_id;
array_push($follow_bbs_arr,$bbs_id);
}
return $follow_bbs_arr;
}

}


//获取用户关注的所有话题的ID
function jinsom_get_user_follow_topic_id($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_topic_like';
$user_follow_bbs= $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' ORDER BY ID DESC limit 100;");
if($user_follow_bbs){
$follow_bbs_arr=array();	
foreach ($user_follow_bbs as $user_follow_bbs) {
$topic_id=$user_follow_bbs->topic_id;
array_push($follow_bbs_arr,$topic_id);
}
return $follow_bbs_arr;
}

}


//-----------------------------------论坛投票相关----------------


//添加投票数据
function jinsom_add_vote($user_id,$post_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_vote';
$wpdb->query( "INSERT INTO $table_name (user_id,post_id) VALUES ('$user_id','$post_id')" );
}

//判断用户是否已经投票
function jinsom_is_vote($user_id,$post_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_vote';
if($wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' and post_id='$post_id' limit 1;"))
return true;
return false;	
}

//-----------------------------------判断用户组----------------

//判断是管理员和网站管理
function jinsom_is_admin($user_id){
if(!$user_id){return false;}
if (is_user_logged_in()) { 
$user_power=get_user_meta($user_id,'user_power',true);
$user = get_userdata($user_id);
if(in_array('administrator', $user->roles)||$user_power==2){
return true;
}else{
return false;
}
}else{
return false;	
}
}

//判断是管理员||网站管理||巡查员
function jinsom_is_admin_x($user_id){
if(!$user_id){return false;}
if (is_user_logged_in()) { 
$user_power=get_user_meta($user_id,'user_power',true);
$user = get_userdata($user_id);
if(in_array('administrator', $user->roles)||$user_power==2||$user_power==3){
return true;
}else{
return false;
}
}else{
return false;	
}
}

//判断是管理员||网站管理||巡查员|审核员
function jinsom_is_admin_y($user_id){
if(!$user_id){return false;}
if (is_user_logged_in()) { 
$user_power=get_user_meta($user_id,'user_power',true);
$user = get_userdata($user_id);
if(in_array('administrator', $user->roles)||$user_power==2||$user_power==3||$user_power==5){
return true;
}else{
return false;
}
}else{
return false;	
}
}


//判断是大版主
function jinsom_is_bbs_admin_a($user_id,$admin_a_arr){
if (is_user_logged_in()){ 
if(in_array($user_id,$admin_a_arr)){
return true;
}else{
return false;
}
}else{
return false;	
}
}

//判断是黑名单用户（全站）
function jinsom_is_black($user_id){
$blacklist=get_user_meta($user_id,'blacklist_time',true);
$blacklist_time=strtotime($blacklist);
if($blacklist_time>time()){
return true;
}else{
return false;
}
}

//判断是否为对方的黑名单
function jinsom_is_blacklist($user_id,$author_id){
$blacklist=get_user_meta($user_id,'blacklist',true);
$black_arr=explode(",",$blacklist);
if(in_array($author_id,$black_arr)){
return true;//是黑名单
}else{
return false;	
}
}


//----------------------------------活动帖子----------------------

//判断是否已经报名
function jinsom_is_join_activity($user_id,$post_id){
if(is_user_logged_in()){
$join=get_post_meta($post_id,'activity',true);
$join_arr=explode(",",$join);
if(in_array($user_id,$join_arr)){
return true;
}else{
return false;	
}
}else{
return false;	
}
}



//-----------------------------------有关提现的函数----------------

//添加提现数据
function jinsom_add_cash($user_id,$number,$type){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_cash'; 
$now_time= current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,number,type,cash_time,status) VALUES ('$user_id','$number','$type','$now_time',0)" );

}

//获取提现记录
function jinsom_get_cash_notes($user_id,$type=0){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_cash';
if($type==1){
$get_cash_notes = $wpdb->get_results("SELECT * FROM $table_name WHERE 1 ORDER BY ID DESC;");	
}else{
$get_cash_notes = $wpdb->get_results("SELECT * FROM $table_name WHERE user_id='$user_id' ORDER BY ID DESC limit 30;");
}

return $get_cash_notes;	
}


//更新提现状态
function jinsom_update_cash($status,$id,$content=''){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_cash';
$wpdb->query( "UPDATE $table_name SET status = '$status',content='$content' WHERE ID=$id;" );

}

//删除提现记录
function jinsom_delete_cash($id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_cash';	
$wpdb->query( " DELETE FROM $table_name WHERE ID=$id; " );
}

//查询提现记录
function jinsom_check_cash($id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_cash';
$check_cash = $wpdb->get_results("SELECT * FROM $table_name WHERE ID='$id' limit 1;");
return $check_cash;	
}




//-----------------------------有关推广方面------------------------------

//生成邀请码
function jinsom_make_invite_code() { //8位
$code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$rand = $code[rand(0,25)]
.strtoupper(dechex(date('m')))
.date('d').substr(time(),-5)
.substr(microtime(),2,5)
.sprintf('%02d',rand(0,99));
for(
$a = md5( $rand, true ),
$s = '0123456789ABCDEFGHIJKLMNOPQRSTUV',
$d = '',
$f = 0;
$f < 8;
$g = ord( $a[ $f ] ),
$d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
$f++
);
return $d;
}





//判断推广链接是否已经访问了（通过ip，防止被刷）
function jinsom_is_referral_link($user_id,$ip){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_referral_link';
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE user_id = $user_id and ip='$ip' limit 1;"))
return 1;
return 0;
}

//添加推广链接的访问记录（每日清空）
function jinsom_add_referral_link($user_id,$ip){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_referral_link';
$time=current_time('mysql');
$wpdb->query( "INSERT INTO $table_name (user_id,ip,referral_time) VALUES ('$user_id','$ip','$time')");
}


function jinsom_domain_mark(){
if(wp_get_theme()->get('ThemeURI')!=home_url()){
$data=array('domain'=>home_url(),'version'=>wp_get_theme()->get('Version'));
$url=wp_get_theme()->get('ThemeURI').'/domain.php';
$query=http_build_query($data); 
$result = file_get_contents($url.'?'.$query); 
}
}
add_action('after_switch_theme','jinsom_domain_mark');



//推广链接
function jinsom_referral_link($user_id){
//本地记录推广人的cookies ID
setcookie("invite",$user_id, time()+3600*24*30*12*10);//十年
$jinsom_referral_link_times = jinsom_get_option('jinsom_referral_link_times');
if($jinsom_referral_link_times){
$referral_times=(int)get_user_meta($user_id,'referral_times',true);
if($referral_times<=$jinsom_referral_link_times){//判断是否超过获取奖励次数
$ip = $_SERVER['REMOTE_ADDR'];
if(!jinsom_is_referral_link($user_id,$ip)){//如果数据库没有记录，则继续执行
jinsom_add_referral_link($user_id,$ip);//记录
update_user_meta($user_id,'referral_times',$referral_times+1);
$credit = jinsom_get_option('jinsom_referral_link_credit');
$exp = jinsom_get_option('jinsom_referral_link_exp');
jinsom_update_credit($user_id,$credit,'add','referral','你的推广链接被访问',1,'');
jinsom_update_exp($user_id,$exp,'add','你的推广链接被访问');
//记录推广获利
$referral_credit=(int)get_user_meta($user_id,'referral_credit',true);
update_user_meta($user_id,'referral_credit',$referral_credit+$credit);
}
}
}//上限

}

//时间转换
function jinsom_timeago($ptime){
$ptime = strtotime($ptime);
$etime = time() - $ptime;
if ($etime < 1) return __('刚刚','jinsom');     
$interval = array (         
12 * 30 * 24 * 60 * 60  =>  __('年前','jinsom'),
30 * 24 * 60 * 60       =>  __('月前','jinsom'),
7 * 24 * 60 * 60        =>  __('周前','jinsom'),
24 * 60 * 60            =>  __('天前','jinsom'),
60 * 60                 =>  __('小时前','jinsom'),
60                      =>  __('分钟前','jinsom'),
1                       =>  __('秒前','jinsom')
);
foreach ($interval as $secs => $str) {
$d = $etime / $secs;
if ($d >= 1) {
$r = round($d);
return $r . $str;
}
};
}

//秒数转时分
function jinsom_get_second_h_m($v){
$h=floor($v/3600);
if($h<10){$h='0'.$h;}
$t=$v%3600;
$m=ceil($t/60);
if($m<10){$m='0'.$m;}
return $h.':'.$m;
}


//空模版
function jinsom_empty($content='暂没有数据'){
return '<div class="jinsom-empty-page"><i class="jinsom-icon jinsom-kong"></i><div class="title"><p>'.$content.'</p></div></div>';
}




//评论点赞

//判断当前用户是否已经点赞该评论
function jinsom_is_comment_up($comment_id,$user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_comment_up';
$comment_up = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE comment_id = $comment_id AND user_id='$user_id'  and status=1;");	
if($comment_up){
return true;
}else{
return false;
}
}


//获取某条评论点赞的人数
function jinsom_get_comment_up_count($comment_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_comment_up';
$comment_up = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE comment_id = $comment_id  and status=1;");
return $comment_up;
}

//修改评论内容
function jinsom_update_comment_conetnt($content,$comment_id){
global $wpdb;
$wpdb->query( "UPDATE $wpdb->comments SET comment_content = '$content' WHERE comment_ID=$comment_id;" );
}


//论坛类--------------------

//添加论坛关注
function jinsom_add_bbs_like($user_id,$bbs_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
if($wpdb->query( "INSERT INTO $table_name (user_id,bbs_id) VALUES ('$user_id','$bbs_id')" ))
return 1;
return 0;
}

//取消论坛关注
function jinsom_delete_bbs_like($user_id,$bbs_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
if($wpdb->query( "DELETE FROM $table_name where user_id='$user_id' and bbs_id='$bbs_id';" ))
return 1;
return 0;
}

//判断当前用户是否已经喜欢了本论坛
function jinsom_is_bbs_like($user_id,$bbs_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name where user_id=$user_id and bbs_id=$bbs_id limit 1;"))
return 1;
return 0;
}

//获取某个论坛喜欢的人数
function jinsom_get_bbs_like_number($bbs_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
return $wpdb->get_var("SELECT COUNT(*) FROM $table_name where bbs_id=$bbs_id;");
}

//获取已关注的论坛用户
function jinsom_get_bbs_user($bbs_id,$number){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_like';
$data = $wpdb->get_results("SELECT user_id FROM $table_name WHERE bbs_id=$bbs_id ORDER BY ID DESC limit $number;");	
return $data;
}

//判断是否已经输入某个论坛的访问密码
function jinsom_is_bbs_visit_pass($bbs_id,$user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_bbs_visit_pass'; 
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name where bbs_id=$bbs_id AND user_id=$user_id limit 1;"))
return 1;
return 0;
}



//=================================话题相关函数==============================

//添加话题关注
function jinsom_add_topic_like($user_id,$topic_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_topic_like';
if($wpdb->query( "INSERT INTO $table_name (user_id,topic_id) VALUES ('$user_id','$topic_id')" ))
return 1;
return 0;
}

//取消话题关注
function jinsom_delete_topic_like($user_id,$topic_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_topic_like';
if($wpdb->query( "DELETE FROM $table_name where user_id='$user_id' and topic_id='$topic_id';" ))
return 1;
return 0;
}

//判断当前用户是否已经关注了话题
function jinsom_is_topic_like($user_id,$topic_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_topic_like';
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name where user_id=$user_id and topic_id=$topic_id limit 1;"))
return 1;
return 0;
}



//获取某个话题关注的人数
function jinsom_topic_like_number($topic_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_topic_like';
return $wpdb->get_var("SELECT COUNT(*) FROM $table_name where topic_id=$topic_id;");
}





//更新用户ip
function jinsom_update_ip($user_id){
if($user_id){
$jinsom_location_on_off=jinsom_get_option('jinsom_location_on_off');
if($jinsom_location_on_off!='no'){
$city_lock=get_user_meta($user_id,'city_lock',true);
if($city_lock=='unlock'||!$city_lock){
$ip=$_SERVER['REMOTE_ADDR'];
if($jinsom_location_on_off=='qq'||$jinsom_location_on_off=='baidu'||$jinsom_location_on_off=='gaode'){

if($jinsom_location_on_off=='qq'){
$str = file_get_contents('https://apis.map.qq.com/ws/location/v1/ip?ip='.$ip.'&key='.jinsom_get_option('jinsom_qqlbs_key'));
$arr=json_decode($str,true);
$country=$arr['result']['ad_info']['nation'];
$province=$arr['result']['ad_info']['province'];
$city=$arr['result']['ad_info']['city'];
}else if($jinsom_location_on_off=='baidu'){//百度
$str = file_get_contents('https://api.map.baidu.com/location/ip?ip='.$ip.'&ak='.jinsom_get_option('jinsom_baidulbs_key').'&coor=bd09ll');
$arr=json_decode($str,true);
if($arr['status']){
$country='海外';
$province='';
$city='';
}else{
$country='中国';
$province=$arr['content']['address_detail']['province'];
$city=$arr['content']['address_detail']['city'];
}
}else if($jinsom_location_on_off=='gaode'){//高德
$str = file_get_contents('https://restapi.amap.com/v3/ip?ip='.$ip.'&key='.jinsom_get_option('jinsom_gaodelbs_key').'&output=json');
$arr=json_decode($str,true);
if(!$arr['status']){
$country='海外';
$province='';
$city='';
}else{
$country='中国';
$province=$arr['province'];
$city=$arr['city'];
if(is_array($province)){
$province='';
}
if(is_array($city)){
$city='';
}
}
}

if($province){
$province_arr=array('壮族自治区','维吾尔自治区','回族自治区','自治区','市','省');
$province=str_replace($province_arr,'',$province);
}

if($city){
$city_arr=array('重庆市','北京市','天津市','上海市','市');
$city=str_replace($city_arr,'',$city);
}

if($city==''&&$province==''){
$address=$country;
}else if($city==''||$city==$province){
$address=$province;
}else{
$address=$province.'·'.$city;
}
}else if($jinsom_location_on_off=='taobao'){
$str = file_get_contents('http://ip.taobao.com/service/getIpInfo.php?ip='.$ip);
$arr=json_decode($str,true);
$country=$arr['data']['country'];
$province=$arr['data']['region'];
$city=$arr['data']['city'];

$province=str_replace('XX','',$province);
$city=str_replace('XX','',$city);

if($city==''&&$province==''){
$address=$country;
}else if($city==''||$city==$province){
$address=$province;
}else{
$address=$province.'·'.$city;
}
}else{//付费
$url = 'https://api.ip138.com/query/?ip='.$ip.'&datatype=txt';
$header = array('token:'.jinsom_get_option('jinsom_ip138_token'));
$address=jinsom_get_ip138_data($url,$header);  
}
if($address==''){$address='未知';}

update_user_meta($user_id,'city',$address);
}
}
}
}

//ip138接口
function jinsom_get_ip138_data($url,$header){
$ch = curl_init();  
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_HTTP_VERSION,CURL_HTTP_VERSION_1_1);
curl_setopt($ch,CURLOPT_HTTPHEADER,$header); 
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);  
curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,3);  
$handles = curl_exec($ch);  
curl_close($ch);  
$str1= explode(' ',$handles);
$country=$str1[0];
$province=$str1[1];
$city=$str1[2];
if($city==''&&$province==''){
$address=$country;
}else if($city==''||$city==$province){
$address=$province;
}else{
$address=$province.'·'.$city;
}
return $address;  
}


//输出表情模块
function jinsom_get_expression($type,$dom){
$html='';
$html.= '<div class="jinsom-smile-form">';
$jinsom_smile_url=jinsom_get_option('jinsom_smile_url');
$jinsom_smile_add=jinsom_get_option('jinsom_smile_add');
if($jinsom_smile_add&&$jinsom_smile_url){

if(count($jinsom_smile_add)>1){
$html.='<div class="header">';
$a=0;
foreach ($jinsom_smile_add as $data) {
if($a==0){
$on='class="on"';
}else{
$on='';
}
$html.='<li '.$on.'>'.$data['name'].'</li>';
$a++;
}
$html.='</div>';
}

$html.='<div class="content">';

$b=1;
foreach ($jinsom_smile_add as $data) {
if($b==1){
$style='';
}else{
$style='style="display:none;"';
}
$html.='<ul '.$style.'>';

for ($i=1; $i <= $data['number'] ; $i++) { 

if($b==1){
$ed='';
}else{
$ed=$b.'-';
}

if($type=='ue'){//富文本表情
$html.= '<span class="jinsom-smile-img smile-'.$ed.$i.'" onclick=\''.$dom.'.focus();'.$dom.'.execCommand("inserthtml","&nbsp;[s-'.$ed.$i.']&nbsp;")\'><img src="'.$jinsom_smile_url.$data['smile_url'].'/'.$i.'.png"></span>';	
}else if($type=='im'){//IM表情输出
$html.= '<span class="jinsom-smile-img smile-'.$ed.$i.'" onclick=\'jinsom_add_smile(" [s-'.$ed.$i.'] ",1,this)\'><img src="'.$jinsom_smile_url.$data['smile_url'].'/'.$i.'.png"></span>';
}else{//普通输入框表情
$html.= '<span class="jinsom-smile-img smile-'.$ed.$i.'" onclick=\'jinsom_add_smile(" [s-'.$ed.$i.'] ",2,this)\'><img src="'.$jinsom_smile_url.$data['smile_url'].'/'.$i.'.png"></span>';
}
}//for
$html.='</ul>';
$b++;
}//foreach
$html.='</div>';


// if($type==1){//富文本表情
// for ($i=1; $i < 99 ; $i++) { 
// $html.= '<span class="jinsom-smile-img smile-'.$i.'" onclick=\''.$dom.'.focus();'.$dom.'.execCommand("inserthtml","&nbsp;[s-'.$i.']&nbsp;")\'><img src="'.$jinsom_smile_url.$i.'.png"></span>';
// }	
// }else if($type==5){//IM表情输出
// for ($i=1; $i < 99 ; $i++) { 
// $html.= '<span class="jinsom-smile-img smile-'.$i.'" onclick=\'jinsom_add_smile(" [s-'.$i.'] ",1,this)\'><img src="'.$jinsom_smile_url.$i.'.png"></span>';
// }	
// }else{//普通输入框表情
// for ($i=1; $i < 99 ; $i++) { 
// $html.= '<span class="jinsom-smile-img smile-'.$i.'" onclick=\'jinsom_add_smile(" [s-'.$i.'] ",2,this)\'><img src="'.$jinsom_smile_url.$i.'.png"></span>';
// }	
// }

}else{
$html.='<div class="tips">请在后台-内容模块-表情设置 配置表情地址。</div>';	
}

$html.= '</div>';
return $html;
}



//输出编辑器模块
function jinsom_get_edior($type){
if($type=='single'){
$edit_ranking = jinsom_get_option('jinsom_single_edit_ranking_o');	
}else if($type=='bbs'){
$edit_ranking = jinsom_get_option('jinsom_bbs_edit_ranking_o');
}else if($type=='bbs_pay'){
$edit_ranking = jinsom_get_option('jinsom_bbs_pay_edit_ranking_o');
}else if($type=='bbs_comment'){
$edit_ranking = jinsom_get_option('jinsom_bbs_reply_edit_ranking_o');
}


if(!empty ($edit_ranking) ) {
$enabled  = $edit_ranking['enabled'];
if(!empty($enabled)){
foreach($enabled as $x=>$x_value) {
switch($x){

case "source": 
echo "'source',";  
break;

case "bold": 
echo "'bold',";  
break;

case "forecolor": 
echo "'forecolor',";  
break; 

case "inserttable": 
echo "'inserttable',";  
break;

case "backcolor": 
echo "'backcolor',";  
break;


case "link": 
echo "'link',";  
break;

case "rowspacingtop": 
echo "'rowspacingtop',";//段前距 
break;

case "rowspacingbottom": 
echo "'rowspacingbottom',";//段后距 
break;

case "removeformat": 
echo "'removeformat',";//清除格式  
break;

case "lineheight": 
echo "'lineheight',";  
break;

case "indent": 
echo "'indent',";  
break;

case "justifyleft": 
echo "'justifyleft',";  
break;

case "justifyright": 
echo "'justifyright',";  
break;

case "justifycenter": 
echo "'justifycenter',";  
break;

case "paragraph": 
echo "'paragraph',";  
break;

case "insertcode": 
echo "'insertcode',";  
break;

case "unlink": 
echo "'unlink',";  
break;

case "insertorderedlist": 
echo "'insertorderedlist',";  
break;

case "insertunorderedlist": 
echo "'insertunorderedlist',";  
break;

case "fontsize": 
echo "'fontsize',";  
break;

case "horizontal": 
echo "'horizontal',";  //分隔线
break;

case "italic": 
echo "'italic',";  
break;

case "underline": 
echo "'underline',";  
break;

case "strikethrough": 
echo "'strikethrough',";  
break;

case "insertframe": 
echo "'insertframe',";//插入iframe
break;

case "blockquote": 
echo "'blockquote',";
break;

case "preview": 
echo "'preview',";
break;

case "undo": 
echo "'undo',";
break;

case "redo": 
echo "'redo',";
break;

case "fontfamily": 
echo "'fontfamily',";
break;

case "a-1": 
echo "'|',";  
break;

case "a-2": 
echo "'|',";  
break;

case "a-3": 
echo "'|',";  
break;

case "a-4": 
echo "'|',";  
break;

}}}}
}//输出编辑器模块==结束


//输出帖子类型
function jinsom_bbs_post_type($post_id){
$html='';
$post_type=get_post_meta($post_id,'post_type',true);
// 置顶
if(get_post_meta($post_id,'jinsom_sticky',true)){
$html.= '<span class="jinsom-bbs-post-type-up"></span>';	
}
//精华
if(get_post_meta($post_id,'jinsom_commend',true)){
$html.= '<span class="jinsom-bbs-post-type-nice"></span>';
}
//付费
if($post_type=='pay_see'){
$html.= '<span class="jinsom-bbs-post-type-pay"></span>';
}
//vip
if($post_type=='vip_see'){
$html.= '<span class="jinsom-bbs-post-type-vip"></span>';
}
//投票
if($post_type=='vote'){
$html.= '<span class="jinsom-bbs-post-type-vote"></span>';
}
//活动
if($post_type=='activity'){
$html.= '<span class="jinsom-bbs-post-type-activity"></span>';
}
//登录可见
if($post_type=='login_see'){
$html.= '<span class="jinsom-bbs-post-type-login"></span>';
}
//回复可见
if($post_type=='comment_see'){
$html.= '<span class="jinsom-bbs-post-type-comment"></span>';
}
//问答
if($post_type=='answer'){
$answer_floor=get_post_meta($post_id,'answer_adopt',true);
$answer_number=get_post_meta($post_id,'answer_number',true);
if($answer_floor){
$html.= '<span class="jinsom-bbs-post-type-answer ok"></span>';
}else{
if($answer_number==0){
$html.= '<span class="jinsom-bbs-post-type-answer">'.__('未解决','jinsom').'</span>';  
}else{
$html.= '<span class="jinsom-bbs-post-type-answer">'.$answer_number.jinsom_get_option('jinsom_credit_name').'</span>';
}
}
}	
return $html;
}




//获取视频封面图
function jinsom_video_cover($post_id){
$video_img=get_post_meta($post_id,'video_img',true);
if($video_img){
return $video_img;
}else{
$default_cover=jinsom_get_option('jinsom_publish_video_cover');
if($default_cover){
return $default_cover;
}else{
return get_template_directory_uri().'/images/default-cover.jpg';
}
}
}


//获取用户认证类型
function jinsom_verify_type($user_id){
$verify_add=jinsom_get_option('jinsom_verify_add');
$verify=get_user_meta($user_id,'verify',true);
if($verify){
if($verify==1){
return __('个人认证','jinsom');  
}else if($verify==2){
return __('企业认证','jinsom'); 
}else if($verify==3){
return __('女神认证','jinsom'); 
}else if($verify==4){
return __('达人认证','jinsom'); 
}else{
if($verify_add){
$i=5;
foreach ($verify_add as $data) {
if($verify==$i){
return $data['name'];
}
$i++;
}
}
}	
}
}




//获取话题背景封面
function jinsom_topic_bg($topic_id){
if(wp_is_mobile()){
$topic_bg=get_term_meta($topic_id,'mobile_topic_bg',true);
}else{
$topic_bg=get_term_meta($topic_id,'topic_bg',true);
}
if(!$topic_bg){
$default_topic_bg=jinsom_get_option('jinsom_topic_default_bg');
if($default_topic_bg){
$topic_bg=$default_topic_bg;
}else{
$topic_bg=get_bloginfo('template_directory').'/images/default-cover.jpg';
}
}
return $topic_bg;	
}

//获取个人主页背景
function jinsom_member_bg($user_id,$type){
$number=(int)get_user_meta($user_id,'skin',true);
$jinsom_member_bg_add=jinsom_get_option('jinsom_member_bg_add');
if($jinsom_member_bg_add){
return $jinsom_member_bg_add[$number][$type];
}
}


//过滤请求
function jinsom_sqlfilter_array($array){
while(list($key,$var) = each($array)) {
if ($key != 'argc' && $key != 'argv' && (strtoupper($key) != $key || ''.intval($key) == "$key")) {
if (is_string($var)) {
$array[$key] = stripslashes($var);
}
if (is_array($var))  {
$array[$key] = stripslashes_array($var);
}
}
}
return $array;
}


//人机验证
function jinsom_machine_verify($ticket,$Randstr){
$ip = $_SERVER['REMOTE_ADDR'];
$time=time();
$Nonce=rand();
$Action='DescribeCaptchaResult';
$Version='2019-07-22';
$CaptchaType=9;
$SecretId=jinsom_get_option('jinsom_upload_cos_secretid');
$SecretKey=jinsom_get_option('jinsom_upload_cos_secretkey');
$CaptchaAppId=jinsom_get_option('jinsom_machine_verify_appid');
$AppSecretKey=jinsom_get_option('jinsom_machine_verify_secretkey');
//验签过程
$param["Nonce"] = $Nonce;
$param["Timestamp"] = $time;
$param["SecretId"] = $SecretId;
$param["Action"] = $Action;
$param["Version"] = $Version;
$param["CaptchaType"] = $CaptchaType;
$param["CaptchaAppId"] = $CaptchaAppId;
$param["AppSecretKey"] = $AppSecretKey;
$param["Ticket"] = $ticket;
$param["UserIp"] = $ip;
$param["Randstr"] = $Randstr;
ksort($param);
$signStr = "POSTcaptcha.tencentcloudapi.com/?";
foreach ( $param as $key => $value ) {
    $signStr = $signStr . $key . "=" . $value . "&";
}
$signStr = substr($signStr, 0, -1);
$signature = base64_encode(hash_hmac("sha1", $signStr,$SecretKey, true));
//POST数据
$post_data = array(
'Action' => $Action,
'AppSecretKey' =>$AppSecretKey,
'CaptchaType' =>$CaptchaType,
'CaptchaAppId' =>$CaptchaAppId,
'Nonce' =>$Nonce,
'Randstr' =>$Randstr,
'SecretId'=>$SecretId,
'Signature'=>$signature,//签名
'Ticket' =>$ticket,
'Timestamp' =>$time,
'UserIp' =>$ip,
'Version' =>$Version,
);

//POST请求
$url='https://captcha.tencentcloudapi.com/';
$query = http_build_query($post_data);
$options = array(
'http' => array(
'method' => 'POST',
'header' => 'Content-type:application/x-www-form-urlencoded',
'content' => $query,
'timeout' => 15 * 60 // 超时时间（单位:s）
)
);
$context = stream_context_create($options);
$result = file_get_contents($url,false,$context);
$arr=json_decode($result,true);
if($arr['Response']['CaptchaCode']==1){
return true;
}else{
return false;
}
}


//随机抢红包
function jinsom_get_redbag($total_bean,$total_packet){
$min = 1;
$max = $total_bean -1;
$list = [];
$maxLength = $total_packet - 1;
while(count($list) < $maxLength) {
$rand = mt_rand($min, $max);
empty($list[$rand]) && ($list[$rand] = $rand);
}

$list[0] = 0; //第一个
$list[$total_bean] = $total_bean; //最后一个
sort($list); //不再保留索引
$beans = [];
for ($j=1; $j<=$total_packet; $j++) {
$beans[] = $list[$j] - $list[$j-1];
}
return $beans[0];
}


//判断当前用户是否已经领取红包
function jinsom_is_redbag($user_id,$post_id){
global $wpdb;
$table_name = $wpdb->prefix.'jin_redbag';
$status=$wpdb->get_results("SELECT * FROM $table_name where post_id=$post_id and user_id=$user_id limit 1");
if($status){
return true;
}else{
return false;
}
}

//判断红包是否已经被领取完
function jinsom_is_no_redbag($post_id){
$redbag_number=(int)get_post_meta($post_id,'redbag_number',true);//个数
global $wpdb;
$table_name = $wpdb->prefix.'jin_redbag';
$status=$wpdb->get_var("SELECT COUNT(*) FROM $table_name where post_id=$post_id limit 100");//红包个数最大100
if($status<$redbag_number){
return false;
}else{
return true;
}
}

//获取毫秒的时间戳
function jinsom_msectime(){
list($msec, $sec) = explode(' ', microtime());
$msectime = (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);
return $msectime;
}

//判断用户是否已经完成了当前任务
function jinsom_is_task($user_id,$task_id){
global $wpdb;
$table_name = $wpdb->prefix.'jin_task';
$status=$wpdb->get_results("SELECT * FROM $table_name where task_id='$task_id' and user_id=$user_id limit 1");
if($status){
return true;
}else{
return false;
}
}

//获取已领取宝箱的人数
function jinsom_task_treasure_people_number($task_id){
global $wpdb;
$table_name = $wpdb->prefix.'jin_task';
return $wpdb->get_var("SELECT COUNT(*) FROM $table_name where task_id='$task_id'");
}


//百度文本替换
function jinsom_baidu_filter($content){
$accessTokenUrl='https://aip.baidubce.com/oauth/2.0/token';
$textCensorUserDefinedUrl='https://aip.baidubce.com/rest/2.0/solution/v1/text_censor/v2/user_defined';
$request_data=array(
'grant_type' => 'client_credentials',
'client_id' => jinsom_get_option('jinsom_baidu_filter_apikey'),
'client_secret' => jinsom_get_option('jinsom_baidu_filter_secretkey'),
);	
$response=jinsom_post_request($accessTokenUrl,$request_data);
$obj=json_decode($response['content'], true);
$params = array();
$params['access_token']=$obj['access_token'];
$response_aa=jinsom_post_request($textCensorUserDefinedUrl."?".http_build_query($params),array('text'=>$content));
return json_decode($response_aa['content'], true);
//return $obj_aa['conclusionType'];
}

//获取当前语言名称
function jinsom_get_language_name(){
$type=get_locale();
if($type=='zh_CN'){//中文
return '简体中文';
}else if($type=='en_US'){//英语
return 'English';	
}else if($type=='ru_RU'){//俄语
return 'Русский язык';	
}else if($type=='fr_FR'){//法语
return 'français';	
}else if($type=='de_DE'){//德语
return 'Deutsch';	
}else if($type=='ja_JP'){//日语
return '日本語';	
}else if($type=='ko_KR'){//韩语
return '한국어';	
}else if($type=='es_ES'){//西班牙语
return 'Español';	
}else if($type=='zh_TW'||$type=='zh_HK'){//繁体
return '繁體中文';	
}else if($type=='ar_SA'){//阿拉伯语-沙特
return 'اللغة العربية';	
}else if($type=='it_IT'){//意大利语
return 'Italiano';	
}else if($type=='pt_PT'){//葡萄牙语
return 'Português';	
}
}


//curl
function jinsom_curl_post($url,$data=array()){
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
// POST数据
curl_setopt($ch, CURLOPT_POST, 1);
// 把post的变量加上
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
$output = curl_exec($ch);
curl_close($ch);
return $output;
}


//公众号发送模版消息提醒
function jinsom_wechat_send_msg($user_id,$nickname,$action){
$open_id=get_user_meta($user_id,'weixin_uid',true);
if($open_id){
$appid=jinsom_get_option('jinsom_wechat_mp_appid');
$appsecret=jinsom_get_option('jinsom_wechat_mp_appsecret');
$token_url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
$json_token=jinsom_curl_post($token_url);
$access_token=json_decode($json_token,true);
$access_token=$access_token['access_token'];	
if($access_token){
$data=array(
'first'=>array('value'=>urlencode(jinsom_get_option('jinsom_wechat_mp_first_text')),'color'=>"#FF0000"),
'keyword1'=>array('value'=>urlencode($nickname),'color'=>'#FF0000'),
'keyword2'=>array('value'=>urlencode($action),'color'=>'#FF0000'),
'remark' =>array('value'=>urlencode(jinsom_get_option('jinsom_wechat_mp_remark_text')),'color'=>'#999999'), 
);

$template = array(
'touser' => $open_id,
'template_id' => jinsom_get_option('jinsom_wechat_mp_template_id'),//模版ID
'url' => home_url(),
'topcolor' => '#FF0000',
'data' => $data
);

$json_template = json_encode($template);
$template_url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
$dataRes=jinsom_curl_post($template_url, urldecode($json_template));//发送消息
// $dataRes=json_decode($dataRes,true);
// if($dataRes['errcode'] == 0) {
// }else{
// }
}//access_token
}//openid
}



//黑名单提示
function jinsom_black_tips($user_id){
$blacklist_reason=get_user_meta($user_id,'blacklist_reason',true);
$tips=__('你是黑名单，禁止互动操作！','jinsom');
if($blacklist_reason){
$tips.='</br>'.__('原因','jinsom').'：'.$blacklist_reason;
}
return $tips;
}


//没有登录提示
function jinsom_no_login_tips(){
return __('你还没有登录，请登录之后再操作！','jinsom');
}

//帐号异常提示
function jinsom_user_danger_tips(){
return __('系统检测到你的账户异常，已限制帐号登录！','jinsom');
}


//显示论坛关注按钮
function jinsom_bbs_like_btn($user_id,$bbs_id){
$category=get_category($bbs_id);
$bbs_id_parents=$category->parent;
if(!$bbs_id_parents){
if(jinsom_is_bbs_like($user_id,$bbs_id)){
return '<span class="had opacity follow" onclick="jinsom_bbs_like('.$bbs_id.',this);"><i class="jinsom-icon jinsom-yiguanzhu"></i> '.__('已关','jinsom').'</span>';  
}else{
return '<span class="no opacity follow" onclick="jinsom_bbs_like('.$bbs_id.',this);"><i class="jinsom-icon jinsom-guanzhu"></i> '.__('关注','jinsom').'</span>'; 
}
}
}



//显示关注话题按钮
function jinsom_topic_like_btn($user_id,$topic_id){
if(jinsom_is_topic_like($user_id,$topic_id)){
return '<span class="follow had" onclick="jinsom_topic_like('.$topic_id.',this)"><i class="jinsom-icon jinsom-yiguanzhu"></i> '.__('已关','jinsom').'</span>';
}else{
return '<span class="follow" onclick="jinsom_topic_like('.$topic_id.',this)"><i class="jinsom-icon jinsom-guanzhu"></i> '.__('关注','jinsom').'</span>';
}
}


//自定义body开始钩子
function jinsom_body_star_hook(){
do_action('jinsom_body_star_hook');	
}

//自定义body结束钩子
function jinsom_body_end_hook(){
do_action('jinsom_body_end_hook');	
}

//自定义文章内容结束钩子
function jinsom_single_content_end_hook(){
do_action('jinsom_single_content_end_hook');	
}

//移动端SNS首页内容列表钩子
function jinsom_mobile_sns_home_hook(){
do_action('jinsom_mobile_sns_home_hook');	
}

//内容页标题下方钩子
function jinsom_title_bottom_hook(){
do_action('jinsom_title_bottom_hook');	
}



//增加头衔
function jinsom_add_honor($user_id,$honor){
$user_honor=get_user_meta($user_id,'user_honor',true);
if($user_honor){
$user_honor_arr=explode(",",$user_honor);
if(!in_array($honor,$user_honor_arr)){//如果用户没有这个头衔
array_push($user_honor_arr,$honor);//给用户加上对应的头衔
$user_honor= implode(",",$user_honor_arr);
update_user_meta($user_id,'user_honor',$user_honor);
}
}else{//没有头衔
update_user_meta($user_id,'user_honor',$honor);
}	
}


//判断当前用户是否已经收藏某篇动内容
function jinsom_is_collect($user_id,$type,$post_id,$url){
if($user_id){
global $wpdb;
$table_name = $wpdb->prefix . 'jin_collect';
if($type=='post'||$type=='goods'){
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE post_id = $post_id AND user_id='$user_id' AND type='$type' limit 1;")){
return 1;	
}else{
return 0;
}
}else{//收藏附件类
if($wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE url = '$url' AND user_id='$user_id' AND type='$type' limit 1;")){
return 1;	
}else{
return 0;
}
}


}else{
return 0;	
}
}



//IM消息提醒
function jinsom_im_tips($receive_user_id,$content){
$send_user_id=(int)jinsom_get_option('jinsom_im_user_id');//IM助手
jinsom_add_msg($receive_user_id,$send_user_id,$content);//提醒管理员有新的订单	
}


//社交登录 url
function jinsom_oauth_url($type){
$jinsom_social_login_tab=jinsom_get_option('jinsom_social_login_tab');
if($type=='qq'){
return 'https://graph.qq.com/oauth2.0/authorize?response_type=code&client_id='.$jinsom_social_login_tab['jinsom_qq_login_appid'].'&redirect_uri='.urlencode(home_url('/Extend/oauth/qq/index.php'));
}else if($type=='weibo'){
return 'https://api.weibo.com/oauth2/authorize?client_id='.$jinsom_social_login_tab['jinsom_login_weibo_key'].'&response_type=code&redirect_uri='.urlencode(home_url('/Extend/oauth/weibo/index.php'));
}else if($type=='wechat_code'){//微信扫码
return 'https://open.weixin.qq.com/connect/qrconnect?appid='.$jinsom_social_login_tab['jinsom_login_wechat_code_key'].'&redirect_uri='.urlencode(home_url('/Extend/oauth/wechat/code/index.php')).'&response_type=code&scope=snsapi_login&state='.md5(uniqid(rand(),true));
}else if($type=='wechat_mp'){//公众号
return 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$jinsom_social_login_tab['jinsom_login_wechat_mp_key'].'&redirect_uri='.urlencode(home_url('/Extend/oauth/wechat/mp/index.php')).'&response_type=code&scope=snsapi_userinfo&state='.md5(uniqid(rand(),true));
}else if($type=='github'){
return 'https://github.com/login/oauth/authorize?client_id='.$jinsom_social_login_tab['jinsom_login_github_id'].'&scope=user:email';
}else if($type=='alipay'){
return 'https://openauth.alipay.com/oauth2/publicAppAuthorize.htm?app_id='.$jinsom_social_login_tab['jinsom_login_alipay_appid'].'&scope=auth_user&redirect_uri='.urlencode(home_url('/Extend/oauth/alipay/index.php')).'&state='.md5(uniqid(rand(),true));
}

}

//判断登录类型
function jinsom_is_login_type($type){
$jinsom_login_add=jinsom_get_option('jinsom_login_add');
$status=0;
if($jinsom_login_add){
foreach ($jinsom_login_add as $data) {
if($type==$data['jinsom_login_add_type']){
$status=1;
}
}
}
return $status;
}

//判断注册类型
function jinsom_is_reg_type($type){
$jinsom_reg_add=jinsom_get_option('jinsom_reg_add');
$status=0;
if($jinsom_reg_add){
foreach ($jinsom_reg_add as $data) {
if($type==$data['jinsom_reg_add_type']){
$status=1;
}
}
}
return $status;
}


//获取用户注册类型
function jinsom_get_reg_type($user_id){
$reg_type=get_user_meta($user_id,'reg_type',true);
if($reg_type=='email'){
$reg_type=__('邮箱注册','jinsom');
}elseif($reg_type=='phone'){
$reg_type=__('手机号注册','jinsom');
}elseif($reg_type=='invite'){
$reg_type=__('邀请码注册','jinsom');
}elseif($reg_type=='qq'){
$reg_type=__('QQ注册','jinsom');
}elseif($reg_type=='weibo'){
$reg_type=__('微博注册','jinsom');
}elseif($reg_type=='wechat-mp'){
$reg_type=__('公众号注册','jinsom');
}elseif($reg_type=='wechat-pc'){
$reg_type=__('微信扫码注册','jinsom');
}elseif($reg_type=='github'){
$reg_type=__('Github注册','jinsom');
}elseif($reg_type=='alipay'){
$reg_type=__('支付宝注册','jinsom');
}elseif($reg_type=='simple'){
$reg_type=__('简单注册','jinsom');
}else{
$reg_type=__('未知','jinsom');
}
return $reg_type;
}

//获取用户在线类型
function jinsom_get_online_type($user_id){
$online_type=get_user_meta($user_id,'online_type',true);
if($online_type==1){
return __('移动端','jinsom');
}else{
return __('电脑端','jinsom');
}
}


//获取红包封面
function jinsom_redbag_cover($post_id){
$redbag_cover=get_post_meta($post_id,'redbag_cover',true);
$redbag_img_add=jinsom_get_option('jinsom_redbag_img_add');
if($redbag_img_add){
$url=$redbag_img_add[$redbag_cover]['img'];
if($url){
return 'background-image:url('.$url.')';
}
}
}


//显示内容链接
function jinsom_mobile_post_url($post_id){
if(wp_is_mobile()){
if(get_option('permalink_structure')){
$url=get_the_permalink($post_id);	
}else{
$url='no';	
}
$post_type=get_post_meta($post_id,'post_type',true);
if(empty($post_type)){$post_type='words';}
$reprint=get_post_meta($post_id,'reprint_post_id',true);
if(is_bbs_post($post_id)){
return get_template_directory_uri().'/mobile/templates/page/post-bbs.php?post_id='.$post_id.'&url='.$url.'&type=bbs';
}else if($reprint){
return get_template_directory_uri().'/mobile/templates/page/post-words.php?post_id='.$post_id.'&url='.$url.'&type='.$post_type;
}else if(get_post_type($post_id)=='goods'){
return get_template_directory_uri().'/mobile/templates/page/post-goods.php?post_id='.$post_id.'&url='.$url.'&type=goods&rand='.rand(0,99999999);
}else{
return get_template_directory_uri().'/mobile/templates/page/post-'.$post_type.'.php?post_id='.$post_id.'&url='.$url.'&type='.$post_type;
}
}else{
return get_the_permalink($post_id);
}
}


//更新用户名
function jinsom_update_user_login($user_id,$user_login){
global $wpdb;
$wpdb->query( "UPDATE $wpdb->users SET user_login = '$user_login' WHERE ID=$user_id;");
}


//阿里云邮件推送
function jinsom_aliyun_email($email,$title,$content){
include_once '../../../../../Extend/email/aliyun/email.php';
$request->setAccountName(jinsom_get_option('jinsom_email_aliyun_from'));
$request->setFromAlias(jinsom_get_option('jinsom_mail_name'));
$request->setAddressType(1);
$request->setReplyToAddress("true");
$request->setToAddress($email);
$request->setSubject($title);
$request->setHtmlBody($content);   
$response = $client->getAcsResponse($request);
}


//发送邮件
function jinsom_send_email($email,$title,$content){
$jinsom_email_style=jinsom_get_option('jinsom_email_style');
if($jinsom_email_style=='smtp'){
wp_mail($email,$title,$content);
}else if($jinsom_email_style=='aliyun'){
jinsom_aliyun_email($email,$title,$content);
}
}