INSERT INTO `u_groups` (`Gruppenname`, `Gruppenname_low-german`, `Gruppenname_english`, `Gruppenname_polish`, `Gruppenname_vietnamese`, `obergruppe`, `order`) VALUES
('Fortführungslisten', NULL, NULL, NULL, NULL, NULL, 5);

SET @group_id = LAST_INSERT_ID();
SET @connection = 'user=xxxx password=xxxx dbname=kvwmapsp';

INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Fortführungsaufträge','','5',@group_id,'SELECT a.oid, a.id AS ff_auftrag_id, a.jahr, a.gemkgnr, a.lfdnr,a.gemkgnr || lpad(a.lfdnr::text, 2, \'0\') || right(a.jahr::text, 2) AS fnnr, a.antragsnr, a.bemerkung, a.created_at, a.updated_at, a.user_name, a.auftragsdatei, \'\' AS auftragsdatei_einlesen, a.datumderausgabe, a.profilkennung, a.auftragsnummer, a.impliziteloeschungderreservierung, a.verarbeitungsart, a.geometriebehandlung, a.mitTemporaeremArbeitsbereich, a.mitObjektenImFortfuehrungsgebiet, a.mitFortfuehrungsnachweis, coalesce(aa.name, \'\') as gebaeude, \'\' AS ff_faelle FROM ff_auftraege a left join aa_anlassart aa on a.gebaeude = aa.code where 1=1','ff_auftraege','','fortfuehrungslisten','/var/www/data/nachweise/fortfuehrungsauftraege/','','','','',NULL,NULL,'',@connection,'','6','id','','10','pixels','25833','','1','100',NULL,'-1','-1','','EPSG:25833','','1.1.0','image/png','60','','','','','0','0','','','','','2');
SET @last_layer_id940083=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'antragsnr','antragsnr','ff_auftraege','a','int4','','','1','32','0','','Text','','Antragsnr.','','','','','','Auftrag',NULL,NULL,'6','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'auftragsdatei','auftragsdatei','ff_auftraege','a','varchar','','','1',NULL,NULL,'','Dokument','','Auftragsdatei','','','','','NAS-Datei mit Fortführungsauftrag im XML-Format','Auftragsdatei',NULL,NULL,'11','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'auftragsdatei_einlesen','','','','not_saveable','','',NULL,NULL,NULL,'','dynamicLink','index.php?go=lade_fortfuehrungsfaelle&ff_auftrag_id=$ff_auftrag_id;Fortführungsfälle laden;no_new_window','','','','','','','Auftragsdatei',NULL,NULL,'12','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'auftragsnummer','auftragsnummer','ff_auftraege','a','int4','','','1','32','0','','Zahl','','Auftragsnummer','','','','','','Auftragsdatei',NULL,NULL,'15','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'bemerkung','bemerkung','ff_auftraege','a','text','','','1',NULL,NULL,'','Textfeld','','Bemerkung','','','','','','Auftrag',NULL,NULL,'7','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'created_at','created_at','ff_auftraege','a','timestamp','','','0',NULL,NULL,'SELECT now()','Text','','erstellt am:','','','','','','Auftrag',NULL,NULL,'8','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'datumderausgabe','datumderausgabe','ff_auftraege','a','timestamp','','','1',NULL,NULL,'','Text','','Datum der Ausgabe','','','','','','Auftragsdatei',NULL,NULL,'13','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'ff_auftrag_id','id','ff_auftraege','a','int4','','PRIMARY KEY','1','32','0','','Text','','Auftrag Id','','','','','','Auftrag',NULL,NULL,'1','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'ff_faelle','','','','not_saveable','','',NULL,NULL,NULL,'','SubFormEmbeddedPK','940084,ff_auftrag_id,fall_beschriftung;embedded','Fortführungsfälle','','','','','','Fortführungsfälle',NULL,NULL,'23','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'fnnr','gemkgnr ','','','not_saveable','','',NULL,NULL,NULL,'','Text','','','','','','','','Auftrag',NULL,NULL,'5','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'gebaeude','gebaeude','ff_auftraege','a','varchar','','','1',NULL,NULL,'','Text','','Änderungen am Gebäudebestand','','','','','','Auftragsdatei',NULL,NULL,'22','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'gemkgnr','gemkgnr','ff_auftraege','a','int4','','','0','32','0','','Auswahlfeld','SELECT gemarkungsnummer AS value, bezeichnung || \' (\' || gemarkungsnummer || \')\' AS output FROM alkis.ax_gemarkung ORDER BY bezeichnung','Gemarkung','','','','','','Auftrag',NULL,NULL,'3','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'geometriebehandlung','geometriebehandlung','ff_auftraege','a','bool','','','1',NULL,NULL,'','Auswahlfeld','select false as value, \'nein\' as output UNION select true as value, \'ja\' as output','Geometriebehandlung','','','','','','Auftragsdatei',NULL,NULL,'18','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'impliziteloeschungderreservierung','impliziteloeschungderreservierung','ff_auftraege','a','int4','','','1','32','0','','Zahl','','implizite Löschung der Reservierung','','','','','','Auftragsdatei',NULL,NULL,'16','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'jahr','jahr','ff_auftraege','a','int4','','','0','32','0','SELECT date_part(\'year\'::text, now())','Auswahlfeld','SELECT extract(year from current_timestamp) - generate_series(0, 10) AS value, extract(year from current_timestamp) - generate_series(0, 10)
AS output','Fortführungsjahr','','','','','','Auftrag',NULL,NULL,'2','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'lfdnr','lfdnr','ff_auftraege','a','int4','','','0','32','0','','Text','SELECT lfdnr + 1 FROM (
SELECT 0 AS lfdnr
UNION
SELECT lfdnr FROM fortfuehrungslisten.ff_auftraege
WHERE jahr = $jahr AND gemkgnr = $gemkgnr ORDER BY lfdnr DESC LIMIT 1
) foo','LfdNr.','','','','','Laufende Nummer pro Fortführungsjahr und Gemarkung','Auftrag',NULL,NULL,'4','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'mitfortfuehrungsnachweis','mitfortfuehrungsnachweis','ff_auftraege','a','bool','','','1',NULL,NULL,'','Auswahlfeld','select false as value, \'nein\' as output UNION select true as value, \'ja\' as output','mit Fortführungsnachweis','','','','','','Auftragsdatei',NULL,NULL,'21','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'mitobjektenimfortfuehrungsgebiet','mitobjektenimfortfuehrungsgebiet','ff_auftraege','a','bool','','','1',NULL,NULL,'','Auswahlfeld','select false as value, \'nein\' as output UNION select true as value, \'ja\' as output','mit Objekt im Fortführungsgebiet','','','','','','Auftragsdatei',NULL,NULL,'20','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'mittemporaeremarbeitsbereich','mittemporaeremarbeitsbereich','ff_auftraege','a','bool','','','1',NULL,NULL,'','Auswahlfeld','select false as value, \'nein\' as output UNION select true as value, \'ja\' as output','mit temporärem Arbeitsbereich','','','','','','Auftragsdatei',NULL,NULL,'19','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'oid','oid','ff_auftraege','a','','','',NULL,NULL,NULL,'','Text','','OID','','','','','','Auftrag',NULL,NULL,'0','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'profilkennung','profilkennung','ff_auftraege','a','varchar','','','1',NULL,NULL,'','Text','','Profilkennung','','','','','','Auftragsdatei',NULL,NULL,'14','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'updated_at','updated_at','ff_auftraege','a','timestamp','','','0',NULL,NULL,'SELECT now()','Time','','geändert am:','','','','','','Auftrag',NULL,NULL,'9','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'user_name','user_name','ff_auftraege','a','varchar','','','0',NULL,NULL,'','User','','bearbeitet von:','','','','','','Auftrag',NULL,NULL,'10','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940083,'verarbeitungsart','verarbeitungsart','ff_auftraege','a','int4','','','1','32','0','','Zahl','','Verarbeitungsart','','','','','','Auftragsdatei',NULL,NULL,'17','0','0');

INSERT INTO layer (`Name`,`alias`,`Datentyp`,`Gruppe`,`pfad`,`maintable`,`Data`,`schema`,`document_path`,`tileindex`,`tileitem`,`labelangleitem`,`labelitem`,`labelmaxscale`,`labelminscale`,`labelrequires`,`connection`,`printconnection`,`connectiontype`,`classitem`,`filteritem`,`tolerance`,`toleranceunits`,`epsg_code`,`template`,`queryable`,`transparency`,`drawingorder`,`minscale`,`maxscale`,`offsite`,`ows_srs`,`wms_name`,`wms_server_version`,`wms_format`,`wms_connectiontimeout`,`wms_auth_username`,`wms_auth_password`,`wfs_geom`,`selectiontype`,`querymap`,`logconsume`,`processing`,`kurzbeschreibung`,`datenherr`,`metalink`,`privileg`) VALUES('Fortführungsfälle','','5',@group_id,'SELECT id AS ff_fall_id, ff_auftrag_id, fortfuehrungsfallnummer, laufendenummer,  \'Fall: \' || fortfuehrungsfallnummer::text || \' altes Flst: \' ||  zeigtaufaltesflurstueck[1] AS fall_beschriftung, ueberschriftimfortfuehrungsnachweis, zeigtaufaltesflurstueck, zeigtaufneuesflurstueck, anlassart, anlassarten  FROM ff_faelle WHERE 1=1','ff_faelle','','fortfuehrungslisten','','','','','',NULL,NULL,'',@connection,'','6','id','','10','pixels','25833','','1','100',NULL,'-1','-1','','EPSG:25833','','1.1.0','image/png','60','','','','','0','0','','','','','2');
SET @last_layer_id940084=LAST_INSERT_ID();
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'anlassart','anlassart','ff_faelle','ff_faelle','varchar','','','1',NULL,NULL,'','Auswahlfeld','SELECT code AS value, \'(\' || code || \') \' || name as output from fortfuehrungslisten.aa_anlassart order by code','Anlassart des Fortführungsfalls','','','','','','',NULL,NULL,'8','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'anlassarten','anlassarten','ff_faelle','ff_faelle','_varchar','','','1',NULL,NULL,'','Text','','Anlassarten der Flurücke','','','','','','',NULL,NULL,'9','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'fall_beschriftung',' fortfuehrungsfallnummer::text ','','','not_saveable','','',NULL,NULL,NULL,'','Text','','Beschriftung','','','','','','',NULL,NULL,'4','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'ff_auftrag_id','ff_auftrag_id','ff_faelle','ff_faelle','int4','','','0','32','0','','SubFormFK','940083,ff_auftrag_id,embedded','Auftrag Id','','','','','','',NULL,NULL,'1','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'ff_fall_id','id','ff_faelle','ff_faelle','int4','','PRIMARY KEY','1','32','0','','Text','','Fall Id','','','','','','',NULL,NULL,'0','0','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'fortfuehrungsfallnummer','fortfuehrungsfallnummer','ff_faelle','ff_faelle','int4','','','0','32','0','','Text','','Fortführungsfallnummer','','','','','','',NULL,NULL,'2','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'laufendenummer','laufendenummer','ff_faelle','ff_faelle','bpchar','','','0',NULL,NULL,'','Text','','lfd Nr.','','','','','','',NULL,NULL,'3','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'ueberschriftimfortfuehrungsnachweis','ueberschriftimfortfuehrungsnachweis','ff_faelle','ff_faelle','bpchar','','','1',NULL,NULL,'','Text','','Überschrift im Fortführungsnachweis','','','','','','',NULL,NULL,'5','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'zeigtaufaltesflurstueck','zeigtaufaltesflurstueck','ff_faelle','ff_faelle','_varchar','','','1',NULL,NULL,'','Text','','alte Flurstücke','','','','','','',NULL,NULL,'6','1','0');
INSERT INTO layer_attributes (`layer_id`,`name`,`real_name`,`tablename`,`table_alias_name`,`type`,`geometrytype`,`constraints`,`nullable`,`length`,`decimal_length`,`default`,`form_element_type`,`options`,`alias`,`alias_low-german`,`alias_english`,`alias_polish`,`alias_vietnamese`,`tooltip`,`group`,`raster_visibility`,`mandatory`,`order`,`privileg`,`query_tooltip`) VALUES(@last_layer_id940084,'zeigtaufneuesflurstueck','zeigtaufneuesflurstueck','ff_faelle','ff_faelle','_varchar','','','1',NULL,NULL,'','Text','','neue Flurstücke','','','','','','',NULL,NULL,'7','1','0');

UPDATE layer_attributes SET options = REPLACE(options, '940083', @last_layer_id940083) WHERE layer_id IN(@last_layer_id940083, @last_layer_id940084) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');
UPDATE layer_attributes SET options = REPLACE(options, '940084', @last_layer_id940084) WHERE layer_id IN(@last_layer_id940083, @last_layer_id940084) AND form_element_type IN ('SubFormPK', 'SubFormFK', 'SubFormEmbeddedPK', 'Autovervollständigungsfeld', 'Auswahlfeld');