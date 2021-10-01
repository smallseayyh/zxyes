<div class="jinsom-member-change-bg-form">
<div class="jinsom-member-change-bg-header">
<div class="jinsom-member-change-bg-head">
<span class="close"><i class="jinsom-icon jinsom-guanbi"></i></span>
</div>
</div>
<div class="jinsom-member-change-bg-content">
<?php 
$jinsom_member_bg_add=jinsom_get_option('jinsom_member_bg_add');
if($jinsom_member_bg_add){
$i=0;
foreach ($jinsom_member_bg_add as $data){
if($data['vip']){$vip='<m>'.__('VIP专属','jinsom').'</m>';}else{$vip='';}
if(get_user_meta($author_id,'skin',true)==$i){
$on='class="on"';
}else{
$on='';
}
echo '
<li '.$on.' bg="'.$data['big_img'].'" number="'.$i.'">
'.$vip.'
<img src="'.$data['cover'].'" alt="'.$data['name'].'">
<span>'.$data['name'].'</span>
</li>';
$i++;
}
}else{
echo jinsom_empty('请后台-基础设置-个人主页 进行添加背景图');
}
?>
</div>	
</div>