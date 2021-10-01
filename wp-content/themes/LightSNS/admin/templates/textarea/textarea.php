<?php if ( ! defined( 'ABSPATH' ) ) { die; } 
if( ! class_exists( 'LightSNS_Field_textarea' ) ) {
class LightSNS_Field_textarea extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

echo $this->field_before();
echo $this->shortcoder();
echo '<textarea name="'. $this->field_name() .'"'. $this->field_attributes() .'>'. htmlspecialchars($this->value) .'</textarea>';
echo $this->field_after();

}

public function shortcoder() {

if( ! empty( $this->field['shortcoder'] ) ) {

$shortcoders = ( is_array( $this->field['shortcoder'] ) ) ? $this->field['shortcoder'] : array_filter( (array) $this->field['shortcoder'] );

foreach( $shortcoders as $shortcode_id ) {

if( isset( LightSNS::$args['shortcoders'][$shortcode_id] ) ) {

$setup_args   = LightSNS::$args['shortcoders'][$shortcode_id];
$button_title = ( ! empty( $setup_args['button_title'] ) ) ? $setup_args['button_title'] : '添加短代码';

echo '<a href="#" class="button button-primary jinsom-panel-shortcode-button" data-modal-id="'. $shortcode_id .'">'. $button_title .'</a>';

}

}

}

}
}
}
