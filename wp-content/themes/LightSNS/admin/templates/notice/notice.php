<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_notice' ) ) {
class LightSNS_Field_notice extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';

echo ( ! empty( $this->field['content'] ) ) ? '<div class="jinsom-panel-notice jinsom-panel-notice-'. $style .'">'. $this->field['content'] .'</div>' : '';

}

}
}
