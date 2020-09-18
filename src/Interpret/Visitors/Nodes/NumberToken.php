<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

class NumberToken extends Node
{
    public function visit()
    {
        return $this->setData(intval($this->getTokenValue()));
    }
}