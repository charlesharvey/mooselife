<?php get_header(); ?>



<?php if (have_posts()) : ?>
    <?php $term = get_queried_object(); ?>
    <?php $locations = get_posts( array(
        'post_type' => 'location',
        'posts_per_page' => -1 ,
        'order' => 'DESC',
        'orderby' => 'date',
        'tax_query' => array(
            array(
                'taxonomy' => 'country',
                'field' => 'slug',
                'terms' => $term->slug
            )
        )
    )); ?>
    <?php get_template_part('partials/locations'); ?>
<script>
    var zoom_out = true;
</script>


<?php else: ?>

    <article class="container">
        <h2><?php _e( 'Sorry, nothing to display.', 'webfactor' ); ?></h2>
    </article>

<?php endif; ?>


<?php get_footer(); ?>
