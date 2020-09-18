<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

/**
 * Class ConditionNode
 * @package Trunda\QueryDSL\Interpret\Visitors\Nodes
 * @property \Trunda\QueryDSL\Interpret\Visitors\BuildQuery $parent
 *
 * >  >  >  #condition
 * >  >  >  >  #field
 * >  >  >  >  >  token(TERM, d)
 * >  >  >  >  token(OP_EQ, =)
 * >  >  >  >  #field
 * >  >  >  >  >  token(TERM, e)
 */
class ConditionNode extends Node
{
    public function getField()
    {
        return $this[0][0]->getData();
    }

    public function getOperator()
    {
        return $this[1]->getData();
    }

    public function getVal()
    {
        return $this[2]->getData();
    }

    public function visit()
    {
        $this->visitChildren();

        $this->parent->getBuilder()->where(
            $this->getField(),
            $this->getOperator(),
            $this->getVal(),
            $this->parent->getBool()
        );

        return $this->node;
    }
}