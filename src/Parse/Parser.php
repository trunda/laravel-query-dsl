<?php

namespace Trunda\QueryDSL\Parse;

class Parser extends \Hoa\Compiler\Llk\Parser
{
    public function __construct()
    {
        parent::__construct(
            [
                'default' => [
                    'skip' => '\s',
                    'AND' => '\&\&|AND|and',
                    'OR' => '\|\||OR|or',
                    'NULL' => 'null|NULL',
                    'TRUE' => 'true|TRUE',
                    'FALSE' => 'false|FALSE',
                    'LB' => '\(',
                    'RB' => '\)',
                    'LA' => '\[',
                    'RA' => '\]',
                    'COM' => ',',
                    'DOT' => '\.',
                    'COL' => '\:',
                    'AT' => '@',
                    'OP_ACC' => '->',
                    'OP_GTE' => '>=',
                    'OP_LTE' => '<=',
                    'OP_NEQ' => '!=',
                    'OP_EQ' => '=',
                    'OP_GT' => '>',
                    'OP_LT' => '<',
                    'OP_IN' => 'IN|in',
                    'OP_LIKE' => 'LIKE|like',
                    'STRING' => '"((?!")[^\\\]|\\\.)*"|\'((?!\')[^\\\]|\\\.)*\'',
                    'APPROX_NUMBER' => '(?:[+-]?[0-9]+(?i:e)[-+]?[0-9]+)|(?:[+-]?[0-9]+\.[0-9]*+(?i:e)[-+]?[0-9]+)|(?:[+-]?[0-9]+\.[0-9]*)|(?:[+-]?\.[0-9]*(?i:e)[-+]?[0-9]+)|(?:[+-]?\.[0-9]+)',
                    'NUMBER' => '[+-]?[0-9]+',
                    'NOT' => '\!|NOT|not',
                    'TERM' => '[^\s:><="\\\'\(\)\.,\[\]]+',
                    'FUNCTION_NAME' => '@[a-zA-Z][a-zA-Z0-9\._]*',
                ],
            ],
            [
                'expression' => new \Hoa\Compiler\Llk\Rule\Concatenation('expression', ['or'], null),
                1 => new \Hoa\Compiler\Llk\Rule\Token(1, 'OR', null, -1, false),
                2 => new \Hoa\Compiler\Llk\Rule\Concatenation(2, [1, 'and'], '#or'),
                3 => new \Hoa\Compiler\Llk\Rule\Repetition(3, 0, -1, 2, null),
                'or' => new \Hoa\Compiler\Llk\Rule\Concatenation('or', ['and', 3], null),
                5 => new \Hoa\Compiler\Llk\Rule\Token(5, 'AND', null, -1, false),
                6 => new \Hoa\Compiler\Llk\Rule\Concatenation(6, [5, 'term'], '#and'),
                7 => new \Hoa\Compiler\Llk\Rule\Repetition(7, 0, -1, 6, null),
                'and' => new \Hoa\Compiler\Llk\Rule\Concatenation('and', ['term', 7], null),
                'term' => new \Hoa\Compiler\Llk\Rule\Choice('term', ['nested', 'not', 'query', 'relationalNestedQuery', 'relationalQuery', 'scopeQuery'], null),
                10 => new \Hoa\Compiler\Llk\Rule\Token(10, 'NOT', null, -1, false),
                'not' => new \Hoa\Compiler\Llk\Rule\Concatenation('not', [10, 'expression'], '#not'),
                12 => new \Hoa\Compiler\Llk\Rule\Token(12, 'LB', null, -1, false),
                13 => new \Hoa\Compiler\Llk\Rule\Token(13, 'RB', null, -1, false),
                'nested' => new \Hoa\Compiler\Llk\Rule\Concatenation('nested', [12, 'expression', 13], '#nested'),
                15 => new \Hoa\Compiler\Llk\Rule\Concatenation(15, ['operator', 'conditional'], null),
                16 => new \Hoa\Compiler\Llk\Rule\Token(16, 'OP_EQ', null, -1, true),
                17 => new \Hoa\Compiler\Llk\Rule\Token(17, 'OP_NEQ', null, -1, true),
                18 => new \Hoa\Compiler\Llk\Rule\Choice(18, [16, 17], null),
                19 => new \Hoa\Compiler\Llk\Rule\Concatenation(19, [18, 'equalityScalar'], null),
                20 => new \Hoa\Compiler\Llk\Rule\Token(20, 'OP_IN', null, -1, true),
                21 => new \Hoa\Compiler\Llk\Rule\Concatenation(21, [20, 'array'], null),
                'conditionalParts' => new \Hoa\Compiler\Llk\Rule\Choice('conditionalParts', [15, 19, 21], null),
                23 => new \Hoa\Compiler\Llk\Rule\Concatenation(23, ['field', 'conditionalParts'], '#query'),
                24 => new \Hoa\Compiler\Llk\Rule\Concatenation(24, ['field'], '#soloQuery'),
                'query' => new \Hoa\Compiler\Llk\Rule\Choice('query', [23, 24], null),
                26 => new \Hoa\Compiler\Llk\Rule\Token(26, 'AT', null, -1, false),
                27 => new \Hoa\Compiler\Llk\Rule\Concatenation(27, ['field'], '#scopeQuery'),
                28 => new \Hoa\Compiler\Llk\Rule\Concatenation(28, ['nestedField'], '#scopeQuery'),
                29 => new \Hoa\Compiler\Llk\Rule\Choice(29, [27, 28], null),
                30 => new \Hoa\Compiler\Llk\Rule\Token(30, 'LB', null, -1, false),
                31 => new \Hoa\Compiler\Llk\Rule\Repetition(31, 0, 1, 'arguments', null),
                32 => new \Hoa\Compiler\Llk\Rule\Token(32, 'RB', null, -1, false),
                'scopeQuery' => new \Hoa\Compiler\Llk\Rule\Concatenation('scopeQuery', [26, 29, 30, 31, 32], null),
                34 => new \Hoa\Compiler\Llk\Rule\Token(34, 'COL', null, -1, false),
                35 => new \Hoa\Compiler\Llk\Rule\Token(35, 'LB', null, -1, false),
                36 => new \Hoa\Compiler\Llk\Rule\Token(36, 'RB', null, -1, false),
                37 => new \Hoa\Compiler\Llk\Rule\Concatenation(37, [34, 35, 'expression', 36], '#relationalNestedQuery'),
                38 => new \Hoa\Compiler\Llk\Rule\Repetition(38, 0, 1, 37, null),
                39 => new \Hoa\Compiler\Llk\Rule\Token(39, 'NUMBER', null, -1, true),
                40 => new \Hoa\Compiler\Llk\Rule\Concatenation(40, ['compareOperator', 39], null),
                41 => new \Hoa\Compiler\Llk\Rule\Repetition(41, 0, 1, 40, null),
                'relationalNestedQuery' => new \Hoa\Compiler\Llk\Rule\Concatenation('relationalNestedQuery', ['field', 38, 41], null),
                43 => new \Hoa\Compiler\Llk\Rule\Concatenation(43, ['nestedField', 'conditionalParts'], '#relationalQuery'),
                44 => new \Hoa\Compiler\Llk\Rule\Concatenation(44, ['nestedField'], '#soloQuery'),
                'relationalQuery' => new \Hoa\Compiler\Llk\Rule\Choice('relationalQuery', [43, 44], null),
                46 => new \Hoa\Compiler\Llk\Rule\Concatenation(46, ['field'], '#conditionalField'),
                'conditional' => new \Hoa\Compiler\Llk\Rule\Choice('conditional', ['conditionScalar', 'function', 46], null),
                48 => new \Hoa\Compiler\Llk\Rule\Token(48, 'TERM', null, -1, true),
                'field' => new \Hoa\Compiler\Llk\Rule\Concatenation('field', [48], '#field'),
                50 => new \Hoa\Compiler\Llk\Rule\Token(50, 'TERM', null, -1, true),
                51 => new \Hoa\Compiler\Llk\Rule\Token(51, 'DOT', null, -1, false),
                52 => new \Hoa\Compiler\Llk\Rule\Token(52, 'TERM', null, -1, true),
                53 => new \Hoa\Compiler\Llk\Rule\Concatenation(53, [51, 52], '#nestedField'),
                54 => new \Hoa\Compiler\Llk\Rule\Repetition(54, 1, -1, 53, null),
                'nestedField' => new \Hoa\Compiler\Llk\Rule\Concatenation('nestedField', [50, 54], null),
                56 => new \Hoa\Compiler\Llk\Rule\Token(56, 'OP_GTE', null, -1, true),
                57 => new \Hoa\Compiler\Llk\Rule\Token(57, 'OP_LTE', null, -1, true),
                58 => new \Hoa\Compiler\Llk\Rule\Token(58, 'OP_EQ', null, -1, true),
                59 => new \Hoa\Compiler\Llk\Rule\Token(59, 'OP_GT', null, -1, true),
                60 => new \Hoa\Compiler\Llk\Rule\Token(60, 'OP_LT', null, -1, true),
                61 => new \Hoa\Compiler\Llk\Rule\Token(61, 'OP_NEQ', null, -1, true),
                62 => new \Hoa\Compiler\Llk\Rule\Token(62, 'OP_LIKE', null, -1, true),
                'operator' => new \Hoa\Compiler\Llk\Rule\Choice('operator', [56, 57, 58, 59, 60, 61, 62], null),
                64 => new \Hoa\Compiler\Llk\Rule\Token(64, 'OP_GTE', null, -1, true),
                65 => new \Hoa\Compiler\Llk\Rule\Token(65, 'OP_LTE', null, -1, true),
                66 => new \Hoa\Compiler\Llk\Rule\Token(66, 'OP_EQ', null, -1, true),
                67 => new \Hoa\Compiler\Llk\Rule\Token(67, 'OP_GT', null, -1, true),
                68 => new \Hoa\Compiler\Llk\Rule\Token(68, 'OP_LT', null, -1, true),
                69 => new \Hoa\Compiler\Llk\Rule\Token(69, 'OP_NEQ', null, -1, true),
                'compareOperator' => new \Hoa\Compiler\Llk\Rule\Choice('compareOperator', [64, 65, 66, 67, 68, 69], null),
                71 => new \Hoa\Compiler\Llk\Rule\Token(71, 'STRING', null, -1, true),
                72 => new \Hoa\Compiler\Llk\Rule\Token(72, 'NUMBER', null, -1, true),
                73 => new \Hoa\Compiler\Llk\Rule\Token(73, 'APPROX_NUMBER', null, -1, true),
                74 => new \Hoa\Compiler\Llk\Rule\Token(74, 'NULL', null, -1, true),
                'scalar' => new \Hoa\Compiler\Llk\Rule\Choice('scalar', [71, 72, 73, 'array', 'bool', 74], null),
                76 => new \Hoa\Compiler\Llk\Rule\Token(76, 'STRING', null, -1, true),
                77 => new \Hoa\Compiler\Llk\Rule\Token(77, 'NUMBER', null, -1, true),
                78 => new \Hoa\Compiler\Llk\Rule\Token(78, 'APPROX_NUMBER', null, -1, true),
                'conditionScalar' => new \Hoa\Compiler\Llk\Rule\Choice('conditionScalar', [76, 77, 78], null),
                80 => new \Hoa\Compiler\Llk\Rule\Token(80, 'NULL', null, -1, true),
                'equalityScalar' => new \Hoa\Compiler\Llk\Rule\Choice('equalityScalar', ['array', 'bool', 80], null),
                82 => new \Hoa\Compiler\Llk\Rule\Token(82, 'TRUE', null, -1, true),
                83 => new \Hoa\Compiler\Llk\Rule\Concatenation(83, [82], '#bool'),
                84 => new \Hoa\Compiler\Llk\Rule\Token(84, 'FALSE', null, -1, true),
                85 => new \Hoa\Compiler\Llk\Rule\Concatenation(85, [84], '#bool'),
                'bool' => new \Hoa\Compiler\Llk\Rule\Choice('bool', [83, 85], null),
                87 => new \Hoa\Compiler\Llk\Rule\Token(87, 'LA', null, -1, false),
                88 => new \Hoa\Compiler\Llk\Rule\Token(88, 'COM', null, -1, false),
                89 => new \Hoa\Compiler\Llk\Rule\Concatenation(89, [88, 'scalar'], '#array'),
                90 => new \Hoa\Compiler\Llk\Rule\Repetition(90, 0, -1, 89, null),
                91 => new \Hoa\Compiler\Llk\Rule\Concatenation(91, ['scalar', 90], null),
                92 => new \Hoa\Compiler\Llk\Rule\Repetition(92, 0, 1, 91, null),
                93 => new \Hoa\Compiler\Llk\Rule\Token(93, 'RA', null, -1, false),
                'array' => new \Hoa\Compiler\Llk\Rule\Concatenation('array', [87, 92, 93], null),
                'string' => new \Hoa\Compiler\Llk\Rule\Token('string', 'STRING', null, -1, true),
                96 => new \Hoa\Compiler\Llk\Rule\Token(96, 'TERM', null, -1, true),
                97 => new \Hoa\Compiler\Llk\Rule\Token(97, 'LB', null, -1, false),
                98 => new \Hoa\Compiler\Llk\Rule\Repetition(98, 0, 1, 'arguments', null),
                99 => new \Hoa\Compiler\Llk\Rule\Token(99, 'RB', null, -1, false),
                100 => new \Hoa\Compiler\Llk\Rule\Repetition(100, 0, 1, 'obj', null),
                'function' => new \Hoa\Compiler\Llk\Rule\Concatenation('function', [96, 97, 98, 99, 100], '#function'),
                102 => new \Hoa\Compiler\Llk\Rule\Token(102, 'COM', null, -1, false),
                103 => new \Hoa\Compiler\Llk\Rule\Concatenation(103, [102, 'argument'], '#arguments'),
                104 => new \Hoa\Compiler\Llk\Rule\Repetition(104, 0, -1, 103, null),
                'arguments' => new \Hoa\Compiler\Llk\Rule\Concatenation('arguments', ['argument', 104], null),
                'argument' => new \Hoa\Compiler\Llk\Rule\Choice('argument', ['scalar', 'function'], null),
                107 => new \Hoa\Compiler\Llk\Rule\Repetition(107, 0, 1, 'obj', null),
                108 => new \Hoa\Compiler\Llk\Rule\Concatenation(108, ['arrayAccess', 107], null),
                109 => new \Hoa\Compiler\Llk\Rule\Repetition(109, 0, 1, 'obj', null),
                110 => new \Hoa\Compiler\Llk\Rule\Concatenation(110, ['propertyAccess', 109], null),
                111 => new \Hoa\Compiler\Llk\Rule\Repetition(111, 0, 1, 'obj', null),
                112 => new \Hoa\Compiler\Llk\Rule\Concatenation(112, ['method', 111], null),
                'obj' => new \Hoa\Compiler\Llk\Rule\Choice('obj', [108, 110, 112], null),
                114 => new \Hoa\Compiler\Llk\Rule\Token(114, 'OP_ACC', null, -1, false),
                115 => new \Hoa\Compiler\Llk\Rule\Token(115, 'TERM', null, -1, true),
                116 => new \Hoa\Compiler\Llk\Rule\Token(116, 'LB', null, -1, false),
                117 => new \Hoa\Compiler\Llk\Rule\Repetition(117, 0, 1, 'arguments', null),
                118 => new \Hoa\Compiler\Llk\Rule\Token(118, 'RB', null, -1, false),
                119 => new \Hoa\Compiler\Llk\Rule\Concatenation(119, [116, 117, 118], '#method'),
                120 => new \Hoa\Compiler\Llk\Rule\Repetition(120, 0, 1, 119, null),
                'method' => new \Hoa\Compiler\Llk\Rule\Concatenation('method', [114, 115, 120], null),
                122 => new \Hoa\Compiler\Llk\Rule\Token(122, 'LA', null, -1, false),
                123 => new \Hoa\Compiler\Llk\Rule\Token(123, 'NUMBER', null, -1, true),
                124 => new \Hoa\Compiler\Llk\Rule\Concatenation(124, [123], '#arrayAccess'),
                125 => new \Hoa\Compiler\Llk\Rule\Token(125, 'STRING', null, -1, true),
                126 => new \Hoa\Compiler\Llk\Rule\Concatenation(126, [125], '#arrayAccess'),
                127 => new \Hoa\Compiler\Llk\Rule\Choice(127, [124, 126], null),
                128 => new \Hoa\Compiler\Llk\Rule\Token(128, 'RA', null, -1, false),
                'arrayAccess' => new \Hoa\Compiler\Llk\Rule\Concatenation('arrayAccess', [122, 127, 128], null),
                130 => new \Hoa\Compiler\Llk\Rule\Token(130, 'OP_ACC', null, -1, false),
                131 => new \Hoa\Compiler\Llk\Rule\Token(131, 'TERM', null, -1, true),
                'propertyAccess' => new \Hoa\Compiler\Llk\Rule\Concatenation('propertyAccess', [130, 131], '#propertyAccess'),
            ],
            [
            ]
        );

        $this->getRule('expression')->setPPRepresentation(' or()');
        $this->getRule('or')->setPPRepresentation(' and() (::OR:: and() #or)*');
        $this->getRule('and')->setPPRepresentation(' term() (::AND:: term() #and)*');
        $this->getRule('term')->setPPRepresentation(' nested() | not() | query() | relationalNestedQuery() | relationalQuery() | scopeQuery()');
        $this->getRule('not')->setDefaultId('#not');
        $this->getRule('not')->setPPRepresentation(' ::NOT:: expression()');
        $this->getRule('nested')->setDefaultId('#nested');
        $this->getRule('nested')->setPPRepresentation(' ::LB:: expression() ::RB::');
        $this->getRule('conditionalParts')->setPPRepresentation(' operator() conditional() | (<OP_EQ>|<OP_NEQ>) equalityScalar() | <OP_IN> array()');
        $this->getRule('query')->setDefaultId('#query');
        $this->getRule('query')->setPPRepresentation(' field() conditionalParts() | field() #soloQuery');
        $this->getRule('scopeQuery')->setDefaultId('#scopeQuery');
        $this->getRule('scopeQuery')->setPPRepresentation(' ::AT:: (field()|nestedField()) ::LB:: arguments()? ::RB::)?');
        $this->getRule('relationalNestedQuery')->setDefaultId('#relationalNestedQuery');
        $this->getRule('relationalNestedQuery')->setPPRepresentation(' field() (::COL:: ::LB:: expression() ::RB::)? (compareOperator() <NUMBER>)?');
        $this->getRule('relationalQuery')->setDefaultId('#relationalQuery');
        $this->getRule('relationalQuery')->setPPRepresentation(' nestedField() conditionalParts() | nestedField() #soloQuery');
        $this->getRule('conditional')->setPPRepresentation(' conditionScalar() | function() | field() #conditionalField');
        $this->getRule('field')->setPPRepresentation(' <TERM> #field');
        $this->getRule('nestedField')->setDefaultId('#nestedField');
        $this->getRule('nestedField')->setPPRepresentation(' <TERM> (::DOT:: <TERM>)+');
        $this->getRule('operator')->setPPRepresentation(' <OP_GTE> | <OP_LTE> | <OP_EQ> | <OP_GT> | <OP_LT> | <OP_NEQ> | <OP_LIKE>');
        $this->getRule('compareOperator')->setPPRepresentation(' <OP_GTE> | <OP_LTE> | <OP_EQ> | <OP_GT> | <OP_LT> | <OP_NEQ>');
        $this->getRule('scalar')->setPPRepresentation(' <STRING> | <NUMBER> | <APPROX_NUMBER> | array() | bool() | <NULL>');
        $this->getRule('conditionScalar')->setPPRepresentation(' <STRING> | <NUMBER> | <APPROX_NUMBER>');
        $this->getRule('equalityScalar')->setPPRepresentation(' array() | bool() | <NULL>');
        $this->getRule('bool')->setDefaultId('#bool');
        $this->getRule('bool')->setPPRepresentation(' <TRUE> | <FALSE>');
        $this->getRule('array')->setDefaultId('#array');
        $this->getRule('array')->setPPRepresentation(' ::LA:: (scalar() (::COM:: scalar())*)? ::RA::');
        $this->getRule('string')->setPPRepresentation(' <STRING>');
        $this->getRule('function')->setDefaultId('#function');
        $this->getRule('function')->setPPRepresentation(' <TERM> ::LB:: arguments()? ::RB:: obj()?');
        $this->getRule('arguments')->setDefaultId('#arguments');
        $this->getRule('arguments')->setPPRepresentation(' argument() (::COM:: argument())*');
        $this->getRule('argument')->setPPRepresentation(' scalar() | function()');
        $this->getRule('obj')->setPPRepresentation(' arrayAccess() obj()? | propertyAccess() obj()? | method() obj()?');
        $this->getRule('method')->setDefaultId('#method');
        $this->getRule('method')->setPPRepresentation(' ::OP_ACC:: <TERM> (::LB:: arguments()? ::RB::)?');
        $this->getRule('arrayAccess')->setDefaultId('#arrayAccess');
        $this->getRule('arrayAccess')->setPPRepresentation(' ::LA:: (<NUMBER> | <STRING>) ::RA::');
        $this->getRule('propertyAccess')->setDefaultId('#propertyAccess');
        $this->getRule('propertyAccess')->setPPRepresentation(' ::OP_ACC:: <TERM>');
    }
}
