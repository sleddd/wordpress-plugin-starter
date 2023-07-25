<?php 
    /* Single Post Query - Block Template */
    $selected_post  = json_decode( $attributes['selectedPost'] );
    $post_id        = $selected_post->value;
    $post           = $post_id ? get_post( $post_id ) : null;
    $title          = null;
    $category       = null;
    $cat_link       = null;
    $image          = null;
    $excert         = null;

    if ( $post ) {
        $title      = $post->post_title ? $post->post_title : $title ;
        $category   = get_the_category( $post_id );

        // Get category name if applicable.
        //var_dump($category);
        if ( ! empty( $category ) ) {
            $cat_link = site_url( $category[0]->taxonomy. '/' . $category[0]->slug );
            $category = $category[0]->name;
        } else {
            $category = null;
        }

        $image      = get_the_post_thumbnail_url( $post_id );
        $excerpt    = $post->post_content ? wp_trim_words( strip_shortcodes( $post->post_content ), 15, '...' ) : $excerpt;
        $permalink  = get_the_permalink( $post_id );
    }
?>
<div class="wpstarterplugin-single-post-query">
    <?php 
        if ( empty ( $image ) ) {
            echo '<img src="' . WP_STARTER_PLUGIN_URL  . 'src/assets/images/single-post-query-placeholder.png"/>';
        } else {
            echo sprintf( '<img src="%s"/>', esc_url( $image ) );
        } 
    ?>
    <hr/>
    <?php 
        // Add the category.
        if ( empty ( $category )  ) { 
            echo '<h3>Nostrud Exercitation</h3>';
        } else {
            if ( ! empty ( $cat_link ) ) {
                echo sprintf( '<a href="%s">', esc_url( $cat_link ) );
            }
            echo sprintf( '<h3>%s</h3>', $category );
            if ( ! empty ( $cat_link ) ) {
                echo '</a>';
            }
        }
        // Add the title.
        if ( empty ( $title )  ) { 
            echo '<h2>Lorem Ipsum</h2>';
        } else {
            if ( ! empty ( $permalink ) ) {
                echo sprintf( '<a href="%s">', esc_url( $permalink ) );
            }
            echo sprintf('<h2>%s</h2>',$title);
            
            if ( ! empty ( $permalink ) ) {
                echo '</a>';
            }
        }
        
        // Add the excerpt.
        if ( empty ( $excerpt )  ) { 
            echo '<p>Adipisci velit, sed quia non numquam eius modi tempora indcidunt ut labore et dolore quaerat voluptatem.</p>';
        } else {
            echo sprintf('<p>%s<p>', $excerpt );
        }
    ?>    
</div>