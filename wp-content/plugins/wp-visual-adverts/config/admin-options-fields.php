<?php
return array(
    'rpadv_settings' => array(
        'sections' => array(
            'defaults' => array(
                'label' => 'General Settings',
            ),            
            'other_settings' => array(
                'label' => 'Other Settings',
            ),                                    
        ),
        'fields' => array(
            'rpadv-image' => array(
                'label' => 'Image Size',            
                'type' => 'imagesize',                        
                'default' =>  array(
                    'width' => 400,
                    'height' => 250,
                    'crop' => 1,
                ),
                'section' => 'defaults',
                'class' => '',
                'note' => 'option allows to set custom size of the advert images.<br /><strong>NB!</strong> Please use "Regenerate Thumbnails" plugin or similar one each time after customization of this option',
            ),                        
            'rpadv-refresh-time' => array(
                'type' => 'number',
                'label' => 'Refresh Time (msec)',
                'default' => 5000,
                'section' => 'defaults',
                'class' => 'text',
                'note' => 'option allows to set custom time between ads updates; in order if you need to disable ads refreshing, you can set this option to zero value',
                'atts' => array(
                    'min' => 0,
                ),                
            ),
            'rpadv-animation-speed' => array(
                'type' => 'number',
                'label' => 'Animation Speed (msec)',
                'default' => 1000,
                'section' => 'defaults',
                'class' => 'text',
                'note' => 'option allows to set speed for fade in/fade out animation effect',
                'atts' => array(
                    'min' => 0,
                ),             
            ),            
            'rpadv-advert-count' => array(
                'type' => 'number',
                'label' => 'Advert Count',
                'default' => 3,
                'section' => 'defaults',
                'class' => 'text',
                'note' => 'option allows to set number of displayed ads inside groups',
                'atts' => array(
                    'min' => 0,
                ),             
            ),            
            'scripts_in_footer' => array(
                'type' => 'checkbox',
                'label' => 'Footer Scripts',
                'default' => 0,
                'section' => 'other_settings',
                'note' => 'option allows to enqueue scripts and styles only for the pages with adverts',
                'class' => '',
            ),
        ),
    ),
);