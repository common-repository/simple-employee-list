<?php 
/**
 * Plugin Name:       Simple Employee List
 * Plugin URI:        https://github.com/Mohib04/employee-list
 * Description:       The best WordPress Plugin for manage employee list.
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mohibbulla Munshi
 * Author URI:        https://in.linkedin.com/in/mohib5g
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       employee
 * Domain Path:       /languages
 */
?>
<?php

defined('ABSPATH') or die('No Script Available');
/* 
    Enqueue Style and Scripts into backend
*/
function sel_admin_style(){
    wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ).'assets/css/bootstrap.min.css' );
    wp_enqueue_script( 'bootstrap',  plugin_dir_url( __FILE__ ).'assets/js/bootstrap.min.js', array('jquery') );
}
add_action('admin_enqueue_scripts', 'sel_admin_style' );

/* 
    Enqueue Style and Scripts into frontend
*/
function sel_frontend_style(){
    wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ).'assets/css/bootstrap.min.css' );
    wp_enqueue_script( 'bootstrap',  plugin_dir_url( __FILE__ ).'assets/js/bootstrap.min.js', array('jquery') );
}
add_action('wp_enqueue_scripts', 'sel_frontend_style' );


/* 
    activation hook
*/
function sel_activated(){
       
    flush_rewrite_rules();
}
register_activation_hook(__FILE__, 'sel_activated');

/* 
    deactivate hook
*/
function sel_deactivated(){
    
}
register_deactivation_hook(__FILE__, 'sel_deactivated');

/* register employee custom post type */
function sel_custom_post_type(){
    register_post_type( 'employee-post-type', array(
        'labels'                 => array(
            'name'               => __( 'Employees', 'employee'),
            'singular_name'      => __( 'Employee', 'employee'),
            'menu_name'          => __( 'Employee', 'Admin Menu Text', 'employee'),
            'add_new'            => __( 'Add Employee', 'employee'),
            'add_new_item'       => __( 'Add New Employee', 'employee')
        ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'            => true,
            'show_in_menu'       => true,
            'query_var'          => true,
            'rewrite'            => array( 'slug' => 'employee' ),
            'hierarchical'       => true,   
            'has_archive'        => true,
            'supports'           => array('thumbnail'),
    ) );
}
add_action('init', 'sel_custom_post_type');

//Register custom meta box
function sel_custom_meta_boxes(){
    add_meta_box(
        'employee-meta',
        esc_html__( 'Add Employee information', 'employee'),
        'render_sel_field',
        'employee-post-type',
        'normal',
        'core'
     
    );
}
function render_sel_field(){
?>
<!-- Render Meta Box fields -->
<form>
    <div class="mb-3">
        <label for="name" class="form-label">Full Name</label>
        <input name="name" type=" email" class="form-control" id="name"
            value="<?php global $post; echo esc_attr(get_post_meta($post->ID, 'name', true)); ?>">
    </div>
    <div class="mb-3">
        <label for="designation" class="form-label">Designation</label>
        <input name="designation" type=" email" class="form-control" id="designation"
            value="<?php global $post; echo esc_attr(get_post_meta($post->ID, 'designation', true)); ?>">
    </div>
    <div class="mb-3">
        <label for="phone" class="form-label">Phone</label>
        <input name="phone" type=" email" class="form-control" id="phone"
            value="<?php global $post; echo esc_attr(get_post_meta($post->ID, 'designation', true)); ?>">
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input name="email" type=" email" class="form-control" id="email"
            value="<?php global $post; echo esc_attr(get_post_meta($post->ID, 'email', true)); ?>">
    </div>
    <div class="mb-3">
        <label for="facebook" class="form-label">Facebook Profile URL</label>
        <input name="facebook" type=" facebook" class="form-control" id="facebook"
            value="<?php global $post; echo esc_attr(get_post_meta($post->ID, 'facebook', true)); ?>">
    </div>
    <div class="mb-3">
        <label for="linkedin" class="form-label">Linkedin Profile URL:</label>
        <input name="linkedin" type=" linkedin" class="form-control" id="linkedin"
            value="<?php global $post; echo esc_attr(get_post_meta($post->ID, 'linkedin', true)); ?>">
    </div>
</form>
<?php

}

//update meta box
function sel_save_meta_box(){
    global $post ;
    if(isset($_POST["name"])):
        update_post_meta($post->ID, "name",
        sanitize_text_field( $_POST["name"]) );
    endif;
    if(isset($_POST["designation"])):
        update_post_meta($post->ID, "designation",
        sanitize_text_field( $_POST["designation"]) );
    endif;
    if(isset($_POST["phone"])):
        update_post_meta($post->ID, "phone",
        sanitize_text_field( $_POST["phone"]) );
    endif;
    if(isset($_POST["email"])):
        update_post_meta($post->ID, "email",
        sanitize_text_field( $_POST["email"]) );
    endif;
    if(isset($_POST["facebook"])):
        update_post_meta($post->ID, "facebook",
        sanitize_text_field( $_POST["facebook"]) );
    endif;
    if(isset($_POST["linkedin"])):
        update_post_meta($post->ID, "linkedin",
        sanitize_text_field( $_POST["linkedin"]) );
    endif;
}
add_action('save_post', 'sel_save_meta_box');

add_action('add_meta_boxes', 'sel_custom_meta_boxes' );


// Short Code Registration
function sel_shortcode(){
    ob_start();
    $get_employee_fields = New WP_Query( array(
        'post_type'      => 'employee-post-type',    
    ) );
?>
<!-- Short Code Output goes here -->

<div class="row g-4">
    <?php while($get_employee_fields->have_posts()): $get_employee_fields->the_post();?>

    <div class="col-sm-4">
        <div class="card"><img src="<?php echo esc_html(get_the_post_thumbnail_url()); ?>" class="card-img-top"
                alt="...">
            <ul class="list-group list-group-flush">
                <li class="list-group-item card-body">
                    <strong>Name: </strong><?php echo esc_html(get_post_meta(get_the_id(), 'name', true));?>
                </li>
                <li class="list-group-item">
                    <strong>Designation:
                    </strong><?php echo esc_html(get_post_meta(get_the_id(), 'designation', true));?>
                </li>
                <li class="list-group-item">
                    <strong>Phone: </strong><?php echo esc_html(get_post_meta(get_the_id(), 'phone', true));?>
                </li>
                <li class="list-group-item">
                    <strong>Email: </strong><?php echo esc_html(get_post_meta(get_the_id(), 'email', true));?>
                </li>
                <li class="text-center list-group-item">
                    <a class="btn btn-outline-success"
                        href="<?php echo esc_html(get_post_meta(get_the_id(), 'facebook', true));?>" target="_blank"
                        role="button"><span class="dashicons dashicons-facebook-alt"></span>
                    </a>
                    <a class="btn btn-outline-success"
                        href="<?php echo esc_html(get_post_meta(get_the_id(), 'linkedin', true));?>" target="_blank"
                        role="button"><span class="dashicons dashicons-linkedin"></span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <?php endwhile;?>
</div>
<?php return ob_get_clean();

}

add_shortcode('employee', 'sel_shortcode') ?>