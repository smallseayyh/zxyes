<?php
//添加地址
require( '../../../../../wp-load.php' );
?>

<div class="jinsom-address-select-content">
<div class="header clear">
<div class="select">当前选择：<span></span></div>
<div class="back opacity"><i class="jinsom-icon jinsom-fanhui"></i>返回上一级</div>
</div>
<div class="content clear">


</div>
</div>
<script type="text/javascript" src="<?php echo get_template_directory_uri();?>/assets/js/address.js"></script>
<script type="text/javascript">
//开始渲染省份
html_g='';
for (var g = 0; g < city.length; g++) {
html_g+='<li class="one" title="'+city[g].name+'">'+city[g].name+'</li>';
}
$('.jinsom-address-select-content .content').html(html_g);



//点击选择
$('.jinsom-address-select-content').on('click','li',function(){

if($(this).hasClass('one')){//第一层点击
$(this).parent().siblings('.header').find('span').html('<m class="a">'+$(this).text()+'</m>');//显示已选
$(this).parent().siblings('.header').show();//显示返回按钮

html_a='';
index=$(this).index();//获取当前点击的位置
$(this).parent().attr('a',index);//记录当前点击的位置，传给第二层使用
$(this).parent().attr('index',2);//记录当前层数

if(!city[index].city[0].name){//如果是海外
// alert('这是最后一层啦！');
$(this).parent().html('<div class="finish"><p>请输入你的详细地址</p><textarea></textarea><div class="other"><div class="name"><p>收件人名字</p><input type="text"></div><div class="phone"><p>收件人手机号</p><input type="text"></div></div><div class="btn opacity" onclick="jinsom_address_add()">确定添加</div></div>');
$('.jinsom-address-select-content textarea').focus()
return false;	
}

if(city[index].city.length>1){

for (var i = 0; i < city[index].city.length; i++) {
html_a+='<li class="two" title="'+city[index].city[i].name+'">'+city[index].city[i].name+'</li>';
}

}else{//直辖市+台湾

for (var x = 0; x < city[index].city[0].area.length; x++) {
html_a+='<li class="three" title="'+city[index].city[0].area[x]+'">'+city[index].city[0].area[x]+'</li>';
}

}




$(this).parent().html(html_a);//渲染



}else if($(this).hasClass('two')){//第二层点击
$(this).parent().siblings('.header').find('span').append('<m class="b">'+$(this).text()+'</m>');//显示已选
html_b='';
index=$(this).parent().attr('a');//获取第一层点击的位置
current_index=$(this).index();//获取第二层点击的位置
$(this).parent().attr('b',current_index);//记录第二层点击位置，传给第三层使用
$(this).parent().attr('index',3);//记录当前层数

for (var j = 0; j < city[index].city[current_index].area.length; j++) {
html_b+='<li class="three" title="'+city[index].city[current_index].area[j]+'">'+city[index].city[current_index].area[j]+'</li>';
}

$(this).parent().html(html_b);//渲染


}else if($(this).hasClass('three')){
// alert('这是最后一层啦！');

if($(this).parent().attr('index')==2){//直辖市
$(this).parent().siblings('.header').find('span').append('<m class="b">'+$(this).text()+'</m>');//显示已选
$(this).parent().attr('index',3);
$(this).parent().attr('b',$(this).index());//记录第三层点击位置
}else{
$(this).parent().siblings('.header').find('span').append('<m class="c">'+$(this).text()+'</m>');//显示已选
$(this).parent().attr('index',4);
$(this).parent().attr('c',$(this).index());//记录第四层点击位置
}

$(this).parent().html('<div class="finish"><p>详细地址；如街道、门牌号、乡镇、村、等</p><textarea></textarea><div class="other"><div class="name"><p>收件人名字</p><input type="text"></div><div class="phone"><p>收件人手机号</p><input type="text"></div></div><div class="btn opacity" onclick="jinsom_address_add()">确定添加</div></div>');


$('.jinsom-address-select-content textarea').focus()

}



});



//返回
$('.jinsom-address-select-content .back').click(function(){
content_dom=$(this).parent().siblings('.content');

if(content_dom.attr('index')==4){//当前第四层，返回第三层
$(this).prev().find('.c').remove();//移除已选的
html_e='';
index=content_dom.attr('b');//获取上一层的点击位置
for (var j = 0; j < city[content_dom.attr('a')].city[index].area.length; j++) {
html_e+='<li class="three" title="'+city[content_dom.attr('a')].city[index].area[j]+'">'+city[content_dom.attr('a')].city[index].area[j]+'</li>';
}
content_dom.attr('index',3);//记录当前的层数
content_dom.html(html_e);//渲染
}else if(content_dom.attr('index')==3){//当前第三层，返回第二层
$(this).prev().find('.b').remove();//移除已选的
html_c='';
index=content_dom.attr('a');//获取上一层的点击位置


if(city[index].city.length>1){
for (var k = 0; k < city[index].city.length; k++) {
html_c+='<li class="two" title="'+city[index].city[k].name+'">'+city[index].city[k].name+'</li>';
}
}else{//直辖市+台湾

for (var k = 0; k < city[index].city[0].area.length; k++) {
html_c+='<li class="three" title="'+city[index].city[0].area[k]+'">'+city[index].city[0].area[k]+'</li>';
}

}



content_dom.attr('index',2);//记录当前的层数
content_dom.html(html_c);//渲染
}else if(content_dom.attr('index')==2){//当前第二层，返回第一层
html_d='';

for (var l = 0; l < city.length; l++) {
html_d+='<li class="one" title="'+city[l].name+'">'+city[l].name+'</li>';
}
content_dom.removeAttr('index');//移除层级记录
content_dom.removeAttr('a');//移除记录
content_dom.removeAttr('b');//移除记录
content_dom.html(html_d);//渲染
$(this).parent().hide();//隐藏返回按钮
}

});


//添加地址
function jinsom_address_add(){
address=$('.jinsom-address-select-content textarea').val();
name=$('.jinsom-address-select-content .other>.name input').val();
phone=$('.jinsom-address-select-content .other>.phone input').val();
city_had=$('.jinsom-address-select-content .header .select>span').text();

layer.load(1);
$.ajax({
type: "POST",
url:jinsom.jinsom_ajax_url+"/action/address-add.php",
data:{city:city_had,address:address,name:name,phone:phone,type:'add'},
success: function(msg){
layer.closeAll('loading');
layer.msg(msg.msg);

if(msg.code==1){

address_number=$('.jinsom-goods-order-confirmation-content .address li').length;//之前地址数量
address=$('.jinsom-address-select-content .header .select span').text()+address;
address='<li><m onclick="jinsom_address_del(this)">删除</m><div class="info"><input type="radio" name="address" value="'+address_number+'"><span>'+address+'</span><p><span>收件人<n>'+name+'</n></span><span>手机号<n>'+phone+'</n></span></p></div></li>';
if(address_number>0){
$('.jinsom-goods-order-confirmation-content .address .list').append(address);
}else{
$('.jinsom-goods-order-confirmation-content .address .list').html(address);
}
layer.close(address_select_form);


}

}
});





}
</script>