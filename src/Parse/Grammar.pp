%skip   SPACE \s


%token AND \&\&
%token OR \|\|
%token NOT \!

%token  LB \(
%token  RB \)

%token TERM (?i:true)|(?i:false)

expression:
    and() (::OR:: #or expression())?

and:
    term() (::AND:: #and expression())?

#not:
    ::NOT:: expression()

nested:
    ::LB:: expression() ::RB::

term:
       <TERM>
       | not()
       | nested()
