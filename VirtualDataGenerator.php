<?php

/**
 * 简述
 *
 * 初始化数组数据
 *
 * @copyright Copyright&copy; 2016, Meizu.com
 * @author   zouzehua <zouzehua@meizu.com>
 * @version $Id: VirtualDataGenerator.php, v ${VERSION} 2016-8-19 10:41 Exp $
 */
class VirtualDataGenerator {
    static public function getVirtualResult() {
        $dataList = [
            ['id' => '0', 'text' => '根', 'parentId' => '-1','weight'=>1],
            ['id' => '1', 'text' => '1', 'parentId' => '0','weight'=>1],
            ['id' => '2', 'text' => '2', 'parentId' => '0','weight'=>1],
            ['id' => '3', 'text' => '3', 'parentId' => '0','weight'=>1],
            ['id' => '4', 'text' => '4', 'parentId' => '0','weight'=>1],
            ['id' => '11', 'text' => '11', 'parentId' => '1','weight'=>1],
            ['id' => '12', 'text' => '12', 'parentId' => '1','weight'=>1],
            ['id' => '21', 'text' => '21', 'parentId' => '2','weight'=>1],
            ['id' => '31', 'text' => '31', 'parentId' => '3','weight'=>1],
            ['id' => '32', 'text' => '32', 'parentId' => '3','weight'=>1],
            ['id' => '41', 'text' => '41', 'parentId' => '4','weight'=>1],
            ['id' => '43', 'text' => '43', 'parentId' => '4','weight'=>1],
            ['id' => '111', 'text' => '111', 'parentId' => '11','weight'=>1],
            ['id' => '121', 'text' => '121', 'parentId' => '12','weight'=>1],
            ['id' => '122', 'text' => '122', 'parentId' => '12','weight'=>1],
            ['id' => '123', 'text' => '123', 'parentId' => '12','weight'=>1],
            ['id' => '321', 'text' => '321', 'parentId' => '32','weight'=>1],
            ['id' => '322', 'text' => '322', 'parentId' => '32','weight'=>1],
            ['id' => '323', 'text' => '323', 'parentId' => '32','weight'=>1],
        ];

        return $dataList;
    }
}