<?php
require_once("TCPDF/tcpdf.php");
require_once("models/dataSzivessegi.php");
require_once("managers/grammar.php");

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

        // var_dump($this->data);

        $this->generate();
    }

    function generate()
    {
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $pdf->setFontSubsetting(true);

        $pdf->setDefaultMonospacedFont('dejavusans');
        $pdf->setMargins(0, 0, 0);
        $pdf->AddPage('', '', true, false);
        $dejavusansb = $pdf->AddFont("dejavusansb");
        if(!$dejavusansb)
        {
            var_dump($dejavusansb);
            return;
        }
        
        $pdf->setFont('dejavusans', '', 10.5, '', false);
        $pdf->setCellMargins(0, 0, 0, 0);
        
        $data = new DataSzivessegi();
        $data = $this->data;
        $data->hVevoHrsz = !empty($data->hVevoHrsz) ? "hrsz: ".$data->hVevoHrsz : "";

        $html = <<<EOD
        <div style="text-align: left;">
            <img src="assets/logonewpdf.jpg" />
        </div>
        EOD;

        $pdf->writeHTML($html, true, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setFont('dejavusans', '', 9.5, '', true);

        $pdf->SetXY(0, 25);

        $html = <<<EOD
        <h2 style="text-align: center;">
            SZÍVESSÉGI FÖLDHASZNÁLATI SZERZŐDÉS
        </h2>
        EOD;

        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setFont('dejavusans', '', 9.5, '',true);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <p style="line-height: 25px;">
            mely létrejött egyrészről:
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td>
                    Név (családi és utónév):
                </td>
                <td style="font-family:dejavusansb;">
                    $data->fullName
                </td>
            </tr>
            <tr>
                <td>
                    Születési családi és utónév:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->fullNameBirth
                </td>
            </tr>
            <tr>
                <td>
                Születési hely:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->regionOfBirth
                </td>
            </tr>
            <tr>
                <td>
                    Születési idő:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->dateOfBirth
                </td>
            </tr>
            <tr>
                <td>
                    Anyja születési neve:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->mothersName
                </td>
            </tr>
            <tr>
                <td>
                    Lakcím: 
                </td>
                <td style="font-family: dejavusansb;">
                $data->postalCode $data->settlement,
$data->kozterNev $data->kozterTipus $data->hazSzam $data->emeletAjto
                </td>
            </tr>
            <tr>
                <td>
                    Személyi szám:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->szemSzam
                </td>
            </tr>
            <tr>
                <td>
                    Adóazonosító jel:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->adoazonJel
                </td>
            </tr>
            <tr>
                <td>
                    Állampolgárság:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->nationality
                </td>
            </tr>
        </table>
        <p style="line-height: 10px;">
          &nbsp;
        </p>
        <div style="line-height: 15px;">mint<span style="font-family: dejavusansb;">
                szívességi használatba adó
            </span> (a továbbiakban: földhasználatba adó), <br />
            másrészről:
        </div>
        <p style="line-height: 5px;">
          &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td>
                    Név (családi és utónév):
                </td>
                <td style="font-family:dejavusansb;">
                    $data->hVevoFullName
                </td>
            </tr>
            <tr>
                <td>
                    Születési családi és utónév:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoFullNameBirth
                </td>
            </tr>
            <tr>
                <td>
                Születési hely:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoRegionOfBirth
                </td>
            </tr>
            <tr>
                <td>
                    Születési idő:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoDateOfBirth
                </td>
            </tr>
            <tr>
                <td>
                    Anyja születési neve:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoMothersName
                </td>
            </tr>
            <tr>
                <td>
                    Lakcím: 
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoPostalCode
                    $data->hVevoSettlement,
                    <br>
                    $data->hVevoKozterNev
                    $data->hVevoKozterTipus
                    $data->hVevoHazSzam
                    <br>
                    $data->hVevoHrsz
                </td>
            </tr>
            <tr>
                <td>
                    Személyi szám:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoSzemSzam
                </td>
            </tr>
            <tr>
                <td>
                    Adóazonosító jel:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoAdoazonJel
                </td>
            </tr>
            <tr>
                <td>
                    Állampolgárság:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoNationality
                </td>
            </tr>
            <tr>
                <td>
                    Földműves nyilvántartási szám:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoFoldmuvesNyilvtartSzam
                </td>
            </tr>
            <tr>
                <td>
                    Kamarai tagsági azonosító szám:
                </td>
                <td style="font-family: dejavusansb;">
                    $data->hVevoKamaraTagAzonSzam
                </td>
            </tr>
        </table>
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <div style="line-height: 15px;"> 
            mint 
            <span style="font-family: dejavusansb;">szívességi használatba vevő</span> 
            (a továbbiakban: földhasználatba vevő) 
            együtt a továbbiakban: szerződő felek között a mező- és erdőgazdasági földek forgalmáról szóló 2013. évi CXXII. 
            törvény (a továbbiakban: Földforgalmi tv.) szerinti föld fogalma alá tartozó földrészlet szívességi használata 
            tárgyában az alulírt helyen és időben, az alábbi feltételek mellett.
        </div>
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    1.
                </td>
                <td style="width: 95%;">
                    A szerződő felek megállapodnak abban, hogy jelen okirat aláírásával egyidejűleg szívességi földhasználati szerződést kötnek a földhasználatba adó alább szereplő tulajdoni hányadát képező ingatlanok vonatkozásában.
                </td>
            </tr>
        </table>
EOD;

        //$pdf->setCellMargins(0.01, 0.01, 0.01, 0.01);
        //$html = str_replace(PHP_EOL, '', $html);
        //$html = preg_replace('/{nbr}[\r\n]/', 'likjzgouzinopuj', $html);
        //$html = preg_replace("/\t+/", "", $html);
        //$html = preg_replace("/\r|\n/", "zzzzzzzzzzzzzzz", $html);
        //$html = preg_replace('/Születési/', 'csecccs', $html);
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $html = "";

        $i = 0;
        foreach($data->foldterSections as $foldterSection)
        {
            $sorsz = "";
            if($i > 0)
            {
                $sorsz = "1.".$i;
            }

            $terulete = $foldterSection->foldTeruletHektar? $foldterSection->foldTeruletHektar." ha" : $foldterSection->foldTeruletM2." m2";
            $teruleteHanyad = $foldterSection->foldBerbeadottTulajdoniHanyadTeruletHektar? $foldterSection->foldBerbeadottTulajdoniHanyadTeruletHektar." ha" : $foldterSection->foldBerbeadottTulajdoniHanyadTeruletM2." m2";

            $muvelesi_aga = "";
            foreach($foldterSection->foldMuvAgs as $foldMuvAg)
            {
                if($foldMuvAg->checked)
                {
                    if($muvelesi_aga != "")
                    {
                        $muvelesi_aga .= ", ";
                    }
                    $muvelesi_aga .= $foldMuvAg->text;
                }
            }

            $html .= <<<EOD
            <p style="line-height: 10px;">
                &nbsp;
            </p>
            <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
                <tr>
                    <td style="width: 5%;">
                        $sorsz
                    </td>
                    <td style="width: 60%;">
                        A föld fekvése szerinti település neve:
                    </td>
                    <td style="font-family: dejavusansb;">
                        $foldterSection->foldSettlement
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">
                        Fekvése:
                    </td>
                    <td style="font-family: dejavusansb;">
                        $foldterSection->foldFekvese
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">
                        Helyrajzi száma:
                    </td>
                    <td style="font-family: dejavusansb;">
                        $foldterSection->foldHrsz
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">
                        Művelési ága:
                    </td>
                    <td style="font-family: dejavusansb;">
                        $muvelesi_aga
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">
                        Területe:
                    </td>
                    <td style="font-family: dejavusansb;">
                        $terulete
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">
                        Kataszteri tiszta jövedelme (AK):
                    </td>
                    <td style="font-family: dejavusansb;">
                        $foldterSection->foldKataszteriTisztaJovedelemAK
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">
                        A használatba adott tulajdoni hányad:
                    </td>
                    <td style="font-family: dejavusansb;">
                        $foldterSection->foldBerbeadottTulajdoniHanyadX / $foldterSection->foldBerbeadottTulajdoniHanyadY
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">
                        Használatba adott tulajdoni hányadnak megfelelő terület:
                    </td>
                    <td style="font-family: dejavusansb;">
                        $teruleteHanyad
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">
                        A használatba adott tulajdoni hányadnak megfelelő
                        <br>
                        kataszteri tiszta jövedelem (AK):
                    </td>
                    <td style="font-family: dejavusansb;">
                        $foldterSection->foldKataszteriTisztaJovedelemTulajdoniHanyadAK
                    </td>
                </tr>
            </table>
            EOD;
            $i++;
        }
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $kettespont = "";
        $idotartam = "határozatlan";
        $tol = Grammar::ev_ho_nap($data->hasznalatbaAdasTol);

        if($data->hasznalatIdotartamHatarozott)
        {
            $ig = Grammar::ev_ho_nap($data->hasznalatbaAdasIg);
            $idotartam = $tol." napjától ".$ig." napjáig tartó határozott";

            $kettespont =  <<<EOD
            A szerződő felek megállapodnak abban, hogy a földhasználatba adó az 1. pontban meghatározott termőfölde(ke)t
            $idotartam
            időtartamra a földhasználatba vevőnek ingyenes használatba adja, földhasználatba vevő földhasználatba veszi.
            EOD;
        }
        else
        {
            $kettespont =  <<<EOD
            A szerződő felek megállapodnak abban, hogy földhasználatba adó az 1. pontban meghatározott termőfölde(ke)t 
            $tol napjától 
            határozatlan időtartamig a földhasználatba vevőnek ingyenes használatba adja, földhasználatba vevő földhasználatba veszi.
            EOD;
        }


        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    2.
                </td>
                <td style="width: 95%;">
                    $kettespont                    
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    3.
                </td>
                <td style="width: 95%;">
                    Felek büntetőjogi felelősségük tudatában kijelentik, hogy a Földforgalmi tv. 5. § 13. pontja szerinti 
                    közeli hozzátartozónak minősülnek, mert közöttük rokoni kapcsolat áll fenn ($data->hasznalatKozeliHozzatart).
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    4.
                </td>
                <td style="width: 95%;">
                    A földhasználatba adó a föld használatát a földhasználatba vevőnek ingyenesen engedi át, ezért a szerződő felek földhasználati díjat nem állapítanak meg.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    5.
                </td>
                <td style="width: 95%;">
                    A földhasználatba vevő köteles az 1. pontban megjelölt ingatlant a jó gazda gondosságával művelési ágának megfelelően művelni és folyamatosan gondoskodni a termőképességének fenntartásáról. Az ingatlan használatának jogát sem visszterhes, sem ingyenes szerződésben harmadik fél részére nem engedheti át.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $ev_ho_nap = Grammar::ev_ho_nap($data->hasznalatBirtokbavetelIdopont);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    6.
                </td>
                <td style="width: 95%;">
                A szerződő felek megállapodnak abban, hogy a jelen szerződés 1. pontjában megjelölt ingatlant 
                $ev_ho_nap 
                napjától a földhasználó birtokba veszi és a szerződés időtartama alatt szedi annak hasznait, viseli terheit.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    7.
                </td>
                <td style="width: 95%;">
                    Jelen szívességi földhasználati szerződés megszűnése esetén a földrészletet
                    - különös tekintettel a gyommentességre - olyan állapotban kell visszaadni a tulajdonosnak, 
                    hogy azon a rendeltetésszerű gazdálkodás azonnal folytatható legyen.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    8.
                </td>
                <td style="width: 95%;">
                    Földhasználatba vevő kijelenti, hogy rendelkezik a fentiek szerinti termőföld megműveléséhez 
                    szükséges jogi és személyi feltételekkel és azokat a szerződés hatálya alatt is fenntartja. A földhasználatba 
                    vevő jelen szerződés aláírásával kijelenti, hogy személyében megfelel a Földforgalmi tv. 5. § 7. pontjában foglalt
                    földműves fogalmának.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    9.
                </td>
                <td style="width: 95%;">
                    Földhasználatba vevő a Földforgalmi tv. 42. §-a alapján az alábbiak szerint nyilatkozom:
                    <ul>
                        <li>
                            A földhasználati jog megszerzésére jogosultsággal rendelkezem, megfelelek a 40. § (1) – (4) bekezdésben, valamint a 41. §-ban foglalt feltételeknek.
                        </li>
                        <li>
                            A szívességi földhasználati szerződésben megjelölt föld használatát másnak nem engedem át, azt magam használom.
                        </li>
                        <li>
                            A szívességi földhasználati szerződés időtartama alatt az 1. pontban leírt földre vonatkozóan eleget teszek földhasznosítási kötelezettségemnek.
                        </li>
                        <li>
                            Nincs jogerősen megállapított és fennálló földhasználati díjtartozásom.
                        </li>
                        <li>
                            A szívességi földhasználati szerződés tárgyát képező föld használatba vételével a már birtokomban és használatomban lévő földterületek nagysága nem haladja meg a Földforgalmi tv. 16. § (2) – (5) bekezdések szerinti birtokmaximumot.
                        </li>
                    </ul>
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    10.
                </td>
                <td style="width: 95%;">
                    Földhasználatba vevő kijelenti, hogy a szívességi földhasználati szerződés tárgyát képező ingatlan elhelyezkedését, természetbeni határait ismeri.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    11.
                </td>
                <td style="width: 95%;">
                    A jelen szívességi földhasználati szerződésben foglalt termőföld területet terhelő közterheket a földhasználatba vevő köteles megfizetni. 
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    12.
                </td>
                <td style="width: 95%;">
                    A földhasználókat megillető és jogszabály szerint járó támogatásokat a földhasználatba vevő jogosult igénybe venni.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    13.
                </td>
                <td style="width: 95%;">
                    A szerződő felek megállapodnak abban, hogy jelen szívességi földhasználati szerződés megszűnik
                    <ul>
                        <li>
                            a határozott időtartamú szívességi földhasználati szerződés esetén az időtartam lejártával, a lejárat napján,
                        </li>
                        <li>
                            határozatlan időtartamú szerződés esetén közös megegyezéssel, a szerződő felek által meghatározott napon,
                        </li>
                        <li>
                            felmondással,
                        </li>
                        <li>
                            azonnali hatályú felmondással,
                        </li>
                        <li>
                            a határozatlan időtartamú szerződés esetén a szerződő felek közötti közeli hozzátartozói viszony bármilyen okból történő megszűnésével, e tényhelyzet beálltát követő 30. napon.
                        </li>
                    </ul>
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    14.
                </td>
                <td style="width: 95%;">
                    A határozatlan időre kötött szívességi földhasználati szerződés 60 napos felmondási idővel mondható fel. A határozott időtartamú szívességi földhasználati szerződés azonnali hatályú felmondással való megszüntetésére – a szerződő felek eltérő megállapodása hiányában - a haszonbérleti szerződés azonnali hatályú felmondására vonatkozó szabályok az irányadók.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    15.
                </td>
                <td style="width: 95%;">
                    A jelen szívességi földhasználati szerződésben nem szabályozott kérdésekben a Polgári Törvénykönyvről szóló 2013. évi V. törvény, továbbá Földforgalmi tv., valamint a mező- és erdőgazdasági földek forgalmáról szóló 2013. évi CXXII. törvénnyel összefüggő egyes rendelkezésekről és átmeneti szabályokról szóló 2013. évi CCXII. törvény szívességi földhasználatra vonatkozó rendelkezései az irányadóak.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">
                    16.
                </td>
                <td style="width: 95%;">
                    A szerződő felek tudomásul veszik, hogy jelen szívességi földhasználati szerződés érvényességének a szívességi használatba vevő földhasználatának, földhasználati nyilvántartásban történő bejegyzésének előfeltétele a szerződés mezőgazdasági igazgatási szerv részéről történő jóváhagyása. Jelen szívességi földhasználati szerződést az aláírástól számított 8 napon belül a földhasználatba vevő köteles mezőgazdasági igazgatási szervhez jóváhagyás céljából benyújtani.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <p>
            Kijelentem, hogy jelen okiratban foglaltak a valóságnak megfelelnek.
        </p>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <p style="line-height: 15px;">
            Jelen szívességi földhasználati szerződést megkötő felek, mint szerződéses akaratuknak mindben
             megegyezőt elolvasás és értelmezés után helyben jóváhagyólag írják alá.
        </p>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 30px;">
            &nbsp;
        </p>
        <p>
            Kelt: ____________________________________, ________ év __________________________ hó ______ nap
        </p>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $pdf->AddPage();
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 30px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    földhasználatba adó
                </td>
                <td style="text-align: center;">
                    _______________________________________
                    <br>
                    földhasználatba vevő
                </td>
            </tr>
        </table>
    
        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    Előttünk, mint tanúk előtt:
                </td>
                <td style="width: 50%; text-align: center;">
                    &nbsp;
                </td>
            </tr>
        </table>

        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    aláírás
                </td>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    aláírás
                </td>
            </tr>
        </table>

        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    név
                </td>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    név
                </td>
            </tr>
        </table>

        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    lakcím
                </td>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    lakcím
                </td>
            </tr>
        </table>

        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    személyazonosító okmány száma
                </td>
                <td style="width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    személyazonosító okmány száma
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
