<?php

namespace Trunda\QueryDSL\Interpret\Visitors\Nodes;

use Hoa\Compiler\Llk\TreeNode;
use Illuminate\Contracts\Support\Arrayable;
use Trunda\QueryDSL\Interpret\Visitors\Visitor;

class Node implements \ArrayAccess, Arrayable
{
    /** @var TreeNode */
    public $node;

    /** @var Visitor */
    protected $parent;

    /**
     * Node constructor.
     * @param \Hoa\Compiler\Llk\TreeNode $node
     * @param \Trunda\QueryDSL\Interpret\Visitor $parent
     */
    public function __construct(TreeNode $node, Visitor $parent)
    {
        $this->node = $node;
        $this->parent = $parent;
    }


    public function visit()
    {
        return $this->visitChildren();
    }

    public function setData($value, $name = 'value')
    {
        return $this->parent->setData($this->node, $value, $name);
    }

    public function getData($name = 'value', $default = '____$value____')
    {
        return $this->parent->getData($this->node, $name, $default);
    }

    protected function visitChildren()
    {
        $this->parent->visitChildren($this->node);

        return $this->node;
    }

    public function offsetExists($offset)
    {
        return $offset < $this->node->getChildrenNumber();
    }

    public function offsetGet($offset)
    {
        return new self($this->node->getChild($offset), $this->parent);
    }

    public function offsetSet($offset, $value)
    {
        throw new \Exception('Cannot do that');
    }

    public function offsetUnset($offset)
    {
        throw new \Exception('Cannot do that');
    }

    public function getTokenValue()
    {
        return $this->node->getValueValue();
    }

    public function setTokenValue($value)
    {
        return $this->parent->setTokenValue($this->node, $value);
    }

    public function toArray()
    {
        return array_map(function ($node) {
            return new self($node, $this->parent);
        }, $this->node->getChildren());
    }
}