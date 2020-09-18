<?php

namespace Trunda\QueryDSL\Interpret\Visitors;

use Hoa\Compiler\Llk\TreeNode;
use Illuminate\Database\Eloquent\Builder;

class BuildQuery extends Visitor
{
    /** @var \Illuminate\Database\Query\Builder */
    protected $builder;
    protected $bool;

    protected $originals = [];

    /**
     * BuildQuery constructor.
     *
     * @param $builder
     */
    public function __construct(Builder $builder, $bool = 'and')
    {
        $this->builder = $builder;
        $this->bool = $bool;
    }

    public function visit(TreeNode $node)
    {
        $name = 'Trunda\\QueryDSL\\Interpret\\Visitors\\Nodes\\' . ucfirst($this->methodOrClassName($node));

        if (class_exists($name)) {
            return (new $name($node, $this))
                ->visit();
        }

        $this->visitChildren($node);
        return $node;
    }

    public function swap(array $values)
    {
        $stack = [];
        foreach ($values as $name => $value) {
            $stack[$name] = $this->$name;
            $this->$name = $value;
        }
        $this->originals[] = $stack;
    }

    public function renew()
    {
        $stack = array_pop($this->originals);
        foreach ($stack as $name => $value) {
            $this->$name = $value;
        }
    }


    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * @return string
     */
    public function getBool()
    {
        return $this->bool;
    }
}
