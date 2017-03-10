<?php
/**
 * Plugin Name: Faster Media Library Queries
 * Plugin URI:  http://wordpress.org/plugins
 * Description: By default, the Media Library search functionality performs a meta query. This plugin removes that join, to prevent database overload on sites with large meta tables.
 * Version:     0.1.0
 * Author:      Nathaniel Taintor, 10up
 * License:     GPLv2+
 * Text Domain: fmlqueries
 * Domain Path: /languages
 */

/**
 * Copyright (c) 2017 10up (email : info@10up.com)
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2 or, at
 * your discretion, any later version, as published by the Free
 * Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Built using yo wp-make:plugin
 * Copyright (c) 2015 10up, LLC
 * https://github.com/10up/generator-wp-make
 */

add_action( 'admin_init', 'fmlqueries_init' );

/**
 * Initializes the plugin and fires an action other plugins can hook into.
 *
 * @uses add_filter()
 * @uses do_action()
 *
 * @return void
 */
function fmlqueries_init() {
	if ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) {
		return;
	}

	add_filter( 'ajax_query_attachments_args', 'fmlqueries_filter_ajax_attachments_query_args', 100 );
}

/**
 * When an Ajax request for attachments is recieved, remove the meta LEFT JOIN
 * that core applies to search for the filename in the `_wp_attached_file` meta field.
 *
 * @param array $query Args passed to WP_Query
 */
function fmlqueries_filter_ajax_attachments_query_args( $query ) {

	// Only attachment queries with search terms are a problem here.
	if ( empty( $query['s'] ) ) {
		return $query;
	}

	remove_filter( 'posts_clauses', '_filter_query_attachment_filenames' );
	add_filter( 'posts_search', 'fmlqueries_filter_posts_search', 10, 2 );

	return $query;
}

/**
 * Filter the SQL clauses of an attachment query to search in the `guid` field
 * for possible filename matches that aren't matched in the title or content fields.
 *
 * @global wpdb $wpdb WordPress database abstraction object.
 *
 * @param string $search The part of the WHERE clause formatted by <@link|WP_Query::parse_search()>
 * @param WP_Query $wp_query The current query object. Passed by reference.
 * @return array The modified clauses.
 */
function fmlqueries_filter_posts_search( $search, $wp_query ) {
	global $wpdb;

	$search = preg_replace(
		"/\({$wpdb->posts}.post_content (NOT LIKE|LIKE) ('[^']+')\)/", "$0 OR ({$wpdb->posts}.guid $1 $2)", $search
	);

	return $search;
}
