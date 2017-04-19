<?php

namespace Potherca\Scanner\Node;

use Potherca\Scanner\AbstractEnum;

class NodeType extends AbstractEnum
{
    /* There are 4 categories of Node Types */

    /**
     * Other Nodes that extend AbstractNode directly
     */
    const ARGUMENT = 'Arg';
    const CONSTANT = 'Const';
    const NAME = 'Name';
    const PARAMETER = 'Param';

    /**
     * PhpParser\Node\Exprs -- expression nodes i.e. language constructs that
     * return a value and thus can occur in other expressions.
     */
    const EXPRESSION = 'Expr';
    const EXPR_FUNC_CALL =  'Expr_FuncCall';
    const EXPR_VARIABLE = 'Expr_Variable';

    /**
     * PhpParser\Node\Scalars -- nodes representing scalar values, like 'string'
     * or magic constants like __FILE__
     */
    const SCALAR = 'Scalar';

    /**
     * PhpParser\Node\Stmts -- statement nodes, i.e. language constructs
     * that do not return a value and can not occur in an expression.
     */
    const STATEMENT = 'Stmt';
}

/*EOF*/
