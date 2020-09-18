<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

class AndNode extends BoolContainer
{
    protected $bool = 'and';
}