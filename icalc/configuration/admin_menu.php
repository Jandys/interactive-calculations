<?php


add_action('admin_menu', 'ic_admin_menu');
add_action('admin_init', 'inter_calc_set_cookie');

const configurationSites = array('inter-calc-configuration',
    'ic-products-configuration',
    'ic-services-configuration',
    'ic-menu-statistics');

global $settingCookie;
$settingCookie = false;


/**
 * Sets an authentication cookie for the iCalc plugin in the admin area.
 *
 * This function checks if the current user has the 'manage_options' capability.
 * If true, it creates or updates a transient based on the current session token and user ID.
 * Then, it ensures a valid token is set and not expired. If the token is missing or expired,
 * it requests a new token from the iCalc API and sets it as a cookie.
 *
 * @return void
 * @global bool $settingCookie A flag to prevent multiple token requests during a single request.
 *
 * @since 1.0.0
 *
 */
function inter_calc_set_cookie()
{
    if (current_user_can('manage_options')) {
        $currentSession = wp_get_session_token();
        $transSession = get_transient($currentSession);
        if ($transSession === false || !wp_get_current_user()->ID !== $transSession) {
            set_transient($currentSession, wp_get_current_user()->ID);
        }
    }

    global $settingCookie;
    if (!$settingCookie) {
        $settingCookie = true;
        if (isset($_GET['page']) && in_array($_GET['page'], configurationSites)) {
            if (!isset($_COOKIE['icalc-expiration']) ||
                !isset($_COOKIE['icalc-token']) ||
                $_COOKIE['icalc-expiration'] <= time()) {

                error_log("NOW I WILL ASK FOR JWT TOKEN after admin init");


                $data = array("user" => wp_get_current_user()->ID, "session" => wp_get_session_token());

                $response = wp_remote_post(get_rest_url(null, ICALC_EP_PREFIX . '/token'), array(
                    'method' => 'POST',
                    'timeout' => 45, // Timeout in seconds
                    'redirection' => 5, // Maximum number of redirections
                    'blocking' => true,
                    'headers' => array(
                        'Content-Type' => 'application/json; charset=utf-8',
                    ),
                    'body' => json_encode($data), // Encode the data as JSON
                    'cookies' => array(),
                ));

                if (is_wp_error($response)) {
                    $error_message = $response->get_error_message();
                    $error_m = "Something went wrong: $error_message";
                    error_log($error_m);
                    $settingCookie = false;
                } else {
                    $response_body = wp_remote_retrieve_body($response);
                    $body = json_decode($response_body);
                    $expiration_time = time() + 3300; // Set the cookie to expire in 55 minutes
                    setcookie('icalc-token', $body->token, $expiration_time, '/');
                    setcookie('icalc-expiration', $expiration_time, $expiration_time, '/');
                    $settingCookie = false;
                }
            } else {
                $settingCookie = false;
            }
        } else {
            $settingCookie = false;
        }
    }
}

/**
 * Registers the Inter Calcus plugin menu and submenu pages in the admin area.
 *
 * This function adds the main menu page for the Inter Calcus plugin with the 'Calcus' title
 * and a set of submenu pages for Products, Services, and Statistics.
 *
 * @return void
 * @since 1.0.0
 *
 */
function ic_admin_menu()
{
    add_menu_page(
        __('Calcus'),
        __('Inter Calcus'),
        'manage_options',
        'inter-calc-configuration',
        'inter_calc_main_configuration',
        'dashicons-schedule',
        8);
    add_submenu_page('inter-calc-configuration',
        __('Products - Inter Calcus'),
        __('IC - Products'),
        'manage_options',
        'ic-products-configuration',
        'ic_menu_products_configuration');
    add_submenu_page('inter-calc-configuration',
        __('Services - Inter Calcus'),
        __('IC - Services'),
        'manage_options',
        'ic-services-configuration',
        'ic_menu_services_configuration');
    add_submenu_page('inter-calc-configuration',
        __('Statistics - Inter Calcus'),
        __('IC - Statistics'),
        'manage_options',
        'ic-menu-statistics',
        'ic_menu_statistics');

}

/**
 * Displays the main configuration page for the Inter Calcus plugin in the admin area.
 *
 * This function checks if the current user is an administrator, then enqueues the required
 * styles and scripts for the Inter Calcus plugin. It also initializes the database and
 * displays the main configuration menu using the MainMenuFrontend class.
 *
 * @return void
 * @since 1.0.0
 *
 */
function inter_calc_main_configuration()
{
    if (is_admin()) {
        wp_enqueue_style('icalc_main-styles', plugins_url('../styles/icalc-main-sheetstyle.css', __FILE__), array(), '0.0.1', false);
        add_action('wp_enqueue_style', 'icalc_main-styles');

        \icalc\db\DatabaseInit::init();

        echo '<div class="wrap">
        <h2>' . __("Inter Calcus Menu", "icalc") . '</h2>';
        \icalc\fe\MainMenuFrontend::configuration();
        echo '</div>';

        wp_enqueue_script('icalc_main-script', plugins_url('../scripts/icalc_main.js', __FILE__), array(), ICALC_VERSION, false);
        add_action('wp_enqueue_scripts', 'icalc_main-script');

    }
}

/**
 *
 * Displays the Product Menu configuration page in the WordPress admin area.
 *
 * If the user is an admin, the function initializes the database, outputs the HTML for the page title, and
 * calls the configuration method of the ProductAdminFrontend class to display the menu settings.
 *
 * @return void
 * @since 1.0.0
 */
function ic_menu_products_configuration()
{
    if (is_admin()) {

        \icalc\db\DatabaseInit::init();

        echo '<div class="wrap">
        <h2>' . __("Product Menu", "icalc") . '</h2>';

        \icalc\fe\ProductAdminFrontend::configuration();

        echo '</div>';
    }
}


/**
 *
 *Displays the Services Menu configuration page in the WordPress admin area.
 *
 * If the user is an admin, the function initializes the database, outputs the HTML for the page title, and
 * calls the configuration method of the ServiceAdminFrontend class to display the menu settings.
 * @return void
 * @since 1.0.0
 *
 */
function ic_menu_services_configuration()
{
    if (is_admin()) {
        \icalc\db\DatabaseInit::init();
        echo '<div class="wrap">
        <h2>' . __("Services Menu", "icalc") . '</h2>';
        \icalc\fe\ServiceAdminFrontend::configuration();
        echo '</div>';
    }
}

/**
 * Displays the Statistics Menu page in the WordPress admin area.
 *
 * If the user is an admin, the function initializes the database, outputs the HTML for the page title, and
 * calls the configuration method of the StatisticsAdminFrontend class to display the statistics data.
 *
 * @return void
 * @since 1.0.0
 */
function ic_menu_statistics()
{
    if (is_admin()) {

        \icalc\db\DatabaseInit::init();

        echo '<div class="wrap">
        <h2>' . __("Statistics Menu", "icalc") . '</h2>';

        \icalc\fe\StatisticsAdminFrontend::configuration();

        echo '</div>';
    }
}