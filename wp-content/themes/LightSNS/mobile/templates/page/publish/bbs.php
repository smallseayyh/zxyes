<?php 
//发表帖子表单
require( '../../../../../../../wp-load.php');
jinsom_upadte_user_online_time();//更新在线状态
$user_id=$current_user->ID;
$bbs_id=(int)$_GET['bbs_id'];

$bbs_parents=get_category($bbs_id)->parent;
if($bbs_parents==0){
$child_bbs_id=0;
}else{
$child_bbs_id=$bbs_id;
$bbs_id=$bbs_parents;
}

$bbs_type=get_term_meta($bbs_id,'bbs_type',true);//类型
$city=get_user_meta($user_id,'city',true);
$categories_child=get_categories("child_of=".$bbs_id."&orderby=description&order=ASC&hide_empty=0");//返回子分类的数组
//功能
$normal=get_term_meta($bbs_id,'bbs_normal',true);
$pay_see=get_term_meta($bbs_id,'bbs_pay_see',true);
$comment_see=get_term_meta($bbs_id,'bbs_comment_see',true);
$vip_see=get_term_meta($bbs_id,'bbs_vip_see',true);
$login_see=get_term_meta($bbs_id,'bbs_login_see',true);
$vote=get_term_meta($bbs_id,'bbs_vote',true);
$answer=get_term_meta($bbs_id,'bbs_answer',true);
$activity=get_term_meta($bbs_id,'bbs_activity',true);

$publish_comment_status=get_term_meta($bbs_id,'publish_comment_status',true);
$publish_comment_private=get_term_meta($bbs_id,'publish_comment_private',true);



//悬赏范围
$answer_price_mini=(int)jinsom_get_option('jinsom_answer_price_mini');
$answer_price_max=(int)jinsom_get_option('jinsom_answer_price_max');

$topic=$_GET['topic'];
jinsom_update_ip($user_id);//更新定位
?>
<div data-page="publish" class="page no-tabbar toolbar-fixed">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php echo get_category($_GET['bbs_id'])->name;?></div>
<div class="right">

<?php if(jinsom_get_option('jinsom_machine_verify_on_off')&&in_array("publish",jinsom_get_option('jinsom_machine_verify_use_for'))&&!jinsom_is_admin($user_id)){?>
<a href="#" class="link icon-only" id="publish-bbs"><?php _e('发表','jinsom');?></a>
<?php }else{?>
<a href="#" class="link icon-only" onclick="jinsom_publish_bbs('','');"><?php _e('发表','jinsom');?></a>
<?php }?>

</div>
</div>
</div>



<div class="page-content">

<?php if($categories_child&&$bbs_parents==0){?>
<div class="jinsom-publish-select-cat">
<div class="select-title"><?php _e('选择分类','jinsom');?></div>
<div class="select-content">
<?php
foreach($categories_child as $data) {
echo '<li data="'.$data->cat_ID.'"><div class="img" style="background-image:url('.jinsom_get_bbs_avatar_url($data->cat_ID,0).')"><div class="shade"></div><span><i class="jinsom-icon jinsom-yiguanzhu"></i></span></div><p>'.$data->cat_name.'</p></li>';}?>
</div>
</div>
<?php }?>

<div class="jinsom-publish-words-form bbs">
<form id="jinsom-publish-form">
<div class="title" style="display: block;"><input type="text" name="title" placeholder="<?php _e('标题','jinsom');?>"></div>
<div class="content">
<textarea name="content" placeholder="<?php echo __('来说点什么吧...','jinsom');?>" class="resizable jinsom-smile-textarea"></textarea>
<i onclick="jinsom_smile_form(this)" class="smile jinsom-icon expression jinsom-weixiao-"></i>
<div class="hidden-smile" style="display: none;"><?php echo jinsom_get_expression(2,0);?></div>
</div>

<!-- 定位 -->
<div class="category-city">
<?php if(!get_user_meta($user_id,'publish_city',true)&&jinsom_get_option('jinsom_location_on_off')!='no'){?>
<div class="city" onclick="jinsom_publish_city(this)"><i class="jinsom-icon jinsom-xiazai19"></i> <m><?php echo $city;?></m></div>
<input type="hidden" id="jinsom-pop-city" name="city" value="<?php echo $city;?>">
<?php }?>
</div>

<?php if($bbs_type=='download'){?>
<div class="download-box">
<div class="li">
<input type="text" class="download-url" style="width:100%;" placeholder="<?php _e('下载地址','jinsom');?>">
<input type="text" class="download-pass-a" style="width:40%;margin-right:15%;" placeholder="<?php _e('提取密码','jinsom');?>">
<input type="text" class="download-pass-b" style="width:40%;" placeholder="<?php _e('解压密码','jinsom');?>">
</div>
<div class="jinsom-bbs-download-add"><span><i class="jinsom-icon jinsom-fabu8"></i> 新增下载地址</span></div>
</div>
<?php }?>

<?php 
//自定义发表字段
$publish_field=get_term_meta($bbs_id,'publish_field',true);
if($publish_field){
echo '<div class="field-box">';
$publish_field_arr=explode(",",$publish_field);
foreach ($publish_field_arr as $data) {
$key_arr=explode("|",$data);
if($key_arr){
if($key_arr[1]=='textarea'){
$input='<textarea name="'.$key_arr[2].'" placeholder="请输入'.$key_arr[0].'"></textarea>';
}else if($key_arr[1]=='select'){
$select_arr=explode("##",$key_arr[3]);
$select_str='<option value="">请选择'.$key_arr[0].'</option>';
for ($i=0; $i<count($select_arr); $i++) {
$select_str.='<option value="'.$select_arr[$i].'">'.$select_arr[$i].'</option>';
} 
$input='<select name="'.$key_arr[2].'">'.$select_str.'</select>';
}else{
if($key_arr[1]=='number'){
$input_type='tel';
}else{
$input_type='text';	
}
$input='<input type="'.$input_type.'" name="'.$key_arr[2].'" style="width:100%;" placeholder="请输入'.$key_arr[0].'">';
}

echo $input;
}

}
echo '</div>';
}
?>

<div class="power-content">
<?php if($pay_see){?>
<input type="tel" name="price" class="price" placeholder="<?php _e('请输入内容售价','jinsom');?>" style="width: 100%;display: none;">
<?php }?>
<?php if($answer){?>
<input type="tel" name="answer-price" class="answer-price" placeholder="<?php _e('请输入悬赏金额','jinsom');?> (<?php echo $answer_price_mini;?>-<?php echo $answer_price_max.jinsom_get_option('jinsom_credit_name');?>)" style="width: 100%;padding: 2vw 0;margin: 2vw 0;display: none;">
<?php }?>
<?php if(($pay_see||$vip_see||$login_see||$comment_see)&&$bbs_type!='download'){?>
<textarea name="hide-content" placeholder="<?php _e('隐藏内容','jinsom');?><?php echo $hide_placeholder;?>" style="display: none;"></textarea>
<?php }?>
</div>

<div class="topic clear">
<?php 
$publish_default_topic=get_term_meta($bbs_id,'publish_default_topic',true);
if($topic){
echo '<span onclick="$(this).remove();" data="'.$topic.'">#'.$topic.'#</span>';
}else if($publish_default_topic){
$publish_default_topic_arr=explode(",",$publish_default_topic);
if($publish_default_topic_arr){
foreach ($publish_default_topic_arr as $topic) {
echo '<span onclick="$(this).remove();" data="'.$topic.'">#'.$topic.'#</span>';
}
}
}

?>
</div><!-- 话题列表 -->
<div class="tool">
<span onclick="jinsom_publish_power_form();" class="power"><i class="jinsom-icon jinsom-quanxian"></i><p>权限</p></span>
<span onclick="jinsom_publish_add_topic_form()"><i class="jinsom-icon jinsom-huati"></i><p><?php echo jinsom_get_option('jinsom_topic_name');?></p></span>
<span class="open-popup" data-popup=".jinsom-publish-aite-popup"><i class="jinsom-icon jinsom-aite2"></i><p>@好友</p></span>

<?php if($bbs_type!='download'){?>
<?php if($publish_comment_private){?>
<span onclick="jinsom_publish_select_comment_private(this)"><i class="jinsom-icon jinsom-kaisuo"></i><p>回复隐私</p></span>
<?php }?>
<?php if($publish_comment_status){?>
<span onclick="jinsom_publish_select_comment_power(this)"><i class="jinsom-icon jinsom-quxiaojinzhi-"></i><p>评论</p></span>
<?php }?>
<?php }?>

</div>
<div class="images clear">
<ul id="jinsom-publish-images-list"></ul>
<div class="add"><i class="jinsom-icon jinsom-tupian2"></i><span class="preloader preloader-white"></span><input id="file" type="file" accept="image/*" multiple="multiple"></div>
</div>

<input type="hidden"  name="post-type">
<input type="hidden"  name="bbs_id" value="<?php echo $bbs_id;?>">
<input type="hidden" name="bbs_child_id" value="<?php echo $child_bbs_id;?>">

<input type="hidden" id="jinsom-pop-comment-status" name="comment-status" value="open">
<?php if($publish_comment_private){?>
<input type="hidden" id="jinsom-pop-comment-private" name="comment-private">
<?php }?>
</form>

<?php echo do_shortcode(get_term_meta($bbs_id,'mobile_publish_footer_html',true));?>
</div>

</div>


<div class="jinsom-publish-power-list-form" style="display: none;">
<div class="jinsom-popup-half-header"><div class="title">内容权限</div><div class="close" onclick="layer.closeAll()"><i class="jinsom-icon jinsom-guanbi"></i></div></div>
<div class="jinsom-publish-power-list clear">
<?php if($normal){?><li type="normal">普通内容</li><?php }?>
<?php if($pay_see){?><li type="pay_see">付费内容</li><?php }?>
<?php if($answer&&$bbs_type!='download'){?><li type="answer">问答悬赏</li><?php }?>
<?php if($vip_see){?><li type="vip_see">VIP可见</li><?php }?>
<?php if($login_see){?><li type="login_see">登录可见</li><?php }?>
<?php if($comment_see&&$bbs_type!='download'){?><li type="comment_see">回复可见</li><?php }?>
<?php if(0){?><li type="activity">活动内容</li><?php }?>
<?php if(0){?><li type="vote">投票内容</li><?php }?>
</div>
</div>



<?php require(get_template_directory().'/mobile/templates/page/publish/popup.php');?>
</div>   
