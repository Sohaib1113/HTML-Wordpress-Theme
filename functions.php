<?php 

function university_files(){
    wp_enqueue_script('main-university',get_theme_file_uri('/build/index.js'),array('jquery'),'1.0', true);
    wp_enqueue_style('font-awesome','//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i" rel="stylesheet');
    wp_enqueue_style('university_main_styles',get_theme_file_uri('/build/style-index.css'));
    wp_enqueue_style('university_main_styles2',get_theme_file_uri('/build/index.css'));
    wp_enqueue_style('font-awesome','//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    wp_enqueue_style('branch-locator-css', get_template_directory_uri() . '/build/branchlocator.css', array(), '1.0', 'all');
    // wp_enqueue_script('main-university-js',get_theme_file_uri('/build/index.js'),array('jquery'),'1.0',true);
}
add_action('wp_enqueue_scripts','university_files');


function university_featuures(){ 
add_theme_support('title-tag');
}

add_action('after_setup_theme','university_featuures');

// function university_post_types(){
//     register_post_type('event',array(
//         'public'=> true,
//         'menu_icon'=>'dashicons-image-filter',
//         'labels'=>array(
//             'name'=>'Events',
//             'add_new_item'=>'Add New Event',
//             'edit_item'=>'Edit Event',
//             'all_items'=>'All Events',
//             'singular_name'=>'Event'
//         )
//     ));
// }
// add_action('init','university_post_types');





// MYCPT
function register_branch_locator_post_type() {
    $labels = array(
        'name'               => 'Branch Locators',
        'singular_name'      => 'Branch Locator',
        'menu_name'          => 'Branch Locator',
        'add_new'            => 'Add New Branch',
        'add_new_item'       => 'Add New Branch',
        'edit_item'          => 'Edit Branch',
        'new_item'           => 'New Branch',
        'view_item'          => 'View Branch',
        'all_items'          => 'All Branches',
        'search_items'       => 'Search Branches',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => false, // For backend only
        'show_ui'            => true,
        'show_in_menu'       => true,
        'supports'           => array('title'), // Only title field
        'capability_type'    => 'post',
        'menu_position'      => 20,
    );

    register_post_type('branch_locator', $args);
}
add_action('init', 'register_branch_locator_post_type');

// taxonokmy
function register_branch_locator_taxonomies() {
    // Country taxonomy
    register_taxonomy('country', 'branch_locator', array(
        'labels' => array(
            'name' => 'Countries',
            'singular_name' => 'Country',
            'add_new_item' => 'Add New Country',
            'edit_item' => 'Edit Country',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
    ));

    // State taxonomy
    register_taxonomy('state', 'branch_locator', array(
        'labels' => array(
            'name' => 'States',
            'singular_name' => 'State',
            'add_new_item' => 'Add New State',
            'edit_item' => 'Edit State',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
    ));

    // City taxonomy
    register_taxonomy('city', 'branch_locator', array(
        'labels' => array(
            'name' => 'Cities',
            'singular_name' => 'City',
            'add_new_item' => 'Add New City',
            'edit_item' => 'Edit City',
        ),
        'hierarchical' => true,
        'show_ui' => true,
        'show_admin_column' => true,
    ));

    // Pincode taxonomy
    register_taxonomy('pincode', 'branch_locator', array(
        'labels' => array(
            'name' => 'Pincodes',
            'singular_name' => 'Pincode',
            'add_new_item' => 'Add New Pincode',
            'edit_item' => 'Edit Pincode',
        ),
        'hierarchical' => false,
        'show_ui' => true,
        'show_admin_column' => true,
    ));
    // Metaboxfor phonelocation
}
add_action('init', 'register_branch_locator_taxonomies');



// Metabox for location and phone
// Meta box for phone number and location
function add_branch_locator_info_meta_box() {
    add_meta_box(
        'branch_locator_info',
        'Branch Info',
        'branch_locator_info_meta_box_callback',
        'branch_locator',
        'side'
    );
}
add_action('add_meta_boxes', 'add_branch_locator_info_meta_box');

function branch_locator_info_meta_box_callback($post) {
    $phone_number = get_post_meta($post->ID, '_branch_phone_number', true);
    $location_address = get_post_meta($post->ID, '_branch_location_address', true);
    ?>
    <label for="branch_phone_number">Phone Number:</label>
    <input type="text" id="branch_phone_number" name="branch_phone_number" value="<?php echo esc_attr($phone_number); ?>" /><br><br>
    
    <label for="branch_location_address">Location/Address:</label>
    <textarea id="branch_location_address" name="branch_location_address"><?php echo esc_textarea($location_address); ?></textarea>
    <?php
}

function save_branch_locator_info_meta_box_data($post_id) {
    if (isset($_POST['branch_phone_number'])) {
        update_post_meta($post_id, '_branch_phone_number', sanitize_text_field($_POST['branch_phone_number']));
    }
    if (isset($_POST['branch_location_address'])) {
        update_post_meta($post_id, '_branch_location_address', sanitize_textarea_field($_POST['branch_location_address']));
    }
}
add_action('save_post', 'save_branch_locator_info_meta_box_data');
// Add custom columns to the Branch Locator admin list
function set_branch_locator_columns($columns) {
    $columns['branch_phone_number'] = __('Phone Number', 'textdomain');
    $columns['branch_location_address'] = __('Location/Address', 'textdomain');
    return $columns;
}
add_filter('manage_branch_locator_posts_columns', 'set_branch_locator_columns');

// Populate custom columns with data
function branch_locator_custom_column($column, $post_id) {
    switch ($column) {
        case 'branch_phone_number':
            $phone_number = get_post_meta($post_id, '_branch_phone_number', true);
            echo esc_html($phone_number);
            break;
        case 'branch_location_address':
            $location_address = get_post_meta($post_id, '_branch_location_address', true);
            echo esc_html($location_address);
            break;
    }
}
add_action('manage_branch_locator_posts_custom_column', 'branch_locator_custom_column', 10, 2);

// Metabox for location and phone

// metabox for category selection
function add_branch_locator_category_meta_box() {
    add_meta_box(
        'branch_locator_category',
        'Branch Category',
        'branch_locator_category_meta_box_callback',
        'branch_locator',
        'side'
    );
}
add_action('add_meta_boxes', 'add_branch_locator_category_meta_box');

function branch_locator_category_meta_box_callback($post) {
    $value = get_post_meta($post->ID, '_branch_category', true);
    ?>
    <label>Select Branch Category:</label><br>
    <input type="radio" name="branch_category" value="Mirae Asset Offices" <?php checked($value, 'Mirae Asset Offices'); ?>> Mirae Asset Offices<br>
    <input type="radio" name="branch_category" value="KFintech Investor Service Center" <?php checked($value, 'KFintech Investor Service Center'); ?>> KFintech Investor Service Center<br>
    <input type="radio" name="branch_category" value="Locate Your Distributor" <?php checked($value, 'Locate Your Distributor'); ?>> Locate Your Distributor<br>
    <?php
}

function save_branch_locator_category_meta_box_data($post_id) {
    if (isset($_POST['branch_category'])) {
        update_post_meta($post_id, '_branch_category', sanitize_text_field($_POST['branch_category']));
    }
}
add_action('save_post', 'save_branch_locator_category_meta_box_data');



// MY API TO FETCH ALL BRANCHES
// Register the custom REST API route
// Register the custom REST API route





// ADMINMENU AND SUBMENU 

add_action('admin_menu', 'simple_admin_menu');

function simple_admin_menu() {
    // Create top-level menu
    add_menu_page(
        'Simple Menu',           // Page title
        'Simple Menu',           // Menu title
        'manage_options',        // Capability
        'simple-menu',           // Menu slug
        'simple_menu_page',      // Function to display the page
        'dashicons-admin-generic', // Icon URL
        6                        // Position
    );

    // Create submenu
    add_submenu_page(
        'simple-menu',           // Parent slug
        'Register',              // Page title
        'Register',              // Menu title
        'manage_options',        // Capability
        'simple-menu-register',  // Submenu slug
        'simple_menu_register_page' // Function to display the subpage
    );
}

function simple_menu_page() {
    echo '<h1>Simple Menu Page</h1>';
    echo '<p>This is the main admin menu page.</p>';
}

function simple_menu_register_page() {
    echo '<h1>Register Page</h1>';
    echo '<p>This is the submenu page for registration.</p>';
}

// SETTINGS
