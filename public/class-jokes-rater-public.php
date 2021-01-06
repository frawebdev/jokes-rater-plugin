<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       frawebdev.com
 * @since      1.0.0
 *
 * @package    Jokes_Rater
 * @subpackage Jokes_Rater/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Jokes_Rater
 * @subpackage Jokes_Rater/public
 * @author     FraWebDev <fra@frawebdev.com>
 */
class Jokes_Rater_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jokes_Rater_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jokes_Rater_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/jokes-rater-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Jokes_Rater_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Jokes_Rater_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/jokes-rater-public.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Init post types
	 */

	public function jokes_rater_all_post_types(){
		$this->joke_post_type();
		$this->like_post_type();
		$this->dislike_post_type();
	}

	/**
	 * Register the Joke Post Type
	 */

	public function joke_post_type(){
		register_post_type( 'jokes',
		// CPT Options
			array(
				'labels' => array(
					'name' => __( 'Jokes' ),
					'singular_name' => __( 'Joke' )
				),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'jokes'),
				'show_in_rest' => true,
				'supports'	=> ['title']
	 
			)
		);
	}

	/**
	 * Register the Like Post Type
	 */

	public function like_post_type(){
		register_post_type( 'like',
		// CPT Options
			array(
				'labels' => array(
					'name' => __( 'Likes' ),
					'singular_name' => __( 'Like' )
				),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'likes')
	 
			)
		);
	}

	/**
	 * Register the Like Post Type
	 */

	public function dislike_post_type(){
		register_post_type( 'dislike',
		// CPT Options
			array(
				'labels' => array(
					'name' => __( 'Dislikes' ),
					'singular_name' => __( 'Dislike' )
				),
				'public' => true,
				'has_archive' => true,
				'rewrite' => array('slug' => 'dislikes')
	 
			)
		);
	}

	/**
	 * Collect Jokes from the rest API and send it to a shortcode
	 */

	 public function collect_jokes(){



		add_filter( 'https_local_ssl_verify', '__return_false' );

		add_filter( 'block_local_requests', '__return_false' );

		$endpoint = get_site_url() . '/wp-json/jokes/v1/selectjoke';

		$response = wp_remote_get($endpoint);

		$response_body = wp_remote_retrieve_body($response);

		$json_response = json_decode($response_body);

		$jokes_output = '<section>';

		for($i = 0; $i < count($json_response); $i += 1){
			$jokes_output .= '<div class="row">';
			$jokes_output .= '<p class="text-yellow-400">' . $json_response[$i]->content . '</p>';
			$jokes_output .= 
			'<form action="" data-exists="' . $json_response[$i]->exist_status . '" method="POST" class="columns twelve">
			<input name="joke_id" value="' . $json_response[$i]->id . '" type="hidden">
			<input name="joke_author_id" value="' . $json_response[$i]->author_id . '" type="hidden">
			<button type="submit" name="like_btn">Likes(' . $json_response[$i]->jokes_rates . ')</button>
			<button type="submit" name="dislike_btn">Dislikes(' . $json_response[$i]->jokes_dislike_rates . ')</button>
			</form>';
			$jokes_output .= '</div>';
		}

		$jokes_output .= '</section>';

		return $jokes_output;

	 }

	 /**
	  * Create new Like Post
	  */

	  public function create_like_post(){

		if(isset($_POST['like_btn'])){

			if(is_user_logged_in()){

				$args = [
					'author'	=> $_POST['joke_author_id'],
					'post_type'	=> ['like', 'dislike']
				];

				$exist_query = new WP_Query($args);

				wp_reset_postdata();

				if($exist_query->found_posts == 0){
					return wp_insert_post([
						'post_type'		=> 'like',
						'post_status'	=> 'publish',
						'meta_input'	=> [
							'liked_post_ID_key'	=> $_POST['joke_id']
						]
					]);
				}

			} 

		}

		if(isset($_POST['dislike_btn'])){

			if(is_user_logged_in()){

				$args = [
					'author'	=> $_POST['joke_author_id'],
					'post_type'	=> ['like', 'dislike']
				];

				$exist_query = new WP_Query($args);

				wp_reset_postdata();

				if($exist_query->found_posts == 0){
					return wp_insert_post([
						'post_type'		=> 'dislike',
						'post_status'	=> 'publish',
						'meta_input'	=> [
							'disliked_post_ID_key'	=> $_POST['joke_id']
						]
					]);
				}

			} 

		}

	  }

} 
