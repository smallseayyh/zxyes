<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_switcher' ) ) {
class LightSNS_Field_switcher extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$active     = ( ! empty( $this->value ) ) ? ' jinsom-panel--active' : '';
$text_on    = ( ! empty( $this->field['text_on'] ) ) ? $this->field['text_on'] : '开';
$text_off   = ( ! empty( $this->field['text_off'] ) ) ? $this->field['text_off'] : '关';
$text_width = ( ! empty( $this->field['text_width'] ) ) ? ' style="width: '. $this->field['text_width'] .'px;"': '';

echo $this->field_before();

echo '<div class="jinsom-panel--switcher'. $active .'"'. $text_width .'>';
echo '<span class="jinsom-panel--on">'. $text_on .'</span>';
echo '<span class="jinsom-panel--off">'. $text_off .'</span>';
echo '<span class="jinsom-panel--ball"></span>';
echo '<input type="text" name="'. $this->field_name() .'" value="'. $this->value .'"'. $this->field_attributes() .' />';
echo '</div>';

echo ( ! empty( $this->field['label'] ) ) ? '<span class="jinsom-panel--label">'. $this->field['label'] . '</span>' : '';

echo '<div class="clear"></div>';

echo $this->field_after();

}

}
}
