<?php

/**
 * 孩子列表类
 */
class Children {

    private $list = [];

    public function getSize() {
        return count($this->list);
    }

    public function addChild(Node $node) {
        $this->list[] = $node;
    }

    // 拼接孩子节点的JSON字符串
    public function toString() {
        $result = "[";
        foreach ($this->list as $value) {
            if ($ret = $value->toString()) {
                $result .= $ret . ',';
            }
        }

        $result = trim($result, ',') . ']';

        return $result;
    }

    // 孩子节点排序
    public function sortChildren() {
        // 对本层节点进行排序
        // 可根据不同的排序属性，传入不同的比较器，这里传入ID比较器
        usort($this->list, array("NodeIDComparator", "compare"));

        // 对每个节点的下一层节点进行排序
        foreach ($this->list as $value) {
            $value->sortChildren();
        }
    }

// 搜索菜单节点，同时进行功能路径过滤
    public function searchTreeNode($keyWord) {
        foreach ($this->list as $node) {
            $node->searchTreeNode($keyWord);
        }
    }

    // 设置孩子节点为不可见
    public function setTreeNotVisible() {
        foreach ($this->list as $node) {
            $node->setTreeNotVisible();
        }
    }

// 设置孩子节点为可见
    public function setTreeVisible() {
        foreach ($this->list as $node) {
            $node->setTreeVisible();
        }
    }

    // 在孩子节点中寻找功能叶子节点
    public function initializeLeafList(&$leafList) {
        foreach ($this->list as $node) {
            $node->initializeLeafList($leafList);
        }
    }
}

/**
 * 节点比较器
 */
class NodeIDComparator {
    // 按照节点编号比较
    static public function compare(Node $a, Node $b) {
        $a_w = strtolower($a->weight);
        $b_w = strtolower($b->weight);

        if ($a_w == $b_w) {
            $a_id = strtolower($a->id);
            $b_id = strtolower($b->id);

            return $a_id < $b_id ? -1 : ($a_id == $b_id ? 0 : 1);
        }

        return ($a_w > $b_w) ? +1 : -1;
    }
}