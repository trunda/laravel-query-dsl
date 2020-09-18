<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

class ApproxNumberToken extends Node
{
    public function visit()
    {
        return $this->setData(floatval($this->getTokenValue()));
    }

}