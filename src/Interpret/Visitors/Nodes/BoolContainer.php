<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

use Illuminate\Database\Eloquent\Builder;

/**
 * Class BoolContainer
 * @package Trunda\QueryDSL\Interpret\Visitors\Nodes
 * @property \Trunda\QueryDSL\Interpret\Visitors\BuildQuery $parent
 */
abstract class BoolContainer extends Node
{
    protected $bool;

    public function visit()
    {
        $this->parent->getBuilder()->where(function (Builder $nestedBuilder) {
            $this->parent->swap(['builder' => $nestedBuilder, 'bool' => $this->bool]);
            $this->visitChildren();
            $this->parent->renew();
        }, null, null, $this->parent->getBool());

        return $this->node;
    }
}