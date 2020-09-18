<?php

namespace Trunda\QueryDSL\Interpret\Visitors;

use Hoa\Compiler\Llk\TreeNode;

class ToString extends Visitor
{
    public function visitNotNode(TreeNode $not)
    {
        return '!' . $this->visit($not->getChild(0));
    }

    public function visitOrNode(TreeNode $or)
    {
        return collect($or->getChildren())->map([$this, 'visit'])->implode(' || ');
    }

    public function visitAndNode(TreeNode $and)
    {
        return collect($and->getChildren())->map([$this, 'visit'])->implode(' && ');
    }

    public function visitNestedNode(TreeNode $nestedNode)
    {
        return '(' . collect($nestedNode->getChildren())->map([$this, 'visit'])->implode(' || ') . ')';
    }

    public function visitAttributeToken(TreeNode $treeNode)
    {
        return $treeNode->getValueValue();
    }
}
