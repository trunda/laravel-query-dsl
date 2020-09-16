%skip   T_SPACE \s

%token T_AND &&
%token T_OR \|\|

%token  T_LP \(
%token  T_RP \)

%token T_TERM [a-zA-Z0-9_][a-zA-Z0-9._]*

#query:
    <T_TERM> and()
    | <T_TERM> or()
    | ::T_LP:: query() ::T_RP::

#and:
    (::T_AND:: query() and())?

#or:
    (::T_OR:: query() or())?