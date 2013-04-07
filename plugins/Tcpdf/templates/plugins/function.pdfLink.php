<?php

/**
 * Available params:
 *  - tag  (boolean)       If set to true - full <a> tag will be generated.
 *  - text (string)        Text to display in the <a> tag (only necassary if $tag is set to true)
 *
 * Examples
 *
 * Basic usage:
 *  {pdfLink __text='Download as pdf'}
 *
 * @param array       $params All attributes passed to this function from the template.
 * @param Zikula_View $view   Reference to the {@link Zikula_View} object.
 *
 * @return pdf file
 */
function smarty_function_pdfLink($params, Zikula_View $view)
{
    if (!isset($params['tag'])) {
        $params['tag'] = true;
    }
    if (!isset($params['text']) && $params['tag']) {
        $view->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('smarty_function_pdfLink', 'text')));
        return;
    }

    $get = $_GET;
    $args = $get;
    unset($args['module']);
    unset($args['type']);
    unset($args['func']);
    $args['theme'] = 'pdf';

    if ($params['tag']) {
        $result = "<a href=\"" . ModUtil::url($get['module'], $get['type'], $get['func'], $args) . "\">{$params['text']}</a>";
    } else {
        $result = ModUtil::url($get['module'], $get['type'], $get['func'], $args);
    }

    if (isset($params['assign'])) {
        $view->assign($params['assign'], $result);
    } else {
        return $result;
    }
}
