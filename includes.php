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
		wp_enqueue_script('wgq-api', plugin_dir_url(__FILE__) . '/assets/api.js', [], '1.0.0', true);
		        
        wp_register_script('wgq-rest', false);
        $rest_args = [
            'security' => wp_create_nonce('wp_rest'),
            'url' => rest_url('wgq/v1/'),
        ];
        
        wp_localize_script('wgq-rest', 'rest_obj', $rest_args);
      	wp_enqueue_script('wgq-rest');
    }
	
	
	public static function CheckForQuoteItemsBlock(): void
	{
		
		if ( has_block("acf/wgq-block") ) {   
	   		wp_register_style('wgq-block-custom-styles', plugin_dir_url(__FILE__) . 'wgq-block/style.css');
			wp_register_script('wgq-block-retrieve-list', plugin_dir_url(__FILE__) . 'wgq-block/retrieve.js');
       		wp_enqueue_script('wgq-block-retrieve-list');
			wp_enqueue_style('wgq-block-custom-styles');
    	} 
	}
	
	
	public static function AddSingleProduct()
	{
		
		if(!is_page('preventivo') || !isset($_REQUEST["product_id"])) return;
		if(!isset($_REQUEST["security"]) || !wp_verify_nonce($_REQUEST["security"], 'wgq')) return;
			
		$prod_id = strip_tags(intval($_REQUEST["product_id"]));		
		$quote_addition = new \Quote();
		$quote_addition->Add([ "product_id" => $prod_id, "quantity" => 1 ]);
		$url = site_url() . '/preventivo/';
		
		nocache_headers(); 
		wp_safe_redirect($url, '302');
		exit();
		
	}
	
	
	public static function NavMenuQuoteButton($items, $args)
	{
		if(is_page('preventivo')) return $items;
		
		$atts = [
			"href" => esc_html('/contatti'),
			"title" => esc_html__('Richiedi un preventivo', 'WooGetQuote'),
			"badge" => '',
			"classes" => [
				"preventivo-nav-button",
				"wgq-quote-button",
				"menu-item",
				"menu-item-type-post_type", 
				"menu-item-object-page",
			],
		];
		
		if(is_user_logged_in()) {
			
			$atts['href'] = esc_html('/preventivo');
			$atts['badge'] = self::QuoteButtonBadge();
		}
		
		$items .= '<li class="' . implode(" ", $atts["classes"]) . '">';
		$items .= '<a href="' . $atts['href'] . '">' . $atts['title'] . '</a>';
		$items .= $atts['badge'];
		$items .= '</li>';
		
		return $items;
		
	}
	
	
	public static function QuoteButtonBadge()
	{
		$quote = new \Quote();
		$products_in_quote = $quote->Count();
		
		$safe_count = intval($products_in_quote['quote_items_count']);
		if($safe_count >= 99) $safe_count = 99;	
		if($safe_count <= 0) return "";
		
		return "<span class='quote-badge'>" . $safe_count . "</span>";
	}
	
	
	

}

