<?php
    global $post;
    $link = get_post_meta($post->ID, 'rpadv_link', true);
    $link_target = get_post_meta($post->ID, 'rpadv_link_target', true);
    if (empty($link_target)) {
        $link_target = '_blank';
    }
    
    $targets = array(
        '_blank' => '_blank',
        '_self' => '_self',
    );
?>
<input type="hidden" name="rpadv_link_noncename" id="rpadv_link_noncename" value="<?php echo wp_create_nonce( RPAdv()->getBaseDir() );?>" />
<div class="rpadv-row">
    <label for="rpadv_link">URL:</label>
    <input type="text" id="rpadv_link" name="rpadv_link" value="<?php echo $link;?>" class="widefat" />
</div>
<br/>
<div class="rpadv-row">
    <label for="rpadv_link_target">Target:</label>
    <select id="rpadv_link_target" name="rpadv_link_target" class="widefat">
        <?php 
            foreach ($targets as $k => $v):
                $selected = $k == $link_target;
        ?>
            <option value="<?php echo $k; ?>"<?php selected( $selected );?>><?php echo $v;?></option>
        <?php 
            endforeach; 
        ?>
    </select>
</div>
