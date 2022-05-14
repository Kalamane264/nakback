<?php
require_once("TCPDF/tcpdf.php");
require_once("classes/dataSzivessegi.php");

class Szivessegi
{
    public $data;

    function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST')
        {
            return;
        }
        
        $cumo = json_decode(file_get_contents('php://input'));
        $this->data = new DataSzivessegi();
        $this->data = ($cumo);

        $this->generate();
    }

    function generate()
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        
        $pdf->setDefaultMonospacedFont('dejavusans');
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->AddPage('', '', false, false);
        $dejavusansb = $pdf->AddFont("dejavusansb");
        if(!$dejavusansb)
        {
            var_dump($dejavusansb);
            return;
        }
        $tagvs = [
            'p' => [
              ['h'=>0.1, ],
              ['h'=>0.1, ]
            ]
          ];
        $pdf->setHtmlVSpace($tagvs);
        $pdf->setFont('dejavusans', '', 10.5, '', true);
        // $pdf->setCellMargins(0, 0, 0, 0);
        
        $data = new DataSzivessegi();
        
        $data = $this->data;
        $data->hrsz = !empty($data->hrsz) ? "hrsz: ".$data->hrsz : "";

        $html = <<<EOD
        <h2 style="text-align: center;">
            SZÍVESSÉGI FÖLDHASZNÁLATI SZERZŐDÉS
        </h2>
        EOD;

        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setFont('dejavusans', '', 9.5, '', true);

        $html = <<<EOD
        <p style="line-height: 55px;">
            mely létrejött egyrészről:
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 20px;">
                    Név (családi és utónév):
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->fullName
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Születési családi és utónév:
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->fullNameBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                Születési hely:
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->regionOfBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Születési idő:
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->dateOfBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Anyja születési neve:
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->mothersName
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Lakcím: 
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->postalCode
                    $data->settlement,
                    <br>
                    $data->kozterNev
                    $data->kozterTipus
                    $data->hazSzam
                    <br>
                    $data->hrsz
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Személyi szám:
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->szemSzam
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Adóazonosító jel:
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->adoazonJel
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Állampolgárság:
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->nationality
                </td>
            </tr>
        </table>
        EOD;

        $pdf->writeHTML($html, false, false, false, false);


        $filename = "szivessegi_".date("yyyy_m_d_".str_replace(array(' ', '.'), '', (string)microtime())).".pdf";
        $pdf->Output( getcwd().'/pdfs/'.$filename, 'F');
        echo '{"filename":"'.$filename.'"}';
    }
}

new Szivessegi();
?>
