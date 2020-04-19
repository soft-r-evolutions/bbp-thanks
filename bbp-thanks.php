<?php
/*
Plugin Name: bbPress Thanks 
Plugin URI: http://wordpress.org/extend/plugins/bbp-thanks/
Author: ewae
Author URI: https://www.game-dates.com
Description: Vote bbPress topics and replies up or down to surface the best replies
Version: 1.0.0
Requires at least: 3.0.0
Tested up to: 5.4
License: GPLv3
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Enqueue Scripts

add_action('wp_enqueue_scripts', 'bbp_thanks_scripts');

function bbp_thanks_scripts() {
    wp_enqueue_style( 'bbp-thanks-css', trailingslashit(plugin_dir_url(__FILE__)) . 'css/bbp-thanks.css', array() );
    if(function_exists( 'is_amp_endpoint' ) && is_amp_endpoint()) {
        // AMP = No JS
    } else {
        wp_enqueue_script( 'bbp-thanks-js', trailingslashit(plugin_dir_url(__FILE__)) . 'js/bbp-thanks.js', array('jquery') );
        wp_localize_script( 'bbp-thanks-js', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
    }
}

// Vote Buttons and Score

add_action('bbp_template_before_lead_topic', 'bbp_thanks_buttons');
add_action('bbp_theme_before_topic_author_details', 'bbp_thanks_buttons');
add_action('bbp_theme_before_reply_author_details', 'bbp_thanks_buttons');

function bbp_thanks_buttons(){
    $post = bbpress()->reply_query->post;
    if(!empty($post)) {
        // Filter Hook: 'bbp_voting_only_topics'
        if(apply_filters('bbp_voting_only_topics', false) && $post->post_type !== 'topic') return;
        // Filter Hook: 'bbp_voting_only_replies'
        if(apply_filters('bbp_voting_only_replies', false) && $post->post_type !== 'reply') return;

        $post_id = $post->ID;
        $forum_id = get_post_meta($post_id, '_bbp_forum_id', true);
        // Filter Hook: 'bbp_voting_allowed_on_forum'
        if(!apply_filters('bbp_voting_allowed_on_forum', true, $forum_id)) return;
        
        $score = (int) get_post_meta($post_id, 'bbp_voting_score', true);
        $ups = (int) get_post_meta($post_id, 'bbp_voting_ups', true);
//        $downs = (int) get_post_meta($post_id, 'bbp_voting_downs', true);
        // Check for and correct discrepancies
//        $calc_score = $ups + $downs;
//        if($score > $calc_score) {
//            $diff = $score - $calc_score;
//            $ups += $diff;
//            update_post_meta($post_id, 'bbp_voting_ups', $ups);
//        }
        // Get user's vote by ID or IP
        $voting_log = get_post_meta($post_id, 'bbp_voting_log', true);
        $voting_log = is_array($voting_log) ? $voting_log : array(); // Set up new array
        $client_ip = $_SERVER['REMOTE_ADDR'];
        $identifier = is_user_logged_in() ? get_current_user_id() : $client_ip;
        $existing_vote = array_key_exists($identifier, $voting_log) ? $voting_log[$identifier] : 0;
        echo '<div class="bbp-thanks'. ($existing_vote == 1 ? ' voted-up' : '') .'">';
            if ($existing_vote == 1) {
                echo '<a class="bbp-thanks-button remove" title="Remove thanks." onclick="bbpress_post_thank_link_clicked(' . $post_id . ', -1); return false;">'.$score.' thanks!</a>';
            } else if ($existing_vote == 0 || $existing_vote == -1) {
                echo '<a class="bbp-thanks-button up" title="Say Thank you!" onclick="bbpress_post_thank_link_clicked(' . $post_id . ', 1); return false;">'.$score.' thanks!</a>';
            }
        echo '</div>';
    }
}

// Process a vote

add_action('wp_ajax_bbpress_post_thank_link_clicked','bbpress_post_add_thank');
add_action('wp_ajax_nopriv_bbpress_post_thank_link_clicked','bbpress_post_add_thank');

function bbpress_post_add_thank(){
    $post_id = (int) $_POST['post_id'];
    $direction = (int) $_POST['direction'];
    $direction = in_array($direction, [1, -1]) ? $direction : 0; // Enforce 1 or -1
    // $voting_cookie = unserialize($_COOKIE['bbp_voting']);
    $voting_log = get_post_meta($post_id, 'bbp_voting_log', true);
    $voting_log = is_array($voting_log) ? $voting_log : array(); // Set up new array
    $client_ip = $_SERVER['REMOTE_ADDR'];
    $identifier = is_user_logged_in() ? get_current_user_id() : $client_ip;

    // All good, add the user's vote
    $score = (int) get_post_meta($post_id, 'bbp_voting_score', true);
    if($direction > 0) {
        $ups = (int) get_post_meta($post_id, 'bbp_voting_ups', true);
        update_post_meta($post_id, 'bbp_voting_ups', $ups + 1);
        $score = $score + 1;
    } elseif($direction < 0) {
        $ups = (int) get_post_meta($post_id, 'bbp_voting_ups', true);
        update_post_meta($post_id, 'bbp_voting_ups', $ups - 1);
        $score = $score - 1;
    }
    // Update the score
    update_post_meta($post_id, 'bbp_voting_score', $score);
    // Log the user's ID or IP
    $voting_log[$identifier] = $direction;
    update_post_meta($post_id, 'bbp_voting_log', $voting_log);
    // Set the cookie
    // $voting_cookie[$post_id] = true;
    // setcookie('bbp_voting', serialize($voting_cookie), time() + (86400 * 30 * 365), '/');
    do_action('bbp_voting_voted', $post_id, $direction, $score);
    echo $score;
    exit;
}

// Sort by Votes

add_filter('bbp_has_replies_query', 'sort_bbpress_replies_by_thanks');

function sort_bbpress_replies_by_thanks( $args = array() ) {
    // Filter Hook: 'sort_bbpress_replies_by_thanks'
    if(!apply_filters('sort_bbpress_replies_by_thanks', false)) return $args;
    $bbPress_post_id = get_the_ID();
    // Filter Hook: 'bbp_voting_allowed_on_forum'
    if(!apply_filters('bbp_voting_allowed_on_forum', true, $bbPress_post_id)) return $args;
    $bbPress_post_type = get_post_type($bbPress_post_id);
    if( $bbPress_post_type == bbp_get_topic_post_type() ){

        // Find any replies that are missing the bbp_voting_score post meta and fill them with 0
        $args2 = $args;
        $args2['meta_query'] = [
            [
                'key' => 'bbp_voting_score',
                'compare' => 'NOT EXISTS',
                'value' => ''
            ],
        ];
        $query = new WP_Query($args2);
        foreach($query->posts as $reply) {
            update_post_meta($reply->ID, 'bbp_voting_score', '0');
        }

        // Now that all missing scores are filled in, we can sort the original args by the score
        $args['meta_key'] = 'bbp_voting_score';
        $args['orderby'] = [
            'post_type' => 'DESC', 
            'meta_value_num' => 'DESC'
        ];
    }
    return $args;
}

// Hook Usage Examples

// add_filter( 'bbp_voting_show_labels', '__return_false' );
// add_filter( 'bbp_voting_only_topics', '__return_true' );
// add_filter( 'sort_bbpress_admin_bypass', '__return_true' );
// add_filter( 'sort_bbpress_replies_by_thanks', '__return_true' );
// add_filter( 'bbp_voting_helpful', function() { return "Good" } );
// add_filter( 'bbp_voting_not_helpful', function() { return "Bad" } );

// add_action( 'bbp_voting_voted', 'my_function_on_bbp_vote', 10, 3 );
// function my_function_on_bbp_vote( $post_id, $direction, $score ) {
//     // Do something
//     // $post_id will be the ID of the topic or reply that was voted on
//     // $direction will be 1 or -1 representing the upvote or downvote
//     // $score will be the new total score
// }

// add_filter( 'bbp_voting_allowed_on_forum', 'allowed_voting_forums', 10, 2 );
// function allowed_voting_forums( $allowed, $forum_id ) {
//     if( in_array( $forum_id, array(123, 124, 125) ) ) {
//         $allowed = true;
//     } else {
//         $allowed = false;
//     }
//     return $allowed;
// }
