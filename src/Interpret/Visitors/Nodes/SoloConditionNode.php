<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

/**
 * Class SoloConditionNode
 * @package Trunda\QueryDSL\Interpret\Visitors\Nodes
 */
class SoloConditionNode extends ConditionNode
{
    public function visit()
    {
        $this->visitChildren();

        $this->parent->getBuilder()->where(
            $this->getField(),
            '=',
            !$this->getData('negate', false),
            $this->parent->getBool()
        );

        return $this->node;
    }
}