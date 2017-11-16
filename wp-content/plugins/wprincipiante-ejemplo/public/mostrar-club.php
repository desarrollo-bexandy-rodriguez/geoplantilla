<?php
/**
 * [Short Description]
 *
 * [Long Description.]
 *
 * @since [1.0]
 *
 * @package [package Name]
 * @subpackage [subpackage Name]
 */

function my_the_post_action( $post_object ) { ?>

      <?php if ( has_post_thumbnail($post_object) ) {?>
        <a href="<?php echo get_the_permalink($post_object);?>" class="post-logo"><?php the_post_thumbnail( 'medium' );?></a>
      <?php } ?>
  <div class="panel panel-default">
  <div class="panel-heading">
  <h3 style="padding: 0 5px;"><a href="<?php echo get_the_permalink($post_object);?>"><?php the_title();?></a></h3>
  </div>
  <div class="panel-body">
    Panel content
  </div>
</div>

<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Panel title</h3>
  </div>
  <div class="panel-body">
    Panel content
  </div>
</div>

<?php
}
add_action( 'the_post', 'my_the_post_action' );



