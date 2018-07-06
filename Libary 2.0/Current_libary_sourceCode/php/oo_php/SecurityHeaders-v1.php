<?php

$SecurityHeaders = new SecurityHeaders;

$domain = "localhost";

$script = "https://code.jquery.com/jquery-3.2.1.slim.min.js "
. "https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js "
. "https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js ";

$style =
"https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css "
. "https://fonts.googleapis.com/css "
. "'unsafe-inline' uri";

$font = "https://fonts.gstatic.com ";

$image = "data:";

$SecurityHeaders->SetContentSecurityHeader($domain, $script, $style, $font, $image);

class SecurityHeaders {
    public function __construct() {
        header("Strict-Transport-Security: max-age=31536000 env=HTTPS"); //set connection to https if connected before to the site using https
        header("X-Frame-Options: SAMEORIGIN"); //prevent page from being loaded as an iframe somewhere else
        header("X-XSS-Protection: 1; mode=block"); //blocks loading the webpage if an xss attack is present
        header("X-Content-Type-Options: nosniff"); // blocks a request if mime type doesn't match the requested content
        header("Referrer-Policy: same-origin"); // only send referrer info to the current domain
    }

    public function SetContentSecurityHeader($ownDomain, $script = '', $style = '', $font = '', $image = '', $object = '', $default = '') {
        header("Content-Security-Policy:"
            . "default-src 'self' *.$ownDomain $default;"
            . "style-src 'self' *.$ownDomain $style;"
            . "font-src 'self' *.$ownDomain $font;"
            . "img-src 'self' *.$ownDomain $image;"
            . "script-src 'self' *.$ownDomain $script;"
            . "object-src 'self' *.$ownDomain $object;"
        );
    }


}
?>
