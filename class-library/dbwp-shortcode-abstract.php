<?php

//@version 0.0.2

class DBWP_Shortcode_Abstract {
	
	protected $atts = array();
	protected $content;
	protected $default_atts = array();
	protected $priorities = array();
	protected $slug;
	
	
	/** Get Methods
	* ------------------------------------------------------------------------- */
	
	
	public function get_atts() { return $this->atts; }
	public function get_content() { return $this->content; }
	public function get_default_atts() { return $this->default_atts; }
	public function get_priorities() { return $this->priorities; }
	public function get_slug(){ return $this->slug; }
	
	
	/** Init Methods
	* ------------------------------------------------------------------------- */
	
	
	public function init(){
			
		add_action( 'init', array( $this , 'action_register_shortcode' ), $this->return_priority( 'add_shortcode' ) );
		
		if ( method_exists( $this , 'the_public_scripts' ) ){
			
			add_action( 'wp_enqueue_scripts', array( $this , 'the_public_scripts' ) , $this->return_priority( 'wp_enqueue_scripts' ) );
			
		} // end if
		
		if ( is_admin() ){

			$this->init_admin();
		
		} // end if
		
	} // end init
	
	
	protected function init_admin(){
		
		if ( method_exists( $this , 'the_admin_scripts' ) ){

			add_action( 'admin_enqueue_scripts', array( $this , 'the_admin_scripts' ) , $this->return_priority( 'admin_enqueue_scripts' ) , 1 );
			
		} // end if
		
	} // end init_admin
	
	
	/** Set Methods
	* ------------------------------------------------------------------------- */
	
	
	public function set_atts( $value ) { $this->atts = $value; }
	public function set_content( $value ) { $this->content = $value; }
	
	
	public function set_shortcode( $atts , $content ){
		
		$this->set_atts( $this->return_parse_atts( $atts , $content , $tag ) );
		
		$this->set_content( $content );
		
	} // end set_shortcode
	
	
	/** Action Methods
	* ------------------------------------------------------------------------- */
	
	
	public function action_register_shortcode(){
		
		add_shortcode( $this->get_slug(), array( $this , 'return_shortcode_html' ) );
		
	} // end action_register_taxonomy
	
	
	/** Return Methods
	* ------------------------------------------------------------------------- */
	
	
	protected function return_parse_atts( $atts , $content , $tag ){
		
		$atts = shortcode_atts( $this->get_default_atts() , $atts , $tag );
		
		return $atts;
		
	} // end return_parse_atts
	
	
	public function return_priority( $action ){
		
		$priorities = $this->get_priorities();
		
		if ( array_key_exists( $action , $priorities ) ){
			
			return $priorities[ $action ];
			
		} else {
			
			return 10;
			
		}// end if
		
	} // end return_priority
	
	
	public function return_shortcode_html( $atts , $content , $tag ){
		
		$html = '';
		
		$this->set_shortcode( $atts , $content );
		
		if ( method_exists( $this , 'the_shortcode_html' ) ){
			
			$html .= $this->the_shortcode_html( $this->get_atts() , $this->get_content() );
			
		} // end if
		
		return $html;
		
	} // end return_shortcode_html
	
	
} // end DBWP_Taxonomy_Abstract