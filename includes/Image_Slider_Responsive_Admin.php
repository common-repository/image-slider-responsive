<?php

if( ! class_exists('Image_Slider_Responsive_Admin') ):

class Image_Slider_Responsive_Admin
{
	public function __construct()
	{
		add_action( 'init', array( $this, 'post_type'), 0 );
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );

		add_filter( 'manage_edit-flexslider2_columns', array($this, 'columns_head') );
		add_action( 'manage_flexslider2_posts_custom_column', array($this, 'columns_content') );
	}

	public function post_type(){

		$labels = array(
			'name'                => _x( 'Slides', 'Post Type General Name', 'flexslider' ),
			'singular_name'       => _x( 'Slide', 'Post Type Singular Name', 'flexslider' ),
			'menu_name'           => __( 'Slider', 'flexslider' ),
			'parent_item_colon'   => __( 'Parent Slide:', 'flexslider' ),
			'all_items'           => __( 'All Slides', 'flexslider' ),
			'view_item'           => __( 'View Slide', 'flexslider' ),
			'add_new_item'        => __( 'Add New Slide', 'flexslider' ),
			'add_new'             => __( 'Add New', 'flexslider' ),
			'edit_item'           => __( 'Edit Slide', 'flexslider' ),
			'update_item'         => __( 'Update Slide', 'flexslider' ),
			'search_items'        => __( 'Search Slide', 'flexslider' ),
			'not_found'           => __( 'Not found', 'flexslider' ),
			'not_found_in_trash'  => __( 'Not found in Trash', 'flexslider' ),
		);
		$args = array(
			'label'               => __( 'slider', 'flexslider' ),
			'description'         => __( 'Post Type Description', 'flexslider' ),
			'labels'              => $labels,
			'supports'            => array( 'title' ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'show_in_nav_menus'   => false,
			'show_in_admin_bar'   => false,
			'menu_icon'           => 'dashicons-slides',
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'rewrite'             => false,
			'capability_type'     => 'post',
		);
		register_post_type( 'flexslider2', $args );
	}

	public function columns_head( $defaults ) {
		unset( $defaults['date'] );

		$defaults['shortcode'] 	= __( 'Shortcode', 'flexslider' );
		$defaults['images'] 	= __( 'Images', 'flexslider' );

		return $defaults;
	}

	public function columns_content( $column_name ) {

		$image_ids 	= explode(',', get_post_meta( get_the_ID(), '_shapla_image_ids', true) );

		if ( 'shortcode' == $column_name ) {
			echo '<pre><code>[image_slider_responsive id="'.get_the_ID().'"]</pre></code>';
		}

		if ( 'images' == $column_name ) {
			?>
			<ul id="slider-thumbs" class="slider-thumbs">
				<?php

				foreach ( $image_ids as $image ) {
					if(!$image) continue;
					$src = wp_get_attachment_image_src( $image, array(50,50) );
					echo "<li><img src='{$src[0]}' width='{$src[1]}' height='{$src[2]}'></li>";
				}

				?>
			</ul>
			<?php
		}
	}

	/**
	 * Adds the meta box container.
	 */
	public function add_meta_box() {
		$meta_box = array(
		    'id' => 'flexslider-metabox-slide',
		    'title' => __('Slide Settings', 'image-slider-responsive'),
		    'description' => __('To use this slider in your posts or pages use the following shortcode:<pre><code>[image_slider_responsive id="'.get_the_ID().'"]</code></pre><br>', 'image-slider-responsive'),
		    'page' => 'flexslider2',
		    'context' => 'normal',
		    'priority' => 'high',
		    'fields' => array(
		        array(
		            'name' => __('Slider Images', 'image-slider-responsive'),
		            'desc' => __('Choose slider images.', 'image-slider-responsive'),
		            'id' => '_flexslider_slide_images',
		            'type' => 'images',
		            'std' => __('Upload Images', 'image-slider-responsive')
		        ),
		        array(
		            'name' => __('Slider Image Size', 'image-slider-responsive'),
		            'desc' => __('Select image size from available image size. Use full for original image size.', 'image-slider-responsive'),
		            'id' => '_flexslider_slide_img_size',
		            'type' => 'select',
		            'std' => 'full',
		            'options' => $this->images_size()
		        ),
		        array(
		            'name' => __('Animation type', 'image-slider-responsive'),
		            'desc' => __('Controls the animation type.', 'image-slider-responsive'),
		            'id' => '_flexslider_animation',
		            'type' => 'select',
		            'std' => 'slide',
		            'options' => array(
		            	'fade' 		=> __('fade', 'image-slider-responsive'),
		            	'slide' 	=> __('slide', 'image-slider-responsive'),
		            )
		        ),
		        array(
		            'name' => __('jQuery easing', 'image-slider-responsive'),
		            'desc' => __('Choose jQuery easing for slide.', 'image-slider-responsive'),
		            'id' => '_flexslider_easing',
		            'type' => 'select',
		            'std' => 'swing',
		            'options' => array(
		            	'swing' 		=> __('swing', 'image-slider-responsive'),
		            	'linear' 	=> __('linear', 'image-slider-responsive'),
		            )
		        ),
		        array(
		            'name' => __('Direction', 'image-slider-responsive'),
		            'desc' => __('Choose sliding direction of the slider.', 'image-slider-responsive'),
		            'id' => '_flexslider_direction',
		            'type' => 'select',
		            'std' => 'horizontal',
		            'options' => array(
		            	'horizontal' 		=> __('horizontal', 'image-slider-responsive'),
		            	'vertical' 	=> __('vertical', 'image-slider-responsive'),
		            )
		        ),
		        array(
		            'name' => __('Animation Loop', 'image-slider-responsive'),
		            'desc' => __('Check to allow sliders to have a seamless infinite loop', 'image-slider-responsive'),
		            'id' => '_flexslider_animationloop',
		            'type' => 'checkbox',
		            'std' => 'on'
		        ),
		        array(
		            'name' => __('Show Dots navigation', 'image-slider-responsive'),
		            'desc' => __('Check to create navigation for paging control of each clide', 'image-slider-responsive'),
		            'id' => '_flexslider_control_nav',
		            'type' => 'checkbox',
		            'std' => 'on'
		        ),
		        array(
		            'name' => __('Show Previous/Next navigation', 'image-slider-responsive'),
		            'desc' => __('Check to create navigation for previous/next navigation', 'image-slider-responsive'),
		            'id' => '_flexslider_direction_nav',
		            'type' => 'checkbox',
		            'std' => 'on'
		        ),
		        array(
		            'name' => __('Slideshow Speed', 'image-slider-responsive'),
		            'desc' => __('Sets the amount of time between each slideshow interval, in milliseconds.', 'image-slider-responsive'),
		            'id' => '_flexslider_slideshowspeed',
		            'type' => 'text',
		            'std' => '7000'
		        ),
		        array(
		            'name' => __('Slideshow Speed', 'image-slider-responsive'),
		            'desc' => __('Sets the duration in which animations will happen, in milliseconds.', 'image-slider-responsive'),
		            'id' => '_flexslider_animationspeed',
		            'type' => 'text',
		            'std' => '600'
		        ),
		        array(
		            'name' => __('Carousel: Item Width', 'image-slider-responsive'),
		            'desc' => __('Box-model width of individual carousel items, including horizontal borders and padding.', 'image-slider-responsive'),
		            'id' => '_flexslider_item_width',
		            'type' => 'text',
		            'std' => '0'
		        ),
		        array(
		            'name' => __('Carousel: Item Margin', 'image-slider-responsive'),
		            'desc' => __('Margin between carousel items.', 'image-slider-responsive'),
		            'id' => '_flexslider_item_margin',
		            'type' => 'text',
		            'std' => '0'
		        ),
		        array(
		            'name' => __('Carousel: Minimum Item', 'image-slider-responsive'),
		            'desc' => __('Minimum number of carousel items that should be visible. Items will resize fluidly when below this.', 'image-slider-responsive'),
		            'id' => '_flexslider_min_item',
		            'type' => 'text',
		            'std' => '0'
		        ),
		        array(
		            'name' => __('Carousel: Maxmimum Item', 'image-slider-responsive'),
		            'desc' => __('Maxmimum number of carousel items that should be visible. Items will resize fluidly when above this limit.', 'image-slider-responsive'),
		            'id' => '_flexslider_max_item',
		            'type' => 'text',
		            'std' => '0'
		        ),
		        array(
		            'name' => __('Carousel: Move Item', 'image-slider-responsive'),
		            'desc' => __('Number of carousel items that should move on animation. If 0, slider will move all visible items.', 'image-slider-responsive'),
		            'id' => '_flexslider_move_item',
		            'type' => 'text',
		            'std' => '0'
		        ),
		    )
		);
		$flexsliderTools_Metaboxs = new ShaplaTools_Metaboxs();
		$flexsliderTools_Metaboxs->shapla_add_meta_box($meta_box);
	}

	/**
	 * Get available image size
	 * 
	 * @return array
	 */
	private function images_size(){
	    $flexslidertools_img_size = get_intermediate_image_sizes();
	    array_push($flexslidertools_img_size, 'full');

	    $singleArray = array();

	    foreach ($flexslidertools_img_size as $key => $value){

	        $singleArray[$value] = $value;
	    }

	    return $singleArray;
	}
}

endif;