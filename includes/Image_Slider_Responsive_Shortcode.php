<?php

if( ! class_exists('Image_Slider_Responsive_Shortcode') ):

class Image_Slider_Responsive_Shortcode
{
	private $plugin_path;

	public function __construct( $plugin_path )
	{
		$this->plugin_path = $plugin_path;

		add_shortcode('image_slider_responsive', array( $this, 'image_slider_responsive') );

		// Backup for old shortcode
		add_shortcode('FlexSlider2', array( $this, 'image_slider_responsive') );
	}

	public function image_slider_responsive( $atts, $content = null ){
	    extract(shortcode_atts(array(
	        'id' => '',
	    ), $atts));

		ob_start();
		include_once $this->plugin_path . '/templates/image_slider_responsive.php';
		return ob_get_clean();
	}
}

endif;