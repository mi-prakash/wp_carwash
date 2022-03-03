<?php
namespace Carwash;
class CarwashHelper 
{
    /**
     * Construct function
     */
    public function __construct()
    {
        // Code here
    }

    /**
     * Function to get input request data and process it
     *
     * @param string $request
     * @param boolean $trim
     */
    public static function Input($request, $trim = true)
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

    /**
     * Function to view content
     *
     * @param string $path
     * @param array $data
     * @return void
     */
    public static function View($path, $data)
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        require_once($path);
    }
}