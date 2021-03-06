
<SCRIPT src="funktionen/tooltip.js" language="JavaScript"  type="text/javascript"></SCRIPT>
<script src="funktionen/selectformfunctions.js" language="JavaScript"  type="text/javascript"></script>
<script type="text/javascript">
<!--

Text[1]=["Hilfe:","Auf dieser Seite können Sie festlegen, welche Rechte eine Stelle beim Zugriff auf einen	bestimmten Layer haben soll.<br><br> Auf Layerebene gibt es 3 verschiedene Privilegien, die Sie der Stelle zuordnen können. Die niedrigste ist 'Lesen und bearbeiten'. Mit dieser Stufe kann der Layer nur abgefragt werden. Mit der zweiten Stufe lassen sich neue Datensätze erzeugen und mit der dritten Stufe außerdem vorhandene Datensätze löschen.<br><br> Darüberhinaus können Sie der Stelle attributbezogene Rechte zuweisen. Ist ein Attribut 'nicht sichtbar', so taucht es in der Sachdatenabfrage nicht auf. Ist ein Attribut lesbar, so erscheint es in der Abfrage. Soll ein Attribut editierbar sein, so wählt man hier das Privileg 'editierbar'. Beim Geometrie-Attribut 'the_geom' gilt: Ist dieses Attribut nicht sichtbar, so kann man auch nicht von der Sachdatenanzeige in die Karte auf das Objekt zoomen. Dafür muß es mindestens lesbar sein.<br>Damit ein Attribut in der Layer-Suche als Suchoption zur Verfügung steht, muss es ebenfalls mindestens lesbar sein.<br><br>Auf der linken Seite können Sie die Default-Rechte für den Layer festlegen, die dann bei der Stellenzuweisung des Layers verwendet werden.<br><br>Wenn Sie den Link 'Default-Rechte allen Stellen zuweisen' verwenden, werden die Defaultrechte allen Stellen zugewiesen und gespeichert. Hierbei ist zu beachten, dass je nach nach Anzahl der Stellen und Attribute eine sehr große Anzahl an Formularvariablen übermittelt wird. Möglicherweise muss dafür in der php.ini der Wert für max_input_vars hoch gesetzt werden."]



function set_all(attribute_names, stelle, value){
	names = attribute_names.split('|');
	for(i = 0; i < names.length; i++){
		element = document.getElementsByName('privileg_'+names[i]+stelle);
		element[0].value = value;
	}
}

function get_from_default(attribute_names, stellen){
	really = true;
	stelle = stellen.split('|');
	if(stelle.length > 1){
		really = confirm('Wollen Sie die Default-Rechte wirklich allen Stellen zuweisen?');
	}
	if(really){
		for(j = 0; j < stelle.length; j++){
			element1 = document.getElementsByName('privileg'+stelle[j]);
			element2 = document.getElementsByName('privileg');
			element1[0].value = element2[0].value;
			element1 = document.getElementsByName('export_privileg'+stelle[j]);
			element2 = document.getElementsByName('export_privileg');
			element1[0].value = element2[0].value;
			names = attribute_names.split('|');
			for(i = 0; i < names.length; i++){
				element1 = document.getElementsByName('privileg_'+names[i]+stelle[j]);
				element2 = document.getElementsByName('privileg_'+names[i]);
				element1[0].value = element2[0].value;
				tooltip1 = document.getElementsByName('tooltip_'+names[i]+stelle[j]);
				tooltip2 = document.getElementsByName('tooltip_'+names[i]);
				tooltip1[0].checked = tooltip2[0].checked;
			}
		}
		save(stellen);
	}
}


function save(stelle){
	document.GUI.stelle.value = stelle;
	document.GUI.go_plus.value = 'speichern';
	document.GUI.submit();
}
  
//-->
</script>

<table border="0" cellpadding="5" cellspacing="2" bgcolor="<?php echo $bgcolor; ?>">
  <tr align="center"> 
    <td colspan="4"><h2><?php echo $this->titel; ?></h2></td>
  </tr>
  <tr>
  	<td>&nbsp;</td>
  </tr>
  <tr>
    <td colspan="2" align="center">
    	<table cellpadding="5" cellspacing="2">
    		<tr> 
			    <td style="border-top:1px solid #C3C7C3;border-left:1px solid #C3C7C3;border-right:1px solid #C3C7C3" colspan="2">Layer</td>
			  </tr>
				<tr>
					<td style="border-bottom:1px solid #C3C7C3;border-right:1px solid #C3C7C3;border-left:1px solid #C3C7C3"> 
			      <select style="width:250px" size="1"  name="selected_layer_id" onchange="document.GUI.scrollposition.value=0;document.GUI.submit();">
			      	<option value="">----------- Bitte wählen -----------</option>
			        <?
			    		for($i = 0; $i < count($this->layerdaten['ID']); $i++){
			    			echo '<option';
			    			if($this->layerdaten['ID'][$i] == $this->formvars['selected_layer_id']){
			    				echo ' selected';
			    			}
			    			echo ' value="'.$this->layerdaten['ID'][$i].'">'.$this->layerdaten['Bezeichnung'][$i].'</option>';
			    		}
			    		?>
			      </select> 
			    </td>
			  </tr>
			</table>
  	</td>
  </tr>
  <? if($this->layer[0]['Name'] != ''){ ?>
	<tr>
  	<td>
			<div style="position:relative;">
				<img src="<?php echo GRAPHICSPATH;?>icon_i.png" onMouseOver="stm(Text[1],Style[0], document.getElementById('TipLayer'))" onmouseout="htm()">
				<DIV id="TipLayer" style="visibility:hidden;position:absolute;z-index:1000;"></DIV>
			</div>
  	</td>
  </tr>
  <tr>
  	<td>
  		<table>
				<tr>
					<td></td>
					<td></td>
					<td>
						<? 
						$stellenanzahl = count($this->stellen['ID']);
						if($stellenanzahl > 0){
						$width1 = $width = 280*$stellenanzahl;
						if($width > 840)$width = 840;
						if($width1 > 840){ ?>
						<div id="upperscrollbar" style="overflow:auto; overflow-y:hidden;width:840px" onscroll="document.getElementById('stellendiv').scrollLeft=this.scrollLeft">
							<div style="width:<? echo $width1; ?>px;height:1px"></div>
						</div>
						<? } ?>
					</td>
				</tr>
  			<tr>
			  	<td valign="top">
			  		<div style="border:1px solid black;">
							<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="10">
								<tr>  	
			  					<? include(LAYOUTPATH.'snippets/attribute_privileges_template.php'); ?>
			  				</tr>
							</table>
						</div>
					<td>	
					<td valign="top">
						<div id="stellendiv" style="border:1px solid black; width:<? echo $width; ?>px; float:right; overflow:auto; overflow-y:hidden" onscroll="document.GUI.scrollposition.value = this.scrollLeft; document.getElementById('upperscrollbar').scrollLeft=this.scrollLeft">
							<table border="1" style="border-collapse:collapse" cellspacing="0" cellpadding="10">
								<tr>
							<?
								for($s = 0; $s < count($this->stellen['ID']); $s++){
									$this->stelle = new stelle($this->stellen['ID'][$s], $this->database);
									$this->layer = $this->stelle->getLayer($this->formvars['selected_layer_id']);
									$this->attributes_privileges = $this->stelle->get_attributes_privileges($this->formvars['selected_layer_id'], true);
									include(LAYOUTPATH.'snippets/attribute_privileges_template.php');
								}
							?>
								</tr>
							</table>
						</div>
					</td>
					<? } ?>
				</tr>
			</table>
		</td>
  </tr>
  <tr> 
    <td colspan="4">&nbsp;</td>
  </tr>
	<tr>
  	<td align="center">
  		<input type="button" name="dummy" onclick="location.href='index.php?go=Layereditor&selected_layer_id=<? echo $this->formvars['selected_layer_id']; ?>#stellenzuweisung'" value="zur Stellenzuweisung">
  	</td>
  </tr>
  <tr> 
    <td colspan="4" >&nbsp;</td>
  </tr>
  <? } ?>
</table>

<input type="hidden" name="scrollposition" value="<? echo $this->formvars['scrollposition']; ?>">
<input type="hidden" name="go" value="Layerattribut-Rechteverwaltung">
<input type="hidden" name="go_plus" value="">
<input type="hidden" name="stelle" value="">

<script type="text/javascript">

	if(document.getElementById("stellendiv"))document.getElementById("stellendiv").scrollLeft="<? echo $this->formvars['scrollposition']; ?>"

</script>


