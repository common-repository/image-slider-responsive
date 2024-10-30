<?php
$image_ids 		= array_filter(explode(',', get_post_meta( $id, '_shapla_image_ids', true) ));
if ( count( $image_ids ) < 2 ) {
	return;
}

$img_size 		= get_post_meta( $id, '_flexslider_slide_img_size', true);
$animation 		= get_post_meta( $id, '_flexslider_animation', true);
$easing 		= get_post_meta( $id, '_flexslider_easing', true);
$direction 		= get_post_meta( $id, '_flexslider_direction', true);
$slideshowspeed = get_post_meta( $id, '_flexslider_slideshowspeed', true);
$animationspeed = get_post_meta( $id, '_flexslider_animationspeed', true);
$animationloop 	= get_post_meta( $id, '_flexslider_animationloop', true);

$item_width 	= get_post_meta( $id, '_flexslider_item_width', true);
$item_margin 	= get_post_meta( $id, '_flexslider_item_margin', true);
$min_item 		= get_post_meta( $id, '_flexslider_min_item', true);
$max_item 		= get_post_meta( $id, '_flexslider_max_item', true);
$move_item 		= get_post_meta( $id, '_flexslider_move_item', true);

$control_nav 	= get_post_meta( $id, '_flexslider_control_nav', true);
$direction_nav 	= get_post_meta( $id, '_flexslider_direction_nav', true);
?>
<div id="id-<?php echo $id; ?>">
	<div
		class="flexslider"
		data-animation = "<?php echo esc_attr($animation); ?>"
		data-easing = "<?php echo esc_attr($easing); ?>"
		data-direction = "<?php echo esc_attr($direction); ?>"
		data-slideshow-speed = "<?php echo intval($slideshowspeed); ?>"
		data-animation-speed = "<?php echo intval($animationspeed); ?>"
		data-animation-loop = "<?php echo ($animationloop == 'off') ? 'false' : 'true'; ?>"
		data-nav-control = "<?php echo ($control_nav == 'off') ? 'false' : 'true'; ?>"
		data-nav-direction = "<?php echo ($direction_nav == 'off') ? 'false' : 'true'; ?>"
		data-item-width = "<?php echo intval($item_width); ?>"
		data-item-margin = "<?php echo intval($item_margin); ?>"
		data-item-min = "<?php echo intval($min_item); ?>"
		data-item-max = "<?php echo intval($max_item); ?>"
		data-item-move = "<?php echo intval($move_item); ?>"
	>
		<ul class="slides">
			<?php
				foreach ($image_ids as $image_id) {
					$src 	= wp_get_attachment_image_src( $image_id, $img_size );
					echo "<li>";
					echo sprintf('<img src="%1$s" width="%2$s" height="%3$s">', $src[0], $src[1], $src[2] );

					$image 	= get_post( $image_id );
					$caption = $image->post_excerpt;
					if ( ! empty($caption) ) {
						echo sprintf('<p class="flex-caption">%1$s</p>', esc_textarea($caption));
					}
					echo "</li>";
				}
			?>
		</ul>
	</div>
</div>