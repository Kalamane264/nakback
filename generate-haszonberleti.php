<?php
ini_set("display_errors", 0);

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
        <h2 style="text-align: center;">HASZONBÉRLETI SZERZŐDÉS
        </h2>
        EOD;

        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->setFont('dejavusans', '', 9.5, '', true);

        $html = <<<EOD
        <p style="line-height: 20px;">
            &nbsp;
        </p>
        <p style="line-height: 25px;">mely létrejött egyrészről:
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td>Név (családi és utónév):
                </td>
                <td style="font-family:dejavusansb;">$data->fullName
                </td>
            </tr>
            <tr>
                <td>Születési családi és utónév:
                </td>
                <td style="font-family: dejavusansb;">$data->fullNameBirth
                </td>
            </tr>
            <tr>
                <td>Születési hely:
                </td>
                <td style="font-family: dejavusansb;">$data->regionOfBirth
                </td>
            </tr>
            <tr>
                <td>Születési idő:
                </td>
                <td style="font-family: dejavusansb;">$data->dateOfBirth
                </td>
            </tr>
            <tr>
                <td>Anyja születési neve:
                </td>
                <td style="font-family: dejavusansb;">$data->mothersName
                </td>
            </tr>
            <tr>
                <td>Lakcím: 
                </td>
                <td style="font-family: dejavusansb;">$data->postalCode $data->settlement, <br>$data->kozterNev $data->kozterTipus <br>$data->hazSzam
                </td>
            </tr>
            <tr>
                <td>Személyi szám:
                </td>
                <td style="font-family: dejavusansb;">$data->szemSzam
                </td>
            </tr>
            <tr>
                <td>Adóazonosító jel:
                </td>
                <td style="font-family: dejavusansb;">$data->adoazonJel
                </td>
            </tr>
            <tr>
                <td>Állampolgárság:
                </td>
                <td style="font-family: dejavusansb;">$data->nationality
                </td>
            </tr>
        </table>
        <p style="line-height: 10px;">
          &nbsp;
        </p>
        <div style="line-height: 15px;">mint<span style="font-family: dejavusansb;">haszonbérbe adó,
            </span><br />
            másrészről:
        </div>
        <p style="line-height: 5px;">
          &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            <tr>
                <td>Név (családi és utónév):
                </td>
                <td style="font-family:dejavusansb;">$data->hVevoFullName
                </td>
            </tr>
            <tr>
                <td>Születési családi és utónév:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoFullNameBirth
                </td>
            </tr>
            <tr>
                <td>Születési hely:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoRegionOfBirth
                </td>
            </tr>
            <tr>
                <td>Születési idő:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoDateOfBirth
                </td>
            </tr>
            <tr>
                <td>Anyja születési neve:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoMothersName
                </td>
            </tr>
            <tr>
                <td>Lakcím: 
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoPostalCode
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
                <td>Személyi szám:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoSzemSzam
                </td>
            </tr>
            <tr>
                <td>Adóazonosító jel:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoAdoazonJel
                </td>
            </tr>
            <tr>
                <td>Állampolgárság:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoNationality
                </td>
            </tr>
            <tr>
                <td>Földműves nyilvántartási szám:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoFoldmuvesNyilvtartSzam
                </td>
            </tr>
            <tr>
                <td>Kamarai tagsági azonosító szám:
                </td>
                <td style="font-family: dejavusansb;">$data->hVevoKamaraTagAzonSzam
                </td>
            </tr>
        </table>
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <div style="line-height: 15px;">mint <span style="font-family: dejavusansb;">haszonbérbe vevő</span> együtt a továbbiakban: 
            <span style="font-family: dejavusansb;">szerződő felek </span>között a mező- és erdőgazdasági 
            földek forgalmáról szóló 2013. évi CXXII. törvény (a továbbiakban: Földforgalmi tv.) szerinti mező-, 
            erdőgazdasági hasznosítású föld (a továbbiakban: termőföld) fogalma alá tartozó földrészlet használata 
            tárgyában az alulírt helyen és időben, az alábbi feltételekkel.
        </div>
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">1.
                </td>
                <td style="width: 95%;">A szerződő felek rögzítik, hogy jelen okirat aláírásával egyidejűleg haszonbérleti szerződést kötnek a haszonbérbe adó alább szereplő tulajdoni hányadát képező ingatlan(ok) vonatkozásában.
                </td>
            </tr>
        </table>
        EOD;

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
                    <td style="width: 5%;">$sorsz
                    </td>
                    <td style="width: 60%;">A föld fekvése szerinti település neve:
                    </td>
                    <td style="font-family: dejavusansb;">$foldterSection->foldSettlement
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">Fekvése:
                    </td>
                    <td style="font-family: dejavusansb;">$foldterSection->foldFekvese
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">Helyrajzi száma:
                    </td>
                    <td style="font-family: dejavusansb;">$foldterSection->foldHrsz
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">Művelési ága:
                    </td>
                    <td style="font-family: dejavusansb;">$muvelesi_aga
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">Területe:
                    </td>
                    <td style="font-family: dejavusansb;">$terulete
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">Kataszteri tiszta jövedelme (AK):
                    </td>
                    <td style="font-family: dejavusansb;">$foldterSection->foldKataszteriTisztaJovedelemAK
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">A használatba adott tulajdoni hányad:
                    </td>
                    <td style="font-family: dejavusansb;">$foldterSection->foldBerbeadottTulajdoniHanyadX / $foldterSection->foldBerbeadottTulajdoniHanyadY
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">Használatba adott tulajdoni hányadnak megfelelő terület:
                    </td>
                    <td style="font-family: dejavusansb;">$teruleteHanyad
                    </td>
                </tr>
                <tr>
                    <td style="width: 5%;">
                        
                    </td>
                    <td style="width: 60%;">A használatba adott tulajdoni hányadnak megfelelő
                        <br>
                        kataszteri tiszta jövedelem (AK):
                    </td>
                    <td style="font-family: dejavusansb;">$foldterSection->foldKataszteriTisztaJovedelemTulajdoniHanyadAK
                    </td>
                </tr>
            </table>
            EOD;
            $i++;
        }
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $tol = Grammar::ev_ho_nap($data->berbeAdasTol);
        $ig = Grammar::ev_ho_nap($data->berbeAdasIg);
        $idotartam = $tol." napjától ".$ig." napjáig tartó határozott";

        $kettespont =  <<<EOD
        A haszonbérbe adó az 1. pontban meghatározott termőfölde(ke)t
        $idotartam
        időtartamra a haszonbérbe vevőnek haszonbérbe adja, a haszonbérbe vevő a termőfölde(ke)t haszonbérbe veszi.
        EOD;

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">2.
                </td>
                <td style="width: 95%;">$kettespont                    
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $harmaspont =  <<<EOD
        A szerződő felek megállapodnak abban, hogy a haszonbér pénzben kerül teljesítésre.
        EOD;

        if($data->dijMegallapitasModja == "3")
        {
            $harmaspont =  <<<EOD
            A szerződő felek megállapodnak abban, hogy a haszonbér természetben kerül teljesítésre.
            EOD;
        }

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">3.
                </td>
                <td style="width: 95%;">$harmaspont
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $negyespont = "";

        if($data->dijMegallapitasModja == "3")
        {
            $negyespont =  <<<EOD
            Haszonbérbe vevő köteles az 1. pontban megjelölt termőföld használati jogának átengedése fejében évente a 
            haszonbérbe adó részére a megállapodott természetbeni haszonbér megfizetésére, 
            amely $data->dijMegallapitasModjaEspedig.
            Az utolsó bérleti évben évben haszonbérbe adó $data->milyenBerletiDijraJogosult haszonbérre jogosult. 
            EOD;
        }
        else
        {
            $negyespont =  <<<EOD
            Haszonbérbe vevő köteles az 1. pontban megjelölt termőföld használati jogának átengedése fejében évente a 
            haszonbérbe adó részére hektáronként 
            $data->evesBerletiDij,- Ft-ot azaz, $data->evesBerletiDijAzaz forintot, legkésőbb tárgyév június hó 01. napjáig a 
            haszonbérbe adó $data->berbeAdoBankszamlaszama számú bankszámlaszámára banki
            átutalással megfizetni. Az utolsó bérleti évben a haszonbérbe adó teljes évi bérleti díjra jogosult, 
            melynek összege $data->evesBerletiDij,- Ft azaz, $data->evesBerletiDijAzaz forint, melyet a haszonbérlő köteles a 
            szerződés lejártának napjáig banki átutalással teljesíteni.
            EOD;
        }

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">4.
                </td>
                <td style="width: 95%;">$negyespont
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $otospont = "";
        if($data->vanMezeiLeltar == "1")
        {
            $otospont =  <<<EOD
            A szerződő felek kijelentik, hogy a szerződés tárgyát képező ingatlan(ok) vonatkozásában
            mezei leltár ellenértéke 
            $data->leltarEllenerteke,- Ft azaz, $data->leltarEllenertekeAzaz forint, 
            amire a haszonbérbe vevő igényt tart, 
            amennyiben az ingatlan(ok) vonatkozásában haszonbérleti joga az előhaszonbérleti rangsorban őt megelőző 
            személyhez, vagy szervezethez kerülne. A mezei leltár ellenértékét a haszonbérleti szerződés mezőgazdasági 
            igazgatási szervnél történő bejegyzését követő nyolc napon belül egy összegben kell megfizetni a haszonbérbe vevő
            $data->berbeVevoBankszamlaszama számú bankszámlaszámára.
            EOD;
        }
        else
        {
            $otospont =  <<<EOD
            A szerződő felek kijelentik, hogy a szerződés tárgyát képező ingatlan(ok) vonatkozásában
            mezei leltárt nem állapítanak meg. 
            EOD;
        }

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">5.
                </td>
                <td style="width: 95%;">$otospont
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
                <td style="width: 5%;">6.
                </td>
                <td style="width: 95%;">A termőföld (ek)re vonatkozó földadót és egyéb közterheket a haszonbérbe vevő viseli a szerződés fennállásának idejére.
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
                <td style="width: 5%;">7.
                </td>
                <td style="width: 95%;">A szerződő felek kijelentik, hogy a földhasználathoz kapcsolódó vagyoni értékű jog –
                különösen a területalapú támogatás igénybevételéhez fűződő jogosultság – a haszonbérleti szerződés fennállása alatt a haszonbérbe vevőt, annak megszűnésével a termőföld tulajdonosát illeti meg, továbbá megállapodnak abban, hogy haszonbérbe adó a terület vadászati hasznosításának jogával kapcsolatos döntési jogkört haszonbérlőnek a szerződés időtartamára átengedi.
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
                <td style="width: 5%;">8.
                </td>
                <td style="width: 95%;">A haszonbérbe vevő köteles az 1. pontban megjelölt ingatlan(oka)t a jó gazda gondosságával művelési ágának megfelelően művelni és folyamatosan gondoskodni a termőképességének fenntartásáról. E körben köteles betartani a természetvédelmi, környezetvédelmi és talajvédelmi előírásokat. Az ingatlan használatának jogát sem visszterhes, sem ingyenes szerződésben harmadik fél részére nem engedheti át, kivéve a haszonbérbeadó kizárólagos hozzájárulásával a mező- és erdőgazdasági földek 
                forgalmáról szóló 2013. évi CXXII. törvénnyel összefüggő egyes rendelkezésekről
                és átmeneti szabályokról szóló 2013. évi CCXII törvény (a továbbiakban: Fétv.) 64.-65.§-ban az alhaszonbérletre vonatkozó eseteket, továbbá a haszonbérelt területen épületet vagy egyéb építményt nem létesíthet, azt csak mezőgazdasági művelés céljára használhatja. A haszonbérbe vevő köteles gondoskodni a tápanyag visszapótlásról, a talaj szükséges megműveléséről, a gyomírtásról.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $hasznalatbaVetelKezdoIdopont = Grammar::ev_ho_nap($data->hasznalatbaVetelKezdoIdopont);
        $hasznalatVege = Grammar::ev_ho_nap($data->hasznalatVege);

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">9.
                </td>
                <td style="width: 95%;">A szerződő felek megállapodnak abban, hogy a jelen szerződés 1. pontjában megjelölt
                ingatlan(oka)t $hasznalatbaVetelKezdoIdopont napjától a haszonbérbe vevő használja. 
                A használat vége: $hasznalatVege nap.
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
                <td style="width: 5%;">10.
                </td>
                <td style="width: 95%;">A haszonbérbe vevő nyilatkozik, hogy olyan természetes személy, aki megfelel a Földforgalmi tv. 40. § (1) bekezdésében foglalt feltételeknek. Haszonbérlő kötelezettséget vállal arra, hogy a haszonbérleti szerződés hatályának teljes tartama alatt megfelel a földműves jogállásnak. Vállalja, hogy a föld használatát másnak nem engedi át, azt rendeltetésszerűen maga használja és ennek során eleget tesz a földhasznosítási kötelezettségének.
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
                <td style="width: 5%;">11.
                </td>
                <td style="width: 95%;">A haszonbérbe vevő nyilatkozik, hogy nincs jogerősen megállapított és fennálló földhasználati díj- vagy egyéb tartozása.
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
                <td style="width: 5%;">12.
                </td>
                <td style="width: 95%;">A haszonbérbe vevő nyilatkozik, hogy a használati jog megszerzését megelőző 5 éven belül nem állapították meg, hogy a szerzési korlátozások megkerülésére irányuló jogügyletet kötött, továbbá, hogy bármilyen jogcímen használatában, birtokában lévő termőföld – a most megszerezni kívánt területtel együtt – sem éri el az 1200 ha-t, a tulajdona pedig a 300 ha-t.
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
                <td style="width: 5%;">13.
                </td>
                <td style="width: 95%;">A haszonbérbe vevő elfogadja, és tudomásul veszi, hogy ha utólagos ellenőrzés során
                jogerősen megállapításra kerül az 12. pont szerinti nyilatkozatának valótlansága, úgy az a Büntető Törvénykönyv (2012. évi C. törvény) szerinti büntetőjogi felelősségre vonását, valamint a haszonbérleti szerződés tárgyát képező föld használata után a jogsértő állapot fennállásának időtartama alatt, a jogsértéssel érintett földterület után a részére folyósított költségvetési vagy európai uniós támogatásnak megfelelő összegű pénzösszeg visszafizetését vonja maga után.
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $tizennegyespont = "";

        if($data->elohaszonberletiJog)
        {
            $tizennegyespont = <<<EOD
            A haszonbérbe vevő kijelenti, hogy a Földforgalmi tv. 46. §
            <ul>
                <li>(1) bekezdés b) pontja szerint, mint olyan földművest, aki helyben lakó szomszédnak minősül
                </li>
                <li>(1) bekezdés d) pontja szerint, mint olyan földművest, akinek a lakóhelye vagy a mezőgazdasági üzemközpontja legalább 3 éve azon a településen van, amelynek közigazgatási határa a haszonbérlet tárgyát képező föld fekvése szerinti település közigazgatási határától közúton vagy közforgalom elől el nem zárt magánúton legfeljebb 20 km távolságra van    
                </li>
                <li>(3) bekezdés a) pontja szerint, mint a föld fekvése szerinti településen az előhaszonbérleti joga gyakorlását megelőzően legalább 3 éve állattartó telepet üzemeltető azon helyben lakó földművest, aki haszonbérletének a célja az állattartáshoz szükséges és azzal arányban álló takarmányszükséglet biztosítása és rendelkezik az e törvény végrehajtására kiadott rendeletben meghatározott állatsűrűséggel
                </li>
            </ul>
            EOD;
        }
        else
        {
            $tizennegyespont = "A haszonbérbe vevő kijelenti, hogy őt a Földforgalmi tv. 46. § alapján előhaszonbérleti jog nem illeti meg.";
        }

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">14.
                </td>
                <td style="width: 95%;">$tizennegyespont
                </td>
            </tr>
        </table>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $tizenotospont = "";

        if($data->hVevoStatus != "0")
        {
            $tizenotospont = <<<EOD
            A haszonbérbe vevő kijelenti, hogy a Földforgalmi tv. 46. § (4) bekezdés
            <ul>
                <li>$data->hVevoStatus
                </li>
            </ul>
            EOD;
        }
        else
        {
            $tizenotospont = "A haszonbérbe vevő kijelenti, hogy a Földforgalmi tv. 46. § (4) bekezdése nem vonatkozik rá.";
        }

        $html = <<<EOD
        <p style="line-height: 10px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            <tr>
                <td style="width: 5%;">15.
                </td>
                <td style="width: 95%;">$tizenotospont
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
                <td style="width: 5%;">16.
                </td>
                <td style="width: 95%;">Szerződő felek tudomásul veszik, hogy az 1. pontban írt ingatlan(ok) vonatkozásában a
                Földforgalmi tv. 46. §-ban foglalt személyeknek előhaszonbérleti joga áll fenn. Az előhaszonbérleti jogra jogosultak tájékoztatása érdekében a haszonbérbe adó köteles e szerződést a Földforgalmi tv. 49. §-ban foglaltaknak megfelelően a szerződő felek aláírását követően 8 napon belül a mezőgazdasági igazgatási szerv részére meg küldeni jóváhagyás céljából. Ha a mezőgazdasági igazgatási szerv megállapítja a szerződés közzétételre való alkalmasságát, hivatalból rendeli el a haszonbérleti szerződés közzétételét.
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
                <td style="width: 5%;">17.
                </td>
                <td style="width: 95%;">Haszonbérbe vevő kijelenti, hogy a haszonbérleti szerződés tárgyát képező ingatlan(ok)
                elhelyezkedését, természetbeni határait ismeri.
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
                <td style="width: 5%;">18.
                </td>
                <td style="width: 95%;">A szerződő felek tudomással bírnak arról, hogy jelen haszonbérleti szerződés megszűnik
                    <ul>
                        <li>a szerződésben meghatározott időtartam lejártával, a lejárat napján;
                        </li>
                        <li>közös megegyezéssel, a szerződő felek által meghatározott napon;
                        </li>
                        <li>a haszonbérbe vevő természetes személy halálával, feltéve, hogy az örökösök a Polgári
                            Törvénykönyvről szóló 2013. évi V. törvényben (a továbbiakban: Ptk.) meghatározott felmondási jogukat az ott meghatározott határidőben gyakorolják;
                        </li>
                        <li>azonnali hatályú felmondással,
                        </li>
                        <li>a Fétv. 60. §-ban meghatározott felmondással;
                        </li>
                        <li>ha a föld természeti erő közvetlen behatása következtében egészben vagy jelentős részben a haszonbérleti szerződés szerinti hasznosításra tartósan alkalmatlanná válik.
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
                <td style="width: 5%;">19.
                </td>
                <td style="width: 95%;"A haszonbérbe adó a szerződést azonnali hatállyal jogosult felmondani, ha a haszonbérbe
                    vevő
                    <ul>
                        <li>az írásban közölt felhívás ellenére nem tesz eleget a hasznosítási kötelezettségének vagy olyan gazdálkodást folytat, amely veszélyezteti a föld termőképességét,
                        </li>
                        <li>a haszonbérbe adó hozzájárulása nélkül vagy attól eltérően a föld használatát másnak átengedte, más célra hasznosította, a földművelési ágát megváltoztatta vagy a földet a termőföld védelméről szóló törvényben meghatározottak szerint más célra  hasznosította,
                        </li>
                        <li>a természetvédelmi jogszabályok vagy a természetvédelmi hatóság jogszabály alapján hozott előírásaitól eltérő, illetőleg a természeti terület állagát vagy állapotát kedvezőtlenül befolyásoló tevékenységet folytat, továbbá, ha a természeti értékek fennmaradását bármely módon veszélyezteti,
                        </li>
                        <li>a haszonbért vagy a földdel kapcsolatos terheket a lejárat után, írásban közölt felszólítás ellenére, a felszólítás közlésétől számított 15 napon belül sem fizeti meg.
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
                <td style="width: 5%;">20.
                </td>
                <td style="width: 95%;">A haszonbérbe vevő azonnali hatállyal jogosult felmondani a szerződést, ha az egészségi
                állapota oly mértékben romlik meg, vagy családi és életkörülményeiben olyan tartós változás következik be, amely a haszonbérletből eredő kötelezettségeinek teljesítését akadályozza vagy azt jelentősen megnehezíti.
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
                <td style="width: 5%;">21.
                </td>
                <td style="width: 95%;">A haszonbérleti szerződés megszűnésekor a szerződő felek egymással kötelesek elszámolni. A haszonbérleti szerződés megszűnésekor a haszonbérbe vevő az általa létesített berendezési és felszerelési tárgyakat a területről elviheti.
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
                <td style="width: 5%;">22.
                </td>
                <td style="width: 95%;">A szerződő felek tudomásul veszik, hogy jelen haszonbérleti szerződés érvényességének,
                földhasználati nyilvántartásban történő bejegyzésének előfeltétele a szerződés mezőgazdasági igazgatási szerv részéről történő jóváhagyása.
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
                <td style="width: 5%;">23.
                </td>
                <td style="width: 95%;">A jelen haszonbérleti szerződésben nem szabályozott kérdésekben a Ptk., továbbá
                Földforgalmi tv., valamint a Fétv. földhasználatra vonatkozó rendelkezései az irányadóak.
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
                <td style="width: 5%;">24.
                </td>
                <td style="width: 95%;">A szerződő felek rögzítik, hogy cselekvőképes magyar állampolgárok, szerződéskötési
                képességük nem korlátozott.
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
                <td style="width: 5%;">25.
                </td>
                <td style="width: 95%;">A szerződő felek kijelentik, hogy a szerződéssel kapcsolatban esetlegesen keletkező
                jogvitáikat elsősorban egyeztetés útján rendezik. Amennyiben az egyeztetés sikertelen, úgy erre az esetre alávetik magukat az ingatlan fekvése szerinti illetékes járásbíróság eljárásának.
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
        <p>Jelen haszonbérleti szerződést megkötő felek, mint szerződéses akaratuknak mindben megegyezőt elolvasás és értelmezés után helyben jóváhagyólag írják alá.
        </p>
        EOD;
        $pdf->writeHTML($html, false, false, false, false);
        $pdf->setMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

        $html = <<<EOD
        <p style="line-height: 30px;">
            &nbsp;
        </p>
        <p> Kelt: ____________________________________, ________ év __________________________ hó ______ nap
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
                    haszonbérbe adó
                </td>
                <td style="text-align: center;">
                    _______________________________________
                    <br>
                    haszonbérbe vevő
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

        $filename = "haszonberleti_".date("yyyy_m_d_".str_replace(array(' ', '.'), '', (string)microtime())).".pdf";
        $pdf->Output( getcwd().'/pdfs/'.$filename, 'F');
        echo '{"filename":"'.$filename.'"}';
    }
}

new Szivessegi();
?>
