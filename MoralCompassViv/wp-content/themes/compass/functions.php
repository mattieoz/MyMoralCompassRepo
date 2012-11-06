<?php
// Metaboxes framework
include_once TEMPLATEPATH . '/inc/metaboxes/metaboxes.php';

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 * 
 * @return void
 */
function jvs_theme_setup() {
    // Add default posts and comments RSS feed links to <head>.
    add_theme_support('automatic-feed-links');

    // Support thumbnails
    add_theme_support('post-thumbnails', array('post'));
}
add_action('after_setup_theme', 'jvs_theme_setup');

/**
 * Enqueue required scripts
 * 
 * @return void
 */
function jvs_enqueue_scripts() {
    // Enqueue jQuery
    wp_enqueue_script('jquery');

    // Enqueue modernizr
    wp_enqueue_script('modernizr', get_bloginfo('template_url') . '/js/libs/modernizr.js', array(), '2.6.1');
    
    // Enqueue custom theme scripts in footer
    wp_enqueue_script('custom-scripts', get_bloginfo('template_url') . '/js/script.min.js', array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'jvs_enqueue_scripts');

/**
 * Register menus
 */
function jvs_register_my_menus() {
    register_nav_menus(
        array(
            'main-menu'    => __('Main Menu'),
            'article-menu' => __('Article Menu'),
            'footer-menu'  => __('Footer Menu')
        )
    );
}
add_action('init', 'jvs_register_my_menus');

/**
 * Remove WP version from <head>
 * 
 * @return string Empty string
 */
function jvs_remove_version() {
    return '';
}
add_filter('the_generator', 'jvs_remove_version');

/**
 * Display the post's thumbnail caption
 * 
 * @return string The image caption
 */
function the_post_thumbnail_caption() {
    global $post;

    $thumbnail_id    = get_post_thumbnail_id($post->ID);
    $thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

    if ($thumbnail_image && isset($thumbnail_image[0])) {
        echo $thumbnail_image[0]->post_excerpt;
    }
}

/**
 * Template for comments and pingbacks.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 */
function jvs_comments( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    switch ( $comment->comment_type ) :
        case 'pingback' :
        case 'trackback' :
            break;
        default :
    ?>
    <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
        <article id="comment-<?php comment_ID(); ?>" class=comment>
            <header class="comment-meta clearfix">
                <div class="comment-author vcard left">
                    <?php echo get_avatar( $comment, 58 ); ?>

                    <h6 class=no-bottom>
                        <?php echo strtoupper(get_comment_author_link()); ?>
                    </h6>

                    <time pubdate datetime="<?php echo get_comment_time('c'); ?>">
                        <?php echo get_comment_date(); ?> at <?php echo get_comment_time() ?>
                    </time>

                    <div class=reply>
                        <?php comment_reply_link( array_merge( $args, array( 'reply_text' => __( 'Reply' ), 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
                    </div>

                </div>

                <?php if ( $comment->comment_approved == '0' ) : ?>
                    <em class=comment-awaiting-moderation><?php _e( 'Your comment is awaiting moderation.' ); ?></em>
                    <br>
                <?php endif; ?>

                <?php if ('0' != $comment->comment_parent) : ?>
                <div class="comment-content left"><?php comment_text(); ?></div>
                <?php endif; ?>

            </header>

            <?php if ('0' == $comment->comment_parent) : ?>
            <div class=comment-content><?php comment_text(); ?></div>
            <?php endif; ?>

        </article>

    <?php
            break;
    endswitch;
}