<?php
/**
 * PDF class used for processing PDF and CSV export
 *
 * @author Anna
 *
 * Destination ($dest) where to send the document. It can take one of the following values:
 *      I: send the file inline to the browser (default). The plug-in is used if available. The name given by name is used when one selects the "Save as" option on the link generating the PDF.
 *      D: send to the browser and force a file download with the name given by name.
 *      F: save to a local server file with the name given by name.
 *      S: return the document as a string (name is ignored).
 *      FI: equivalent to F + I option
 *      FD: equivalent to F + D option
 *      E: return the document as base64 mime multi-part email attachment (RFC 2045)
 */

require_once(__DIR__ . '/../../vendor/autoload.php');

class Pdf {

    private 
            $prefix_project_name    = '',
            $page_size              = 'A4',
            $page_lang              = 'en',
            $dest                   = 'D';

    //constructor
    public function __construct($prefix_project_name = '', 
                                $page_size = 'A4', 
                                $page_lang = 'en', 
                                $dest = 'D') {
        $this->prefix_project_name  = $prefix_project_name;
        $this->page_size            = $page_size;
        $this->page_lang            = $page_lang;
        $this->dest                 = $dest;
    }

    /* 
    * Generate file name for PDF and CSV export
    *
    * @param    string      $report         shorter of report name ('PPR' for player progression report, etc)
    * @param    string      $file_type      extension of file ('csv' or 'pdf')
    *
    */
    public function getFileNameForExport($report, $file_type) {
        return $this->prefix_project_name . '_' . $report . '_' . time() . '.' . $file_type;
    }

    /*
    * Generate PDF using TCPDF and Html2PDF libraries
    *
    * @param    string      $html           html text for export PDF
    * @param    char        $orientation    orientation of pdf ('P' for Portrait or 'L' for Landscape, default value 'P')
    * @param    string      $report         shorter of report name ('PPR' for player progression report, etc)
    *
    */ 
    public function generatePDF($html, $orientation = 'P', $report) {
        
        $html2pdf = new \Spipu\Html2Pdf\Html2Pdf($orientation, $this->page_size, $this->page_lang);
        
        $html2pdf->writeHTML($html);
        
        $html2pdf->output($this->getFileNameForExport($report, 'pdf'), $this->dest);
    }

}