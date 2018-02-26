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



			// check if city exist
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
		
				else:

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


				foreach ($check_city_exist as $city_post) :

					$city_value = $city_post->meta_value;

				endforeach;



				//check if term relationships exist
				$kelowna = 3113; //to be checked on wp_terms term_id
				$vancouver = 3114; //to be checked on wp_terms term_id
				$check_rel_exist = $wpdb->get_results("SELECT * FROM $wpdb->term_relationships 
					WHERE $wpdb->term_relationships.object_id = $post_id and  
					$wpdb->term_relationships.term_taxonomy_id = $vancouver
					or $wpdb->term_relationships.object_id = $post_id and  
					$wpdb->term_relationships.term_taxonomy_id = $kelowna");

			
					if ($wpdb->num_rows > 0) :

						$wpdb->delete( 
							"$wpdb->term_relationships", 
							array( 
								'object_id' => $post_id,
							 	'term_taxonomy_id' => $kelowna
							 	), 
							array( 
								'%d',
								'%d'
								) 
						);

						$wpdb->delete( 
						"$wpdb->term_relationships", 
						array( 
							'object_id' => $post_id,
						 	'term_taxonomy_id' => $vancouver
						 	), 
						array( 
							'%d',
							'%d' 
							) 
						);
					
					endif;	


					if ($city_value == 'kelowna') :
						$term_taxonomy_id = 3113;
					elseif ($city_value == 'vancouver') :
						$term_taxonomy_id = 3114;
					endif;


					$wpdb->insert( 
						"$wpdb->term_relationships", 
						array( 
							'object_id' => $post_id, 
							'term_taxonomy_id' => $term_taxonomy_id
						), 
						array( 
							'%d', 
							'%d'
						) 
					);

    	?>
    		<div style="border-bottom: 1px dashed grey; margin: 10px;"><a target="_blank" href="<?php echo $post->guid; ?>"><?php echo $post->post_title; ?></a></div>

    	<?php

		endforeach;

		echo '<h2>Car location updated!</h2>';

    } 
} 


$CcarsCreateLocationKey = new CcarsCreateLocationKey; 


?>
