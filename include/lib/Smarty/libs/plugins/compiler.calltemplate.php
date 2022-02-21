<?php
function smarty_compiler_calltemplate($tag_args, &$compiler) {
    $_attrs = $compiler->_parse_attrs($tag_args);

    if (!isset($_attrs['name'])) $compiler->_syntax_error("calltemplate: missing name parameter");
    $_func_name = $compiler->_dequote($_attrs['name']);
    $_func = 'smarty_calltemplate_'.$_func_name;
    $_params = 'array(';
    $_sep = '';
    unset($_attrs['name']);
    foreach ($_attrs as $_key=>$_value) {
        $_params .= "$_sep'$_key'=>$_value";
        $_sep = ',';
    }
    $_params .= ')';
    return "$_func(\$this, $_params); ";
}
?>