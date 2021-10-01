<?php if ( ! defined( 'ABSPATH' ) ) { die; }
if( ! class_exists( 'LightSNS_Field_button_set' ) ) {
class LightSNS_Field_button_set extends LightSNS_Fields {

public function __construct( $field, $value = '', $unique = '', $where = '', $parent = '' ) {
parent::__construct( $field, $value, $unique, $where, $parent );
}

public function render() {

$args = wp_parse_args( $this->field, array(
'multiple' => false,
'options'  => array(),
) );

$value = ( is_array( $this->value ) ) ? $this->value : array_filter( (array) $this->value );

echo $this->field_before();

if( ! empty( $args['options'] ) ) {

echo '<div class="jinsom-panel-siblings jinsom-panel--button-group" data-multiple="'. $args['multiple'] .'">';

foreach( $args['options'] as $key => $option ) {

$type    = ( $args['multiple'] ) ? 'checkbox' : 'radio';
$extra   = ( $args['multiple'] ) ? '[]' : '';
$active  = ( in_array( $key, $value ) ) ? ' jinsom-panel--active' : '';
$checked = ( in_array( $key, $value ) ) ? ' checked' : '';

echo '<div class="jinsom-panel--sibling jinsom-panel--button'. $active .'">';
echo '<input type="'. $type .'" name="'. $this->field_name( $extra ) .'" value="'. $key .'"'. $this->field_attributes() . $checked .'/>';
echo $option;
echo '</div>';

}

echo '</div>';

}

echo '<div class="clear"></div>';

echo $this->field_after();

}

}
}
