<?php
ini_set("display_errors", 1);

require_once("TCPDF/tcpdf.php");
require_once("models/dataSzivessegi.php");
require_once("managers/grammar.php");

class Ocsg
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

        $idotartam = "határozatlan időtartamra";
        if($data->hasznalatIdotartamHatarozott)
        {
          $idotartam = $data->evre.". évre";
        }


        $html = <<<EOD
        <h2 style="text-align: center; line-height: 16px;">
          SZERZŐDÉS ŐSTERMELŐK CSALÁDI GAZDASÁGÁNAK LÉTREHOZÁSÁRÓL
        </h2>
        <p style="line-height: 4px;">
            &nbsp;
        </p>
        <p style="line-height: 14px;">Alulírott, egymással hozzátartozói láncolatban álló mezőgazdasági őstermelő tagok az alábbi napon és helyen, a családi gazdaságokról szóló 2020. CXXIII. törvény (a továbbiakban: Törvény) rendelkezései alapján, 
          $idotartam
           a következőkben meghatározott tartalommal őstermelők családi gazdaságának alapítását határozták el.
        </p>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <h2 style="line-height: 16px;">I. AZ ŐSTERMELŐK CSALÁDI GAZDASÁGÁT LÉTREHOZÓ MEZŐGAZDASÁGI ŐSTERMELŐ TAGOK
        </h2>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <p style="line-height: 16px;">Az őstermelők családi gazdaságának mezőgazdasági őstermelő tagjai (a továbbiakban együttesen: őstermelők családi gazdaságának tagjai, vagy tagok):
        </p>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
EOD;
        $tagok = "";

        $i = 1;
        foreach($data->ocsTags as $ocsTag)
        {
          $tagok .= <<<EOD
          <tr>
            <td style="width: 10%;">
              <b>I/$i.</b>
            </td>
            <td style="width: 45%;">Családi és utónév:
            </td>
            <td>$ocsTag->fullName
            </td>
          </tr>
          
          <tr>
            <td style="width: 10%;">
              &nbsp;
            </td>
            <td style="width: 45%;">Születési családi és utónév:
            </td>
            <td>$ocsTag->fullNameBirth
            </td>
          </tr>

          <tr>
            <td style="width: 10%;">
              &nbsp;
            </td>
            <td style="width: 45%;">Születési hely, idő:
            </td>
            <td>$ocsTag->regionOfBirth, $ocsTag->dateOfBirth
            </td>
          </tr>

          <tr>
            <td style="width: 10%;">
              &nbsp;
            </td>
            <td style="width: 45%;">Anyja születési családi és utóneve:
            </td>
            <td>$ocsTag->mothersName
            </td>
          </tr>

          <tr>
            <td style="width: 10%;">
              &nbsp;
            </td>
            <td style="width: 45%;">Adóazonosító jel:
            </td>
            <td>$ocsTag->adoazonJel
            </td>
          </tr>

          <tr>
            <td style="width: 10%;">
              &nbsp;
            </td>
            <td style="width: 45%;">Lakcím:
            </td>
            <td>$ocsTag->postalCode $ocsTag->settlement, <br>$ocsTag->kozterNev $ocsTag->kozterTipus $ocsTag->hazSzam $ocsTag->emeletAjto
            </td>
          </tr>

          <tr>
            <td style="width: 10%;">
              &nbsp;
            </td>
            <td style="width: 45%;">Családtag neve, akivel hozzátartozói viszonyát megadja:
            </td>
            <td>$ocsTag->csaladtagNeve
            </td>
          </tr>

          <tr>
            <td style="width: 10%;">
              &nbsp;
            </td>
            <td style="width: 45%;">Hozzátartozói minősége:
            </td>
            <td>$ocsTag->hozzatartMinoseg
            </td>
          </tr>

          <tr>
            <td style="line-height: 10px;">
              &nbsp;
            </td>
          </tr>
EOD;
          
          $i++;
        }

        $ovtj = $data->ovtj;

        $html .= <<<EOD
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            $tagok
        </table>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <p style="line-height: 16px;">Az őstermelők családi gazdaságának tagjai kijelentik, hogy személyükben a Törvény rendelkezései értelmében mezőgazdasági őstermelőnek minősülnek, és más őstermelők családi gazdaságának nem tagjai.
        </p>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <h2 style="line-height: 16px;">II. AZ ŐSTERMELŐK CSALÁDI GAZDASÁGÁNAK CÉLJA, TEVÉKENYSÉGI KÖRE
        </h2>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <p style="line-height: 16px;"><b>II/1.</b> Jelen szerződés I. pontjában szereplő őstermelők családi gazdaságának tagjai egybehangzóan úgy határoznak, hogy jelen szerződéssel őstermelők családi gazdaságát hoznak létre, amelyben a tagok az őstermelői tevékenységüket saját gazdaságukban közösen, valamennyi mezőgazdasági őstermelő tag személyes közreműködésén alapulva, összehangoltan végzik.
        </p>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <p style="line-height: 16px;"><b>II/2.</b> A társaság tevékenységi köre
        </p>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
          <tr>
            <td style="width: 45%;">Mező-, erdőgazdasági tevékenységek:
            </td>
            <td>$ovtj->kod $ovtj->nev
            </td>
          </tr>
          <tr>
            <td>
              &nbsp;
            </td>
          </tr>
          <tr>
            <td style="width: 45%;">Kiegészítő tevékenységek:
            </td>
            <td>$ovtj->kieg
            </td>
          </tr>
        </table>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <h2 style="line-height: 16px;">III. AZ ŐSTERMELŐK CSALÁDI GAZDASÁGA TAGJAINAK VAGYONI HOZZÁJÁRULÁSA
        </h2>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
        <p style="line-height: 16px;">Jelen szerződés I. pontjában szereplő őstermelők családi gazdaságának tagjai az általuk végzett közös gazdálkodás érdekében, mezőgazdasági őstermelőként személyes használatukban lévő, valamennyi mező-, erdőgazdasági hasznosítású földnek, mezőgazdasági őstermelőként személyes használatukban lévő mezőgazdasági termelőeszközöknek, az azokhoz kapcsolódó vagyoni értékű jogoknak rendelkezésre bocsátásáról a következőképpen rendelkeznek.
        </p>
        <p style="line-height: 1px;">
            &nbsp;
        </p>
EOD;
        $vagyoniHjs = "";
        $i = 0;
        $counterForPoints = 0;
        foreach($data->ocsTags as $ocsTag)
        {
          $termofoldek = "";
          foreach($data->ocsgTagVagyoniHjPageDTOs[$i]->ocsTagVagyoniHjDTOs as $vagyoniHj)
          {
            $terulet = $vagyoniHj->foldTeruletHektar? $vagyoniHj->foldTeruletHektar." ha" : $vagyoniHj->foldTeruletM2." m2";

            $muvAgs = "";
            $first = true;
            foreach($vagyoniHj->foldMuvAgs as $muvAg)
            {
              if($muvAg->checked)
              {
                if(!$first)
                {
                  $muvAgs .= "<br>";
                }

                $muvAgs .= $muvAg->text;
                $first = false;
              }
            }

            if(!$muvAgs)
              continue;

            $termofoldek .= <<<EOD
            <tr>
              <td border="1">$vagyoniHj->telepules
              </td>
              <td border="1">$vagyoniHj->hrsz
              </td>
              <td border="1">$terulet
              </td>
              <td border="1">$vagyoniHj->akErtek
              </td>
              <td border="1">$muvAgs
              </td>
              <td border="1">$vagyoniHj->jogcim
              </td>
            </tr>
EOD;
          }

          $termeloeszkozokIngatlan = "";
          foreach($data->ocsgVagyontargyakPageDTOs[$i]->ocsgIngatlanDTOs as $ocsgIngatlan)
          {
            if(!$ocsgIngatlan->megnevezes)
              continue;

            $termeloeszkozokIngatlan .= <<<EOD
              <tr>
                <td border="1">$ocsgIngatlan->megnevezes
                </td>
                <td border="1">$ocsgIngatlan->postalCode $ocsgIngatlan->settlement
                </td>
                <td border="1">$ocsgIngatlan->kozterNev $ocsgIngatlan->kozterTipus $ocsgIngatlan->hazSzam $ocsgIngatlan->emeletAjto
                </td>
                <td border="1">$ocsgIngatlan->jogcim
                </td>
              </tr>
EOD;
          }

          $termeloeszkozokIngo_1_2 = "";
          $termeloeszkozokIngo_3 = "";
          foreach($data->ocsgVagyontargyakPageDTOs[$i]->ocsgIngosagDTOs as $ocsgIngosag)
          {
            if(!$ocsgIngosag->vagyontargyTipusa)
              continue;

            if($ocsgIngosag->vagyontargyTipusa == 3)
            {
              $termeloeszkozokIngo_3 .= <<<EOD
              <tr>
                <td border="1">$ocsgIngosag->megnevezes
                </td>
                <td border="1">$ocsgIngosag->allatallomanyMenyisege
                </td>
                <td border="1">$ocsgIngosag->jogcim
                </td>
              </tr>
EOD;
            }
            else
            {
              $termeloeszkozokIngo_1_2 .= <<<EOD
              <tr>
                <td border="1">$ocsgIngosag->megnevezes
                </td>
                <td border="1">$ocsgIngosag->azonosito
                </td>
                <td border="1">$ocsgIngosag->rendszam
                </td>
                <td border="1">$ocsgIngosag->jogcim
                </td>
              </tr>
EOD;
            }
          }

          $i++;

          if($termofoldek || $termeloeszkozokIngatlan || $termeloeszkozokIngo_1_2 || $termeloeszkozokIngo_3)
          {
            $counterForPoints++;
            $vagyoniHjs .= <<<EOD
            <p style="line-height: 16px;"><b>III/1.$counterForPoints.</b> Jelen szerződés I/$i. pontjában rögzített 
              $ocsTag->fullName nevű 
              mezőgazdasági őstermelő tag jelen szerződéssel létrehozásra kerülő őstermelők családi gazdaságában való tagsági jogviszonyának fennállásáig, illetve az általa, mint mezőgazdasági őstermelőként határozott időtartamban használt termőföld esetén a használati jogosultsága fennállásáig, az őstermelők családi gazdaságának rendelkezésére bocsájtja az alábbi ingatlanokat és ingóságokat:
            </p>
EOD;
          }
          
          if($termofoldek)
          {
            $vagyoniHjs .= <<<EOD
            <p style="line-height: 16px;"><b>Mező-, erdőgazdasági hasznosítású termőföldek
              </b>
            </p>
            <table cellspacing="0" cellpadding="2" border="0" style="line-height: 15px; text-align: center;">
              <tr>
                <td>Település
                </td>
                <td>Helyrajzi szám
                </td>
                <td>Hektár/m2
                </td>
                <td>AK érték
                </td>
                <td>Művelési ág
                </td>
                <td>Jogcím
                </td>
              </tr>
              <tr>
                <td style="line-height: 4px;">
                  &nbsp;
                </td>
              </tr>
              $termofoldek
            </table>
EOD;
          }
          if($termeloeszkozokIngatlan)
          {
            $vagyoniHjs .= <<<EOD
            <p style="line-height: 1px;">
                &nbsp;
            </p>
            <p style="line-height: 16px;"><b>Mezőgazdasági termelőeszközök (ingatlan vagyontárgyak)
              </b>
            </p>
            <table cellspacing="0" cellpadding="2" border="0" style="line-height: 15px; text-align: center;">
              <tr>
                <td>Megnevezés
                </td>
                <td>Település
                </td>
                <td>Cím
                </td>
                <td>Jogcím
                </td>
              </tr>
              <tr>
                <td style="line-height: 4px;">
                  &nbsp;
                </td>
              </tr>
              $termeloeszkozokIngatlan
            </table>
EOD;
          }

          if($termeloeszkozokIngo_1_2)
          {
            $vagyoniHjs .= <<<EOD
            <p style="line-height: 1px;">
                &nbsp;
            </p>
            <p style="line-height: 16px;"><b>Mezőgazdasági termelőeszközök (ingó vagyontárgyak)
              </b>
            </p>
            <table cellspacing="0" cellpadding="2" border="0" style="line-height: 15px; text-align: center;">
              <tr>
                <td>Megnevezés
                </td>
                <td>Azonosító
                </td>
                <td>Rendszám
                </td>
                <td>Jogcím
                </td>
              </tr>
              <tr>
                <td style="line-height: 4px;">
                  &nbsp;
                </td>
              </tr>
              $termeloeszkozokIngo_1_2
            </table>
EOD;
          }
          if($termeloeszkozokIngo_3)
          {
            $vagyoniHjs .= <<<EOD
            <p style="line-height: 1px;">
                &nbsp;
            </p>
            <p style="line-height: 16px;"><b>Mezőgazdasági termelőeszközök (ingó vagyontárgyak - állatállomány)
              </b>
            </p>
            <table cellspacing="0" cellpadding="2" border="0" style="line-height: 15px; text-align: center;">
              <tr>
                <td>Megnevezés
                </td>
                <td>Darabszám
                </td>
                <td>Jogcím
                </td>
              </tr>
              <tr>
                <td style="line-height: 4px;">
                  &nbsp;
                </td>
              </tr>
              $termeloeszkozokIngo_3
            </table>
EOD;
          }

          if($termofoldek || $termeloeszkozokIngatlan || $termeloeszkozokIngo_1_2 || $termeloeszkozokIngo_3)
          {
            $vagyoniHjs .= <<<EOD
            <p>
                &nbsp;
            </p>
EOD;
          }
        }

        $html .= $vagyoniHjs;

        $html .= <<<EOD
        <p style="line-height: 16px;"><b>III/2.</b> A III/1. pontban felsorolt ingatlanokat és ingóságokat az őstermelők családi gazdaságának tagjai a <b><i>jelen szerződés hatályba lépésével egyidejűleg</i></b> az őstermelők családi gazdaságának a rendelkezésére bocsájtják.
        </p>
        <p style="line-height: 16px;"><b>III/3.</b> Az őstermelők családi gazdaságának tagjai tudomásul veszik, hogy a teljesítendő vagyoni hozzájárulásuk után kamat, díjazás, vagy más egyéb típusú jövedelem nem jár.
        </p>
        <p style="line-height: 16px;"><b>III/4.</b> Az őstermelők családi gazdaságának tagjai rögzítik, hogy ha valamelyik tag nem szolgáltatja a jelen szerződés III. pontjában rögzített hozzájárulást, bármelyik tag követelheti tőle a szerződésszerű teljesítést.
        </p>
        <p>
          &nbsp;
        </p>
        <h2 style="line-height: 16px;">IV. A TAGOK SZEMÉLYES KÖZREMŰKÖDÉSE 
        </h2>
        <p style="line-height: 16px;"><b>IV/1.</b> Az őstermelők családi gazdaságának tagjai kötelesek az őstermelők családi gazdaságának tevékenységében személyesen közreműködni.
        </p>
        <p style="line-height: 16px;"><b>IV/2.</b> A tagok személyes közreműködésének formája:
        </p>
EOD;
        $kozremukodesek = "";
        
        $counter = 0;
        foreach($data->ocsTags as $ocsTag)
        {
          $counter++;
          $kozremukodesek .= <<<EOD
          <tr>
            <td style="width: 5%;">
              &nbsp;
            </td>
            <td style="width: 45%;">Jelen szerződés I/$counter. pontjában szereplő tag:
            </td>
            <td>$ocsTag->szemelyesKozremukodesFormaja
            </td>
          </tr>
          <tr>
            <td style="line-height: 4px;">
              &nbsp;
            </td>
          </tr>
EOD;
        }

        $html .= <<<EOD
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 15px;">
            $kozremukodesek
        </table>
EOD;

        $html .= <<<EOD
        <p style="line-height: 16px;"><b>IV/3.</b> Az őstermelők családi gazdaságának tagjai tudomásul veszik, hogy személyes közreműködésükért díjat nem számíthatnak fel.
        </p>
EOD;


        $pdf->writeHTML($html, true, false, false, false);
        $filename = "ocsg_".date("yyyy_m_d_".str_replace(array(' ', '.'), '', (string)microtime())).".pdf";
        $pdf->Output( getcwd().'/pdfs/'.$filename, 'F');
        echo '{"filename":"'.$filename.'"}';
    }
}

new Ocsg();
?>
