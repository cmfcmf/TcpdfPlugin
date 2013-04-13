<?php

/**
 * Available params:
 * - code  (string)   The string to generate the barcode of.
 * - type (string)    The type of the barcode. You can find all available types in Plugin.php::createBarcode2d().
 * - width (integer)  The width of a dot / pixel.
 * - height (integer) The height of a dot / pixel.
 * - color (string)   The color of the a dots / pixels.
 *
 * Examples
 *
 * Basic usage:
 *  {barcode2d code='yourCode'}
 *
 * @param array       $params All attributes passed to this function from the template.
 * @param Zikula_View $view   Reference to the {@link Zikula_View} object.
 *
 * @return html barcode
 */
function smarty_function_barcode2d($params, Zikula_View $view)
{
    if (!isset($params['code'])) {
        $view->trigger_error(__f('Error! in %1$s: the %2$s parameter must be specified.', array('smarty_function_barcode', 'code')));
        return;
    }

    $code = $params['code'];
    $type = (isset($params['type'])) ? $params['type'] : 'QRCODE,H';
    $format = (isset($params['format'])) ? $params['format'] : 'html';
    $width = (isset($params['width'])) ? $params['width'] : '6';
    $height = (isset($params['height'])) ? $params['height'] : '6';
    $color = (isset($params['color'])) ? $params['color'] : 'black';

    $tcpdf = PluginUtil::loadPlugin('SystemPlugin_Tcpdf_Plugin');
    $result = $tcpdf->createBarcode2d($code, $type, /*$format,*/ $width, $height, $color);

    if (isset($params['assign'])) {
        $view->assign($params['assign'], $result);
    } else {
        return $result;
    }
}
