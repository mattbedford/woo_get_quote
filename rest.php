<?php

namespace WooGetQuote;


class rest
{
    
    public function Register()
    {
        register_rest_route('woo-get-quote/v1', '/add', [
            'methods' => 'POST',
            'callback' => [self::class, 'Add'],
            'permission_callback' => function() {
            	return is_user_logged_in();
        	}
        ]);

        register_rest_route('woo-get-quote/v1', '/remove', [
            'methods' => 'POST',
            'callback' => [self::class, 'Remove'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);

        register_rest_route('woo-get-quote/v1', '/clear-one', [
            'methods' => 'POST',
            'callback' => [self::class, 'ClearOne'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);


        register_rest_route('woo-get-quote/v1', '/clear-all', [
            'methods' => 'POST',
            'callback' => [self::class, 'ClearAll'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);

        register_rest_route('woo-get-quote/v1', '/retrieve', [
            'methods' => 'POST',
            'callback' => [self::class, 'Retrieve'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);

        register_rest_route('woo-get-quote/v1', '/count', [
            'methods' => 'POST',
            'callback' => [self::class, 'Count'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);

    }


    private function Add($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->Add($args);
            return $res;
        
        } catch (\Exception $e) {
            Logger($e->getMessage());
        
        }
        
    }


    private function Remove($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->Remove($args);
            return $res;
        
        } catch (\Exception $e) {
            Logger($e->getMessage());
        
        }
    }


    private function ClearOne($request)
    {

        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->ClearOne($args);
            return $res;
        
        } catch (\Exception $e) {
            Logger($e->getMessage());
        
        }
        
    }


    private function ClearAll($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->ClearAll($args);
            return $res;
        
        } catch (\Exception $e) {
            Logger($e->getMessage());
        
        }
    }


    private function Retrieve($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->Retrieve($args);
            return $res;
        
        } catch (\Exception $e) {
            Logger($e->getMessage());
        
        }
    }


    private function Count($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->Count($args);
            return $res;
        
        } catch (\Exception $e) {
            Logger($e->getMessage());
        
        }
    }

}
