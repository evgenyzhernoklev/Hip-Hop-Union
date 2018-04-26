<?php
/**
 * Twenty Sixteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

/**
 * Twenty Sixteen only works in WordPress 4.4 or later.
 */
if ( version_compare( $GLOBALS['wp_version'], '4.4-alpha', '<' ) ) {
	require get_template_directory() . '/inc/back-compat.php';
}

if ( ! function_exists( 'twentysixteen_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * Create your own twentysixteen_setup() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_setup() {
	/*
	 * Make theme available for translation.
	 * Translations can be filed at WordPress.org. See: https://translate.wordpress.org/projects/wp-themes/twentysixteen
	 * If you're building a theme based on Twenty Sixteen, use a find and replace
	 * to change 'twentysixteen' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'twentysixteen' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for custom logo.
	 *
	 *  @since Twenty Sixteen 1.2
	 */
	add_theme_support( 'custom-logo', array(
		'height'      => 240,
		'width'       => 240,
		'flex-height' => true,
	) );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 1200, 9999 );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu', 'twentysixteen' ),
		'social'  => __( 'Social Links Menu', 'twentysixteen' ),
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside',
		'image',
		'video',
		'quote',
		'link',
		'gallery',
		'status',
		'audio',
		'chat',
	) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', twentysixteen_fonts_url() ) );

	// Indicate widget sidebars can use selective refresh in the Customizer.
	add_theme_support( 'customize-selective-refresh-widgets' );
}
endif; // twentysixteen_setup
add_action( 'after_setup_theme', 'twentysixteen_setup' );

/**
 * Sets the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'twentysixteen_content_width', 840 );
}
add_action( 'after_setup_theme', 'twentysixteen_content_width', 0 );

/**
 * Registers a widget area.
 *
 * @link https://developer.wordpress.org/reference/functions/register_sidebar/
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Sidebar', 'twentysixteen' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	register_sidebar( array(
		'name'          => __( 'Информация в подвале', 'twentysixteen' ),
		'id'            => 'sidebar-2',
		'description'   => __( 'Информация появляется в подвале.', 'twentysixteen' ),
		'before_widget' => '<div class="widget %2$s" role="complementary">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => __( 'Информация в подвале на английском', 'twentysixteen' ),
		'id'            => 'sidebar-3',
		'description'   => __( 'Информация появляется в подвале.', 'twentysixteen' ),
		'before_widget' => '<div class="widget %2$s" role="complementary">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );
}
add_action( 'widgets_init', 'twentysixteen_widgets_init' );

if ( ! function_exists( 'twentysixteen_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Sixteen.
 *
 * Create your own twentysixteen_fonts_url() function to override in a child theme.
 *
 * @since Twenty Sixteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function twentysixteen_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/* translators: If there are characters in your language that are not supported by Merriweather, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Merriweather font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Merriweather:400,700,900,400italic,700italic,900italic';
	}

	/* translators: If there are characters in your language that are not supported by Montserrat, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Montserrat font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Montserrat:400,700';
	}

	/* translators: If there are characters in your language that are not supported by Inconsolata, translate this to 'off'. Do not translate into your own language. */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'twentysixteen' ) ) {
		$fonts[] = 'Inconsolata:400';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), 'https://fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Handles JavaScript detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'twentysixteen_javascript_detection', 0 );

/**
 * Enqueues scripts and styles.
 *
 * @since Twenty Sixteen 1.0
 */
function twentysixteen_scripts() {
	// Add custom fonts, used in the main stylesheet.
	wp_enqueue_style( 'twentysixteen-fonts', twentysixteen_fonts_url(), array(), null );

	// Add Genericons, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/genericons/genericons.css', array(), '3.4.1' );

	// Theme stylesheet.
	wp_enqueue_style( 'twentysixteen-style', get_stylesheet_uri() );

	// Custom theme stylesheet.
	wp_enqueue_style( 'union-style', get_template_directory_uri() . '/css/app.css' );

	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'twentysixteen-ie', get_template_directory_uri() . '/css/ie.css', array( 'twentysixteen-style' ), '20160816' );
	wp_style_add_data( 'twentysixteen-ie', 'conditional', 'lt IE 10' );

	wp_enqueue_script( 'twentysixteen-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20160816', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	if ( is_singular() && wp_attachment_is_image() ) {
		wp_enqueue_script( 'twentysixteen-keyboard-image-navigation', get_template_directory_uri() . '/js/keyboard-image-navigation.js', array( 'jquery' ), '20160816' );
	}

	wp_enqueue_script( 'twentysixteen-script', get_template_directory_uri() . '/js/functions.js', array( 'jquery' ), '20160816', true );

	wp_localize_script( 'twentysixteen-script', 'screenReaderText', array(
		'expand'   => __( 'expand child menu', 'twentysixteen' ),
		'collapse' => __( 'collapse child menu', 'twentysixteen' ),
	) );
}
add_action( 'wp_enqueue_scripts', 'twentysixteen_scripts' );

/**
 * Adds custom classes to the array of body classes.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $classes Classes for the body element.
 * @return array (Maybe) filtered body classes.
 */
function twentysixteen_body_classes( $classes ) {
	// Adds a class of custom-background-image to sites with a custom background image.
	if ( get_background_image() ) {
		$classes[] = 'custom-background-image';
	}

	// Adds a class of group-blog to sites with more than 1 published author.
	if ( is_multi_author() ) {
		$classes[] = 'group-blog';
	}

	// Adds a class of no-sidebar to sites without active sidebar.
	if ( ! is_active_sidebar( 'sidebar-1' ) ) {
		$classes[] = 'no-sidebar';
	}

	// Adds a class of hfeed to non-singular pages.
	if ( ! is_singular() ) {
		$classes[] = 'hfeed';
	}

	return $classes;
}
add_filter( 'body_class', 'twentysixteen_body_classes' );

/**
 * Converts a HEX value to RGB.
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $color The original color, in 3- or 6-digit hexadecimal form.
 * @return array Array containing RGB (red, green, and blue) values for the given
 *               HEX code, empty array otherwise.
 */
function twentysixteen_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for content images
 *
 * @since Twenty Sixteen 1.0
 *
 * @param string $sizes A source size value for use in a 'sizes' attribute.
 * @param array  $size  Image size. Accepts an array of width and height
 *                      values in pixels (in that order).
 * @return string A source size value for use in a content image 'sizes' attribute.
 */
function twentysixteen_content_image_sizes_attr( $sizes, $size ) {
	$width = $size[0];

	840 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 62vw, 840px';

	if ( 'page' === get_post_type() ) {
		840 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	} else {
		840 > $width && 600 <= $width && $sizes = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 61vw, (max-width: 1362px) 45vw, 600px';
		600 > $width && $sizes = '(max-width: ' . $width . 'px) 85vw, ' . $width . 'px';
	}

	return $sizes;
}
add_filter( 'wp_calculate_image_sizes', 'twentysixteen_content_image_sizes_attr', 10 , 2 );

/**
 * Add custom image sizes attribute to enhance responsive image functionality
 * for post thumbnails
 *
 * @since Twenty Sixteen 1.0
 *
 * @param array $attr Attributes for the image markup.
 * @param int   $attachment Image attachment ID.
 * @param array $size Registered image size or flat array of height and width dimensions.
 * @return string A source size value for use in a post thumbnail 'sizes' attribute.
 */
function twentysixteen_post_thumbnail_sizes_attr( $attr, $attachment, $size ) {
	if ( 'post-thumbnail' === $size ) {
		is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 984px) 60vw, (max-width: 1362px) 62vw, 840px';
		! is_active_sidebar( 'sidebar-1' ) && $attr['sizes'] = '(max-width: 709px) 85vw, (max-width: 909px) 67vw, (max-width: 1362px) 88vw, 1200px';
	}
	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', 'twentysixteen_post_thumbnail_sizes_attr', 10 , 3 );

/**
 * Modifies tag cloud widget arguments to have all tags in the widget same font size.
 *
 * @since Twenty Sixteen 1.1
 *
 * @param array $args Arguments for tag cloud widget.
 * @return array A new modified arguments.
 */
function twentysixteen_widget_tag_cloud_args( $args ) {
	$args['largest'] = 1;
	$args['smallest'] = 1;
	$args['unit'] = 'em';
	return $args;
}
add_filter( 'widget_tag_cloud_args', 'twentysixteen_widget_tag_cloud_args' );



/******************************************************************************/
/*********************************** Pages ************************************/
/******************************************************************************/
/**
 * Add description field for post types => pages
*/
abstract class Page_Meta_Boxes
{
    public static function add()
    {
        $screens = ['page'];
        foreach ($screens as $screen) {
						add_meta_box(
								'page_excerpt_id',       									// Unique ID
								'Описание (выводится после заголовка)', 	// Box title
								[self::class, 'html_excerpt'], 						// Content callback, must be of type callable
								$screen                 									// Post type
						);
        }
    }

    public static function save($post_id)
    {
				if (array_key_exists('page_excerpt_field', $_POST)) {
						update_post_meta(
								$post_id,
								'_page_excerpt_meta_key',
								$_POST['page_excerpt_field']
						);
				}
    }

		public static function html_excerpt($post)
    {
        $valuePhone = get_post_meta($post->ID, '_page_excerpt_meta_key', true);
        ?>
				<textarea name="page_excerpt_field" id="page_excerpt_field" value="<?php echo $valuePhone ?>" style="width: 100%;" /><?php echo $valuePhone ?></textarea>
      	<?php
    }
}

add_action('add_meta_boxes', ['Page_Meta_Boxes', 'add']);
add_action('save_post', ['Page_Meta_Boxes', 'save']);



/******************************************************************************/
/*********************************** Posts ************************************/
/******************************************************************************/
/**
 * Add custom fields for post types => posts, news
*/
abstract class Post_Meta_Boxes
{
    public static function add()
    {
        $screens = ['post', 'news'];
        foreach ($screens as $screen) {
						add_meta_box(
								'post_author_id',        				// Unique ID
								'Автор', 												// Box title
								[self::class, 'html_link'],   	// Content callback, must be of type callable
								$screen                 				// Post type
						);
        }
    }

    public static function save($post_id)
    {
				if (array_key_exists('post_author_field', $_POST)) {
						update_post_meta(
								$post_id,
								'_post_author_meta_key',
								$_POST['post_author_field']
						);
				}
    }

		public static function html_link($post)
    {
				$valueLink = get_post_meta($post->ID, '_post_author_meta_key', true);
        ?>
				<input type="text" name="post_author_field" id="post_author_field" value="<?php echo $valueLink ?>" style="width: 100%;" />
        <?php
    }
}

add_action('add_meta_boxes', ['Post_Meta_Boxes', 'add']);
add_action('save_post', ['Post_Meta_Boxes', 'save']);



/******************************************************************************/
/********************************* glossary ***********************************/
/******************************************************************************/
/**
 * Add custom post types => glossary
*/
add_action( 'init', 'create_post_type_glossary' );

function create_post_type_glossary() {
	register_post_type( 'glossary',
  		array(
          'labels' => array( // добавляем новые элементы в административную частьку
              'name' => __( 'Библиотека' ),
              'singular_name' => __( 'Библиотека' ),
              'has_archive' => true,
              'add_new' => 'Добавить новую запись',
              'not_found' => 'Ничего не найдено',
              'not_found_in_trash' => 'В корзине запиисей не найдено'
          ),
          'public' => true,
					'menu_icon' => 'dashicons-book-alt',
          'has_archive' => true,
          'supports' => array( //добавляем элементы в редактор
              'title',
							'editor',
							'excerpt',
              'thumbnail',
							'revisions',
							'comments'
          ),
         'taxonomies' => array('category', 'post_tag') //добавляем к записям необходимый набор таксономий
     	)
	);
}



/**
 * Add custom fields for post types => posts
*/
abstract class Glossary_Meta_Boxes
{
    public static function add()
    {
        $screens = ['glossary'];
        foreach ($screens as $screen) {
						add_meta_box(
								'glossary_author_id',        		// Unique ID
								'Автор', 												// Box title
								[self::class, 'html_link'],   	// Content callback, must be of type callable
								$screen                 				// Post type
						);
        }
    }

    public static function save($post_id)
    {
				if (array_key_exists('glossary_author_field', $_POST)) {
						update_post_meta(
								$post_id,
								'_glossary_author_meta_key',
								$_POST['glossary_author_field']
						);
				}
    }

		public static function html_link($post)
    {
				$valueLink = get_post_meta($post->ID, '_glossary_author_meta_key', true);
        ?>
				<input type="text" name="glossary_author_field" id="glossary_author_field" value="<?php echo $valueLink ?>" style="width: 100%;" />
        <?php
    }
}

add_action('add_meta_boxes', ['Glossary_Meta_Boxes', 'add']);
add_action('save_post', ['Glossary_Meta_Boxes', 'save']);




/******************************************************************************/
/********************************** Persons ***********************************/
/******************************************************************************/
/**
 * Add custom post types => persons
*/
add_action( 'init', 'create_post_type_persons' );

function create_post_type_persons() {
	register_post_type( 'person',
  		array(
          'labels' => array( // добавляем новые элементы в административную частьку
              'name' => __( 'Персоны' ),
              'singular_name' => __( 'Персона' ),
              'has_archive' => true,
              'add_new' => 'Добавить новую персону',
              'not_found' => 'Ничего не найдено',
              'not_found_in_trash' => 'В корзине персон не найдено'
          ),
          'public' => true,
					'menu_icon' => 'dashicons-admin-users',
          'has_archive' => true,
          'supports' => array( //добавляем элементы в редактор
              'title',
							'excerpt',
              'thumbnail',
              'page-attributes'
          ),
         'taxonomies' => array('category') //добавляем к записям необходимый набор таксономий
     	)
	);
}



/**
 * Add custom fields for post types => persons
*/
abstract class Person_Meta_Boxes
{
    public static function add()
    {
        $screens = ['person'];
        foreach ($screens as $screen) {
						add_meta_box(
								'person_link_id',        								// Unique ID
								'Ссылка на контакты', 									// Box title
								[self::class, 'html_link'],   					// Content callback, must be of type callable
								$screen                 								// Post type
						);
            add_meta_box(
                'person_phone_id',        							// Unique ID
                'Телефон', 															// Box title
                [self::class, 'html_phone'],   					// Content callback, must be of type callable
                $screen                 								// Post type
            );
						add_meta_box(
								'person_name_english_id',       				// Unique ID
								'Имя на английском', 										// Box title
								[self::class, 'html_name_english'], 		// Content callback, must be of type callable
								$screen                 								// Post type
						);
						add_meta_box(
								'person_excerpt_id',       							// Unique ID
								'Описание деятельности на английском', 	// Box title
								[self::class, 'html_excerpt'], 					// Content callback, must be of type callable
								$screen                 								// Post type
						);
        }
    }

    public static function save($post_id)
    {
				if (array_key_exists('person_link_field', $_POST)) {
						update_post_meta(
								$post_id,
								'_person_link_meta_key',
								$_POST['person_link_field']
						);
				}
        if (array_key_exists('person_phone_field', $_POST)) {
            update_post_meta(
                $post_id,
                '_person_phone_meta_key',
                $_POST['person_phone_field']
            );
        }
				if (array_key_exists('person_name_english_field', $_POST)) {
						update_post_meta(
								$post_id,
								'_person_name_english_meta_key',
								$_POST['person_name_english_field']
						);
				}
				if (array_key_exists('person_excerpt_field', $_POST)) {
						update_post_meta(
								$post_id,
								'_person_excerpt_meta_key',
								$_POST['person_excerpt_field']
						);
				}
    }

		public static function html_link($post)
    {
				$valueLink = get_post_meta($post->ID, '_person_link_meta_key', true);
        ?>
				<input type="text" name="person_link_field" id="person_link_field" value="<?php echo $valueLink ?>" style="width: 100%;" />
        <?php
    }

    public static function html_phone($post)
    {
        $valuePhone = get_post_meta($post->ID, '_person_phone_meta_key', true);
        ?>
				<input type="text" name="person_phone_field" id="person_phone_field" value="<?php echo $valuePhone ?>" style="width: 100%;" />
        <?php
    }

		public static function html_name_english($post)
    {
        $valuePhone = get_post_meta($post->ID, '_person_name_english_meta_key', true);
        ?>
				<input type="text" name="person_name_english_field" id="person_name_english_field" value="<?php echo $valuePhone ?>" style="width: 100%;" />
        <?php
    }

		public static function html_excerpt($post)
    {
        $valuePhone = get_post_meta($post->ID, '_person_excerpt_meta_key', true);
        ?>
				<textarea name="person_excerpt_field" id="person_excerpt_field" value="<?php echo $valuePhone ?>" style="width: 100%;" /><?php echo $valuePhone ?></textarea>
      	<?php
    }
}

add_action('add_meta_boxes', ['Person_Meta_Boxes', 'add']);
add_action('save_post', ['Person_Meta_Boxes', 'save']);



/**
 * Add custom thumbnails for post types => persons
*/
if (class_exists('MultiPostThumbnails')) {
    new MultiPostThumbnails(
        array(
            'label' => 'Фото для ховера',
            'id' => 'secondary-image',
            'post_type' => 'person'
        )
    );
}



/******************************************************************************/
/********************************** Partners **********************************/
/******************************************************************************/
/**
 * Add custom post types => partners
*/
add_action( 'init', 'create_post_type_partners' );

function create_post_type_partners() {
	register_post_type( 'partner',
  		array(
          'labels' => array( // добавляем новые элементы в административную частьку
              'name' => __( 'Партнеры' ),
              'singular_name' => __( 'Партнер' ),
              'has_archive' => true,
              'add_new' => 'Добавить нового партнера',
              'not_found' => 'Ничего не найдено',
              'not_found_in_trash' => 'В корзине партнеров не найдено'
          ),
          'public' => true,
					'menu_icon' => 'dashicons-groups',
          'has_archive' => true,
          'supports' => array( //добавляем элементы в редактор
              'title',
							'excerpt',
              'thumbnail',
              'page-attributes'
          )//,
         //'taxonomies' => array('category') //добавляем к записям необходимый набор таксономий
     	)
	);
}

/**
 * Add custom fields for post types => partners
*/
abstract class Partner_Meta_Boxes
{
    public static function add()
    {
        $screens = ['partner'];
        foreach ($screens as $screen) {
						add_meta_box(
								'partner_link_id',        				// Unique ID
								'Ссылка на контакты', 					// Box title
								[self::class, 'html_link'],   	// Content callback, must be of type callable
								$screen                 				// Post type
						);
        }
    }

    public static function save($post_id)
    {
				if (array_key_exists('partner_link_field', $_POST)) {
						update_post_meta(
								$post_id,
								'_partner_link_meta_key',
								$_POST['partner_link_field']
						);
				}
    }

		public static function html_link($post)
    {
				$valueLink = get_post_meta($post->ID, '_partner_link_meta_key', true);
        ?>
				<input type="text" name="partner_link_field" id="partner_link_field" value="<?php echo $valueLink ?>" style="width: 100%;" />
        <?php
    }
}

add_action('add_meta_boxes', ['Partner_Meta_Boxes', 'add']);
add_action('save_post', ['Partner_Meta_Boxes', 'save']);



/******************************************************************************/
/******************************* English news *********************************/
/******************************************************************************/
/**
 * Add custom post types => news
*/
add_action( 'init', 'create_post_type_news' );

function create_post_type_news() {
	register_post_type( 'news',
  		array(
          'labels' => array( // добавляем новые элементы в административную частьку
              'name' => __( 'English news' ),
              'singular_name' => __( 'English news' ),
              'has_archive' => true,
              'add_new' => 'Add post',
              'not_found' => 'No posts',
              'not_found_in_trash' => 'No posts in trash'
          ),
          'public' => true,
					'menu_icon' => 'dashicons-admin-post',
					'menu_position' => 4,
          'has_archive' => true,
          'supports' => array( //добавляем элементы в редактор
              'title',
							'editor',
							'excerpt',
              'thumbnail',
							'revisions',
							'comments'
          ),
         'taxonomies' => array('category', 'post_tag') //добавляем к записям необходимый набор таксономий
     	)
	);
}



/******************************************************************************/
/*********************************** common ***********************************/
/******************************************************************************/
/**
 * Add svg support
*/
function my_myme_types($mime_types){
    $mime_types['svg'] = 'image/svg+xml'; // поддержка SVG
    return $mime_types;
}

add_filter('upload_mimes', 'my_myme_types', 1, 1);



/**
 * Checks if current page has special slug
*/
function has_slug($slug) {
	$url = $_SERVER["REQUEST_URI"];
	$isSlug = strpos($url, $slug);

	if ( $isSlug ) :
		return true;
	endif;

	return false;
}



/**
 * Add custom logo
*/
function hip_logo() {
		$url = has_slug('en') || has_slug('news') ? '/en/' : '/';
    $custom_logo_id = get_theme_mod( 'custom_logo' );
    $html = sprintf( '<a href="%1$s" class="custom-logo-link" rel="home" itemprop="url">%2$s</a>',
            esc_url( $url ),
            wp_get_attachment_image( $custom_logo_id, 'full', false, array(
                'class'    => 'custom-logo',
            ) )
        );
    return $html;
}

add_filter( 'get_custom_logo', 'hip_logo' );



/**
 * Add custom file in article footer
*/
function attached_file() {
		global $post;

		$is_showing_file = get_post_meta($post->ID, 'select_type', true);
		$attached_file_link = esc_url( get_post_meta($post->ID, 'pfdFile', true) );
		$attached_file_name = get_post_meta($post->ID, 'file_name', true);
		$html = '';

		if ($is_showing_file == 'true') :
			$html = sprintf( '<div class="attached-file">' .
											 '<a href="%1$s" class="attached-file-link"' .
											 'download>%2$s</a></div>',
								$attached_file_link,
								$attached_file_name
							);
		endif;

		return $html;
}



/**
 * Add custom script
*/
wp_enqueue_script( 'script', get_template_directory_uri() . '/js/custom.js', array ( 'jquery' ), 1.1, true);

if(is_admin()) {
    wp_enqueue_script('newmeta', get_template_directory_uri() . '/js/newmeta.js');
}



/**
 * Add custom field for file upload
*/
function new_meta_box() {
	  add_meta_box(
	      'new_meta_box', // Идентификатор(id)
	      'Текстовый файл', // Заголовок области с мета-полями(title)
	      'show_my_new_metabox', // Вызов(callback)
	      'glossary', // где будет отображаться, post означает в форме стандартного добавления записи
	      'normal',
	      'high');
}
add_action('add_meta_boxes', 'new_meta_box'); // Запускаем функцию

$new_meta_fields = array(
    array(
        'name'  		=> 'File',
        'label' 		=> 'Файл',
        'desc'  		=> '',
        'id'    		=> 'pfdFile',
        'type'  		=> 'file',
				'file_name' => 'file_name'
    ),
    array(
        'label' => 'Отображать в записи?',
        'desc'  => '',
        'id'    => 'select_type',
        'type'  => 'select',
        'options' => array (  // Параметры, всплывающие данные
            'one' => array (
                'label' => 'Да',  // Название поля
                'value' => 'true'  // Значение
            ),
            'two' => array (
                'label' => 'Нет',  // Название поля
                'value' => 'false'  // Значение
            )
        )
    ),
);

function show_my_new_metabox() {
	global $new_meta_fields; // Обозначим наш массив с полями глобальным
	global $post;  // Глобальный $post для получения id создаваемого/редактируемого поста
	// Выводим скрытый input, для верификации. Безопасность прежде всего!
	echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';
	echo '<table class="form-table">';

	foreach ($new_meta_fields as $field) {
		// Получаем значение если оно есть для этого поля
		$meta = get_post_meta($post->ID, $field['id'], true);
		$file_name = get_post_meta($post->ID, $field['file_name'], true);

		// Начинаем выводить таблицу
		echo '<tr>
		    <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
		    <td>';
		    switch($field['type']) {
		        case 'file':
		            echo    '<input name="'.$field['id'].'" type="hidden" class="custom_upload_file" value="'.$meta.'" />
								<input name="'.$field['file_name'].'" type="hidden" class="custom_upload_file_name" value="'.$file_name.'" />
		            <a href="'.$meta.'" class="custom_file_prev">'.$file_name.'</a><br /><br />
		            <input class="custom_upload_file_button button" type="button" value="Выберите файл" />
		            <small style="display: inline-block; margin: 5px 0 0 10px;"><a href="#" class="custom_clear_file_button">Убрать файл</a></small>
		            <br clear="all" /><span class="description">'.$field['desc'].'</span>';
		        break;
		        case 'select':
		            echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
		            foreach ($field['options'] as $option) {
		                echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
		            }
		            echo '</select><br /><span class="description">'.$field['desc'].'</span>';
		        break;
		    }
		echo '</td></tr>';
	}

	echo '</table>';
}

function save_my_new_meta_fields($post_id) {
    global $new_meta_fields;  // Массив с нашими полями

    // проверяем наш проверочный код
    if (!wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__)))
        return $post_id;
    // Проверяем авто-сохранение
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    // Проверяем права доступа
    if ('new_book' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }

    // Если все отлично, прогоняем массив через foreach
    foreach ($new_meta_fields as $field) {
				$old_name = get_post_meta($post_id, $field['file_name'], true); // Получаем старые данные (если они есть), для сверки
				$new_name = $_POST[$field['file_name']];

				if ($new_name && $new_name != $old_name) {  // Если данные новые
						update_post_meta($post_id, $field['file_name'], $new_name); // Обновляем данные
				} elseif ('' == $new_name && $old_name) {
						delete_post_meta($post_id, $field['file_name'], $old_name); // Если данных нету, удаляем мету.
				}

        $old_id = get_post_meta($post_id, $field['id'], true); // Получаем старые данные (если они есть), для сверки
        $new_id = $_POST[$field['id']];

        if ($new_id && $new_id != $old_id) {  // Если данные новые
            update_post_meta($post_id, $field['id'], $new_id); // Обновляем данные
        } elseif ('' == $new_id && $old_id) {
            delete_post_meta($post_id, $field['id'], $old_id); // Если данных нету, удаляем мету.
        }
    } // end foreach
}
add_action('save_post', 'save_my_new_meta_fields'); // Запускаем функцию сохранения
