<?php

namespace Potherca\Scanner\Parser;

/**
 * @url http://php.net/manual/en/tokens.php
 */
class Token
{
    const T_ABSTRACT = 'T_ABSTRACT'; // "abstract" -- Class Abstraction (available since PHP 5.0.0)
    const T_AND_EQUAL = 'T_AND_EQUAL'; // "&=" -- assignment operators
    const T_ARRAY = 'T_ARRAY'; // "array()" -- array(), array syntax
    const T_ARRAY_CAST = 'T_ARRAY_CAST'; // "(array)" -- type-casting
    const T_AS = 'T_AS'; // "as" -- foreach
    const T_BAD_CHARACTER = 'T_BAD_CHARACTER'; // " " -- anything below ASCII 32 except \t (0x09), \n (0x0a) and \r (0x0d)
    const T_BOOLEAN_AND = 'T_BOOLEAN_AND'; // "&&" -- logical operators
    const T_BOOLEAN_OR = 'T_BOOLEAN_OR'; // "||" -- logical operators
    const T_BOOL_CAST = 'T_BOOL_CAST'; // "(bool) or (boolean)" -- type-casting
    const T_BREAK = 'T_BREAK'; // "break" -- break
    const T_CALLABLE = 'T_CALLABLE'; // "callable" -- callable
    const T_CASE = 'T_CASE'; // "case" -- switch
    const T_CATCH = 'T_CATCH'; // "catch" -- Exceptions (available since PHP 5.0.0)
    const T_CHARACTER = 'T_CHARACTER'; // " " -- not used anymore
    const T_CLASS = 'T_CLASS'; // "class" -- classes and objects
    const T_CLASS_C = 'T_CLASS_C'; // "__CLASS__" -- magic constants
    const T_CLONE = 'T_CLONE'; // "clone" -- classes and objects
    const T_CLOSE_TAG = 'T_CLOSE_TAG'; // "? >" or "% >" -- escaping from HTML
    const T_COMMENT = 'T_COMMENT'; // "// or #, and /* */" -- comments
    const T_CONCAT_EQUAL = 'T_CONCAT_EQUAL'; // ".=" -- assignment operators
    const T_CONST = 'T_CONST'; // "const" -- class constants
    const T_CONSTANT_ENCAPSED_STRING = 'T_CONSTANT_ENCAPSED_STRING'; // ""foo" or 'bar'" -- string syntax
    const T_CONTINUE = 'T_CONTINUE'; // "continue" -- continue
    const T_CURLY_OPEN = 'T_CURLY_OPEN'; // "{$" -- complex variable parsed syntax
    const T_DEC = 'T_DEC'; // "--" -- incrementing/decrementing operators
    const T_DECLARE = 'T_DECLARE'; // "declare" -- declare
    const T_DEFAULT = 'T_DEFAULT'; // "default" -- switch
    const T_DIR = 'T_DIR'; // "__DIR__" -- magic constants (available since PHP 5.3.0)
    const T_DIV_EQUAL = 'T_DIV_EQUAL'; // "/=" -- assignment operators
    const T_DNUMBER = 'T_DNUMBER'; // "0.12, etc." -- floating point numbers
    const T_DOC_COMMENT = 'T_DOC_COMMENT'; // "/** */" -- PHPDoc style comments
    const T_DO = 'T_DO'; // "do" -- do..while
    const T_DOLLAR_OPEN_CURLY_BRACES = 'T_DOLLAR_OPEN_CURLY_BRACES'; // "${" -- complex variable parsed syntax
    const T_DOUBLE_ARROW = 'T_DOUBLE_ARROW'; // "=>" -- array syntax
    const T_DOUBLE_CAST = 'T_DOUBLE_CAST'; // "(real), (double) or (float)" -- type-casting
    const T_DOUBLE_COLON = 'T_DOUBLE_COLON'; // "::" -- see T_PAAMAYIM_NEKUDOTAYIM below
    const T_ECHO = 'T_ECHO'; // "echo" -- echo
    const T_ELLIPSIS = 'T_ELLIPSIS'; // "..." -- function arguments (available since PHP 5.6.0)
    const T_ELSE = 'T_ELSE'; // "else" -- else
    const T_ELSEIF = 'T_ELSEIF'; // "elseif" -- elseif
    const T_EMPTY = 'T_EMPTY'; // "empty" -- empty()
    const T_ENCAPSED_AND_WHITESPACE = 'T_ENCAPSED_AND_WHITESPACE'; // "" $a"" -- constant part of string with variables
    const T_ENDDECLARE = 'T_ENDDECLARE'; // "enddeclare" -- declare, alternative syntax
    const T_ENDFOR = 'T_ENDFOR'; // "endfor" -- for, alternative syntax
    const T_ENDFOREACH = 'T_ENDFOREACH'; // "endforeach" -- foreach, alternative syntax
    const T_ENDIF = 'T_ENDIF'; // "endif" -- if, alternative syntax
    const T_ENDSWITCH = 'T_ENDSWITCH'; // "endswitch" -- switch, alternative syntax
    const T_ENDWHILE = 'T_ENDWHILE'; // "endwhile" -- while, alternative syntax
    const T_END_HEREDOC = 'T_END_HEREDOC'; // " " -- heredoc syntax
    const T_EVAL = 'T_EVAL'; // "eval()" -- eval()
    const T_EXIT = 'T_EXIT'; // "exit or die" -- exit(), die()
    const T_EXTENDS = 'T_EXTENDS'; // "extends" -- extends, classes and objects
    const T_FILE = 'T_FILE'; // "__FILE__" -- magic constants
    const T_FINAL = 'T_FINAL'; // "final" -- Final Keyword
    const T_FINALLY = 'T_FINALLY'; // "finally" -- Exceptions (available since PHP 5.5.0)
    const T_FOR = 'T_FOR'; // "for" -- for
    const T_FOREACH = 'T_FOREACH'; // "foreach" -- foreach
    const T_FUNCTION = 'T_FUNCTION'; // "function or cfunction" -- functions
    const T_FUNC_C = 'T_FUNC_C'; // "__FUNCTION__" -- magic constants
    const T_GLOBAL = 'T_GLOBAL'; // "global" -- variable scope
    const T_GOTO = 'T_GOTO'; // "goto" -- (available since PHP 5.3.0)
    const T_HALT_COMPILER = 'T_HALT_COMPILER'; // "__halt_compiler()" -- __halt_compiler (available since PHP 5.1.0)
    const T_IF = 'T_IF'; // "if" -- if
    const T_IMPLEMENTS = 'T_IMPLEMENTS'; // "implements" -- Object Interfaces
    const T_INC = 'T_INC'; // "++" -- incrementing/decrementing operators
    const T_INCLUDE = 'T_INCLUDE'; // "include()" -- include
    const T_INCLUDE_ONCE = 'T_INCLUDE_ONCE'; // "include_once()" -- include_once
    const T_INLINE_HTML = 'T_INLINE_HTML'; // " " -- text outside PHP
    const T_INSTANCEOF = 'T_INSTANCEOF'; // "instanceof" -- type operators
    const T_INSTEADOF = 'T_INSTEADOF'; // "insteadof" -- Traits (available since PHP 5.4.0)
    const T_INT_CAST = 'T_INT_CAST'; // "(int) or (integer)" -- type-casting
    const T_INTERFACE = 'T_INTERFACE'; // "interface" -- Object Interfaces
    const T_ISSET = 'T_ISSET'; // "isset()" -- isset()
    const T_IS_EQUAL = 'T_IS_EQUAL'; // "==" -- comparison operators
    const T_IS_GREATER_OR_EQUAL = 'T_IS_GREATER_OR_EQUAL'; // ">=" -- comparison operators
    const T_IS_IDENTICAL = 'T_IS_IDENTICAL'; // "===" -- comparison operators
    const T_IS_NOT_EQUAL = 'T_IS_NOT_EQUAL'; // "!= or <>" -- comparison operators
    const T_IS_NOT_IDENTICAL = 'T_IS_NOT_IDENTICAL'; // "!==" -- comparison operators
    const T_IS_SMALLER_OR_EQUAL = 'T_IS_SMALLER_OR_EQUAL'; // "<=" -- comparison operators
    const T_SPACESHIP = 'T_SPACESHIP'; // "<=>" -- comparison operators (available since PHP 7.0.0)
    const T_LINE = 'T_LINE'; // "__LINE__" -- magic constants
    const T_LIST = 'T_LIST'; // "list()" -- list()
    const T_LNUMBER = 'T_LNUMBER'; // "123, 012, 0x1ac, etc." -- integers
    const T_LOGICAL_AND = 'T_LOGICAL_AND'; // "and" -- logical operators
    const T_LOGICAL_OR = 'T_LOGICAL_OR'; // "or" -- logical operators
    const T_LOGICAL_XOR = 'T_LOGICAL_XOR'; // "xor" -- logical operators
    const T_METHOD_C = 'T_METHOD_C'; // "__METHOD__" -- magic constants
    const T_MINUS_EQUAL = 'T_MINUS_EQUAL'; // "-=" -- assignment operators
    const T_MOD_EQUAL = 'T_MOD_EQUAL'; // "%=" -- assignment operators
    const T_MUL_EQUAL = 'T_MUL_EQUAL'; // "*=" -- assignment operators
    const T_NAMESPACE = 'T_NAMESPACE'; // "namespace" -- namespaces (available since PHP 5.3.0)
    const T_NS_C = 'T_NS_C'; // "__NAMESPACE__" -- namespaces (available since PHP 5.3.0)
    const T_NS_SEPARATOR = 'T_NS_SEPARATOR'; // "\" -- namespaces (available since PHP 5.3.0)
    const T_NEW = 'T_NEW'; // "new" -- classes and objects
    const T_NUM_STRING = 'T_NUM_STRING'; // ""$a[0]"" -- numeric array index inside string
    const T_OBJECT_CAST = 'T_OBJECT_CAST'; // "(object)" -- type-casting
    const T_OBJECT_OPERATOR = 'T_OBJECT_OPERATOR'; // "->" -- classes and objects
    const T_OPEN_TAG = 'T_OPEN_TAG'; // "<?php, <? or <%" -- escaping from HTML
    const T_OPEN_TAG_WITH_ECHO = 'T_OPEN_TAG_WITH_ECHO'; // "<?= or <%=" -- escaping from HTML
    const T_OR_EQUAL = 'T_OR_EQUAL'; // "|=" -- assignment operators
    const T_PAAMAYIM_NEKUDOTAYIM = 'T_PAAMAYIM_NEKUDOTAYIM'; // "::" -- ::. Also defined as T_DOUBLE_COLON.
    const T_PLUS_EQUAL = 'T_PLUS_EQUAL'; // "+=" -- assignment operators
    const T_POW = 'T_POW'; // "**" -- arithmetic operators (available since PHP 5.6.0)
    const T_POW_EQUAL = 'T_POW_EQUAL'; // "**=" -- assignment operators (available since PHP 5.6.0)
    const T_PRINT = 'T_PRINT'; // "print()" -- print
    const T_PRIVATE = 'T_PRIVATE'; // "private" -- classes and objects
    const T_PUBLIC = 'T_PUBLIC'; // "public" -- classes and objects
    const T_PROTECTED = 'T_PROTECTED'; // "protected" -- classes and objects
    const T_REQUIRE = 'T_REQUIRE'; // "require()" -- require
    const T_REQUIRE_ONCE = 'T_REQUIRE_ONCE'; // "require_once()" -- require_once
    const T_RETURN = 'T_RETURN'; // "return" -- returning values
    const T_SL = 'T_SL'; // "<<" -- bitwise operators
    const T_SL_EQUAL = 'T_SL_EQUAL'; // "<<=" -- assignment operators
    const T_SR = 'T_SR'; // ">>" -- bitwise operators
    const T_SR_EQUAL = 'T_SR_EQUAL'; // ">>=" -- assignment operators
    const T_START_HEREDOC = 'T_START_HEREDOC'; // "<<<" -- heredoc syntax
    const T_STATIC = 'T_STATIC'; // "static" -- variable scope
    const T_STRING = 'T_STRING'; // "parent, self, etc." -- identifiers, e.g. keywords like parent and self, function names, class names and more are matched. See also T_CONSTANT_ENCAPSED_STRING.
    const T_STRING_CAST = 'T_STRING_CAST'; // "(string)" -- type-casting
    const T_STRING_VARNAME = 'T_STRING_VARNAME'; // ""${a" -- complex variable parsed syntax
    const T_SWITCH = 'T_SWITCH'; // "switch" -- switch
    const T_THROW = 'T_THROW'; // "throw" -- Exceptions
    const T_TRAIT = 'T_TRAIT'; // "trait" -- Traits (available since PHP 5.4.0)
    const T_TRAIT_C = 'T_TRAIT_C'; // "__TRAIT__" -- __TRAIT__ (available since PHP 5.4.0)
    const T_TRY = 'T_TRY'; // "try" -- Exceptions
    const T_UNSET = 'T_UNSET'; // "unset()" -- unset()
    const T_UNSET_CAST = 'T_UNSET_CAST'; // "(unset)" -- type-casting
    const T_USE = 'T_USE'; // "use" -- namespaces (available since PHP 5.3.0; reserved since PHP 4.0.0)
    const T_VAR = 'T_VAR'; // "var" -- classes and objects
    const T_VARIABLE = 'T_VARIABLE'; // "$foo" -- variables
    const T_WHILE = 'T_WHILE'; // "while" -- while, do..while
    const T_WHITESPACE = 'T_WHITESPACE'; // "\t \r\n"
    const T_XOR_EQUAL = 'T_XOR_EQUAL'; // "^=" -- assignment operators
    const T_YIELD = 'T_YIELD'; // "yield" -- generators (available since PHP 5.5.0)
}

/*EOF*/
