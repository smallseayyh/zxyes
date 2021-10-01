<?php 
require( '../../../../../../../wp-load.php');
$user_id=$current_user->ID;
if(jinsom_is_admin($user_id)){
$author_id=$_GET['author_id'];	
}else{
$author_id=$user_id;
}
$user_info = get_userdata($author_id);

?>
<div data-page="get-password" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding">找回密码</div>
<div class="right">
<a href="#" class="link icon-only" onclick="jinsom_update_question(<?php echo $author_id;?>)">下一步</a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content update-phone">

<div class="jinsom-setting-update-phone-email-input question">

<p>
<select id="jinsom-mobile-update-question">
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
</p>

<p class="code"><input type="text" placeholder="请输入安全问题的答案" id="jinsom-mobile-update-answer" value="<?php echo $user_info->answer;?>"></p>


</div>

</div>       

