<?php

global $wpdb;
			
$sql = 'DELETE FROM '.$wpdb->prefix.'options WHERE option_name LIKE "tmb_adn_data_%"';

return $wpdb->query($sql);

?>