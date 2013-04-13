<?php

/**
 * Available params:
 * - code  (string)   The string to generate the barcode of.
 * - type (string)    The type of the barcode. You can find all available types in Plugin.php::createBarcode1d().
 * - width (integer)  The width of *one* bar.
 * - height (integer) The height of *one* bar.
 * - color (string)   The color of the bars.
 *
 * Examples
 *
 * Basic usage:
 *  {barcode1d code='yourCode'}
 *
 * @param array       $params All attributes passed to this function from the template.
 * @param Zikula_View $view   Reference to the {@link Zikula_View} object.
 *
 * @return html barcode
 */
function smarty_function_barcode1d($params, Zikula_View $view)
{
    if (!isset($params['code'])) {
        $view->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('smarty_function_barcode', 'code')));
        return;
    }

    $code = $params['code'];
    $type = (isset($params['type'])) ? $params['type'] : 'C128';
    //$format = (isset($params['format'])) ? $params['format'] : 'html';
    $width = (isset($params['width'])) ? $params['width'] : '2';
    $height = (isset($params['height'])) ? $params['height'] : '30';
    $color = (isset($params['color'])) ? $params['color'] : 'black';

    $tcpdf = PluginUtil::loadPlugin('SystemPlugin_Tcpdf_Plugin');
    $result = $tcpdf->createBarcode1d($code, $type, /*$format,*/ $width, $height, $color);

    $theme = UserUtil::getTheme();

    if($theme != 'pdf') {
        if (isset($params['assign'])) {
            $view->assign($params['assign'], $result);
        } else {
            return $result;
        }
    } else {
        return "!--TCPDFBARCODE dimension='1D' code='$code' type='$type' width='$width' height='$height' color='$color'--!";
    }
}
