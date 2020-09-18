%skip   SPACE \s


%token AND \&\&|AND|and
%token OR \|\||OR|or
%token NULL null|NULL
%token TRUE true|TRUE
%token FALSE false|FALSE

%token  LB \(
%token  RB \)

%token  LA \[
%token  RA \]
%token  COM ,
%token  DOT \.
%token  COL \:

%token OP_ACC ->
%token OP_GTE >=
%token OP_LTE <=
%token OP_NEQ !=
%token OP_EQ =
%token OP_GT >
%token OP_LT <
%token OP_IN IN|in
%token OP_LIKE LIKE|like

%token STRING "((?!")[^\\]|\\.)*"|'((?!')[^\\]|\\.)*'
%token APPROX_NUMBER (?:[+-]?[0-9]+(?i:e)[-+]?[0-9]+)|(?:[+-]?[0-9]+\.[0-9]*+(?i:e)[-+]?[0-9]+)|(?:[+-]?[0-9]+\.[0-9]*)|(?:[+-]?\.[0-9]*(?i:e)[-+]?[0-9]+)|(?:[+-]?\.[0-9]+)
%token NUMBER [+-]?[0-9]+

%token NOT \!|NOT|not

%token SCOPE @[^\s:><="\'\(\)\.,\[\]]+
%token TERM [^\s:><="\'\(\)\.,\[\]]+
%token FUNCTION_NAME @[a-zA-Z][a-zA-Z0-9\._]*

expression:
    or()

or:
    and() (::OR:: and() #or)*

and:
    term() (::AND:: term() #and)*

#not:
    ::NOT:: expression()

#nested:
    ::LB:: expression() ::RB::

#condition:
    field() operator() conditional()
    | field() (<OP_EQ>|<OP_NEQ>) equalityScalar()
    | field() in() #inCondition
    | field() #soloCondition
    | <SCOPE> #scope (::LB:: arguments() ::RB::)?
    | relationalCondition() #relationalCondition


relationalCondition:
    field() (::COL:: ::LB:: expression() ::RB::)? (compareOperator() <NUMBER>)?

in:
    ::OP_IN:: array()

conditional:
    conditionScalar()
    | function()
    | field() #conditionalField

field:
    <TERM> #field

#nestedField:
    <TERM> (::DOT:: <TERM>)+

operator:
    <OP_GTE> | <OP_LTE> | <OP_EQ> | <OP_GT> | <OP_LT> | <OP_NEQ> | <OP_LIKE>

compareOperator:
    <OP_GTE> | <OP_LTE> | <OP_EQ> | <OP_GT> | <OP_LT> | <OP_NEQ>

scalar:
    <STRING> | <NUMBER> | <APPROX_NUMBER> | array() | bool() | <NULL>

conditionScalar:
    <STRING> | <NUMBER> | <APPROX_NUMBER>

equalityScalar:
    array() | bool() | <NULL>

#bool:
    <TRUE> | <FALSE>

#array:
    ::LA:: (scalar() (::COM:: scalar())*)? ::RA::

string:
    <STRING>

#function:
    <TERM> ::LB:: arguments()? ::RB:: obj()?

#arguments:
    argument() (::COM:: argument())*

argument:
    scalar()
    | function()

obj:
    arrayAccess() obj()?
    | propertyAccess() obj()?
    | method() obj()?

#method:
    ::OP_ACC:: <TERM> (::LB:: arguments()? ::RB::)?

#arrayAccess:
    ::LA:: (<NUMBER> | <STRING>) ::RA::

#propertyAccess:
    ::OP_ACC:: <TERM>

term:
    nested()
    | not()
    | condition()
