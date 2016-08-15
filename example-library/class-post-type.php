<?php // version 0.0.1

class Post_Type extends DBWP_Post_Abstract {
	
	// Labels for the custom post type
	protected $labels = array(
		/*'name'               => 'UPDATE:POSTNAMEPLURAL', 
		'singular_name'      => 'UPDATE:POSTNAMESINGULAR', 
		'menu_name'          => 'UPDATE:POSTNAMEPLURAL', 
		'name_admin_bar'     => 'UPDATE:POSTNAMESINGULAR', 
		'add_new'            => 'Add New', 
		'add_new_item'       => 'Add New UPDATE:POSTNAMESINGULAR',
		'new_item'           => 'New UPDATE:POSTNAMESINGULAR',
		'edit_item'          => 'Edit UPDATE:POSTNAMESINGULAR',
		'view_item'          => 'View UPDATE:POSTNAMESINGULAR',
		'all_items'          => 'All UPDATE:POSTNAMEPLURAL',
		'search_items'       => 'Search UPDATE:POSTNAMEPLURAL',
		'parent_item_colon'  => 'Parent UPDATE:POSTNAMEPLURAL:',
		'not_found'          => 'No UPDATE:POSTNAMEPLURAL found.',
		'not_found_in_trash' => 'No UPDATE:POSTNAMEPLURAL found in Trash.',*/
	);
	
	// Args used to register the custom post type
	protected $post_type_args = array(
        /*'description'        => 'Description.',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'UPDATE:REWRITEURL' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )*/
	);
	
	// Meta fields used in the post type | key => default value
	protected $meta_fields = array(
		/*'_field_one'   => 1,
		'_field_two'   => 2,
		'_field_three' => 3,
		'_field_four'  => 4,*/
	);
	
	// Set Priorities for a given action | action => priority
	protected $priorities = array(
		/*'register_post_type'     => 10,
		'wp_enqueue_scripts'     => 10,
		'edit_form_after_title'  => 10,
		'edit_form_after_editor' => 10,
		'admin_enqueue_scripts'  => 10,
		'action_save_post'       => 10,*/
	);
	
	// Enqueue admin script on editor only
	protected $scripts_editor_only = true;
	
	// Enqueue script on single post type only
	protected $scripts_single_only = true;
	
	/** The Methods
	* ------------------------------------------------------------------------- */
	
	// Adds edit form to post type ater title field
	protected function the_edit_form_after_editor( $post , $meta ){
		
		/*foreach( $meta as $name => $value ){
			
			echo '<input type="text" name="' . $name . '" value="' . $value . '" />';
			
		} // end foreach*/
		
	} // end the_edit_form_after_editor
	
	
	// Adds edit form to post type ater title field
	protected function the_edit_form_after_title( $post , $meta ){
		
		/*foreach( $meta as $name => $value ){
			
			echo '<input type="text" name="' . $name . '" value="' . $value . '" />';
			
		} // end foreach*/
		
	} // end the_edit_form_after_title
	
	
	// Enqueue admin scripts. Will only enqueue on edit screen if $scripts_editor_only = true (default)
	protected function the_scripts_admin(){
		
		//wp_enqueue_style( 'test_style',  '/bla/style.css', false );
		
	} // end the_scripts_admin
	
	
	// Enqueue public scripts. Will only enqueue on single post type template if $scripts_single_only = true (default)
	protected function the_scripts_public(){
		
		//wp_enqueue_style( 'test_style',  '/bla/style.css', false );
		
	} // end the_scripts_public
	
	
	/** Return Methods
	* ------------------------------------------------------------------------- */
	
	// Sanitizes meta fields - must have or post type won't save
	protected function return_clean_meta( $save_fields ){
		
		//return $save_fields;
		
	} // end return_clean_meta
	
}