<?php 
//获取我的页面的数据
require( '../../../../../../wp-load.php' );
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
$jinsom_mobile_mine_style=jinsom_get_option('jinsom_mobile_mine_style');
$jinsom_mobile_mine_add=jinsom_get_option('jinsom_mobile_mine_add');
if($jinsom_mobile_mine_add){
foreach($jinsom_mobile_mine_add as $data){
$mine_type=$data['jinsom_mobile_mine_type'];
if($mine_type=='list'){
echo '<div class="jinsom-mine-box list-block clear '.$jinsom_mobile_mine_style.'">';

if($jinsom_mobile_mine_style=='cell'&&$data['marks']){
echo '<div class="jinsom-mine-box-title clear">'.do_shortcode($data['marks']).'</div>';
}

if($data['jinsom_mobile_mine_menu_list_add']){
foreach ($data['jinsom_mobile_mine_menu_list_add'] as $menu){
$menu_type=$menu['jinsom_mobile_mine_menu_list_type'];

// if($menu_type=='info'){//用户信息资料



// }else{
$badge='';
$after='';
if($menu_type=='custom'){//自定义菜单
$link=do_shortcode($menu['link']);
$after=do_shortcode($menu['right_html']);
$default_icon='<i class="jinsom-icon jinsom-yingyong3"></i>';
}else if($menu_type=='majia'){//马甲
$link=$theme_url.'/mobile/templates/page/majia.php';
$default_icon='<i class="jinsom-icon jinsom-qiehuanyonghu"></i>';
}else if($menu_type=='wallet'){//钱包
$link=$theme_url.'/mobile/templates/page/mywallet/mywallet.php';
$after='<span class="jinsom-mine-list-credit">'.(int)get_user_meta($user_id,'credit',true).'</span> '.jinsom_get_option('jinsom_credit_name');
$default_icon='<i class="jinsom-icon jinsom-qianbao"></i>';	
}else if($menu_type=='vip'){//会员
$link=$theme_url.'/mobile/templates/page/vip.php';
$after=jinsom_vip_text($user_id);
$default_icon='<i class="jinsom-icon jinsom-huiyuan"></i>';	
}else if($menu_type=='notice'){//消息
$link=$theme_url.'/mobile/templates/page/notice.php';
$default_icon='<i class="jinsom-icon jinsom-xiaoxi"></i>';	
}else if($menu_type=='sign'){//签到
$link=$theme_url.'/mobile/templates/page/sign.php';
if(jinsom_is_sign($user_id,date('Y-m-d',time()))){
$after=sprintf(__( '累计%s天','jinsom'),get_user_meta($user_id,'sign_c',true));
}else{
$after=__('今日还没有签到','jinsom');
}
$default_icon='<i class="jinsom-icon jinsom-qiandao1"></i>';	
}else if($menu_type=='follower'){//粉丝
$link=$theme_url.'/mobile/templates/page/follower.php';
$after='<span class="badge">'.jinsom_follower_count($user_id).'</span>';
$default_icon='<i class="jinsom-icon jinsom-fensi"></i>';	
}else if($menu_type=='following'){//关注
$link=$theme_url.'/mobile/templates/page/follower.php?type=following';
$after='<span class="badge">'.jinsom_following_count($user_id).'</span>';
$default_icon='<i class="jinsom-icon jinsom-guanzhu8"></i>';	
}else if($menu_type=='visitor'){//访客
$visitor=(int)get_user_meta($user_id,'visitor',true);
$add_visit=(int)get_user_meta($user_id,'add_visit',true);
$add_visit_a=$visitor-$add_visit;
if($add_visit_a>0){$badge='<i></i>';}
$link=$theme_url.'/mobile/templates/page/visitor.php';
$after='<span class="badge">'.$visitor.' 人气';
$default_icon='<i class="jinsom-icon jinsom-jiaoyin"></i>';	
}else if($menu_type=='gift'){//礼物
$link=$theme_url.'/mobile/templates/page/my-gift.php';
$after='<span class="badge">'.(int)get_user_meta($user_id,'charm',true).' 魅力</span>';
$default_icon='<i class="jinsom-icon jinsom-liwu"></i>';	
}else if($menu_type=='bbs'){//论坛
$link=$theme_url.'/mobile/templates/page/bbs-like.php';
$default_icon='<i class="jinsom-icon jinsom-luntan"></i>';	
}else if($menu_type=='topic'){//话题
$link=$theme_url.'/mobile/templates/page/topic-like.php';
$default_icon='<i class="jinsom-icon jinsom-huati2"></i>';	
}else if($menu_type=='invite'){//推广
$link=$theme_url.'/mobile/templates/page/referral.php';
$after=(int)get_user_meta($user_id,'invite_number',true).__('人','jinsom');
$default_icon='<i class="jinsom-icon jinsom-tuiguang"></i>';	
}else if($menu_type=='collect'){//收藏
$link=$theme_url.'/mobile/templates/page/collect.php';
$default_icon='<i class="jinsom-icon jinsom-shoucang"></i>';	
}else if($menu_type=='blacklist'){//黑名单
$link=$theme_url.'/mobile/templates/page/blacklist.php';
$default_icon='<i class="jinsom-icon jinsom-heimingdan2"></i>';	
}else if($menu_type=='history-single'){//历史浏览
$link=$theme_url.'/mobile/templates/page/history-single.php';
$default_icon='<i class="jinsom-icon jinsom-lishi"></i>';	
}else if($menu_type=='task'){//任务
$link=$theme_url.'/mobile/templates/page/task.php';
$task_times=(int)get_user_meta($user_id,'task_times',true);
$after=sprintf(__('已完成<n>%s</n>个任务','jinsom'),$task_times);
$default_icon='<i class="jinsom-icon jinsom-flag"></i>';	
}else if($menu_type=='profile'){//设置
$link=$theme_url.'/mobile/templates/page/setting/setting.php?author_id='.$user_id;
$after='<i class="jinsom-icon jinsom-erweima"></i>';
$default_icon='<i class="jinsom-icon jinsom-shezhi2"></i>';	
}else if($menu_type=='key-recharge'){//卡密兑换
$link=$theme_url.'/mobile/templates/page/mywallet/key.php?navbar_name='.$menu['name'];
$default_icon='<i class="jinsom-icon jinsom-qiamizhifu"></i>';	
}

if($menu['icon']){$icon=$menu['icon'];}else{$icon=$default_icon;}


if($jinsom_mobile_mine_style=='list'){
$templates='
<li class="'.$menu_type.'">
<a class="link item-link item-content" href="'.$link.'">
<div class="item-media"><font style="color:'.$menu['icon_color'].'">'.$icon.'</font></div>
<div class="item-inner">
<div class="item-title">'.$menu['name'].$badge.'</div>
<div class="item-after">'.$after.'</div>
</div>
</a>
</li>';
}else{
$templates='
<li class="'.$menu_type.'">
<a class="link" href="'.$link.'">
<font style="color:'.$menu['icon_color'].'">'.$icon.'</font>
<p>'.$menu['name'].$badge.'</p>
</a>
</li>';
}


if($menu_type=='majia'){//马甲
if(jinsom_get_option('jinsom_majia_on_off')){
$majia_user_id=jinsom_get_option('jinsom_majia_user_id');
$majia_arr=explode(",",$majia_user_id);
if(in_array($user_id,$majia_arr)||current_user_can('level_10')){
echo $templates;
}
}
}else{
echo $templates;
}



// }




}
}else{
echo '该模块还没有添加菜单！';
}
echo '</div>';

}else if($mine_type=='profile'){//资料信息
echo '<div class="jinsom-mine-box list-block clear '.$jinsom_mobile_mine_style.'">';

$user_exp=(int)get_user_meta($user_id,'exp',true);
$max_exp=jinsom_lv_current_max($user_id);
$percent_exp=($user_exp/$max_exp)*100;
if($percent_exp>100){$percent_exp=100;}

if($jinsom_mobile_mine_style=='list'){
echo '
<li class="jinsom-mine-user-info">
<a class="link item-link item-content" href="'.jinsom_mobile_author_url($user_id).'">
<div class="item-media">'.jinsom_avatar($user_id,'80',avatar_type($user_id)).jinsom_verify($user_id).'</div>
<div class="item-inner">
<div class="item-title">
<div class="name">'.jinsom_nickname($user_id).jinsom_sex($user_id).jinsom_lv($user_id).jinsom_honor($user_id).'</div>
<div class="exp">
<div class="progress">
<div class="bar" style="width:'.$percent_exp.'%;"></div>
</div>
<span class="progress-text">'.$user_exp.'/'.$max_exp.'</span>
</div>
<div class="id">'.__('用户ID','jinsom').'：'.$user_id.'</div>
</div>
</div>
</a>
</li>';
}else{

echo '
<div class="jinsom-mine-user-info-cell">

<div class="header clear">
<a href="'.jinsom_mobile_author_url($user_id).'" class="link">
<div class="avatarimg">'.jinsom_avatar($user_id,'80',avatar_type($user_id)).jinsom_verify($user_id).'</div>
<div class="info">
<p class="name">'.jinsom_nickname($user_id).jinsom_sex($user_id).jinsom_vip($user_id).jinsom_honor($user_id).'</p>
<p class="id">用户ID：'.$user_id.'</p>
</div>
<div class="go">个人主页 <i class="jinsom-icon jinsom-arrow-right"></i></div>
</a>
</div>

<div class="exp">
<div class="progress"><div class="bar" style="width:'.$percent_exp.'%;"></div></div>
<span class="progress-text">'.$user_exp.'/'.$max_exp.'</span>
</div>
<div class="number">
<div class="list"><span>'.count_user_posts($user_id,'post').'</span><p>内容</p></div>
<div class="list"><span>'.(int)get_user_meta($user_id,'visitor',true).'</span><p>人气</p></div>
<div class="list"><a href="'.$theme_url.'/mobile/templates/page/follower.php" class="link"><span>'.jinsom_follower_count($user_id).'</span><p>粉丝</p></a></div>
<div class="list"><a href="'.$theme_url.'/mobile/templates/page/follower.php?type=following" class="link"><span>'.jinsom_following_count($user_id).'</span><p>关注</p></a></div>
</div>
</div>
';
}

echo '</div>';

}else{
echo do_shortcode($data['jinsom_mobile_mine_type_html']);
}




}//foreach

}else{
echo '请在后台-主题配置-移动设置-我的页面-进行配置数据';
}
