<?php 
//黑名单保释
require( '../../../../../wp-load.php' );
$bail_user_id=$_POST['user_id'];
?>

<div class="jinsom-blacklist-bail-content">
<div class="tips"><?php _e('你确定要花费','jinsom');?> <font style="color:#f00;"><?php echo jinsom_get_option('jinsom_blacklist_bail_number');?><?php echo jinsom_get_option('jinsom_credit_name');?>/<?php _e('天','jinsom');?></font> <?php _e('保释他吗？','jinsom');?></div>
<div class="btn opacity" onclick="jinsom_blacklist_bail(<?php echo $bail_user_id;?>)"><?php _e('确定保释','jinsom');?></div>
</div>