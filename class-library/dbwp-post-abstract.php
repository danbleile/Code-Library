<?php

//@verson 0.0.1

class DBWP_Post_Abstract {
	
	
	protected $labels;
	protected $meta_data = array();
	protected $meta_fields = array();
	protected $post_type_args;
	protected $priorities = array();
	protected $save_meta = true;
	protected $slug;
	protected $taxonomies = array();
	
	
	/** Get Methods
	* ------------------------------------------------------------------------- */
	
	
	public function get_labels() { return $this->labels; }
	public function get_meta_data() { return $this->meta_data; }
	public function get_meta_fields() { return $this->meta_fields; }
	public function get_post_type_args() { return $this->post_type_args; }
	public function get_priorities() { return $this->priorities; }
	public function get_save_meta() { return $this->save_meta; }
	public function get_slug() { return $this->slug; }
	public function get_taxonomies() { return $this->taxonomies; }
	
	
	/** Construct Method
	* ------------------------------------------------------------------------- */
	
	
	public function __construct( array $taxonomies = array()  ){
		
		$this->set_taxonomies( $taxonomies );
		
	} // end __construct
	
	/** Init Methods
	* ------------------------------------------------------------------------- */
	
	
	public function init(){
		
		$post_type_args = $this->get_post_type_args();
		
		if ( ! empty( $post_type_args ) ){
			
			add_action( 'init' , array( $this , 'action_register_post_type' ) , $this->return_priority( 'register_post_type' ) );
			
		} // end if
		
		if ( is_admin() ){

			$this->init_admin();
		
		} // end if
		
	} // end init
	
	
	protected function init_admin(){
		
		if ( method_exists( $this , 'the_edit_form_after_title' ) ){
			
			add_action( 'edit_form_after_title' , array( $this , 'action_edit_form_after_title' ), $this->return_priority( 'edit_form_after_title' ) ); 
			
		} // end if
		
		$meta_fields = $this->get_meta_fields();
		
		if ( $this->get_save_meta() && ! empty( $meta_fields ) ){
			
			add_action( 'save_post_' . $this->get_slug() , array( $this , 'action_save_post' ), $this->return_priority( 'action_save_post' ) , 3 );
			
		} // end if
		
	} // end init_admin
	
	
	/** Set Methods
	* ------------------------------------------------------------------------- */
	
	
	public function set_meta_data( $meta ){ $this->meta_data = $meta; }
	public function set_meta_data_value( $key , $value ){ $this->meta_data[ $key ] = $value; }
	public function set_taxonomies( $taxes ) { $this->taxonomies = $taxes; }
	
	
	public function set_meta_data_values_by_post_id( $post_id ){
		
		$meta_fields = $this->get_meta_fields();
		
		if ( ! empty( $meta_fields ) ){
			
			foreach( $meta_fields as $key => $default ){
				
				$meta_value = get_post_meta( $post_id , $key , true );
				
				if ( $meta_value !== '' ){
					
					$meta_fields[ $key ] = $meta_value;
					
				} // end if
					
			} // end foreach
			
		} // end if
		
		$this->set_meta_data( $meta_fields );
		
	} // end set_meta_data_values
	
	
	public function set_meta_data_values_by_form(){
		
		$save_fields = array();
		
		$meta_fields = $this->get_meta_fields();
		
		if ( ! empty( $meta_fields ) ){
			
			foreach( $meta_fields as $key => $default ){
				
				if ( isset( $_POST[ $key ] ) ){
					
					$save_fields[ $key ] = $_POST[ $key ];
					
				} // end if
		
			} // end foreach
			
		} // end if
		
		if ( $save_fields ){
		
			$clean_fields = $this->return_clean_meta( $save_fields );
		
			$this->set_meta_data( $clean_fields );
			
		} // end if
		
	} // end set_meta_data_values_by_form
	
	
	/** Action Methods
	* ------------------------------------------------------------------------- */
	
	
	public function action_edit_form_after_title( $post ){
		
		if ( $post->post_type == $this->get_slug() ){
		
			$this->set_meta_data_values_by_post_id( $post->ID );
		
			$this->the_edit_form_after_title( $post , $this->get_meta_data() );
		
		} // end if
		
	} // end action_edit_form_after_title
	
	
	public function action_register_post_type(){
		
		$post_type_args = $this->return_post_type_args();
		
		register_post_type( $this->get_slug() , $post_type_args );
		
	} // end register_post_type
	
	
	public function action_save_post( $post_id , $post, $update ){
		
		if ( $this->return_do_save( $post_id , $post, $update ) ){
			
			$this->set_meta_data_values_by_form();
			
			$meta = $this->get_meta_data();
			
			foreach( $meta as $key => $value ){
				
				update_post_meta( $post_id , $key , $value );
				
			} // end foreach
			
		} // end if
		
	} // end action_save_post
	
	
	/** Return Methods
	* ------------------------------------------------------------------------- */
	
	
	protected function return_clean_meta( $meta ){
		
		return array();
		
	} // end return_clean_meta
	
	
	protected function return_do_save( $post_id , $post, $update ){
		
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			
			return false;
			
		} // end if
		
		// Verify that the input is coming from the proper form
		if ( ! wp_verify_nonce( $_POST['meta_data_nonce'], plugin_basename( __FILE__ ) ) ) {
			
			return false;
			
		} // end if
		
		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

			if ( current_user_can( 'edit_page', $post_id ) ) {

				return true;

			} // end if

		} else {

			if ( current_user_can( 'edit_post', $post_id ) ) {

				return true;

			} // end if

		} // end if
		
		return false;
		
	} // end return_do_save
	
	
	public function return_meta_value( $key , $post_id = false ){
		
		$meta = $this->get_meta_data();
		
		if ( array_key_exists( $key , $meta ) ){
			
			return $meta[ $key ];
			
		} else {
			
			return '';
			
		} // end if
		
	} // end return_field_value
	
	
	public function return_post_type_args(){
		
		$type_args = $this->get_post_type_args();
		
		$type_args['labels'] = $this->get_labels();
		
		$taxonomies = $this->return_taxonomy_slugs();
		
		if ( $taxonomies ){
			
			if ( ! empty( $type_args['taxonomies'] ) ){
			
				$taxonomies = array_merge( $type_args['taxonomies'] , $taxonomies );
				
			} // end if
			
			$type_args['taxonomies'] = $taxonomies;
			
		} // end if
		
		return $type_args;
		
	} // end build_post_type_args
	
	
	public function return_priority( $action ){
		
		$priorities = $this->get_priorities();
		
		if ( array_key_exists( $action , $priorities ) ){
			
			return $priorities[ $action ];
			
		} else {
			
			return 10;
			
		}// end if
		
	} // end return_priority
	
	
	public function return_taxonomy_slugs(){
		
		$taxonomies = array();
		
		$tax_objs = $this->get_taxonomies();
		
		if ( ! empty( $tax_objs ) ){
			
			foreach( $tax_objs as $index => $tax_obj ){
				
				if ( is_object( $tax_obj ) && method_exists( $tax_obj , 'get_slug' ) ){
					
					$taxonomies[] = $tax_obj->get_slug();
					
				} // end if
				
			} // end foreach
			
		} // end if
		
		return $taxonomies;
		
	} // end return_taxonomy_slugs
	
	
	/** The Methods
	* ------------------------------------------------------------------------- */
	
	
	/** Other Methods
	* ------------------------------------------------------------------------- */
	
	
}