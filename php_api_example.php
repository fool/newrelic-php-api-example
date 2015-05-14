<?php

// fool 3 March 2013

// this script has 2 features:
// 1) demonstrates use of the PHP Agent API
// 2) customer trouble reproduction/investigation tool


//// setting content type to non-(x)html will prevent RUM autoinstrumentation.  must come before any other output to stdout.
// header('Content-type: application/pdf');

//// this will throw an error that NR captures as a traced error:
// include("missingfile");
//// this will too:
// newrelic_notice_error("oops");

function fone() {
	sleep(5);
}
function ftwo() {
	sleep(5);
}


//// I demand you give me a RUM header.
if( extension_loaded('newrelic')) { echo newrelic_get_browser_timing_header(); }

//// Shall I choose my own app name? 
if( extension_loaded('newrelic')) { newrelic_set_appname("NR PHP API test script"); }


//// am i a background job?  This will cause reporting as non-web in the NR UI
if( extension_loaded('newrelic')) { newrelic_background_job(); }

//// Shall I choose my own transaction name?
if( extension_loaded('newrelic')) { newrelic_name_transaction("/sasquatch"); }
//// want to trace those pesky potentially slow functions?
if( extension_loaded('newrelic')) { newrelic_add_custom_tracer("fone"); }
if( extension_loaded('newrelic')) { newrelic_add_custom_tracer("ftwo"); }
//// report custom metrics (useful for dashboard testing Custom/thingy/*)
if( extension_loaded('newrelic')) { newrelic_custom_metric("Custom/thingy/1",4000); }
if( extension_loaded('newrelic')) { newrelic_custom_metric("Custom/thingy/2",2300); }
// custom parameters show up in two places:  traces (error/transaction) and insights transaction events
if( extension_loaded('newrelic')) { newrelic_add_custom_parameter ("hi", "mom"); }

//// our favorite payload
phpinfo();

//// more than one way to ride an armadillo.  i once cared for some reason.
call_user_func("fone");
ftwo();

//// if autoinstrumentation is off && page got header & is eligible, this will generate the necessary RUM footer.  otherwise it's a NOOP, so safe to leave in there.
echo newrelic_get_browser_timing_footer();
?>
