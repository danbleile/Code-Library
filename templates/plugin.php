<?php // version 0.0.1
/*
Plugin Name: 
Plugin URI: 
Description: 
Author: 
Author URI: 
Version:
*/

class PLUGIN_CLASS_NAME {
	
	
	public static $version = '0.0.1';
	
	public static $instance;
	
	
	/**
	 * Get the current instance or set it an return
	 * @return object current instance of PLUGIN_CLASS_NAME
	 */
	 public static function get_instance(){
		 
		 if ( null == self::$instance ) {
			 
            self::$instance = new self;
			
			self::$instance->init();
			
        } // end if
 
        return self::$instance;
		 
	 } // end get_instance
	 
	 
	 protected function init(){
	 } // end init


} // end PLUGIN_CLASS_NAME

$plugin_var = PLUGIN_CLASS_NAME::get_instance();