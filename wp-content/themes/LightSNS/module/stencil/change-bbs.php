<?php
//转移板块
require( '../../../../../wp-load.php' );
$post_id=(int)$_POST['post_id'];
$bbs_id=(int)$_POST['bbs_id'];
$bbs_name=jinsom_get_option('jinsom_bbs_name');//论坛名称
$bbs_arr=wp_get_post_categories($post_id);
$html = wp_dropdown_categories( array( 'echo' => 0, 'hierarchical' => 1,'exclude'=>1,'hide_empty'=>false,'value_field' => 'term_id,name', 'class'=>'jinsom-change-bbs') ); // Your Query here
$html = str_replace( "select", "div", $html );
$html = str_replace( "<option class=", "<li><input type='checkbox' class=", $html );
$html = str_replace('">', '"><span>', $html );
$html = str_replace( "</option>", "</span></li>", $html );
$html = str_replace( "&nbsp;", "", $html );

foreach ($bbs_arr as $id) {
$html = str_replace('value="'.$id.'"','checked="checked" value="'.$id.'"', $html );
}

?>
<div class="jinsom-change-bbs-content">
<?php echo $html;?>
<div class="btn opacity" onclick="jinsom_change_bbs(<?php echo $post_id;?>,<?php echo $bbs_id;?>)">确定转移</div>
<div class="tips">如果该<?php echo $bbs_name;?>有子版块，一定要勾选对应的父级<?php echo $bbs_name;?>。【同级<?php echo $bbs_name;?>不能勾选多个】</div>
</div>

<script type="text/javascript">
$(".jinsom-change-bbs-content li span").click(function(){
if($(this).prev().is(':checked')){
$(this).prev().removeAttr("checked");
}else{
$(this).prev().prop("checked",true);
}
});
</script>