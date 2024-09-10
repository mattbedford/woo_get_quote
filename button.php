<?php


namespace WooGetQuote;


class Button 
{

	// TODO: consider changing text to "richiedi un prewventivo" if no items in cart.
 	public static function Html()
	{
		
		$link = "/" . "#single-contact-form";
		$text = "Richiedi un preventivo";
		
		if(is_user_logged_in()) {
		
			$link = "/" . "preventivo";

			$product_id = get_the_id();
			if(0 !== $product_id && 'product' === get_post_type($product_id)) {
				$link .= '/?product_id=' . $product_id . '&security=' . wp_create_nonce('wgq');
			}
			
			if(true === self::HasItemsInQuote()) {
				$text = "Aggiungi al tuo preventivo";
			}
			
		}
		
		
		echo "<a href='" . $link . "'>";
		echo "<button class='wgq-quote-button'>";
		echo __($text, 'WooGetQuote');
		echo "</button>";
		echo "</a>";
		
	}
	
	
	public static function HasItemsInQuote()
	{
		$quote = new \Quote();
		$prods = $quote->Count();
		
		if($prods["quote_items_count"] && $prods["quote_items_count"] > 0) return true;
		return false;
	}
  
}
