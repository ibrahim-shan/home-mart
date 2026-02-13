<?php
var_dump(
    function_exists('imagecreate'),
    extension_loaded('gd'),
    php_ini_loaded_file()
);
