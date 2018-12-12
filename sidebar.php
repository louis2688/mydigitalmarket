<?php
/**
 * The sidebar containing the main widget area.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package circle
 */

if ( ! is_active_sidebar( $sidebar_name = Circle_Sidebar::get_sidebar() ) ) {
	return;
}
?>
<aside id="secondary" class="sidebar widget-area" role="complementary">
	<?php dynamic_sidebar( $sidebar_name ); ?>
</aside><!-- #secondary -->
