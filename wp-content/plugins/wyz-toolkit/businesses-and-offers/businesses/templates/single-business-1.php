<?php

/**
 * Get business map.
 */
$business_data->the_business_map(); ?>

<!-- Slider Publicidad
============================================ -->
<div class="publicidad sigle-superior">
	<?php
		$banner_superior = '';
		$banner_superior = get_post_meta( $id, 'wyzi_claim_fields_1' , true );
		echo do_shortcode($banner_superior);
?>
</div>

<?php WyzHelpers::wyz_the_business_subheader( $id );?>

<!-- Slider Secundario
============================================ -->
<div class="publicidad single-inferior">
	<?php
		$banner_inferior = '';
		$banner_inferior = get_post_meta( $id, 'wyzi_claim_fields_2' , true );
		echo do_shortcode($banner_inferior);
?>
</div>

<!-- Business Tab Area
============================================ -->
<div class="business-tab-area margin-bottom-100">
	<div class="container">
		<div class="row">
			<!-- Business Tab List -->
			<div class="business-tab-list col-xs-12" <?php if ('' != $banner_inferior) echo 'style="margin-top: 0px !important;"'?>>
				<ul id="business-tabs">
				<?php $business_data->the_tabs();?>
				</ul>
			</div>
			<?php if ( 'on' == get_option( 'wyz_allow_business_post_edit' ) ) {
				require_once( $business_path . 'forms/post-edit-form.php');
			}?>
			<!-- Business Content Area -->
			<div class="business-sidebar-content-area margin-top-50">
				<!-- Business Sidebar -->
				<?php WyzHelpers::the_business_sidebar( $id );?>
				<div class="<?php echo ( 'on' === wyz_get_option( 'resp' ) ? 'col-md-9 col-xs-12' : 'col-xs-9');?>">
					<!-- Business Tab Content -->
					<div class="tab-content">

					<?php $business_data->the_tabs_content(); ?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>
