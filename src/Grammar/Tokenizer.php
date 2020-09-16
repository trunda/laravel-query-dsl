<?php

namespace Trunda\QueryDSL\Grammar;

class Tokenizer extends \Nette\Tokenizer\Tokenizer
{
    const T_WHITESPACE = 'T_WHITESPACE';
    const T_IS_EQUAL = 'T_IS_EQUAL';
    const T_IS_NOT_EQUAL = 'T_IS_NOT_EQUAL';
    const T_IS_GREATER_OR_EQUAL = 'T_IS_GREATER_OR_EQUAL';
    const T_IS_SMALLER_OR_EQUAL = 'T_IS_SMALLER_OR_EQUAL';
    const T_IS_GREATER = 'T_IS_GREATER';
    const T_IS_SMALLER = 'T_IS_SMALLER';
    const T_INT_NUM = 'T_INT_NUM';
    const T_APPROX_NUM = 'T_APROX_NUM';
    const T_BOOLEAN_AND = 'T_BOOLEAN_AND';
    const T_BOOLEAN_OR = 'T_BOOLEAN_OR';
    const T_BRACKET_LEFT = 'T_BRACKET_LEFT';
    const T_BRACKET_RIGHT = 'T_BRACKET_RIGHT';
    const T_NAME = 'T_NAME';
    const T_NOT = 'T_NOT';
    const T_BOOL_TRUE = 'T_BOOL_TRUE';
    const T_BOOL_FALSE = 'T_BOOL_FALSE';
    const T_NULL = 'T_NULL';
    const T_COMMA = 'T_COMMA';
    const T_STRING = 'T_STRING';

    public function __construct()
    {
        parent::__construct([
            self::T_WHITESPACE          => '[ \r\t\n]+',
            self::T_IS_EQUAL            => '=',
            self::T_IS_NOT_EQUAL        => '!=',
            self::T_IS_GREATER_OR_EQUAL => '>=',
            self::T_IS_SMALLER_OR_EQUAL => '<=',
            self::T_IS_GREATER          => '>',
            self::T_IS_SMALLER          => '<',
            self::T_BOOLEAN_AND         => '&&',
            self::T_BOOLEAN_OR          => '\|\|',
            self::T_BRACKET_LEFT        => '\(',
            self::T_BRACKET_RIGHT       => '\)',
            self::T_NOT                 => '\!',
            self::T_BOOL_TRUE           => '(?i:true)',
            self::T_BOOL_FALSE          => '(?i:false)',
            self::T_NULL                => '(?i:null)',
            self::T_COMMA               => ',',
            self::T_APPROX_NUM          => '[+-]?' .
                '(?:[0-9]+(?i:e)[-+]?[0-9]+)|' .
                '(?:[0-9]+\.[0-9]*+(?i:e)[-+]?[0-9]+)|' .
                '(?:[0-9]+\.[0-9]*)|' .
                '(?:\.[0-9]*(?i:e)[-+]?[0-9]+)|' .
                '(?:\.[0-9]+)',
            self::T_STRING              => '(?:"(?:(?!")[^\\\]|\\\.)*")|' .
                "(?:'(?:(?!')[^\\\]|\\\.)*')",
            self::T_INT_NUM             => '[+-]?[0-9]+',
            self::T_NAME                => '[a-zA-Z0-9_][a-zA-Z0-9._]*',
        ]);
    }
}
