<?php
//简单注册表单
require( '../../../../../../wp-load.php' );
$jinsom_reg_doc_url=jinsom_get_option('jinsom_reg_doc_url');
$jinsom_machine_verify_on_off=jinsom_get_option('jinsom_machine_verify_on_off');
$jinsom_machine_verify_use_for=jinsom_get_option('jinsom_machine_verify_use_for');
?>
<div class="jinsom-pop-login-form">
<li class="username"><input id="jinsom-pop-username" type="text" placeholder="<?php _e('账号','jinsom');?>"></li>
<li class="pass"><input id="jinsom-pop-password" type="text" placeholder="<?php _e('设置你的密码','jinsom');?>"></input></li>
<div class="jinsom-reg-doc">
<input type="checkbox" id="jinsom-reg-doc">
<span><?php _e('同意','jinsom');?>
<?php
$jinsom_reg_doc_add=jinsom_get_option('jinsom_reg_doc_add');
if($jinsom_reg_doc_add){
foreach ($jinsom_reg_doc_add as $data) {
echo '<a href="'.$data['url'].'" target="_blank">《'.$data['name'].'》</a>';
}
}
?>
</span>
</div>
<div class="jinsom-login-btn">

<?php if($jinsom_machine_verify_on_off&&in_array("reg",$jinsom_machine_verify_use_for)){?>
<span class="reg opacity" id="reg-1"><?php _e('注册','jinsom');?></span>
<script type="text/javascript">
new TencentCaptcha(document.getElementById('reg-1'),'<?php echo jinsom_get_option('jinsom_machine_verify_appid');?>',function(res){
if(res.ret === 0){jinsom_pop_reg_simple(res.ticket,res.randstr);}
});
</script>
<?php }else{?>
<span class="reg opacity" onclick="jinsom_pop_reg_simple('','');"><?php _e('注册','jinsom');?></span>
<?php }?>

<span class="login opacity" onclick="jinsom_pop_login_style();"><?php _e('登录','jinsom');?></span>
</div>
</div>
