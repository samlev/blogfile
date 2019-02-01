<?php
/**
 * Settings.php
 *
 * @package
 * @author: Samuel Levy <sam@samuellevy.com>
 */

/**
 * Class Settings
 *
 * simple static helper class for settings
 */
class Settings {
    /**
     * Sets a system setting, with some basic protection for some settings
     *
     * @param string $setting The setting name
     * @param mixed $value The value to set
     * @param boolean $overwrite Overwrite any existing setting with the same name
     */
    public static function set(string $setting, $value, bool $overwrite = false) {
        $_MYSQLI = &$GLOBALS['_MYSQLI'];
        // check for some 'protected' types
        switch ($setting) {
            case 'displayname':
            case 'password':
                $overwrite = true;
                break;
        }

        // add the setting to the database
        $query = "INSERT INTO `" . DB_PREF . "settings` (`setting`,`value`,`time_set`)
                  VALUES  ('" . mysqli_real_escape_string($_MYSQLI, $setting) . "',
                           '" . mysqli_real_escape_string($_MYSQLI, serialize($value)) . "',
                           NOW())";
        run_query($query);

        // check if we're overwriting (actually, we're just removing any other variables by the same name)
        if ($overwrite) {
            // remove any other entries
            $query = "DELETE FROM `" . DB_PREF . "settings`
                      WHERE `setting`='" . mysqli_real_escape_string($_MYSQLI, $setting) . "'
                      AND `id` != " . intval($_MYSQLI->insert_id);

            run_query($query);
        }
    }

    /**
     * Gets a system setting
     *
     * @param string $setting The name of the variable to get
     * @param mixed $default The default if the setting doesn't exist
     *
     * @return mixed The value of the setting, or default.
     */
    public static function get(string $setting, $default = null) {
        $_MYSQLI = &$GLOBALS['_MYSQLI'];

        // get the setting
        $query = "SELECT `value`
                  FROM `" . DB_PREF . "settings`
                  WHERE `setting`='" . mysqli_real_escape_string($_MYSQLI, $setting) . "'
                  ORDER BY `id` DESC
                  LIMIT 1";

        $result = run_query($query);

        // if we got a row, unserialize it, if not, use the default.
        $row = mysqli_fetch_assoc($result);
        if (! empty($row)) {
            $ret = unserialize($row['value']);
        } else {
            $ret = $default;
        }

        // free the result
        mysqli_free_result($result);

        // and return the value
        return $ret;
    }

    /**
     * Simply checks if a setting exists
     *
     * @param string $setting The name of the setting
     *
     * @return boolean True if the setting exists, False if not.
     */
    public static function exists(string $setting) {
        $_MYSQLI = &$GLOBALS['_MYSQLI'];

        // get the setting
        $query = "SELECT `id`
                  FROM `" . DB_PREF . "settings`
                  WHERE `setting`='" . mysqli_real_escape_string($_MYSQLI, $setting) . "'
                  LIMIT 1";

        $result = run_query($query);

        $row = mysqli_fetch_assoc($result);
        if (! empty($row)) {
            $ret = true;
        } else {
            $ret = false;
        }

        // free the result
        mysqli_free_result($result);

        // and return
        return $ret;
    }

    /**
     * Deletes a setting if it exists
     *
     * @param string $setting The name of the setting
     */
    public static function delete(string $setting) {
        $_MYSQLI = &$GLOBALS['_MYSQLI'];

        $query = "SELECT `id`
                  FROM `" . DB_PREF . "settings`
                  WHERE `setting`='" . mysqli_real_escape_string($_MYSQLI, $setting) . "'";

        run_query($query);
    }

    /** Searches for a setting with a specific value, and returns the number of occurances
     *
     * @param string $setting The setting name
     * @param mixed $value The value to search for
     *
     * @return integer The number of times this setting and value occur
     */
    static function search(string $setting, $value) {
        $_MYSQLI = &$GLOBALS['_MYSQLI'];

        // search for the setting and the value
        $query = "SELECT count(`id`) as count
                  FROM `" . DB_PREF . "settings`
                  WHERE `setting`='" . mysqli_real_escape_string($_MYSQLI, $setting) . "'
                  AND `value`='" . mysqli_real_escape_string($_MYSQLI, serialize($value)) . "'
                  GROUP BY `value`";

        $result = run_query($query);
        $row    = mysqli_fetch_assoc($result);
        mysqli_free_result($result);

        if ($row) {
            return $row['count'];
        } else {
            return 0;
        }
    }
}