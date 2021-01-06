<?php

class Add_Meta_Boxes
{

    /**
     * Save and intantiate all Meta Boxes
     */

    public function jokes_rater_meta_boxes(){
        $this->liked_jokes_ID_meta_box();
        $this->disliked_jokes_ID_meta_box();
    }

    public function save_jokes_rater_meta_boxes($post_id){
        $this->save_liked_jokes_ID($post_id);
        $this->save_disliked_jokes_ID($post_id);
    }

    /**
     * Liked Jokes Meta Box
     */

    public function liked_jokes_ID_meta_box(){
        add_meta_box(
            'liked_jokes_ID', 
            __('Liked Jokes ID', 'jokes_rater_plugin'), 
            [$this, 'liked_jokes_ID_html'], 
            ['like']
        );
    }

    public function liked_jokes_ID_html($post){
        wp_nonce_field('save_liked_jokes_ID', 'liked_jokes_ID_nonce');
        $value = get_post_meta($post->ID, 'liked_post_ID_key', true);
    ?>

        <input type="number" name="liked_jokes_ID" value="<?php echo esc_attr($value); ?>">

    <?php
    }

    public function save_liked_jokes_ID($post_id){
        if(!isset($_POST['liked_jokes_ID_nonce'])){
            return;
        } 
    
        if(!wp_verify_nonce($_POST['liked_jokes_ID_nonce'], 'save_liked_jokes_ID')){
            return;
        }
    
        if(!isset($_POST['liked_jokes_ID'])){
            return;
        }
    
        $liked_jokes_ID = sanitize_text_field($_POST['liked_jokes_ID']);
    
        update_post_meta($post_id, 'liked_post_ID_key', $liked_jokes_ID);
    }

    /**
     * Disliked Jokes Meta Box
     */

    public function disliked_jokes_ID_meta_box(){
        add_meta_box(
            'disliked_jokes_ID', 
            __('Disliked Jokes ID', 'jokes_rater_plugin'), 
            [$this, 'disliked_jokes_ID_html'], 
            ['dislike']
        );
    }

    public function disliked_jokes_ID_html($post){
        wp_nonce_field('save_disliked_jokes_ID', 'disliked_jokes_ID_nonce');
        $value = get_post_meta($post->ID, 'disliked_post_ID_key', true);
    ?>

        <input type="number" name="disliked_jokes_ID" value="<?php echo esc_attr($value); ?>">

    <?php
    }

    public function save_disliked_jokes_ID($post_id){
        if(!isset($_POST['disliked_jokes_ID_nonce'])){
            return;
        } 
    
        if(!wp_verify_nonce($_POST['disliked_jokes_ID_nonce'], 'save_disliked_jokes_ID')){
            return;
        }
    
        if(!isset($_POST['disliked_jokes_ID'])){
            return;
        }
    
        $disliked_jokes_ID = sanitize_text_field($_POST['disliked_jokes_ID']);
    
        update_post_meta($post_id, 'disliked_post_ID_key', $disliked_jokes_ID);
    }

}