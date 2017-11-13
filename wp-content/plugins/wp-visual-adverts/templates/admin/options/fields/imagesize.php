<?php 
    $args = $params;
    $label = !empty($args->fields['fields'][$args->field]['label']) ? $args->fields['fields'][$args->field]['label'] : '';
    $class = !empty($args->fields['fields'][$args->field]['class']) ? $args->fields['fields'][$args->field]['class'] : ''; 
    $note = !empty($args->fields['fields'][$args->field]['note']) ? $args->fields['fields'][$args->field]['note'] : '';
    $atts = !empty($args->fields['fields'][$args->field]['atts']) ? $args->fields['fields'][$args->field]['atts'] : '';
    if (is_array($atts)) {
        $atts_s = '';
        foreach ($atts as $key => $value) {
            $atts_s .= $key . '="' . $value . '"';
        }
        $atts = $atts_s;
    }
    
    if (empty($args->data[$args->field])) {
        $options = get_option('rpadv_settings');
     
        $args->data[$args->field] = array(
            'width' => $options['rpadv-image-width'],
            'height' => $options['rpadv-image-height'],
            'crop' => !empty($options['rpadv-image-crop']) ? '1' : '',            
        );
    }

    $value = $args->data[$args->field];
?>
<tr>
    <th scope="row"><?php echo $label;?></th>
    <td class="wcp-visual-adverts-field-imagesize">
        <div class="wcp-visual-adverts-field-settings-row">        
            <label for="rpadv_settings[rpadv-image][width]">Width</label>
            <input min="0" step="1" type="number" class="small-text" name="rpadv_settings[rpadv-image][width]" value="<?php echo $value['width'];?>"  />                        
            <label for="rpadv_settings[rpadv-image][height]">Height</label>                        
            <input min="0" step="1" type="number" class="small-text" name="rpadv_settings[rpadv-image][height]" value="<?php echo $value['height'];?>"  />                               
            <?php if (!empty($note)): ?>
                <div class="wcp-visual-adverts-field-settings-notice">
                    <span class="dashicons dashicons-editor-help"></span>
                    <p class="description"><?php echo $note;?><span class="dashicons dashicons-no-alt"></span></p>
                </div> 
            <?php endif;?>  
            <div class="wcp-visual-adverts-crop-row">
                <input type="checkbox" value="1" name="rpadv_settings[rpadv-image][crop]" <?php echo checked( 1, (isset($value['crop'])) ? 1 : '' , false ); ?> >
                <label for="rpadv_settings[rpadv-image][crop]">Crop thumbnail to exact dimensions</label> 
            </div>
        </div>
    </td>
</tr>    
