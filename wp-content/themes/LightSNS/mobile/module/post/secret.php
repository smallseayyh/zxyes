<?php 
require( '../../../../../../wp-load.php' );
$require_url=get_template_directory();
$user_id=$current_user->ID;
$page=(int)$_POST['page'];
if(!$page){$page=1;}
$type=strip_tags($_POST['type']);
$load_type=strip_tags($_POST['load_type']);//加载类型
if($type==''){$type='new';}
$number=10;
$offset=($page-1)*$number;


$args = array(
'post_type' =>'secret',
'post_status'=>'publish',
);	
$args['no_found_rows']=true;
$args['showposts']=$number;
$args['offset']=$offset;

if($type=='hot'){//热门
$args['meta_key']='nice_num';
$args['orderby']='meta_value_num';	
}

if($type=='rand'){//穿越
$args['orderby']='rand';	
}
if(isset($_POST['author_id'])){
$args['author']=$user_id;	
}

query_posts($args);
if(have_posts()){
while(have_posts()):the_post();
require(get_template_directory().'/post/secret.php');	
endwhile;
}else{
if($load_type=='more'){
echo 0;
}else{
echo jinsom_empty();
}	
}
