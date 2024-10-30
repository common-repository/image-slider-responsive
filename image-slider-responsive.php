<?php
/**
 * Plugin Name:       Image Slider Responsive
 * Plugin URI:        https://wordpress.org/plugins/image-slider-responsive/
 * Description:       A WordPress plugin to include image slider into your theme.
 * Version:           2.1.0
 * Author:            Sayful Islam
 * Author URI:        https://profiles.wordpress.org/sayful/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

if ( !class_exists('Image_Slider_Responsive')):

class Image_Slider_Responsive {

    private $plugin_name    = 'image-slider-responsive';
    private $plugin_version = '2.1.0';
    private $plugin_path;
    private $plugin_url;

	/**
	 * Instance of this class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	public function __construct(){
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts') );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts') );
		add_action( 'wp_footer', array( $this, 'inline_scripts'), 30 );

		$this->includes();
	}

	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function includes(){
		require_once $this->plugin_path() . '/includes/ShaplaTools_Metaboxs.php';
		require_once $this->plugin_path() . '/includes/Image_Slider_Responsive_Admin.php';
		require_once $this->plugin_path() . '/includes/Image_Slider_Responsive_Shortcode.php';

		new Image_Slider_Responsive_Admin();
		new Image_Slider_Responsive_Shortcode( $this->plugin_path() );
	}

	public function admin_scripts(){
		global $post;
		if( is_a( $post, 'WP_Post' ) && $post->post_type != 'flexslider2' ){
			return;
		}

		wp_enqueue_media();
		wp_enqueue_style( $this->plugin_name, $this->plugin_url() . '/assets/css/admin.css' );
	}

	public function enqueue_scripts()
	{
		if( ! $this->should_load_script() ) {
			return;
		}
		wp_enqueue_style( $this->plugin_name, $this->plugin_url() . '/assets/css/style.css', array(), $this->plugin_version, 'all');
		wp_enqueue_script( 'flexslider', $this->plugin_url() . '/assets/js/jquery.flexslider-min.js', array( 'jquery' ), '2.6.3', false );
	}

	public function inline_scripts()
	{
		if( ! $this->should_load_script() ) {
			return;
		}
		?>
		<script type="text/javascript" charset="utf-8">
			jQuery( document ).ready(function( $ ){
				$( 'body' ).find('.flexslider').each(function(){
			  		var _this = $(this);
				  	_this.flexslider({
				    	animation: _this.data('animation'),
				    	easing: _this.data('easing'),
				    	direction: _this.data('direction'),
				    	slideshowSpeed: _this.data('slideshow-speed'),
						animationSpeed: _this.data('animation-speed'),
						animationLoop: _this.data('animation-loop'),
						controlNav: _this.data('nav-control'),
						directionNav: _this.data('nav-direction'),
						itemWidth: _this.data('item-width'),
						itemMargin: _this.data('item-margin'),
						minItems: _this.data('item-min'),
						maxItems: _this.data('item-max'),
						move: _this.data('item-move'),
				  	});
			  	});
			});
		</script>
		<?php
	}

	private function should_load_script()
	{
		global $post;
		if ( ! is_a( $post, 'WP_Post' ) ) {
			return false;
		}

		if ( has_shortcode( $post->post_content, 'all_slider') ) {
			return true;
		}

		if ( has_shortcode( $post->post_content, 'slider') ) {
			return true;
		}

		if ( has_shortcode( $post->post_content, 'FlexSlider2') ) {
			return true;
		}

		if ( has_shortcode( $post->post_content, 'image_slider_responsive') ) {
			return true;
		}

		return false;
	}

    /**
     * Plugin path.
     *
     * @return string Plugin path
     */
    private function plugin_path() {
        if ( $this->plugin_path ) return $this->plugin_path;

        return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
    }

    /**
     * Plugin url.
     *
     * @return string Plugin url
     */
    private function plugin_url() {
        if ( $this->plugin_url ) return $this->plugin_url;

        return $this->plugin_url = untrailingslashit( plugin_dir_url( __FILE__ ) );
    }
}

add_action( 'plugins_loaded', array( 'Image_Slider_Responsive', 'get_instance' ) );
endif;