<?php
// include 2D barcode class
require_once('plugins/Tcpdf/lib/vendor/tcpdf/tcpdf_barcodes_1d.php');

/**
 * Class TCPDFBarcode_Custom
 */
class TCPDFBarcodeCustom extends TCPDFBarcode
{
    /**
     * Custom extension of native method, works exactly like the native one if no $filename is given.
     * If $filename is set, it will save the file at this place.
     */
    public function getBarcodePNG($w=2, $h=30, $color=array(0,0,0), $filename = false) {
        // calculate image size
        $width = ($this->barcode_array['maxw'] * $w);
        $height = $h;
        if (function_exists('imagecreate')) {
            // GD library
            $imagick = false;
            $png = imagecreate($width, $height);
            $bgcol = imagecolorallocate($png, 255, 255, 255);
            imagecolortransparent($png, $bgcol);
            $fgcol = imagecolorallocate($png, $color[0], $color[1], $color[2]);
        } elseif (extension_loaded('imagick')) {
            $imagick = true;
            $bgcol = new imagickpixel('rgb(255,255,255');
            $fgcol = new imagickpixel('rgb('.$color[0].','.$color[1].','.$color[2].')');
            $png = new Imagick();
            $png->newImage($width, $height, 'none', 'png');
            $bar = new imagickdraw();
            $bar->setfillcolor($fgcol);
        } else {
            return false;
        }
        // print bars
        $x = 0;
        foreach ($this->barcode_array['bcode'] as $k => $v) {
            $bw = round(($v['w'] * $w), 3);
            $bh = round(($v['h'] * $h / $this->barcode_array['maxh']), 3);
            if ($v['t']) {
                $y = round(($v['p'] * $h / $this->barcode_array['maxh']), 3);
                // draw a vertical bar
                if ($imagick) {
                    $bar->rectangle($x, $y, ($x + $bw - 1), ($y + $bh - 1));
                } else {
                    imagefilledrectangle($png, $x, $y, ($x + $bw - 1), ($y + $bh - 1), $fgcol);
                }
            }
            $x += $bw;
        }

        if(!$filename) {
            // send headers
            header('Content-Type: image/png');
            header('Cache-Control: public, must-revalidate, max-age=0'); // HTTP/1.1
            header('Pragma: public');
            header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // Date in the past
            header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');

            if ($imagick) {
                $png->drawimage($bar);
                echo $png;
            } else {
                imagepng($png);
                imagedestroy($png);
            }
        } else {
            if ($imagick) {
                $png->writeimage($filename);
            } else {
                imagepng($png, $filename);
                imagedestroy($png);
            }
        }
    }

    /**
     * Custom extension of native method, works exactly like the native one if no $filename is given.
     * If $filename is set, it will save the file at this place.
     */
    public function getBarcodeSVG($w=3, $h=3, $color='black', $filename = false) {
        if(!$filename) {
            parent::getBarcodeSVG($w, $h, $color);
        } else {
            $code = $this->getBarcodeSVGcode($w, $h, $color);
            file_put_contents($filename, $code);
        }
    }
}