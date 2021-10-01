<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if(! class_exists('LightSNS_Field_verify')){
class LightSNS_Field_verify extends LightSNS_Fields {
public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}
public function render(){
?>
<div class="jinsom-panel-update-info">
<p>你目前使用的是版本：<?php echo wp_get_theme()->Version;?> 【<span id="jinsom-get-update-info">检测更新</span>】</p>
<p><font style="color:#f00;font-weight:bold;">1、任何没有经过官方授权将LightSNS用于商业用途(含网站有广告)，将追究其法律责任。</font></p>
<p><font style="color:#f00;font-weight:bold;">2、任何将LightSNS上传到互联网平台、线上或线下传播、转让、倒卖等行为将追究其法律责任。</font></p>
<p><font style="color:#f00;font-weight:bold;">3、任何其他非官方渠道获取到的LightSNS都属于盗版。</font></p>
<p><font style="color:#f00;font-weight:bold;">4、禁止使用LightSNS用于任何非法违法，一经发现将取消授权，请做一位尊法守法的良好公民。</font></p>
<p>作者QQ：1194636035</p>
<p>LightSNS用户群：539335490 (需要捐助才能加入)</p>
<p>LightSNS官网：<a href="https://q.jinsom.cn/" target="_blank" style="color: #00689a;text-decoration: underline;">https://q.jinsom.cn</a></p>
<!-- 版权为林金胜所有，未经授权，不得实施复制传播倒卖等任何侵权行为 -->
</div>
<?php 
}
}
}