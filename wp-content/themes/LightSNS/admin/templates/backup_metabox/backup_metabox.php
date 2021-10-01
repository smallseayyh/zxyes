<?php if(!defined('ABSPATH')){die;}
if(!class_exists('LightSNS_Field_backup_metabox')){
class LightSNS_Field_backup_metabox extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render(){
echo $this->field_before();
echo '
<div class="jinsom-panel-field jinsom-panel-field-backup">
<textarea id="jinsom-admin-backup-metabox-val" class="jinsom-panel-import-data" placeholder="把你备份的json设置数据复制在这里，然后点击导入按钮"></textarea>
<span class="button button-primary" onclick="jinsom_amdin_backup_metabox_import()">导入数据</span>
<a href="javascript:" class="button button-primary" onclick="jinsom_amdin_backup_metabox()" style="float:right;">下载数据</a>
</div>';
echo $this->field_after();
}

}
}
