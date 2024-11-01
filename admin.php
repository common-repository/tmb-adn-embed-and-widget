<?php

class TMB_ADN_Admin_Page extends scbAdminPage {

	function setup() {
		$this->args = array(
			'menu_title' => 'TMB ADN',
			'page_title' => __( 'TMB App.net plugin options', 'tmb_adn' ),
		);
	}
	
	function page_content() {
		
		echo html( 'h3', __('Embed settings', 'tmb_adn') );
		
		echo $this->form_table(array(
			array(
				'title' => __( 'Display name', 'tmb_adn' ),
				'type' => 'select',
				'name' => 'display_name',
				'value' => array( 'username', 'full name' ),
				'desc' => __( 'Display the @username or the users full name', 'tmb_adn' )
			),array(
				'title' => __( 'Display App.net client', 'tmb_adn' ),
				'type' => 'radio',
				'name' => 'display_client',
				'value' => array( 'yes' => __('Yes', 'tmb_adn'), 'no' => __('No', 'tmb_adn') ),
			),array(
				'title' => __( 'Open links in new window', 'tmb_adn' ),
				'type' => 'radio',
				'name' => 'open_links_new',
				'value' => array( 'yes' => __('Yes', 'tmb_adn'), 'no' => __('No', 'tmb_adn') ),
			),array(
				'title' => __( 'Load plugin stylesheet', 'tmb_adn' ),
				'type' => 'radio',
				'name' => 'load_stylesheet',
				'value' => array( 'yes' => __('Yes', 'tmb_adn'), 'no' => __('No', 'tmb_adn') ),
			),array(
				'title' => __( 'Post expires', 'tmb_adn' ),
				'type' => 'radio',
				'name' => 'post_expires',
				'value' => array( 'yes' => __('Yes', 'tmb_adn'), 'no' => __('No', 'tmb_adn') ),
			),array(
				'title' => ' ',
				'type' => 'select',
				'name' => 'post_expires_time',
				'value' => array(
					(60*5) => __( "every 5 minutes", 'tmb_adn' ),
					(60*15) => __( "every 15 minutes", 'tmb_adn' ),
					(60*30) => __( "every 30 minutes", 'tmb_adn' ),
					(60*60) => __( "hourly", 'tmb_adn' ),
					(60*60*24) => __( "daily", 'tmb_adn' ),
					(60*60*24*7) => __( "weekly", 'tmb_adn' ),
				),
				'desc' => __( "How often should it be refreshed if it does expire?", 'tmb_adn' )
			)
		));
		
	}

}

?>