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
