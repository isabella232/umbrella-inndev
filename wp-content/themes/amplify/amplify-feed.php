<?php
/**
 * Single Post Template: Amplify Feed
 * Template Name: Amplify Feed Template
 * Description: Shows the saved links feed that can be embedded on external sites.
 */
?>

<!DOCTYPE html>
<!--[if lt IE 7]> <html <?php language_attributes(); ?> class="no-js ie6"> <![endif]-->
<!--[if IE 7]>    <html <?php language_attributes(); ?> class="no-js ie7"> <![endif]-->
<!--[if IE 8]>    <html <?php language_attributes(); ?> class="no-js ie8"> <![endif]-->
<!--[if IE 9]>    <html <?php language_attributes(); ?> class="no-js ie9"> <![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--> <html <?php language_attributes(); ?> class="no-js"> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
</head>
<body>

    <div class="amplify-embed-feed">

        <?php

        if( isset( $_GET['tag'] ) ){
            
            $term = $_GET['tag'];
            $term = get_term_by( 'slug', $term, 'lr-tags' );
            
            if ( function_exists ( 'largo_get_term_meta_post' ) ) {
                
                $term_meta_post = largo_get_term_meta_post( 'lr-tags', $term->term_id );
                $term_meta = get_post_meta( $term_meta_post );

                if( $term_meta['lr_more_link'] ){
                    $term_more_link = $term_meta['lr_more_link'][0];
                }

                $thumbnail = get_the_post_thumbnail_url( $term_meta_post, 'large' );
                echo '<img src="'.$thumbnail.'">';

            }
            ?>
            <h2><?php echo $term->name; ?></h2>
            <p><?php echo $term->description; ?></p>
            <?php
                if( $term_more_link ){
                    echo '<a href="'.$term_more_link.'" target="_blank">Learn More</a>';
                }
            ?>
            <?php
        }

        $query_args = array (
            'post_type'    => 'rounduplink',
            'post_status'  => 'publish'
        );

        if( $term ){

            $tax_query = array (
                'taxonomy' => 'lr-tags',
                'field' => 'slug',
                'terms' => $term->slug,
            );

            $query_args['tax_query'] = array( $tax_query );

        }

        $my_query = new WP_Query( $query_args );

        if ( $my_query->have_posts() ) {

            echo '<h3>Stories from the Project</h3>';

            while ( $my_query->have_posts() ) : $my_query->the_post();
            $saved_link = get_post_custom( $post->ID );

            // skip roundups
            if ( get_post_type( $post ) === 'roundup' ) continue; ?>

            <div class="saved-link clearfix">
                <h5><?php
                    if ( isset( $saved_link["lr_url"][0] ) ) {
                        $output = '<a href="' . $saved_link["lr_url"][0] . '" ';
                        $output .= 'target="_blank" ';
                        $output .= '>' . get_the_title() . '</a>';
                    } else {
                        $output = get_the_title();
                    }
                    echo $output;
                    ?>
                </h5>

                <?php
                    if ( isset($saved_link["lr_source"][0] ) && !empty( $saved_link["lr_source"][0] ) ) {
                        $saved_link_source = '<p class="source">' . __('By: ', 'link-roundups') . '<span>';
                        if ( !empty( $saved_link["lr_url"][0] ) ) {
                            $saved_link_source .= '<a href="' . $saved_link["lr_url"][0] . '" ';
                            $saved_link_source .= 'target="_blank" ';
                            $saved_link_source .= '>' . $saved_link["lr_source"][0] . '</a>';
                        } else {
                            $saved_link_source .= $saved_link["lr_source"][0];
                        }
                        $saved_link_source .= '</span></p>';
                        echo $saved_link_source;
                    }
                    if ( isset( $saved_link["lr_desc"][0] ) ) {
                        echo '<div class="description">';
                            echo $saved_link["lr_desc"][0];
                        echo '</div>';
                    }
                ?>
            </div>
            
        <?php
            endwhile;
        } else {
            _e( '<p class="error">You don\'t have any recent links or the link roundups plugin is not active.</p>', 'link-roundups' );
        } // end recent links

        ?>

    </div>
</body>
</html>