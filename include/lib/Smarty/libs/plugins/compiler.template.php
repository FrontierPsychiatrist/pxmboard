<?php
/* create code for a function declaration */
function smarty_compiler_template($tag_args, &$compiler) {   
    $attrs = $compiler->_parse_attrs($tag_args);
    $func_key = '"' . md5('php-5') . '[[' . md5(uniqid('pxmboard')) . '";';
    array_push($compiler->_tag_stack, array('template', $attrs, $tag_args, $func_key));
    if (!isset($attrs['name'])) $compiler->_syntax_error("template: missing name parameter");

    $func_name = $compiler->_dequote($attrs['name']);
    $func = 'smarty_calltemplate_'.$func_name;
    return $func_key . "if (!function_exists('$func')) { function $func(&\$this, \$params) { \$_template_tpl_vars = \$this->_tpl_vars; \$this->assign(\$params); ";
}

/* create code for closing a function definition */
function smarty_compiler_template_close($tag_args, &$compiler) {
    list($name, $attrs, $open_tag_args, $func_key) = array_pop($compiler->_tag_stack);
    if ($name!='template') $compiler->_syntax_error("unexpected {/template}");
    return " \$this->_tpl_vars = \$_template_tpl_vars; }} " . $func_key ;
}
$this->register_compiler_function('/template', 'smarty_compiler_template_close');

/* callback to replace all $this with $smarty */
function smarty_replace_template($match) {
    $tokens = token_get_all('<?php ' . $match[2]);

    /* remove trailing <?php */
    $open_tag = '';
    while ($tokens) {
        $token = array_shift($tokens);
        if (is_array($token)) {
            $open_tag .= $token[1];
        } else {
            $open_tag .= $token;
        }
        if ($open_tag == '<?php ') break;
    }

    /* replace */
    for ($i=0, $count=count($tokens); $i<$count; $i++) {
        if (is_array($tokens[$i])) {
            if ($tokens[$i][0] == T_VARIABLE && $tokens[$i][1] == '$this') {
                $tokens[$i] = '$smarty';
            } else {
                $tokens[$i] = $tokens[$i][1];
            }
        }
    }
    return implode('', $tokens);
}

/* postfilter to squeeze the code to make php5 happy */
function smarty_postfilter_template($source, &$compiler) {
    $search = '("' . md5('php-5') . '\[\[[0-9a-f]{32}";)';
    if ((double)phpversion()>=5.0) {
        /* filter sourcecode. look for func_keys and replace all $this
           in-between with $smarty */
        while (1) {
            $new_source = preg_replace_callback('/' . $search . '(.*)\\1/Us', 'smarty_replace_template', $source);
            if (strcmp($new_source, $source)==0) break;
            $source = $new_source;
        }
    } else {
        /* remove func_keys */
        $source = preg_replace('/' . $search . '/', '', $source);
    }
    return $source;
}
$this->register_postfilter('smarty_postfilter_template');
?>
