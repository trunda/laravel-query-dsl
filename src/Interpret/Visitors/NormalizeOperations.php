<?php

namespace Trunda\QueryDSL\Interpret\Visitors;

use Hoa\Compiler\Llk\TreeNode;

class NormalizeOperations extends Visitor
{
    public function visitConditionNode(TreeNode $node)
    {
        // If value is array and comparator T_EQ, treat is as IN
        if ($node->getChild(2)->getId() === '#array'
            && in_array($node->getChild(1)->getValueToken(), ['OP_EQ', 'OP_NEQ'])) {
            $newNode = new TreeNode(
                "#inCondition",
                null,
                [
                    $node->getChild(0),
                    $node->getChild(2)
                ],
                $node->getParent()
            );

            $newNode->getData()['negate'] = $node->getChild(1)->getValueToken() === 'OP_NEQ';
            
            return $newNode;
        }

        return $node;
    }
}