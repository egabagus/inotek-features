<?php
/*
Plugin Name: Inotek Features
Plugin URI: http://inotek.org/
Description: Plugin to support Inotek Features
Author: Developer
Version: 0.1.0
 */

include plugin_dir_path(__FILE__) . 'includes/inotek_sme.php';
include plugin_dir_path(__FILE__) . 'includes/inotek_people.php';
include plugin_dir_path(__FILE__) . 'includes/inotek_partner.php';
include plugin_dir_path(__FILE__) . 'includes/inotek_testimonial.php';
include plugin_dir_path(__FILE__) . 'includes/inotek_forum.php';
include plugin_dir_path(__FILE__) . 'includes/inotek_achievement.php';
include plugin_dir_path(__FILE__) . 'includes/inotek_innovation.php';
include plugin_dir_path(__FILE__) . 'includes/inotek_journey.php';

function inotek_features_install() {}

register_activation_hook(__FILE__, 'inotek_features_install');

function inotek_features_remove() {}

register_activation_hook(__FILE__, 'inotek_features_remove');

function inotek_custom_post_type()
{
    inotek_sme_post_type();
    inotek_partner_post_type();
    inotek_people_post_type();
    inotek_testimonial_post_type();
    inotek_forum_post_type();
    inotek_innovation_post_type();
    inotek_achievement_post_type();
    inotek_journey_post_type();
}

add_action('init', 'inotek_custom_post_type');

function inotek_feature_meta_boxes()
{
    inotek_people_meta_box();
    inotek_sme_meta_box();
    inotek_partner_meta_box();
    inotek_testimonial_meta_box();
    inotek_forum_meta_box();
    inotek_innovation_meta_box();
    inotek_achievement_meta_box();
    inotek_journey_meta_box();
}

add_action('add_meta_boxes', 'inotek_feature_meta_boxes', 1);
