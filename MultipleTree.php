<?php

/**
 * 简述
 *
 * 详细说明(可略)
 *
 * @copyright Copyright&copy; 2016, Meizu.com
 * @author   zouzehua <zouzehua@meizu.com>
 * @version $Id: MultipleTree.php, v ${VERSION} 2016-8-19 10:40 Exp $
 */
class MultipleTree {
    static public function run() {
        //读取层次数据结果集列表
        $dataList = VirtualDataGenerator::getVirtualResult();
        // 构造加权多叉树
        $root = self::buildWeightedMultiTree($dataList);

        // 构造功能叶子列表
        $functionLeafList = self::buildFunctionLeafList($root);

        // 对多叉树重新进行横向排序
        $root->sortChildren();
        // 输出首次登录后的树形菜单
        Utils::debug("首次登录时的树形菜单：\n" . $root->toString());

        // 进行菜单节点搜索（即功能路径筛选）
        self::searchTreeNode($root, "123");

        // 输出搜索结果
        Utils::debug("搜索后的树形菜单：\n" . $root->toString());

        // 增加功能路径权值
        self::increaseRouteWeight($root, $functionLeafList, "321");
        self::increaseRouteWeight($root, $functionLeafList, "111");

        // 对多叉树重新进行横向排序
        $root->sortChildren();
        // 输出权值变化后的树形菜单
        Utils::debug("路径权值变化后再次登录时的树形菜单：\n" . $root->toString());

        // 获取热点功能叶子
        $hotFunctionLeaf = self::getHotFunctionLeaf($functionLeafList);

        // 输出热点功能叶子
        self::printHotFunctionLeaf($hotFunctionLeaf);

        // 程序输出结果如下：

        //首次登录时的树形菜单：
        //{id : 0, text : 根, children : [{id : 1, text : 1, children : [{id : 11, text : 11, children : [{id : 111, text : 111, leaf : true}]},{id : 12, text : 12, children : [{id : 121, text : 121, leaf : true},{id : 122, text : 122, leaf : true},{id : 123, text : 123, leaf : true}]}]},{id : 2, text : 2, children : [{id : 21, text : 21, leaf : true}]},{id : 3, text : 3, children : [{id : 31, text : 31, leaf : true},{id : 32, text : 32, children : [{id : 321, text : 321, leaf : true},{id : 322, text : 322, leaf : true},{id : 323, text : 323, leaf : true}]}]},{id : 4, text : 4, children : [{id : 41, text : 41, leaf : true},{id : 43, text : 43, leaf : true}]}]}
        //  搜索后的树形菜单：
        //{id : 0, text : 根, children : [{id : 1, text : 1, children : [{id : 12, text : 12, children : [{id : 123, text : 123, leaf : true}]}]}]}
        //路径权值变化后再次登录时的树形菜单：
        //{id : 0, text : 根, children : [{id : 2, text : 2, children : [{id : 21, text : 21, leaf : true}]},{id : 4, text : 4, children : [{id : 41, text : 41, leaf : true},{id : 43, text : 43, leaf : true}]},{id : 1, text : 1, children : [{id : 12, text : 12, children : [{id : 121, text : 121, leaf : true},{id : 122, text : 122, leaf : true},{id : 123, text : 123, leaf : true}]},{id : 11, text : 11, children : [{id : 111, text : 111, leaf : true}]}]},{id : 3, text : 3, children : [{id : 31, text : 31, leaf : true},{id : 32, text : 32, children : [{id : 322, text : 322, leaf : true},{id : 323, text : 323, leaf : true},{id : 321, text : 321, leaf : true}]}]}]}
        //输出热点功能叶子
        //[{id : 111, text : 111, leaf : true},{id : 321, text : 321, leaf : true}]
    }

    /**
     * 构造加权多叉树
     * @return
     */
    static public function buildWeightedMultiTree($dataList) {
        // 节点列表（散列表，用于临时存储节点对象）
        $nodeList = [];
        // 根节点
        $root = new Node();
        // 根据结果集构造节点列表（存入散列表）
        foreach ($dataList as $value) {
            $node                = new Node();
            $node->id            = $value['id'];
            $node->text          = $value['text'];
            $node->parentId      = $value['parentId'];
            $node->weight        = $value['weight'];
            $nodeList[$node->id] = $node;
        }

        foreach ($nodeList as $node) {
            if ('-1' == $node->parentId) {
                $root = $node;
            } else {
                $nodeList[$node->parentId]->addChild($node);
                $node->parentNode = $nodeList[$node->parentId];
            }
        }

        return $root;
    }

    /**
     * 构造功能叶子列表
     * @param root
     * @return
     */
    static public function buildFunctionLeafList(Node $root) {
        $functionLeafList = [];
        $root->initializeLeafList($functionLeafList);

        return $functionLeafList;
    }

    /**
     * 进行菜单节点搜索（即功能路径筛选）
     * @param root
     * @param keyWord
     */
    static public function searchTreeNode(Node $root, $keyWord) {
        // 首先设置整棵树的功能路径为不可见
        $root->setTreeNotVisible();
        // 在整棵功能树中搜索包含关键字的节点，并进行路径筛选
        $root->searchTreeNode($keyWord);
    }

    /**
     * 增加功能路径权值
     * @param root
     */
    static public function increaseRouteWeight(Node $root, array $functionLeafList, $nodeId) {
        // 首先设置整棵树的功能路径为可见
        $root->setTreeVisible();
        // 对包含功能叶子节点的路径权值加1
        foreach ($functionLeafList as $leafNode) {
            if ($leafNode->id == $nodeId) {
                $leafNode->increaseRouteWeight();
            }

        }
    }

    /**
     * 获取热点功能叶子
     * @param functionLeafList
     * @return
     */
    static public function getHotFunctionLeaf(array $functionLeafList) {
        $count = $totalWeight = 0;

        foreach ($functionLeafList as $node) {
            $totalWeight += $node->weight;
            $count++;
        }
        //这里平均权重为四舍五入
        $avgWeight = ($count > 0) ? round($totalWeight / $count, 2) : 0;


        $retList = [];
        foreach ($functionLeafList as $node) {
            if ($node->weight > $avgWeight) {
                $retList[] = $node;
            }

        }

        return $retList;
    }


    /**
     * 输出热点功能叶子
     * @param hotFunctionLeaf
     */
    static public function printHotFunctionLeaf($hotFunctionLeaf) {
        $result = '[';
        if ($hotFunctionLeaf) {
            foreach ($hotFunctionLeaf as $node) {
                if ($ret = $node->toString()) {
                    $result .= $ret . ',';
                }

            }
        }
        $result = trim($result, ',') . ']';
        Utils::debug("输出热点功能叶子\n" . $result);
    }
}