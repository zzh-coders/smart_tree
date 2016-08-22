<?php
/**
 * 简述
 *
 * 详细说明(可略)
 *
 * @copyright Copyright&copy; 2016, Meizu.com
 * @author   zouzehua <zouzehua@meizu.com>
 * @version $Id: index.php, v ${VERSION} 2016-8-19 10:43 Exp $
 */

spl_autoload_register(function($class){
    include $class.'.php';
});
MultipleTree::run();