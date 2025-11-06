<?php

/*
 | --------------------------------------------------------------------
 | App Namespace
 | --------------------------------------------------------------------
 |
 | This defines the default Namespace that is used throughout
 | CodeIgniter to refer to the Application directory. Change
 | this constant to change the namespace that all application
 | classes should use.
 |
 | NOTE: changing this will require manually modifying the
 | existing namespaces of App\* namespaced-classes.
 */
defined('APP_NAMESPACE') || define('APP_NAMESPACE', 'App');

/*
 | --------------------------------------------------------------------------
 | Composer Path
 | --------------------------------------------------------------------------
 |
 | The path that Composer's autoload file is expected to live. By default,
 | the vendor folder is in the Root directory, but you can customize that here.
 */
defined('COMPOSER_PATH') || define('COMPOSER_PATH', ROOTPATH . 'vendor/autoload.php');

/*
 |--------------------------------------------------------------------------
 | Timing Constants
 |--------------------------------------------------------------------------
 |
 | Provide simple ways to work with the myriad of PHP functions that
 | require information to be in seconds.
 */
defined('SECOND') || define('SECOND', 1);
defined('MINUTE') || define('MINUTE', 60);
defined('HOUR')   || define('HOUR', 3600);
defined('DAY')    || define('DAY', 86400);
defined('WEEK')   || define('WEEK', 604800);
defined('MONTH')  || define('MONTH', 2_592_000);
defined('YEAR')   || define('YEAR', 31_536_000);
defined('DECADE') || define('DECADE', 315_360_000);

/*
 | --------------------------------------------------------------------------
 | Exit Status Codes
 | --------------------------------------------------------------------------
 |
 | Used to indicate the conditions under which the script is exit()ing.
 | While there is no universal standard for error codes, there are some
 | broad conventions.  Three such conventions are mentioned below, for
 | those who wish to make use of them.  The CodeIgniter defaults were
 | chosen for the least overlap with these conventions, while still
 | leaving room for others to be defined in future versions and user
 | applications.
 |
 | The three main conventions used for determining exit status codes
 | are as follows:
 |
 |    Standard C/C++ Library (stdlibc):
 |       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
 |       (This link also contains other GNU-specific conventions)
 |    BSD sysexits.h:
 |       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
 |    Bash scripting:
 |       http://tldp.org/LDP/abs/html/exitcodes.html
 |
 */
defined('EXIT_SUCCESS')        || define('EXIT_SUCCESS', 0);        // no errors
defined('EXIT_ERROR')          || define('EXIT_ERROR', 1);          // generic error
defined('EXIT_CONFIG')         || define('EXIT_CONFIG', 3);         // configuration error
defined('EXIT_UNKNOWN_FILE')   || define('EXIT_UNKNOWN_FILE', 4);   // file not found
defined('EXIT_UNKNOWN_CLASS')  || define('EXIT_UNKNOWN_CLASS', 5);  // unknown class
defined('EXIT_UNKNOWN_METHOD') || define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     || define('EXIT_USER_INPUT', 7);     // invalid user input
defined('EXIT_DATABASE')       || define('EXIT_DATABASE', 8);       // database error
defined('EXIT__AUTO_MIN')      || define('EXIT__AUTO_MIN', 9);      // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      || define('EXIT__AUTO_MAX', 125);    // highest automatically-assigned error code

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_LOW instead.
 */
define('EVENT_PRIORITY_LOW', 200);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_NORMAL instead.
 */
define('EVENT_PRIORITY_NORMAL', 100);

/**
 * @deprecated Use \CodeIgniter\Events\Events::PRIORITY_HIGH instead.
 */
define('EVENT_PRIORITY_HIGH', 10);

defined('Nutrition_Criteria_Items') || define('Nutrition_Criteria_Items', [
    "Energy" => [
      "one-day-intake" => 8400,
      "unit" => "kJ",
    ], 
    "Fat" => [
      "one-day-intake" => 70,
      "unit" => "g",
      "food_criteria" => [
        "low" => ['high' => 3.0],
        "med" => ['low' => 3.0, 'high' => 17.5],
        'high' => ['low' => 17.5]
      ],
      "drink_criteria" => [
        "low" => ['high' => 1.5],
        "med" => ['low' => 1.5, 'high' => 8.75],
        'high' => ['low' => 8.75]
      ]
    ], 
    "Saturates" => [
      "one-day-intake" => 20,
      "unit" => "g",
      "food_criteria" => [
        "low" => ['high' => 1.5],
        "med" => ['low' => 1.5, 'high' => 5.0],
        "high" => ['low' => 5.0]
      ],
      "drink_criteria" => [
        "low" => ['high' => 0.75],
        "med" => ['low' => 0.75, 'high' => 2.5],
        "high" => ['low' => 2.5]
      ]
    ], 
    "Sugars" => [
      "one-day-intake" => 90,
      "unit" => "g",
      "food_criteria" => [
        "low" => ['high' => 5.0],
        "med" => ['low' => 5.0, 'high' => 22.5],
        "high" => ['low' => 22.5]
      ],
      "drink_criteria" => [
        "low" => ['high' => 2.5],
        "med" => ['low' => 2.5, 'high' => 11.25],
        "high" => ['low' => 11.25]
      ]
    ], 
    "Salt" => [
      "one-day-intake" => 6,
      "unit" => "g",
      "food_criteria" => [
        "low" => ['high' => 0.3],
        "med" => ['low' => 0.3, 'high' => 1.5],
        "high" => ['low' => 1.5]
      ],
      "drink_criteria" => [
        "low" => ['high' => 0.3],
        "med" => ['low' => 0.3, 'high' => 0.75],
        "high" => ['low' => 0.75]
      ]
    ]
]);

defined('PriceList') || define('PriceList', [
  'Season_Presell' => '06',
  'Day_Today_Elite' => '08',
  'TradeShow' => '09',
  'Day_Today' => '10',
  'Day_Today_Club' => '11',
  'U_Save' => '12',
  'Cash_N_Carry' => '999'
]);

defined('CmsType') || define('CmsType', [
  'top_ribbon' => 'top_ribbon',
  'home_banner' => 'home_banner',
  'category_carousel' => 'category_carousel',
  'category_banner' => 'category_banner',
  'shop_by_category' => 'shop_by_category',
  'products_carousel' => 'products_carousel',
  'brochure' => 'brochure',
  'brand' => 'brand',
  'bottom_banner' => 'bottom_banner',
  'multibuy' => 'multibuy',
  'sponsor' => 'sponsor',
]);

defined('API_BASE_URL') || define('API_BASE_URL', 'http://order.uniteduk.co.uk');
defined('API_USER_SERIAL') || define('API_USER_SERIAL', 'websiteapi');
defined('API_USER_ACTIVATION') || define('API_USER_ACTIVATION', '6t3rhfskhd');

defined('LOGON_USER_MENUES') || define('LOGON_USER_MENUES', [
  'my_account' => ["label" => "My Balance", "icon" => "/assets/images/icons/png/pound-coin-outline.png", "url" => "/myaccount/credit_account/balance"],
  // 'my_orders' => ["label" => "My Orders", "icon" => "/assets/images/icons/png/rules.png", "url" => "/pastorders"],
  'my_order_history' => ["label" => "My Order History", "icon" => "/assets/images/icons/png/rules.png", "url" => "/myaccount/order_history"],
  'my_invoice_history' => ["label" => "My Invoice History", "icon" => "/assets/images/icons/png/invoice_history.png", "url" => "/myaccount/invoice_history"],
  'credit_ledger' => ["label" => "Credit Ledger Details", "icon" => "/assets/images/icons/png/note-book.png", "url" => "/myaccount/ledger"],
  'ulp' => ["label" => "United Loyalty Program", "icon" => "/assets/images/icons/png/ulp.png", "url" => "https://loyalty.uniteduk.com/"],
  'help' => ["label" => "Help & Support", "icon" => "/assets/images/icons/png/question-mark-circle-outline.png", "url" => ""],
  'switch_account' => ["label" => "Switch Account", "icon" => "/assets/images/icons/png/arrow-goes-left-right.png", "url" => "/home/logout"],
  // 'add_employee' => ["label" => "Add Employee Access", "icon" => "/assets/images/icons/png/lock-open-line.png", "url" => ""],
  'log_out' => ["label" => "Log Out", "icon" => "/assets/images/icons/png/logout.png", "url" => "/home/logout"],
]);

defined('BAND_OPTIONS') || define('BAND_OPTIONS', [
  '' => 'None', 
  'small' => 'Small', 
  'medium' => 'Medium', 
  'large' => 'Large', 
  'elite' => 'Elite'
]);

defined('PRICE_OPTIONS') || define('PRICE_OPTIONS', [
  '001' => '',
  '005' => 'Q5',
  '007' => 'One Day Specials',
  '008' => 'Day ToDay Elite',
  '009' => 'Chill Delivered Promo',
  '010' => 'Day ToDay',
  '011' => 'Day ToDay Express',
  '012' => 'USave',
  '999' => 'Std Pricing',
]);