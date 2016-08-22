<?php

/**
 * 节点类
 */
class Node {

    /**
     * 节点编号
     */
    public $id;
    /**
     * 节点内容
     */
    public $text;
    /**
     * 父节点编号
     */
    public $parentId;
    /**
     * 节点权值
     */
    public $weight;
    /**
     * 父节点引用
     */
    public $parentNode;
    /**
     * 是否可见，默认为true
     */
    public $visible = true;
    /**
     * 孩子节点列表
     */
    private $children = null;

    /**
     * Node constructor.
     */
    public function __construct() {
        $this->children = new Children();
    }

    // 先序遍历，拼接JSON字符串
    public function toString() {
        if ($this->visible) {
            $result = '{id : ' . $this->id . ', text : ' . $this->text;

            if ($this->children != null && $this->children->getSize() != 0) {
                $result .= ', children : ' . $this->children->toString();
            } else {
                $result .= ', leaf : true';
            }

            return $result . '}';
        } else {
            return '';
        }

    }

    // 兄弟节点横向排序
    public function sortChildren() {
        if ($this->children != null && $this->children->getSize() != 0) {
            $this->children->sortChildren();
        }
    }

    // 添加孩子节点
    public function addChild($node) {
        $this->children->addChild($node);
    }

    // 先序遍历，构造功能叶子列表
    public function initializeLeafList(&$leafList) {
        if ($this->children->getSize() == 0) {
            $leafList[] = $this;
        } else {
            $this->children->initializeLeafList($leafList);
        }
    }

// 先序遍历，设置该节点下的所有功能路径为不可见
    public function setTreeNotVisible() {
        $this->visible = false;
        if ($this->children != null && $this->children->getSize() != 0) {

            $this->children->setTreeNotVisible();
        }
    }

// 先序遍历，设置该节点下的所有功能路径为可见

    public function searchTreeNode($keyWord) {
        if (strpos($this->text, $keyWord) > -1) {
            $this->setTreeVisible();
            $this->setRouteVisible();
        } else {
            if ($this->children != null && $this->children->getSize() != 0) {
                $this->children->searchTreeNode($keyWord);
            }
        }
    }

    // 设置包含该叶子节点的功能路径可见

    public function setTreeVisible() {
        $this->visible = true;
        if ($this->children != null && $this->children->getSize() != 0) {

            $this->children->setTreeVisible();
        }
    }


    // 先序遍历，搜索菜单节点，同时进行功能路径过滤

    public function setRouteVisible() {
        $this->visible = true;
        for ($parentNode = $this->parentNode; $parentNode != null; $parentNode = $parentNode->parentNode) {
            $parentNode->visible = true;
        }
    }

    public function increaseRouteWeight() {
        $this->weight++;
        $this->updateNodeWeightToDB($this);
        for ($parentNode = $this->parentNode; $parentNode != null; $parentNode = $parentNode->parentNode) {

            $parentNode->weight++;
            $this->updateNodeWeightToDB($parentNode);
        }
    }

    // 更新节点权值到数据库
    public function updateNodeWeightToDB(Node $node) {
        // 暂时不实现，实际应用中需要实现该方法
        // 或者用户退出系统时，遍历整棵树，统一更新所有节点的权值到数据库中，应该这样做比较好，一次性统一处理
        return true;
    }
}