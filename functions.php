<?php
/*
*  Author: Todd Motto | @toddmotto
*  URL: webfactor.com | @webfactor
*  Custom functions, support, custom post types and more.
*/

/*------------------------------------*\
External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
Theme Support
\*------------------------------------*/

if (!isset($content_width)) {
    $content_width = 900;
}

if (function_exists('add_theme_support')) {
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 1600, '', true); // Large Thumbnail
    add_image_size('medium', 800, '', true); // Medium Thumbnail
    add_image_size('small', 400, '', true); // Small Thumbnail
    add_image_size('tiny', 30, 30, true); // Tiny Thumbnail // used for checking brightness
    add_image_size('square', 200, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');




    // Localisation Support
    load_theme_textdomain('webfactor', get_template_directory() . '/languages');
}

/*------------------------------------*\
Functions
\*------------------------------------*/

// HTML5 Blank navigationh
function webfactor_nav() {
    wp_nav_menu(
        array(
            'theme_location'  => 'header-menu',
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '<ul>%3$s</ul>',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}

function wf_version() {
    return '0.1.6';
}

// Load HTML5 Blank scripts (header.php)
function webfactor_header_scripts() {
    if ($GLOBALS['pagenow'] != 'wp-login.php') {
        $tdu = get_template_directory_uri();

        wp_register_script('gmaps', '//maps.google.com/maps/api/js?key=AIzaSyAxQfqRqtPLAW4BolFMCxTiv9y--R8CXdU', array(), wf_version(), true);
        wp_enqueue_script('gmaps'); // Enqueue it!

        if (is_admin()) {
            wp_register_script('adminscripts', $tdu  . '/js/adminscripts.js', array(), wf_version(), true);
            wp_enqueue_script('adminscripts'); // Enqueue it!
        } else {
            wp_deregister_script('jquery');

            // wp_register_script('featherlight', $tdu  . '/js/featherlight.js', array('jquery'), wf_version(), true  );
            // wp_enqueue_script('featherlight'); // Enqueue it!
            //
            // wp_register_script('fl_gallery', $tdu  . '/js/featherlight.gallery.js', array('jquery'), wf_version(), true  );
            // wp_enqueue_script('fl_gallery'); // Enqueue it!

            wp_register_script('lazyload', $tdu  . '/js/lazyload.min.js', array(), wf_version(), true);
            wp_enqueue_script('lazyload'); // Enqueue it!



            wp_register_script('scripts', $tdu  . '/js/scripts.js', array(), wf_version(), true);
            wp_enqueue_script('scripts'); // Enqueue it!
        }
    }
}

// Load HTML5 Blank conditional scripts
function webfactor_conditional_scripts() {
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), wf_version()); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}

// Load HTML5 Blank styles
function webfactor_styles() {
    $tdu = get_template_directory_uri();


    wp_dequeue_style('wp-block-library');

    // wp_register_style('featherlightcss', $tdu  . '/js/featherlight.css', array(), wf_version()  );
    // wp_enqueue_style('featherlightcss'); // Enqueue it!
    //
    // wp_register_style('fl_gallery_css', $tdu  . '/js/featherlight.gallery.css', array(), wf_version()  );
    // wp_enqueue_style('fl_gallery_css'); // Enqueue it!

    wp_register_style('wf_style', $tdu  . '/css/global.css', array(), wf_version(), 'all');
    wp_enqueue_style('wf_style'); // Enqueue it!


    $hour =   intval(date('H', time()));
    if ($hour > 18) {
        wp_register_style('wf_style_dark', $tdu  . '/style_dark.css', array(), wf_version(), 'all');
        wp_enqueue_style('wf_style_dark'); // Enqueue it!
    }
}

// Register HTML5 Blank Navigation
function register_html5_menu() {
    register_nav_menus(array( // Using array to specify more menus if needed
        'primary-navigation' => __('Primary Menu', 'webfactor'), // Main Navigation
        'footer-navigation' => __('Footer Menu', 'webfactor'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'webfactor') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '') {
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var) {
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist) {
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes) {
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar')) {
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'webfactor'),
        'description' => __('Description for this widget-area...', 'webfactor'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'webfactor'),
        'description' => __('Description for this widget-area...', 'webfactor'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style() {
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination() {
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length) {
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '') {
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more) {
    global $post;
    return '... <a class="view_article" href="' . get_permalink($post->ID) . '">' . __('lire plus', 'webfactor') . '</a>';
}

// Remove Admin bar
function remove_admin_bar() {
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag) {
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions($html) {
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function webfactorgravatar($avatar_defaults) {
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments() {
    if (!is_admin()) {
        if (is_singular() and comments_open() and (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function webfactorcomments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);

    if ('div' == $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    } ?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
        <?php if ('div' != $args['style']) : ?>
            <div id="div-comment-<?php comment_ID() ?>" class="comment-body">
            <?php endif; ?>
            <div class="comment-author vcard">
                <?php if ($args['avatar_size'] != 0) {
                    echo get_avatar($comment, $args['180']);
                } ?>
                <?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
            </div>
            <?php if ($comment->comment_approved == '0') : ?>
                <em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
                <br />
            <?php endif; ?>

            <div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars(get_comment_link($comment->comment_ID)) ?>">
                    <?php
                    printf(__('%1$s at %2$s'), get_comment_date(), get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'), '  ', ''); ?>
            </div>

            <?php comment_text() ?>

            <div class="reply">
                <?php comment_reply_link(array_merge($args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
            </div>
            <?php if ('div' != $args['style']) : ?>
            </div>
        <?php endif; ?>
    <?php
}

/*------------------------------------*\
Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'webfactor_header_scripts'); // Add Custom Scripts to wp_head
// add_action('wp_print_scripts', 'webfactor_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'webfactor_styles'); // Add Theme Stylesheet
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu

add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'webfactorgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]



/*------------------------------------*\
ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null) {
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}




function chilly_nav($menu) {
    wp_nav_menu(
        array(
            'theme_location'  => $menu,
            'menu'            => '',
            'container'       => 'div',
            'container_class' => 'menu-{menu slug}-container',
            'container_id'    => '',
            'menu_class'      => 'menu',
            'menu_id'         => '',
            'echo'            => true,
            'fallback_cb'     => 'wp_page_menu',
            'before'          => '',
            'after'           => '',
            'link_before'     => '',
            'link_after'      => '',
            'items_wrap'      => '%3$s',
            'depth'           => 0,
            'walker'          => ''
        )
    );
}

function chilly_map($atts, $content = null) {
    $attributes = shortcode_atts(array(
        'title' => "Rue du Midi 15 Case postale 411 1020 Renens"
    ), $atts);



    $title = $attributes['title'];
    $chilly_map = '<div id="map_container_1"></div>';
    $chilly_map .= "<script> var latt = 46.5380683; var lonn=6.5812023; var map_title = '" . $title . "'  </script>";
    return $chilly_map;
}
add_shortcode('chilly_map', 'chilly_map');


function disable_wp_emojicons() {

    // all actions related to emojis
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');

    // filter to remove TinyMCE emojis
    // add_filter( 'tiny_mce_plugins', 'disable_emojicons_tinymce' );
}
add_action('init', 'disable_wp_emojicons');


function remove_json_api() {

    // Remove the REST API lines from the HTML Header
    remove_action('wp_head', 'rest_output_link_wp_head', 10);
    remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
    // Remove the REST API endpoint.
    remove_action('rest_api_init', 'wp_oembed_register_route');
    // Turn off oEmbed auto discovery.
    add_filter('embed_oembed_discover', '__return_false');
    // Don't filter oEmbed results.
    remove_filter('oembed_dataparse', 'wp_filter_oembed_result', 10);
    // Remove oEmbed discovery links.
    remove_action('wp_head', 'wp_oembed_add_discovery_links');
    // Remove oEmbed-specific JavaScript from the front-end and back-end.
    remove_action('wp_head', 'wp_oembed_add_host_js');
    // Remove all embeds rewrite rules.
    // add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
add_action('after_setup_theme', 'remove_json_api');





function thumbnail_of_post_url($post_id, $size = 'large') {
    $image_id = get_post_thumbnail_id($post_id);
    $image_url = wp_get_attachment_image_src($image_id, $size);
    $image = $image_url[0];
    return $image;
}




add_action('init', 'create_custom_post_types'); // Add our HTML5 Blank Custom Post Type


function create_custom_post_types() {
    $labels = array(
        'name'                       => 'Countries',
        'singular_name'              => 'Country',
    );

    $args = array(
        'labels'                     => $labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );
    register_taxonomy('country', array('location'), $args);





    $trip_labels = array(
        'name'                       => 'Trips',
        'singular_name'              => 'Trip',
    );

    $trip_args = array(
        'labels'                     => $trip_labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => false,
    );
    register_taxonomy('trip', array('location'), $trip_args);



    register_post_type(
        'location', // Register Custom Post Type
        array(
            'labels' => array(
                'name' => __('Locations', 'html5blank'), // Rename these to suit
                'singular_name' => __('Location', 'html5blank'),
                'add_new' => __('Ajouter', 'html5blank'),
                'add_new_item' => __('Add New Location', 'html5blank'),
                'edit' => __('Edit', 'html5blank'),
                'edit_item' => __('Edit Location', 'html5blank'),
                'new_item' => __('New Location', 'html5blank'),
                'view' => __('View Location', 'html5blank'),
                'view_item' => __('View Location', 'html5blank'),
                'search_items' => __('Search Locations', 'html5blank'),
                'not_found' => __('No Locations found', 'html5blank'),
                'not_found_in_trash' => __('No Locations found in Trash', 'html5blank')
            ),
            'public' => true,
            'menu_position' =>  5,
            'menu_icon'   => 'dashicons-location',
            'exclude_from_search' => false,
            'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
            'has_archive' => true,
            'supports' => array(
                'title',
                'thumbnail',
                'editor'
            ), // Go to Dashboard Custom HTML5 Blank post for supports
            'can_export' => true, // Allows export in Tools > Export
            'taxonomies' => array() // Add Category and Post Tags support
        )
    );


    // register_post_type('expense', // Register Custom Post Type
    // array(
    //     'labels' => array(
    //         'name' => __('Expenses', 'html5blank'), // Rename these to suit
    //         'singular_name' => __('Expense', 'html5blank'),
    //         'add_new' => __('Ajouter', 'html5blank'),
    //         'add_new_item' => __('Add New Expense', 'html5blank'),
    //         'edit' => __('Edit', 'html5blank'),
    //         'edit_item' => __('Edit Expense', 'html5blank'),
    //         'new_item' => __('New Expense', 'html5blank'),
    //         'view' => __('View Expense', 'html5blank'),
    //         'view_item' => __('View Expense', 'html5blank'),
    //         'search_items' => __('Search Expenses', 'html5blank'),
    //         'not_found' => __('No Expenses found', 'html5blank'),
    //         'not_found_in_trash' => __('No Expenses found in Trash', 'html5blank')
    //     ),
    //     'public' => true,
    //     'exclude_from_search' => false,
    //     'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
    //     'has_archive' => true,
    //     'supports' => array(
    //         'title'
    //     ), // Go to Dashboard Custom HTML5 Blank post for supports
    //     'can_export' => true, // Allows export in Tools > Export
    //     'taxonomies' => array( 'expense_cat' ) // Add Category and Post Tags support
    // ));
    //
    //
    //
    //
    //   $args_expense_cat = array(
    //       'hierarchical'               => true,
    //       'public'                     => true,
    //       'show_ui'                    => true,
    //       'show_admin_column'          => true,
    //       'show_in_nav_menus'          => true,
    //       'show_tagcloud'              => false,
    //   );
    //   register_taxonomy( 'expense_cat', array( 'expense' ), $args_expense_cat );
}




function locations_for_map($locations) {
    $ret = array();
    foreach ($locations as $location) {
        $z = new stdClass();


        $latlon = get_field('latitude', $location->ID);
        $latlonsplit = explode(',', $latlon);

        $countries = get_the_terms($location->ID, 'country');
        $country = ($countries) ?  $countries[0]->slug : '-';

        if (sizeof($latlonsplit) == 2) {
            $z->title = $location->post_title;
            $z->lat = round($latlonsplit[0] * 100000) / 100000;
            $z->lng = round($latlonsplit[1] * 100000) / 100000;
            $z->id =  $location->ID;
            $z->country = $country;

            if ($z->lat != '') {
                $y = json_encode($z, JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE);
                array_push($ret, $y);
            }
        }
    }

    $ret = array_reverse($ret);

    echo '[' . "\n" . implode(",\n", $ret) . ']';
}



function days_travelled($locations) {
    if (sizeof($locations) > 1) {
        $first = $locations[0];
        $last = end($locations);

        $f_date = strtotime($first->post_date);
        $l_date = strtotime($last->post_date);
        $diff = ($f_date - $l_date);
        $diff_in_days = round($diff / 60 / 60 / 24);

        if ($diff_in_days == 1) {
            return '1 day';
        } else {
            return $diff_in_days . ' days';
        }
    } else {
        return 0;
    }
}



function nice_date($string, $format) {
    $time = strtotime($string);
    $d =  date($format, $time);
    return $d;
}


// function show_all_on_expense_archive( $wp_query ) {
// 	if ( is_archive() ){
// 		global $wp_query;
//         $wp_query->set( 'posts_per_page',-1 );
//
// 	}
// }
// add_filter('pre_get_posts', 'show_all_on_expense_archive' );
//

add_filter('manage_location_posts_columns', 'custom_location_columns');
function custom_location_columns($defaults) {
    $defaults['latitude']  = "Lng/Lat";
    return $defaults;
}


add_action('manage_location_posts_custom_column', 'custom_location_table_content', 10, 2);
function custom_location_table_content($column_name, $post_id) {
    if ($column_name == 'latitude') {
        echo get_field('latitude', $post_id);
    }
}

// add_action('add_meta_boxes', 'wporg_add_custom_box');
function wporg_add_custom_box() {
    add_meta_box(
        'location_finder_box',   // Unique ID
        'Location Finder',  // Box title
        'location_finder_box_html',  // Content callback, must be of type callable
        'location'  // Post type
    );
}
function location_finder_box_html() {
    $str =  '<input type="text"  id="location_search" name="location_search" placeholder="type location here" />';
    $str .= '<a href="#" id="location_search_button">Search</a>';
    $str .= '<ul id="location_results"></ul>';

    echo $str;
}


add_filter('pre_get_posts', 'set_post_order_in_admin', 5);
function set_post_order_in_admin($wp_query) {
    global $pagenow;
    if (is_admin() && 'edit.php' == $pagenow && !isset($_GET['orderby'])) {
        $wp_query->set('orderby', 'date');
        $wp_query->set('order', 'DESC');
    }
}




/// set a default trip category on a location if one is not set
add_action('save_post', 'set_default_trip_cat_for_locations', 100, 2);
function set_default_trip_cat_for_locations($post_id, $post) {
    if ($post->post_status === 'publish' && $post->post_type === 'location') {
        $defaults = array(
            'trip' => array(get_field('current_trip', 'option')),
        );
        $taxonomies = get_object_taxonomies($post->post_type);
        foreach ((array) $taxonomies as $taxonomy) {
            $terms = wp_get_post_terms($post_id, $taxonomy);
            if (empty($terms) && array_key_exists($taxonomy, $defaults)) {
                wp_set_object_terms($post_id, $defaults[$taxonomy], $taxonomy);
            }
        }
    }
}


if (function_exists('acf_add_options_page')) {
    add_action('acf/init', 'acf_add_options_page');


    acf_add_options_page(array(
        'page_title'    => 'Vamoose Settings',
        'menu_title'    => 'Vamoose Settings',
        'menu_slug'     => 'vamoose-settings',
    ));
}




    ?>