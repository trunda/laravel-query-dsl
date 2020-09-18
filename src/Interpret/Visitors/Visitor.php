<?php

namespace Trunda\QueryDSL\Interpret\Visitors;

use Hoa\Compiler\Llk\TreeNode;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

abstract class Visitor
{
    public function visit(TreeNode $node)
    {
        $method = 'visit' . ucfirst($this->methodOrClassName($node));

        if (method_exists($this, $method)) {
            return call_user_func([$this, $method], $node);
        }

        $this->visitChildren($node);

        return $node;
    }

    protected function methodOrClassName(TreeNode $node)
    {
        $name = $node->getId();
        if (Str::startsWith($name, '#')) {
            return Str::substr($name, 1) . 'Node';
        } elseif ($name === 'token') {
            return Str::camel(Str::lower($node->getValueToken())) . 'Token';
        }
    }

    public function visitChildren(TreeNode $node)
    {
        $node->setChildren(collect($node->getChildren())->map([$this, 'visit'])->toArray());

        return $node;
    }

    public function setData(TreeNode $node, $value, $name = 'value')
    {
        $node->getData()[$name] = $value;

        return $node;
    }

    public function getData(TreeNode $node, $name = 'value', $default = '____$value____')
    {
        if ($default === '____$value____') {
            $default = $node->getValueValue();
        }

        return Arr::get($node->getData(), $name, $default);
    }

    public function setTokenValue(TreeNode $node, $value)
    {
        $data = $node->getValue();
        $data['value'] = $value;
        $node->setValue($data);

        return $node;
    }
}
