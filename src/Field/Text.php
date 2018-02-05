<?php
/**
 * The text field.
 *
 * @package Meta Box
 */

namespace MetaBox\Field;

/**
 * Text field class.
 */
class Text extends Input {
	/**
	 * Normalize parameters for field.
	 *
	 * @param array $field Field parameters.
	 *
	 * @return array
	 */
	public static function normalize( $field ) {
		$field = parent::normalize( $field );

		$field = wp_parse_args( $field, [
			'size'      => 30,
			'maxlength' => false,
			'pattern'   => false,
		] );

		return $field;
	}

	/**
	 * Get the attributes for a field.
	 *
	 * @param array $field Field parameters.
	 * @param mixed $value Meta value.
	 *
	 * @return array
	 */
	public static function get_attributes( $field, $value = null ) {
		$attributes = parent::get_attributes( $field, $value );
		$attributes = wp_parse_args( $attributes, [
			'size'        => $field['size'],
			'maxlength'   => $field['maxlength'],
			'pattern'     => $field['pattern'],
			'placeholder' => $field['placeholder'],
		] );

		return $attributes;
	}
}
