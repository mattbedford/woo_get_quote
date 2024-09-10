<?php

/**
 * Plugin Name: Woo Get Quote
 * Description: A WooCommerce plugin that creates a kinda-shopping-cart get quote system.
 * Version:     1.4.0
 * Author:      Dev Team
 * Text Domain: do-woo-like
 * License:     GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Uses a class (added, eg. to nav menu item) to create a "generate quote request" for logged-in users.
// Reqs:
// ACF, to create block; WooCommerce for the display.
// A link somewhere with "wgq-quote-button" class.
// A page ("preventivo") to display the quote alongside a form. Currently set to work with CF7, but could be anything.
// Uses WooCommerce hooks to generate a get-quote button on product page. Can be customized.

namespace WooGetQuote;

if (!defined('ABSPATH')) {
    exit;
}


class InitWooGetQuote
{
    public function init()
    {

		if(!defined("WooGetQuote\QUOTEPAGE")) define("WooGetQuote\QUOTEPAGE", "preventivo");
		
        include_once 'includes.php';
		include_once 'quote.php';
        include_once 'rest.php';
		include_once 'button.php';
		include_once 'wgq-block/template.php';
		self::RegisterQuoteItemsBlock();

        if (!class_exists('WooCommerce')) {
            return;
        }
						
        if (is_user_logged_in()) {
			
            add_action('rest_api_init', [new rest(), 'Register']);
			add_action('wp_enqueue_scripts', [Includes::class, 'Enqueue']);
    		add_action('wp_enqueue_scripts', [Includes::class, 'CheckForQuoteItemsBlock']);
    		add_action('admin_enqueue_scripts', [Includes::class, 'CheckForQuoteItemsBlock']);
			add_action('template_redirect', [Includes::class, 'AddSingleProduct'], 20);

        }

		add_filter('wp_nav_menu_items', [Includes::class, 'NavMenuQuoteButton'], 10, 2);
        add_action( 'woocommerce_single_product_summary', [Button::class, 'Html'], 75 );
    }
	
	
	
	public static function RegisterQuoteItemsBlock() {
			
      	register_block_type( dirname(__FILE__) . '/wgq-block' );
	}
	
	
}
add_action('init', [new InitWooGetQuote(), 'init']);


