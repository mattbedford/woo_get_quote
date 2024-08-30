<?php

namespace WooGetQuote;

// This is a kinda singleton to contain all the peripheral methods.
class Includes
{
    

    public static function Enqueue()
    {
        
        if(!is_user_logged_in()) return;

        wp_enqueue_style('wgq-style', plugin_dir_url(__FILE__) . '/assets/style.css');
      	wp_enqueue_script('wgq-scripts', plugin_dir_url(__FILE__) . '/assets/scripts.js', [], '1.0.0', true);
        
        wp_register_script('wgq-rest', false);
        $rest_args = [
            'security' => wp_create_nonce('wp_rest'),
            'url' => rest_url('wgq/v1/'),
        ];
        
        wp_localize_script('wgq-rest', 'rest_obj', $rest_args);
      	wp_enqueue_script('wgq-rest');
    }

}



