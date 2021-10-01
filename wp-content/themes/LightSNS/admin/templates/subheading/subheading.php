<?php if ( ! defined( 'ABSPATH' ) ) { die; } 
if( ! class_exists( 'LightSNS_Field_subheading' ) ) {
class LightSNS_Field_subheading extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

echo ( ! empty( $this->field['content'] ) ) ? $this->field['content'] : '';

}

}
}
