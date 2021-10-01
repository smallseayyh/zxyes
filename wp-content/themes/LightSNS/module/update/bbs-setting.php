<?php
//更新论坛设置信息
require( '../../../../../wp-load.php' );
$user_id=$current_user->ID;
$bbs_id=$_POST['bbs_id'];
$admin_a=get_term_meta($bbs_id,'bbs_admin_a',true);
$admin_a_arr=explode(",",$admin_a);
if(jinsom_is_admin($user_id)||jinsom_is_bbs_admin_a($user_id,$admin_a_arr)){//管理员或者大版主

//基本
$avatar=$_POST['avatar'];
$bg=$_POST['bg'];
$bg_mobile=$_POST['bg_mobile'];
$desc=$_POST['desc'];
$notice=$_POST['notice'];
$copyright=$_POST['copyright'];
$mobile_copyright=$_POST['mobile_copyright'];


//外观布局
$bbs_type=$_POST['bbs_type'];
$layout_sidebar=$_POST['layout-sidebar'];
$single_sidebar=$_POST['single_sidebar'];
$list_style=$_POST['list-style'];
$css=$_POST['css'];
$mobile_css=$_POST['mobile_css'];
$bbs_single_nav=$_POST['bbs_single_nav'];

//发帖权限
$power=$_POST['power'];
$publish_power_exp=$_POST['publish_power_exp'];
$publish_power_honor=$_POST['publish_power_honor'];
$publish_power_verify=$_POST['publish_power_verify'];

//回帖权限
$bbs_comment_power=$_POST['bbs_comment_power'];
$bbs_comment_power_exp=$_POST['bbs_comment_power_exp'];
$bbs_comment_power_honor=$_POST['bbs_comment_power_honor'];
$bbs_comment_power_verify=$_POST['bbs_comment_power_verify'];


//访问权限
$visit_power=$_POST['visit_power'];
$visit_power_pass=$_POST['visit_power_pass'];
$visit_power_exp=$_POST['visit_power_exp'];
$visit_power_user=$_POST['visit_power_user'];
$visit_power_honor=$_POST['visit_power_honor'];
$visit_power_verify=$_POST['visit_power_verify'];
$visit_power_pay_price=$_POST['visit_power_pay_price'];
$visit_power_pay_user_id=$_POST['visit_power_pay_user_id'];
$visit_power_pay_user_list=$_POST['visit_power_pay_user_list'];

//大小版主名称
$admin_a_name=$_POST['admin_a_name'];
$admin_b_name=$_POST['admin_b_name'];

$admin_a=$_POST['admin_a'];
$admin_b=$_POST['admin_b'];
$blacklist=$_POST['blacklist'];

//功能
$normal=$_POST['normal'];
$pay_see=$_POST['pay_see'];
$vip_see=$_POST['vip_see'];
$comment_see=$_POST['comment_see'];
$login_see=$_POST['login_see'];

$vote=$_POST['vote'];
$answer=$_POST['answer'];
$group_im=$_POST['group_im'];
$activity=$_POST['activity'];
$pending=$_POST['pending'];


//回复选项
$publish_images=$_POST['publish_images'];
$publish_files=$_POST['publish_files'];
$publish_comment_status=$_POST['publish_comment_status'];
$publish_comment_private=$_POST['publish_comment_private'];
$publish_text=$_POST['publish_text'];
$activity_text=$_POST['activity_text'];


//帖子相关
$showposts=$_POST['showposts'];
$post_target=$_POST['post_target'];
$post_child_commend=$_POST['post_child_commend'];
$child_show_bbs=$_POST['child_show_bbs'];//子论坛显示子版块
$credit_post_number=(int)$_POST['credit_post_number'];
$credit_reply_number=(int)$_POST['credit_reply_number'];
$exp_post_number=(int)$_POST['exe_post_number'];
$exp_reply_number=(int)$_POST['exe_reply_number'];
$last_reply_time=(int)$_POST['last_reply_time'];

//广告
$ad_header=$_POST['ad_header'];
$ad_footer=$_POST['ad_footer'];
$ad_single_header=$_POST['ad_single_header'];
$ad_single_footer=$_POST['ad_single_footer'];

//发布头部自定义区域
$publish_default_topic=$_POST['publish_default_topic'];//发表预设话题
$publish_field=$_POST['publish_field'];//发表字段
$publish_page_header_html=$_POST['publish_page_header_html'];
$mobile_publish_footer_html=$_POST['mobile_publish_footer_html'];//移动端底部

//seo
$bbs_seo_title=$_POST['bbs_seo_title'];
$bbs_seo_desc=$_POST['bbs_seo_desc'];
$bbs_seo_keywords=$_POST['bbs_seo_keywords'];


//默认数据类型
$data_type=$_POST['data_type'];

//菜单
$enabled_menu=rtrim($_POST['enabled_menu'],',');//开启的菜单
if(!$enabled_menu){$enabled_menu='empty';}
$disabled_menu=rtrim($_POST['disabled_menu'],',');//关闭的菜单
if(!$disabled_menu){$disabled_menu='empty';}


//开关默认值设置
if($bbs_single_nav=='on'){$bbs_single_nav=1;}else{$bbs_single_nav=0;}
if($normal=='on'){$normal=1;}else{$normal=0;}
if($pay_see=='on'){$pay_see=1;}else{$pay_see=0;}
if($vip_see=='on'){$vip_see=1;}else{$vip_see=0;}
if($comment_see=='on'){$comment_see=1;}else{$comment_see=0;}
if($login_see=='on'){$login_see=1;}else{$login_see=0;}

if($vote=='on'){$vote=1;}else{$vote=0;}
if($answer=='on'){$answer=1;}else{$answer=0;}
if($group_im=='on'){$group_im=1;}else{$group_im=0;}
if($activity=='on'){$activity=1;}else{$activity=0;}
if($pending=='on'){$pending=1;}else{$pending=0;}//审核

if($post_target=='on'){$post_target=1;}else{$post_target=0;}
if($post_child_commend=='on'){$post_child_commend=1;}else{$post_child_commend=0;}
if($child_show_bbs=='on'){$child_show_bbs=1;}else{$child_show_bbs=0;}//子论坛显示子版块

//发布选项开关
if($publish_images=='on'){$publish_images=1;}else{$publish_images=0;}
if($publish_files=='on'){$publish_files=1;}else{$publish_files=0;}
if($publish_comment_status=='on'){$publish_comment_status=1;}else{$publish_comment_status=0;}
if($publish_comment_private=='on'){$publish_comment_private=1;}else{$publish_comment_private=0;}


//基本
update_term_meta($bbs_id,'bbs_avatar',$avatar);
update_term_meta($bbs_id,'bbs_type',$bbs_type);
update_term_meta($bbs_id,'bbs_bg',$bg);
update_term_meta($bbs_id,'bbs_mobile_bg',$bg_mobile);
update_term_meta($bbs_id,'bbs_notice',$notice);
update_term_meta($bbs_id,'copyright',$copyright);
update_term_meta($bbs_id,'mobile_copyright',$mobile_copyright);

//外观布局
update_term_meta($bbs_id,'layout_sidebar',$layout_sidebar);
update_term_meta($bbs_id,'single_sidebar',$single_sidebar);
update_term_meta($bbs_id,'bbs_list_style',$list_style);
update_term_meta($bbs_id,'bbs_css',$css);
update_term_meta($bbs_id,'bbs_mobile_css',$mobile_css);
update_term_meta($bbs_id,'bbs_single_nav',$bbs_single_nav);

//发帖权限
update_term_meta($bbs_id,'bbs_power',$power);
if($power==6){//指定需要的发帖经验
update_term_meta($bbs_id,'publish_power_exp',$publish_power_exp);
}
if($power==7){//指定头衔
update_term_meta($bbs_id,'publish_power_honor',$publish_power_honor);
}
if($power==8){//指定认证
update_term_meta($bbs_id,'publish_power_verify',$publish_power_verify);
}

//回帖权限
update_term_meta($bbs_id,'bbs_comment_power',$bbs_comment_power);
if($bbs_comment_power==6){//指定需要的发帖经验
update_term_meta($bbs_id,'bbs_comment_power_exp',$bbs_comment_power_exp);
}
if($bbs_comment_power==7){//指定头衔
update_term_meta($bbs_id,'bbs_comment_power_honor',$bbs_comment_power_honor);
}
if($bbs_comment_power==8){//指定认证
update_term_meta($bbs_id,'bbs_comment_power_verify',$bbs_comment_power_verify);
}

//访问权限
update_term_meta($bbs_id,'bbs_visit_power',$visit_power);
if($visit_power==5){//密码访问
update_term_meta($bbs_id,'bbs_visit_pass',$visit_power_pass);
}
if($visit_power==6){//指定经验
update_term_meta($bbs_id,'bbs_visit_exp',$visit_power_exp);
}
if($visit_power==7){//指定用户
update_term_meta($bbs_id,'bbs_visit_user',$visit_power_user);
}
if($visit_power==9){//指定头衔
update_term_meta($bbs_id,'bbs_visit_honor',$visit_power_honor);
}
if($visit_power==10){//指定认证
update_term_meta($bbs_id,'bbs_visit_verify',$visit_power_verify);
}
if($visit_power==11){//付费
update_term_meta($bbs_id,'visit_power_pay_price',$visit_power_pay_price);
update_term_meta($bbs_id,'visit_power_pay_user_id',$visit_power_pay_user_id);
update_term_meta($bbs_id,'visit_power_pay_user_list',$visit_power_pay_user_list);
}



if(jinsom_is_admin($user_id)){//管理员才可以设置版主
update_term_meta($bbs_id,'bbs_admin_a',$admin_a);
}

update_term_meta($bbs_id,'bbs_admin_b',$admin_b);
update_term_meta($bbs_id,'bbs_blacklist',$blacklist);

//功能
update_term_meta($bbs_id,'bbs_normal',$normal);
update_term_meta($bbs_id,'bbs_pay_see',$pay_see);
update_term_meta($bbs_id,'bbs_vip_see',$vip_see);
update_term_meta($bbs_id,'bbs_comment_see',$comment_see);
update_term_meta($bbs_id,'bbs_login_see',$login_see);

update_term_meta($bbs_id,'bbs_vote',$vote);
update_term_meta($bbs_id,'bbs_answer',$answer);
update_term_meta($bbs_id,'bbs_group_im',$group_im);
update_term_meta($bbs_id,'bbs_activity',$activity);
update_term_meta($bbs_id,'pending',$pending);//审核

//发布选项
update_term_meta($bbs_id,'publish_images',$publish_images);
update_term_meta($bbs_id,'publish_files',$publish_files);
update_term_meta($bbs_id,'publish_comment_status',$publish_comment_status);
update_term_meta($bbs_id,'publish_comment_private',$publish_comment_private);
update_term_meta($bbs_id,'publish_text',$publish_text);//发表文字
update_term_meta($bbs_id,'activity_text',$activity_text);//报名文字



//帖子相关
update_term_meta($bbs_id,'bbs_showposts',$showposts);
update_term_meta($bbs_id,'bbs_post_target',$post_target);
update_term_meta($bbs_id,'bbs_post_child_commend',$post_child_commend);
update_term_meta($bbs_id,'child_show_bbs',$child_show_bbs);//子论坛显示子版块

if(jinsom_is_admin($user_id)){
update_term_meta($bbs_id,'bbs_credit_post_number',$credit_post_number);
update_term_meta($bbs_id,'bbs_credit_reply_number',$credit_reply_number);
update_term_meta($bbs_id,'bbs_exp_post_number',$exp_post_number);
update_term_meta($bbs_id,'bbs_exp_reply_number',$exp_reply_number);
update_term_meta($bbs_id,'publish_field',$publish_field);//发表字段
//大小版主名称
update_term_meta($bbs_id,'admin_a_name',$admin_a_name);
update_term_meta($bbs_id,'admin_b_name',$admin_b_name);

}

update_term_meta($bbs_id,'bbs_last_reply_time',$last_reply_time);

//广告
update_term_meta($bbs_id,'bbs_ad_header',$ad_header);
update_term_meta($bbs_id,'bbs_ad_footer',$ad_footer);
update_term_meta($bbs_id,'ad_single_header',$ad_single_header);
update_term_meta($bbs_id,'ad_single_footer',$ad_single_footer);

//发布头部自定义区域
update_term_meta($bbs_id,'publish_default_topic',$publish_default_topic);
update_term_meta($bbs_id,'publish_page_header_html',$publish_page_header_html);
update_term_meta($bbs_id,'mobile_publish_footer_html',$mobile_publish_footer_html);//移动端底部

//seo
update_term_meta($bbs_id,'bbs_seo_title',$bbs_seo_title);
update_term_meta($bbs_id,'bbs_seo_desc',$bbs_seo_desc);
update_term_meta($bbs_id,'bbs_seo_keywords',$bbs_seo_keywords);

//描述
update_term_meta($bbs_id,'desc',$desc);

//菜单
update_term_meta($bbs_id,'enabled_menu',$enabled_menu);
update_term_meta($bbs_id,'disabled_menu',$disabled_menu);

update_term_meta($bbs_id,'custom_menu_name_1',$_POST['custom_menu_name_1']);
update_term_meta($bbs_id,'custom_menu_topic_1',$_POST['custom_menu_topic_1']);
update_term_meta($bbs_id,'custom_menu_name_2',$_POST['custom_menu_name_2']);
update_term_meta($bbs_id,'custom_menu_topic_2',$_POST['custom_menu_topic_2']);
update_term_meta($bbs_id,'custom_menu_name_3',$_POST['custom_menu_name_3']);
update_term_meta($bbs_id,'custom_menu_topic_3',$_POST['custom_menu_topic_3']);
update_term_meta($bbs_id,'custom_menu_name_4',$_POST['custom_menu_name_4']);
update_term_meta($bbs_id,'custom_menu_topic_4',$_POST['custom_menu_topic_4']);
update_term_meta($bbs_id,'custom_menu_name_5',$_POST['custom_menu_name_5']);
update_term_meta($bbs_id,'custom_menu_topic_5',$_POST['custom_menu_topic_5']);



//默认数据类型
update_term_meta($bbs_id,'data_type',$data_type);

}//判断是否管理员，防止抓包
