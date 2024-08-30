<?php

if( ! defined( 'ABSPATH' ) ) exit;
$path = preg_replace('/wp-content.*$/','',__DIR__);
include_once($path.'wp-load.php'); 


class Quote
{
    private $quote_contents;
    private $user_id;
    private $error = [];


    public function __construct()
    {
        $this->user_id = get_current_user_id();
        if(0 === $this->user_id) {
            $this->error = ["error", "Utente non autorizzato"];
            return;
        }
        $this->quote_contents = get_user_meta($this->user_id, 'quote_contents', true);
        if(empty($this->quote_contents)) {
            $this->quote_contents = [];
        }
    }


    private function Add($request) {
		
		$item = $this->SanitizeRequest($request);
        if(!empty($this->error)) {
            return $this->error;
        }

        // Check if the item is already in the quote.
        // If it is, update the quantity; else, add the item to the quote.
        if (in_array($item['product_id'], array_column($this->quote_contents, 'product_id'))) {
            foreach ($this->quote_contents as &$cartItem) {
                if ($cartItem['product_id'] == $item['product_id']) {
					$cartItem['quantity'] += $item['quantity'];
                } 
            }
        } else {
            $this->quote_contents[] = $item;
        }

        $this->save_quote_contents();
        return $this->quote_contents;
		
    }


    private function Remove($request) {

        $item = $this->SanitizeRequest($request);

        if (in_array($item['product_id'], array_column($this->quote_contents, 'product_id'))) {
            foreach ($this->quote_contents as $index => &$cartItem) {
                if ($cartItem['product_id'] == $item['product_id']) {
					$cartItem['volume'] -= $item['volume'];
					
                    // If quantity is 0 or less, remove item from quote and reset array keys
					if($cartItem['volume'] <= 0) {
						unset($this->quote_contents[$index]);
						$this->quote_contents = array_values($this->quote_contents);
					}
                }
            }
        } else {
            $this->error = ["error", "Prodotto non trovato nel carrello"];
        }

        $this->save_quote_contents();
        return $this->quote_contents;

    }


    private function ClearOne($request) {

        $item = $this->SanitizeRequest($request);

        // Check if the item is in the quote already
        if (in_array($item, array_column($this->quote_contents, 'id'))) {
            // If it is, remove it
            foreach ($this->quote_contents as $key => $cartItem) {
                if ($cartItem['id'] == $item) {
                    unset($this->quote_contents[$key]);
                }
            }
        } else {
            $this->error = ["error", "Prodotto non trovato nel carrello"];
        }

        $this->save_quote_contents();
        return $this->quote_contents;
    }


	private function ClearAll() {
		$this->save_quote_contents();
        return $this->quote_contents;
	}


    private function save_quote_contents()
    {
        update_user_meta($this->user_id, 'quote_contents', $this->quote_contents);
    }


    private function Retrieve($request)
    {
        return $this->quote_contents;
    }
	
	private function SanitizeRequest($request): array
    {
		
        $post_vars = [];

		if(!isset($request['product_id'])) {
			$this->error = ["error", "Nessun prodotto inviato al sistema. Prego riprovare."];
			return;
		}
		
		$post_vars['product_id'] = htmlspecialchars($request['product_id']);
	
        return $post_vars;
	}
	
	
	// Statics
	public static function Count($current_user_id) {
		
		if (0 !== $current_user_id) {
			$quote_contents = get_user_meta($current_user_id, 'quote_contents', true);
			if(is_array($quote_contents) && !empty($quote_contents)) {
					$cart_count = count($quote_contents);
			} else {
					$cart_count = 0;
			}
					
		}
		
		return array("user_id" => $current_user_id, "quote_items_count" => $cart_count);
		
	}
	

}