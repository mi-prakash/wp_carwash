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
     * Function to check security after POST
     *
     * @param string $nonce_field
     * @param string $action
     * @param integer|null $post_id
     * @return boolean
     */
    public static function is_secured($nonce_field, $action, $post_id = null)
    {
        $nonce = self::Input($nonce_field);

        if ($nonce == '') {
            return false;
        }
        if (!wp_verify_nonce($nonce, $action)) {
            return false;
        }
        if (!empty($post_id)) {
            if (!current_user_can('edit_post', $post_id)) {
                return false;
            }
            if (wp_is_post_autosave($post_id)) {
                return false;
            }
            if (wp_is_post_revision($post_id)) {
                return false;
            }
        }

        return true;
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
    public static function View($path, $data, $top = true, $bottom = true)
    {
        foreach ($data as $key => $value) {
            $$key = $value;
        }
        
        if ($top && strpos($path, 'front') !== false) {
            require_once('front/layout/top.php');
        }
        require_once($path.'.php');
        if ($bottom && strpos($path, 'front') !== false) {
            require_once('front/layout/bottom.php');
        }
    }

    /**
     * Function for returning default Appointment Status fields
     *
     * @return array
     */
    public static function GetAppointmentStatusFields() {
        $result = array(
            'pending'       => 'Pending',
            'processing'    => 'Processing',
            'completed'     => 'Completed'
        );
        
        return $result;
    }
}