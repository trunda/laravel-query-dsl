<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

class StringToken extends Node
{
    public function visit()
    {
        return $this->setData(trim($this->getTokenValue(), "\"'"));
    }
}