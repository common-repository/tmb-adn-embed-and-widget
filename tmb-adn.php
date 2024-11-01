<?php
/*
Plugin Name: TMB App.net Embed and Widget
Plugin URI: http://www.bensmann.no/downloads/wordpress-adn-plugin/
Description: Adds ADN functionality to WordPress
Version: 0.1.4.1
Author: Thomas Bensmann
Author URI: http://thomas.bensmann.no
License: GPL2

    Copyright 2012  Thomas Bensmann  (email : thomas@bensmann.no)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

require_once( dirname(__FILE__) . '/scb/load.php' );
require_once( dirname(__FILE__) . '/widget.php');


wp_embed_register_handler( 'tmb_adn', '#(https|http)://alpha.app.net/(.+?)/post/(.+?)($|&)#i', 'tmb_adn_embed_handler' );

function tmb_adn_embed_handler( $matches, $attr, $url, $rawattr ) {
	return '[tmb_adn_post id="'.$matches[3].'"]';
}


if ( ! class_exists( 'TMB_ADN' ) ):

	class TMB_ADN {
		public  $url,
				$json,
				$cache,
				$expires = 180;
		
		private	$_options,
				$_transient,
				$_transient_prefix = 'tmb_adn_data__';
				
		function __construct(){
			
			$this->_options = $this->_get_options();
			
			if ( $this->_options->get('post_expires') == false ) {
				$this->expires = -1;
			}else{
				$this->expires = $this->_options->get('post_expires_time');
			}
			
		}
		
		protected function _get_options(){
			return new scbOptions( 'tmb_adn_options', __FILE__, array(
				'display_name'		=> 'username',
				'display_client'	=> 'yes',
				'open_links_new'	=> 'yes',
				'load_stylesheet'	=> 'yes',
				'post_expires'		=> 'no',
				'post_expires_time'	=> (60*60),
				'current_version'	=> 0,
				'version'			=> 1.3
			));
		}
		
		public function admin_init(){
			$options = self::_get_options();

			// Creating settings page objects
			if ( is_admin() ) {
				require_once( dirname( __FILE__ ) . '/admin.php' );
				new TMB_ADN_Admin_Page( __FILE__, $options );
			}
		}
		
		public static function load_resources(){
			$options = self::_get_options();
			
			if($options->get('load_stylesheet') == "yes"){
				wp_enqueue_style( 'tmb_adn_style', plugins_url( '/style.min.css', __FILE__ ), array(), ".14");
			}
		}

		public static function update(){
			$options = self::_get_options();
			$version = $options->get( 'version' );
			$current_version = $options->get( 'current_version' );

			if( $current_version < $version ){
				
				if( $current_version == 0 ){
					self::flush_db();
				}

				$options->set( 'current_version' , $version );
			}
		}
		
		public static function flush_db(){
			global $wpdb;
			
			$sql = 'DELETE FROM '.$wpdb->prefix.'options WHERE option_name LIKE "tmb_adn_data_%"';
			
			return $wpdb->query($sql);
			return $sql;
		}
		
		public static function get_post_by_id($id){
			$adn = new self();
			
			$adn->_transient = $adn->_transient_prefix . $id;
			$adn->url = "https://alpha-api.app.net/stream/0/posts/".$id;
			
			if($adn->has_cached())
				return $adn->get_cached();
			
			$adn->fetch_json();
			$adn->set_cached();
			
			return $adn;
		}
		
		public function has_cached(){
			if ($this->_options->get('post_expires') === 'yes' && ( $this->cache = get_transient( $this->_transient )) !== false ){
				$this->cache->cache_from = "transient";
				return true;
			}
			else if($this->_options->get('post_expires') === 'no' && ( $this->cache = get_option( $this->_transient )) !== false ){
				$this->cache->cache_from = "option";
				return true;
			}else
				return false;
		}
		
		public function get_cached(){
			$this->cache->cache = true;
			return $this->cache;
		}
	
		public function set_cached(){
			if($this->_options->get('post_expires') == 'yes')
				set_transient( $this->_transient, $this, $this->expires );
			else
				add_option( $this->_transient, $this, '', 'no' );
		}
		
		public function fetch_json(){
			$this->json = json_decode(file_get_contents($this->url));
		}
		
		public static function render_embed($atts){
			extract( shortcode_atts( array(
				'id' => false
			), $atts ) );
			
			if($id === false)
				return;
		
			$adn = self::get_post_by_id($id);
			
			$ext_target = "";
			
			if($adn->_options->get('open_links_new','yes') == 'yes'){
				$adn->json->data->html = preg_replace("/<a(.*?)>/", "<a$1 target=\"_blank\">", $adn->json->data->html);
				$ext_target = 'target="_blank"';
			}
			
			ob_start();
			?>
			<div class="tmb_adn_frame">
				<div class="tmb_adn_post">
					<a href="http://alpha.app.net/<?php echo $adn->json->data->user->username; ?>" <?php echo $ext_target; ?> class="tmb_adn_user">
						<img src="<?php echo $adn->json->data->user->avatar_image->url; ?>" alt="@<?php echo $adn->json->data->user->username; ?>" class="tmb_adn_user_image"></a><div class="tmb_adn_post_body">
						<a href="http://alpha.app.net/<?php echo $adn->json->data->user->username; ?>" <?php echo $ext_target; ?> class="tmb_adn_user_link">
							<?php echo ($adn->_options->get('display_name') == 'username' ? $adn->json->data->user->username : $adn->json->data->user->name) ; ?>
						</a><?php echo nl2br($adn->json->data->html); ?>
						
						<div class="tmb_adn_meta">
							<a href="<?php echo $adn->json->data->canonical_url ; ?>" <?php echo $ext_target; ?> class="tmb_adn_post_date">
								<?php $date = strtotime($adn->json->data->created_at); echo date("j. F Y",$date); ?>
							</a>
							<?php if($adn->_options->get('display_client') == "yes" && $adn->json->data->source): ?>
							via <a href="<?php echo $adn->json->data->source->link; ?>" <?php echo $ext_target; ?>>
								<?php echo $adn->json->data->source->name; ?>
							</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
			<?php
			$r = apply_filters('tmb_adn_embed_rendered' ,ob_get_contents()) ;
			ob_end_clean();
			return $r;
		}
	}

endif;



add_shortcode("tmb_adn_post",array("TMB_ADN","render_embed"));

add_action( 'wp_enqueue_scripts', array("TMB_ADN","load_resources") ); 	

scb_init( array( 'TMB_ADN', 'admin_init' ) );

add_action( 'admin_init', array('TMB_ADN','update') );


?>