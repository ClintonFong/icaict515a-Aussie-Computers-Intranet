<?php

// constants
define( "SET_SCREEN_WIDTH",         "1024px");

// user access levels
// ---------------------
define( "AL_EMPLOYEE",              "0" );
define( "AL_PROCUREMENT_MEMBER",    "2" );
define( "AL_MANAGER",               "5" );
define( "AL_ADMIN",                 "9" );


// order stage
// ---------------------
define( "OS_SUBMITTED",             "0" );
define( "OS_APPROVED",              "1" );
define( "OS_REJECTED",              "-1" );
define( "OS_PROCESSED",             "2" );
define( "OS_SAVED",                 "-2" );


define("LOGGED_IN",                 "1");
define("LOGGED_OUT",                "0");

define("TEMPORARY_PASSWORD_LENGTH", "8");



// default procurement team email
// define( "PROCUREMENT_TEAM_EMAIL",   "procurement-team@clintonfong.com" );
define( "PROCUREMENT_TEAM_EMAIL",   "cf.evocca.test.s2@gmail.com" );



// database variables
// ------------------
/*
define( "DB_SERVER",                "localhost" );
define( "USER_NAME",                "root" );
define( "PASSWORD",                 "" );
define( "DATABASE",                 "icaict515a" );
*/

define( "DB_SERVER",                "68.178.217.16" );
define( "USER_NAME",                "evocca" );
define( "PASSWORD",                 "Attraction001!!" );
define( "DATABASE",                 "evocca" );

?>