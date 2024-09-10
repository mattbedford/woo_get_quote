<?php

namespace WooGetQuote;


class rest
{
    //TO DO: Simplify this and polymorphize.
    
    public function Register()
    {
        register_rest_route('wgq/v1', '/add', [
            'methods' => 'POST',
            'callback' => [self::class, 'add'],
            'permission_callback' => function() {
            	return is_user_logged_in();
        	}
        ]);

        register_rest_route('wgq/v1', '/remove', [
            'methods' => 'POST',
            'callback' => [self::class, 'remove'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);

        register_rest_route('wgq/v1', '/clearone', [
            'methods' => 'POST',
            'callback' => [self::class, 'clearone'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);


        register_rest_route('wgq/v1', '/clearall', [
            'methods' => 'POST',
            'callback' => [self::class, 'clearall'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);

        register_rest_route('wgq/v1', '/retrieve', [
            'methods' => 'POST',
            'callback' => [self::class, 'retrieve'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);
		
		register_rest_route('wgq/v1', '/retrievefull', [
            'methods' => 'POST',
            'callback' => [self::class, 'retrievefull'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);

        register_rest_route('wgq/v1', '/count', [
            'methods' => 'POST',
            'callback' => [self::class, 'count'],
            'permission_callback' => function() {
                return is_user_logged_in();
            }
        ]);

    }


    public static function add($request)
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


    public static function remove($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->Remove($args);
            return $res;
        
        } catch (\Exception $e) {
            logger($e->getMessage());
        
        }
    }


    public static function clearone($request)
    {

        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->ClearOne($args);
            return $res;
        
        } catch (\Exception $e) {
            logger($e->getMessage());
        
        }
        
    }


    public static function clearall($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->ClearAll($args);
            return $res;
        
        } catch (\Exception $e) {
            logger($e->getMessage());
        
        }
    }


    public static function retrieve($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->Retrieve($args);
            return $res;
        
        } catch (\Exception $e) {
            logger($e->getMessage());
        
        }
    }
	
	
	public static function retrievefull($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->RetrieveFull($args);
            return $res;
        
        } catch (\Exception $e) {
            logger($e->getMessage());
        
        }
    }


    public static function count($request)
    {
        
        include_once plugin_dir_path(__FILE__) . 'quote.php';
        $args = $request->get_json_params();
        $quote = new \Quote();
        
        $res = null;

        try {

            $res = $quote->Count($args);
            return $res;
        
        } catch (\Exception $e) {
            logger($e->getMessage());
        
        }
    }

}
