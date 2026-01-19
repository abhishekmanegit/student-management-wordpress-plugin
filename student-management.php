<?php
/**
 * Plugin Name: Student Management Plugin
 * Description: Custom WordPress plugin to manage student records.
 * Version: 0.1
 * Author: Abhishek Mane
 */

defined('ABSPATH') || exit;

add_action('init', 'smp_register_student_cpt');

function smp_register_student_cpt() {
    $args = array(
        'public' => true,
        'label'  => 'Students',
        'supports' => array('title'),
    );

    register_post_type('student', $args);
}

add_action('admin_menu', 'smp_add_admin_menu');

function smp_add_admin_menu() {
    add_menu_page(
        'Student Management',     // Page title
        'Students',               // Menu title
        'manage_options',         // Capability
        'student-management',     // Menu slug
        'smp_admin_page',         // Callback function
        'dashicons-welcome-learn-more', // Icon
        6                          // Position
    );
}

function smp_admin_page() {
    echo '<div class="wrap">';
    echo '<h1>Student Management</h1>';
    echo '<p>Welcome to the Student Management Plugin.</p>';
    echo '</div>';
}

add_action('add_meta_boxes', 'smp_add_student_meta_box');

function smp_add_student_meta_box() {
    add_meta_box(
        'smp_student_details',
        'Student Details',
        'smp_student_meta_box_callback',
        'student',
        'normal',
        'default'
    );
}

function smp_student_meta_box_callback($post) {
    $roll = get_post_meta($post->ID, '_smp_roll', true);
    $class = get_post_meta($post->ID, '_smp_class', true);
    ?>

    <label for="smp_roll">Roll Number</label><br>
    <input type="text" id="smp_roll" name="smp_roll" value="<?php echo esc_attr($roll); ?>" /><br><br>

    <label for="smp_class">Class</label><br>
    <input type="text" id="smp_class" name="smp_class" value="<?php echo esc_attr($class); ?>" />

    <?php
}

add_action('save_post', 'smp_save_student_meta');

function smp_save_student_meta($post_id) {

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    if (isset($_POST['smp_roll'])) {
        update_post_meta($post_id, '_smp_roll', sanitize_text_field($_POST['smp_roll']));
    }

    if (isset($_POST['smp_class'])) {
        update_post_meta($post_id, '_smp_class', sanitize_text_field($_POST['smp_class']));
    }
}

add_action('rest_api_init', 'smp_register_student_api');

function smp_register_student_api() {
    register_rest_route('smp/v1', '/students', array(
        'methods'  => 'GET',
        'callback' => 'smp_get_students',
    ));
}

function smp_get_students() {

    $args = array(
        'post_type'      => 'student',
        'posts_per_page' => -1,
    );

    $query = new WP_Query($args);

    $students = array();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();

            $students[] = array(
                'id'    => get_the_ID(),
                'name'  => get_the_title(),
                'roll'  => get_post_meta(get_the_ID(), '_smp_roll', true),
                'class' => get_post_meta(get_the_ID(), '_smp_class', true),
            );
        }
    }

    wp_reset_postdata();

    return $students;
}
