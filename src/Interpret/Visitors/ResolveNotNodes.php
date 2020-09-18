<?php

namespace Trunda\QueryDSL\Interpret\Visitors;

use Hoa\Compiler\Llk\TreeNode;
use Illuminate\Support\Arr;
use Trunda\QueryDSL\Interpret\Visitors\Nodes\Node;

class ResolveNotNodes extends Visitor
{
    protected $negate = false;

    public function visitNotNode(TreeNode $node)
    {
        $this->negate = !$this->negate;
        $this->visitChildren($node);
        $this->negate = false;

        return $node->getChild(0);
    }

    public function visitSoloConditionNode(TreeNode $node)
    {
        return $this->toggleNegateOnNode($node);
    }

    public function visitInConditionNode(TreeNode $node)
    {
        return $this->toggleNegateOnNode($node);
    }

    public function visitConditionNode(TreeNode $node)
    {
        if ($this->negate) {
            return (tap(new Node($node, $this), function (Node $node) {
                $node[1]->setTokenValue($this->reverseOperator($node[1]->getTokenValue()));
            }))->node;
        }

        return $node;
    }

    public function visitAndNode(TreeNode $node)
    {
        return $this->reverseBooleanCondition($node, ['#or', '#and']);
    }

    public function visitOrNode(TreeNode $node)
    {
        return $this->reverseBooleanCondition($node, ['#and', '#or']);
    }

    protected function toggleNegateOnNode(TreeNode $node)
    {
        if ($this->negate) {
            $this->setData(
                $node,
                !$this->getData($node, 'negate', false),
                'negate'
            );
        }

        return $node;
    }

    protected function reverseBooleanCondition(TreeNode $node, array $bools)
    {
        $originalNegate = $this->negate;

        $node->setChildren(
            collect($node->getChildren())->map(function (TreeNode $node) use ($originalNegate) {
                $this->negate = $originalNegate;
                return $this->visit($node);
            })->toArray()
        );

        $this->negate = $originalNegate;

        $node->setId($this->negate ? $bools[0] : $bools[1]);

        return $node;
    }

    protected function reverseOperator($operator)
    {
        return Arr::get([
            '='    => '!=',
            '!='   => '=',
            '>'    => '<=',
            '>='   => '<',
            '<'    => '>=',
            '<='   => '>',
            'LIKE' => 'NOT LIKE',
            'like' => 'NOT LIKE',
        ], $operator, $operator);
    }
}