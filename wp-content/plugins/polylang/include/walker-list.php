<?php

/*
 * displays a language list
 *
 * @since 1.2
 */
class PLL_Walker_List extends Walker {
	var $db_fields = array( 'parent' => 'parent', 'id' => 'id' );

	/*
	 * outputs one element
	 *
	 * @since 1.2
	 *
	 * @see Walker::start_el
	 */
	function start_el( &$output, $element, $depth = 0, $args = array(), $current_object_id = 0 ) {
		$output .= sprintf(
			"\t".'<li class="%s"><a hreflang="%s" href="%s">%s</a></li>'."\n",
			esc_attr( implode( ' ', $element->classes ) ),
			esc_attr( $element->locale ),
			esc_url( $element->url ),
			$args['show_flags'] && $args['show_names'] ? $element->flag.'&nbsp;'.esc_html( $element->name ) : $element->flag.esc_html( $element->name )
		);
	}

	/*
	 * overrides Walker::display_element as it expects an object with a parent property
	 *
	 * @since 1.2
	 *
	 * @see Walker::display_element
	 */
	function display_element( $element, &$children_elements, $max_depth, $depth = 0, $args, &$output ) {
		$element = (object) $element; // make sure we have an object
		$element->parent = $element->id = 0; // don't care about this
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}

	/*
	 * overrides Walker:walk to set depth argument
	 *
	 * @since 1.2
	 *
	 * @param array $elements elements to display
	 * @param array $args
	 * @return string
	 */
	function walk( $elements, $args = array() ) {
		return parent::walk( $elements, -1, $args );
	}
}
