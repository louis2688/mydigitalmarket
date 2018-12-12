<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package circle
 */

$background = circle_footer_background( circle_option( 'footer_bg_type' ), circle_option( 'footer_bg_color' ), circle_option( 'footer_bg_image' ) );
?>					
					<?php if ( empty( $GLOBALS['hidden_layout'] ) ) : ?>
					</div><!-- /#layout -->
					<?php endif;?>
					<?php if ( empty( $GLOBALS['hidden_container'] ) ) : ?>
				</div><!-- /#container -->
				<?php endif;?>
				<?php if ( empty( $GLOBALS['hidden_layout'] ) ) : ?>
			</div><!-- /.bx-content__info -->
		</div><!-- /.bx-content -->
		<?php endif;?>
	</section><!-- /#page-container -->
	<footer id="footer" class="site-footer" style="background: <?php echo esc_attr( $background ); ?>">
		<div class="container">
			<div class="footer-wrap text-center">

				<?php
				/**
				 * Circle Footer Hook.
				 *
				 * @hooked circle_footer_logo - 10
				 * @hooked circle_footer_social - 20
				 * @hooked circle_footer_copyright - 30
				 */
				do_action( 'circle_footer' );
				?>
				
			</div>
		</div>
	</footer>
	</div>
</div>
<?php wp_footer(); ?>
</body>
</html>
