<?php

function paradise_post_type_init()
{
    $labels = array(
        'name'                  => _x('Bookings', 'Post type general name', 'car-booking'),
        'singular_name'         => _x('Booking', 'Post type singular name', 'car-booking'),
        'menu_name'             => _x('Bookings', 'Admin Menu text', 'car-booking'),
        'name_admin_bar'        => _x('car-booking', 'Add New on Toolbar', 'car-booking'),
        'add_new'               => __('Add New', 'car-booking'),
        'add_new_item'          => __('Add New car-booking', 'car-booking'),
        'new_item'              => __('New car-booking', 'car-booking'),
        'edit_item'             => __('Edit car-booking', 'car-booking'),
        'view_item'             => __('View car-booking', 'car-booking'),
        'all_items'             => __('All Car Bookings', 'car-booking'),
        'search_items'          => __('Search Car Bookings', 'car-booking'),
        'parent_item_colon'     => __('Parent Car Bookings:', 'car-booking'),
        'not_found'             => __('No Car Bookings found.', 'car-booking'),
        'not_found_in_trash'    => __('No Car Bookings found in Trash.', 'car-booking'),
        'featured_image'        => _x('Booking Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'car-booking'),
        'set_featured_image'    => _x('Set cover image', 'Overrides the “Set featured image” phrase for this post type. Added in 4.3', 'car-booking'),
        'remove_featured_image' => _x('Remove cover image', 'Overrides the “Remove featured image” phrase for this post type. Added in 4.3', 'car-booking'),
        'use_featured_image'    => _x('Use as cover image', 'Overrides the “Use as featured image” phrase for this post type. Added in 4.3', 'car-booking'),
        'archives'              => _x('Booking archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'car-booking'),
        'insert_into_item'      => _x('Insert into car-booking', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'car-booking'),
        'uploaded_to_this_item' => _x('Uploaded to this car-booking', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'car-booking'),
        'filter_items_list'     => _x('Filter car-bookings list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'car-booking'),
        'items_list_navigation' => _x('Bookings list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'car-booking'),
        'items_list'            => _x('Bookings list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'car-booking'),
    );
    $args = array(
        'labels'             => $labels,
        'description'        => 'Booking custom post type.',
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array('slug' => 'car-booking'),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => 20,
        'supports'           => array('title', 'editor', 'author', 'thumbnail'),
        // 'taxonomies'         => array( 'category', 'post_tag' ),
        'show_in_rest'       => true
    );

    register_post_type('car_booking', $args);
}
add_action('init', 'paradise_post_type_init');


add_action('admin_menu', 'paradise_add_pages');

function paradise_add_pages()
{
    add_submenu_page(
        'edit.php?post_type=car_booking',
        __('Booking List', 'car-booking'),
        __('Booking List', 'car-booking'),
        'manage_options',
        'car-booking-list',
        'admin_car_booking_list_callback'
    );
}

function admin_car_booking_list_callback()
{ ?>
    <div class="table-wrapper paradise-admin-table">
        <table class="paradise-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Date start</th>
                    <th>Date End</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>Contact</th>
                    <th>Driver Details</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <img src="image.webp" alt="driver" />
                        <span>Saugat Thapa</span><span> 123456789</span>
                    </td>
                    <td>2024/04/16</td>
                    <td>2024/04/18</td>
                    <td>Kathmandu</td>
                    <td>Colombo</td>
                    <td>123456789</td>
                    <td>
                        <img src="image.webp" alt="driver" />
                        <span>Saugat Thapa</span><span> 123456789</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="image.webp" alt="driver" />
                        <span>Saugat Thapa</span><span> 123456789</span>
                    </td>
                    <td>2024/04/16</td>
                    <td>2024/04/18</td>
                    <td>Kathmandu</td>
                    <td>Colombo</td>
                    <td>123456789</td>
                    <td>
                        <img src="image.webp" alt="driver" />
                        <span>Saugat Thapa</span><span> 123456789</span>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
<?php }
