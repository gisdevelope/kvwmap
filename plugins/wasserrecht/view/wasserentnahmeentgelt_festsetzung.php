<?php
$wrz = null;
$gewaesserbenutzung = null;

$speereEingabeFestsetzung = false;
$errorEingabeFestsetzung = null;

$zugelassenesEntnahmeEntgelt = 0;
$nichtZugelassenesEntnahmeEntgelt = 0;
$zugelassenerUmfangEntgeltsatz = 0;
$zugelassenerUmfangEntgelt = 0;

$findDefaultWrz = true;

$isTrue = ["true",1,"t"];
		
// print_r($_REQUEST);

if($_SERVER ["REQUEST_METHOD"] == "POST")
{
    //print_r($_POST);
    
    foreach($_POST as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(startsWith($keyEscaped, "festsetzung_freigeben_"))
        {
            festsetzung_freigeben($this, $valueEscaped, "festsetzung_freigeben_", true, $wrz, $gewaesserbenutzung, $errorEingabeFestsetzung, $speereEingabeFestsetzung);
            break;
        }
        if(startsWith($keyEscaped, "festsetzung_speichern_"))
        {
            festsetzung_freigeben($this, $valueEscaped, "festsetzung_speichern_", false, $wrz, $gewaesserbenutzung, $errorEingabeFestsetzung, $speereEingabeFestsetzung);
            break;
        }
    }
}
elseif($_SERVER ["REQUEST_METHOD"] == "GET")
{
//     print_r($_GET);

    foreach($_GET as $key => $value)
    {
        $keyEscaped = htmlspecialchars($key);
        $valueEscaped = htmlspecialchars($value);
        
        if(strtolower($keyEscaped) === "getfestsetzung")
		{
		    $festsetzungWrzId = $valueEscaped;
// 		    echo "<br />festsetzungWrzId: " . $festsetzungWrzId;
		    $festsetzungWrz = new WasserrechtlicheZulassungen($this);
		    $wrz = $festsetzungWrz->find_by_id($this, 'id', $festsetzungWrzId);
// 		    var_dump($wrz);
// 		    echo "<br />wrz id: " . $wrz->getId();
// 		    echo "<br />wrz isErklaerungFreigegeben: " . var_dump($wrz->isErklaerungFreigegeben());
		    if((!empty($wrz) && !empty($wrz->getId())) && $wrz->isErklaerungFreigegeben())
		    {
// 		        echo "<br />wrz id: " . $wrz->getId();
		        $gb = new Gewaesserbenutzungen($this);
		        $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
		        if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0 && !empty($gewaesserbenutzungen[0]))
		        {
		            $gewaesserbenutzung = $gewaesserbenutzungen[0];
		        }
// 		        echo "<br />gewaesserbenutzung: " . $gewaesserbenutzung[0]->getId();

		        if($wrz->isFestsetzungFreigegeben())
		        {
		            $speereEingabeFestsetzung = true;
		        }
		    }
		    else
		    {
		        $findDefaultWrz = false;
		    }
		    break;
		 }
    }
}

function festsetzung_freigeben(&$gui, $valueEscaped, $keyName, $festsetzungFreigeben, &$wrz, &$gewaesserbenutzung, &$errorEingabeFestsetzung, &$speereEingabeFestsetzung)
{
    $festsetzungFreigebenWrz = new WasserrechtlicheZulassungen($gui);
    $wrz = $festsetzungFreigebenWrz->find_by_id($gui, 'id', $valueEscaped);
    if (!empty($wrz))
    {
        $gb = new Gewaesserbenutzungen($gui);
        $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
        if(!empty($gewaesserbenutzungen) && count($gewaesserbenutzungen) > 0 && !empty($gewaesserbenutzungen[0])) 
        {
            $gewaesserbenutzung = $gewaesserbenutzungen[0];
            // $gewaesserbenutzungfestsetzungFreigegebenId = $gewaesserbenutzung->getId();
            
            if(!empty($gewaesserbenutzung))
            {
                if($errorEingabeFestsetzung === null)
                {
                    for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i ++)
                    {
                        $teilgewaesserbenutzung_art_benutzung = htmlspecialchars($_POST["teilgewaesserbenutzung_art_benutzung_" . $i]);
//                         var_dump($teilgewaesserbenutzung_art_benutzung);
                        $teilgewaesserbenutzung_wiedereinleitung_bearbeiter = htmlspecialchars($_POST["teilgewaesserbenutzung_wiedereinleitung_bearbeiter_" . $i]);
//                         var_dump($teilgewaesserbenutzung_wiedereinleitung_bearbeiter);
                        $teilgewaesserbenutzung_befreiungstatbestaende = htmlspecialchars($_POST["teilgewaesserbenutzung_befreiungstatbestaende_" . $i]);
//                         var_dump($teilgewaesserbenutzung_befreiungstatbestaende);
                        $freitext = htmlspecialchars($_POST["festsetzung_freitext"]);
                        
                        // check for not filled out lines
                        if (empty($teilgewaesserbenutzung_art_benutzung)
                            && empty($teilgewaesserbenutzung_wiedereinleitung_bearbeiter)
                            && empty($teilgewaesserbenutzung_befreiungstatbestaende))
                        {
                            break;
                        }
                        
                        if (!empty($teilgewaesserbenutzung_art_benutzung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $teilgewaesserbenutzung_art_benutzung) !== 0
                            && !empty($teilgewaesserbenutzung_wiedereinleitung_bearbeiter) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $teilgewaesserbenutzung_wiedereinleitung_bearbeiter) !== 0
                            && !empty($teilgewaesserbenutzung_befreiungstatbestaende) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $teilgewaesserbenutzung_befreiungstatbestaende) !== 0)
                        {
                            
                            $errorEingabeFestsetzung = null;
                            if($festsetzungFreigeben)
                            {
                                $speereEingabeFestsetzung = true;
                            }
                            //                         echo var_dump($speereEingabeFestsetzung);
                            
                            // update an existing teilgewaesserbenutzung
                            if (!empty($gewaesserbenutzung->teilgewaesserbenutzungen[$i - 1])) {
                                $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[$i - 1];
                                $berechneter_entgeltsatz = htmlspecialchars($_POST["teilgewaesserbenutzung_berechneter_entgeltsatz_" . $i]);
                                $berechnetes_entgelt = htmlspecialchars($_POST["teilgewaesserbenutzung_berechnetes_entgelt_" . $i]);
                                $teilgewaesserbenutzungId = $teilgewaesserbenutzung->updateTeilgewaesserbenutzung_Bearbeiter($teilgewaesserbenutzung_art_benutzung, $teilgewaesserbenutzung_wiedereinleitung_bearbeiter, $teilgewaesserbenutzung_befreiungstatbestaende, $freitext, $berechneter_entgeltsatz, $berechnetes_entgelt);
                                
                                $gui->add_message('notice', 'Teilgewässerbenutzungen (id: ' . $teilgewaesserbenutzungId . ') erfolgreich geändert!');
                            }                        // else --> if not there --> create one
                            else {
                                $errorEingabeFestsetzung = $i;
                                break;
                            }
                        }
                        else
                        {
                            $errorEingabeFestsetzung = $i;
                            break;
                        }
                    }
                }
                
                if($errorEingabeFestsetzung === null)
                {
                    if($festsetzungFreigeben)
                    {
                        $wrz->insertFestsetzungDatum();
                        $festsetzungsNutzer = $gui->user->Vorname . ' ' . $gui->user->Name;
                        $wrz->insertFestsetzungNutzer($festsetzungsNutzer);
                        
                        $summe_zugelassene_entnahmemengen = htmlspecialchars($_POST["summe_zugelassene_entnahmemengen"]);
                        $wrz->insertFestsetzungSummeZugelasseneEntnahmemengen($summe_zugelassene_entnahmemengen);
                        $summe_entgelt = htmlspecialchars($_POST["summe_entgelt"]);
                        $wrz->insertFestsetzungSummeEntgelt($summe_entgelt);
                    }
                    
                    // update gewaesserbenutzungen, because teilgewaesserbenutzungen where added
                    $gewaesserbenutzungen = $gb->find_where_with_subtables('wasserrechtliche_zulassungen=' . $wrz->getId());
                    $gewaesserbenutzung = $gewaesserbenutzungen[0];
                } else {
                    if ($errorEingabeFestsetzung > 0) {
                        $gui->add_message('error', 'Eingabe in Zeile ' . $errorEingabeFestsetzung . ' ist fehlerhaft oder nicht vollständig! Bitte überprüfen Sie Ihre Angaben!');
                    }
                }
            }
        }
    }
}

//try to find the first WRZ if, no wrz was given
if((empty($wrz) || empty($wrz->getId())) && $findDefaultWrz)
{
    $defaultWrz = new WasserrechtlicheZulassungen($this);
    $results = $defaultWrz->find_where('1=1', 'id');
    
    if(!empty($results) && count($results) > 0)
    {
        $wrz = $results[0];
    }
    
    if($wrz->isFestsetzungFreigegeben())
    {
        $speereEingabeFestsetzung = true;
    }
}

if(!empty($wrz) && !empty($wrz->getId()))
{
    if($wrz->isErklaerungFreigegeben())
    {
        $wrz->getDependentObjects($this, $wrz);
        if(empty($gewaesserbenutzung) && !empty($wrz->gewaesserbenutzungen) && count($wrz->gewaesserbenutzungen) > 0 && !empty($wrz->gewaesserbenutzungen[0]))
        {
            $gewaesserbenutzung = $wrz->gewaesserbenutzungen[0];
        }
        
        $tab1_id="wasserentnahmeentgelt_erklaerung_der_entnahme";
        $tab1_name="Erklärung der Entnahme";
        $tab1_active=false;
        $tab1_visible=true;
        $tab2_id="wasserentnahmeentgelt_festsetzung";
        $tab1_extra_parameter_key="geterklaerung";
        $tab1_extra_parameter_value=$wrz->getId();
        $tab2_name="Festsetzung";
        $tab2_active=true;
        $tab2_visible=true;
        include_once ('includes/header.php');
        
        ?>

	<div id="wasserentnahmeentgelt_festsetzung" class="tabcontent" style="display: block">

    	<form action="index.php" id="festsetzung_freigeben_form" accept-charset="" method="POST">
        		
    		<?php 
    		     include_once ('includes/wasserentnahmeentgelt_header.php'); 
    		?>
    		
    		  <table class="wasserrecht_table" style="margin-top: 20px; width: 1000px">
                  <tr>
                  	<th></th>
                    <th>Erklärter Teil-Benutzungsart</th>
                    <th>Erklärter Teil-Benutzungszweck</th>
                    <th>Erklärter Teil-Benutzungsumfang in m³/a</th>
                    <th>Wiedereinleitung</th>
                    <th>Mengenbestimmung</th>
                    <th>Art der Benutzung</th>
                    <th>Wiedereinleitung</th>
                    <th>Befreiungstatbestände nach § 16 LWaG</th>
                    <th>Entgeltsatz</th>
                    <th>Entgelt</th>
                  </tr>
           		  <?php
           		  
           		  $zugelassenerUmfangEntgeltsatz = $gewaesserbenutzung->getZugelassenerUmfang();
           		  $zugelassenerUmfangEntgelt = $zugelassenerUmfangEntgeltsatz;
           		  
                  for ($i = 1; $i <= WASSERRECHT_ERKLAERUNG_ENTNAHME_TEILGEWAESSERBENUTZUNGEN_COUNT; $i++) 
                  {
                      
                          $teilgewaesserbenutzung = null;
                          if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0 
                              && count($gewaesserbenutzung->teilgewaesserbenutzungen) > ($i - 1) && !empty($gewaesserbenutzung->teilgewaesserbenutzungen[$i -1]))
                          {
                              $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[$i - 1];
//                               var_dump($teilgewaesserbenutzung->gewaesserbenutzungArt->getName());
//                               echo "<br>teilgewaesserbenutzung: " . var_dump($teilgewaesserbenutzung->gewaesserbenutzungArt->getId());

                              if(!empty($teilgewaesserbenutzung))
                              {
                                  //Art Benutzung
                                  $getArtBenutzung = null;
                                  if(!empty(htmlspecialchars($_REQUEST['teilgewaesserbenutzung_art_benutzung_' . $i])))
                                  {
                                      if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['teilgewaesserbenutzung_art_benutzung_' . $i])) !== 0)
                                      {
                                          $getArtBenutzung = htmlspecialchars($_REQUEST['teilgewaesserbenutzung_art_benutzung_' . $i]);
                                      }
                                      else
                                      {
                                          $getArtBenutzung = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                      }
                                  }
                                  else
                                  {
                                      if(!empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->art_benutzung))
                                      {
                                          $getArtBenutzung = $teilgewaesserbenutzung->art_benutzung->getId();
                                      }
                                  }
                                  
                                  //Wiedereinleitung Bearbeiter
                                  $getWiedereinleitungBearbeiter = null;
                                  if(!empty(htmlspecialchars($_REQUEST['teilgewaesserbenutzung_wiedereinleitung_bearbeiter_' . $i])))
                                  {
                                      if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['teilgewaesserbenutzung_wiedereinleitung_bearbeiter_' . $i])) !== 0)
                                      {
                                          $getWiedereinleitungBearbeiter = htmlspecialchars($_REQUEST['teilgewaesserbenutzung_wiedereinleitung_bearbeiter_' . $i]);
                                          $getWiedereinleitungBearbeiter = in_array(strtolower($getWiedereinleitungBearbeiter), $isTrue);
                                      }
                                      else
                                      {
                                          $getWiedereinleitungBearbeiter = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                      }
                                  }
                                  else
                                  {
                                      if(!empty($teilgewaesserbenutzung))
                                      {
                                          $getWiedereinleitungBearbeiter = $teilgewaesserbenutzung->getWiedereinleitungBearbeiter();
                                          if(!is_null($getWiedereinleitungBearbeiter))
                                          {
                                              $getWiedereinleitungBearbeiter = in_array(strtolower($getWiedereinleitungBearbeiter), $isTrue);
                                          }
                                          else
                                          {
                                              $getWiedereinleitungBearbeiter = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                          }
                                      }
                                  }
//                                   echo "getWiedereinleitungBearbeiter: " . var_dump($getWiedereinleitungBearbeiter);
                                  
                                  //Befreiungstatbestände
                                  $getBefreiungstatbestaende = null;
                                  if(!empty(htmlspecialchars($_REQUEST['teilgewaesserbenutzung_befreiungstatbestaende_' . $i])))
                                  {
                                      if(strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, htmlspecialchars($_REQUEST['teilgewaesserbenutzung_befreiungstatbestaende_' . $i])) !== 0)
                                      {
                                          $getBefreiungstatbestaende = htmlspecialchars($_REQUEST['teilgewaesserbenutzung_befreiungstatbestaende_' . $i]);
                                          $getBefreiungstatbestaende = in_array(strtolower($getBefreiungstatbestaende), $isTrue);
                                      }
                                      else
                                      {
                                          $getBefreiungstatbestaende = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                      }
                                  }
                                  else
                                  {
                                      if(!empty($teilgewaesserbenutzung))
                                      {
                                          $getBefreiungstatbestaende = $teilgewaesserbenutzung->getBefreiungstatbestaende();
                                          if(!is_null($getBefreiungstatbestaende))
                                          {
                                              $getBefreiungstatbestaende = in_array(strtolower($getBefreiungstatbestaende), $isTrue);
                                          }
                                          else
                                          {
                                              $getBefreiungstatbestaende = WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE;
                                          }
                                      }
                                  }
//                                   echo "getBefreiungstatbestaende: " . var_dump($getBefreiungstatbestaende);
                                  
                                  if($errorEingabeFestsetzung === $i)
                                  {
                                      ?>
                                  	     <tr style="border: 3px solid red">
                                  	<?php
                                 	 }
                                 	 else
                                 	 {
                                 	     ?>
                                 	     <tr>
                                 	<?php
                                 	 }    
                                  	?>
                                      <td><?php echo $i; ?>.</td>
                                      <td><?php echo !empty($teilgewaesserbenutzung->gewaesserbenutzungArt) ? $teilgewaesserbenutzung->gewaesserbenutzungArt->getName() : "" ?></td>
                                      <td><?php echo !empty($teilgewaesserbenutzung->gewaesserbenutzungZweck) ? $teilgewaesserbenutzung->gewaesserbenutzungZweck->getName() : "" ?></td>
                                      <td><?php echo !empty($teilgewaesserbenutzung->getUmfang()) ? $teilgewaesserbenutzung->getUmfang() : "" ?></td>
                                      <td><?php echo !empty($teilgewaesserbenutzung->getWiedereinleitungNutzer()) && $teilgewaesserbenutzung->getWiedereinleitungNutzer() === "t" ? "ja" : "nein" ?></td>
                                      <td><?php echo !empty($teilgewaesserbenutzung->mengenbestimmung) ? $teilgewaesserbenutzung->mengenbestimmung->getName() : "" ?></td>
                                      <td>
                                      	<select name="teilgewaesserbenutzung_art_benutzung_<?php echo $i; ?>" onchange="setNewTab('wasserentnahmeentgelt_festsetzung',{'getfestsetzung':'<?php echo $wrz->getId() ?>','teilgewaesserbenutzung_art_benutzung_<?php echo $i; ?>':this.value})" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>
                                    		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                    		<option value="1" <?php echo!is_null($getArtBenutzung) && $getArtBenutzung === "1" ?  'selected' : ''?>>GW</option>
                                    		<option value="2" <?php echo !is_null($getArtBenutzung) && $getArtBenutzung === "2" ?  'selected' : ''?>>OW</option>
                                    	</select>
                                      </td>
                                      <td>
                                      	<select name="teilgewaesserbenutzung_wiedereinleitung_bearbeiter_<?php echo $i; ?>" onchange="setNewUrlParameter(this,'teilgewaesserbenutzung_wiedereinleitung_bearbeiter_<?php echo $i; ?>')" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>
                                    		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                    		<option value="true" <?php echo strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getWiedereinleitungBearbeiter) !== 0 && $getWiedereinleitungBearbeiter ?  'selected' : ''?>>ja</option>
                                    		<option value="false" <?php echo strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getWiedereinleitungBearbeiter) !== 0 && !$getWiedereinleitungBearbeiter ?  'selected' : ''?>>nein</option>
                                    	</select>
                                      </td>
                                      <td>
                                      	<select name="teilgewaesserbenutzung_befreiungstatbestaende_<?php echo $i; ?>" onchange="setNewUrlParameter(this,'teilgewaesserbenutzung_befreiungstatbestaende_<?php echo $i; ?>')" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>
                                    		<option value='<?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE ?>'><?php echo WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_TEXT ?></option>
                                    		<option value="true" <?php echo strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getBefreiungstatbestaende) !== 0 && $getBefreiungstatbestaende ?  'selected' : ''?>>ja</option>
                                    		<option value="false" <?php echo strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getBefreiungstatbestaende) !== 0 && !$getBefreiungstatbestaende ?  'selected' : ''?>>nein</option>
                                    	</select>
                                      </td>
                                      <td>
                                      	<?php
//                                       	var_dump($getArtBenutzung);
//                                       	var_dump($getBefreiungstatbestaende);
//                                       	var_dump($getWiedereinleitungBearbeiter);
                                      	if (!empty($getArtBenutzung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getArtBenutzung) !== 0
                                      	    && !is_null($getWiedereinleitungBearbeiter) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getWiedereinleitungBearbeiter) !== 0
                                      	    && !is_null($getBefreiungstatbestaende) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getBefreiungstatbestaende) !== 0)
                                          	{
                                          	    $berechneter_entgeltsatz = $gewaesserbenutzung->getTeilgewaesserbenutzungEntgeltsatz($teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, $zugelassenesEntnahmeEntgelt, $nichtZugelassenesEntnahmeEntgelt, $zugelassenerUmfangEntgeltsatz);
                                          	    echo $berechneter_entgeltsatz;
                                          	    echo "<input type='hidden' name='teilgewaesserbenutzung_berechneter_entgeltsatz_" . $i . "' value='" . $berechneter_entgeltsatz . "' />";
                                          	}
                                      	?>
                                      </td>
                                      <td>
                                      	<?php
                                          	if (!empty($getArtBenutzung) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getArtBenutzung) !== 0
                                          	    && !is_null($getWiedereinleitungBearbeiter) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getWiedereinleitungBearbeiter) !== 0
                                          	    && !is_null($getBefreiungstatbestaende) && strcmp(WASSERRECHT_ERKLAERUNG_ENTNAHME_BITTE_AUSWAEHLEN_VALUE, $getBefreiungstatbestaende) !== 0)
                                          	{
                                          	    $berechnetes_entgelt =  $gewaesserbenutzung->getTeilgewaesserbenutzungEntgelt($teilgewaesserbenutzung, $getArtBenutzung, $getBefreiungstatbestaende, $getWiedereinleitungBearbeiter, $zugelassenesEntnahmeEntgelt, $nichtZugelassenesEntnahmeEntgelt, $zugelassenerUmfangEntgelt);
                                          	    echo $berechnetes_entgelt;
                                          	    echo "<input type='hidden' name='teilgewaesserbenutzung_berechnetes_entgelt_" . $i ."' value='" . $berechnetes_entgelt . "' />";
                                          	}
                                      	    
                                      	?>
                                      </td>
                                  </tr>
                           <?php
                              }
                          }
                      }
                  ?>
                  <tr>
                  	<td></td>
                  	<td></td>
                  	<td>Zugelassene Entnahmemenge:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="zugelassene_entnahmemenge" name="zugelassene_entnahmemenge" readonly="readonly" value="<?php echo $gewaesserbenutzung->getEntnahmemenge(true) ?>"></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td>Zugelassene Entnahme Entgelt:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="zugelassene_entnahme_entgelt" name="zugelassene_entnahme_entgelt" readonly="readonly" value="<?php echo $zugelassenesEntnahmeEntgelt ?>"></td>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td></td>
                  	<td>Nicht zugelassene Entnahme:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="nicht_zugelassene_entnahmemenge" name="nicht_zugelassene_entnahmemenge" readonly="readonly" value="<?php echo $gewaesserbenutzung->getEntnahmemenge(false) ?>"></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td>Nicht zugelassene Entnahmeentgelt:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="nicht_zugelassene_entnahme_entgelt" name="nicht_zugelassene_entnahme_entgelt" readonly="readonly" value="<?php echo $nichtZugelassenesEntnahmeEntgelt ?>"></td>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td></td>
                  	<td>Summe Entnahmemengen:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_entnahmemengen" name="summe_zugelassene_entnahmemengen" readonly="readonly" value="<?php echo $gewaesserbenutzung->getUmfangAllerTeilbenutzungen() ?>"></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td>Summe Entgelt:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_entgelt" name="summe_entgelt" readonly="readonly" value="<?php echo $zugelassenesEntnahmeEntgelt + $nichtZugelassenesEntnahmeEntgelt ?>"></td>
                  </tr>
                  <tr>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td></td>
                  	<td>Summe gebucht:</td>
                  	<td><input class="wasserrecht_table_inputfield" type="text" id="summe_gebucht" name="summe_gebucht" readonly="readonly" value=""></td>
                  </tr>
              </table>
           
           <div class="wasserrecht_display_table" style="margin-top: 20px; margin-left: 15px">
               <label for="festsetzung_freitext">Festsetzung Freitext:</label>
               <?php 
                   $teilgewaesserbenutzung = null;
                   if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0 && !empty($gewaesserbenutzung->teilgewaesserbenutzungen[0]))
                   {
                       $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[0];
                       //var_dump($teilgewaesserbenutzung);
                   }
               ?>
               <textarea rows="10" cols="180" id="festsetzung_freitext" name="festsetzung_freitext" style="display: block;" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>><?php echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->getFreitext()) ? $teilgewaesserbenutzung->getFreitext() : ""; ?></textarea>
           </div>
           
           <div class="wasserrecht_display_table" style="margin-top: 20px; margin-left: 15px">
            
                <div class="wasserrecht_display_table_row">
                    <div class="wasserrecht_display_table_cell_caption">Erklärung oder Schätzung:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    	<?php 
//                         	$teilgewaesserbenutzung = null;
//                         	if(!empty($gewaesserbenutzung->teilgewaesserbenutzungen) && count($gewaesserbenutzung->teilgewaesserbenutzungen) > 0 && !empty($gewaesserbenutzung->teilgewaesserbenutzungen[0]))
//                         	{
//                         	    $teilgewaesserbenutzung = $gewaesserbenutzung->teilgewaesserbenutzungen[0];
//                         	    //var_dump($teilgewaesserbenutzung);
//                         	}
                        	echo !empty($teilgewaesserbenutzung) && !empty($teilgewaesserbenutzung->teilgewaesserbenutzungen_art) ? $teilgewaesserbenutzung->teilgewaesserbenutzungen_art->getName() : "";
                    	?>
                     </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
           		<div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Datum Erklärung:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    	<?php
                            echo $wrz->getErklaerungDatumHTML();
                	    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Bearbeiter Erklärung:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    <?php 
                            echo $wrz->getErklaerungNutzer();
                    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
	   			<div class="wasserrecht_display_table_row">
	   				<div class="wasserrecht_display_table_cell_caption">
            			<input type="hidden" name="go" value="wasserentnahmeentgelt_festsetzung">
						<button class="wasserrecht_button" name="festsetzung_speichern_<?php echo $wrz->getId(); ?>" value="<?php echo $wrz->getId(); ?>" type="submit" id="festsetzung_speichern_button_<?php echo $wrz->getId(); ?>" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>Festsetzung speichern</button>
           			</div>
           			<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
           		</div>
	   			
	   			<div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
	   			<div class="wasserrecht_display_table_row">
	   				<div class="wasserrecht_display_table_cell_caption">
						<button class="wasserrecht_button" name="festsetzung_freigeben_<?php echo $wrz->getId(); ?>" value="<?php echo $wrz->getId(); ?>" type="submit" id="festsetzung_freigeben_button_<?php echo $wrz->getId(); ?>" <?php echo $speereEingabeFestsetzung ? "disabled='disabled'" : "" ?>>Festsetzung freigeben</button>
           			</div>
           			<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
           		</div>
           		
           		<div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
	   			<div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Datum Freigabe:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    <?php
                            echo $wrz->getFestsetzungDatumHTML();
                	 ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Bearbeiter Freigabe:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                  	<?php 
                            echo $wrz->getFestsetzungNutzerHTML();
                    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
		   		</div>
                
                <div class="wasserrecht_display_table_row">
           			<div class="wasserrecht_display_table_cell_caption">Abgelegte Festsetzungen</div>
				</div>
				<?php 
				    if(!empty($wrz->isFestsetzungDokumentErstellt()))
    				{?>
        				<div class="wasserrecht_display_table_row">
                            <div class="wasserrecht_display_table_cell_caption">
            					<?php
            					   echo '<a href="' . $this->actual_link . WASSERRECHT_DOCUMENT_URL_PATH . $wrz->festsetzung_dokument->getPfad() . '" target="_blank">' . $wrz->festsetzung_dokument->getName() . '</a>';
            					?>
                   			</div>
        				</div>
    			<?php
    				}
				
				?>
				
				<div class="wasserrecht_display_table_row">
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
    		   		<div class="wasserrecht_display_table_cell_spacer"></div>
    		   		<div class="wasserrecht_display_table_row_spacer"></div>
	   			</div>
	   			
	   			<div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Sammelbescheid erstellt:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    	<?php
                	       if($wrz->isFestsetzungDokumentErstellt())
                	       {
                	           echo $wrz->getFestsetzungDokumentDatum();
                	       }
                	    ?>
                    </div>
                </div>
                
                <div class="wasserrecht_display_table_row">
            		<div class="wasserrecht_display_table_cell_caption">Verwaltungsaufwand beantragt:</div>
                    <div class="wasserrecht_display_table_cell_spacer"></div>
                    <div class="wasserrecht_display_table_cell_white">
                    <?php
//                         echo $this->user->Vorname . ' ' . $this->user->Name
                    ?>
                    </div>
                </div>
                
            </div>
              
 	</form>
</div>
 <?php
    }
    else
    {
        echo '<h1 style=\"color: red;\">Erklärung wurde noch niccht freigegeben!<h1>';
    }
    
}
else
{
    echo '<h1 style=\"color: red;\">Keine Wasserrechtliche Zulassung gefunden!<h1>';
}

?>