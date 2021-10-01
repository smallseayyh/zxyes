<?php if ( ! defined( 'ABSPATH' ) ) { die; } 
if( ! class_exists( 'LightSNS_Field_submessage' ) ) {
class LightSNS_Field_submessage extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$style = ( ! empty( $this->field['style'] ) ) ? $this->field['style'] : 'normal';

echo '<div class="jinsom-panel-submessage jinsom-panel-submessage-'. $style .'">'. $this->field['content'] .'</div>';

}

}
}
