<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

class OrNode extends BoolContainer
{
    protected $bool = 'or';
}