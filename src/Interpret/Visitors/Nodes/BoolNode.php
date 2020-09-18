<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

/**
 * Class BoolNode
 * @package Trunda\QueryDSL\Interpret\Visitors\Nodes
 * >  #bool
 * >  >  token(TRUE, true)
 *
 * %token TRUE true|TRUE
 */
class BoolNode extends Node
{
    public function visit()
    {
        return $this->setData(filter_var($this[0]->getTokenValue(), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE));
    }
}