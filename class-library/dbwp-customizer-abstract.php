<?php

class DBWP_Customizer_Abstract {
	
	protected $panels = array();
	
	
	/** Get Methods
	* ------------------------------------------------------------------------- */
	
	
	public function get_panels(){ return $this->panels; }
	
	
	/** Init Methods
	* ------------------------------------------------------------------------- */
	
	
	public function init(){
	} // end init
	
	
	public function init_admin(){
		
		add_action( 'customize_register' , array( $this , 'init_register' ) );
		
	} // end init_admin
	
	
	public function init_register( $wp_customize ){
		
		if ( ! empty( $this->get_panels( $wp_customize ) ) ){
			
			
			
		} // end if
		
	} // end init_register
	
	
	/** Action Methods
	* ------------------------------------------------------------------------- */
	
	
	protected function action_add_panels( $wp_customize ){
		
		$panels = $this->get_panels();
		
		foreach( $panels as $panel_slug => $panel ){
		} // end foreach
		
	} // end action_add_panels
	
}