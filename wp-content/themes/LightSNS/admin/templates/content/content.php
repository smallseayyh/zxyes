<?php if ( ! defined( 'ABSPATH' ) ) { die; } 
if( ! class_exists( 'LightSNS_Field_content' ) ) {
class LightSNS_Field_content extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {
echo $this->field['content'];
}

}
}
