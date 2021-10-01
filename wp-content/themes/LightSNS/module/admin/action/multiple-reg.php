<?php
//批量注册
require( '../../../../../../wp-load.php' );

//判断是否登录
if (!is_user_logged_in()){ 
$data_arr['code']=0;
$data_arr['msg']=jinsom_no_login_tips();
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

//判断是否管理员
if (!current_user_can('level_10')){ 
$data_arr['code']=0;
$data_arr['msg']='你没有权限！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();	
}

if(isset($_POST['username'])){
$username=$_POST['username'];
$password=$_POST['password'];
$city=$_POST['city'];
$sex=$_POST['sex'];
if(empty($password)){$password='12345678';}

if(empty($username)){
$data_arr['code']=0;
$data_arr['msg']='请输入用户昵称！';
header('content-type:application/json');
echo json_encode($data_arr);
exit();		
}

$username_arr=explode(",",$username);
$content='';
$user_data='';
foreach ($username_arr as $data){
if($data!=''){
if(jinsom_nickname_exists($data)){//已经存在
$content.='<li class="clear"><span>'.$data.'</span><span style="color:#f00">注册失败！昵称已存在</span></li>';
}else{
$rand_name='multiple_'.rand(100000000,999999999);//随机生成用户名
$user_id = wp_create_user($rand_name,$password,0);
if($user_id){

update_user_meta($user_id,'avatar_type','default');
update_user_meta($user_id,'nickname',$data);

if($city=='on'){
$city_data='石家庄,唐山,秦皇岛,邯郸,邢台,保定,张家口,承德,沧州,廊坊,太原,大同,阳泉,长治,晋城,朔州,忻州,吕梁,呼和浩特,包头,乌海,赤峰,呼伦贝尔,通辽,乌兰察布,鄂尔多斯,巴彦淖尔,晋中,临汾,运城,沈阳,大连,鞍山,抚顺,本溪,丹东,锦州,营口,阜新,辽阳,盘锦,铁岭,长春,吉林,四平,辽源,通化,白山,哈尔滨,齐齐哈尔,牡丹江,佳木斯,大庆,伊春,鸡西,鹤岗,双鸭山,七台河,绥化南京,无锡,徐州,常州,苏州,南通,连云港,淮安,盐城,杭州,宁波,温州,绍兴,湖州,嘉兴,金华,衢州,台州,合肥,芜湖,蚌埠,淮南,马鞍山,淮北,铜陵,安庆,黄山,阜阳,宿州,滁州,六安,宣城,福州,莆田,泉州,厦门,漳州,龙岩,三明,南平南昌,赣州,宜春,吉安,上饶,抚州,九江,景德镇,济南,青岛,淄博,枣庄,东营,烟台,潍坊,济宁,泰安,威海,日照,滨州,德州,聊城,临沂,郑州,开封,洛阳,平顶山,安阳,鹤壁,新乡,焦作,濮阳,许昌,漯河,三门峡,商丘,周口,驻马店武汉,黄石,十堰,荆州,宜昌,襄阳,鄂州,荆门,黄冈,孝感,咸宁长沙,株洲,湘潭,衡阳,邵阳,岳阳,张家界,益阳,常德,娄底,郴州,永州,广州,深圳,珠海,汕头,佛山,韶关,湛江,肇庆,江门,茂名,惠州,梅州,汕尾,河源,阳江,清远,东莞,中山,潮州南宁,柳州,桂林,梧州,北海,崇左,来宾,贺州,玉林,百色,河池,钦州,海口,三亚,成都,绵阳,自贡,攀枝花,泸州,德阳,广元,遂宁,内江,乐山,资阳,宜宾,南充,达州,雅安,贵阳,六盘水,遵义,铜仁,昆明,昭通,曲靖,玉溪,拉萨,日喀则,昌都,西安,铜川,宝鸡,咸阳,兰州,嘉峪关,金昌,白银,天水,天津,上海,酒泉,张掖,重庆,北京,西宁,海东,武威,定西,银川,石嘴山,乌鲁木齐,克拉玛依,吐鲁番,哈密,吴忠,固原,中卫,陇南,平凉,庆阳,渭南,汉中,安康,商洛,延安,榆林,林芝,山南,那曲,普洱,保山,丽江,临沧,毕节,安顺,广安,巴中,眉山,三沙,儋州,防城港,贵港,揭阳,云浮,怀化,随州,南阳,信阳,菏泽,莱芜,萍乡,新余,鹰潭,宁德,池州,亳州,丽水,舟山,扬州,镇江,泰州,宿迁,黑河,白城,松原,朝阳,葫芦岛,衡水';
$city_arr=explode(",",$city_data);
$rand_city=rand(0,count($city_arr));
update_user_meta($user_id,'city',$city_arr[$rand_city]);//更新城市
}

if($sex=='on'){
$rand_sex=rand(0,1);
$sex_arr=array('男生','女生');
update_user_meta($user_id,'gender',$sex_arr[$rand_sex]);
}

$user_data.=$user_id.',';
$content.='<li class="clear"><span>'.$data.'</span><span style="color:#5FB878">注册成功！</span></li>';
}else{
$content.='<li class="clear"><span>'.$data.'</span><span style="color:#f00">注册失败！</span></li>';
}
}

}
}

$user_data=substr($user_data,0,strlen($user_data)-1);//移除最后一个字符
$data_arr['code']=1;
$data_arr['content']=$content;
$data_arr['user_data']=$user_data;
$data_arr['msg']='批量注册成功！';

}else{
$data_arr['code']=0;
$data_arr['msg']='参数错误！';	
}
header('content-type:application/json');
echo json_encode($data_arr);