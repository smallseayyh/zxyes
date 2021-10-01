<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_palette' ) ) {
class LightSNS_Field_palette extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$palette = ( ! empty( $this->field['options'] ) ) ? $this->field['options'] : array();

echo $this->field_before();

if( ! empty( $palette ) ) {

echo '<div class="jinsom-panel-siblings jinsom-panel--palettes">';

foreach ( $palette as $key => $colors ) {

$active  = ( $key === $this->value ) ? ' jinsom-panel--active' : '';
$checked = ( $key === $this->value ) ? ' checked' : '';

echo '<div class="jinsom-panel--sibling jinsom-panel--palette'. $active .'">';

if( ! empty( $colors ) ) {

foreach( $colors as $color ) {

echo '<span style="background-color: '. $color .';"></span>';

}

}

echo '<input type="radio" name="'. $this->field_name() .'" value="'. $key .'"'. $this->field_attributes() . $checked .'/>';
echo '</div>';

}

echo '</div>';

}

echo '<div class="clear"></div>';

echo $this->field_after();

}

}
}
