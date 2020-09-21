<?php

namespace Trunda\QueryDSL\Parse;

use Hoa\Compiler\Llk\Llk;
use Hoa\Compiler\Llk\Parser;
use Hoa\Compiler\Llk\TreeNode;
use Hoa\File\Read;
use Illuminate\Support\Collection;

class Compiler
{
    protected ?Parser $parser = null;

    public function lex($input): Collection
    {
        return collect($this->getParser()->getTokens());
    }

    public function parse(string $input): TreeNode
    {
        return $this->getParser()->parse($input);
    }

    public function getParser(): Parser
    {
        if (!$this->parser) {
            $this->parser = $this->buildParser();
        }

        return $this->parser;
    }

    public function buildParser()
    {
        if (class_exists($className = __NAMESPACE__ . '\\Parser')) {
            return new $className();
        }

        return $this->loadParser();
    }

    public function saveParser()
    {
        $res = "<?php\n\n";
        $res .= 'namespace ' . __NAMESPACE__ . ";\n\n";
        $res .= Llk::save($this->loadParser(), 'Parser');

        file_put_contents(__DIR__ . '/Parser.php', $res);
    }

    public function loadParser(): \Hoa\Compiler\Llk\Parser
    {
        return Llk::load(new Read(__DIR__ . '/Grammar.pp'));
    }
}
