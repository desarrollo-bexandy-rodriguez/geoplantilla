<?php 
    $args = new stdClass();
    $args->settings = $params;
    $args->key = isset( $_GET['tab'] ) ? $_GET['tab'] : 'rpadv_settings';
    $args->tabs = $args->settings->getTabs();
    $args->fieldSet = $args->settings->getFieldSet();
    $args->data = $args->settings->getSettings($args->key);
    $args->fields = $args->settings->getFields($args->key);
    $title = !empty($args->settings->getConfig()->admin->options->title) ? $args->settings->getConfig()->admin->options->title : '';
?>
<?php if (!empty($title)) :?>
<div class="wcp-visual-adverts-headline">
    <table>
        <tr>
            <td class="wcp-visual-adverts-headline-icon">                                                                                               
                <img src="<?php echo RPAdv()->getAssetUrl( 'images/icons/icon-128x128.png' ); ?>" width="128" height="128" />    
            </td>
            <td class="wcp-visual-adverts-headline-info">
                <h1><?php echo $title;?></h1>
                <p>WCP Visual Adverts plugin allows you to quickly and easily create different types of advertisements and group them into categories to display in the sidebar of your site.</p> 
                <p>If you really like our plugin, please <a class="scfp-plugin-headline-rate" title="Rate Our Plugin" target="blank" href="https://wordpress.org/support/view/plugin-reviews/wp-visual-adverts?filter=5#postform">rate us</a>!</p>
            </td>
            <td class="wcp-visual-adverts-headline-links">
                <div class="wcp-visual-adverts-headline-links-wrapper">
                    <h2>Useful Links</h2>
                    <ul>
                        <li><a href="http://wpdemo.webcodin.com/wordpress-plugin-wcp-visual-adverts/documentation/getting-started/" target="_blank" title="Documentation"><span class="dashicons dashicons-book"></span> Documentation</a></li>
                        <li><a href="http://wpdemo.webcodin.com/wordpress-plugin-wcp-visual-adverts/documentation/faq/" target="_blank" title="FAQ"><span class="dashicons dashicons-info"></span> FAQ</a></li>  
                        <li><a href="http://wpdemo.webcodin.com/stay-in-touch/" target="_blank" title="Support Form"><span class="dashicons dashicons-sos"></span> Support Plugin</a></li>
                        <li><a href="http://wpdemo.webcodin.com/wordpress-plugin-wcp-visual-adverts/" target="_blank" title="Live Demo"><span class="dashicons dashicons-images-alt2"></span> Live Demo</a></li>
                    </ul>                 
                </div>
            </td>
        </tr>
    </table>
</div>
<?php endif;?>

<div class="wrap wcp-visual-adverts-form-wrap">
    <?php 
        screen_icon();
        settings_errors();
        
        echo $args->settings->getParentModule()->getTemplate('admin/options/render-tabs', $args);
    ?>
    <form method="post" action="options.php">
        <?php wp_nonce_field( 'update-options' ); ?>
        <?php settings_fields( $args->key ); ?>
        
        <?php echo $args->settings->getParentModule()->getTemplate('admin/options/render-page', $args); ?>
        
        <p class="submit">
            <input id="submit" class="button button-primary" type="submit" value="Save Changes" name="submit">
            <a class="button button-primary" href="?page=<?php echo $args->settings->getPage();?>&tab=<?php echo $args->key;?>&reset-settings=true" >Reset to Default</a>
        </p>
    </form>
</div>