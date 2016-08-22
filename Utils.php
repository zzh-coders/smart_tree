<?php

/**
 * 简述
 *
 * 详细说明(可略)
 *
 * @copyright Copyright&copy; 2016, Meizu.com
 * @author   zouzehua <zouzehua@meizu.com>
 * @version $Id: Utils.php, v ${VERSION} 2016-8-19 11:26 Exp $
 */
class Utils {
    static public function debug($arr) {
        echo '<pre>' . print_r($arr, true) . '</pre>';
    }
}