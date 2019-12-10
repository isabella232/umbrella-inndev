<?php
/**
 * Single Post Template: Amplify Feed
 * Template Name: Amplify Feed Template
 * Description: Shows the saved links feed that can be embedded on external sites.
 */

?>

<img src="http://placehold.it/200x200">
<h2>Lens on Lightfoot</h2>
<a href="https://inn.org/amplify" target="_blank">Learn More</a>

<?php

$query_args = array (
    'post__not_in' => get_option( 'sticky_posts' ),
    'showposts'    => $instance['num_posts'],
    'post_type'    => 'rounduplink',
    'tax_query' => array(
        array (
            'taxonomy' => 'lr-tags',
            'field' => 'slug',
            'terms' => 'lens-on-lightfoot',
        )
    ),
    'post_status'  => 'publish'
);
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
    </div> <!-- /.post-lead -->
    
<?php
    endwhile;
} else {
    _e( '<p class="error">You don\'t have any recent links or the link roundups plugin is not active.</p>', 'link-roundups' );
} // end recent links