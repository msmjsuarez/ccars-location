<?php

/* this is use for cronjobs after csv file is being imported*/

require_once('../../../wp-config.php');

class CcarsCreateLocationKey { 
       
    function __construct() { 

    	global $wpdb;

		$results = $wpdb->get_results("SELECT * FROM $wpdb->posts WHERE $wpdb->posts.post_type = 'listings'");


		foreach ($results as $post) :

			$post_id = $post->ID;
			$post_content = $post->post_content;

			if (stripos($post_content,'kelowna')) :
		    	$dealer_location = "kelowna";
			elseif (stripos($post_content,'vancouver')) :
		    	$dealer_location = "vancouver";
			else:
			    $dealer_location = "vancouver";
			endif;


			$check_city_exist = $wpdb->get_results("SELECT * FROM $wpdb->postmeta 
					WHERE $wpdb->postmeta.post_id = $post_id and $wpdb->postmeta.meta_key = 'city'");

			
				if($wpdb->num_rows > 0) :
					
					$wpdb->update( 
						"$wpdb->postmeta", 
						array( 
							'meta_value' => $dealer_location
						), 
						array( 
							'post_id' => $post_id,
							'meta_key' => 'city' 
						), 
						array(
							'%s'
						), 
						array( 
							'%d', 
							'%s' 
						) 
					);
		
				else :

					$wpdb->insert( 
						"$wpdb->postmeta", 
						array( 
							'post_id' => $post_id, 
							'meta_key' => 'city',
							'meta_value' => $dealer_location
						), 
						array( 
							'%d', 
							'%s',
							'%s'
						) 
					);

				endif;

			


    	?>
    		<li><?php echo $post_id; ?> <a href="<?php echo $post->guid; ?>"><?php echo $post->post_title; ?></a></li>

    	<?php

		endforeach;

		echo '<h2>Task done!</h2>';

    } 
} 


$CcarsCreateLocationKey = new CcarsCreateLocationKey; 


?>
