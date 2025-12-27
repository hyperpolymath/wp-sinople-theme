<?php
/**
 * IndieWeb Integration for Sinople Theme
 *
 * Implements Webmention and Micropub endpoints for IndieWeb Level 4 compliance
 *
 * @package Sinople
 * @since 1.0.0
 */

declare(strict_types=1);

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Add IndieWeb discovery links to head
 */
function sinople_indieweb_discovery() {
    $webmention_endpoint = rest_url( 'sinople/v1/webmention' );
    $micropub_endpoint   = rest_url( 'sinople/v1/micropub' );

    echo '<link rel="webmention" href="' . esc_url( $webmention_endpoint ) . '">' . "\n";
    echo '<link rel="micropub" href="' . esc_url( $micropub_endpoint ) . '">' . "\n";
    echo '<link rel="authorization_endpoint" href="https://indieauth.com/auth">' . "\n";
    echo '<link rel="token_endpoint" href="https://tokens.indieauth.com/token">' . "\n";
}
add_action( 'wp_head', 'sinople_indieweb_discovery' );

/**
 * Register IndieWeb REST API endpoints
 */
function sinople_register_indieweb_api() {
    // Webmention endpoint
    register_rest_route( 'sinople/v1', '/webmention', array(
        'methods'             => 'POST',
        'callback'            => 'sinople_webmention_endpoint',
        'permission_callback' => '__return_true',
    ) );

    // Micropub endpoint
    register_rest_route( 'sinople/v1', '/micropub', array(
        'methods'             => array( 'GET', 'POST' ),
        'callback'            => 'sinople_micropub_endpoint',
        'permission_callback' => 'sinople_micropub_permission',
    ) );
}
add_action( 'rest_api_init', 'sinople_register_indieweb_api' );

/**
 * Webmention endpoint handler
 */
function sinople_webmention_endpoint( $request ) {
    $source = $request->get_param( 'source' );
    $target = $request->get_param( 'target' );

    // Validate parameters
    if ( empty( $source ) || empty( $target ) ) {
        return new WP_Error( 'invalid_request', 'Source and target required', array( 'status' => 400 ) );
    }

    // Verify target is on this site
    $home_url = home_url();
    if ( strpos( $target, $home_url ) !== 0 ) {
        return new WP_Error( 'invalid_target', 'Target not on this site', array( 'status' => 400 ) );
    }

    // Queue webmention for async processing
    $webmention_id = wp_insert_post( array(
        'post_type'   => 'sinople_webmention',
        'post_status' => 'pending',
        'post_title'  => $source,
        'meta_input'  => array(
            '_sinople_wm_source' => esc_url_raw( $source ),
            '_sinople_wm_target' => esc_url_raw( $target ),
        ),
    ) );

    return new WP_REST_Response( array(
        'status'  => 'accepted',
        'message' => 'Webmention accepted for processing',
    ), 202 );
}

/**
 * Micropub permission callback
 *
 * Verifies the IndieAuth bearer token with the token endpoint.
 *
 * @param WP_REST_Request $request The request object.
 * @return bool|WP_Error True if authorized, WP_Error otherwise.
 */
function sinople_micropub_permission( WP_REST_Request $request ): bool|WP_Error {
    // GET requests for config queries don't require auth
    if ( $request->get_method() === 'GET' ) {
        return true;
    }

    $token = $request->get_header( 'Authorization' );

    if ( empty( $token ) ) {
        return new WP_Error( 'unauthorized', 'Authorization required', array( 'status' => 401 ) );
    }

    // Verify token with IndieAuth using security helper
    $security = sinople_security();
    $result = $security->verify_indieauth_token( $token );

    if ( is_wp_error( $result ) ) {
        return $result;
    }

    // Check for create scope
    $scopes = $result['scope'] ?? '';
    if ( strpos( $scopes, 'create' ) === false && strpos( $scopes, 'post' ) === false ) {
        return new WP_Error( 'insufficient_scope', 'Token lacks create scope', array( 'status' => 403 ) );
    }

    return true;
}

/**
 * Micropub endpoint handler
 *
 * Handles Micropub configuration queries and post creation.
 *
 * @param WP_REST_Request $request The request object.
 * @return WP_REST_Response|WP_Error Response or error.
 */
function sinople_micropub_endpoint( WP_REST_Request $request ): WP_REST_Response|WP_Error {
    $method = $request->get_method();

    if ( $method === 'GET' ) {
        // Configuration query
        $q = $request->get_param( 'q' );

        if ( $q === 'config' ) {
            return new WP_REST_Response( array(
                'media-endpoint' => rest_url( 'sinople/v1/media' ),
            ) );
        }

        if ( $q === 'syndicate-to' ) {
            return new WP_REST_Response( array(
                'syndicate-to' => array(),
            ) );
        }

        return new WP_REST_Response( array(), 200 );
    }

    if ( $method === 'POST' ) {
        // Create post from Micropub request
        $content_type = $request->get_content_type();
        $security = sinople_security();

        if ( isset( $content_type['value'] ) && $content_type['value'] === 'application/json' ) {
            $data = $request->get_json_params();
        } else {
            $data = $request->get_body_params();
        }

        // Validate required fields
        if ( empty( $data ) ) {
            return new WP_Error( 'invalid_request', 'No data provided', array( 'status' => 400 ) );
        }

        // Extract and sanitize content
        // Micropub content can be string or array (with html/value keys)
        $raw_content = $data['content'] ?? '';
        if ( is_array( $raw_content ) ) {
            if ( isset( $raw_content['html'] ) ) {
                $content = $security->sanitize_micropub_content( $raw_content['html'], 'html' );
            } elseif ( isset( $raw_content['value'] ) ) {
                $content = $security->sanitize_micropub_content( $raw_content['value'], 'text' );
            } elseif ( isset( $raw_content[0] ) ) {
                $content = $security->sanitize_micropub_content( $raw_content[0], 'text' );
            } else {
                $content = '';
            }
        } else {
            $content = $security->sanitize_micropub_content( $raw_content, 'text' );
        }

        // Sanitize title
        $raw_title = $data['name'] ?? '';
        if ( is_array( $raw_title ) && isset( $raw_title[0] ) ) {
            $raw_title = $raw_title[0];
        }
        $title = is_string( $raw_title ) ? sanitize_text_field( $raw_title ) : '';

        // If no title, generate from content
        if ( empty( $title ) && ! empty( $content ) ) {
            $title = wp_trim_words( wp_strip_all_tags( $content ), 10, '...' );
        }

        // Parse h-entry microformat with sanitized data
        $post_data = array(
            'post_type'    => 'post',
            'post_status'  => 'publish',
            'post_title'   => $title,
            'post_content' => $content,
        );

        // Handle category if provided
        if ( isset( $data['category'] ) ) {
            $categories = is_array( $data['category'] ) ? $data['category'] : array( $data['category'] );
            $category_ids = array();
            foreach ( $categories as $cat ) {
                if ( is_string( $cat ) ) {
                    $term = get_term_by( 'name', sanitize_text_field( $cat ), 'category' );
                    if ( $term ) {
                        $category_ids[] = $term->term_id;
                    }
                }
            }
            if ( ! empty( $category_ids ) ) {
                $post_data['post_category'] = $category_ids;
            }
        }

        $post_id = wp_insert_post( $post_data, true );

        if ( is_wp_error( $post_id ) ) {
            return new WP_Error( 'create_failed', 'Failed to create post: ' . $post_id->get_error_message(), array( 'status' => 500 ) );
        }

        $location = get_permalink( $post_id );

        return new WP_REST_Response( array( 'url' => $location ), 201, array(
            'Location' => $location,
        ) );
    }

    return new WP_Error( 'method_not_allowed', 'Method not allowed', array( 'status' => 405 ) );
}
