<?php

/**
 * Select Tree Walker for cascading select fields.
 *
 * @uses IWJMB_Walker_Select
 */
class IWJMB_Walker_Select_Tree {

	/**
	 * Field data.
	 *
	 * @var string
	 */
	public $field;

	/**
	 * Field meta value.
	 *
	 * @var array
	 */
	public $meta = array();

	function __construct( $db_fields, $field, $meta ) {
		$this->db_fields = wp_parse_args( (array) $db_fields, array(
			'parent' => '',
			'id'     => '',
			'label'  => '',
		) );
		$this->field     = $field;
		$this->meta      = (array) $meta;
	}

	function walk( $options ) {
		$parent   = $this->db_fields['parent'];
		$children = array();

		foreach ( $options as $option ) {
			$children[ $option->$parent ][] = $option;
		}
		$top_level = isset( $children[0] ) ? 0 : $options[0]->$parent;
		return $this->display_level( $children, $top_level, true );
	}

	function display_level( $options, $parent_id = 0, $active = false ) {
		$id         = $this->db_fields['id'];
		$field      = $this->field;
		$walker     = new IWJMB_Walker_Select( $this->db_fields, $field, $this->meta );
        $attributes = IWJMB_Field::call( 'get_attributes', $field, $this->meta );
        if($parent_id){
            $attributes['required'] = '';
        }

		$children = $options[ $parent_id ];
		$output   = sprintf(
			'<div class="iwjmb-select-tree %s" data-parent-id="%s"><select %s>',
			$active ? '' : 'hidden',
			$parent_id,
			IWJMB_Field::render_attributes( $attributes )
		);
		$output .= isset( $field['placeholder'] ) ? "<option value=''>{$field['placeholder']}</option>" : '<option></option>';
		$output .= $walker->walk( $children, - 1 );
		$output .= '</select>';

		foreach ( $children as $c ) {
			if ( isset( $options[ $c->$id ] ) ) {
				$output .= $this->display_level( $options, $c->$id, in_array( $c->$id, $this->meta ) && $active );
			}
		}

		$output .= '</div>';
		return $output;
	}
}
