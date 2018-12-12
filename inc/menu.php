<?php
/**
 * Circle menu utils classes.
 *
 * @package Circle
 */

/**
 * Custom walker primary menu
 */
class Circle_Walker_Nav_Menu extends Walker_Nav_Menu {
	/**
	 * Starts the list before the elements are added.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments. @see wp_nav_menu().
	 */
	public function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "\n$indent<ul class=\"navbar-child\">\n";
	}
	/**
	 * Start the element output.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   An array of arguments.
	 * @param int    $id     Current item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$class_names = $value = '';
		$item->classes[] = 'navbar-nav__item';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = ' menu-item-' . $item->ID;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		if ( in_array( 'current-menu-item', $classes ) ) {
			$class_names .= ' active';
		}
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';
		$output .= $indent . '<li' . $id . $value . $class_names .'>';
		$atts = array();
		$atts['title']  = ! empty( $item->title )	? $item->title	: '';
		$atts['target'] = ! empty( $item->target )	? $item->target	: '';
		$atts['rel']    = ! empty( $item->xfn )		? $item->xfn	: '';
		// If item has_children add atts to a.
		if ( $args->has_children ) {
			$atts['href']   		= ! empty( $item->url ) ? $item->url : '';
			$atts['class']			= 'navbar-nav__link';
			$atts['data-target']	= '.navbar-child';
		} else {
			$atts['href'] = ! empty( $item->url ) ? $item->url : '';
			$atts['class']			= 'navbar-nav__link';
		}
		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= ( $args->has_children ) ? ' <i class="fa fa-angle-right"></i></a>' : '</a>';
		$item_output .= $args->after;
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
	/**
	 * Display element menu.
	 */
	public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
		if ( ! $element ) {
			return;
		}
		$id_field = $this->db_fields['id'];
		// Display this element.
		if ( is_object( $args[0] ) ) {
		   	$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
		}
		parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
	}
}

/**
 * Circle Menu Support Class
 */
class Circle_Menu_Support {
	/**
	 * Conditionally hook into WordPress
	 */
	public static function init() {
	}

	/**
	 * Helper fallback menu
	 */
	public static function fallback() {
		$fallback_menu = '<ul id="fallback-menu" class="nav navbar-nav"><li class="navbar-nav__item"><a class="navbar-nav__link" href="%1$s" rel="home">%2$s</a></li></ul>';
		printf( $fallback_menu, esc_url( home_url( '/' ) ), esc_html__( 'Home', 'circle' ) ); // WPCS: XSS OK.
	}
}

Circle_Menu_Support::init();
