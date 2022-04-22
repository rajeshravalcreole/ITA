<?php
/**
* Template Name: English_pdf
*
* @package WordPress
* @subpackage Twenty_Fourteen
* @since Twenty Fourteen 1.0

* HTML2PDF Librairy - example
 *
 * HTML => PDF convertor
 * distributed under the LGPL License
 *
 * @author      Laurent MINGUET <webmaster@html2pdf.fr>
 *
 * isset($_GET['vuehtml']) is not mandatory
 * it allow to display the result in the HTML format
 */

    // get the HTML
    ob_start();
    include(get_template_directory() .'/html2pdf/html_template/english_pdf_template.php');
    $content = ob_get_clean();

    // convert in PDF
    require_once(get_template_directory() .'/html2pdf/html2pdf.class.php');
    try
    {
        $html2pdf = new HTML2PDF('P', 'A4', 'fr', true, 'UTF-8', array(0, 0, 0, 0));
//      $html2pdf->setModeDebug();
        $html2pdf->setDefaultFont('sfpro');
        $html2pdf->writeHTML($content);
        $html2pdf->Output('english_pdf.pdf');
    }
    catch(HTML2PDF_exception $e) {
        echo $e;
        exit;
    }
