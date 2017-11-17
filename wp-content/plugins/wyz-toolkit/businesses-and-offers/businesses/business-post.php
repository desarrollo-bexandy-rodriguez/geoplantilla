<?php
/**
 * Business and Business posts creator
 *
 * @package wyz
 */

if ( ! post_type_exists( 'wyz_business_post' ) ) {

	// Create business post cpt.
	add_action( 'init', 'wyz_create_business_post', 5 );
}

/**
 * Creates the wyz_business_post cpt
 */
function wyz_create_business_post() {
	$bus_post_name = esc_html__( wyz_syntax_permalink( get_option( 'wyz_business_post_old_single_permalink' ) ) );
	register_post_type( 'wyz_business_post',array(
		'public' => true,
		'map_meta_cap' => true,
		'capabilities' => array(
			'publish_posts' => 'publish_businesses',
			'edit_posts' => 'edit_businesses',
			'edit_others_posts' => 'edit_others_businesses',
			'delete_posts' => 'delete_businesses',
			'delete_published_posts' => 'delete_published_businesses',
			'edit_published_posts' => 'edit_published_businesses',
			'delete_others_posts' => 'delete_others_businesses',
			'read_private_posts' => 'read_private_businesses',
			'read_post' => 'read_business',
		),
		'labels' => array(
			'name' => esc_html( $bus_post_name ),
			'singular_name' => esc_html( $bus_post_name ),
			'add_new' => esc_html__( 'Add New', 'wyzi-business-finder' ),
			'add_new_item' => esc_html__( 'Add New', 'wyzi-business-finder' ) . ' ' . $bus_post_name,
			'edit' => esc_html__( 'Edit', 'wyzi-business-finder' ),
			'edit_item' => esc_html__( 'Edit', 'wyzi-business-finder' ) . ' ' . $bus_post_name,
			'new_item' => esc_html__( 'New', 'wyzi-business-finder' ) . ' ' . $bus_post_name,
			'view' => esc_html__( 'View', 'wyzi-business-finder' ),
			'view_item' => esc_html__( 'View', 'wyzi-business-finder' ) . ' ' . $bus_post_name,
			'search_items' => esc_html__( 'Search', 'wyzi-business-finder' ) . ' ' . $bus_post_name,

			'not_found' => esc_html__( 'No', 'wyzi-business-finder' ) . ' ' . $bus_post_name . ' ' . esc_html__( 'found', 'wyzi-business-finder' ),
			'not_found_in_trash' => esc_html__( 'No', 'wyzi-business-finder' ) . ' ' . $bus_post_name . ' ' . esc_html__( 'found in trash', 'wyzi-business-finder' ),
			'parent' => esc_html__( 'Parent', 'wyzi-business-finder' ) . ' ' . $bus_post_name,
		),
		'public' => true,
		'menu_position' => 57.1,
		'has_archive' => true,
		'supports' => array( 'title', 'thumbnail', 'editor', 'comments' ),
		'taxonomies' => array( '' ),
		'menu_icon' => plugins_url( 'images/posts.png', __FILE__ ),
		'exclude_from_search' => true,
		'rewrite' => array( 'slug' => esc_html( get_option( 'wyz_business_post_old_single_permalink' ) ) ),
	) );
}

/**
 * Class WyzBusinessPost.
 */

if (class_exists('WyzBusinessPostOverride')) {
	class WyzBusinessPostOverridden extends WyzBusinessPostOverride { }
} else {
	class WyzBusinessPostOverridden { }
}

class WyzBusinessPost extends WyzBusinessPostOverridden {


	private static $comments = array();

	/**
	 * Get Business category data.
	 *
	 * @param int $business_id the business id.
	 */
	private static function wyz_get_category_data( $business_id ) {

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_get_category_data') ) {
			return WyzBusinessPostOverride::wyz_get_category_data( $business_id );
		}

		$cat_id = WyzHelpers::wyz_get_representative_business_category_id( $business_id );
		$parent_cat = get_term( $cat_id, 'wyz_business_category' );
		if ( ! is_wp_error( $parent_cat ) && ! empty( $parent_cat ) ) {
			//$parent_cat = $parent_cat[0];
			$cat_name = $parent_cat->name;
			$cat_link = get_term_link( $parent_cat );
			$cat_icn = wp_get_attachment_url( get_term_meta( $cat_id, 'wyz_business_icon_upload', true ) );
			$color = get_term_meta( $cat_id, 'wyz_business_cat_bg_color', true );
		} else {
			$cat_id = $cat_name = $cat_link = $cat_icn = $color = '';
		}

		$data = array(
			'id' => $cat_id,
			'name' => $cat_name,
			'color' => $color,
			'icon' => $cat_icn,
			'link' => $cat_link,
		);

		return $data;
	}



	/**
	 * Get Business data.
	 *
	 * @param int $id the business id.
	 */
	private static function wyz_get_business_data( $id ) {

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_get_business_data') ) {
			return WyzBusinessPostOverride::wyz_get_business_data( $id );
		}

		$slgn = get_post_meta( $id, 'wyz_business_slogan', true );
		$dsc = get_post_meta( $id, 'wyz_business_description', true );
		$dsc = preg_replace("/<img[^>]+\>/i", " ", $dsc);
		$dsc = preg_replace("/<div[^>]+>/", "", $dsc);
		$dsc = preg_replace("/<\/div[^>]+>/", "", $dsc);
		$dsc = wp_strip_all_tags( $dsc );
		$cntr = get_post_meta( $id, 'wyz_business_country', true );
		$cntr_link = '';
		if ( '' != $cntr && ! empty( $cntr ) ) {
			$cntr_link = get_post_type_archive_link( 'wyz_business' ) . '?location=' . $cntr;
			$cntr = get_the_title( $cntr );
		}
		$wbst = get_post_meta( $id, 'wyz_business_website', true );

		$category = self::wyz_get_category_data( $id );

		$rate_nb = get_post_meta( $id, 'wyz_business_rates_count', true );
		$rate_sum = get_post_meta( $id, 'wyz_business_rates_sum', true );
		if ( 0 == $rate_nb ) {
			$rate = 0;
		} else {
			$rate = number_format( ( $rate_sum ) / $rate_nb, 1 );
		}

		if ( ! isset( $slgn ) || empty( $slgn ) ) {
			$slgn = '';
		}
		if ( ! isset( $dsc ) || empty( $dsc ) ) {
			$dsc = '';
		}
		if ( ! isset( $cntr ) || empty( $cntr ) ) {
			$cntr = '';
		}
		if ( ! isset( $wbst ) || empty( $wbst ) ) {
			$wbst = '';
		}

		$data = array(
			'slogan' => $slgn,
			'id' => $id,
			'description' => $dsc,
			'country_name' => $cntr,
			'country_link' => $cntr_link,
			'website' => $wbst,
			'category' => $category,
			'rate_number' => $rate_nb,
			'rate' => $rate,
		);

		return $data;
	}


	/**
	 * Creates business posts.
	 *
	 * @param array   $value the business data.
	 * @param boolean $is_current_user_author wheather current user is the business owner or not.
	 * @param boolean $is_wall is current page the businesses wall.
	 */
	public static function wyz_create_post( $value, $is_current_user_author = false, $is_wall = false ) {

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_create_post') ) {
			return WyzBusinessPostOverride::wyz_create_post( $value, $is_current_user_author, $is_wall );
		}

		$template_type = 1;

		if ( function_exists( 'wyz_get_theme_template' ) )
			$template_type = wyz_get_theme_template();

		return $template_type == 1 ? self::wyz_create_post_1( $value, $is_current_user_author, $is_wall ) : self::wyz_create_post_2( $value, $is_current_user_author, $is_wall );
	}



	private static function wyz_create_post_1( $value, $is_current_user_author, $is_wall ) {

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_create_post_1') ) {
			return WyzBusinessPostOverride::wyz_create_post_1( $value, $is_current_user_author, $is_wall );
		}

		$categories = self::wyz_get_category_data( $value['business_ID'] );

		$comm_count = get_comments_number( $value['ID'] );
		if ( 0 == $comm_count ) {
			$comm_stat = esc_html__( 'no comments', 'wyzi-business-finder' );
		} else
			$comm_stat = sprintf( _n( '%d<span> comment</span>', '%d<span> comments</span>', $comm_count, 'wyzi-business-finder' ), $comm_count );

		ob_start();
		?>

		<div class="animated sin-busi-post">
			<!-- Post Head -->
			<div class="head fix">
				<?php
				if ( $is_wall ) {
					echo '<a href="' . esc_url( get_the_permalink( $value['business_ID'] ) ) . '" class="post-logo">';
				}
				if ( has_post_thumbnail( $value['business_ID'] ) ) {
					echo get_the_post_thumbnail( $value['business_ID'], 'medium', array( 'class' => 'wyz-post-thumb' ) );
				}
				if ( $is_wall ) { echo '</a>'; }
				echo '<h3>';
				if ( $is_wall ) { echo '<a href="' . esc_url( get_the_permalink( $value['business_ID'] ) ) . '">'; }
				echo  esc_html( $value['name'] );
				if ( $is_wall ) { echo '</a>'; }
				?>
				</h3>
				<?php
				if ( ! $is_wall && ( $is_current_user_author || current_user_can( 'manage_options' ) ) ) { ?>
				<i class="bus-post-x fa fa-angle-down" data-id=<?php echo json_encode( $value['ID'] ); ?> data-comm_enabled=<?php echo ( comments_open( $value['ID'] ) ? 1 : 0 );?> data-title=<?php echo json_encode( $value['name'] );?>></i>
				<?php } ?>
			</div>
			<!-- Post Content -->
			<div class="content shadow">
				<?php if ( isset( $value['post'] ) && ! empty( $value['post'] ) ) {
					echo '<p>' . $value['post'] . '</p>';
				} else {
					$value['post'] = '';
				}
				$vid = get_post_meta( $value['ID'], 'vid', true );
				if ( ! empty( $vid ) ) {
					echo $vid;
				}
				$post_image = '';
				if ( has_post_thumbnail( $value['ID'] ) ) {
					echo get_the_post_thumbnail( $value['ID'], 'large' );
					$post_image = get_the_post_thumbnail_url(  $value['ID'], 'large' );
				}
				if ( ! $post_image )$post_image = '';
				if ( '' !== $categories['icon'] ) { ?>
				<a class="busi-post-label wyz-secondary-color-text" style="background-color:<?php echo esc_attr( $categories['color'] );?>;" href="<?php echo esc_url( $categories['link'] );?>">
					<img src="<?php echo esc_url( $categories['icon'] );?>" alt="<?php echo esc_attr( $categories['name'] );?>" />
				</a>
				<?php } ?>
			</div>
			<!-- Post Footer -->
			<div class="footer fix">
				<?php
				$liked = ( is_array( $value ) && in_array( get_current_user_id(), $value['user_likes'] ) ) || $value ==  get_current_user_id();
				WyzPostShare::the_like_button( $value['ID'], 1, $value['likes'], $liked );
				if ( ! comments_open( $value['ID'] ) && 0 < get_comments_number( $value['ID'] ) ) {?>
				<div class="post-comment">
					<a href="<?php echo get_the_permalink( $value['ID'] );?>"><?php esc_html_e( 'comments closed', 'wyzi-business-finder' )?></a>
					<span><?php echo $comm_stat;?></span>
				</div>
				<?php } elseif ( comments_open( $value['ID'] ) ) {?>
				<div class="post-comment">
					<a href="<?php echo get_the_permalink( $value['ID'] );?>"><?php esc_html_e( 'comments', 'wyzi-business-finder' )?></a>
					<span><?php echo $comm_stat;?></span>
				</div>
				<?php }
				WyzPostShare::the_share_buttons( $value['ID'] );
				?>
				<span class="date"><?php echo esc_html( $value['time'] ); ?></span>


			</div>
			<?php self::get_post_comments();?>
			<div class="post-footer-comments">
				<?php if ( comments_open( $value['ID'] ) ) {?>
				<div class="post-footer-comment-form">
					<input type="text" class="wyz-input post_footer_comment_content" placeholder="<?php esc_html_e( 'post a comment','wyzi-business-finder' );?>..."/>
					<button class="btn-square wyz-primary-color wyz-prim-color post_footer_comment_btn" data-id="<?php echo $value['ID'];?>"><?php esc_html_e( 'Comment', 'wyzi-business-finder' );?></button>
					<input type="hidden" class="wyz_business_post_comment_nonce" value="<?php echo wp_create_nonce( 'wyz-business-post-comment-nonce-' . $value['ID'] ); ?>"/>
				</div>
				<?php }?>
				<div class="the-post-comments">
				<?php self::display_post_comments( $value['ID'] );?>
				</div>
			</div>

		</div>
		<?php
		return ob_get_clean();
	}



	private static function wyz_create_post_2( $value, $is_current_user_author, $is_wall ) {

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_create_post_2') ) {
			return WyzBusinessPostOverride::wyz_create_post_2( $value, $is_current_user_author, $is_wall );
		}

		$categories = self::wyz_get_category_data( $value['business_ID'] );

		$comm_count = get_comments_number( $value['ID'] );
		if ( 0 == $comm_count ) {
			$comm_stat = esc_html__( 'no comments', 'wyzi-business-finder' );
		} else
			$comm_stat = sprintf( _n( '%d<span> comment</span>', '%d<span> comments</span>', $comm_count, 'wyzi-business-finder' ), $comm_count );

		ob_start();
		?>

		<div class="wall-post mb-20">
			<!-- Wall Post Head -->
			<div class="head">
				<?php
				if ( $is_wall ) {
					echo '<a href="' . esc_url( get_the_permalink( $value['business_ID'] ) ) . '" class="company-logo">';
				} else {
					echo '<span class="company-logo">';
				}
				if ( has_post_thumbnail( $value['business_ID'] ) ) {
					echo get_the_post_thumbnail( $value['business_ID'], 'medium', array( 'class' => 'wyz-post-thumb' ) );
				}
				if ( $is_wall ) { echo '</a>'; }
				else { echo '</span>'; }?>
				<div class="head-content">
				<h3 class="title">
					<?php if ( $is_wall ) echo '<a href="' . esc_url( get_the_permalink( $value['business_ID'] ) ) . '">';
					echo  esc_html( $value['name'] );
					if ( $is_wall ) { echo '</a>'; } ?>
				</h3>
				<?php echo '<p class="author">' . esc_html__( 'Post by', 'wyzi-business-finder' ) . ': <a href="' . esc_url( get_the_permalink( $value['business_ID'] ) ) . '">' . get_the_author_meta( 'display_name', get_post_field( 'post_author', $value['ID'] ) ) . '</a></p>';
				echo '<p class="category"><a href="'.esc_url( $categories['link'] ).'">'.esc_attr( $categories['name'] ).'</a></p>';?>
				</div>
				<?php
				if ( ! $is_wall && ( $is_current_user_author || current_user_can( 'manage_options' ) ) ) { ?>
				<i class="bus-post-x fa fa-angle-down" data-id=<?php echo json_encode( $value['ID'] ); ?> data-comm_enabled=<?php echo ( comments_open( $value['ID'] ) ? 1 : 0 );?> data-title=<?php echo json_encode( $value['name'] );?>></i>
				<?php } ?>
			</div>

			<?php
			if ( has_post_thumbnail( $value['ID'] ) ) {
				echo '<span class="image">' . get_the_post_thumbnail( $value['ID'], 'large' ) . '</span>';
			}?>

			<!-- Wall Post Content -->
			<div class="content">
				<?php if ( isset( $value['post'] ) && ! empty( $value['post'] ) ) {
					echo '<p>' . $value['post'] . '</p>';
				} else {
					$value['post'] = '';
				}
				$vid = get_post_meta( $value['ID'], 'vid', true );
				if ( ! empty( $vid ) ) {
					echo $vid;
				}?>
			</div>
			<!-- Wall Post Footer -->
			<div class="footer fix">
				<?php
				$liked = ( is_array( $value ) && in_array( get_current_user_id(), $value['user_likes'] ) ) || $value ==  get_current_user_id();
				WyzPostShare::the_like_button( $value['ID'], 2 , $value['likes'], $liked);

				if ( ! comments_open( $value['ID'] ) && 0 < get_comments_number( $value['ID'] ) ) {?>
				<a class="wyz-prim-color-txt" href="<?php echo get_the_permalink( $value['ID'] );?>" title="<?php esc_html_e( 'comments closed', 'wyzi-business-finder' )?>" class="wall-no-comments"><i class="fa fa-reply"></i><span><?php echo $comm_stat;?></span></a>
				<?php } elseif ( comments_open( $value['ID'] ) ) {?>
				<a class="wyz-prim-color-txt" href="<?php echo get_the_permalink( $value['ID'] );?>"><i class="fa fa-reply"></i><span><?php echo $comm_stat;?></span></a>
				<?php } ?>
				<?php WyzPostShare::the_share_buttons( $value['ID'], 2 );?>
			</div>

			<?php self::get_post_comments();?>
			<div class="post-footer-comments">
				<?php if ( comments_open( $value['ID'] ) ) {?>
				<div class="post-footer-comment-form">
					<input type="text" class="wyz-input post_footer_comment_content" placeholder="<?php esc_html_e( 'post a comment','wyzi-business-finder' );?>..."/>
					<button class="action-btn bg-grey wyz-prim-color-hover post_footer_comment_btn" data-id="<?php echo $value['ID'];?>"><?php esc_html_e( 'Comment', 'wyzi-business-finder' );?></button>
					<input type="hidden" class="wyz_business_post_comment_nonce" value="<?php echo wp_create_nonce( 'wyz-business-post-comment-nonce-' . $value['ID'] ); ?>"/>
				</div>
				<?php }?>
				<div class="the-post-comments">
				<?php self::display_post_comments( $value['ID'] );?>
				</div>
			</div>

		</div>
		<?php
		return ob_get_clean();
	}



	private static function display_post_comments( $post_id ) {

		if ( method_exists( 'WyzBusinessPostOverride', 'display_post_comments') ) {
			return WyzBusinessPostOverride::display_post_comments( $post_id );
		}

		foreach ( self::$comments as $comment) {
			echo self::get_the_comment( $comment );
		}
		if ( 1 < get_comments_number( $post_id ) ) {?>
			<div class="the-comment the-comment-more">
				<div class="com-header">
					<span class="com-name wyz-primary-color-text wyz-prim-color-txt"><a class="com-name com-view-more wyz-primary-color-text wyz-prim-color-txt" data-id="<?php echo $post_id;?>" data-offset="1" href="#"><?php esc_html_e( 'View All', 'wyzi-business-finder' );?></a></span>
				</div>
			</div>
		<?php }
	}



	private static function comments_empty() {
		return empty( $this->comments );
	}

	private static function get_post_comments() {
		$args = array(
			'status' => 'approve',
			'number' => '1',
			'post_id' => get_the_ID(),
		);
		self::$comments = get_comments( $args );
	}


	public static function get_the_comment( $comment ) {

		if ( method_exists( 'WyzBusinessPostOverride', 'get_the_comment') ) {
			return WyzBusinessPostOverride::get_the_comment( $comment );
		}

		ob_start();?>
		<div class="the-comment">
			<div class="com-header">
				<?php $user = get_user_by( 'login', $comment->comment_author );
				$avatar = '';
				if ( $user ) {
					$username = $user->display_name;
					$avatar = get_avatar( $user->ID, 30, false, $username );
					echo $avatar;
				}
				else {
					$username = '';
				}?>
				<span class="com-name wyz-primary-color-text wyz-prim-color-txt"><?php echo $username;?></span>
				<span class="com-date"><?php WyzHelpers::the_publish_date( $comment->comment_date_gmt );?></span>
			</div>
			<div class="com-content<?php echo !empty($avatar)?' has-avatar':'';?>"><p><?php echo $comment->comment_content;?></p></div>
		</div>
		<?php
		return ob_get_clean();
	}



	private static function wyz_get_business_header( $is_grid , $business_data = ''){

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_get_business_header') ) {
			return WyzBusinessPostOverride::wyz_get_business_header( $is_grid );
		}

		$sticky = is_sticky();
		ob_start();?>
		<div class="sin-bordes sin-busi-post<?php echo $is_grid ? ' bus-post-grid' : '';
									   echo $sticky ? ' bus-sticky' : '';?> sin-busi-item" style="border: 0px !important;">
			<div class="head fix con-bordes" style="border: 1px solid #ececec !important; position: relative;">
			<?php if ( $sticky ) {?>
					<div class="sticky-notice featured-banner"><span class="wyz-primary-color wyz-prim-color"><?php esc_html_e( 'FEATURED', 'wyzi-business-finder' );?></span></div>
			<?php }?>
			<?php if ( has_post_thumbnail() ) {?>
				<a href="<?php echo get_the_permalink();?>" class="post-logo"><?php the_post_thumbnail( 'medium' );?></a>
			<?php } ?>
				<h3 style="padding: 0 5px;"><a href="<?php echo get_the_permalink();?>"><?php the_title();?></a></h3>
				<div class="" style="position: absolute; bottom: 3px; right: 15px;">
						<?php
							$business_id = $business_data['id'];
							$mapGPS = get_post_meta($business_id,'wyz_business_location',true);
							$lat = $mapGPS['latitude'];
							$lon = $mapGPS['longitude'];
						 ?>

						 	<a href="<?php echo get_the_permalink();?>" class="btn btn-danger btn-xs" title="Leer Más">
								<span class="dashicons dashicons-info"></span>  Leer Más
							</a>

						<a href="https://www.google.com/maps?daddr=<?php echo $lat; ?>,<?php echo $lon; ?>" class="btn btn-primary btn-xs" title="Cómo Llegar">
						<span class="dashicons dashicons-admin-site"></span> Cómo Llegar
						</a>
				</div>

			</div>
		<?php
		return ob_get_clean();
	}



	private static function wyz_get_business_content( $business_data, $is_grid ){

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_get_business_content') ) {
			return WyzBusinessPostOverride::wyz_get_business_content( $business_data, $is_grid );
		}

		ob_start();
		$excerpt_len = $is_grid ? 150 : 230;?>
		<div class="content con-bordes" style="padding: 0px !important; border: 1px solid #ececec !important; margin-top: 15px !important; height: 200px !important;" >

			<?php if ( '' != $business_data['description'] ) { ?>
				<p><?php echo WyzHelpers::substring_excerpt( $business_data['description'], $excerpt_len );//substr( $business_data['description'] , 0, $excerpt_len );?></p>
				<a class="read-more wyz-secondary-color-text" href="<?php echo esc_attr( get_permalink() );?>"><?php esc_html_e( 'read more', 'wyzi-business-finder' )?></a>
			<?php }?>

			<?php

					$meta_custom = '';
					$meta_custom = get_post_meta( $business_data['id'], 'wyzi_claim_fields_0' , true );
					$content = get_post_field('post_content', $business_data['id']);
					echo do_shortcode($meta_custom);
			?>

			<?php if ( '' !== $business_data['category']['icon'] ) { ?>
				<a class="busi-post-label" style="display:none; background-color:<?php echo esc_attr( $business_data['category']['color'] );?>;" href="<?php echo esc_url( $business_data['category']['link'] );?>">
					<img src="<?php echo esc_url( $business_data['category']['icon'] );?>" alt="<?php echo esc_attr( $business_data['category']['name'] );?>" />
				</a>
			<?php }?>
			</div>

		<?php
		return ob_get_clean();
	}



	private static function wyz_get_business_footer( $business_data ){

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_get_business_footer') ) {
			return WyzBusinessPostOverride::wyz_get_business_footer( $business_data );
		}

		ob_start();?>
			<div class="footer fix"></div>
		</div>
		<?php
		return ob_get_clean();
	}




	/**
	 * Creates business to display in a business archives page.
	 */
	public static function wyz_create_business() {

		if ( method_exists( 'WyzBusinessPostOverride', 'wyz_create_business') ) {
			return WyzBusinessPostOverride::wyz_create_business();
		}

		$business_data = self::wyz_get_business_data( get_the_ID() );
		return self::wyz_get_business_header( false , $business_data) . self::wyz_get_business_content( $business_data, false ) . self::wyz_get_business_footer( $business_data );
	}





	public static function wyz_create_business_grid_look() {



		$business_data = self::wyz_get_business_data( get_the_ID() );
		$sticky = is_sticky();
		if ($sticky) {
			return self::custom_get_business_listable( true , $business_data );
		} else {
			return self::custom_get_business_listable( true , $business_data );
		}

	}

	private static function custom_get_business_listable( $is_grid , $business_data = ''){

		$sticky = is_sticky();
		ob_start();?>

						<a href="<?php echo esc_attr( get_permalink() );?>" class="grid__item  grid__item--widget" style="padding-left: 15px !important; padding-right: 15px !important; height: 440px !important">
							<article class="card  card--listing  card--widget  " data-latitude="1.0"
							         data-longitude="2.0"
							         data-img="imagen"
							         data-permalink="url">

							<?php

									$meta_custom = '';
									$meta_custom = get_post_meta( $business_data['id'], 'wyzi_claim_fields_0' , true );
									if ('' != $meta_custom) {
										echo do_shortcode($meta_custom);
									} else { ?>
																		<aside class="card__image" style="">
								<?php if ( $sticky ) {?>
															<span class="card__featured-tag"><?php esc_html_e( 'FEATURED', 'wyzi-business-finder' );?></span>
													<?php }?>

								</aside>
									<?php }

								?>


								<div class="card__content">
									<h2 class="card__title" itemprop="name" ><?php the_title();?></h2>
									<?php
									$bldg = get_post_meta( $business_data['id'], 'wyz_business_bldg' , true );
									$street = get_post_meta( $business_data['id'], 'wyz_business_street' , true );
									$city = get_post_meta( $business_data['id'], 'wyz_business_city' , true );
									?>
									<div class="card__tagline" style="font-size: 0.7em !important; overflow: hidden"><?php echo $bldg.'  '.$street.' '.$city; ?></div>
									<footer class="card__footer">

											<ul class="card__tags">
													<li>
														<div class="card__tag">
															<div class="pin__icon">
																<svg width="18" height="26" viewBox="0 0 18 26" xmlns="http://www.w3.org/2000/svg"><g transform="translate(-16 -11)" fill="none" fill-rule="evenodd"><circle stroke="currentColor" stroke-width="2" cx="24" cy="24" r="24"></circle><path d="M18.92 26.79c.11.016.227.03.34.044-.474.387-.843.87-1.017 1.395l-.01.027-2.212 8.043c-.08.295.093.6.388.68.05.013.098.02.147.02.243 0 .466-.162.533-.407l2.21-8.028c.256-.747 1.21-1.406 2.137-1.513-.15.224-.27.474-.335.74l-1.197 5.83c-.046.224.1.443.323.49.028.005.056.008.084.008.193 0 .366-.136.406-.332l1.194-5.813c.087-.353.34-.687.634-.887.704.027 1.437.042 2.19.042.756 0 1.49-.015 2.193-.042.294.2.545.528.63.87l1.197 5.83c.04.196.213.332.406.332.028 0 .056-.003.084-.01.225-.045.37-.264.323-.49l-1.2-5.845c-.065-.26-.18-.503-.33-.722.926.107 1.88.766 2.136 1.512l2.21 8.028c.067.246.29.407.532.407.05 0 .098-.006.147-.02.295-.08.468-.385.387-.68l-2.215-8.043-.008-.028c-.175-.526-.543-1.008-1.02-1.396.116-.015.233-.028.342-.044 1.975-.284 2.82-.67 2.91-1.33.11-.78-.867-1.17-1.837-1.446-2.995-.855-3.438-1.423-3.49-1.628-.073-.295.565-.91 1.128-1.455.702-.677 1.575-1.522 2.11-2.594 1.06-2.13.632-3.628.086-4.51C30.164 11.74 27.028 11 24.736 11c-2.294 0-5.43.74-6.722 2.827-.546.88-.974 2.38.087 4.51.47.942 1.177 1.713 1.802 2.392.602.655 1.225 1.332 1.118 1.71-.076.27-.59.836-3.174 1.574-.97.277-1.947.665-1.838 1.447.093.66.937 1.046 2.913 1.33zm-.77-1.712c2.497-.713 3.674-1.412 3.935-2.34.274-.97-.487-1.798-1.368-2.757-.575-.625-1.227-1.334-1.626-2.137-.668-1.344-.714-2.5-.136-3.433.986-1.59 3.614-2.304 5.78-2.304 2.17 0 4.797.713 5.782 2.304.578.934.532 2.09-.137 3.433-.45.902-1.214 1.64-1.888 2.293-.89.86-1.66 1.604-1.43 2.52.234.934 1.428 1.614 4.258 2.422.352.1.595.19.76.26-.64.225-2.057.47-4.175.596-.042 0-.084-.005-.126-.005-.05 0-.096.007-.14.02-.862.046-1.83.074-2.905.074-1.076 0-2.043-.03-2.904-.075-.044-.013-.09-.02-.14-.02-.04 0-.083.003-.125.004-2.118-.127-3.536-.37-4.175-.595.165-.073.408-.162.76-.262z" fill="currentColor"></path></g></svg>
															</div>
														</div>
													</li>

													<li>

														<div class="card__tag">
															<div class="pin__icon">
																<svg width="22" height="22" viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg"><g transform="translate(-14 -12)" fill="none" fill-rule="evenodd"><circle stroke="currentColor" stroke-width="2" cx="24" cy="24" r="24"></circle><path d="M33.333 19.065h-4V17.03c0-2.602-2.18-5.03-4.766-5.03C21.98 12 20 14.428 20 17.03v2.035h-4l-2 14.912h22l-2.667-14.912zm-12-2.033c0-1.998 1.248-3.462 3.234-3.462 1.985 0 3.433 1.464 3.433 3.462v2.033h-6.667v-2.033zm-4 3.388H20v.68c-.36.233-.6.64-.6 1.104 0 .722.58 1.308 1.292 1.308.714 0 1.292-.586 1.292-1.308 0-.486-.262-.91-.65-1.135v-.65H28v.91c-.206.23-.333.537-.333.874 0 .722.578 1.308 1.292 1.308.712 0 1.29-.586 1.29-1.308 0-.59-.386-1.09-.916-1.252v-.532H32l2 12.2H16l1.334-12.2z" fill="currentColor"></path></g></svg>															</div>
														</div>

													</li>

											</ul>


											<div class="address  card__address">
			<?php if ( has_post_thumbnail() ) {?>
				<?php the_post_thumbnail( 'medium' );?>
			<?php } ?>

											</div>

									</footer>
								</div><!-- .card__content -->
							</article><!-- .card.card--listing -->
						</a><!-- .grid_item -->
		<?php
		return ob_get_clean();
	}

	/*public static function is_business_open( $business_id ) {
		//D	A textual representation of a day, three letters
		$days = WyzHelpers::get_days( $business_id );
		$days = $days[1];
		$key_arr = array(
			'Mon' => 0,
			'Tue' => 1,
			'Wed' => 2,
			'Thu' => 3,
			'Fri' => 4,
			'Sat' => 5,
			'Sun' => 6,
		);
		$now_day = $days[ date( 'D' ) ];
		if ( ( ! isset( $now_day[ 'open' ] ) || empty( $now_day[ 'open' ] ) ) && ( ! isset( $now_day[ 'close' ] ) || empty( $now_day['close'] ) ) ) return false;
		$_12_format = ( false !== strpos( $now_day['open'], 'AM' ) || false !== strpos( $now_day['open'], 'PM' ) || false !== strpos( $now_day['close'], 'PM' ) || false !== strpos( $now_day['close'], 'PM' ) );

		$start_time = '';
		$end_time = '';

		if ( $_12_format ) {

		}
	}*/
}
?>
