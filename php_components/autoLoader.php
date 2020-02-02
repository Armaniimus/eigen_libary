<?php
/**
 * This function automaticly loads the controllers, models and view class files
 */

spl_autoload_register(function ($class) {
    $model =        "model/$class.php";
    $libModel =     "model/libary/$class.php";
    $view =         "view/$class.php";
    $controller =   "controller/$class.php";

    if (file_exists($model) ) {
        require_once "$model";

    } elseif (file_exists($libModel) ) {
        require_once "$libModel";

    } elseif (file_exists($controller) ) {
        require_once "$controller";

    } elseif (file_exists($view) ) {
        require_once "$view";
    }
});

?>
