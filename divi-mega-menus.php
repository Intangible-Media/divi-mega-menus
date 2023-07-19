<?php
/*
Plugin Name: Divi Mega Menus
Description: A plugin to create mega menus using the Divi builder.
Version: 1.0
Author: Your Name
*/

// Register the Mega Menus post type
function create_mega_menu_post_type() {
    $args = array(
        'public' => true,
        'label'  => 'Mega Menus',
        'show_in_rest' => true,
        'supports' => array('title', 'editor')
    );
    register_post_type('mega_menu', $args);
}
add_action('init', 'create_mega_menu_post_type');

// Add Divi Builder support to the Mega Menus post type
function add_divi_builder_to_mega_menu($post_types) {
    $post_types[] = 'mega_menu';
    return $post_types;
}
add_filter('et_builder_post_types', 'add_divi_builder_to_mega_menu');

// Add a custom field to menu items
function add_custom_field_to_menu_item($item_id, $item) {
    ?>
    <p class="description description-wide">
        <label for="edit-menu-item-mega-menu-id-<?php echo $item_id; ?>">
            Mega Menu ID<br />
            <input type="text" id="edit-menu-item-mega-menu-id-<?php echo $item_id; ?>" class="widefat code edit-menu-item-custom" name="menu-item-mega-menu-id[<?php echo $item_id; ?>]" value="<?php echo get_post_meta($item_id, '_menu_item_mega_menu_id', true); ?>" />
        </label>
    </p>
    <?php
}
add_action('wp_nav_menu_item_custom_fields', 'add_custom_field_to_menu_item', 10, 2);

// Save the Mega Menu ID when the menu item is saved
function save_mega_menu_id($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-item-mega-menu-id'][$menu_item_db_id])) {
        $mega_menu_id = $_POST['menu-item-mega-menu-id'][$menu_item_db_id];
        update_post_meta($menu_item_db_id, '_menu_item_mega_menu_id', $mega_menu_id);
    }
}
add_action('wp_update_nav_menu_item', 'save_mega_menu_id', 10, 2);

// Add the Mega Menu to the menu item output
function add_mega_menu_to_menu_item($item_output, $item, $depth, $args) {
    // Check if the Divi Visual Builder is active
    if (!isset($_GET['et_fb']) || $_GET['et_fb'] != 1) {
        $mega_menu_id = get_post_meta($item->ID, '_menu_item_mega_menu_id', true);
        if ($mega_menu_id) {
            $mega_menu_content = get_post_field('post_content', $mega_menu_id);
            $item_output .= '<div class="mega-menu-content">' . apply_filters('the_content', $mega_menu_content) . '</div>';
        }
    }
    return $item_output;
}
add_filter('walker_nav_menu_start_el', 'add_mega_menu_to_menu_item', 10, 4);

// Enqueue the necessary CSS and JavaScript
function enqueue_mega_menu_scripts() {
    // Check if the Divi Visual Builder is active
    if (!isset($_GET['et_fb']) || $_GET['et_fb'] != 1) {
        // If the Divi Visual Builder is not active, enqueue the scripts and styles
        wp_enqueue_style('divi-mega-menus', plugins_url('divi-mega-menus.css', __FILE__));
        wp_enqueue_script('divi-mega-menus', plugins_url('divi-mega-menus.js', __FILE__), array('jquery'), false, true);
    }
}
add_action('wp_enqueue_scripts', 'enqueue_mega_menu_scripts');

function load_mega_menu_single_template($template) {
    global $post;

    // Check if the current post is a 'mega_menu' post
    if ($post->post_type == 'mega_menu') {
        // Check if the file exists in the plugin's directory
        if (file_exists(plugin_dir_path(__FILE__) . 'single-mega_menu.php')) {
            // Return the path to the 'single-mega_menu.php' file in the plugin's directory
            return plugin_dir_path(__FILE__) . 'single-mega_menu.php';
        }
    }

    // Return the default template if the 'mega_menu' template is not found
    return $template;
}

add_filter('single_template', 'load_mega_menu_single_template');
