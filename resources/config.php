<?php

/*
http://code.tutsplus.com/tutorials/organize-your-next-php-project-the-right-way--net-5873
*/

/*
    The important thing to realize is that the config file should be included in every
    page of your project, or at least any page you want access to these settings.
    This allows you to confidently use these settings throughout a project because
    if something changes such as your database credentials, or a path to a specific resource,
    you'll only need to update it here.
*/

require_once("site.php");

$config = array(
    /* Store database credentials or other data pertaining to your databases */
    "db" => array(
        "haze_db" => array(
            "dbname" => "haze_db",
            "username" => "root",
            "password" => "mysql",
            "host" => "localhost"
        ),
        "db2" => array(
            "dbname" => "database2",
            "username" => "dbUser",
            "password" => "pa$$",
            "host" => "localhost"
        )
    ),

    /* Stored URLS to reference remote resources throughout the site */
    "urls" => array(
        /* TODO: Figure out what our base URL is */
        "baseUrl" => "http://example.com",
        "steamUrl" => "http://store.steampowered.com"
    ),
    /* Commonly-used paths to various site resources */
    "paths" => array(
        "resources" => $_SERVER["DOCUMENT_ROOT"] . "/resources",
        "images" => $_SERVER["DOCUMENT_ROOT"] . "/img",
        "styles" => $_SERVER["DOCUMENT_ROOT"] . "/css",
        "javascripts" => $_SERVER["DOCUMENT_ROOT"] . "/js"
        )
    );

/*
    I will usually place the following in a bootstrap file or some type of environment
    setup file (code that is run at the start of every page request), but they work
    just as well in your config file if it's in php (some alternatives to php are xml or ini files).
*/

/*
    Initializing the site.php that contains various utility functions for the whole site
*/
$site = new Site();

//Provide your site name here
$site->SetWebsiteName('haze.com');


//Provide your database login details here:
//hostname, user name, password, database name and table name
//note that the script will create the table (for example, fgusers in this case)
//by itself on submitting register.php for the first time
//TODO: change this to use $config variables?
$site->InitDB(/*hostname*/'localhost',
                      /*username*/'root',
                      /*password*/'mysql',
                      /*database name*/'haze_db',
                      /*table name*/'User');

//For better security. Get a random string from this link: http://tinyurl.com/randstr
// and put it here
$site->SetRandomKey('qSRcVS6DrTzrPvr');

/*
    Creating constants for heavily used paths makes things a lot easier.
    ex. require_once(LIBRARY_PATH . "Paginator.php")
*/
defined("LIBRARY_PATH")
    or define("LIBRARY_PATH", realpath(dirname(__FILE__) . '/library'));

defined("TEMPLATES_PATH")
    or define("TEMPLATES_PATH", realpath(dirname(__FILE__) . '/templates'));

/*
    Error reporting.
*/
ini_set("error_reporting", "true");
error_reporting(E_ALL|E_STRCT);

?>
