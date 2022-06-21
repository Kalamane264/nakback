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
        $pdf->setCellMargins(0, 0, 0, 0);
        
        $data = new DataSzivessegi();
        $data = $this->data;
        $data->hrsz = !empty($data->hrsz) ? "hrsz: ".$data->hrsz : "";
        $data->hVevoHrsz = !empty($data->hVevoHrsz) ? "hrsz: ".$data->hVevoHrsz : "";

        $html = <<<EOD
        <h2 style="text-align: center; line-height: 55px;">
            SZÍVESSÉGI FÖLDHASZNÁLATI SZERZŐDÉS
        </h2>
        EOD;

        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setFont('dejavusans', '', 9.5, '', true);

        $html = <<<EOD
        <p style="line-height: 25px;">
            mely létrejött egyrészről:
        </p>
        <p style="line-height: 20px;">
            &nbsp;
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
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->fullNameBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                Születési hely:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->regionOfBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Születési idő:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->dateOfBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Anyja születési neve:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->mothersName
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Lakcím: 
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->postalCode $data->settlement, <br>$data->kozterNev $data->kozterTipus <br>$data->hazSzam<br>
                    $data->hrsz
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Személyi szám:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->szemSzam
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Adóazonosító jel:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->adoazonJel
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Állampolgárság:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->nationality
                </td>
            </tr>
        </table>
        <p style="line-height: 20px;">
          &nbsp;
        </p>
        <p style="line-height: 20px;">
            mint
            <span style="font-family: dejavusansb;">
                szívességi használatba adó
            </span> (a továbbiakban: földhasználatba adó),
        </p>
        <p>
            másrészről:
        </p>
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 20px;">
                    Név (családi és utónév):
                </td>
                <td style="font-family:dejavusansb; line-height: 20px;">
                    $data->hVevoFullName
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Születési családi és utónév:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoFullNameBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                Születési hely:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoRegionOfBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Születési idő:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoDateOfBirth
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Anyja születési neve:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoMothersName
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Lakcím: 
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
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
                <td style="line-height: 20px;">
                    Személyi szám:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoSzemSzam
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Adóazonosító jel:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoAdoazonJel
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Állampolgárság:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoNationality
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Földműves nyilvántartási szám:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoFoldmuvesNyilvtartSzam
                </td>
            </tr>
            <tr>
                <td style="line-height: 20px;">
                    Kamarai tagsági azonosító szám:
                </td>
                <td style="font-family: dejavusansb; line-height: 20px;">
                    $data->hVevoKamaraTagAzonSzam
                </td>
            </tr>
        </table>
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <p style="text-align: justify; line-height: 15px;">mint <span style="font-family: dejavusansb;">szívességi használatba vevő</span> (a továbbiakban: földhasználatba vevő) együtt a továbbiakban: szerződő felek között a mező- és erdőgazdasági földek forgalmáról szóló 2013. évi CXXII. törvény (a továbbiakban: Földforgalmi tv.) szerinti föld fogalma alá tartozó földrészlet szívességi használata tárgyában az alulírt helyen és időben, az alábbi feltételek mellett.
        </p>
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    1.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    A szerződő felek megállapodnak abban, hogy jelen okirat aláírásával egyidejűleg szívességi földhasználati szerződést kötnek a földhasználatba adó alább szereplő tulajdoni hányadát képező ingatlanok vonatkozásában.
                </td>
            </tr>
        </table>
        EOD;

        $pdf->writeHTML($html, false, false, false, false);
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
            <p style="line-height: 20px;">
                &nbsp;
            </p>
            <table cellspacing="0" cellpadding="0" border="0">
                <tr>
                    <td style="line-height: 20px; width: 5%;">
                        $sorsz
                    </td>
                    <td style="line-height: 20px; width: 60%;">
                        A föld fekvése szerinti település neve:
                    </td>
                    <td style="font-family: dejavusansb; line-height: 20px;">
                        $foldterSection->foldSettlement
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 20px; width: 5%;">
                        
                    </td>
                    <td style="line-height: 20px; width: 60%;">
                        Fekvése:
                    </td>
                    <td style="font-family: dejavusansb; line-height: 20px;">
                        $foldterSection->foldFekvese
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 20px; width: 5%;">
                        
                    </td>
                    <td style="line-height: 20px; width: 60%;">
                        Helyrajzi száma:
                    </td>
                    <td style="font-family: dejavusansb; line-height: 20px;">
                        $foldterSection->foldHrsz
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 20px; width: 5%;">
                        
                    </td>
                    <td style="line-height: 20px; width: 60%;">
                        Művelési ága:
                    </td>
                    <td style="font-family: dejavusansb; line-height: 20px;">
                        $muvelesi_aga
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 20px; width: 5%;">
                        
                    </td>
                    <td style="line-height: 20px; width: 60%;">
                        Területe:
                    </td>
                    <td style="font-family: dejavusansb; line-height: 20px;">
                        $terulete
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 20px; width: 5%;">
                        
                    </td>
                    <td style="line-height: 20px; width: 60%;">
                        Kataszteri tiszta jövedelme (AK):
                    </td>
                    <td style="font-family: dejavusansb; line-height: 20px;">
                        $foldterSection->foldKataszteriTisztaJovedelemAK
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 20px; width: 5%;">
                        
                    </td>
                    <td style="line-height: 20px; width: 60%;">
                        A bérbe adott tulajdoni hányad:
                    </td>
                    <td style="font-family: dejavusansb; line-height: 20px;">
                        $foldterSection->foldBerbeadottTulajdoniHanyad
                    </td>
                </tr>
                <tr>
                    <td style="line-height: 20px; width: 5%;">
                        
                    </td>
                    <td style="line-height: 20px; width: 60%;">
                        A bérbe adott tulajdoni hányadnak megfelelő
                        <br>
                        kataszteri tiszta jövedelem (AK):
                    </td>
                    <td style="font-family: dejavusansb; line-height: 20px;">
                        $foldterSection->foldKataszteriTisztaJovedelemTulajdoniHanyadAK
                    </td>
                </tr>
            </table>
            EOD;
            $i++;
        }
        $pdf->writeHTML($html, false, false, false, false);

        $idotartam = "határozatlan";
        if($data->hasznalatIdotartamHatarozott)
        {
            $tol = Grammar::ev_ho_nap($data->hasznalatbaAdasTol);
            $ig = Grammar::ev_ho_nap($data->hasznalatbaAdasIg);
            $idotartam = $tol." napjától ".$ig." napjáig tartó határozott";
        }

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    2.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    A haszonbérbe adó az 1. pontban meghatározott termőfölde(ke)t $idotartam időtartamra a haszonbérbe vevőnek
                    haszonbérbe adja, a haszonbérbe vevő a termőfölde(ke)t haszonbérbe veszi.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    3.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    Felek büntetőjogi felelősségük tudatában kijelentik, hogy közeli hozzátartozónak minősülnek, mert közöttük 
                    rokoni kapcsolat áll fenn ($data->hasznalatKozeliHozzatart).
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    4.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    A földhasználatba adó a föld használatát a földhasználatba vevőnek ingyenesen engedi át, ezért a szerződő felek földhasználati díjat nem állapítanak meg.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    5.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    A földhasználatba vevő köteles az 1. pontban megjelölt ingatlant a jó gazda gondosságával művelési ágának megfelelően művelni és folyamatosan gondoskodni a termőképességének fenntartásáról. Az ingatlan használatának jogát sem visszterhes, sem ingyenes szerződésben harmadik fél részére nem engedheti át.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $ev_ho_nap = Grammar::ev_ho_nap($data->hasznalatBirtokbavetelIdopont);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    6.
                </td>
                <td style="line-height: 15px; width: 95%;">
                A szerződő felek megállapodnak abban, hogy a jelen szerződés 1. pontjában megjelölt ingatlant 
                $ev_ho_nap 
                napjától a földhasználó birtokba veszi és a szerződés időtartama alatt szedi annak hasznait, viseli terheit.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    7.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    Jelen szívességi földhasználati szerződés megszűnése esetén a földrészletet 
                    – különös tekintettel a gyommentességre – olyan állapotban kell visszaadni a tulajdonosnak, 
                    hogy azon a rendeltetésszerű gazdálkodás azonnal folytatható legyen.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    8.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    Földhasználatba vevő kijelenti, hogy rendelkezik a fentiek szerinti termőföld megműveléséhez 
                    szükséges jogi és személyi feltételekkel és azokat a szerződés hatálya alatt is fenntartja. A földhasználatba 
                    vevő jelen szerződés aláírásával kijelenti, hogy személyében megfelel a Földforgalmi tv. 5. § 7. pontjában foglalt
                    földműves fogalmának.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    9.
                </td>
                <td style="line-height: 15px; width: 95%;">
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

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    10.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    Földhasználatba vevő kijelenti, hogy a szívességi földhasználati szerződés tárgyát képező ingatlan elhelyezkedését, természetbeni határait ismeri.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    11.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    A jelen szívességi földhasználati szerződésben foglalt termőföld területet terhelő közterheket a földhasználatba vevő köteles megfizetni. 
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    12.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    A földhasználókat megillető és jogszabály szerint járó támogatásokat a földhasználatba vevő jogosult igénybe venni.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    13.
                </td>
                <td style="line-height: 15px; width: 95%;">
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

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    14.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    A határozatlan időre kötött szívességi földhasználati szerződés 60 napos felmondási idővel mondható fel. A határozott időtartamú szívességi földhasználati szerződés azonnali hatályú felmondással való megszüntetésére – a szerződő felek eltérő megállapodása hiányában – a haszonbérleti szerződés azonnali hatályú felmondására vonatkozó szabályok az irányadók.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    15.
                </td>
                <td style="line-height: 15px; width: 95%;">
                    A jelen szívességi földhasználati szerződésben nem szabályozott kérdésekben a Polgári Törvénykönyvről szóló 2013. évi V. törvény, továbbá Földforgalmi tv., valamint a mező- és erdőgazdasági földek forgalmáról szóló 2013. évi CXXII. törvénnyel összefüggő egyes rendelkezésekről és átmeneti szabályokról szóló 2013. évi CCXII. törvény szívességi földhasználatra vonatkozó rendelkezései az irányadóak.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

        $html = <<<EOD
        <p style="line-height: 40px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 15px; width: 5%;">
                    16.
                </td>
                <td style="line-height: 15px; width: 95%;">
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

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <p>
            Jelen szívességi földhasználati szerződést megkötő felek, mint szerződéses akaratuknak mindben
             megegyezőt elolvasás és értelmezés után helyben jóváhagyólag írják alá.
        </p>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);

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

        $html = <<<EOD
        <p style="line-height: 30px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    földhasználatba adó
                </td>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    földhasználatba vevő
                </td>
            </tr>
        </table>
    
        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    Előttünk, mint tanúk előtt:
                </td>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    &nbsp;
                </td>
            </tr>
        </table>

        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    aláírás
                </td>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    aláírás
                </td>
            </tr>
        </table>

        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    név
                </td>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    név
                </td>
            </tr>
        </table>

        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    lakcím
                </td>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    lakcím
                </td>
            </tr>
        </table>

        <p style="line-height: 15px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0">
            <tr>
                <td style="line-height: 20px; width: 50%; text-align: center;">
                    _______________________________________
                    <br>
                    személyazonosító okmány száma
                </td>
                <td style="line-height: 20px; width: 50%; text-align: center;">
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
