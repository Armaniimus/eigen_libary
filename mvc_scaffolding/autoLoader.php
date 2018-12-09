<?php
/**
 * This function automaticly loads the config, router and all model files
 */
function myAutoLoad() {
    function loadModels($class) {
        req($class, 'Model');
    }

    function loadRouter($class) {
        req($class, 'Router');
    }

    function req($class, $folder) {
        if (strpos($class, '.php') !== false) {
            if (file_exists("$folder/$class")){
                require_once "$folder/$class";
            }
        }
    }

    array_map('loadModels', scandir('Model'));
    array_map('loadRouter', scandir('Router'));
};
myAutoLoad();

?>
