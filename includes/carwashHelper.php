<?php
class CarwashHelper 
{
    /**
     * Construct function
     */
    public function __construct()
    {

    }

    /**
     * Function to get request data and process it
     *
     * @param string $request
     * @param boolean $trim
     */
    public static function Get($request, $trim = true)
    {
        if ($_POST) {
            if (is_array($_POST[$request])) {
                $value = $_POST[$request];
            } elseif (is_numeric($_POST[$request])) {
                $value = sanitize_text_field(isset($_POST[$request]) ? $_POST[$request] : 0);
                if ($trim) {
                    $value = trim($value);
                }
            } else {
                $value = sanitize_text_field(isset($_POST[$request]) ? $_POST[$request] : '');
                if ($trim) {
                    $value = trim($value);
                }
            }

            return $value;
        }
        
        return false;
    }
}