<?php

return [

    /*
    |--------------------------------------------------------------------------
    | All other Language Lines - TODO: split descriptions and messages?
    |--------------------------------------------------------------------------
    */
    'Academic degree'           => 'Akademischer Titel',
    'Active'					=> 'Aktiv',
    'Active?'					=> 'Aktiv?',
    'Additional Options'		=> 'Zusätzliche Optionen',
    'Address Line 1'			=> 'Adresszeile 1',
    'Address Line 2'			=> 'Adresszeile 2',
    'Address Line 3'			=> 'Adresszeile 3',
    'Amount'                    => 'Betrag',
    'Assigned'					=> 'Zugewiesen -',
    'BIC'						=> 'BIC',
    'Bank fee'                  => 'Bankgebühr',
    'Bank Account Holder'		=> 'Kontoinhaber',
    'Birthday'					=> 'Geburtstag',
    'Business'                  => 'Berufs-/Geschäftsbezeichnung',
    'City'						=> 'Stadt',
    'Choose KML file'			=> 'Wähle KML file',

    'Company'					=> 'Firma',
    'conninfo' => [
        'error' => 'Fehler beim Erstellen der PDF-Datei: ',
        'missing_company' => 'Dem SEPA-Konto ":var" ist kein Unternehmen zugewiesen.',
        'missing_costcenter' => 'Dem Vertrag ist keine Kostenstelle zugewiesen.',
        'missing_sepaaccount' => 'Der Kostenstelle ":var" ist kein SEPA-Konto zugewiesen.',
        'missing_template' => 'Die Vorlage für die Verbindungsinformationen im Unternehmen ":var" ist nicht gesetzt.',
        'missing_template_file' => 'Die Vorlage der Verbindungsinformationen im Unternehmen ":var" existiert nicht als Datei.',
        'read_failure' => 'Fehler beim Lesen/Template ist leer.',
    ],
    'Contract Number'			=> 'Vertragsnummer',
    'Contract Start'			=> 'Vertragsbeginn',
    'Contract End'				=> 'Vertragsende',
    'Contract valid' 			=> 'Vertrag gültig',
    'Contract'					=> 'Vertrag',
    'Contract List'				=> 'Vertragsliste',
    'Contracts'					=> 'Verträge',
    'International prefix'		=> 'Ländervorwahl',
    'Country code'				=> 'Ländercode',
    // Descriptions of Form Fields in Edit/Create
    'accCmd_error_noCC' 	=> 'Den Verträgen :numbers wurde keine Kostenstelle zugewiesen. Für die Kunden wird keine Rechnung erstellt.',
    'accCmd_invoice_creation_deactivated' => 'Bei folgenden Verträgen wurde die Rechnungserstellung deaktiviert: :contractnrs',
    'Create'					=> 'Erstelle',
    'accCmd_processing' 	=> 'Der Abrechnungslauf wird erstellt. Bitte warten Sie bis der Prozess abgeschlossen ist.',
    'Date of installation address change'	=> 'Datum der Änderung der Installationsadresse',
    'Date of value'             => 'Buchungstag',
    'Delete'					=> 'Lösche',
    'Day'						=> 'Tag',
    'Description'				=> 'Beschreibung',
    'Device'					=> 'Gerät',
    'accCmd_notice_CDR' 	=> 'Dem Vertrag :contract_nr [ID :contract_id] werden Einzelverbindungsnachweise abgerechnet, obwohl kein gültiger Telefontarif vorliegt. (Kommt durch verzögerte Abrechnung nach Beenden des Tarifs vor)',
    'Device List'				=> 'Geräteliste',
    'Device Type'				=> 'Gerätetyp',
    'Device Type List'			=> 'Gerätetypenliste',
    'Devices'					=> 'Geräte',
    'DeviceTypes'				=> 'Gerätetypen',
    'Directory assistance'      => 'Nutzung Auskunft',
    'District'					=> 'Ortsteil',
    'Dunning'                   => 'Mahnwesen',
    'Edit'						=> 'Ändern -',
    'Edit '						=> 'Ändere ',
    'Endpoints'					=> 'Endpunkte',
    'Endpoints List'			=> 'Endpunktliste',
    'Entry'						=> 'Eintrag',
    'alert' 				=> 'Achtung!',
    'ALL' 					=> 'ALLE',
    'E-Mail Address'			=> 'Email-Adresse',
    'Entry electronic media'    => 'Datennutzung elektronische Verzeichnisse',
    'Entry in print media'      => 'Datennutzung Print-Verzeichnisse',
    'Entry type'                => 'Suchverzeichnis',
    'Fee'                       => 'Gebühr',
    'Fee for return debit notes' => 'Gebühr für Rücklastschriften',
    'First IP'					=> 'Erste IP',
    'Firstname'					=> 'Vorname',
    'Fixed IP'					=> 'Statische IP',
    'Floor'                     => 'Etage',
    'Force Restart'				=> 'Neustart erzwingen',
    'Geocode origin'			=> 'Herkunft der Geodaten',
    'House number'              => 'Hausnummer',
    'IBAN'						=> 'IBAN',
    'Internet Access'			=> 'Internetzugriff',
    'Inventar Number'			=> 'Inventarnummer',
    'Call Data Record'		=> 'Einzelverbindungsnachweis',
    'IP address'				=> 'IP Adresse',
    'Language'					=> 'Sprache',
    'Lastname'					=> 'Nachname',
    'Last IP'					=> 'Letzte IP',
    'ccc'					    => 'Kundenkontrollzentrum',
    'page_html_header'          => 'Kundenkontrollzentrum',
    'pdflatex' => [
        'default' => 'Fehler beim Ausführen von pdflatex - Rückgabewert: :var',
        'missing' => 'Fehler: pdflatex ist nicht installiert!',
        'syntax'  => 'Pdflatex: Syntax-Fehler in der tex Vorlage (Platzhalter falsch buchstabiert?) :var',
    ],
    'MAC Address'				=> 'MAC Adresse',
    'Main Menu'					=> 'Hauptmenü',
    'Maturity' 					=> 'Laufzeit',
    'cdr' 					=> 'Evn',
    'cdr_discarded_calls' 	=> "EVN: Vertragsnr oder -ID ':contractnr' in Datenbank nicht gefunden - :count Telefongespräche der Telefonnr :phonenr mit einem Preis von :price :currency können nicht zugeordnet werden.",
    'cdr_missing_phonenr' 	=> 'EVN: Einzelverbindungsnachweise mit Telefonnummer :phonenr gefunden, die nicht in der Datenbank existiert. :count Telefongespräche mit einem Preis von :price :currency können nicht zugeordnet werden.',
    'cdr_missing_reseller_data' => 'EVN konnte nicht geladen werden. Reseller Daten in Environment Datei fehlen!',
    'cdr_offset' 			=> 'Zeitdifferenz EVN zu Rechnung in Monaten',
    'close' 				=> 'Schliessen',
    'contract' => [
        'concede_credit' => 'Es existieren Posten mit jährlichem Abrechnungszyklus, die bereits (in vollem Umfang) abgerechnet wurden. Bitte prüfen Sie, ob der Kunde eine Gutschrift erhalten soll!',
        'early_cancel' => 'Möchten Sie den Vertrag wirklich vor Tariflaufzeitende :date kündigen?',
        ],
    'iteM' => [
        'concede_credit' => 'Dieser Posten wurde bereits abgerechnet (möglicherweise für das komplette Jahr). Bitte prüfen Sie, ob der Kunde eine Gutschrift erhalten soll!',
    ],
    'contract_nr_mismatch'  => 'Es konnte keine nächste Vertragsnummer gefunden werden, da die Datenbankabfrage fehl schlug. Die Ursache dafür liegt bei folgenden Verträgen, die eine Vertragsnummer haben, die nicht zur Kostenstelle passt: :nrs. Bitte tragen Sie die korrekte Kostenstelle ein oder lassen Sie eine neue Vertragsnummer für die Verträge vergeben.',
    'contract' => [
        'numberrange' => [
            'failure' => 'Keine freie Vertragsnummer für die gewählte Kostenstelle gefunden.',
            'missing' => 'Es konnte keine neue Kundennummer vergeben werden, da die Kostenstelle über keinen Nummernkreis verfügt.',
        ],
    ],
    'cpe_log_error' 		=> 'ist nicht beim Server registriert - Kein Logeintrag gefunden',
    'cpe_not_reachable' 	=> 'aber via PING nicht erreichbar (ICMP kann herstellerabhängig vom Router geblockt werden)',
    'cpe_fake_lease'		=> 'Der DHCP Server hat kein Lease für den Endpunkt angelegt, weil dessen IP Adresse statisch vergeben ist und der Server diesen somit nicht verfolgen muss. Das folgende Lease wurde lediglich als Referenz manuell generiert:',
    'D' 					=> 'Tag|Tage',
    'dashbrd_ticket' 		=> 'Neue mir zugewiesene Tickets',
    'device_probably_online' =>	':type ist wahrscheinlich online',
    'eom' 					=> 'zum Monatsende',
    'envia_no_interaction' 	=> 'Keine envia-TEL-Aufrträge, die eine Bearbeitung benötigen',
    'Month'						=> 'Monat',
    'envia_interaction'	 	=> 'envia-TEL-Auftrag benötigt eine Bearbeitung|envia-TEL-Aufträge benötigen Bearbeitung',
    'Net'						=> 'Netto',
    'Netmask'					=> 'Netzmaske',
    'Internet Access'			=> 'Internetzugriff',
    'no' 						=> 'nein',
    'Noble rank'                => 'Historischer Namenszusatz/Adelstitel',
    'Nobiliary particle'        => 'Vorsatzwort',
    'Number'					=> 'Nummer',
    'Number usage'              => 'Kennzeichnung, wie Nummer genutzt wird',
    'Options'					=> 'Optionen',
    'or: Upload KML file'		=> 'oder lade KML hoch',
    'Other name suffix'         => 'Sonstiger Namenszusatz',
    'Parent Device Type'		=> 'Eltern Gerätetyp',
    'Parent Object'				=> 'Eltern Objekt',
    'Period of Notice' 			=> 'Kündigungsfrist',
    'Password'					=> 'Passwort',
    'Confirm Password'					=> 'Passwort bestätigen',
    'Phone'						=> 'Telefon',
    'Phone ID next month'		=> 'Telefon ID nächsten Monat',
    'Phonenumber'				=> 'Telefonnummer',
    'Phonenumbers'				=> 'Telefonnummern',
    'Phonenumbers List'			=> 'Telefonnummernliste',
    'Postcode'					=> 'Postleitzahl',
    'Prefix Number'				=> 'Vorwahl',
    'Price'						=> 'Preis',
    'Public CPE'				=> 'Öffentliches CPE',
    'Publish address'           => 'Veröffentlichungs-Kennzeichen-Adresse',
    'QoS next month'			=> 'QoS nächsten Monat',
    'Real Time Values'			=> 'Echtzeitwerte',
    'Realty'                    => 'Liegenschaft',
    'Remember Me'				=> 'An diesem Gerät eingeloggt bleiben',
    'Reverse search'            => 'Inverse Suche',
    'Salutation'				=> 'Anrede',
    'Save'						=> 'Speichern',
    'Save All'					=> 'Alle Speichern',
    'Save / Restart'			=> 'Speichern / Neustart',
    'Serial Number'				=> 'Seriennummer',
    'Sign me in' 				=> 'Anmelden',
    'snmp' => [
        'errors_walk' => 'Die Abfrage folgender OIDs schlug fehl: :oids.',
        'errors_set' => 'Das Setzen folgender Parameter schlug fehl: :oids.',
        'missing_cmts' => 'Dem Cluster fehlt ein übergeordnetes CMTS.',
        'undefined' => 'Für diesen Netzelementtyp ist kein Controlling definiert.',
        'unreachable' => 'Das Gerät ist nicht per SNMP erreichbar.',
    ],
    'State'						=> 'Status',
    'Street'					=> 'Straße',
    'Typee'						=> 'Typ',
    'Unexpected exception' 		=> 'Unerwarteter Fehler',
    'US level' 					=> 'US Pegel',
    'Username'					=> 'Nutzername',
    'Users'						=> 'Nutzer',
    'Vendor'					=> 'Hersteller',
    'Year'						=> 'Jahr',
    'yes' 						=> 'ja',
    'home' 						=> 'Startseite',
    'indices_unassigned' 		=> 'Einer/Einige der zugewiesenen Indizes konnten keinem Parameter zugeordnet werden! Sie werden somit aktuell nur nicht genutzt. Sie können gelöscht oder für später behalten werden. Vergleichen Sie dazu die Parameterliste im Netzelement mit der Liste der Indizes!',
    'item_credit_amount_negative' => 'Ein negativer Betrag bei Gutschriften wird zur Lastschrift für den Kunden! Sind Sie sicher, dass der Betrag dem Kunde abgezogen werden soll?',
    'invoice' 					=> 'Rechnung',
    'Global Config'				=> 'Globale Konfiguration',
    'GlobalConfig'				=> 'Globale Konfiguration',
    'VOIP'						=> 'VoIP',
    'Customer Control Center'	=> 'Kundenkontrollzentrum',
    'Provisioning'				=> 'Provisionierung',
    'BillingBase'				=> 'Billing Base Konfiguration',
    'Ccc' 						=> 'CCC Konfiguration',
    'HfcBase' 					=> 'HfcBase Konfiguration',
    'ProvBase' 					=> 'ProvBase Konfiguration',
    'ProvVoip' 					=> 'ProvVoip Konfiguration',
    'ProvVoipEnvia' 			=> 'ProvVoipEnvia Konfiguration',
    'HFC'						=> 'HFC',
    'Rank'						=> 'Rang',
    'Assign Users'				=> 'Benutzer zuweisen',
    'Invoices'					=> 'Rechnungen',
    'Ability'					=> 'Fähigkeit',
    'Allow'						=> 'Erlauben',
    'Allow to'					=> 'Erlaube',
    'Forbid'					=> 'Verbieten',
    'Forbid to'					=> 'Verbiete',
    'Save Changes'				=> 'Änderungen speichern',
    'Manage'					=> 'Verwalten',
    'View'						=> 'Ansehen',
    'Create'					=> 'Erstelle',
    'Update'					=> 'Ändern',
    'Delete'					=> 'Lösche',
    'Help'						=> 'Hilfe',
    'All abilities'				=> 'Alle Fähigkeiten',
    'View everything'			=> 'Alle Seiten ansehen',
    'Use api'					=> 'API benutzen',
    'See income chart'			=> 'Einkommensdiagramm ansehen',
    'View analysis pages of modems'	=> 'Analyseseite der Modems aufrufen',
    'View analysis pages of cmts' => 'Analyseseite der CMTS aufrufen',
    'Download settlement runs'	=> 'Abrechnungsläufe downloaden',
    'Not allowed to acces this user' => 'Zugriff auf diesen Nutzer ist nicht gestattet',
    'log_out'				=> 'Ausloggen',
    'System Log Level'			=> 'System Logging Stufe',
    'Headline 1'				=> 'Überschrift Kopfzeile',
    'Headline 2'				=> 'Überschrift Navigationsleiste',
    'M' 					=> 'Monat|Monate',
    'Mark solved'			=> 'Als gelöst markeren?',
    'missing_product' 		=> 'Fehlendes Produkt!',
    'modem_eventlog_error'	=> 'Modem Eventlog nicht gefunden',
    'modem_force_restart_button_title' => 'Startet nur das Modem neu. Speichert keine geänderten Daten!',
    'modem_reset_button_title' => 'Resettet nur das Modem. Speichert keine geänderten Daten!',
    'CDR retention period' 		=> 'Aufbewahrungsfrist für Einzelverbindungsnachweise',
    'Day of Requested Collection Date'	=> 'Monatlicher Abrechnungstag',
    'Tax in %'					=> 'Mehrwertsteuer in %',
    'Invoice Number Start'		=> 'Start Nummerierung Rechnungen',
    'Split Sepa Transfer-Types'	=> 'SEPA-Transfertypen aufteilen?',
    'Mandate Reference'			=> 'Mandatsrefferenz',
    'e.g.: String - {number}'	=> 'z.Bsp.: Sring - {Nummer}',
    'Item Termination only end of month'=> 'Posten nur am ende des Monats kündigen?',
    'Language for settlement run' => 'Sprache für Abrechnungslauf',
    'Uncertain start/end dates for tariffs' => 'Ungewisse Tarif-Start-/Enddaten',
    'modem_monitoring_error'=> 'Möglicherweise war das Modem bis jetzt nicht online. Beachten Sie, dass Diagramme erst ab
		dem Zeitpunkt verfügbar sind, von dem an das Modem online ist. Wurden alle Diagramme unsauber angezeigt, könnte es
		sich um ein größeres Problem, wie eine Fehlkonfiguration von Cacti, handeln. Wenden Sie sich dazu an ihren Administrator.',
    'Connection Info Template'	=> 'Vorlage für Verbindungsinformationen',
    'Upload Template'			=> 'Vorlage hochladen',
    'SNMP Read Only Community'	=> 'SNMP Read Only Community',
    'SNMP Read Write Community'	=> 'SNMP Read Write Community',
    'Provisioning Server IP'	=> 'Provisionierungsserver',
    'Domain Name for Modems'	=> 'Modem Domain Name',
    'Notification Email Address'=> 'Benachrichtigungs E-Mail Adresse',
    'DHCP Default Lease Time'	=> 'DHCP Standard Lease Zeit',
    'DHCP Max Lease Time'		=> 'DHCP Maximale Lease Zeit',
    'Start ID Contracts'		=> 'Start Nummerierung Verträge',
    'Start ID Modems'			=> 'Start Nummerierung Modems',
    'Start ID Endpoints'		=> 'Start Nummerierung Endpunkte',
    'Downstream rate coefficient' => 'Übertragungsratenkoeffizient Vorwärtskanal',
    'Upstream rate coefficient' => 'Übertragungsratenkoeffizient Rückwärtskanal',
    'Additional modem reset button' => 'Zusätzlicher Modem Reset Button',
    'modemAnalysis' => [
        'cfOutdated' => 'Das Modem läuft nicht mit dem aktuellen Configfile! Der letzte Download fand vor dem Erzeugen des Configfiles statt.',
        'cfError' => 'Beim Erstellen des Configfiles gab es einen Fehler! Es existiert daher noch kein Configfile für dieses Modem. Bitte im Laravel Log für eine genauere Fehlerbeschreibung nachsehen.',
        'cpeMacMissmatch' => 'Der Status über Internetzugriff und Telefonie kann nicht ermittelt werden, da sich im Configfile mindestens eine der CPE MAC Adressen von den MACs der zugewiesenen MTAs unterscheidet.',
        'fullAccess' => 'Internetzugriff und Telefonie ist freigeschalten. (gemäß Configfile)',
        'missingLD' => 'Info: Der letzte Download des Configfiles ist zu lang her, um festzustellen ob das Modem die aktuellen Konfigurationen übernommen hat.',
        'noNetworkAccess' => 'Internetzugriff und Telefonie ist gesperrt. (gemäß Configfile)',
        'onlyVoip' => 'Internetzugriff ist gesperrt. Nur Telefonie ist erlaubt. (gemäß Configfile)',
    ],
    'modem_no_diag'         => 'Keine Diagramme verfügbar',
    'Start ID MTA´s'			=> 'Start Nummerierung MTA\'s',
    'modem_lease_error'		=> 'Kein gültiger Lease gefunden',
    'modem_lease_valid' 	=> 'Modem hat einen gültigen Lease',
    'modem_log_error' 		=> 'Modem ist nicht beim Server registriert - Kein Logeintrag gefunden',
    'modem_configfile_error'=> 'Modem Konfigurationsdatei nicht gefunden',
    'Academic Degree'			=> 'Akademischer Titel',
    'modem_offline'			=> 'Modem ist Offline',
    'Contract number'			=> 'Vertragsnummer',
    'Contract Nr'				=> 'Vertragsnr',
    'Contract number legacy'	=> 'Historische Vertragsnummer',
    'Cost Center'				=> 'Kostenstelle',
    'Create Invoice'			=> 'Rechnung erstellen',
    'Customer number'			=> 'Kundennummer',
    'Customer number legacy'	=> 'Historische Kundennummer',
    'Department'				=> 'Abteilung',
    'End Date' 					=> 'Enddatum',
    'House Number'				=> 'Hausnummer',
    'House Nr'					=> 'Hausnr',
    'Salesman'					=> 'Verkäufer',
    'Start Date' 				=> 'Startdatum',
    'modem_restart_error' 		=> 'Das Modem konnte nicht neugestartet werden! (offline?)',
    'Contact Persons' 			=> 'Antennengemeinschaft/Kontakt',
    'modem_restart_success_cmts' => 'Das Modem wurde erfolgreich über das CMTS neugestartet',
    'Accounting Text (optional)'=> 'Verwendungszweck (optional)',
    'Cost Center (optional)'	=> 'Kostenstelle (optional)',
    'Credit Amount' 			=> 'Gutschrift - Betrag',
    'modem_restart_success_direct' => 'Das Modem wurde erfolgreich direkt über SNMP neugestartet',
    'Item'						=> 'Posten',
    'Items'						=> 'Posten',
    'modem_save_button_title' 	=> 'Speichert geänderte Daten. Berechnet die Geoposition neu, wenn Adressdaten geändert wurden und weist es ggf. einer neuen MPR hinzu (falls sich x/y Koordinate geändert hat oder manuell geändert wurde). Baut das Configfile und startet das Modem neu, wenn sich mindestens eines der folgenden Einträge geändert hat: Öffentliche IP, Netzwerkzugriff, Configfile, QoS, MAC-Adresse.',
    'Product'					=> 'Produkt',
    'Start date' 				=> 'Startdatum',
    'Active from start date' 	=> 'Ab Startdatum aktiv',
    'Valid from'				=> 'Startdatum',
    'Valid to'					=> 'Enddatum',
    'Valid from fixed'			=> 'Ab Startdatum aktiv',
    'Valid to fixed'			=> 'Festes Enddatum',
    'modem_statistics'		=> 'Anzahl Online / Offline Modems',
    'Configfile'				=> 'Konfigurationsdatei',
    'Mta'						=> 'MTA',
    'month' 				=> 'Monat',
    'Configfiles'				=> 'Konfigurationsdatei',
    'Choose Firmware File'		=> 'Firmware-Datei auswählen',
    'Config File Parameters'	=> 'Parameter für die Konfigurationsdatei',
    'or: Upload Certificate File'	=> 'oder: Zertifikat-Datei hochladen',
    'or: Upload Firmware File'	=> 'oder: Firmware-Datei hochladen',
    'Parent Configfile'			=> 'Übergeordnete Konfigurationsdatei',
    'Public Use'				=> 'Öffentliche Nutzung',
    'mta_configfile_error'	=> 'MTA Konfigurationsdatei nicht gefunden',
    'IpPool'						=> 'IP-Bereich',
    'SNMP Private Community String'	=> 'SNMP privater Community String',
    'SNMP Public Community String'	=> 'SNMP öffentlicher Community String',
    'noCC'					=> 'Keine Kostenstelle zugewiesen',
    'IP-Pools'					=> 'IP-Bereich',
    'Type of Pool'				=> 'Art des IP-Bereichs',
    'Type of signal' => 'Type of Signal',
    'IP network'				=> 'IP Netz',
    'IP netmask'				=> 'IP Netzmaske',
    'IP router'					=> 'IP Router',
    'oid_list' 				=> 'Achtung: OIDs, die nicht bereits in der Datenbank existieren werden nicht beachtet! Bitte laden Sie das zuvor zugehörige MibFile hoch!',
    'Phone tariffs'				=> 'Telefontarife',
    'External Identifier'		=> 'Externer Identifikator',
    'Usable'					=> 'Verfügbar?',
    'password_change'		=> 'Passwort ändern',
    'password_confirm'		=> 'Password bestätigen',
    'phonenumber_missing'       => 'Die Telefonnummer :phonenr des Vertrages :contractnr fehlt im System, :provider berechnet aber Telefonate.',
    'phonenumber_mismatch'      => 'Die Telefonnummer :phonenr gehört aktuell nicht zum Vertrag :contractnr. Damit wird gegebenenfalls der falsche Vertrag/Kunde für die Gespräche abgerechnet.',
    'phonenumber_nr_change_hlkomm' => 'Beim Ändern dieser Nummer können die angefallen Gespräche der alten Nummer nicht mehr diesem Vertrag angerechnet werden, da HL Komm bzw. Pyur nur die Telefonnummer in den Einzelverbindungsnachweisen mitschickt. Bitte ändern Sie diese Nummer nur, wenn es sich um eine Testnummer handelt oder Sie sicher sind, dass keine Gespräche mehr abgerechnet werden.',
    'phonenumber_overlap_hlkomm' => 'Diese Nummer existiert bereits oder hat im/in den letzten :delay Monat(en) exisiert. Da HL Komm oder Pyur in den Einzelverbindungsnachweisen nur die Telefonnummer mitsendet, wird es nicht möglich sein getätigte Anrufe zum richtigen Vertrag zuzuweisen! Das kann zu falschen Abrechnungen führen. Bitte fügen Sie die Nummer nur hinzu, wenn es sich um eine Testnummer handelt oder Sie sicher sind, dass keine Gespräche mehr abgerechnet werden.',
    'show_ags' 				=> 'Zeige AG Auswahlfeld auf Vertragsseite',
    'snmp_query_failed' 	=> 'SNMP-Abfrage fehlgeschlagen für folgende OIDs: ',
    'Billing Cycle'				=> 'Abrechnungszyklus',
    'Bundled with VoIP product?'=> 'Gebündelt mit VoIP-Produkt?',
    'Calculate proportionately' => 'Anteilig berechnen',
    'Price (Net)'				=> 'Preis (Netto)',
    'Number of Cycles'			=> 'Anzahl der Zyklen',
    'Product Entry'				=> 'Produkteintrag',
    'Qos (Data Rate)'			=> 'QoS (Datenrate)',
    'with Tax calculation ?'	=> 'mit Steuern berechnen?',
    'Phone Sales Tariff'		=> 'Telefontarif Verkauf/Endkunde',
    'Phone Purchase Tariff'		=> 'Telefontarif Einkauf',
    'sr_repeat' 			=> 'Wiederholen für SEPA-Konto:', // Settlementrun repeat
    'Account Holder'			=> 'Kontoinhaber',
    'Account Name'				=> 'Kontoname',
    'Choose Call Data Record template file'	=> 'Wählen Sie eine Einzelverbindungsnachweis-Vorlage',
    'Choose invoice template file'			=> 'Wählen Sie eine Rechnungs-Vorlage',
    'CostCenter'				=> 'Kostenstelle',
    'Creditor ID' => 'Gläubiger ID',
    'Institute'					=> 'Bank',
    'Invoice Headline'			=> 'Rechnungsüberschrift',
    'Invoice Text for negative Amount with Sepa Mandate'	=> 'Text für negativen Betrag mit SEPA-Mandat',
    'Invoice Text for negative Amount without Sepa Mandate'	=> 'Text für negativen Betrag ohne SEPA-Mandat',
    'Invoice Text for positive Amount with Sepa Mandate'	=> 'Text für positiven Betrag mit SEPA-Mandat',
    'Invoice Text for positive Amount without Sepa Mandate'	=> 'Text für positiven Betrag ohne SEPA-Mandat',
    'SEPA Account'				=> 'SEPA-Konto',
    'SepaAccount'				=> 'SEPA-Konto', // siehe Companies
    'upload_dependent_mib_err' => "Bitte Laden Sie zuvor die ':name' hoch! (Die zugehörigen OIDs können sonst nicht geparsed werden)",
    'Upload CDR template'		=> 'Einzelverbindungsnachweis-Vorlage hochladen',
    'Upload invoice template'	=> 'Rechnungsvorlage hochladen',
    'user_settings'			=> 'Nutzereinstellungen',
    'user_glob_settings'	=> 'Globale Nutzereinstellungen',

    'voip_extracharge_default' => 'Preisaufschlag Telefonie Standard in %',
    'voip_extracharge_mobile_national' => 'Preisaufschlag Telefonie Mobilfunk national in %',
    'General'				=> 'Allgemein',
    'Verified'				=> 'Geprüft',
    'tariff'				=> 'Tarif',
    'item'					=> 'Posten',
    'sepa'                  => 'mit_SEPA',
    'no_sepa'               => 'ohne_SEPA',
    'Call_Data_Records'     => 'Einzelverbindungsnachweise',
    'Y'                     => 'Jahr|Jahre',
    'accounting'            => 'Rechnungssatzdatei',
    'booking'               => 'Buchungssatzdatei',
    'DD'                    => 'SEPA Lastschriften',
    'DD_FRST'               => 'SEPA Erstlastschriften',
    'DD_RCUR'               => 'SEPA Wiederkehrende Lastschriften',
    'DD_OOFF'               => 'SEPA Einzellastschriften',
    'DD_FNAL'               => 'SEPA Finallastschriften',
    'DC'					=> 'SEPA Gutschriften',
    'salesmen_commission'	=> 'Verkäuferprovision',
    'Assign Role'				=> 'Rollen zuweisen',
    'Associated SEPA Account'	=> 'Verknüpftes SEPA-Konto',
    'Month to create Bill'		=> 'Monat für den eine Rechnung erstellt werden soll',
    'Choose logo'			=> 'Logo auswählen',
    'Directorate'			=> 'Geschäftsführer',
    'Mail address'			=> 'Mail Adresse',
    'Management'			=> 'Verwaltung',
    'Registration Court 1'	=> 'Verwaltungsgericht 1',
    'Registration Court 2'	=> 'Verwaltungsgericht 2',
    'Registration Court 3'	=> 'Verwaltungsgericht 3',
    'Sales Tax Id Nr'		=> 'Umsatzsteuer-Nr',
    'Tax Nr'				=> 'Steuer-Nr.',
    'Transfer Reason for Invoices'	=> 'Grund für Rechnungsübertrag',
    'Upload logo'			=> 'Logo hochladen',
    'Web address'			=> 'Web-Adresse',
    'Zip'					=> 'PLZ',
    'Commission in %'		=> 'Provision in %',
    'Product Types'			=> 'Produkttyp(en)',
    'Already recurring ?' 	=> 'Bereits wiederkehrend?',
    'Date of Signature' 	=> 'Datum der Unterzeichnung',
    'Disable temporary' 	=> 'Vorübergehend deaktivieren',
    'Reference Number' 		=> 'Referenznummer',
    'Bank Bank Institutee' 		=> 'Bank',
    'Contractnr'			=> 'Kundennummer',
    'Invoicenr'				=> 'Rechnungsnummer',
    'Calling Number'		=> 'Nummer des Anrufers',
    'Called Number'			=> 'Nummer des Angerufenen',
    'Target Month'			=> 'Für Monat',
    'Date'					=> 'Datum',
    'Count'					=> 'Anzahl',
    'Tax'					=> 'MwSt.',
    'RCD'         => 'Fälligkeitsdatum',
    'Currency'				=> 'Währung',
    'Gross'					=> 'Brutto',
    'Net'					=> 'Netto',
    'MandateID'     => 'SEPA Mandatsnummer',
    'MandateDate'   => 'SEPA Mandatsdatum',
    'Commission in %'		=> 'Provision in %',
    'Total' => 'Absolut',
    'Total fee'				=> 'Gebühren gesamt',
    'Commission Amount'		=> 'Provision',
    'Zip Files' 			=> 'ZIP Dateien',
    'primary'				=> 'Primär',
    'secondary'				=> 'Sekundär',
    'disabled'				=> 'deaktiviert',
    'Value (deprecated)'          => 'Wert (veraltet)',
    'Priority (lower runs first)' => 'Priorität (niedrigere wird zuerst ausgeführt)',
    'Priority' 				=> 'Priorität',
    'Title' 				=> 'Titel',
    'Created at'			=> 'Erstellt am',
    'Activation date'       => 'Aktivierungsdatum',
    'Deactivation date'     => 'Deaktivierungsdatum',
    'SIP domain'            => 'Registrar',
    'Created at' 			=> 'Erstellt am',
    'Last status update'	=> 'Letztes Update',
    'Last user interaction' => 'Letzte Interaktion',
    'Method'				=> 'Methode',
    'Ordertype ID'			=> 'Bestelltyp ID',
    'Ordertype'				=> 'Bestelltyp',
    'Orderstatus ID'		=> 'Bestellstatusnummer',
    'Orderstatus'			=> 'Bestellstatus',
    'Orderdate'				=> 'Bestelldatum',
    'Ordercomment'			=> 'Kommentar',
    'Envia customer reference' => 'envia-TEL-Kundenreferenz',
    'Envia contract reference' => 'envia-TEL-Vertragsreferenz',
    'Contract ID'			=> 'Vertragsnummer',
    'Phonenumber ID'		=> 'Telefonnummer ID',
    'Related order ID'		=> 'Verwandte Bestellnummer',
    'Related order type'	=> 'Verwandter Bestelltyp',
    'Related order created' => 'Verwandte Bestellung erstellt',
    'Related order last updated' => 'Letzte Aktualisierung der verwandten Bestellung',
    'Related order deleted'	=> 'Verwandte Bestellung gelöscht',
    'Envia Order'			=> 'envia-TEL-Auftrag',
    'Document type'			=> 'Dokumenttyp',
    'Upload document'		=> 'Dokument hochladen',
    'Call Start'			=> 'Anrufbeginn',
    'Call End'				=> 'Anrufende',
    'Call Duration/s'		=> 'Gesprächsdauer',
    'min. MOS'				=> 'Min. MOS',
    'Packet loss/%'			=> 'Paketverlust in %',
    'Jitter/ms'				=> 'Jitter in ms',
    'avg. Delay/ms'			=> 'durchschnittliche Verzögerung in ms',
    'Caller (-> Callee)'	=> 'Anrufer (-> Angerufener)',
    '@Domain'				=> '@Domain',
    'min. MOS 50ms'			=> 'Min. MOS 50ms',
    'min. MOS 200ms'		=> 'Min. MOS 200ms',
    'min. MOS adaptive 500ms'	=> 'Min. MOS adaptive 500ms',
    'avg. MOS 50ms'			=> 'Durchschnittler MOS 50ms.',
    'avg. MOS 200ms'		=> 'Durchschnittler MOS 200ms',
    'avg. MOS adaptive 500ms'	=> 'Durchschnittler MOS 500ms',
    'Received Packets'		=> 'Empfangene Pakete',
    'Lost Packets'			=> 'Verlorene Pakete',
    'avg. Delay/ms'			=> 'durchschnittliche Verzögerung in ms',
    'avg. Jitter/ms'		=> 'durchschnittlicher Jitter in ms',
    'max. Jitter/ms'		=> 'maximaler Jitter in ms',
    '1 loss in a row'		=> 'Einfacher Paketverlust',
    '2 losses in a row'		=> '2 Pakete in Folge verloren',
    '3 losses in a row'		=> '3 Pakete in Folge verloren',
    '4 losses in a row'		=> '4 Pakete in Folge verloren',
    '5 losses in a row'		=> '5 Pakete in Folge verloren',
    '6 losses in a row'		=> '6 Pakete in Folge verloren',
    '7 losses in a row'		=> '7 Pakete in Folge verloren',
    '8 losses in a row'		=> '8 Pakete in Folge verloren',
    '9 losses in a row'		=> '9 Pakete in Folge verloren',
    'PDV 50ms - 70ms' => 'PDV von 50 bis 70ms',
    'PDV 70ms - 90ms' => 'PDV von 70 bis 90ms',
    'PDV 90ms - 120ms' => 'PDV von 90 bis 120ms',
    'PDV 120ms - 150ms' => 'PDV von 120 bis 150ms',
    'PDV 150ms - 200ms' => 'PDV von 150 bis 200ms',
    'PDV 200ms - 300ms' => 'PDV von 200 bis 300ms',
    'PDV >300 ms' => 'PDV von über 300ms',
    'Callee (-> Caller)'	=> 'Angerufener (-> Anrufer)',
    'Credit' => 'Gutschrift',
    'Other'                     => 'Sonstiges',
    'Once'                      => 'Einmalig',
    'Monthly'                   => 'Monatlich',
    'Quarterly'                 => 'Vierteljährlich',
    'Yearly'                    => 'Jährlich',
    'NET'                       => 'Netz',
    'CMTS'                      => 'CMTS',
    'DATA'                      => 'Daten',
    'CLUSTER'                   => 'Cluster',
    'NODE'                      => 'Knoten',
    'AMP'                       => 'Verstärker',
    'None'                      => 'Keins',
    'Null'                      => 'Null',
    'generic'                   => 'Allgemein',
    'network'                   => 'Netzwerk',
    'vendor'                    => 'Hersteller',
    'user'                      => 'Benutzer',
    'Yes'                       => 'Ja',
    'No'                        => 'Nein',
    'Has telephony'             => 'Hat Telefonie',
    'OID for PreConfiguration Setting' => 'OID für Vorkonfiguration',
    'PreConfiguration Value'    => 'Wert für Vorkonfiguration',
    'PreConfiguration Time Offset' => 'Zeitverzögerung zwischen Vorkonfig & SNMP-Abfrage',
    'Reload Time - Controlling View' => 'Reload Time - Controlling View',
    'Due Date'                  => 'Fälligkeitsdatum',
    'Type'                      => 'Typ',
    'Assigned users'            => 'Zugewiesene Nutzer',
    'active contracts'          => 'Aktive Verträge',
    'assigned_items'            => 'Diesem Produkt sind Posten zugewiesen',
    'Product_Delete_Error'      => 'Das Produkt mit der ID :id konnte nicht gelöscht werden',
    'Product_Successfully_Deleted' => 'Das Produkt mit der ID :id wurde erfolgreich gelöscht',
    'total'                     => 'Insgesamt',
    'new_items'                 => 'Neue Posten',
    'new_customers'             => 'Neukunden',
    'cancellations'             => 'Kündigungen',
    'support'                   => 'Hilfe',
    'Balance'                   => 'Saldo',
    'Week'                      => 'Woche',
    'log_msg_descr'             => 'Zeige Beschreibungen zu den Logeinträgen',
    'postalInvoices'            => 'Postalische Rechnungen',
    'zipCmdProcessing'          => 'PDF mit postalischen Rechnungen wird erstellt',
    'last'                      => 'Letzter|Letzte',
    'of'                        => 'von',
    'parts'                     => 'Teil|Teile',
    'purchase'                  => 'Einkauf',
    'sale'                      => 'Verkauf',
    'position rectangle'        => 'Rechteck',
    'position polygon'          => 'Polygon (Vieleck)',
    'nearest amp/node object'   => 'Nächster Verstärker/Knoten',
    'assosicated upstream interface' => 'Zugewiesenes Upstream Interface',
    'cluster (deprecated)'      => 'Cluster (veraltet)',
    'Cable Modem'               => 'Kabelmodem',
    'CPE Private'               => 'Private CPE-IPs',
    'CPE Public'                => 'Öffentliche CPE-IPs',
    'MTA'                       => 'MTA',
    'Minimum Maturity'          => 'Mindestlaufzeit',
    'Enable AGC'                => 'AGC aktivieren',
    'AGC offset'                => 'AGC Versatz',
    'spectrum'                  => 'Spektrum',
    'levelDb'                   => 'Pegel in dBmV',
    'noSpectrum'                => 'Für dieses Modem kann kein Spektrum erstellt werden.',
    'createSpectrum'            => 'Spektrum erstellen',
    'configfile_outdated'       => 'Konfigurationsdatei ist veraltet - Fehler beim Generieren der Datei!',
    'shouldChangePassword'       => 'Bitte ändern Sie Ihr Passwort!',
    'PasswordExpired'           => 'Ihr Passwort ist abgelaufen. Passwörter sollten regelmäßig gewechselt werden um sicher zu bleiben. Vielen Dank!',
    'newUser'                   => 'Willkommen in NMS Prime. Bitte ändern Sie zur Sicherheit Ihr Passwort. Vielen Dank!',
    'Password Reset Interval'   => 'Passwortwechsel-Intervall',
    'PasswordClick'             => 'Klicken Sie HIER um Ihr Passwort zu ändern.',
    'hello'                     => 'Hallo',
    'newTicketAssigned'         => 'dir wurde ein neues Ticket zugewiesen.',
    'ticket'                    => 'Ticket',
    'title'                     => 'Titel',
    'description'               => 'Beschreibung',
    'ticketUpdated'             => 'Ticket :id geändert',
    'newTicket'                 => 'Neues Ticket',
    'deletedTicketUsers'        => 'Gelöscht von Ticket :id',
    'deletedTicketUsersMessage' => 'Du wurdest aus dem Ticket Nr. :id entfernt.',
    'ticketUpdatedMessage'      => 'das Ticket wurde geändert.',
    'noReplyMail'               => 'Adresse der Noreply E-mail',
    'noReplyName'               => 'Name der Noreply E-mail',
    'deleteSettlementRun'       => 'Abrechnungslauf :time wird gelöscht...',
    'created'                   => 'Erstellt!',
    'Urban district'            => 'Ortsnamenzusatz',
    'Zipcode'                   => 'Postleitzahl',
    'base' => [
        'delete' => [
            'success' => ':model :id wurde gelöscht',
            'fail' => ':model :id konnte nicht gelöscht werden',
        ],
    ],
    'pleaseWait'                => 'Das Erstellen des Spektrums kann einige Sekunden dauern.',
    'import'                    => 'Importieren',
    'exportConfigfiles'         => 'Exportiert diese Konfigurationsdatei und deren untergeordnete Dateien.',
    'importTree'                => 'Bitte geben Sie die dazu zugehörige Übergeordnete Konfigurationsdatei an.',
    'exportSuccess'             => ':name exportiert!',
    'setManually'               => ':file von :name muss manuell hinzugefügt werden.',
    'invalidJson'               => 'Die ausgewählte Datei ist falsch formattiert oder keine JSON.',
    'proximity'                 => 'Umgebungssuche',
    'all'                       => 'Alle',
    'dashboard'                 => [
        'log' =>[
            'created'       => 'erstellt',
            'deleted'       => 'löscht',
            'updated'       => 'aktualisiert',
            'updated N:M'   => 'aktualisiert',
        ],
    ],
    'Modem'                         => 'Modem',
    'PhonenumberManagement'         => 'Phonenumber Management',
    'NetElement'                    => 'Netzelement',
    'SepaMandate'                   => 'SEPA-Mandat',
    'EnviaOrder'                    => 'EnviaOrder',
    'Ticket'                        => 'Ticket',
    'CccUser'                       => 'CCC-Benutzer',
    'EnviaOrderDocument'            => 'EnviaOrderDocument',
    'EnviaContract'                 => 'Envia Vertag',
    'Endpoint'                      => 'Endpunkt',
    'PhonebookEntry'                => 'Telefonbucheintrag',
    'Sla'                           => 'SLA',
    'TRC class'                 => 'Sperrklasse',
    'Carrier in'                => 'Carrier (Altvertrag)',
    'EKP in'                    => 'EKP (Altvertrag)',
    'Incoming porting'          => 'Eingehende Rufnummernportierung',
    'Outgoing porting'          => 'Ausgehende Rufnummernportierung',
    'Subscriber company'        => 'Firma (Altvertrag)',
    'Subscriber department'     => 'Abteilung (Altvertrag)',
    'Subscriber salutation'     => 'Anrede (Altvertrag)',
    'Subscriber academic degree'    => 'Akademischer Titel (Altvertrag)',
    'Subscriber firstname'      => 'Vorname (Altvertrag)',
    'Subscriber lastname'       => 'Nachname (Altvertrag)',
    'Subscriber street'         => 'Straße (Altvertrag)',
    'Subscriber house number'   => 'Hausnummer (Altvertrag)',
    'Subscriber zipcode'        => 'Postleitzahl (Altvertrag)',
    'Subscriber city'           => 'Ort (Altvertrag)',
    'Subscriber district'       => 'Ortsteil (Altvertrag)',
    'Termination date'          => 'Deaktivierungsdatum',
    'Carrier out'               => 'Carrier (Folgevertrag)',
    'geopos_x_y'                => 'geografische Länge/Breite',
    'error'                     => 'Fehler',
];
