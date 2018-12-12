<?php
/**
 * Admin welcome screen for this theme.
 *
 * @package AweThemes
 */

/**
 * AT_Admin_Welcome class.
 *
 * We don't need use {Theme}_Admin_Welcome prefix,
 * for other extends code can be re-use this class easy.
 */
final class AT_Admin_Welcome {
	/**
	 * Singleton reference to singleton instance.
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Gets the instance via lazy initialization.
	 *
	 * @return self
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static;
		}

		return static::$instance;
	}

	/**
	 * Tabs collection to render.
	 *
	 * @var array
	 */
	protected $tabs;

	/**
	 * Cache Tabs collection.
	 *
	 * @var array
	 */
	protected $cache_tabs;

	/**
	 * Menu slug for admin page.
	 *
	 * @var string
	 */
	protected $menu_slug = 'awethemes';

	/**
	 * Private constuctor for Singleton Design Pattern.
	 *
	 * @see https://github.com/domnikl/DesignPatternsPHP/tree/master/Creational/Singleton
	 */
	private function __construct() {
		add_action( 'admin_menu', array( $this, '_add_theme_menu' ), 30 );
		add_action( 'after_switch_theme', array( $this, '_set_welcome_redirect' ), 30 );
		add_action( 'admin_init',  array( $this, '_handler_switch_theme' ) );
	}

	/**
	 * Handler add tab to admin-welcome.
	 *
	 * @param array   $tab      The tab to register.
	 * @param integer $priority Tab priority, default is 10.
	 */
	public function add_tab( array $tab, $priority = 10 ) {
		if ( ! isset( $tab['id'] ) ) {
			return new WP_Error( 'whoop' );
		}

		$title = empty( $tab['title'] ) ? $tab['id'] : $tab['title'];
		$id = sanitize_key( $tab['id'] );

		$tab = wp_parse_args( $tab, array(
			'id'       => $id,
			'title'    => $title,
			'link'     => '#',
			// 'callback' => '',
		) );

		$this->tabs[ $priority ][ $id ] = $tab;

		return true;
	}

	/**
	 * Remove tab by key and priority.
	 *
	 * @param  strinf  $id       The tab to remove.
	 * @param  integer $priority Tab priority, default is 10.
	 * @return boolean
	 */
	public function remove_tab( $id, $priority = 10 ) {
		if ( isset( $this->tabs[ $priority ][ $id ] ) ) {
			unset( $this->tabs[ $priority ][ $id ] );

			return true;
		}

		return false;
	}

	/**
	 * Get flaten of registerd tabs.
	 *
	 * @return array
	 */
	public function get_tabs() {
		// Return if cache_tabs is available.
		if ( null !== $this->cache_tabs ) {
			return $this->cache_tabs;
		}

		// Build tabs from raw_tabs.
		$raw_tabs = $this->tabs;
		ksort( $raw_tabs );

		$tabs = array();
		foreach ( $raw_tabs as $id => $tab ) {
			$tabs = array_merge( $tabs, $tab );
		}

		// Cache this tabs.
		$this->cache_tabs = apply_filters( 'at_admin_welcome_tabs', $tabs );

		return $this->cache_tabs;
	}

	/**
	 * Add welcome theme menu.
	 *
	 * @access private
	 */
	public function _add_theme_menu() {
		$theme = wp_get_theme();
		$menu_name = sprintf( 'Welcome %s', $theme->get( 'Name' ) );

		add_theme_page( $menu_name, $menu_name, 'manage_options', $this->menu_slug, array( $this, '_welcome_screen' ) );
	}

	/**
	 * Render default welcome screen.
	 *
	 * @access private
	 */
	public function _welcome_screen() {
		$current_tab = $this->get_current_tab();

		// Don't output anything if $tabs is empty.
		if ( ! $current_tab ) {
			return;
		}

		?><div class="wrap awethemes-welcome-page">
			<?php $this->display_nav_tabs( $current_tab['id'] ); ?>

			<?php if ( ! empty( $current_tab['callback'] ) ) : ?>
				<div class="awethemes-welcome-tab <?php echo esc_attr( $current_tab['id'] ); ?>">
					<?php call_user_func( $current_tab['callback'] ); ?>
				</div>
			<?php endif; ?>

		</div><?php
	}

	/**
	 * Display nav tabs
	 * We make it public so any extends code can be use this method.
	 *
	 * @param string $current Active current tab by key.
	 */
	public function display_nav_tabs( $current = null ) {
		$tabs = $this->get_tabs();
		$links = '';

		// Don't output anything if $tabs is empty.
		if ( empty( $tabs ) ) {
			return;
		}

		if ( is_null( $current ) ) {
			$current = $this->get_current_tab();
			$current = $current['id'];
		}

		// Build tabs.
		foreach ( $tabs as $id => $tab ) {
			if ( ! empty( $tab['callback'] ) ) {
				$tab['link'] = add_query_arg( 'tab', $id, admin_url( 'themes.php?page=' . $this->menu_slug ) );
			}

			$active = ( $id == $current ) ? 'nav-tab-active' : '';
			$links  .= '<a class="nav-tab ' . $active . '" href="' . esc_url( $tab['link'] ) . '">' . esc_html( $tab['title'] ) . '</a>';
		}

		if ( $links ) {
			printf( '<h2 class="nav-tab-wrapper">%s</h2>', $links ); // WPCS: xss ok.
		}
	}

	/**
	 * Get current tab from $_REQUEST['tab'] otherewide
	 * use first key in registed tabs as current tab.
	 *
	 * @return array|null
	 */
	protected function get_current_tab() {
		$tabs = $this->get_tabs();

		if ( empty( $tabs ) ) {
			return;
		}

		if ( isset( $_REQUEST['tab'] ) && isset( $tabs[ $_REQUEST['tab'] ] ) ) {
			$current_tab = $tabs[ $_REQUEST['tab'] ];
		} else {
			$current_tab = reset( $tabs );
		}

		return $current_tab;
	}

	/**
	 * Hanlder after switch theme setup.
	 */
	public function _handler_switch_theme() {
		$redirect = get_transient( '_at_welcome_redirect' );
		delete_transient( '_at_welcome_redirect' );
		$redirect && wp_redirect( admin_url( 'themes.php?page=awethemes' ) );
	}

	/**
	 * Set redirect transition on update or activation.
	 */
	public function _set_welcome_redirect() {
		if ( ! is_network_admin() ) {
			set_transient( '_at_welcome_redirect', 1, 30 );
		}
	}
}

/**
 * Initialization for first run.
 */
$GLOBALS['at_admin_welcome'] = AT_Admin_Welcome::instance();

/**
 * So, you can register new welcome-tab in here.
 */
do_action( 'admin_welcome_init', $GLOBALS['at_admin_welcome'] );
