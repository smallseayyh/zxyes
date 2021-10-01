<?php 
require( '../../../../../wp-load.php' );
$author_id=(int)$_POST['author_id'];
$user_info=get_userdata($author_id);
$gender=$user_info->gender;
$user_id=$current_user->ID;
if($user_id!=$author_id&&!jinsom_is_admin($user_id)){exit;}
$jinsom_cash_on_off = jinsom_get_option('jinsom_cash_on_off');
$jinsom_verify_url = jinsom_get_option('jinsom_verify_url');

$avatar_type=get_user_meta($author_id,'avatar_type',true);
$upload_avatar=get_user_meta($author_id,'customize_avatar',true);

$jinsom_qq_avatar=get_user_meta($author_id,'qq_avatar',true);
$jinsom_weibo_avatar=get_user_meta($author_id,'weibo_avatar',true);
$jinsom_wechat_avatar=get_user_meta($author_id,'wechat_avatar',true);
$jinsom_github_avatar=get_user_meta($author_id,'github_avatar',true);
$jinsom_alipay_avatar=get_user_meta($author_id,'alipay_avatar',true);

$verify_add=jinsom_get_option('jinsom_verify_add');
$user_honor=$user_info->user_honor;

if(empty($user_honor)){
$use_honor='';
}else{
$use_honor=$user_info->use_honor;//当前使用的头衔
if(empty($use_honor)){
$honor_arr=explode(",",$user_honor);
update_user_meta($author_id,'use_honor',$honor_arr[0]);
$use_honor=$honor_arr[0];
}
}

if($user_id==$author_id){
jinsom_update_ip($author_id);//更新定位
}

$jinsom_member_profile_setting_add=jinsom_get_option('jinsom_member_profile_setting_add');
?>

<div class="jinsom-page jinsom-setting-page" author_id="<?php echo $author_id;?>">
<div class="layui-tab layui-tab-brief" id="jinsom-setting-menu">
<ul class="layui-tab-title">
<li class="layui-this"><?php _e('基本设置','jinsom');?></li>
<li><?php _e('账户设置','jinsom');?></li>
<li><?php _e('偏好设置','jinsom');?></li>
<?php if($jinsom_member_profile_setting_add){?>
<li><?php _e('其他资料','jinsom');?></li>
<?php }?>
<li><?php _e('背景音乐','jinsom');?></li>
</ul>
<div class="layui-tab-content">
<div class="layui-tab-item layui-show">

<form id="jinsom-setting-base" class="layui-form layui-form-pane">

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('头像类型','jinsom');?></label>
<div class="layui-input-block" style="margin-left: 150px;">
<?php if($upload_avatar){
echo jinsom_avatar($author_id,'40','upload');
?>
<input type="radio" name="avatar_type" title="<?php _e('上传','jinsom');?>"  value="upload" <?php if($avatar_type=='upload'||$avatar_type=='') echo 'checked';?>>
<?php }else{
echo jinsom_avatar($author_id,'40','default');
?>
<input type="radio" name="avatar_type" title="<?php _e('默认','jinsom');?>"  value="default" <?php if($avatar_type=='default'||$avatar_type=='') echo 'checked';?>>
<?php } ?>


<?php if($jinsom_qq_avatar){?>
<?php echo jinsom_avatar($author_id,'40','qq'); ?>
<input type="radio" name="avatar_type" title="QQ" value="qq" <?php if($avatar_type=='qq') echo 'checked';?>>
<?php }?>

<?php if($jinsom_weibo_avatar){?>
<?php echo jinsom_avatar($author_id,'40','weibo');?>
<input type="radio" name="avatar_type" title="<?php _e('微博','jinsom');?>" value="weibo" <?php if($avatar_type=='weibo') echo 'checked';?>>
<?php } ?>

<?php if($jinsom_wechat_avatar){?>
<?php echo jinsom_avatar($author_id,'40','wechat'); ?>
<input type="radio" name="avatar_type" title="<?php _e('微信','jinsom');?>" value="wechat" <?php if($avatar_type=='wechat') echo 'checked';?>>
<?php } ?>

<?php if($jinsom_github_avatar){?>
<?php echo jinsom_avatar($author_id,'40','github');?>
<input type="radio" name="avatar_type" title="Github" value="github" <?php if($avatar_type=='github') echo 'checked';?>>
<?php } ?>

<?php if($jinsom_alipay_avatar){?>
<?php echo jinsom_avatar($author_id,'40','alipay');?>
<input type="radio" name="avatar_type" title="<?php _e('支付宝','jinsom');?>" value="alipay" <?php if($avatar_type=='alipay') echo 'checked';?>>
<?php } ?>

</div>
</div>



<div class="layui-form-item">
<label class="layui-form-label"><?php _e('用户ID','jinsom');?></label>
<div class="layui-input-inline">
<input class="layui-input" value="<?php echo $author_id;?>" disabled="" style="cursor: not-allowed;">
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('帐号','jinsom');?></label>
<div class="layui-input-inline">
<input class="layui-input" value="<?php echo $user_info->user_login;?>" disabled="" style="cursor: not-allowed;">
</div>
<div class="layui-form-mid layui-word-aux"><?php _e('不允许修改，可用于登录','jinsom');?></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('昵称','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" id="jinsom-nickname" class="layui-input" value="<?php echo $user_info->nickname;?>" disabled="">
</div>

<div class="layui-form-mid layui-word-aux mail"><i class="aa" onclick="jinsom_update_nickname_form(<?php echo $author_id;?>);"><?php _e('修改','jinsom');?></i>
</div>
</div>


<?php if(jinsom_is_admin($user_id)&&$author_id!=$user_id&&$author_id!=1){?>

<?php if(current_user_can('level_10')){?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('用户组','jinsom');?></label>
<div class="layui-input-inline">
<select name="user_power" lay-filter="user_power" id="jinsom-profile-user-power">
<option value ="1" <?php if($user_info->user_power==1) echo 'selected = "selected"'; ?>><?php _e('正常用户','jinsom');?></option>
<option value ="2" <?php if($user_info->user_power==2) echo 'selected = "selected"'; ?>><?php _e('网站管理','jinsom');?></option>
<option value ="3" <?php if($user_info->user_power==3) echo 'selected = "selected"'; ?>><?php _e('巡查员','jinsom');?></option>
<option value ="5" <?php if($user_info->user_power==5) echo 'selected = "selected"'; ?>><?php _e('审核员','jinsom');?></option>
<option value ="4" <?php if($user_info->user_power==4) echo 'selected = "selected"'; ?>><?php _e('风险用户--[不能登录]','jinsom');?></option>
</select>
</div>
</div>
<?php }?>


<div class="layui-form-item" <?php if($user_info->user_power!=4) echo 'style="display:none;"'; ?> id="jinsom-profile-user-power-danger-reason">
<label class="layui-form-label"><?php _e('封号原因','jinsom');?></label>
<div class="layui-input-inline" style="width: 360px;">
<textarea name="danger_reason" class="layui-textarea" placeholder="<?php _e('可以留空，留空则不提示封号原因','jinsom');?>"><?php echo $user_info->danger_reason;?></textarea>
</div>
</div> 


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('黑名单','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" name="blacklist_time" id="jinsom-setting-blacklist" value="<?php echo $user_info->blacklist_time;?>" class="layui-input">
</div>
<div class="layui-form-mid layui-word-aux"><?php _e('被拉黑的用户，全站不能进行互动','jinsom');?></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('拉黑原因','jinsom');?></label>
<div class="layui-input-inline" style="width: 360px;">
<textarea name="blacklist_reason" class="layui-textarea"><?php echo $user_info->blacklist_reason;?></textarea>
</div>
</div> 

<?php }?>


<?php if(jinsom_is_admin($user_id)){ ?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('头衔','jinsom');?></label>
<div class="layui-input-inline" style="width: 360px;">
<textarea name="user_honor" placeholder="<?php _e('多个头衔请用英文逗号隔开','jinsom');?>" class="layui-textarea"><?php echo $user_honor;?></textarea>
</div>
<div class="layui-form-mid layui-word-aux honor"><i class="aa" onclick="jinsom_use_honor_form(<?php echo $author_id;?>);"><?php _e('头衔管理','jinsom');?></i></div>
</div> 
<?php }else{?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('头衔','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" class="layui-input" id="jinsom-honor" value="<?php echo $use_honor;?>" disabled="" style="cursor: not-allowed;">
</div>
<div class="layui-form-mid layui-word-aux honor"><i class="aa" onclick="jinsom_use_honor_form(<?php echo $author_id;?>);"><?php _e('头衔管理','jinsom');?></i></div>
</div> 	
<?php }?>

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label"><?php _e('生日','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" name="birthday" id="jinsom-setting-birthday" value="<?php echo $user_info->birthday;?>" class="layui-input">
</div>
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('性别','jinsom');?></label>
<div class="layui-input-inline">
<select name="gender">
<option value ="女生" <?php if($gender=='女生') echo 'selected = "selected"'; ?>><?php _e('女生','jinsom');?></option>
<option value ="男生" <?php if($gender=='男生') echo 'selected = "selected"'; ?>><?php _e('男生','jinsom');?></option>
<option value ="保密" <?php if($gender!='女生'&&$gender!='男生') echo 'selected = "selected"'; ?>><?php _e('保密','jinsom');?></option>
</select>
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('经验','jinsom');?></label>
<div class="layui-input-inline">
<input type="number" name="exp" value="<?php echo jinsom_get_user_exp($author_id);?>" <?php if(!jinsom_is_admin($user_id)){ echo 'disabled="" ';} ?> class="layui-input">
</div>
</div>

<?php if(jinsom_is_admin($user_id)){ ?>

<div class="layui-form-item">
<label class="layui-form-label"><?php echo jinsom_get_option('jinsom_credit_name');?></label>
<div class="layui-input-inline">
<input type="number" name="credit" value="<?php  echo (int)$user_info->credit;?>" <?php if(!jinsom_is_admin($user_id)){ echo 'disabled="" ';} ?> class="layui-input">
</div>
<div class="layui-form-mid layui-word-aux mail"><i class="aa" onclick="jinsom_mywallet_form(<?php echo $author_id;?>)">
<?php if($user_id==$author_id){echo '我的钱包';}else{echo '查看Ta的钱包';}?>
</i></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('补签卡','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" name="sign_card" value="<?php  echo (int)$user_info->sign_card;?>" class="layui-input">
</div>
<div class="layui-form-mid layui-word-aux mail"><?php _e('张','jinsom');?></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('改名卡','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" name="nickname_card" value="<?php  echo (int)$user_info->nickname_card;?>" class="layui-input">
</div>
<div class="layui-form-mid layui-word-aux mail"><?php _e('张','jinsom');?></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('累计签到','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" name="sign_c" value="<?php  echo (int)$user_info->sign_c;?>" class="layui-input">
</div>
<div class="layui-form-mid layui-word-aux mail"><?php _e('天','jinsom');?></div>
</div>

<div class="layui-form-item">
<div class="layui-inline">
<label class="layui-form-label"><?php _e('VIP时间','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" name="vip_time" id="jinsom-setting-vip-time" value="<?php echo $user_info->vip_time;?>" class="layui-input">
</div>
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('VIP成长值','jinsom');?></label>
<div class="layui-input-inline">
<input type="number" name="vip_number" value="<?php echo (int)$user_info->vip_number;?>" class="layui-input">
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('魅力值','jinsom');?></label>
<div class="layui-input-inline">
<input type="number" name="charm" value="<?php echo (int)$user_info->charm;?>" class="layui-input">
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('认证类型','jinsom');?></label>
<div class="layui-input-inline">
<select name="verify">
<option value ="0" <?php if($user_info->verify==0) echo 'selected = "selected"'; ?>><?php _e('普通用户','jinsom');?></option>
<option value ="1" <?php if($user_info->verify==1) echo 'selected = "selected"'; ?>><?php _e('个人认证','jinsom');?></option>
<option value ="2" <?php if($user_info->verify==2) echo 'selected = "selected"'; ?>><?php _e('企业认证','jinsom');?></option>
<option value ="3" <?php if($user_info->verify==3) echo 'selected = "selected"'; ?>><?php _e('女神认证','jinsom');?></option>
<option value ="4" <?php if($user_info->verify==4) echo 'selected = "selected"'; ?>><?php _e('达人认证','jinsom');?></option>
<?php 
if($verify_add){
$i=5;
foreach ($verify_add as $data) {
if($user_info->verify==$i){
$selected='selected = "selected"';
}else{
$selected='';	
}
echo '<option value ="'.$i.'" '.$selected.'>'.$data['name'].'</option>';
$i++;
}
}
?>
</select>
</div>
<div class="layui-form-mid layui-word-aux mail"><a class="aa" href="<?php echo $jinsom_verify_url;?>" target="_blank"><?php _e('申请认证','jinsom');?></a></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('认证说明','jinsom');?></label>
<div class="layui-input-block">
<input type="text" name="verify_info" class="layui-input" value="<?php  echo $user_info->verify_info;?>">
</div>
</div>

<?php }else{//非管理员 ?>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('补签卡','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" value="<?php  echo (int)$user_info->sign_card;?>" class="layui-input" disabled="">
</div>
<div class="layui-form-mid layui-word-aux mail"><?php _e('张','jinsom');?></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('改名卡','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" value="<?php  echo (int)$user_info->nickname_card;?>" class="layui-input" disabled="">
</div>
<div class="layui-form-mid layui-word-aux mail"><?php _e('张','jinsom');?></div>
</div>


<?php 
if($user_info->verify){
$verify_text=jinsom_verify_type($author_id);  
}else{
$verify_text=__('普通用户','jinsom'); 
}
$verify_info=$user_info->verify_info;
?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('认证类型','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" value="<?php echo $verify_text;?>" class="layui-input" disabled="">
</div>
<div class="layui-form-mid layui-word-aux mail"><a class="aa" href="<?php echo $jinsom_verify_url;?>" target="_blank"><?php _e('申请认证','jinsom');?></a></div>
</div>

<?php if($user_info->verify!=0){?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('认证说明','jinsom');?></label>
<div class="layui-input-block">
<input type="text" class="layui-input" disabled="" value="<?php  echo $verify_info;?>">
</div>
</div>
<?php }?>

<?php }//判断是否管理员?>


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('个人说明','jinsom');?></label>
<div class="layui-input-block">
<textarea placeholder="<?php _e('介绍一下你自己嘛','jinsom');?>" class="layui-textarea" name="description"><?php echo $user_info->description;?></textarea>
</div>
</div>
<input type="hidden" name="author_id" value="<?php echo $user_info->ID;?>">
<div class="jinsom-setting-btn opacity" onclick="jinsom_setting('base')"><?php _e('保存设置','jinsom');?></div>


</form>
</div><!-- 基本资料结束 -->


<div class="layui-tab-item">
<form id="jinsom-setting-account" class="layui-form layui-form-pane">


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('安全邮箱','jinsom');?></label>
<div class="layui-input-inline">
<input value="<?php echo $user_info->user_email;?>" placeholder="<?php _e('请设置安全邮箱','jinsom');?>" class="layui-input" disabled="" id="jinsom-profile-mail">
</div>
<div class="layui-form-mid layui-word-aux mail">
<i class="aa" onclick="jinsom_update_mail_form(<?php echo $user_info->ID;?>,2);"><?php _e('设置','jinsom');?></i>
</div>
</div>


<?php if(jinsom_get_option('jinsom_sms_style')!='close'){?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('安全手机','jinsom');?></label>
<div class="layui-input-inline">
<input value="<?php echo $user_info->phone;?>" placeholder="<?php _e('请设置安全手机号','jinsom');?>" class="layui-input" disabled="" id="jinsom-profile-phone">
</div>
<div class="layui-form-mid layui-word-aux phone"><i class="aa" onclick="jinsom_update_phone_form(<?php echo $author_id;?>,2);"><?php _e('设置','jinsom');?></i>
</div>
</div> 
<?php }?> 



<div class="layui-form-item">
<label class="layui-form-label"><?php _e('安全问题','jinsom');?></label>
<div class="layui-input-inline">
<select name="question">
<?php  
$jinsom_safe_question_add = jinsom_get_option('jinsom_safe_question_add');
if($jinsom_safe_question_add){
foreach ($jinsom_safe_question_add as $question) {
if($user_info->question==$question['content']){
$select='selected = "selected"';
}else{
$select='';
}
echo '<option value="'.$question['content'].'" '.$select.'>'.$question['content'].'</option>';
}
}else{?>
<option value="你父亲叫什么名字？" <?php if($user_info->question=='你父亲叫什么名字？') echo 'selected = "selected"'; ?>>你父亲叫什么名字？</option>
<option value="你母亲叫什么名字？" <?php if($user_info->question=='你母亲叫什么名字？') echo 'selected = "selected"'; ?>>你母亲叫什么名字？</option>
<?php }?>
</select>
</div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('问题答案','jinsom');?></label>
<div class="layui-input-inline">
<input type="text"  name="answer" value="<?php echo $user_info->answer;?>"  lay-verify="required" placeholder="<?php _e('请输入你的密保答案','jinsom');?>" autocomplete="off" class="layui-input">
</div>
<div class="layui-form-mid layui-word-aux mail"><?php _e('忘记密码可通过密保问题找回','jinsom');?></div>
</div>

<?php if(jinsom_is_login_type('qq')){ ?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('QQ账号','jinsom');?></label>
<div class="layui-input-inline jinsom-binding-social">
<?php if($jinsom_qq_avatar){?>
<?php echo jinsom_avatar($author_id,'30','qq');?>
<span class="help-block"><m class="had"><?php _e('已绑定','jinsom');?></m><m class="down" onclick="jinsom_social_login_off('qq',<?php echo $author_id;?>,this)">(<?php _e('解绑','jinsom');?>)</m></span>
<?php }else{ ?>
<?php if(jinsom_is_admin($user_id)&&$author_id!=$user_id){?>
<a class="jinsom-binding-tips" ><?php _e('未绑定','jinsom');?></a>
<?php }else{?>
<a class="jinsom-binding-up" href="<?php echo jinsom_oauth_url('qq');?>" target="_blank"><?php _e('点击绑定QQ登录','jinsom');?></a>
<?php }?>
<?php } ?>
</div>
</div>
<?php } ?>

<?php if(jinsom_is_login_type('weibo')){ ?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('微博账号','jinsom');?></label>
<div class="layui-input-inline jinsom-binding-social">
<?php if($jinsom_weibo_avatar){?>
<?php echo jinsom_avatar($author_id,'30','weibo');?>
<span class="help-block"><m class="had"><?php _e('已绑定','jinsom');?></m><m class="down" onclick="jinsom_social_login_off('weibo',<?php echo $author_id;?>,this)">(<?php _e('解绑','jinsom');?>)</m></span>
<?php }else{ ?>
<?php if(jinsom_is_admin($user_id)&&$author_id!=$user_id){?>
<a class="jinsom-binding-tips" ><?php _e('未绑定','jinsom');?></a>
<?php }else{?>
<a class="jinsom-binding-up" href="<?php echo jinsom_oauth_url('weibo');?>" target="_blank"><?php _e('点击绑定微博登录','jinsom');?></a>
<?php } ?>
<?php } ?>
</div>
</div>
<?php } ?>


<?php if(jinsom_is_login_type('wechat_code')||jinsom_is_login_type('wechat_mp')){ ?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('微信账号','jinsom');?></label>
<div class="layui-input-inline jinsom-binding-social">
<?php if(get_user_meta($author_id,'wechat_avatar',true)){?>
<?php echo jinsom_avatar($author_id,'30','wechat');?>
<span class="help-block"><m class="had"><?php _e('已绑定','jinsom');?></m><m class="down" onclick="jinsom_social_login_off('wechat',<?php echo $author_id;?>,this)">(<?php _e('解绑','jinsom');?>)</m></span>
<?php }else{ ?>
<?php if(jinsom_is_admin($user_id)&&$author_id!=$user_id){?>
<a class="jinsom-binding-tips" ><?php _e('未绑定','jinsom');?></a>
<?php }else{?>
<a class="jinsom-binding-up" href="<?php echo jinsom_oauth_url('wechat_code');?>" target="_blank"><?php _e('点击绑定微信登录','jinsom');?></a>
<?php }?>
<?php } ?>
</div>
</div>
<?php } ?>

<?php if(jinsom_is_login_type('github')){ ?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('Github账号','jinsom');?></label>
<div class="layui-input-inline jinsom-binding-social">
<?php if($jinsom_github_avatar){?>
<?php echo jinsom_avatar($author_id,'30','github');?>
<span class="help-block"><m class="had"><?php _e('已绑定','jinsom');?></m><m class="down" onclick="jinsom_social_login_off('github',<?php echo $author_id;?>,this)">(<?php _e('解绑','jinsom');?>)</m></span>
<?php }else{ ?>
<?php if(jinsom_is_admin($user_id)&&$author_id!=$user_id){?>
<a class="jinsom-binding-tips" ><?php _e('未绑定','jinsom');?></a>
<?php }else{?>
<a class="jinsom-binding-up" href="<?php echo jinsom_oauth_url('github');?>" target="_blank"><?php _e('点击绑定Github登录','jinsom');?></a>
<?php } ?>
<?php } ?>
</div>
</div>
<?php } ?>

<?php if(jinsom_is_login_type('alipay')){ ?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('支付宝账号','jinsom');?></label>
<div class="layui-input-inline jinsom-binding-social">
<?php if($jinsom_alipay_avatar){?>
<?php echo jinsom_avatar($author_id,'30','alipay');?>
<span class="help-block"><m class="had"><?php _e('已绑定','jinsom');?></m><m class="down" onclick="jinsom_social_login_off('alipay',<?php echo $author_id;?>,this)">(<?php _e('解绑','jinsom');?>)</m></span>
<?php }else{ ?>
<?php if(jinsom_is_admin($user_id)&&$author_id!=$user_id){?>
<a class="jinsom-binding-tips" ><?php _e('未绑定','jinsom');?></a>
<?php }else{?>
<a class="jinsom-binding-up" href="<?php echo jinsom_oauth_url('alipay');?>" target="_blank"><?php _e('点击绑定支付宝登录','jinsom');?></a>
<?php } ?>
<?php } ?>
</div>
</div>
<?php } ?>

<input type="hidden" name="author_id" value="<?php echo $user_info->ID;?>">
<div class="jinsom-setting-btn opacity" onclick="jinsom_setting('account')"><?php _e('保存设置','jinsom');?></div>

</form>
</div><!-- 账户设置结束 -->

<div class="layui-tab-item">
<form id="jinsom-setting-privacy" class="layui-form layui-form-pane" >

<?php 
if(jinsom_get_option('jinsom_location_on_off')!='no'){//是否开启位置服务
if(jinsom_is_admin($user_id)||is_vip($author_id)){?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('城市位置','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" name="city" value="<?php echo $user_info->city;?>" class="layui-input">
</div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('自动定位','jinsom');?></label>
<div class="layui-input-inline" style="width: 80px;">
<?php if($user_info->city_lock=='lock'){//==不自动获取 ?>
<input type="checkbox" name="city-lock" lay-skin="switch" lay-text="开|关">
<?php }else{//其他情况都自动获取?>
<input type="checkbox" name="city-lock" lay-skin="switch" lay-text="开|关" checked="">
<?php }?>
</div>
</div>

<?php }else{?>
<div class="layui-form-item">
<label class="layui-form-label"><?php _e('城市位置','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" name="city" value="<?php echo $user_info->city;?>" class="layui-input" disabled="" style="cursor: not-allowed;">
</div>
<div class="layui-form-mid layui-word-aux"><a href="javascript:jinsom_recharge_vip_form()" class="aa"><?php _e('开通会员','jinsom');?></a><?php _e('可自定义设置个性位置','jinsom');?></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('自动定位','jinsom');?></label>
<div class="layui-input-inline" style="width: 80px;">
<div class="layui-unselect layui-form-switch layui-form-onswitch" lay-skin="_switch"><em>开</em><i></i></div>
</div>
<div class="layui-form-mid layui-word-aux"><a href="javascript:jinsom_recharge_vip_form()" class="aa"><?php _e('开通会员','jinsom');?></a><?php _e('可以关闭自动定位城市位置','jinsom');?></div>
</div>
<?php }?>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('发布位置','jinsom');?></label>
<div class="layui-input-inline" style="width: 80px;">
<?php if(!$user_info->publish_city){//不存在字段则开启 ?>
<input type="checkbox" name="publish_city" lay-skin="switch" lay-text="开|关" checked="">
<?php }else{//存在字段则关闭?>
<input type="checkbox" name="publish_city" lay-skin="switch" lay-text="开|关">
<?php }?>
</div>
<div class="layui-form-mid layui-word-aux"><?php _e('开启之后发表内容将会显示你的城市位置','jinsom');?></div>
</div>
<?php }//是否开启位置?>


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('聊天免扰','jinsom');?></label>
<div class="layui-input-inline" style="width: 80px;">
<?php if($user_info->im_privacy){//开启 ?>
<input type="checkbox" name="im-privacy" lay-skin="switch" lay-text="开|关" checked="">
<?php }else{//关闭?>
<input type="checkbox" name="im-privacy" lay-skin="switch" lay-text="开|关">
<?php }?>
</div>
<div class="layui-form-mid layui-word-aux"><?php _e('开启之后，只允许你关注的用户给你发送消息','jinsom');?></div>
</div>


<div class="layui-form-item">
<label class="layui-form-label"><?php _e('隐藏喜欢','jinsom');?></label>
<div class="layui-input-inline" style="width: 80px;">
<?php if($user_info->hide_like){//开启 ?>
<input type="checkbox" name="hide-like" lay-skin="switch" lay-text="开|关" checked="">
<?php }else{//关闭?>
<input type="checkbox" name="hide-like" lay-skin="switch" lay-text="开|关">
<?php }?>
</div>
<div class="layui-form-mid layui-word-aux"><?php _e('开启之后，其他用户无法查看你喜欢的内容','jinsom');?></div>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('隐藏购买','jinsom');?></label>
<div class="layui-input-inline" style="width: 80px;">
<?php if($user_info->hide_buy){//开启 ?>
<input type="checkbox" name="hide-buy" lay-skin="switch" lay-text="开|关" checked="">
<?php }else{//关闭?>
<input type="checkbox" name="hide-buy" lay-skin="switch" lay-text="开|关">
<?php }?>
</div>
<div class="layui-form-mid layui-word-aux"><?php _e('开启之后，其他用户无法查看你购买的内容','jinsom');?></div>
</div>


<input type="hidden" name="author_id" value="<?php echo $user_info->ID;?>">
<input type="hidden" name="privacy" value="1">
<div class="jinsom-setting-btn opacity" onclick="jinsom_setting('privacy')"><?php _e('保存设置','jinsom');?></div>
</form>
</div>



<?php 
//其他资料
if($jinsom_member_profile_setting_add){
if($jinsom_member_profile_setting_add){
echo '<div class="layui-tab-item"><form id="jinsom-setting-social" class="layui-form layui-form-pane" >';
foreach ($jinsom_member_profile_setting_add as $data) {
echo '<div class="layui-form-item">
<label class="layui-form-label">'.$data['name'].'</label>
<div class="layui-input-inline">
<input type="text" name="'.$data['value'].'" value="'.get_user_meta($author_id,$data['value'],true).'" placeholder="请输入'.$data['name'].'" class="layui-input">
</div>
</div>';

}
echo '
<input type="hidden" name="other-profile" value="1">
<input type="hidden" name="author_id" value="'.$user_info->ID.'">
<div class="jinsom-setting-btn opacity" onclick=\'jinsom_setting("social")\'>'.__('保存设置','jinsom').'</div>
</form></div>';
}
}
?>



<!-- 背景音乐设置 -->
<div class="layui-tab-item">
<div class="layui-form layui-form-pane" >

<div class="layui-form-item jinsom-profile-bg-music">

<div class="layui-inline"> 
<label class="layui-form-label"><?php _e('音乐地址','jinsom');?></label>
<div class="layui-input-inline">
<input type="text" id="jinsom-bg-music-url" value="<?php  echo $user_info->bg_music_url;?>" placeholder="<?php _e('输入外链https://或本地上传','jinsom');?>" class="layui-input">
</div>
</div>

<?php if(!is_vip($author_id)&&!jinsom_is_admin($user_id)){?>
<div class="layui-inline">
<span onclick="jinsom_recharge_vip_form()"><m><?php _e('VIP特权','jinsom');?></m><?php _e('本地上传','jinsom');?></span>
</div>
<?php }else{?>
<div class="layui-inline">
<span><?php _e('本地上传','jinsom');?>
<form id="jinsom-upload-user-bg-music-form" method="post" enctype="multipart/form-data" action="<?php echo get_template_directory_uri();?>/module/upload/music-bg.php">
<input id="jinsom-upload-user-bg-music" type="file" name="file" title="<?php _e('点击上传音乐','jinsom');?>">
<input type="hidden" name="author_id" value="<?php echo $author_id;?>">
</form>
</span>
</div>
<?php }?>

</div>

<div class="jinsom-bg-music-progress">
<span class="jinsom-bg-music-bar"></span>
<span class="jinsom-bg-music-percent">0%</span>
</div>

<div class="layui-form-item">
<label class="layui-form-label"><?php _e('自动播放','jinsom');?></label>
<div class="layui-input-block">
<input type="checkbox" <?php if($user_info->bg_music_on_off){echo 'checked=""';}?> lay-skin="switch"  title="开关" id="jinsom-bg-music-on-off" lay-text="开启|关闭">
</div>
</div>

<p class="jinsom-profile-bg-music-tips"><?php _e('上传完音乐之后请记住要保存一下','jinsom');?></p>
<div class="jinsom-setting-btn opacity" onclick="jinsom_update_profile_bg_music()"><?php _e('保存设置','jinsom');?></div>

</div>
</div><!-- 背景音乐设置 -->

</div>
</div>

</div>

<script type="text/javascript">
layui.use(['laydate','form','element'], function(){
var form = layui.form;
var element = layui.element;
var laydate = layui.laydate;
laydate.render({elem: '#jinsom-setting-birthday',type: 'date'});
laydate.render({elem: '#jinsom-setting-vip-time',type: 'date'});
laydate.render({elem: '#jinsom-setting-blacklist',type: 'date'});


//用户组
form.on('select(user_power)', function(data){
$select_value=parseInt($("#jinsom-profile-user-power").val());
if($select_value==4){
$('#jinsom-profile-user-power-danger-reason').show(); 
}else{
$('#jinsom-profile-user-power-danger-reason').hide(); 
}
});

});
</script>
