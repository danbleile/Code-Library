<?php

//@verson 0.0.6

class DBWP_Post_Abstract {
	
	protected $author_id;
	protected $id;
	protected $labels;
	protected $meta_data = array();
	protected $meta_fields = array();
	protected $nonce_action = 'edit_post';
	protected $nonce_name = '_post_wp_nonce';
	protected $post;
	protected $post_excerpt;
	protected $post_content;
	protected $post_date;
	protected $post_modified;
	protected $post_name;
	protected $post_parent;
	protected $post_status;
	protected $post_title;
	protected $post_type;
	protected $post_type_args;
	protected $priorities = array();
	protected $save_meta = true;
	protected $scripts_editor_only = true;
	protected $scripts_single_only = true;
	protected $taxonomies = array();
	
	
	/** Get Methods
	* ------------------------------------------------------------------------- */
	
	
	public function get_author_id(){ return $this->author_id; }
	public function get_id() { return $this->id; }
	public function get_labels() { return $this->labels; }
	public function get_meta_data() { return $this->meta_data; }
	public function get_meta_fields() { return $this->meta_fields; }
	public function get_nonce_action() { return '_' . $this->get_post_type() . '_' . $this->nonce_action; }
	public function get_nonce_name() { return $this->nonce_name; }
	public function get_post() { return $this->post; }
	public function get_post_excerpt() { return $this->post_excerpt; }
	public function get_post_content( $filter = false ) { return ( $filter ) ? apply_filters( 'the_content' , $this->post_content ) : $this->post_content; }
	public function get_post_date() { return $this->post_date; }
	public function get_post_modified() { return $this->post_modified; }
	public function get_post_name() { return $this->post_name; }
	public function get_post_parent(){ return $this->post_parent; }
	public function get_post_status() { return $this->post_status; }
	public function get_post_title( $filter = false ) { return ( $filter ) ? apply_filters( 'the_title' , $this->post_title ) : $this->post_title; }
	public function get_post_type() { return $this->post_type; }
	public function get_post_type_args() { return $this->post_type_args; }
	public function get_priorities() { return $this->priorities; }
	public function get_save_meta() { return $this->save_meta; }
	public function get_scripts_editor_only() { return $this->scripts_editor_only; }
	public function get_scripts_single_only() { return $this->scripts_single_only; }
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
		
		if ( method_exists( $this , 'the_public_scripts' ) ){
			
			add_action( 'wp_enqueue_scripts', array( $this , 'action_wp_enqueue_scripts' ) , $this->return_priority( 'wp_enqueue_scripts' ) );
			
		} // end if
		
		if ( is_admin() ){

			$this->init_admin();
		
		} // end if
		
	} // end init
	
	
	protected function init_admin(){
		
		if ( method_exists( $this , 'the_edit_form_after_title' ) ){
			
			add_action( 'edit_form_after_title' , array( $this , 'action_edit_form_after_title' ), $this->return_priority( 'edit_form_after_title' ) ); 
			
		} // end if
		
		if ( method_exists( $this , 'the_edit_form_after_editor' ) ){
			
			add_action( 'edit_form_after_editor' , array( $this , 'action_edit_form_after_editor' ), $this->return_priority( 'edit_form_after_editor' ) );
			
		} // end if
		
		if ( method_exists( $this , 'the_admin_scripts' ) ){
			



			add_action( 'admin_enqueue_scripts', array( $this , 'action_admin_enqueue_scripts' ) , $this->return_priority( 'admin_enqueue_scripts' ) , 1 );
			
		} // end if
		
		$meta_fields = $this->get_meta_fields();
		
		if ( $this->get_save_meta() && ! empty( $meta_fields ) ){
			
			add_action( 'save_post_' . $this->get_post_type(), array( $this , 'action_save_post' ), $this->return_priority( 'action_save_post' ) , 3 );
			
		} // end if
		
	} // end init_admin
	
	
	/** Set Methods
	* ------------------------------------------------------------------------- */
	
	
	// Property Sets
	
	public function set_author_id( $value ){ $this->author_id = $value; }
	public function set_id( $value ) { $this->id = $value; }
	public function set_labels( $value ) { $this->labels = $value; }
	public function set_meta_data( $value ) { $this->meta_data = $value; }
	public function set_meta_data_value( $key , $value ){ $this->meta_data[ $key ] = $value; }
	public function set_meta_fields( $value ) { $this->meta_fields = $value; }
	public function set_post( $value ) { $this->post = $value; }
	public function set_post_excerpt( $value ) { $this->post_excerpt = $value; }
	public function set_post_content( $value ) { $this->post_content = $value; }
	public function set_post_date( $value ) { $this->post_date = $value; }
	public function set_post_modified( $value ) { $this->post_modified = $value; }
	public function set_post_name( $value ) { $this->post_name = $value; }
	public function set_post_parent( $value ){ $this->post_parent = $value; }
	public function set_post_status( $value ) { $this->post_status = $value; }
	public function set_post_title( $value ) { $this->post_title = $value; }
	public function set_post_type_args( $value ) { $this->post_type_args = $value; }
	public function set_priorities( $value ) { $this->priorities = $value; }
	public function set_save_meta( $value ) { $this->save_meta = $value; }
	public function set_taxonomies( $value ) { $this->taxonomies = $value; }
	
	
	// Object Sets
	
	public function set_by_wp_post( $post , $meta_by = 'post_id' ){
		
		if ( is_numeric( $post ) ){ // post is id
			
			$post = get_post( $post );
			
		} // end if
		
		$this->set_wp_post_values( $post );
		
		switch( $meta_by ){
			
			case 'post_id':
				$this->set_meta_data_values_by_post_id( $post->ID );
				break;
			case 'form':
				$this->set_meta_data_values_by_form();
				break;
		} // end switch
		
	} // set_by_wp_post
	
	
	public function set_wp_post_values( $wp_post ){
		
		$this->set_id( $wp_post->ID );
		$this->set_author_id( $wp_post->post_author );
		$this->set_post_name( $wp_post->post_name );
		$this->set_post_title( $wp_post->post_title );
		$this->set_post_date( $wp_post->post_date );
		$this->set_post_content( $wp_post->post_content );
		$this->set_post_excerpt( $wp_post->post_excerpt );
		$this->set_post_status( $wp_post->post_status );
		$this->set_post_parent( $wp_post->post_parent );
		$this->set_post_modified( $wp_post->post_modified );
		
	} // end set_wp_post_values
	
	
	public function set_meta_data_values_by_post_id( $post_id ){
		
		$meta_fields = $this->get_meta_fields();
		
		$post_meta = get_post_meta( $post_id );
		
		if ( ! empty( $meta_fields ) ){
			
			foreach( $meta_fields as $key => $default ){
				
				if ( array_key_exists( $key , $post_meta ) ){
					
					$meta_value = get_post_meta( $post_id , $key , true );
					
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
	
	
	public function action_admin_enqueue_scripts( $hook ){
		
		if ( $this->get_scripts_editor_only() && ( 'post.php' != $hook ) ){
			
			return;
			
		} // end if
			
		$this->the_admin_scripts();
		
	} // end if
	
	
	public function action_edit_form_after_editor( $post ){
		
		if ( $post->post_type == $this->get_post_type() ){
			
			wp_nonce_field( $this->return_nonce_action( $post->ID ) , $this->get_nonce_name() );
			
			if ( ! isset( $this->post ) ) $this->set_by_wp_post( $post );
		
			$this->the_edit_form_after_editor( $post , $this->get_meta_data() );
			
		} // end if
		
	} // end action_edit_form_after_editor
	
	
	public function action_edit_form_after_title( $post ){
		
		if ( $post->post_type == $this->get_post_type() ){
			
			wp_nonce_field( $this->return_nonce_action( $post->ID ) , $this->get_nonce_name() );
		
			if ( ! isset( $this->post ) ) $this->set_by_wp_post( $post );
		
			$this->the_edit_form_after_title( $post , $this->get_meta_data() );
		
		} // end if
		
	} // end action_edit_form_after_title
	
	
	public function action_register_post_type(){
		
		$post_type_args = $this->return_post_type_args();
		
		register_post_type( $this->get_post_type() , $post_type_args );
		
	} // end register_post_type
	
	
	public function action_save_post( $post_id , $post, $update ){
		
		if ( is_admin() && $this->return_do_save( $post_id , $post, $update ) ){
			
			$this->set_by_wp_post( $post , 'form' );
			
			$meta = $this->get_meta_data();
			
			foreach( $meta as $key => $value ){
				
				update_post_meta( $post_id , $key , $value );
				
			} // end foreach
			
		} // end if
		
	} // end action_save_post
	
	
	public function action_wp_enqueue_scripts(){
		
		if ( $this->get_scripts_single_only() && ! is_singular( $this->get_post_type() ) ){
			
			return;
			
		} // end if
			
		$this->the_public_scripts();
		
	} // end if
	
	
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
		if ( ! wp_verify_nonce( $_POST[ $this->get_nonce_name() ] , $this->return_nonce_action( $post_id ) ) ) {
			
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
	
	
	public function return_nonce_action( $post_id ){
		
		return $this->get_nonce_action() . '_' . $post->ID;
		
	} // end return_nonce_action
	
	
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
	
	
	/** Utility Methods
	* ------------------------------------------------------------------------- */
	
	
	/** Other Methods
	* ------------------------------------------------------------------------- */
	
	
}