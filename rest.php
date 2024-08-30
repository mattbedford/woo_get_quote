<?php

namespace WooGetQuote;


class rest
{
    
    public function registerLoggedInRoutes()
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

    }


    public function Add($request)
    {
        
    }


    public function Remove($request)
    {
        
    }


    public function ClearOne($request)
    {
        
    }


    public function ClearAll($request)
    {
        
    }
    

    public function Retrieve($request)
    {
        
    }

}
