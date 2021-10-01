<?php 
require( '../../../../../../../wp-load.php');
$theme_url=get_template_directory_uri();
$user_id=$current_user->ID;
?>
<div data-page="setting-language" class="page no-tabbar">

<div class="navbar">
<div class="navbar-inner">
<div class="left">
<a href="#" class="back link icon-only">
<i class="jinsom-icon jinsom-fanhui2"></i>
</a>
</div>
<div class="center sliding"><?php _e('语言设置','jinsom');?></div>
<div class="right">
<a href="#" class="link icon-only"></a>
</div>
</div>
</div>

<div class="page-content jinsom-setting-content">


<div class="jinsom-setting-box language">

<?php 
$jinsom_languages_add=jinsom_get_option('jinsom_languages_add');
if($jinsom_languages_add){
foreach ($jinsom_languages_add as $data) {
if(get_locale()==$data['code']){$on='class="on"';}else{$on='';}
//echo '<li '.$on.' onclick=\'jinsom_change_language(this,"'.$data['code'].'")\'>'.$data['name'].'</li>';

echo '
<li '.$on.' onclick=\'jinsom_change_language(this,"'.$data['code'].'")\'>
<a href="#" class="link">
<span class="title">'.$data['name'].'</span>
<span class="a"><i class="jinsom-icon jinsom-yiguanzhu"></i></span>	
</a>
</li>
';

}
}?>


</div>

</div>       

