<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

class NullToken extends Node
{
    public function visit()
    {
        return $this->setData(null);
    }

}