<?php

if (! defined('ABSPATH')) exit;
$path = preg_replace('/wp-content.*$/', '', __DIR__);
include_once($path . 'wp-load.php');


class Quote
{
    private $quote_contents;
    private $user_id;
    private $error = [];


    public function __construct()
    {
        $this->user_id = get_current_user_id();
        if (0 === $this->user_id) {
            $this->error = ["error", "Utente non autorizzato"];
            return;
        }
        $this->quote_contents = get_user_meta($this->user_id, 'quote_contents', true);
        if (empty($this->quote_contents)) {
            $this->quote_contents = [];
        }
    }


    public function Add($request)
    {

        $item = $this->SanitizeRequest($request);
        if (!empty($this->error)) {
            return $this->error;
        }

        // Check if the item is already in the quote.
        // If it is, update the quantity; else, add the item to the quote.
        if (in_array($item['product_id'], array_column($this->quote_contents, 'product_id'))) {
            foreach ($this->quote_contents as &$cartItem) {
                if ($cartItem['product_id'] == $item['product_id']) {
                    $cartItem['quantity'] += $item['quantity'];
					$cartItem['product_name'] = $item['product_name'];					
                }
            }
        } else {
            $this->quote_contents[] = $item;
        }

        $this->save_quote_contents();
        return $this->quote_contents;
    }


    public function Remove($request)
    {

        $item = $this->SanitizeRequest($request);

        if (in_array($item['product_id'], array_column($this->quote_contents, 'product_id'))) {
            foreach ($this->quote_contents as $index => &$cartItem) {
                if ($cartItem['product_id'] == $item['product_id']) {
                    $cartItem['quantity'] -= $item['quantity'];

                    // If quantity is 0 or less, remove item from quote and reset array keys
                    if ($cartItem['quantity'] <= 0) {
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


    public function ClearOne($request)
    {

        $item = $this->SanitizeRequest($request);

        // Check if the item is in the quote already
        if (in_array($item['product_id'], array_column($this->quote_contents, 'product_id'))) {
            // If it is, remove it
            foreach ($this->quote_contents as $key => &$cartItem) {
                if ($cartItem['product_id'] == $item['product_id']) {
                    unset($this->quote_contents[$key]);
                }
            }
        } else {
            $this->error = ["error", "Prodotto non trovato nel carrello"];
        }

        $this->save_quote_contents();
        return $this->quote_contents;
    }


    public function ClearAll()
    {
        
		$this->quote_contents = [];
		$this->save_quote_contents();
        return $this->quote_contents;
    }


    public function Retrieve($request)
    {
        return $this->quote_contents;
    }

	
	public function RetrieveFull($request)
    {
		$result = [];
		$contents = $this->quote_contents;
        foreach($contents as $item) {
			$id = $item["product_id"];
			
			$result[] = [
				'id' => $id,
				'quantity' => $item['quantity'],
				'image' => get_the_post_thumbnail_url($id, 'thumbnail'),
				'name' => get_the_title($id),
				'desc' => get_the_excerpt($id),
			];
		}
		return $result;
    }
	

    private function save_quote_contents()
    {
        update_user_meta($this->user_id, 'quote_contents', $this->quote_contents);
    }

    private function SanitizeRequest($request): array
    {

        if (!isset($request['product_id'])) {
            $this->error = ["error", "Nessun prodotto inviato al sistema. Prego riprovare."];
            return [];
        }
		
		if('publish' !== get_post_status($request['product_id'])) {
            $this->error = ["error", "Prodotto non trovato. Prego riprovare."];
            return [];
        }

        $post_vars = [];
		$post_vars['product_name'] = get_the_title($request['product_id']);
        $post_vars['product_id'] = htmlspecialchars($request['product_id']);
		$post_vars['quantity'] = 1;

        return $post_vars;
    }


    public function Count()
    {

        if (0 === $this->user_id) {
            return array("user_id" => 0, "quote_items_count" => 0);
        }

        $quote_contents = get_user_meta($this->user_id, 'quote_contents', true);
        if (is_array($quote_contents) && !empty($quote_contents)) {
            $cart_count = count($quote_contents);
        } else {
            $cart_count = 0;
        }

        return array("user_id" => $this->user_id, "quote_items_count" => $cart_count);
    }
}
