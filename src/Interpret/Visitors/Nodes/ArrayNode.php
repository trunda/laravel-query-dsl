<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

class ArrayNode extends Node
{
    public function visit()
    {
        $this->visitChildren();

        $this->setData(collect($this)->map->getData());

        return $this->node;
    }

}