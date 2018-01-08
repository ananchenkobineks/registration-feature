<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class ReyReg_Actions_Filters {

	public function __construct() {

		add_action('init', array( $this, 'init') );
	}

	public function init() {

		add_filter( 'bp_get_displayed_user_nav_orders', array( $this, 'bp_user_nav_filter' ) );
		add_filter( 'bp_get_displayed_user_nav_forums', array( $this, 'bp_user_nav_forums_filter' ), 10, 2 );
		add_filter( 'bp_get_displayed_user_nav_groups', array( $this, 'bp_user_nav_groups_filter' ), 10, 2 );
	}

	public function bp_user_nav_filter( $arr ) {
		return "";
	}

	public function bp_user_nav_forums_filter( $html, &$user_nav_item ) {

		$link = get_site_url()."/forums";

		return "
			<li id='{$user_nav_item->css_id}-personal-li'><a id='user-{$user_nav_item->css_id}' href='{$link}''>{$user_nav_item->name}</a></li>
		";
	}

	public function bp_user_nav_groups_filter( $html, &$user_nav_item ) {

		$link = get_site_url()."/groups";

		return "
			<li id='{$user_nav_item->css_id}-personal-li'><a id='user-{$user_nav_item->css_id}' href='{$link}''>{$user_nav_item->name}</a></li>
		";
	}

}

new ReyReg_Actions_Filters();