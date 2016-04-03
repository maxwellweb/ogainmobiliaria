<?php if ( ! defined( 'ABSPATH' ) ) exit;

/*
|--------------------------------------------------------------------------
| Deprecated Database Functions
|--------------------------------------------------------------------------
|
| Included for backwards compatibility with Visual Composer.
|
*/
function ninja_forms_get_all_forms(){
    return Ninja_Forms()->form()->get_forms();
}