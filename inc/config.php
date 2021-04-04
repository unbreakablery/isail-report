<?php
/**
 * config.php
 *
 * Author: Anna
 *
 * Global configuration file
 *
 */

session_start();
ob_start();

// Include Database class
require 'classes/Database.php';

// Include Common class
// NOTE : Please create common object with 'env' param as the following if not development environment.
// $common = new Common('production') or new Common('staging')
require 'classes/Common.php';
$common = new Common(); // development environment

// Set cookie expire time
// $common->setCookieTimeout(30) : 30 days
$common->setCookieTimeout(); // default 7 days

// Set session expire time
// $common->setSessionTimeout(30) : 30 mins
$common->setSessionTimeout(); // default 20 mins

// Include PDF class
require 'classes/Pdf.php';
$pdf = new Pdf($common->getPrefixProjectName()); // prefix_project_name, page_size = 'A4', page_lang = 'en', dest = 'D'

if (!isset($trackPage)) {
    $trackPage = "";
	$common->trackPage = "";
}

if (!$trackPage == 'login' && !$trackPage == 'register') {
    $common->trackPage = $trackPage;

    if (isset($_SESSION['expiretime'])) {
        if ($_SESSION['expiretime'] < time()) {
            session_destroy();
            $common->authenticate();
        } else {
            $_SESSION['expiretime'] = time() + $common->session_timeout;
        }
    } else {
        session_destroy();
        $common->authenticate();
    }

    if (!isset($_SESSION['username'])) {
        $common->authenticate();
    } else {
        $common->username       = $_SESSION['username'];
        $common->full_username  = $_SESSION['user_f_name'] . ' ' . $_SESSION['user_l_name'];
        $common->role           = $_SESSION['role'];
    }
    
}

// Include Template class
require 'classes/Template.php';

// Create a new Template Object
$iSAIL_UI                               = new Template('iSAIL Report', 'v1.0.0', 'assets'); // Name, version and assets folder's name

// Global Meta Data
$iSAIL_UI->author                       = 'Anna';
$iSAIL_UI->robots                       = 'noindex, nofollow';
$iSAIL_UI->title                        = 'iSAIL Report';
$iSAIL_UI->description                  = 'iSAIL Report - Dashboard For Game Level Score created by Anna';

$iSAIL_UI->copyright_year               = 2018;

// Global Included Files (eg useful for adding different sidebars or headers per page)
$iSAIL_UI->inc_sidebar                  = 'base_sidebar.php';
$iSAIL_UI->inc_header                   = 'base_header.php';

// Global Color Theme
$iSAIL_UI->theme                        = '';       // '' for default theme or 'amethyst', 'city', 'flat', 'modern', 'smooth'

// Global Cookies
$iSAIL_UI->cookies                      = true;    // True: Remembers active color theme between pages (when set through color theme list), False: Disables cookies

// Global Body Background Image
$iSAIL_UI->body_bg                      = '';       // eg 'assets/img/photos/photo10@2x.jpg' Useful for login/lockscreen pages

// Global Header Options
$iSAIL_UI->l_header_fixed               = true;     // True: Fixed Header, False: Static Header

// Global Sidebar Options
$iSAIL_UI->l_sidebar_position           = 'left';   // 'left': Left Sidebar and right Side Overlay, 'right': Flipped position
$iSAIL_UI->l_sidebar_mini               = false;    // True: Mini Sidebar Mode (> 991px), False: Disable mini mode
$iSAIL_UI->l_sidebar_visible_desktop    = true;     // True: Visible Sidebar (> 991px), False: Hidden Sidebar (> 991px)
$iSAIL_UI->l_sidebar_visible_mobile     = false;    // True: Visible Sidebar (< 992px), False: Hidden Sidebar (< 992px)

// Global Side Overlay Options
$iSAIL_UI->l_side_overlay_hoverable     = false;    // True: Side Overlay hover mode (> 991px), False: Disable hover mode
$iSAIL_UI->l_side_overlay_visible       = false;    // True: Visible Side Overlay, False: Hidden Side Overlay

// Global Sidebar and Side Overlay Custom Scrolling
$iSAIL_UI->l_side_scroll                = true;     // True: Enable custom scrolling (> 991px), False: Disable it (native scrolling)

// Global Active Page (it will get compared with the url of each menu link to make the link active and set up main menu accordingly)
//$iSAIL_UI->main_nav_active              = basename($_SERVER['PHP_SELF']);
$iSAIL_UI->main_nav_active              = $_SERVER['PHP_SELF'];

// Google Maps API Key (you will have to obtain a Google Maps API key to use Google Maps, for more info please have a look at https://developers.google.com/maps/documentation/javascript/get-api-key#key)
$iSAIL_UI->google_maps_api_key          = '';

// Global Main Menu

if (isset($common->role) && ($common->role == 1)) {
    $iSAIL_UI->main_nav                     = array(
        array(
            'name'  => '<span class="sidebar-mini-hide">Reports</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Aggregate Report</span>',
            'icon'  => 'fa fa-tasks',
            'url'   => '/modules/aggregate/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Player Report</span>',
            'icon'  => 'fa fa-table',
            'url'   => '/modules/player/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Comparison Report</span>',
            'icon'  => 'fa fa-dashboard',
            'url'   => '/modules/comparison/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">' . $common->getModelsShortLabel() . ' Comparison Report</span>',
            'icon'  => 'si si-bar-chart',
            'url'   => '/modules/comparison_model/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Coaching Report</span>',
            'icon'  => 'fa fa-sitemap',
            'url'   => '/modules/coaching/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Progression Report</span>',
            'icon'  => 'fa fa-line-chart',
            'url'   => '/modules/progression/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Management</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">User Management</span>',
            'icon'  => 'si si-users',
            'url'   => '/modules/users/management.php'
        ),       
        array(
            'name'  => '<span class="sidebar-mini-hide">Class Management</span>',
            'icon'  => 'fa fa-group',
            'url'   => '/modules/class/management.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">GKN Management</span>',
            'icon'  => 'fa fa-key',
            'url'   => '/modules/gkn/management.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Coaching Rpt Mgmt</span>',
            'icon'  => 'fa fa-sitemap',
            'url'   => '/modules/coaching/management.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Create</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">New User</span>',
            'icon'  => 'si si-users',
            'url'   => '/modules/users/create.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">New Class</span>',
            'icon'  => 'fa fa-group',
            'url'   => '/modules/class/create_criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">New GKN</span>',
            'icon'  => 'fa fa-key',
            'url'   => '/modules/gkn/create_criteria.php'
        ),
    );
} else if (isset($common->role) && ($common->role == 2)) {
    $iSAIL_UI->main_nav                     = array(
        array(
            'name'  => '<span class="sidebar-mini-hide">Reports</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Aggregate Report</span>',
            'icon'  => 'fa fa-tasks',
            'url'   => '/modules/aggregate/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Player Report</span>',
            'icon'  => 'fa fa-table',
            'url'   => '/modules/player/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Comparison Report</span>',
            'icon'  => 'fa fa-dashboard',
            'url'   => '/modules/comparison/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">' . $common->getModelsShortLabel() . ' Comparison Report</span>',
            'icon'  => 'si si-bar-chart',
            'url'   => '/modules/comparison_model/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Progression Report</span>',
            'icon'  => 'fa fa-line-chart',
            'url'   => '/modules/progression/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Management</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Class Management</span>',
            'icon'  => 'fa fa-group',
            'url'   => '/modules/class/management.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Create</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">New Class</span>',
            'icon'  => 'fa fa-pencil-square-o',
            'url'   => '/modules/class/create_criteria.php'
        ),        
    );
} else if (isset($common->role) && ($common->role == 3)) {
    $iSAIL_UI->main_nav                     = array(
        array(
            'name'  => '<span class="sidebar-mini-hide">Reports</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Aggregate Report</span>',
            'icon'  => 'fa fa-tasks',
            'url'   => '/modules/aggregate/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Player Report</span>',
            'icon'  => 'fa fa-table',
            'url'   => '/modules/player/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Comparison Report</span>',
            'icon'  => 'fa fa-dashboard',
            'url'   => '/modules/comparison/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Progression Report</span>',
            'icon'  => 'fa fa-line-chart',
            'url'   => '/modules/progression/criteria.php'
        )
    );
} else {
    $iSAIL_UI->main_nav                     = array(
        array(
            'name'  => '<span class="sidebar-mini-hide">Reports</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Aggregate Report</span>',
            'icon'  => 'fa fa-tasks',
            'url'   => '/modules/aggregate/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Player Report</span>',
            'icon'  => 'fa fa-table',
            'url'   => '/modules/player/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Comparison Report</span>',
            'icon'  => 'fa fa-dashboard',
            'url'   => '/modules/comparison/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">' . $common->getModelsShortLabel() . ' Comparison Report</span>',
            'icon'  => 'si si-bar-chart',
            'url'   => '/modules/comparison_model/criteria.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Management</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Class Management</span>',
            'icon'  => 'fa fa-group',
            'url'   => '/modules/class/management.php'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">Create</span>',
            'type'  => 'heading'
        ),
        array(
            'name'  => '<span class="sidebar-mini-hide">New Class</span>',
            'icon'  => 'fa fa-pencil-square-o',
            'url'   => '/modules/class/create_criteria.php'
        ),        
    );
}