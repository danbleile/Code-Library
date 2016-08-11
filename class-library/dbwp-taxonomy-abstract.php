<?php

//@version 0.0.1

class DBWP_Taxonomy_Abstract {
	
	protected $slug;
	protected $labels;
	protected $post_types;
	protected $priorities = array();
	protected $register_args;
	
	
	/** Get Methods
	* ------------------------------------------------------------------------- */
	
	
	public function get_labels() { return $this->labels; }
	public function get_slug(){ return $this->slug; }
	public function get_post_types() { return $this->post_types; }
	public function get_priorities() { return $this->priorities; }
	public function get_register_args() { return $this->register_args; }
	
	
	/** Init Methods
	* ------------------------------------------------------------------------- */
	
	
	public function init(){
		
		if ( $this->get_register_args() ){
			
			add_action( 'init', array( $this , 'action_register_taxonomy' ), $this->return_priority( 'action_register_taxonomy' ) );
			
		} // end if
		
	} // end init
	
	
	public function init_admin(){
	} // end init_admin
	
	
	/** Set Methods
	* ------------------------------------------------------------------------- */
	
	
	/** Action Methods
	* ------------------------------------------------------------------------- */
	
	
	public function action_register_taxonomy(){
		
		$register_args = $this->get_register_args();
		
		$register_args['labels'] = $this->get_labels();
		
		register_taxonomy( $this->get_slug() , $this->get_post_types() , $register_args );
		
	} // end action_register_taxonomy
	
	
	
	/** Return Methods
	* ------------------------------------------------------------------------- */
	
	
	public function return_priority( $action ){
		
		$priorities = $this->get_priorities();
		
		if ( array_key_exists( $action , $priorities ) ){
			
			return $priorities[ $action ];
			
		} else {
			
			return 11;
			
		}// end if
		
	} // end return_priority
	
	
	
} // end DBWP_Taxonomy_Abstract