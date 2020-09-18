<?php


namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

use Illuminate\Support\Str;

class OpLikeToken extends Node
{
    public function visit()
    {
        return $this->setData(Str::upper($this->getData()));
    }

}