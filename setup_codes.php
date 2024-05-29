<?php


add_action('wp_enqueue_scripts', 'paradice_booking_load');
function paradice_booking_load()
{
    wp_enqueue_media();
}

add_action("admin_menu", "createBookingMenu");

function createBookingMenu()
{
    add_menu_page("Car Booking", "Car Booking", 0, "car-booking-list", "myBookingfunction", "dashicons-schedule", 5);
    add_submenu_page("car-booking-list", "Add Booking", "Add Booking", 0, "add-booking", "myAddingBookingFunction");
    add_submenu_page("car-booking-list", "Setting", "Setting", 0, "booking-setting", "myBookingSettingFunction");
}
function myBookingfunction()

{
    global $wpdb;
    $car_booking_table = $wpdb->prefix . 'car_booking'; ?>
    <div class="table-wrapper paradise-admin-table">
        <h4 class="upcoming-rides">Upcoming rides </h4>
        <table class="paradise-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Date start</th>
                    <th>Date End</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>No of Travellers </th>
                    <th>Driver Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $upcoming_rides = $wpdb->get_results($wpdb->prepare("SELECT * FROM $car_booking_table WHERE status=%s AND DATE(date_to)>NOW() ORDER BY id desc", 'booking'));
                if ($upcoming_rides) {
                    foreach ($upcoming_rides as $upcoming_ride) {
                        $driver_id = $upcoming_ride->assigned_user;
                        $customer_id = $upcoming_ride->user_id;
                ?>
                        <tr>
                            <td>
                                <?php

                                $customer_details = get_userdata($customer_id);
                                // var_dump($user_details);
                                // $customer_image = get_avatar_url($customer_id);
                                $customer_phone_number = get_user_meta($customer_id, 'phone_number', true);

                                $custmoer_profile_id = get_user_meta($customer_id, 'profile_image_id', true);
                                $customer_profile = wp_get_attachment_url($custmoer_profile_id);
                                ?>

                                <img src="<?php echo $customer_profile ? $customer_profile : '' ?>" alt="customer" />
                                <div class="admin_customer_details">
                                    <span><?php echo $customer_details->display_name; ?> </span><span>
                                        <?php echo $customer_phone_number ?></span>
                                </div>
                            </td>

                            <td><?php echo $upcoming_ride->date_from; ?></td>
                            <td><?php echo $upcoming_ride->date_to; ?></td>
                            <td><?php echo $upcoming_ride->source; ?></td>
                            <td><?php echo $upcoming_ride->destination; ?></td>
                            <td><?php echo $upcoming_ride->no_of_travellers; ?> </td>
                            <td>
                                <?php

                                $driver_details = get_userdata($driver_id);
                                // var_dump($user_details);
                                // $driver_image = get_avatar_url($driver_id);
                                $driver_phone_number = get_user_meta($driver_id, 'phone_number', true);
                                $driver_profile_id = get_user_meta($driver_id, 'profile_image_id', true);
                                $driver_profile = wp_get_attachment_url($driver_profile_id);
                                ?>

                                <img src="<?php echo $driver_profile ? $driver_profile : '' ?>" alt="customer" />
                                <div class="admin_customer_details">
                                    <span><?php echo $driver_details->display_name; ?> </span><span>
                                        <?php echo $driver_phone_number ?></span>
                                </div>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr>';
                    echo '<td> <h5> No data found. </h5></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="table-wrapper paradise-admin-table">
        <h4 class="completed_rides">Completed Rides</h4>
        <table class="paradise-table">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Date start</th>
                    <th>Date End</th>
                    <th>Source</th>
                    <th>Destination</th>
                    <th>No of Travellers </th>
                    <th>Driver Details</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $completed_rides = $wpdb->get_results($wpdb->prepare("SELECT * FROM $car_booking_table WHERE status=%s AND DATE(date_to)<NOW() ORDER BY id desc", 'booking'));
                if ($completed_rides) {
                    foreach ($completed_rides as $completed_ride) {
                        $driver_id = $completed_ride->assigned_user;
                        $customer_id = $completed_ride->user_id;
                ?>
                        <tr>
                            <td>
                                <?php

                                $customer_details = get_userdata($customer_id);
                                // var_dump($user_details);
                                // $customer_image = get_avatar_url($customer_id);
                                $customer_phone_number = get_user_meta($customer_id, 'phone_number', true);

                                $custmoer_profile_id = get_user_meta($customer_id, 'profile_image_id', true);
                                $customer_profile = wp_get_attachment_url($custmoer_profile_id);
                                ?>

                                <img src="<?php echo $customer_profile ? $customer_profile : '' ?>" alt="customer" />
                                <div class="admin_customer_details"> <span><?php echo $customer_details->display_name; ?>
                                    </span><span>
                                        <?php echo $customer_phone_number ?></span>
                                </div>

                            </td>

                            <td><?php echo $completed_ride->date_from; ?></td>
                            <td><?php echo $completed_ride->date_to; ?></td>
                            <td><?php echo $completed_ride->source; ?></td>
                            <td><?php echo $completed_ride->destination; ?></td>
                            <td><?php echo $completed_ride->no_of_travellers; ?> </td>
                            <td>
                                <?php

                                $driver_details = get_userdata($driver_id);
                                // var_dump($user_details);
                                // $driver_image = get_avatar_url($driver_id);
                                $driver_phone_number = get_user_meta($driver_id, 'phone_number', true);
                                $driver_profile_id = get_user_meta($driver_id, 'profile_image_id', true);
                                $driver_profile = wp_get_attachment_url($driver_profile_id);
                                ?>

                                <img src="<?php echo $driver_profile ? $driver_profile : '' ?>" alt="customer" />
                                <div class="admin_customer_details">
                                    <span><?php echo $driver_details->display_name; ?> </span><span>
                                        <?php echo $driver_phone_number ?></span>
                                </div>
                            </td>
                        </tr>
                <?php
                    }
                } else {
                    echo '<tr>';
                    echo '<td><h5> No data found. </h5></td>';
                    echo '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>
<?php }

function myBookingSettingFunction()
{ ?>
    <div class="container" data-ng-app="myApp" data-ng-controller="myCtrl">
        <div class="pts-wrapper">
            <table class="booking-setting-table">
                <tr>
                    <th>Title</th>
                    <th>Description</th>
                </tr>
                <tr data-ng-repeat="customer in people | filter: table">
                    <td>Shortcode for User</td>
                    <td>[paradise-date-picker-user]</td>
                </tr>
                <tr data-ng-repeat="customer in people | filter: table">
                    <td>Shortcode for Driver</td>
                    <td>[paradise-date-picker-driver]</td>
                </tr>
            </table>
        </div>
    </div>
<?php
}

function myAddingBookingFunction()
{
    if (isset($_POST['admin_booking_button'])) {

        global $wpdb;
        $table = $wpdb->prefix . 'car_booking';
        $user_email = sanitize_email($_POST['user_email']);
        $date_range = sanitize_text_field($_POST['admin_daterange']);
        $source = sanitize_text_field($_POST['admin_location_from']);
        $destination = sanitize_text_field($_POST['admin_location_to']);
        $no_of_travellers = $_POST['admin_no_of_travellers'];

        if ($user_email == '' || $date_range == '' || $source == '' || $destination == '' || $no_of_travellers == '') {
            echo "Fields cannot be empty";
            return;
        }
        // var_dump($date_range);
        $date_range_explode = explode('-', $date_range);
        $starting_date_create = date_create($date_range_explode[0]);
        $ending_date_create = date_create($date_range_explode[1]);

        $starting_date = date_format($starting_date_create, "Y-m-d H:i:s");
        $ending_date = date_format($ending_date_create, "Y-m-d H:i:s");

        $customer_id = '';
        $user = get_user_by('email', $user_email); // checking if email address already exist
        if (!$user) {
            $random_password = wp_generate_password($length = 12, $include_standard_special_chars = false);
            $user_id = wp_create_user($user_email, $random_password, $user_email); // creating new user
            if (is_wp_error($user_id)) {
                // examine the error message
                echo ("Error: " . $user_id->get_error_message());
                exit;
            } else {
                $customer_id = $user_id;
            }
        } else {
            $customer_id = $user->ID;
        }

        $date_range = array();
        // $count_of_drivers = count($users_driver_ids);

        // date rand implode gareko random id ko lagi block garna lai
        $starting_date_without_time = date("Y-m-d", strtotime($starting_date));
        $endiing_date_without_time = date("Y-m-d", strtotime($ending_date));

        $current_date = strtotime($starting_date_without_time);
        $end_timestamp = strtotime($endiing_date_without_time);

        $available_drivers = paradise_get_avilable_driver($starting_date_without_time, $endiing_date_without_time); // used for getting available drivers
        if (($key = array_search($customer_id, $available_drivers)) !== false) {

            unset($available_drivers[$key]); // removing current user from available drivers
        }
        if (!$available_drivers) {
            echo 'Drivers are currently unavailable. Please try again later.';
            return;
            // wp_send_json_error($error);
            // wp_die();
        }

        $random_key = array_rand($available_drivers);
        $random_id = $available_drivers[$random_key];

        // random id ko block date haru get gareko

        $bookings = $wpdb->get_results("SELECT `blocked_date` FROM $table WHERE `status` = 'booking' AND `user_id` = $random_id");

        while ($current_date <= $end_timestamp) {
            $date_range[] = date('Y-m-d', $current_date);
            $current_date = strtotime('+1 day', $current_date);
        }
        $concat_ids = implode(', ', $date_range);

        // check random id table ma xa ki xaina vanera
        // added status and chaged id to user_id 
        $sql = $wpdb->prepare("SELECT * FROM $table WHERE user_id = %d and status= %s", $random_id, 'block');
        $result = $wpdb->get_results($sql);

        if (empty($bookings[0]->{'blocked_date'})) {

            // random user lai assign garera date booking garxa
            $assigned = array('user_id' => $customer_id, 'date_from' => $starting_date, 'date_to' => $ending_date, 'assigned_user' => $random_id, 'source' => $source, 'destination' => $destination, 'no_of_travellers' => $no_of_travellers, 'status' => 'booking');
            $assigned_format = array('%d', '%s', '%s', '%d', '%s', '%s', '%d', '%s');
            $wpdb->insert($table, $assigned, $assigned_format);
            // if user alerady database ma xa vane random id ko block date update garxa natra insert garxa
            if ($result) {
                $add_block_id = $wpdb->get_results("UPDATE $table SET `blocked_date` = CONCAT(blocked_date, ', $concat_ids') WHERE `user_id` = $random_id");
            } else {
                $data = array('user_id' => $random_id, 'blocked_date' => $concat_ids, 'status' => 'block');
                $format = array('%d', '%s', '%s');
                $wpdb->insert($table, $data, $format);
            }
        }
        // Redirect or show a success message

    }
?>
    <div class="pts_admin_wrapper">
        <h2>Add Booking</h2>
        <form id="admin_user_date_destination" method="post">
            <div class="booking_details">
                <div class="booking-msg"></div>
                <div class="form-group">
                    <label for="admin_daterange">User Email</label>
                    <!-- <span class="dashicons dashicons-email"></span> -->
                    <input type="email" name="user_email" class="admin_booking_email" placeholder="example@gmail.com" value="" />
                </div>
                <div class="form-group">
                    <label for="admin_daterange">Date:</label>
                    <!-- <span class="dashicons dashicons-calendar"></span> -->
                    <input type="text" name="admin_daterange" class="admin_booking_date_range" value="" />
                </div>
                <div class="form-group">
                    <!-- <span class="dashicons dashicons-location"></span> -->
                    <label for="location-from">Source</label>
                    <input type="text" name="admin_location_from" class="admin_booking_source" placeholder="From" />
                </div>
                <div class="form-group">
                    <!-- <span class="dashicons dashicons-location"></span> -->
                    <label for="location-to">Destination</label>
                    <input type="text" name="admin_location_to" class="admin_booking_destination" placeholder="To" />
                </div>
                <div class="form-group">
                    <!-- <span class="dashicons dashicons-businessman"></span> -->
                    <label for="number_of_travellers">Number of Travellers</label>
                    <!-- <input type="number" name="admin_number_of_travellers" class="admin_booking_no_of_travellers" placeholder="Number of people">?\?\?\\\ -->
                    <input type="number" name="admin_no_of_travellers" class="admin_booking_no_of_travellers" value="" />
                </div>
            </div>
            <div class="form-group">
                <input type="submit" class="components-button is-primary" name="admin_booking_button" value="Submit">
            </div>

        </form>
    </div>

    <?php
}


function add_car_trip_tab_to_dashboard_menus($dashboard_menus)
{
    if (is_user_logged_in()) {
        // Get the current user's data
        $current_user = wp_get_current_user();
        if (in_array('driver', (array) $current_user->roles)) {
            $dashboard_menus['car-trip'] = array(
                'menu_title'      => __('Car Trip', 'driver-registration'),
                'menu_class'      => 'lrf-car-trip',
                'menu_content_cb' => 'display_car_trip_tab_content',
                'priority'        => 50,
            );
            $dashboard_menus['car-trip-doc'] = array(
                'menu_title'      => __('Car Trip Documents', 'driver-registration'),
                'menu_class'      => 'lrf-car-trip-doc',
                'menu_content_cb' => 'display_car_trip_doc_tab_content',
                'priority'        => 50,
            );
        }
    }
    return $dashboard_menus;
}
add_filter('wp_travel_engine_user_dashboard_menus', 'add_car_trip_tab_to_dashboard_menus');

function display_car_trip_tab_content()
{
    echo '<h2>' . __('Car Trips', 'driver-registration') . '</h2>';

    if (is_user_logged_in()) {
        global $wpdb;
        $current_user_id = get_current_user_id();
        $table = $wpdb->prefix . 'car_booking';
        $user_upcoming_rides = $wpdb->get_results("SELECT * FROM $table WHERE `status` = 'booking' AND `user_id` = $current_user_id AND DATE(date_to)>NOW() ORDER BY id desc"); ?>
        <!-- HTML code here -->
        <div class="customer-dashboard-front">
            <div class="table-wrapper paradise-customer-table">
                <h4 class="customer-upcoming-rides">Upcoming Journey</h4>
                <table class="paradise-table" id="pd-cust-table">
                    <thead>
                        <tr>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th> No of Travellers </th>
                            <!-- <th>Contact</th> -->
                            <th>Driver Details</th>
                        </tr>
                    </thead>
                    <tbody>

                        <?php
                        if ($user_upcoming_rides) {
                            foreach ($user_upcoming_rides as $user_upcoming_ride) { ?>
                                <tr>
                                    <td><?php echo date("Y-m-d", strtotime($user_upcoming_ride->date_from)); ?></td>
                                    <td><?php echo date("Y-m-d", strtotime($user_upcoming_ride->date_to)); ?></td>
                                    <td><?php echo $user_upcoming_ride->source; ?></td>
                                    <td><?php echo $user_upcoming_ride->destination; ?> </td>
                                    <td> <?php echo $user_upcoming_ride->no_of_travellers; ?> </td>

                                    <td>
                                        <?php
                                        $user_id = $user_upcoming_ride->assigned_user;
                                        $user_details = get_userdata($user_id);
                                        // $user_image = get_avatar_url($user_id);
                                        $phone_number = get_user_meta($user_id, 'phone_number', true);

                                        $profile_image_id = get_user_meta($user_id, 'profile_image_id', true);
                                        $profile_image_src = wp_get_attachment_url($profile_image_id);
                                        ?>

                                        <img src="<?php echo $profile_image_src ? $profile_image_src : '' ?>" alt="driver" />
                                        <div class="driver_details">
                                            <span><?php echo $user_details->display_name; ?> </span><span>
                                                <?php echo $phone_number ?></span>
                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } else {
                            echo '<tr>';
                            echo '<td> <h5>No data found. </h5></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <?php $user_completed_rides = $wpdb->get_results("SELECT * FROM $table WHERE `status` = 'booking' AND `user_id` = $current_user_id AND DATE(date_to)<NOW() ORDER BY id desc"); ?>
            <div class="table-wrapper paradise-customer-table">
                <h4 class="customer-completed-rides">Completed Journey</h4>
                <table class="paradise-table" id="pd-cust-table">
                    <thead>
                        <tr>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th> No of Travellers </th>
                            <!-- <th>Contact</th> -->
                            <th>Driver Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($user_completed_rides) {
                            foreach ($user_completed_rides as $user_completed_ride) { ?>
                                <tr>
                                    <td><?php echo date("Y-m-d", strtotime($user_completed_ride->date_from)); ?></td>
                                    <td><?php echo date("Y-m-d", strtotime($user_completed_ride->date_to)); ?></td>
                                    <td><?php echo $user_completed_ride->source; ?></td>
                                    <td><?php echo $user_completed_ride->destination; ?> </td>
                                    <td> <?php echo $user_completed_ride->no_of_travellers; ?> </td>

                                    <td>
                                        <?php
                                        $user_id = $user_completed_ride->assigned_user;
                                        $user_details = get_userdata($user_id);
                                        // $user_image = get_avatar_url($user_id);
                                        $phone_number = get_user_meta($user_id, 'phone_number', true);

                                        $profile_image_id = get_user_meta($user_id, 'profile_image_id', true);
                                        $profile_image_src = wp_get_attachment_url($profile_image_id);
                                        ?>

                                        <img src="<?php echo $profile_image_src ? $profile_image_src : '' ?>" alt="driver" />
                                        <div class="driver_details">
                                            <span><?php echo $user_details->display_name; ?> </span><span>
                                                <?php echo $phone_number ?></span>
                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } else {
                            echo '<tr>';
                            echo '<td><h5>No data found. </h5></td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
}
function display_car_trip_doc_tab_content()
{
    echo '<h2>' . __('Car Trips Docs', 'driver-registration') . '</h2>';
    ?>



    <?php

    if (is_user_logged_in()) {
        $user_id = get_current_user_id();
        if (isset($_POST['update'])) {
            if (!empty($_FILES['update_driver_img']['name'])) {
                update_image($_FILES['update_driver_img'], 'profile_image_id', $user_id);
            }
            if (!empty($_FILES['update_car_img']['name'])) {
                update_image($_FILES['update_car_img'], 'car_image_id', $user_id);
            }
            if (!empty($_FILES['update_lisence_img']['name'])) {
                update_image($_FILES['update_lisence_img'], 'liscence', $user_id);
            }
            if (!empty($_FILES['update_video']['name'])) {
                update_image($_FILES['update_video'], 'ps_video_id', $user_id);
            }
            if (!empty($_FILES['update_doc1']['name'])) {
                update_image($_FILES['update_doc1'], 'other_doc1_id', $user_id);
            }
            if (!empty($_FILES['update_doc2']['name'])) {
                update_image($_FILES['update_doc2'], 'other_doc2_id', $user_id);
            }
        }

        $driver_image_id = get_user_meta($user_id, "profile_image_id", true);
        $car_image_id = get_user_meta($user_id, "car_image_id", true);
        $ps_video_id = get_user_meta($user_id, "ps_video_id", true);
        $liscence_id = get_user_meta($user_id, "liscence", true);
        $other_doc1_id = get_user_meta($user_id, "other_doc1_id", true);
        $other_doc2_id = get_user_meta($user_id, "other_doc2_id", true);

        $driver_image_url = get_attachment_url($driver_image_id);
        $car_image_url = get_attachment_url($car_image_id);
        $video_url = get_attachment_url($ps_video_id);
        $liscence_url = get_attachment_url($liscence_id);
        $other_doc1_url = get_attachment_url($other_doc1_id);
        $other_doc2_url = get_attachment_url($other_doc2_id);

        if (isset($_POST['upload'])) {
            // Process the image URL (e.g., save it as a post meta, display a message, etc.)
            update_user_meta($user_id, 'profile_image_id', $driver_image_id);
            // update_user_meta($user_id, 'car_image_id', $car_image_id);
            // update_user_meta($user_id, 'ps_video_id', $ps_video_id);
            // update_user_meta($user_id, 'liscence', $liscence_id);
            // update_user_meta($user_id, 'other_doc1_id', $other_doc1_id);
            // update_user_meta($user_id, 'other_doc2_id', $other_doc2_id);
        }
    }

    ?>
    <form method="post" enctype="multipart/form-data">

        <div class="row car-trip-doc">
            <div class="col-1 car-trip-col">
                <h5>Driver Image</h5>
            </div>
            <div class="col-2 car-trip-col">
                <div class="car-trip-image">
                    <img src="<?php echo $driver_image_url; ?>" alt="driver-image">
                </div>
            </div>
            <div class="col-3 car-trip-col">
                <!-- <button id="update_driver_image">Upload</button> -->
                <input type="file" name="update_driver_img" id="update_driver_img">
            </div>
        </div>
        <div class="row car-trip-doc">
            <div class="col-1 car-trip-col">
                <h5>Car Image</h5>
            </div>
            <div class="col-2 car-trip-col">
                <div class="car-trip-image">
                    <img src="<?php echo $car_image_url; ?>" alt="car-image">
                </div>
            </div>
            <div class="col-3 car-trip-col">
                <input type="file" name="update_car_img" id="update_car_img">
            </div>
        </div>
        <div class="row car-trip-doc">
            <div class="col-1 car-trip-col">
                <h5>Lisence</h5>
            </div>
            <div class="col-2 car-trip-col">
                <div class="car-trip-image">
                    <img src="<?php echo $liscence_url; ?>" alt="liscence-image">
                </div>
            </div>
            <div class="col-3 car-trip-col">
                <input type="file" name="update_lisence_img" id="update_lisence_img">
            </div>
        </div>
        <div class="row car-trip-doc">
            <div class="col-1 car-trip-col">
                <h5>Video</h5>
            </div>
            <div class="col-2 car-trip-col">
                <div class="car-trip-image">
                    <video id="my-video" class="video-js" controls preload="auto" <source src="<?php echo $video_url; ?>" type='video/mp4'></video>
                    <!-- <img src="<?php //echo $video_url; ?>" alt="video"> -->
                </div>
            </div>
            <div class="col-3 car-trip-col">
                <input type="file" name="update_video" id="update_video" accept="video/mp4,video/x-m4v,video/*">
            </div>
        </div>
        <div class="row car-trip-doc">
            <div class="col-1 car-trip-col">
                <h5>Document1</h5>
            </div>
            <div class="col-2 car-trip-col">
                <div class="car-trip-image">
                    <img src="<?php echo $other_doc1_url; ?>" alt="doc1-image">
                </div>
            </div>
            <div class="col-3 car-trip-col">
                <input type="file" name="update_doc1" id="update_doc1" accept=".pdf,.doc,.docx,image/*,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            </div>
        </div>
        <div class="row car-trip-doc">
            <div class="col-1 car-trip-col">
                <h5>Document2</h5>
            </div>
            <div class="col-2 car-trip-col">
                <div class="car-trip-image">
                    <img src="<?php echo $other_doc2_url; ?>" alt="doc2-image">
                </div>
            </div>
            <div class="col-3 car-trip-col">
                <input type="file" name="update_doc2" id="update_doc2" accept=".pdf,.doc,.docx,image/*,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
            </div>
        </div>

        <input type="submit" value="update" name="update">
    </form>

<?php

}
function get_attachment_url($attachment_id)
{
    $image_url = wp_get_attachment_url($attachment_id,true);
    return $image_url;
}


function update_image($file, $meta_key, $user_id)
{
    if (!empty($file['name'])) {
        $uploaded_file = $file;

        // Check for upload errors
        if ($uploaded_file['error'] != 0) {
            wp_die('There was an error uploading the image.');
        }

        // Use the WordPress function to handle file uploads
        $upload = wp_handle_upload($uploaded_file, array('test_form' => false));
        if ($upload && !isset($upload['error'])) {


            // Get the path to the upload directory.
            $wp_upload_dir = wp_upload_dir();

            // Prepare an array of post data for the attachment.
            $attachment = array(
                'guid'           => $wp_upload_dir['url'] . '/' . basename($upload['file']),
                'post_mime_type' => $upload['type'],
                'post_title'     => preg_replace('/\.[^.]+$/', '', basename($upload['file'])),
                'post_content'   => '',
                'post_status'    => 'inherit'
            );

            // Insert the attachment.
            $attach_id = wp_insert_attachment($attachment, $upload['file']);


            if ($attach_id) {
                update_user_meta($user_id, $meta_key, $attach_id);
            } else {
                wp_die('Failed to create attachment.');
            }
        } else {
            wp_die($upload['error']);
        }
    } else {
        wp_die('No file was uploaded.');
    }
}
