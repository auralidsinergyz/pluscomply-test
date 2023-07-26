<?php
/**
 * Post API: Walker_GroupDropdown class
 *
 * @since      3.8
 */

/**
 * Core class used to create an HTML drop-down list of pages.
 *
 * @since 2.1.0
 *
 * @see   Walker
 */
class Walker_GroupDropdown extends Walker {

	/**
	 * What the class handles.
	 *
	 * @since 2.1.0
	 * @var string
	 *
	 * @see   Walker::$tree_type
	 */
	public $tree_type = 'page';

	/**
	 * Database fields to use.
	 *
	 * @since 2.1.0
	 * @var array
	 *
	 * @see   Walker::$db_fields
	 * @todo  Decouple this
	 */
	public $db_fields = array(
		'parent' => 'post_parent',
		'id'     => 'ID',
	);

	/**
	 * Starts the element output.
	 *
	 * @param string  $output Used to append additional content. Passed by reference.
	 * @param WP_Post $page   Page data object.
	 * @param int     $depth  Optional. Depth of page in reference to parent pages. Used for padding.
	 *                        Default 0.
	 * @param array   $args   Optional. Uses 'selected' argument for selected page to set selected HTML
	 *                        attribute for option element. Uses 'value_field' argument to fill "value"
	 *                        attribute. See wp_dropdown_pages(). Default empty array.
	 * @param int     $id     Optional. ID of the current page. Default 0 (unused).
	 *
	 * @see   Walker::start_el()
	 *
	 * @since 2.1.0
	 *
	 */
	public function start_el( &$output, $page, $depth = 0, $args = array(), $id = 0 ) {

		$padding = apply_filters(
			'ulgm_group_mangement_group_dropdown_padding',
			[
				'input'      => '— ',
				'multiplier' => $depth * 1
			],
			$depth
		);

		$pad = str_repeat( $padding['input'], $padding['multiplier'] );

		if ( ! isset( $args['value_field'] ) || ! isset( $page->{$args['value_field']} ) ) {
			$args['value_field'] = 'ID';
		}

		$output .= "\t<option class=\"level-$depth\" value=\"" . esc_attr( $page->{$args['value_field']} ) . '"';
		if ( $page->ID == $args['selected'] ) {
			$output .= ' selected="selected"';
		}
		$output .= '>';

		$title = $page->post_title;
		if ( '' === $title ) {
			/* translators: %d: ID of a post. */
			$title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		/**
		 * Filters the page title when creating an HTML drop-down list of pages.
		 *
		 * @param string  $title Page title.
		 * @param WP_Post $page  Page data object.
		 *
		 * @since 3.1.0
		 *
		 */
		$title = apply_filters( 'list_pages', $title, $page );

		$output .= $pad . esc_html( $title );
		$output .= "</option>\n";
	}

	/**
	 * Display array of elements hierarchically.
	 *
	 * Does not assume any existing order of elements.
	 *
	 * $max_depth = -1 means flatly display every element.
	 * $max_depth = 0 means display all levels.
	 * $max_depth > 0 specifies the number of display levels.
	 *
	 * @since 2.1.0
	 * @since 5.3.0 Formalized the existing `...$args` parameter by adding it
	 *              to the function signature.
	 *
	 * @param array $elements  An array of elements.
	 * @param int   $max_depth The maximum hierarchical depth.
	 * @param mixed ...$args   Optional additional arguments.
	 * @return string The hierarchical item output.
	 */
	public function walk( $elements, $max_depth, ...$args ) {
		$output = '';

		// Invalid parameter or nothing to walk.
		if ( $max_depth < -1 || empty( $elements ) ) {
			return $output;
		}

		$parent_field = $this->db_fields['parent'];
		$id_field     = $this->db_fields['id'];

		// Flat display.
		if ( -1 == $max_depth ) {
			$empty_array = array();
			foreach ( $elements as $e ) {
				$this->display_element( $e, $empty_array, 1, 0, $args, $output );
			}

			return $output;
		}

		/*
		 * Need to display in hierarchical order.
		 * Separate elements into two buckets: top level and children elements.
		 * Children_elements is two dimensional array, eg.
		 * Children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		$top_level_elements = array();
		$children_elements  = array();
		$a_check            = array();

		foreach ( $elements as $e ) {
			$a_check[] = $e->$id_field;
		}

		foreach ( $elements as $e ) {
			if ( empty( $e->$parent_field ) ) {
				$top_level_elements[$e->$id_field] = $e;
			} elseif ( !empty( $e->$parent_field ) && ! in_array( $e->$parent_field, $a_check ) ) {
				$top_level_elements[$e->$id_field] = $e;
			} else {
				$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		/*
		 * When none of the elements is top level.
		 * Assume the first one must be root of the sub elements.
		 */
		if ( empty( $top_level_elements ) ) {

			$first = array_slice( $elements, 0, 1 );
			$root  = $first[0];

			$top_level_elements = array();
			$children_elements  = array();
			foreach ( $elements as $e ) {
				if ( $root->$parent_field == $e->$parent_field ) {
					$top_level_elements[] = $e;
				} else {
					$children_elements[ $e->$parent_field ][] = $e;
				}
			}
		}

		foreach ( $top_level_elements as $e ) {
			$this->display_element( $e, $children_elements, $max_depth, 0, $args, $output );
		}

		/*
		 * If we are displaying all levels, and remaining children_elements is not empty,
		 * then we got orphans, which should be displayed regardless.
		 */
		if ( ( 0 == $max_depth ) && count( $children_elements ) > 0 ) {
			$empty_array = array();
			foreach ( $children_elements as $orphans ) {
				foreach ( $orphans as $op ) {
					$this->display_element( $op, $empty_array, 1, 0, $args, $output );
				}
			}
		}

		return $output;
	}

	/**
	 * Display array of elements hierarchically.
	 *
	 * Does not assume any existing order of elements.
	 *
	 * $max_depth = -1 means flatly display every element.
	 * $max_depth = 0 means display all levels.
	 * $max_depth > 0 specifies the number of display levels.
	 *
	 * @since 2.1.0
	 * @since 5.3.0 Formalized the existing `...$args` parameter by adding it
	 *              to the function signature.
	 *
	 * @param array $elements  An array of elements.
	 * @param int   $max_depth The maximum hierarchical depth.
	 * @param mixed ...$args   Optional additional arguments.
	 * @return string The hierarchical item output.
	 */
	public function walk_array( $elements, $max_depth, ...$args ) {
		$output = [];

		// Invalid parameter or nothing to walk.
		if ( $max_depth < -1 || empty( $elements ) ) {
			return $output;
		}

		$parent_field = $this->db_fields['parent'];
		$id_field     = $this->db_fields['id'];

		// Flat display.
		if ( -1 == $max_depth ) {
			$empty_array = array();
			foreach ( $elements as $e ) {
				$this->display_element_array( $e, $empty_array, 1, 0, $args, $output );
			}
			return $output;
		}

		/*
		 * Need to display in hierarchical order.
		 * Separate elements into two buckets: top level and children elements.
		 * Children_elements is two dimensional array, eg.
		 * Children_elements[10][] contains all sub-elements whose parent is 10.
		 */
		$top_level_elements = array();
		$children_elements  = array();
		$a_check            = array();

		foreach ( $elements as $e ) {
			$a_check[] = $e->$id_field;
		}

		foreach ( $elements as $e ) {
			if ( empty( $e->$parent_field ) ) {
				$top_level_elements[$e->$id_field] = $e;
			} elseif ( !empty( $e->$parent_field ) && ! in_array( $e->$parent_field, $a_check ) ) {
				$top_level_elements[$e->$id_field] = $e;
			} else {
				$children_elements[ $e->$parent_field ][] = $e;
			}
		}

		/*
		 * When none of the elements is top level.
		 * Assume the first one must be root of the sub elements.
		 */
		if ( empty( $top_level_elements ) ) {

			$first = array_slice( $elements, 0, 1 );
			$root  = $first[0];

			$top_level_elements = array();
			$children_elements  = array();
			foreach ( $elements as $e ) {
				if ( $root->$parent_field == $e->$parent_field ) {
					$top_level_elements[] = $e;
				} else {
					$children_elements[ $e->$parent_field ][] = $e;
				}
			}
		}

		foreach ( $top_level_elements as $e ) {
			$this->display_element_array( $e, $children_elements, $max_depth, 0, $args, $output );
		}

		/*
		 * If we are displaying all levels, and remaining children_elements is not empty,
		 * then we got orphans, which should be displayed regardless.
		 */
		if ( ( 0 == $max_depth ) && count( $children_elements ) > 0 ) {
			$empty_array = array();
			foreach ( $children_elements as $orphans ) {
				foreach ( $orphans as $op ) {
					$this->display_element_array( $op, $empty_array, 1, 0, $args, $output );
				}
			}
		}

		return $output;
	}

	/**
	 * Traverse elements to create list from elements.
	 *
	 * Display one element if the element doesn't have any children otherwise,
	 * display the element and its children. Will only traverse up to the max
	 * depth and no ignore elements under that depth. It is possible to set the
	 * max depth to include all depths, see walk() method.
	 *
	 * This method should not be called directly, use the walk() method instead.
	 *
	 * @since 2.5.0
	 *
	 * @param object $element           Data object.
	 * @param array  $children_elements List of elements to continue traversing (passed by reference).
	 * @param int    $max_depth         Max depth to traverse.
	 * @param int    $depth             Depth of current element.
	 * @param array  $args              An array of arguments.
	 * @param string $output            Used to append additional content (passed by reference).
	 */
	public function display_element_array( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}

		$id_field = $this->db_fields['id'];
		$id       = $element->$id_field;

		// Display this element.
		$this->has_children = ! empty( $children_elements[ $id ] );
		if ( isset( $args[0] ) && is_array( $args[0] ) ) {
			$args[0]['has_children'] = $this->has_children; // Back-compat.
		}

		$this->start_el_array( $output, $element, $depth, ...array_values( $args ) );

		// Descend only when the depth is right and there are children for this element.
		if ( ( 0 == $max_depth || $max_depth > $depth + 1 ) && isset( $children_elements[ $id ] ) ) {

			foreach ( $children_elements[ $id ] as $child ) {

				if ( ! isset( $newlevel ) ) {
					$newlevel = true;
					// Start the child delimiter.
					$this->start_lvl( $output, $depth, ...array_values( $args ) );
				}
				$this->display_element_array( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
			}
			unset( $children_elements[ $id ] );
		}

		if ( isset( $newlevel ) && $newlevel ) {
			// End the child delimiter.
			$this->end_lvl( $output, $depth, ...array_values( $args ) );
		}

		// End this element.
		$this->end_el( $output, $element, $depth, ...array_values( $args ) );
	}

	/**
	 * Starts the element output.
	 *
	 * @param string  $output Used to append additional content. Passed by reference.
	 * @param WP_Post $page   Page data object.
	 * @param int     $depth  Optional. Depth of page in reference to parent pages. Used for padding.
	 *                        Default 0.
	 * @param array   $args   Optional. Uses 'selected' argument for selected page to set selected HTML
	 *                        attribute for option element. Uses 'value_field' argument to fill "value"
	 *                        attribute. See wp_dropdown_pages(). Default empty array.
	 * @param int     $id     Optional. ID of the current page. Default 0 (unused).
	 *
	 * @see   Walker::start_el()
	 *
	 * @since 2.1.0
	 *
	 */
	public function start_el_array( &$output, $page, $depth = 0, $args = array(), $id = 0 ) {

		$padding = apply_filters(
			'ulgm_group_mangement_group_dropdown_padding',
			[
				'input'      => '— ',
				'multiplier' => $depth * 1
			],
			$depth
		);

		$pad = str_repeat( $padding['input'], $padding['multiplier'] );

		if ( ! isset( $args['value_field'] ) || ! isset( $page->{$args['value_field']} ) ) {
			$args['value_field'] = 'ID';
		}



		$title = $page->post_title;
		if ( '' === $title ) {
			/* translators: %d: ID of a post. */
			$title = sprintf( __( '#%d (no title)' ), $page->ID );
		}

		/**
		 * Filters the page title when creating an HTML drop-down list of pages.
		 *
		 * @param string  $title Page title.
		 * @param WP_Post $page  Page data object.
		 *
		 * @since 3.1.0
		 *
		 */
		$title = apply_filters( 'list_pages', $title, $page );
		$output[$page->ID]= esc_html( $title );
	}
}
