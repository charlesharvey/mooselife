<?php get_header(); ?>


<?php if (have_posts()): while (have_posts()) : the_post(); ?>

    <?php $locations = get_posts(array(
        'post_type' => 'location',
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'date',
        'tax_query' => array(
        array(
            'taxonomy' => 'trip',
            'field' => 'slug',
            'terms' => 'trip_2'
        )
    )
     )); ?>
<?php get_template_part('partials/locations'); ?>



<?php endwhile; ?>

<?php else: ?>

    <article class="container">
        <h2><?php _e('Sorry, nothing to display.', 'webfactor'); ?></h2>
    </article>

<?php endif; ?>


<?php get_footer(); ?>
