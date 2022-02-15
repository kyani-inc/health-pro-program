<?php
namespace TotalThemeCore\Cpt;

defined( 'ABSPATH' ) || exit;

/**
 * Staff Post Type.
 *
 * @package TotalThemeCore
 * @version 1.3.2
 */
final class Staff {

	/**
	 * Instance.
	 *
	 * @access private
	 * @var object Class object.
	 */
	private static $instance;

	/**
	 * Create or retrieve the instance of Staff.
	 */
	public static function instance() {
		if ( is_null( static::$instance ) ) {
			static::$instance = new self();
			static::$instance->init_hooks();
		}

		return static::$instance;
	}

	/**
	 * Hook into actions and filters.
	 */
	public function init_hooks() {

		// Adds the staff post type.
		add_action( 'init', array( $this, 'register_post_type' ), 0 );

		// Adds the staff taxonomies.
		if ( ttc_validate_boolean( get_theme_mod( 'staff_tags', 'on' ) ) ) {
			add_action( 'init', array( $this, 'register_tags' ), 0 );
		}
		if ( ttc_validate_boolean( get_theme_mod( 'staff_categories', 'on' ) ) ) {
			add_action( 'init', array( $this, 'register_categories' ), 0 );
		}

		// Register staff sidebar.
		if ( get_theme_mod( 'staff_custom_sidebar', true ) ) {
			add_filter( 'wpex_register_sidebars_array', array( $this, 'register_sidebar' ) );
		}

		// Add image sizes.
		add_filter( 'wpex_image_sizes', array( $this, 'add_image_sizes' ) );

		// Create relations between users and staff members.
		if ( apply_filters( 'wpex_staff_users_relations', true ) ) {
			add_action( 'personal_options_update', array( $this, 'save_custom_profile_fields' ) );
			add_action( 'edit_user_profile_update', array( $this, 'save_custom_profile_fields' ) );
			add_filter( 'personal_options', array( $this, 'personal_options' ) );
		}

		/*-------------------------------------------------------------------------------*/
		/* -  Admin only actions/filters.
		/*-------------------------------------------------------------------------------*/
		if ( is_admin() ) {

			// Adds columns in the admin view for taxonomies.
			add_filter( 'manage_edit-staff_columns', array( $this, 'edit_columns' ) );
			add_action( 'manage_staff_posts_custom_column', array( $this, 'column_display' ), 10, 2 );

			// Allows filtering of posts by taxonomy in the admin view.
			add_action( 'restrict_manage_posts', array( $this, 'tax_filters' ) );

			// Add new image sizes tab.
			add_filter( 'wpex_image_sizes_tabs', array( $this, 'image_sizes_tabs' ) );

			// Add gallery metabox to staff.
			add_filter( 'wpex_gallery_metabox_post_types', array( $this, 'add_gallery_metabox' ), 20 );

		}

		/*-------------------------------------------------------------------------------*/
		/* -  Front-End only actions/filters.
		/*-------------------------------------------------------------------------------*/
		if ( ! is_admin() || wp_doing_ajax() ) {

			// Displays correct sidebar for staff posts.
			if ( get_theme_mod( 'staff_custom_sidebar', true ) ) {
				add_filter( 'wpex_get_sidebar', array( $this, 'display_sidebar' ) );
			}

			// Alter the post layouts for staff posts and archives.
			add_filter( 'wpex_post_layout_class', array( $this, 'layouts' ) );

			// Add subheading for staff member if enabled.
			add_action( 'wpex_post_subheading', array( $this, 'add_position_to_subheading' ) );

			// Archive query tweaks.
			add_action( 'pre_get_posts', array( $this, 'pre_get_posts' ) );

			// Tweak page header title.
			add_filter( 'wpex_page_header_title_args', array( $this, 'alter_title' ) );

			// Frontend staff user relations.
			if ( apply_filters( 'wpex_staff_users_relations', true ) ) {
				add_filter( 'pre_get_avatar', array( $this, 'filter_avatar' ), 10, 3 );
				add_filter( 'the_author', array( $this, 'filter_the_author' ) );
				add_filter( 'author_link', array( $this, 'filter_author_link' ), 10, 2 );
				add_filter( 'get_comment_author', array( $this, 'filter_comment_author' ), 10, 3 );
				add_filter( 'get_comment_author_url', array( $this, 'filter_comment_url' ), 10, 3 );
			}

		}

	} // End __construct

	/*-------------------------------------------------------------------------------*/
	/* -  Class Methods.
	/*-------------------------------------------------------------------------------*/

	/**
	 * Return correct staff name.
	 */
	public function staff_name() {
		if ( function_exists( 'wpex_get_staff_name' ) ) {
			return wpex_get_staff_name();
		}
		return get_theme_mod( 'staff_labels', esc_html__( 'Staff', 'total-theme-core' ) );
	}

	/**
	 * Return correct staff singular name.
	 */
	public function staff_singular_name() {
		if ( function_exists( 'wpex_get_staff_singular_name' ) ) {
			return wpex_get_staff_singular_name();
		}
		return get_theme_mod( 'staff_singular_name', esc_html__( 'Staff Member', 'total-theme-core' ) );
	}

	/**
	 * Return correct staff icon.
	 */
	public function staff_menu_icon() {
		if ( function_exists( 'wpex_get_staff_menu_icon' ) ) {
			return wpex_get_staff_menu_icon();
		}
		return get_theme_mod( 'staff_admin_icon', 'businessman' );
	}

	/**
	 * Register post type.
	 */
	public function register_post_type() {

		$name          = $this->staff_name();
		$singular_name = $this->staff_singular_name();
		$has_archive   = wp_validate_boolean( get_theme_mod( 'staff_has_archive', false ) );
		$default_slug  = $has_archive ? 'staff' : 'staff-member';
		$slug          = ( $slug = get_theme_mod( 'staff_slug' ) ) ?: $default_slug;
		$menu_icon     = $this->staff_menu_icon();

		$labels = array(
			'name'               => $name,
			'singular_name'      => $singular_name,
			'add_new'            => esc_html__( 'Add New', 'total-theme-core' ),
			'add_new_item'       => sprintf( esc_html__( 'Add New %s', 'total-theme-core' ), $singular_name ),
			'edit_item'          => sprintf( esc_html__( 'Edit %s', 'total-theme-core' ), $singular_name ),
			'new_item'           => sprintf( esc_html__( 'Add New %s', 'total-theme-core' ), $singular_name ),
			'view_item'          => sprintf( esc_html__( 'View %s', 'total-theme-core' ), $singular_name ),
			'search_items'       => esc_html__( 'Search Items', 'total-theme-core' ),
			'not_found'          => esc_html__( 'No Items Found', 'total-theme-core' ),
			'not_found_in_trash' => esc_html__( 'No Items Found In Trash', 'total-theme-core' )
		);

		$supports = array(
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'comments',
			'custom-fields',
			'revisions',
			'author',
			'page-attributes',
		);

		$args = array(
			'public'          => true,
			'capability_type' => 'post',
			'labels'          => $labels,
			'supports'        => $supports,
			'has_archive'     => $has_archive,
			'show_in_rest'    => wp_validate_boolean( get_theme_mod( 'staff_show_in_rest', false ) ),
			'menu_icon'       => 'dashicons-' . sanitize_html_class( $menu_icon ),
			'menu_position'   => 20,
			'rewrite'         => array(
				'slug'        => $slug,
				'with_front'  => false
			),
		);

		$args = (array) apply_filters( 'wpex_staff_args', $args );

		register_post_type( 'staff', $args );

	}

	/**
	 * Register Staff tags.
	 */
	public function register_tags() {

		$name = ( $name = get_theme_mod( 'staff_tag_labels' ) ) ? esc_html( $name ) : esc_html__( 'Staff Tags', 'total-theme-core' );
		$slug = ( $slug = get_theme_mod( 'staff_tag_slug' ) ) ? esc_html( $slug ) : 'staff-tag';

		$labels = array(
			'name' => $name,
			'singular_name'              => $name,
			'menu_name'                  => $name,
			'search_items'               => esc_html__( 'Search Staff Tags', 'total-theme-core' ),
			'popular_items'              => esc_html__( 'Popular Staff Tags', 'total-theme-core' ),
			'all_items'                  => esc_html__( 'All Staff Tags', 'total-theme-core' ),
			'parent_item'                => esc_html__( 'Parent Staff Tag', 'total-theme-core' ),
			'parent_item_colon'          => esc_html__( 'Parent Staff Tag:', 'total-theme-core' ),
			'edit_item'                  => esc_html__( 'Edit Staff Tag', 'total-theme-core' ),
			'update_item'                => esc_html__( 'Update Staff Tag', 'total-theme-core' ),
			'add_new_item'               => esc_html__( 'Add New Staff Tag', 'total-theme-core' ),
			'new_item_name'              => esc_html__( 'New Staff Tag Name', 'total-theme-core' ),
			'separate_items_with_commas' => esc_html__( 'Separate staff tags with commas', 'total-theme-core' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove staff tags', 'total-theme-core' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used staff tags', 'total-theme-core' ),
		);

		$args = apply_filters( 'wpex_taxonomy_staff_tag_args', array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => false,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'          => $slug,
				'with_front'    => false
			),
		) );

		register_taxonomy( 'staff_tag', array( 'staff' ), $args );

	}

	/**
	 * Register Staff category.
	 */
	public function register_categories() {

		$name = ( $name = get_theme_mod( 'staff_cat_labels' ) ) ? esc_html( $name ) : esc_html__( 'Staff Categories', 'total-theme-core' );
		$slug = ( $slug = get_theme_mod( 'staff_cat_slug' ) ) ? esc_html( $slug ) : 'staff-category';

		$labels = array(
			'name'                       => $name,
			'singular_name'              => $name,
			'menu_name'                  => $name,
			'search_items'               => esc_html__( 'Search', 'total-theme-core' ),
			'popular_items'              => esc_html__( 'Popular', 'total-theme-core' ),
			'all_items'                  => esc_html__( 'All', 'total-theme-core' ),
			'parent_item'                => esc_html__( 'Parent', 'total-theme-core' ),
			'parent_item_colon'          => esc_html__( 'Parent', 'total-theme-core' ),
			'edit_item'                  => esc_html__( 'Edit', 'total-theme-core' ),
			'update_item'                => esc_html__( 'Update', 'total-theme-core' ),
			'add_new_item'               => esc_html__( 'Add New', 'total-theme-core' ),
			'new_item_name'              => esc_html__( 'New', 'total-theme-core' ),
			'separate_items_with_commas' => esc_html__( 'Separate with commas', 'total-theme-core' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove', 'total-theme-core' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used', 'total-theme-core' ),
		);

		$args = apply_filters( 'wpex_taxonomy_staff_category_args', array(
			'labels'            => $labels,
			'public'            => true,
			'show_in_nav_menus' => true,
			'show_ui'           => true,
			'show_tagcloud'     => true,
			'hierarchical'      => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'          => $slug,
				'with_front'    => false
			),
		) );

		register_taxonomy( 'staff_category', array( 'staff' ), $args );

	}


	/**
	 * Adds columns to the WP dashboard edit screen.
	 */
	public function edit_columns( $columns ) {
		if ( taxonomy_exists( 'staff_category' ) ) {
			$columns['staff_category'] = esc_html__( 'Category', 'total-theme-core' );
		}
		if ( taxonomy_exists( 'staff_tag' ) ) {
			$columns['staff_tag'] = esc_html__( 'Tags', 'total-theme-core' );
		}
		return $columns;
	}


	/**
	 * Adds columns to the WP dashboard edit screen.
	 */
	public function column_display( $column, $post_id ) {

		switch ( $column ) :

			// Display the staff categories in the column view.
			case 'staff_category':

				$category_list = get_the_term_list( $post_id, 'staff_category', '', ', ', '' );

				if ( ! empty( $category_list ) && ! is_wp_error( $category_list ) ) {
					echo $category_list;
				} else {
					echo '&#8212;';
				}

			break;

			// Display the staff tags in the column view.
			case 'staff_tag':

				$tag_list = get_the_term_list( $post_id, 'staff_tag', '', ', ', '' );

				if ( ! empty( $tag_list ) && ! is_wp_error( $tag_list ) ) {
					echo $tag_list;
				} else {
					echo '&#8212;';
				}

			break;

		endswitch;

	}

	/**
	 * Adds taxonomy filters to the staff admin page.
	 */
	public function tax_filters( $post_type ) {

		if ( 'staff' == $post_type ) {

			$taxonomies = array( 'staff_category', 'staff_tag' );

			foreach ( $taxonomies as $tax_slug ) {

				if ( ! taxonomy_exists( $tax_slug ) ) {
					continue;
				}

				$current_tax_slug = $_GET[$tax_slug] ?? false;
				$tax_obj = get_taxonomy( $tax_slug );
				$tax_name = $tax_obj->labels->name;
				$terms = get_terms( $tax_slug );

				if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) { ?>

					<select name="<?php echo esc_attr( $tax_slug ); ?>" id="<?php echo esc_attr( $tax_slug ); ?>" class="postform">

					<option value=""><?php echo esc_html( $tax_name ); ?></option>

					<?php foreach ( $terms as $term ) { ?>

						<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $current_tax_slug, $term->slug, true ); ?>><?php echo esc_html( $term->name ); ?> (<?php echo absint( $term->count ); ?> )</option>

					<?php } ?>

					</select>

				<?php }

			}

		}

	}

	/**
	 * Registers a new custom staff sidebar.
	 */
	public function register_sidebar( $sidebars ) {
		$obj = get_post_type_object( 'staff' );
		$post_type_name = $obj->labels->name;
		$sidebars['staff_sidebar'] = $post_type_name . ' ' . esc_html__( 'Sidebar', 'total-theme-core' );
		return $sidebars;
	}

	/**
	 * Alter main sidebar to display staff sidebar.
	 */
	public function display_sidebar( $sidebar ) {
		if ( is_singular( 'staff' ) || wpex_is_staff_tax() || is_post_type_archive( 'staff' ) ) {
			$sidebar = 'staff_sidebar';
		}
		return $sidebar;
	}

	/**
	 * Alter the post layouts for staff posts and archives.
	 */
	public function layouts( $layout_class ) {
		if ( is_singular( 'staff' ) ) {
			$layout_class = get_theme_mod( 'staff_single_layout' );
		} elseif ( wpex_is_staff_tax() || is_post_type_archive( 'staff' ) ) {
			$layout_class = get_theme_mod( 'staff_archive_layout', 'full-width' );
		}
		return $layout_class;
	}

	/**
	 * Display position for page header subheading.
	 */
	public function add_position_to_subheading( $subheading ) {
		if ( is_singular( 'staff' )
			&& get_theme_mod( 'staff_single_header_position', true )
			&& ! in_array( 'title', wpex_staff_single_blocks() )
			&& $meta = get_post_meta( get_the_ID(), 'wpex_staff_position', true )
		) {
			$subheading = $meta;
		}
		return $subheading;
	}

	/**
	 * Archive query tweaks.
	 */
	public function pre_get_posts( $query ) {

		if ( ! function_exists( 'wpex_is_staff_tax' ) || is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( wpex_is_staff_tax() || $query->is_post_type_archive( 'staff' ) ) {

			$query->set( 'posts_per_page', get_theme_mod( 'staff_archive_posts_per_page', '12' ) );

			$archive_orderby = get_theme_mod( 'staff_archive_orderby' );

			if ( ! empty( $archive_orderby ) ) {
				$query->set( 'orderby', $archive_orderby );
			}

			$archive_order = get_theme_mod( 'staff_archive_order' );

			if ( ! empty( $archive_order ) ) {
				$query->set( 'order', $archive_order );
			}

		}

	}

	/**
	 * Adds a "staff" tab to the image sizes admin panel.
	 */
	public function image_sizes_tabs( $array ) {
		$array['staff'] = wpex_get_staff_name();
		return $array;
	}

	/**
	 * Adds image sizes for the staff to the image sizes panel.
	 */
	public function add_image_sizes( $sizes ) {
		$obj            = get_post_type_object( 'staff' );
		$post_type_name = $obj->labels->singular_name;
		$sizes['staff_entry'] = array(
			'label'   => sprintf( esc_html__( '%s Entry', 'total-theme-core' ), $post_type_name ),
			'width'   => 'staff_entry_image_width',
			'height'  => 'staff_entry_image_height',
			'crop'    => 'staff_entry_image_crop',
			'section' => 'staff',
		);
		$sizes['staff_post'] = array(
			'label'   => sprintf( esc_html__( '%s Post', 'total-theme-core' ), $post_type_name ),
			'width'   => 'staff_post_image_width',
			'height'  => 'staff_post_image_height',
			'crop'    => 'staff_post_image_crop',
			'section' => 'staff',
		);
		$sizes['staff_related'] = array(
			'label'   => sprintf( esc_html__( '%s Post Related', 'total-theme-core' ), $post_type_name ),
			'width'   => 'staff_related_image_width',
			'height'  => 'staff_related_image_height',
			'crop'    => 'staff_related_image_crop',
			'section' => 'staff',
		);
		return $sizes;
	}

	/**
	 * Tweak the page header.
	 */
	public function alter_title( $args ) {
		if ( is_singular( 'staff' ) ) {
			$blocks = wpex_staff_single_blocks();
			if ( $blocks && is_array( $blocks ) && ! in_array( 'title', $blocks ) ) {
				$args['string']   = single_post_title( '', false );
				$args['html_tag'] = 'h1';
			}
		}
		return $args;
	}

	/**
	 * Adds the staff post type to the gallery metabox post types array.
	 */
	public function add_gallery_metabox( $types ) {
		$types[] = 'staff';
		return $types;
	}

	/**
	 * Adds field to user dashboard to connect to staff member.
	 */
	public function personal_options( $user ) {

		// Get staff members.
		$staff_posts = get_posts( array(
			'post_type'      => 'staff',
			'posts_per_page' => -1,
			'fields'         => 'ids',
		) );

		// Return if no staff.
		if ( ! $staff_posts ) {
			return;
		}

		// Get staff meta.
		$meta_value = get_user_meta( $user->ID, 'wpex_staff_member_id', true ); ?>

			<tr>
				<th scope="row"><?php esc_html_e( 'Connect to Staff Member', 'total-theme-core' ); ?></th>
				<td>
					<fieldset>
						<select type="text" id="wpex_staff_member_id" name="wpex_staff_member_id">
							<option value="" <?php selected( $meta_value, '', true ); ?>>&#8212;</option>
							<?php foreach ( $staff_posts as $id ) { ?>
								<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $meta_value, $id, true ); ?>><?php echo esc_html( get_the_title( $id ) ); ?></option>
							<?php } ?>
						</select>
					</fieldset>
				</td>
			</tr>

		<?php

	}

	/**
	 * Saves user profile fields.
	 */
	public function save_custom_profile_fields( $user_id ) {

		// Get meta.
		$meta = $_POST['wpex_staff_member_id'] ?? '';

		// Get options.
		$relations = get_option( 'wpex_staff_users_relations' );
		$relations = is_array( $relations ) ? $relations : array(); // sanitize

		// Add item.
		if ( $meta ) {

			// Prevent staff ID's from being used more then 1x.
			if ( array_key_exists( $meta, $relations ) ) {
				return;
			}

			// Update list of relations.
			else {
				$relations[$user_id] = $meta;
				update_option( 'wpex_staff_users_relations', $relations );
			}

			// Update user meta.
			update_user_meta( $user_id, 'wpex_staff_member_id', $meta );

		}

		// Remove item.
		else {

			unset( $relations[ $user_id ] );
			update_option( 'wpex_staff_users_relations', $relations );
			delete_user_meta( $user_id, 'wpex_staff_member_id' );

		}

	}

	/**
	 * Filter the user avatar when a staff member is connected to it.
	 */
	public function filter_avatar( $html, $id_or_email, $args ) {

		if ( ! function_exists( 'wpex_get_staff_member_by_user' )
			|| ! function_exists( 'wpex_process_user_identifier' )
			|| ! function_exists( 'wpex_get_post_thumbnail' )
		) {
			return $html;
		}

		$user = wpex_process_user_identifier( $id_or_email );

		if ( is_object( $user ) && isset( $user->ID ) ) {

			$staff_member_id = wpex_get_staff_member_by_user( $user->ID );

			if ( $staff_member_id ) {

				$staff_thumbnail = get_post_thumbnail_id( $staff_member_id );

				if ( $staff_thumbnail ) {

					$class = array( 'avatar', 'avatar-' . (int) $args['size'], 'photo' );

					$staff_avatar_args = array(
						'attachment' => $staff_thumbnail,
						'size'       => 'wpex_custom',
						'width'      => $args['height'],
						'height'     => $args['width'],
						'alt'        => $args['alt'],
					);

					if ( ! empty( $args['class'] ) ) {
						if ( is_array( $args['class'] ) ) {
							$class = array_merge( $class, $args['class'] );
						} else {
							$class[] = $args['class'];
						}
					}

					$staff_avatar_args['class'] = $class;

					if ( ! empty( $args['extra_attr'] ) ) {
						$staff_avatar_args['attributes'] = array_map( 'esc_attr', $args['extra_attr'] );
					}

					$staff_avatar = wpex_get_post_thumbnail( $staff_avatar_args );

					if ( $staff_avatar ) {
						$html = $staff_avatar;
					}


				}

			}

		}

		return $html;

	}

	/**
	 * Alter the author name when a staff member is connected to an author.
	 */
	public function filter_the_author( $name ) {
		global $authordata;
		if ( function_exists( 'wpex_get_staff_member_by_user' ) && is_object( $authordata ) && isset( $authordata->ID ) ) {
			$staff_member = wpex_get_staff_member_by_user( $authordata->ID );
			if ( $staff_member ) {
				$name = get_the_title( $staff_member );
			}
		}
		return $name;
	}

	/**
	 * Filter the author url when a staff member is connected to an author.
	 */
	public function filter_author_link( $link, $author_id ) {
		if ( $author_id && function_exists( 'wpex_get_staff_member_by_user' ) ) {
			$staff_member = wpex_get_staff_member_by_user( $author_id );
			if ( $staff_member ) {
				$link = get_permalink( $staff_member );
			}
		}
		return $link;
	}

	/**
	 * Filter the comment author url when a staff member is connected to an author.
	 */
	public function filter_comment_author( $author, $comment_ID, $comment ) {

		if ( ! function_exists( 'wpex_get_staff_member_by_user' ) ) {
			return $author;
		}

		if ( is_object( $comment ) && isset( $comment->comment_author_email ) ) {
			$user = get_user_by( 'email', $comment->comment_author_email );
			if ( is_object( $user ) && isset( $user->ID ) ) {
				$staff_member = wpex_get_staff_member_by_user( $user->ID );
				if ( $staff_member ) {
					$author = get_the_title( $staff_member );
				}
			}
		}

		return $author;
	}

	/**
	 * Filter the comment author url when a staff member is connected to an author.
	 */
	public function filter_comment_url( $url, $id, $comment ) {

		if ( ! function_exists( 'wpex_get_staff_member_by_user' ) ) {
			return $url;
		}

		if ( is_object( $comment ) && isset( $comment->comment_author_email ) ) {
			$user = get_user_by( 'email', $comment->comment_author_email );
			if ( is_object( $user ) && isset( $user->ID ) ) {
				$staff_member = wpex_get_staff_member_by_user( $user->ID );
				if ( $staff_member ) {
					$url = get_permalink( $staff_member );
				}
			}
		}

		return $url;
	}

}