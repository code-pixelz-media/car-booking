<?php



/**
 * The function `paradice_booking_load_scripts` enqueues various scripts and styles for a booking
 * plugin in WordPress.
 */
function paradice_booking_load_scripts()
{
    wp_enqueue_media();
    wp_enqueue_script( 'fullcalender', 'https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js', '', rand(), true );
    wp_enqueue_script('paradise-jquery', "https://cdn.jsdelivr.net/jquery/latest/jquery.min.js", array(), rand());
    wp_enqueue_script('paradise-moment', "https://cdn.jsdelivr.net/momentjs/latest/moment.min.js", array(), rand());
    wp_enqueue_script('paradise-datepicker-js', "https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js", array(), rand());
    wp_enqueue_script('paradise-jquery-ui-js', "https://code.jquery.com/ui/1.9.2/jquery-ui.js", array(), rand());
    wp_enqueue_script('paradise-multi-datepicker-js', "https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js", array(), rand());
    wp_enqueue_script('paradise-main-js', plugin_dir_url(__FILE__) . 'public/js/main.js', array(), rand());
    wp_enqueue_style('paradise-datepicker-css', "https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css", false, rand());
    wp_enqueue_style('paradise-style-css', plugin_dir_url(__FILE__) . 'public/css/style.css', false, rand());

    wp_enqueue_style('paradise-admin-style', plugin_dir_url(__FILE__) . 'admin/css/style.css', false, rand());
    wp_enqueue_script('paradise-admin-js', plugin_dir_url(__FILE__) . 'admin/js/script.js', array(), rand());

    wp_localize_script('paradise-main-js', 'myajax', array('ajaxurl' => admin_url('admin-ajax.php')));

    // wp_enqueue_media();
}
add_action('admin_enqueue_scripts', 'paradice_booking_load_scripts');
add_action('wp_enqueue_scripts', 'paradice_booking_load_scripts');



function enqueue_font_awesome()
{
    // Enqueue Font Awesome CSS from a CDN
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'enqueue_font_awesome');

require plugin_dir_path(__FILE__) . '/setup_codes.php';
/* Use shortcode [paradise-date-picker-user] */
add_shortcode('paradise-date-picker-user', 'paradise_date_picker_shortcode_user');
function paradise_date_picker_shortcode_user()
{
    ob_start();
    if (is_user_logged_in()) {
        global $wpdb;
        $current_user_id = get_current_user_id();
        $table = $wpdb->prefix . 'car_booking';
        $avatar_image = plugin_dir_url(__FILE__) . '/assets/images/avatar.jpeg';
        $user_upcoming_rides = $wpdb->get_results("SELECT * FROM $table WHERE `status` = 'booking' AND `user_id` = $current_user_id AND DATE(date_to)>NOW() ORDER BY id desc"); ?>
        <!-- HTML code here -->
        <div class="customer-dashboard-front">
            <div class="paradise-msg"></div>
            <div class="booking-success">Booked Successfully !</div>
            <form id="user_date_destination">
                <div class="booking_details">
                    <div class="form-group">
                        <span class="dashicons dashicons-calendar"></span>
                        <label for="daterange">Date</label>
                        <input type="text" name="daterange" class="booking_date_range" value="" />
                    </div>
                    <div class="form-group">
                        <span class="dashicons dashicons-location"></span>
                        <label for="location-from">Source</label>
                        <input type="text" name="location-from" class="booking_source" placeholder="From">
                    </div>
                    <div class="form-group">
                        <span class="dashicons dashicons-location"></span>
                        <label for="location-to">Destination</label>
                        <input type="text" name="location-to" class="booking_destination" placeholder="To">
                    </div>
                    <div class="form-group">
                        <span class="dashicons dashicons-businessman"></span>
                        <label for="number_of_travellers">Number of Travellers</label>
                        <input type="number" name="number_of_travellers" class="booking_no_of_travellers" placeholder="Number of people">
                    </div>
                </div>

                <div class="form-group drivers-details">
                    <input type="hidden" class="driver_id">
                    <img src="#" class="driver_vehicle_image " alt="vehicle">
                    <img src="#" class="driver_profile_image" alt='driver'>
                    <div class="paradise_driver_details">
                        <span class="driver_name"> </span>
                        <span class="driver_contact"></span>
                    </div>

                </div>
                <div class="form-group">
                    <input type="button" class="booking_next_button" value="Next">
                </div>

                <div class="form-group" id="user-side-submit-btn">
                    <input type="button" class="booking_button" value="Submit">
                </div>
            </form>
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

                                        <img src="<?php echo $profile_image_src ? $profile_image_src : $avatar_image; ?>" alt="driver" />
                                        <div class="driver_details">
                                            <span><?php echo $user_details->display_name; ?> </span>
                                            <span><?php echo $phone_number ?></span>
                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } else {
                            echo '<tr>';
                            echo '<td> No data found. </td>';
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

                                        <img src="<?php echo $profile_image_src ? $profile_image_src : $avatar_image; ?>" alt="driver" />
                                        <div class="driver_details">
                                            <span><?php echo $user_details->display_name; ?> </span><span>
                                                <?php echo $phone_number ?></span>
                                        </div>
                                    </td>
                                </tr>
                        <?php }
                        } else {
                            echo '<tr>';
                            echo '<td>No data found. </td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php
    }
    $output = ob_get_contents();
    ob_get_clean();
    return $output;
}

/* Use shortcode [paradise-date-picker-driver] */
add_shortcode('paradise-date-picker-driver', 'paradise_date_picker_shortcode_driver');
function paradise_date_picker_shortcode_driver()
{
    if (is_user_logged_in()) {
        $current_user_id = get_current_user_id();
        $user = get_userdata($current_user_id);
        $avatar_image = plugin_dir_url(__FILE__) . '/assets/images/avatar.jpeg';
        // Get all the user roles as an array.
        $user_roles = $user->roles;

        if (!in_array('driver', $user_roles) && !in_array('administrator', $user_roles)) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            get_template_part(404); // This line displays the 404 template.
            exit();
        }


        ob_start();
        global $wpdb;
        $table = $wpdb->prefix . 'car_booking';



        if (isset($_POST['block'])) {
            $data_of_blocked_date_current_users = get_block_date_of_specific_user();
            if (!$data_of_blocked_date_current_users[0]->{'blocked_date'}) {
                $block_date = $_POST['multi_data'];
                var_dump($block_date);
                $data = array('user_id' => $current_user_id, 'blocked_date' => $block_date, 'status' => 'block');
                $format = array('%d', '%s', '%s');
                $wpdb->insert($table, $data, $format);
            } else {
                $block_date = $_POST['multi_data'];
                $wpdb->query($wpdb->prepare("UPDATE $table SET blocked_date='$block_date' WHERE user_id =%d AND status=%s", $current_user_id, 'block'));
            }
        }
        $current_user_block_dates = array();
        $data_of_blocked_date_current_users = get_block_date_of_specific_user();
        foreach ($data_of_blocked_date_current_users as $obj) {
            $explode_dates = explode(", ", $obj->blocked_date);
            foreach ($explode_dates as $explode_date) {
                if (!in_array($explode_date, $current_user_block_dates)) {
                    $current_user_block_dates[] = $explode_date;
                }
            }
        }
        $implode_dates = '';
        if ($current_user_block_dates) {
            $implode_dates = "'" . implode("','", $current_user_block_dates) . "'";
        }


    ?>
        <div class="driver-dashboard-front">
            <div class="book-date-driver d-flex justify-space-around">
                <form method="post" class="paradise-front-driver">
                    <span>Select date to block date</span>
                    <input id="datePick" type="text" name="multi_data" data-blocked-date="<?php echo $implode_dates; ?>" />
                    <input type="submit" value="Block Date" name="block">
                </form>
                <?php //if ($implode_dates) {
                ?>
                    <!-- <div id='ui-datepicker' class='ui-datepicker-calendar'></div> -->
                <?php //} ?>
            </div>
            <div class="table-wrapper paradise-driver-table">
                <?php
                $driver_upcoming_rides = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE assigned_user=%d AND status=%s AND DATE(date_to)>NOW() ORDER BY id desc", $current_user_id, 'booking'));
                ?>
                <h4 class="driver-upcoming-ride">Upcoming Rides </h4>
                <table class="paradise-table" id="pd-driver-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Source</th>
                            <th>Destination</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($driver_upcoming_rides) {
                            foreach ($driver_upcoming_rides as $driver_upcoming_ride) {
                        ?>
                                <tr>

                                    <td>
                                        <?php
                                        $user_id = $driver_upcoming_ride->user_id;
                                        $user_details = get_userdata($user_id);
                                        // var_dump($user_details);
                                        // $user_image = get_avatar_url($user_id);
                                        $phone_number = get_user_meta($user_id, 'phone_number', true);
                                        $profile_image_id = get_user_meta($user_id, 'profile_image_id', true);
                                        $profile_image_src = wp_get_attachment_url($profile_image_id);
                                        ?>

                                        <img src="<?php echo $profile_image_src ? $profile_image_src : $avatar_image; ?>" alt="driver" />
                                        <div class="driver_details">
                                            <span><?php echo $user_details->display_name; ?> </span><span>
                                                <?php echo $phone_number ?></span>
                                        </div>
                                    </td>
                                    <td><?php echo $driver_upcoming_ride->date_from; ?></td>
                                    <td><?php echo $driver_upcoming_ride->date_to; ?></td>
                                    <td><?php echo $driver_upcoming_ride->source; ?></td>
                                    <td><?php echo $driver_upcoming_ride->destination; ?></td>

                                </tr>

                        <?php
                            }
                        } else {
                            echo '<tr>';
                            echo '<td>  No data found. </td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="table-wrapper paradise-driver-table">
                <h4 class="driver-completed-ride">Completed Rides </h4>
                <?php
                $driver_completed_rides = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table WHERE assigned_user=%d AND status=%s AND DATE(date_to)<NOW() ORDER BY id desc", $current_user_id, 'booking'));
                ?>
                <table class="paradise-table" id="pd-driver-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Source</th>
                            <th>Destination</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($driver_completed_rides) {
                            foreach ($driver_completed_rides as $driver_completed_ride) {
                        ?>
                                <tr>

                                    <td>
                                        <?php
                                        $user_id = $driver_completed_ride->user_id;
                                        $user_details = get_userdata($user_id);
                                        // var_dump($user_details);
                                        // $user_image = get_avatar_url($user_id);
                                        $phone_number = get_user_meta($user_id, 'phone_number', true);
                                        $profile_image_id = get_user_meta($user_id, 'profile_image_id', true);
                                        $profile_image_src = wp_get_attachment_url($profile_image_id);
                                        ?>

                                        <img src="<?php echo $profile_image_src ? $profile_image_src : $avatar_image; ?>" alt="driver" />
                                        <span><?php echo $user_details->display_name; ?> </span><span>
                                            <?php echo $phone_number ?></span>
                                    </td>
                                    <td><?php echo $driver_completed_ride->date_from; ?></td>
                                    <td><?php echo $driver_completed_ride->date_to; ?></td>
                                    <td><?php echo $driver_completed_ride->source; ?></td>
                                    <td><?php echo $driver_completed_ride->destination; ?></td>

                                </tr>

                        <?php
                            }
                        } else {
                            echo '<tr>';
                            echo '<td> No data found. </td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>

        <?php
    }
    $output = ob_get_contents();
    ob_get_clean();
    return $output;
}

function get_block_date_of_specific_user()
{
    global $wpdb;
    $current_user_id = get_current_user_id();
    $table = $wpdb->prefix . 'car_booking';
    $data = $wpdb->get_results("SELECT blocked_date FROM $table WHERE `user_id` = $current_user_id AND `status`='block' ORDER BY id DESC LIMIT 1");
    return $data;
}

/**
 * function paradise_random_driver is used for getting driver after user submit booking details
 */
add_action("wp_ajax_paradise_random_driver", "paradise_random_driver");
// add_action("wp_ajax_nopriv_book_date_range_for_car_booking", "paradise_random_driver");

function paradise_random_driver()
{

    $current_user_id = get_current_user_id();
    $starting_date = $_POST['start_date'];
    $ending_date = $_POST['end_date'];
    $date_range = array();

    $starting_date_without_time = date("Y-m-d", strtotime($starting_date));
    $endiing_date_without_time = date("Y-m-d", strtotime($ending_date));
    $available_drivers = paradise_get_avilable_driver($starting_date_without_time, $endiing_date_without_time); // used for getting available drivers
    if (($key = array_search($current_user_id, $available_drivers)) !== false) {

        unset($available_drivers[$key]); // removing current user from available drivers
    }
    if (!$available_drivers) {
        $error = 'Drivers are currently unavailable. Please try again later.';
        wp_send_json_error($error);
        wp_die();
    }

    $random_key = array_rand($available_drivers);
    $random_id = $available_drivers[$random_key];

    $driver_details = get_userdata($random_id);
    $driver_name =  $driver_details->display_name;
    $driver_phone = get_user_meta($random_id, 'phone_number', true);

    $driver_image = get_user_meta($random_id, 'profile_image_id', true);
    $driver_image_src = wp_get_attachment_url($driver_image);

    $vehicle_image = get_user_meta($random_id, 'car_image_id', true);
    $vehicle_image_src = wp_get_attachment_url($vehicle_image);

    $return = array(
        'ID'      => $random_id,
        'name' => $driver_name,
        'phone' => $driver_phone,
        'driver_image' => $driver_image_src,
        'vehicle_image' => $vehicle_image_src,
    );
    wp_send_json_success($return);
    wp_die();
    // var_dump($random_id);
}


add_action("wp_ajax_book_date_range_for_car_booking", "book_date_range_for_car_booking");
add_action("wp_ajax_nopriv_book_date_range_for_car_booking", "book_date_range_for_car_booking");

function book_date_range_for_car_booking()
{
    global $wpdb;
    $table = $wpdb->prefix . 'car_booking';
    $current_user_id = get_current_user_id();
    $source = $_POST['source'];
    $destination = $_POST['destination'];
    $no_of_travellers = $_POST['no_of_travellers'];
    $starting_date = $_POST['start_date'];
    $ending_date = $_POST['end_date'];
    $random_id = $_POST['driver_id'];
    $date_range = array();
    // $count_of_drivers = count($users_driver_ids);

    // date rand implode gareko random id ko lagi block garna lai
    $starting_date_without_time = date("Y-m-d", strtotime($starting_date));
    $endiing_date_without_time = date("Y-m-d", strtotime($ending_date));

    // date rand implode gareko random id ko lagi block garna lai
    $starting_date_without_time = date("Y-m-d", strtotime($starting_date));
    $endiing_date_without_time = date("Y-m-d", strtotime($ending_date));

    // $available_drivers = paradise_get_avilable_driver($starting_date_without_time, $endiing_date_without_time);
    // if (($key = array_search($current_user_id, $available_drivers)) !== false) {

    //     unset($available_drivers[$key]); // removing current user from available drivers
    // }
    // if (!$available_drivers) {
    //     echo 'Drivers are currently unavailable. Please try again later.';
    //     wp_die();
    // }

    $current_date = strtotime($starting_date_without_time);
    $end_timestamp = strtotime($endiing_date_without_time);

    // $random_key = array_rand($available_drivers);
    // $random_id = $available_drivers[$random_key];

    $table = $wpdb->prefix . 'car_booking';

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
        $assigned = array('user_id' => $current_user_id, 'date_from' => $starting_date, 'date_to' => $ending_date, 'assigned_user' => $random_id, 'source' => $source, 'destination' => $destination, 'no_of_travellers' => $no_of_travellers, 'status' => 'booking');
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

        $customer_info = get_userdata($current_user_id);
        $driver_info = get_userdata($random_id);

        $customer_email = $customer_info->user_email;
        $driver_email = $driver_info->user_email;
        $admin_email = get_option('admin_email');
        // // // for sending mail
        $subject = 'Confirmation of Booked Trip';
        $body = 'We are thrilled to confirm that your trip from ' . $source . ' to ' . $destination . ' has been successfully booked! Your journey is scheduled to begin on ' . $starting_date . ' and conclude on ' . $ending_date . '.';
        $headers = array('Content-Type: text/html; charset=UTF-8');

        wp_mail($customer_email, $subject, $body, $headers); // for customer 

        wp_mail($driver_email, $subject, $body, $headers); // for driver

        $admin_subject = 'New Booked Trip';
        $admin_body = 'The new trip has been booked from ' . $source . ' to ' . $destination . ' and is scheduled to run from ' . $starting_date . ' to ' . $ending_date . '';

        wp_mail($admin_email, $admin_subject, $admin_body, $headers); // for admin
    } else {
        echo 'block date haru xa';
    }
    wp_die();
}

// book garda chai block garne ali kati mileko xaina, driver available xa ki xaina vanera check mileko xaina

if (!function_exists('paradise_get_avilable_driver')) {
    function paradise_get_avilable_driver($starting_date, $ending_date)
    {

        // for getting user having role drivers
        $all_users = get_users();
        $get_all_drivers = array();
        foreach ($all_users as $user) {

            if ($user->has_cap('driver')) {
                $get_all_drivers[] = $user;
            }
        }
        // for getting drivers id;
        $driver_ids = [];
        if ($get_all_drivers) {
            foreach ($get_all_drivers as $get_all_driver) {
                $driver_ids[] = $get_all_driver->{'ID'};
            }
        }

        global $wpdb;
        $car_booking = $wpdb->prefix . 'car_booking';

        $start_date = strtotime($starting_date);
        $end_date = strtotime($ending_date);

        // getting data from wp_car_booking table
        $get_blocked_users_data = $wpdb->get_results("SELECT * FROM $car_booking WHERE status='block'");
        $available_user = [];

        foreach ($get_blocked_users_data as $get_blocked_user_data) {
            // getting driver id having blocked date

            $user_id = (int) $get_blocked_user_data->{'user_id'};
            // $user_details = get_userdata($user_id);

            $blocked_dates = $get_blocked_user_data->{'blocked_date'};  // getting blocked date -> in string 
            $explode_blocked_dates = explode(',', $blocked_dates); // converting blocked date into an array;

            $blocked_date_matched = false; // variable for checking if blocked date matched

            // looping through multiple blocekd date of specific user
            foreach ($explode_blocked_dates as $explode_blocked_date) {
                // comparing if blocked date gets between start date and end date
                if (strtotime($explode_blocked_date) >= $start_date && strtotime($explode_blocked_date) <= $end_date) {
                    $blocked_date_matched = true;  // if blocked date get between start and end date then setting it true
                    break;
                }
            }

            if (!$blocked_date_matched) {
                $available_user[] = $user_id;
            }
        }
        $user_id_column = array_column($get_blocked_users_data, 'user_id'); // getting user ids from car_booking table

        // getting drivers id from users table which are not listed in car_booking table
        $available_driver = array_diff($driver_ids, $user_id_column);

        // merging available  drivers form from user table and car_booking table
        $merging_available_users = array_merge($available_driver, $available_user);

        return $merging_available_users;
    }
}

/**
 *  function for adding phone number field to the users register and edit profile page
 */

if (!function_exists('paradise_user_profile_fields')) {
    add_action('show_user_profile', 'paradise_user_profile_fields');
    add_action('edit_user_profile', 'paradise_user_profile_fields');
    add_action('user_new_form', 'paradise_user_profile_fields');

    function paradise_user_profile_fields($user)
    {
        global $pagenow;
        $id = $user->id ?? 0;
        $phone_number = get_user_meta($id, 'phone_number', true) ?? '';
        $user_role = $user->roles ?? 'subscriber';
        $avatar_image = plugin_dir_url(__FILE__) . '/assets/images/avatar.jpeg';
        $lisence_placeholder_image = plugin_dir_url(__FILE__) . '/assets/images/lisence-placeholder.png';
        ?>

            <table class="form-table">
                <tr>
                    <th><label for="phone_number">Phone Number</label></th>
                    <td>
                        <input type="number" name="phone_number" id="phone_number" value="<?php echo $phone_number ? $phone_number : ''; ?>" class="regular-text" placeholder="Enter phone number." />
                    </td>
                </tr>
            </table>
            <?php

            if ($pagenow == 'user-edit.php' || $pagenow == 'profile.php') {
                $profile_image_id = get_user_meta($user->id, 'profile_image_id', true);
                $profile_image_src = wp_get_attachment_url($profile_image_id);
            ?>

                <table class="form-table">
                    <tr>
                        <th><label for="user_profile">Profile Image</label></th>
                        <td>
                            <div class="user_profile_image">

                                <img src="<?php echo $profile_image_src ? $profile_image_src : '' ?>" id="profile-image-src">
                                <a href='#' class='upload_profile_image button button-secondary'><?php _e('Upload Profile Image'); ?></a>

                                <input type='hidden' name='profile_image_id' id='profile_image_id' value='<?php echo $profile_image_id ? $profile_image_id : $avatar_image; ?>' />
                            </div>
                        </td>
                    </tr>
                </table>
            <?php
            }
            if (($pagenow == 'user-edit.php' || $pagenow == 'profile.php') && in_array('driver', $user_role)) {
                $car_image_id = get_user_meta($user->id, 'car_image_id', true);
                $car_image_src = wp_get_attachment_url($car_image_id);
            ?>

                <table class="form-table">
                    <tr>
                        <th><label for="phone_number">Vehicle Image</label></th>
                        <td>
                            <div class="driver_car_image_main">

                                <img src="<?php echo $car_image_src ? $car_image_src : '' ?>" id="driver-car-image-src">
                                <a href='#' class='upload_car_image button button-secondary'><?php _e('Upload Vehicle Image'); ?></a>

                                <input type='hidden' name='car_image_id' id='car_image_id' value='<?php echo $car_image_id ? $car_image_id : ''; ?>' />
                            </div>
                        </td>
                    </tr>
                </table>
            <?php
            }
            if (($pagenow == 'user-edit.php' || $pagenow == 'profile.php') && in_array('driver', $user_role)) {
                $video_id = get_user_meta($user->id, 'ps_video_id', true);
                $video_src = wp_get_attachment_url($video_id);
            ?>
                <table class="form-table">
                    <tr>
                        <th><label for="ps-video">Video</label></th>
                        <td>
                            <div class="driver_car_image_main">
                                <!-- <img src="<?php //echo $video_src ? $video_src : '' 
                                                ?>" id="video-src"> -->
                                <video id="my-video" class="video-js" controls preload="auto" <source src="<?php echo $video_src ? $video_src : '' ?>" type='video/mp4'></video>
                                <a href='#' class='upload_video_image button button-secondary'><?php _e('Upload Video'); ?></a>
                                <input type='hidden' name='ps_video_id' id='ps_video_id' value='<?php echo $video_id ? $video_id : ''; ?>' />
                            </div>
                        </td>
                    </tr>
                </table>
            <?php
            }
            if (($pagenow == 'user-edit.php' || $pagenow == 'profile.php') && in_array('driver', $user_role)) {
                $liscence_image_id = get_user_meta($user->id, 'liscence', true);
                $liscence_image_src = wp_get_attachment_url($liscence_image_id);
            ?>
                <table class="form-table">
                    <tr>
                        <th><label for="liscence">Liscence Image</label></th>
                        <td>
                            <div class="driver_car_image_main">
                                <img src="<?php echo $liscence_image_src ? $liscence_image_src : '' ?>" id="liscence-image-src">
                                <a href='#' class='upload_liscence_image button button-secondary'><?php _e('Upload Liscence Image'); ?></a>

                                <input type='hidden' name='liscence' id='liscence' value='<?php echo $liscence_image_id ? $liscence_image_id : $lisence_placeholder_image; ?>' />
                            </div>
                        </td>
                    </tr>
                </table>
            <?php
            }
            if (($pagenow == 'user-edit.php' || $pagenow == 'profile.php') && in_array('driver', $user_role)) {
                $other_doc1_id = get_user_meta($user->id, 'other_doc1_id', true);
                $other_doc1_src = wp_get_attachment_url($other_doc1_id);
            ?>
                <table class="form-table">
                    <tr>
                        <th><label for="other-document1">Other Document Image</label></th>
                        <td>
                            <div class="driver_car_image_main">
                                <img src="<?php echo $other_doc1_src ? $other_doc1_src : $lisence_placeholder_image ?>" id="other-doc1-src">
                                <a href='#' class='upload_other_doc1_image button button-secondary'><?php _e('Upload Other Document Image'); ?></a>
                                <input type='hidden' name='other_doc1_id' id='other_doc1_id' value='<?php echo $other_doc1_id ? $other_doc1_id : ''; ?>' />
                            </div>
                        </td>
                    </tr>
                </table>
            <?php
            }
            if (($pagenow == 'user-edit.php' || $pagenow == 'profile.php') && in_array('driver', $user_role)) {
                $other_doc2_id = get_user_meta($user->id, 'other_doc2_id', true);
                $other_doc2_src = wp_get_attachment_url($other_doc2_id);
            ?>
                <table class="form-table">
                    <tr>
                        <th><label for="other_doc2">Other Documents Image</label></th>
                        <td>
                            <div class="driver_car_image_main">
                                <img src="<?php echo $other_doc2_src ? $other_doc2_src : $lisence_placeholder_image ?>" id="other-doc2-src">
                                <a href='#' class='upload_other_doc2_image button button-secondary'><?php _e('Upload Other Document Image'); ?></a>
                                <input type='hidden' name='other_doc2_id' id='other_doc2_id' value=' <?php echo $other_doc2_id ? $other_doc2_id : ''; ?>' />
                            </div>
                        </td>
                    </tr>
                </table>
    <?php
            }
        }
    }


    if (!function_exists('paradise_save_user_profile_fields')) {
        add_action('user_register', 'paradise_save_user_profile_fields', 10, 1);
        add_action('personal_options_update', 'paradise_save_user_profile_fields', 10, 1);
        add_action('edit_user_profile_update', 'paradise_save_user_profile_fields', 10, 1);

        function paradise_save_user_profile_fields($user_id)
        {

            if (isset($_POST['phone_number'])) {
                update_user_meta($user_id, 'phone_number', $_POST['phone_number']);
            }
            if (isset($_POST['car_image_id'])) {
                update_user_meta($user_id, 'car_image_id', $_POST['car_image_id']);
            }
            if (isset($_POST['profile_image_id'])) {
                update_user_meta($user_id, 'profile_image_id', $_POST['profile_image_id']);
            }
            if (isset($_POST['liscence'])) {
                update_user_meta($user_id, 'liscence', $_POST['liscence']);
            }
            if (isset($_POST['other_doc1_id'])) {
                update_user_meta($user_id, 'other_doc1_id', $_POST['other_doc1_id']);
            }
            if (isset($_POST['other_doc2_id'])) {
                update_user_meta($user_id, 'other_doc2_id', $_POST['other_doc2_id']);
            }
            if (isset($_POST['ps_video_id'])) {
                update_user_meta($user_id, 'ps_video_id', $_POST['ps_video_id']);
            }
        }
    }


    /**
     * Function used for booking from backend
     */
    // add_action('wp_ajax_paradise_admin_booking', 'paradise_admin_booking');
    function paradise_admin_booking()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'car_booking';
        // $current_user_id = get_current_user_id();
        $user_email = $_POST['user_email'];
        $source = $_POST['source'];
        $destination = $_POST['destination'];
        $no_of_travellers = $_POST['no_of_travellers'];
        $starting_date = $_POST['start_date'];
        $ending_date = $_POST['end_date'];

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
            $error = 'Drivers are currently unavailable. Please try again later.';
            wp_send_json_error($error);
            wp_die();
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
        } else {
            echo 'block date haru xa';
        }
        wp_die();
    }