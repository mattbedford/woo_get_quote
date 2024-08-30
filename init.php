<?php

/**
 * Plugin Name: Woo Get Quote
 * Description: A WooCommerce plugin that creates a kinda-shopping-cart get quote system.
 * Version:     1.0.0
 * Author:      Dev Team
 * Text Domain: do-woo-like
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

namespace WooGetQuote;

if (!defined('ABSPATH')) {
    exit;
}

class InitWooGetQuote
{
    public function init()
    {

        include_once 'includes.php';
        include_once 'rest.php';

        if (!class_exists('WooCommerce')) {
            return;
        }

        if (!is_user_logged_in()) {
        } else {
            add_action('rest_api_init', [new rest(), 'registerLoggedInRoute']);
        }

        add_action('wp_enqueue_scripts', [Includes::class, 'enqueueScripts']);
        add_action('woocommerce_before_shop_loop_item', [Includes::class, 'heartHtml'], 80);
    }
}
add_action('init', [new InitWooGetQuote(), 'init']);
