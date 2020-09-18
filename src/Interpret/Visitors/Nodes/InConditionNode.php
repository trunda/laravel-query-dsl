<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

/**
 * Class InConditionNode
 * @package Trunda\QueryDSL\Interpret\Visitors\Nodes
 * @property \Trunda\QueryDSL\Interpret\Visitors\BuildQuery $parent
 */
class InConditionNode extends ConditionNode
{
    public function visit()
    {
        $this->visitChildren();

        $this->parent->getBuilder()
            ->whereIn(
                $this->getField(),
                $this[1]->getData(),
                $this->parent->getBool(),
                $this->getData('negate', false)
            );

        return $this->node;
    }
}