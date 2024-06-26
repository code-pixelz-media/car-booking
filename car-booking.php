<?php


/**
 * Plugin Name: Car Booking
 * Plugin URI: https://codepixelzmedia.com.np/
 * Description: Book your destination. 
 * Version: 1.0.0
 * Author: Chakramani Joshi
 * Text Domain: car-booking
 * Author URI: https://codepixelzmedia.com.np/
 */



if (!defined('ABSPATH')) {

        exit;
};

define('PLUGIN_DIR', plugin_dir_path(__FILE__));

register_activation_hook(__FILE__, 'activate_paradise_plugin');
register_deactivation_hook(__FILE__, 'deactivate_paradise_plugin');



/**
 * The function `activate_paradise_plugin` creates a database table for car booking if it does not
 * already exist.
 */
function activate_paradise_plugin()
{
        global $wpdb;
        $prefix = $wpdb->prefix;
        $form_db = $prefix . "car_booking";
        $charset_collate = $wpdb->get_charset_collate();
        if ($wpdb->get_var("SHOW TABLES LIKE '$form_db'") !== $form_db) {
                $sql = "CREATE TABLE " . $form_db . "(
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                user_id int(15) NOT NULL,
                date_from datetime NULL,
                date_to datetime NULL,
                assigned_user int(9) NULL,
                source varchar(255) NULL,
                destination varchar(255) NULL,
                no_of_travellers int(11) NULL,
                blocked_date varchar(255) NULL,
                status varchar(20) NOT NULL,
                created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY  (id)
                ) $charset_collate;";
                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
                dbDelta($sql);
        }


        $result = add_role(
                'driver',
                __('Driver', 'car-booking'),
                array(
                        'upload_files' => true,
                        'edit_posts' => true, // This capability allows viewing the media library
                )
        );

        if (null !== $result) {
                echo "Success: {$result->name} user role created.";
        } else {
                echo 'Failure: user role already exists.';
        }
}


/**
 * The function "deactivate_paradise_plugin" drops a table named "car_booking" from the WordPress
 * database.
 */
function deactivate_paradise_plugin()
{
        global $wpdb;
        $table_name = $wpdb->prefix . 'car_booking';
        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);
        // remove_role('driver');
}

$init_file = PLUGIN_DIR . 'car-booking-loading.php';

require $init_file;



add_action('test', function () {
?>
        <!-- <style>
                #calendar {
                        margin: 0;
                        padding: 0;
                        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                        max-width: 1100px;
                        margin: 40px auto;
                        font-size: 14px;
                }
        </style>
        <div id='calendar'></div>
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.14/index.global.min.js"></script>
        <script>
                document.addEventListener('DOMContentLoaded', function() {
                        var calendarEl = document.getElementById('calendar');

                        var calendar = new FullCalendar.Calendar(calendarEl, {
                                headerToolbar: {
                                        left: 'prev,next today',
                                        center: 'title',
                                        right: 'dayGridMonth,timeGridWeek'
                                },
                                initialDate: '2024-06-12',
                                events: [{
                                                start: '2024-06-11T10:00:00',
                                                end: '2024-06-11T16:00:00',
                                                display: 'background',
                                                color: '#ff9f89'
                                        },
                                        {
                                                start: '2024-06-13T10:00:00',
                                                end: '2024-06-13T16:00:00',
                                                display: 'background',
                                                color: '#ff9f89'
                                        },
                                        {
                                                start: '2024-06-24',
                                                end: '2024-06-28',
                                                overlap: false,
                                                display: 'background'
                                        },
                                        {
                                                start: '2024-06-06',
                                                end: '2024-06-08',
                                                overlap: false,
                                                display: 'background'
                                        }
                                ]
                        });

                        calendar.render();
                });
        </script> -->
<?php
});
