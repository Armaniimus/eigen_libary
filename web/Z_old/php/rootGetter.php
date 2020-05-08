<?php
function getRoot() {
    //you can set this as an alternative to running the below method
    //if your always sure you have the same url
    $rootUrl = false;

    if ($rootUrl) {
        return $rootUrl;
    } else {
        $fileUriArray = explode('\\', __file__);

        while(true) {
            $uriPart = Array_pop($fileUriArray);
            if ($uriPart == 'public_html' || $uriPart == 'websitenoombla2019') {
                Array_push($fileUriArray, $uriPart);
                $uri = implode($fileUriArray, '\\');
                return $uri;
                break;
            }
        }
    }
}
$root = getRoot();
