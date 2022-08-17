<?php
ini_set("display_errors", 0);

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
        <p style="line-height: 16px;"><b>IV/4.</b> Rendelkezés a nyereség és veszteség tagok közötti megosztásáról
        </p>
EOD;

        $reszesedesek = "";

        if($data->nyeresegVesztesegMegosztasa == 2)
        {
          $html .= <<<EOD
          <p style="line-height: 16px;">A tagok a közös tevékenység nyereségét és veszteségét egymás között az alábbi arányban osztják fel:
          </p>
EOD;
          
          foreach($data->ocsTags as $ocsTag)
          {
            $reszesedesek .= <<<EOD
            <tr>
              <td style="width: 5%;">
                &nbsp;
              </td>
              <td style="width: 25%;">Tag neve:
              </td>
              <td>$ocsTag->fullName
              </td>
              <td style="width: 35%;">Részesedése:
              </td>
              <td>$ocsTag->reszesedese%
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
            $reszesedesek
          </table>
EOD;
        }
        else
        {
          $html .= <<<EOD
            <p style="line-height: 16px;">A közös tevékenység nyeresége és vesztesége egymás között egyenlő arányban oszlik meg.
            </p>
EOD;
        }

        $html .= <<<EOD
        <p style="line-height: 16px;"><b>IV/5.</b> Az őstermelők családi gazdaságának tagjai rögzítik, hogy ha valamely tag harmadik személlyel kötött szerződés alapján a IV/4. pontban rögzített veszteség viselésének arányától eltérően köteles helytállni, úgy a tagok kötelesek a IV/4. pontban meghatározottak szerint egymással elszámolni.
        </p>
        <p style="line-height: 16px;"><b>IV/6.</b> Az őstermelők családi gazdaságának tagjai rögzítik, hogy az őstermelői tevékenységgel előállított termék értékesítése során az őstermelők családi gazdasága tagjának nevében, annak képviselőjeként az őstermelők családi gazdaságának bármelyik tagja vagy alkalmazottja is eljárhat.
        </p>
        <p>
          &nbsp;
        </p>
        <h2 style="line-height: 16px;">V. AZ ŐSTERMELŐK CSALÁDI GAZDASÁGÁNAK KÉPVISELŐ SZEMÉLYE 
        </h2>
        <p style="line-height: 1px;">
          &nbsp;
        </p>
EOD;
        if($data->kepviseloOcsgTagIndex)
        {
          $kepviselo = $data->ocsTags[$data->kepviseloOcsgTagIndex];
          $number = $data->kepviseloOcsgTagIndex + 1;
          $html .= <<<EOD
          <p style="line-height: 16px;"><b>V/1.</b> Az őstermelők családi gazdaságának tagjai megállapodnak abban, hogy maguk közül őstermelők családi gazdaságának képviselőjévé jelen szerződés I/$number. pontjában szereplő, $kepviselo->fullName  nevű (adóazonosító jel: $kepviselo->adoazonJel) tagot jelölik ki.
          </p>
EOD;
        }

        $html .= <<<EOD
        <p style="line-height: 16px;"><b>V/2.</b> Az őstermelők családi gazdaságának tagjai rögzítik, hogy az V/1. pontban kijelölt őstermelők családi gazdaságának képviselője a többi tag képviseletében az őstermelők családi gazdaságának tevékenysége körében jogokat szerezhet és kötelezettségeket vállalhat.
        </p>
        <p style="line-height: 16px;"><b>V/3.</b> Az őstermelők családi gazdaságának tagjai tudomásul veszik, hogy az V/1. pontban kijelölt, őstermelők családi gazdaságának képviselője jogosult az őstermelők családi gazdaságának az őstermelő nyilvántartásba vétele iránti kérelmet előterjeszteni.
        </p>
        <p style="line-height: 16px;"><b>V/4.</b> Az őstermelők családi gazdaságának tagjai tudomásul veszik, hogy az V/1. pontban kijelölt őstermelők családi gazdaságának képviselője az őstermelők családi gazdaságának képviseletére az őstermelők családi gazdaságának a nyilvántartásba vételével válik jogosulttá.
        </p>
        <p>
          &nbsp;
        </p>
        <h2 style="line-height: 16px;">VI. AZ ŐSTERMELŐK CSALÁDI GAZDASÁGÁNAK KÖZPONTJA
        </h2>
        <p style="line-height: 1px;">
          &nbsp;
        </p>
        <p style="line-height: 16px;"><b>VI/1.</b> Az őstermelők családi gazdaságának tagjai rögzítik, hogy az őstermelők családi gazdaságának <b>központja címe:</b> $data->kpPostalCode $data->kpSettlement, $data->kpKozterNev $data->kpKozterTipus $data->kpHazSzam $data->kpEmeletAjto ingatlan.
        </p>
        <p>
          &nbsp;
        </p>
        <h2 style="line-height: 16px;">VII. RENDELKEZÉS A SZERZŐDÉS MÓDOSÍTÁSÁRÓL, MEGSZÜNTETÉSÉRŐL
        </h2>
        <p style="line-height: 16px;"><b>VII/1.</b> Jelen szerződést a családi gazdaság tagjai jogosultak bármikor közös megegyezéssel írásban módosítani, vagy megszüntetni.
        </p>
EOD;

        if($data->hasznalatIdotartamHatarozott == 1)
        {
          $html .= <<<EOD
          <p style="line-height: 16px;"><b>VII/2.</b> Határozott időre kötött szerződés esetén a tagot a rendes felmondás joga nem illeti meg. 
          </p>
EOD;
        }
        else
        {
          $html .= <<<EOD
          <p style="line-height: 16px;"><b>VII/2.</b> Határozatlan időre kötött szerződés esetén: A jelen szerződést bármely tag 3 hónapos felmondási idővel a többi taghoz intézett írásbeli nyilatkozatával felmondhatja. Tagok rögzítik, hogy azon tag, aki alkalmatlan időben gyakorolja felmondási jogát, köteles az ebből eredő károkat megtéríteni, feltéve, hogy az időpont alkalmatlanságára a többi tag figyelmeztette, és felmondását a figyelmeztetés ellenére fenntartotta. 
          </p>
EOD;
        }
        $html .= <<<EOD
        <p style="line-height: 16px;"><b>VII/3.</b> Az őstermelők családi gazdaságának tagjai tudomásul veszik, hogy jelen szerződés megszűnése után a tagok között elszámolásnak van helye. Az elszámolás során a közös vagyont tagok között jelen szerződés III. pontjában rögzített hozzájárulásuk arányában kell felosztani. A közös használatba adott dolgokat vissza kell szolgáltatni annak a tagnak, aki azt az őstermelők családi gazdasága használatába adta, rendelkezésére bocsájtotta.
        </p>
EOD;

        $nextNum = "VIII";
        $pontokSzama = "8";
        if($data->tovabbvitelModja)
        {
          $nextNum = "IX";
          $pontokSzama = "9";

          $html .= <<<EOD
          <p>
            &nbsp;
          </p>
          <h2 style="line-height: 16px;">VIII. AZ ŐSTERMELŐK CSALÁDI GAZDASÁGÁNAK TAGJAI AZ ŐSTERMELŐK CSALÁDI GAZDASÁGÁNAK NYILVÁNTARTÁSBA VÉTELÉT MEGELŐZŐEN GAZDÁLKODÁSUK FOLYTATÁSA ÉRDEKÉBEN VÁLLALT KÖTELEZETTSÉGEK ÉS MEGSZERZETT JOGOK ŐSTERMELŐK CSALÁDI GAZDASÁGÁNAK KERETEIN BELÜL TÖRTÉNŐ TOVÁBBVITELÉNEK MÓDJA
          </h2>
          <p style="line-height: 16px;">$data->tovabbvitelModja
          </p>
EOD;
        }

        $date = getdate();
        $datum = $date["year"].". év ". $date["mon"].". hó ".$date["mday"].". nap.";

        $html .= <<<EOD
        <p>
          &nbsp;
        </p>
        <h2 style="line-height: 16px;">$nextNum. EGYÉB RENDELKEZÉSEK
        </h2>
        <p style="line-height: 1px;">
          &nbsp;
        </p>
        <p style="line-height: 16px;"><b>$nextNum/1.</b> A jelen szerződésben nem szabályozott kérdésekben a Törvény, valamint a Polgári Törvénykönyvről szóló 2013. évi V. törvény polgári jogi társasági szerződésre vonatkozó rendelkezéseit kell alkalmazni.
        </p>
        <p style="line-height: 16px;"><b>$nextNum/2.</b> Jelen szerződés az aláírás napján lép hatályba.
        </p>
        <p style="line-height: 16px;"><b>$nextNum/3.</b> Jelen szerződés - amely ..... oldalból és $pontokSzama fő pontból áll, továbbá 3 egymással mindenben megegyező példányban készült - elolvasás és kölcsönös értelmezés után, a tagok, mint akaratukkal mindenben megegyezőt írták alá.
        </p>
        <p>
          &nbsp;
        </p>
        <p style="line-height: 16px;">$datum
        </p>
EOD;

        $alairashelyek = "";
        $i = 0;
        $j = 1;
        foreach($data->ocsTags as $ocsTag)
        {
          if($i % 2 == 0)
          {
            $alairashelyek .= "<tr>";
          }

          $alairashelyek .= <<<EOD
          <td style="width: 50%; text-align: center;">
            _______________________________________
            <br>
            $j. számú családtag
          </td>
EOD;

          if($i % 2 == 1 || ($i+1) == count($data->ocsTags))
          {
            $alairashelyek .= "</tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr>";
          }

          $i++;
          $j++;
        }

        $html .= <<<EOD
        <p style="line-height: 30px;">
            &nbsp;
        </p>
        <table cellspacing="0" cellpadding="0" border="0" style="line-height: 20px;">
            $alairashelyek
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

        $pdf->writeHTML($html, true, false, false, false);
        $filename = "ocsg_".date("yyyy_m_d_".str_replace(array(' ', '.'), '', (string)microtime())).".pdf";
        $pdf->Output( getcwd().'/pdfs/'.$filename, 'F');
        echo '{"filename":"'.$filename.'"}';
    }
}

new Ocsg();
?>
