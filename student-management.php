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
