<?php



/**
 * The function `paradice_booking_load_scripts` enqueues various scripts and styles for a booking
 * plugin in WordPress.
 */
function paradice_booking_load_scripts()
{
    wp_enqueue_script('paradise-jquery', "https://cdn.jsdelivr.net/jquery/latest/jquery.min.js", array(), rand());
    wp_enqueue_script('paradise-moment', "https://cdn.jsdelivr.net/momentjs/latest/moment.min.js", array(), rand());
    wp_enqueue_script('paradise-datepicker-js', "https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js", array(), rand());
    wp_enqueue_script('paradise-jquery-ui-js', "https://code.jquery.com/ui/1.9.2/jquery-ui.js", array(), rand());
    wp_enqueue_script('paradise-multi-datepicker-js', "https://cdn.rawgit.com/dubrox/Multiple-Dates-Picker-for-jQuery-UI/master/jquery-ui.multidatespicker.js", array(), rand());
    wp_enqueue_script('paradise-main-js', plugin_dir_url(__FILE__) . '/assets/js/main.js', array(), rand());
    wp_enqueue_style('paradise-datepicker-css', "https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css", false,   rand());
    wp_enqueue_style('paradise-style-css', plugin_dir_url(__FILE__) . '/assets/css/style.css', false,   rand());

    wp_localize_script('paradise-main-js', 'myajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('admin_enqueue_scripts', 'paradice_booking_load_scripts');
add_action('wp_enqueue_scripts', 'paradice_booking_load_scripts');


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
        $bookings = $wpdb->get_results("SELECT * FROM $table WHERE `status` = 'booking' AND `user_id` = $current_user_id"); ?>
        <!-- HTML code here -->
        <div class="customer-dashboard-front">

            <form id="user_date_destination">
                <div class="form-group">
                    <label for="daterange">Date:</label>
                    <input type="text" name="daterange" class="booking_date_range" value="" />
                </div>
                <div class="form-group">
                    <label for="location-from">Source</label>
                    <input type="text" name="location-from" class="booking_source" placeholder="From">
                </div>
                <div class="form-group">
                    <label for="location-to">Destination</label>
                    <input type="text" name="location-to" class="booking_destination" placeholder="To">
                </div>
                <div class="form-group">
                    <label for="number_of_travellers">Number of Travellers</label>
                    <input type="number" name="number_of_travellers" class="booking_no_of_travellers" placeholder="Number of people">
                </div>
                <div class="form-group">
                    <label for="Phone number">Phone Number</label>
                    <input type="number" name="booking_phone_number" placeholder="Phone Number" class="booking_phone_number"/>

                </div>

                <div class="form-group">
                    <input type="button" class="booking_button" value="Submit">
                </div>
            </form>

            <div class="table-wrapper paradise-customer-table">
                <table class="paradise-table" id="pd-cust-table">
                    <thead>
                        <tr>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th> No of Travellers </th>
                            <th>Contact</th>
                            <th>Driver Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking) { ?>
                            <tr>
                                <td><?php echo date("Y-m-d", strtotime($booking->date_from)); ?></td>
                                <td><?php echo date("Y-m-d", strtotime($booking->date_to)); ?></td>
                                <td><?php echo $booking->source; ?></td>
                                <td><?php echo $booking->destination;?> </td>
                                <td> <?php echo $booking->no_of_travellers; ?> </td>
                                <td><?php echo $booking->phone_number ?> </td>
                                <td>
                                    <?php 
                                    $user_id = $booking->assigned_user;
                                    $user_details = get_userdata($user_id);
                                    $user_image = get_avatar_url($user_id);
                                    ?>

                                    <img src="<?php echo $user_image?$user_image:''?>" alt="driver" />
                                    <span><?php echo $user_details->display_name; ?> </span><span> 123456789</span>
                                </td>
                            </tr>
                        <?php } ?>
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
    ob_start();
    if (is_user_logged_in()) {
        $current_user_id = get_current_user_id();
        if (isset($_POST['block'])) {
            global $wpdb;

            $table = $wpdb->prefix . 'car_booking';
            $block_date = $_POST['multi_data'];
            $data = array('user_id' => $current_user_id, 'blocked_date' => $block_date, 'status' => 'block');
            $format = array('%d', '%s', '%s');
            $wpdb->insert($table, $data, $format);
        }
        $data_of_blocked_date_current_users = get_block_date_of_specific_user();

        var_dump($data_of_blocked_date_current_users);
        $current_user_block_dates = array();
        foreach ($data_of_blocked_date_current_users as $obj) {
            $explode_dates = explode(", ", $obj->blocked_date);
            foreach ($explode_dates as $explode_date) {
                if (!in_array($explode_date, $current_user_block_dates)) {
                    $current_user_block_dates[] = $explode_date;
                }
            }
        }
        $implode_dates = "'" . implode("','", $current_user_block_dates) . "'";
    ?>
        <div class="driver-dashboard-front">

            <form method="post" class="paradise-front-driver">
                <span>Select date to block date</span>
                <input id="datePick" type="text" name="multi_data" data-blocked-date="<?php echo $implode_dates; ?>" />
                <input type="submit" value="Block Date" name="block">
            </form>

            <div class="table-wrapper paradise-driver-table">
                <table class="paradise-table" id="pd-driver-table">
                    <thead>
                        <tr>
                            <th>Customer Name</th>
                            <th>Date start</th>
                            <th>Date End</th>
                            <th>Source</th>
                            <th>Destination</th>
                            <th>Contact</th>
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
                        </tr>
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

function get_block_date_of_specific_user()
{
    global $wpdb;
    $current_user_id = get_current_user_id();
    $table = $wpdb->prefix . 'car_booking';
    $data = $wpdb->get_results("SELECT blocked_date FROM $table WHERE `user_id` = $current_user_id AND `status`>= 'block' ORDER BY id DESC LIMIT 1");
    return $data;
}
// function generate_random_driver()
// {
//     $args = array(
//         'role'    => 'driver',
//         'fields'  => 'ID'
//     );
//     $user_query = new WP_User_Query($args);
//     $users = $user_query->get_results();
//     return $users;
// }


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
    $phone_number = $_POST['phone_number'];
    $starting_date = $_POST['start_date'];
    $ending_date = $_POST['end_date'];
    // $users_driver_ids = generate_random_driver();
    $date_range = array();
    // $count_of_drivers = count($users_driver_ids);

    // date rand implode gareko random id ko lagi block garna lai
    $starting_date_without_time = date("Y-m-d", strtotime($starting_date));
    $endiing_date_without_time = date("Y-m-d", strtotime($ending_date));

    // date rand implode gareko random id ko lagi block garna lai
    $starting_date_without_time = date("Y-m-d", strtotime($starting_date));
    $endiing_date_without_time = date("Y-m-d", strtotime($ending_date));

    $available_drivers = paradise_get_avilable_driver($starting_date_without_time, $endiing_date_without_time);

    if (!$available_drivers) {
        echo 'not any drivers are available';
        wp_die();
    }

    $current_date = strtotime($starting_date_without_time);
    $end_timestamp = strtotime($endiing_date_without_time);

    $random_key = array_rand($available_drivers);
    $random_id = $available_drivers[$random_key];

    $table = $wpdb->prefix . 'car_booking';

    // echo 'random id => ' . $random_id;

    // random id ko block date haru get gareko

    /*** -----------------------------------------NOTE: no of travellers ko column missing xa database ma --------------------------- */

    $bookings = $wpdb->get_results("SELECT `blocked_date` FROM $table WHERE `status` = 'booking' AND `user_id` = $random_id");




    while ($current_date <= $end_timestamp) {
        $date_range[] = date('Y-m-d', $current_date);
        $current_date = strtotime('+1 day', $current_date);
    }
    $concat_ids = implode(', ', $date_range);

    // check random id table ma xa ki xaina vanera
    $sql = $wpdb->prepare("SELECT * FROM $table WHERE id = %d", $random_id);
    $result = $wpdb->get_results($sql);

    if (empty($bookings)) {
        // random user lai assign garera date booking garxa
        $assigned = array('user_id' => $current_user_id, 'date_from' => $starting_date, 'date_to' => $ending_date, 'assigned_user' => $random_id,'source'=>$source, 'destination'=>$destination, 'no_of_travellers'=>$no_of_travellers, 'phone_number'=>$phone_number, 'status' => 'booking');
        $assigned_format = array('%d', '%s', '%s', '%d', '%s', '%s', '%d', '%d', '%s');
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
    die();
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
            $user_id = (int)$get_blocked_user_data->{'user_id'};
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
        // var_dump($merging_available_users);
        return $merging_available_users;
    }
}
