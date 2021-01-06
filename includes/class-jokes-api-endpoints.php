<?php

class Jokes_Rater_API_Endpoints
{

    /**
     * Add All Endpoints
     */

     public function all_api_endpoints(){
         $this->joke_post_type_route();
     }


    /**
     * Create Joke Post Type Route
     */
    
    public function joke_post_type_route(){
        register_rest_route('jokes/v1/', 'selectJoke', [
			'methods'	=> 'GET',
			'callback'	=> [$this, 'jokes_results']
		]);
    }

    public function jokes_results(){
		$args = [
			'post_type'			=> 'jokes',
			'posts_per_page'	=> -1
		];

		$jokes = new WP_Query($args);

		wp_reset_postdata();

		$results = [];

		while($jokes->have_posts()){
			$jokes->the_post();

			/**
			 * like in the endpoint
			 */
			
			$rates_args = [
				'post_type'	=> 'like',
				'meta_query'=> [
					[
						'key'		=> 'liked_post_ID_key',
						'compare'	=> '=',
						'value'		=> get_the_ID()
					] 
				]
			];

			$jokes_rates = new WP_Query($rates_args);

			wp_reset_postdata();

			/**
			 * dislike in the endpoint
			 */

			$dislike_rates_args = [
				'post_type'	=> 'dislike',
				'meta_query'=> [
					[
						'key'		=> 'disliked_post_ID_key',
						'compare'	=> '=',
						'value'		=> get_the_ID()
					] 
				]
			];

			$jokes_dislike_rates = new WP_Query($dislike_rates_args);

			wp_reset_postdata();

			/**
			 * the array sent to the API
			 */

			array_push($results, [
				'title'					=> get_the_title(),
				'content'				=> get_the_content(),
				'id'					=> get_the_ID(),
				'permalink' 			=> get_the_permalink(),
				'author_id'				=> get_the_author_meta('ID'),
				'jokes_rates'			=> $jokes_rates->found_posts,
				'jokes_dislike_rates'	=> $jokes_dislike_rates->found_posts
			]);

		} 

		return $results;

    }

}