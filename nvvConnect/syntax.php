<?php
/**
 * nvvConnect Plugin: Zeigt NVV-Ergebnisse und Tabelle an
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Benjamin Förster <dw@benni.dfoerster.de>
 */
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
 
class syntax_plugin_nvvConnect extends DokuWiki_Syntax_Plugin {
    /* EINSTELLUNGEN */
    function __construct() {
		# Welche Saisons sind gespeichert?
		$this->all_seasons = Array("2010", "2011", "2012", "2013", "2014");
		
		# Aktuelle Saison
		$this->season = "2014";
		
        $this->matches = array(
            # Tag auf entsprechende Methode verweisen
            'nvvTabelle' => '_printTabelle',
            'nvvTabelleKlein' => '_printTabelleKlein',
            'nvvGespielt' => '_printGespielt',
            'nvvGespieltKlein' => '_printGespieltKlein',
            'nvvVorschau' => '_printVorschau',
            'nvvVorschauNext' => '_printVorschauNext',
            'nvvPlatz' => '_printTabellenplatz',
            'nvvPlaetze' => '_printTabellenplaetze',
            'nvvPlatzKurz' => '_printTabellenplatzKurz',
            );
        $this->teams = array(
            # unsere Teams
			"2010" => Array(
				"d1" => Array("id"=>"1643", "name"=>"TuS Rüppurr",
	                        "team" => 'd1', "tname"=>'Damen 1',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"d2" => Array("id"=>"1642", "name"=>"TuS Rüppurr II",
	                        "team" => 'd2', "tname"=>'Damen 2',
							'liga_kurz'=>'BL', 'liga_lang' => 'Bezirksliga'),
				"h1" => Array("id"=>"1657", "name"=>"VSG Ettl/Rüppurr I",
	                        "team" => 'h1', "tname"=>'Herren 1',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"h2" => Array("id"=>"1657", "name"=>"VSG Ettl/Rüppurr II",
	                        "team" => 'h2', "tname"=>'Herren 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"h3" => Array("id"=>"1665", "name"=>"VSG Ettlingen/Rüppurr III",
	                        "team" => 'h3', "tname"=>'Herren 3',
							'liga_kurz'=>'BL', 'liga_lang' => 'Bezirksliga'),
				"h4" => Array("id"=>"1689", "name"=>"VSG Ettlingen/Rüppurr IV",
	                        "team" => 'h4', "tname"=>'Herren 4',
							'liga_kurz'=>'BK', 'liga_lang' => 'Bezirksklasse'),
			 ),
			"2011" => Array(
				"h1" => Array("id"=>"1751", "name"=>"VSG Ettlingen/Rüppurr",
	                        "team" => 'h1', "tname"=>'Herren 1',
							'liga_kurz'=>'VL', 'liga_lang' => 'Verbandsliga'),
				"h2" => Array("id"=>"1712", "name"=>"VSG Ettlingen/Rüppurr 2",
	                        "team" => 'h2', "tname"=>'Herren 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"h3" => Array("id"=>"1762", "name"=>"VSG Ettlingen/Rüppurr 3",
	                        "team" => 'h3', "tname"=>'Herren 3',
							'liga_kurz'=>'BL', 'liga_lang' => 'Bezirksliga'),
				"d1" => Array("id"=>"1696", "name"=>"TuS Rüppurr",
	                        "team" => 'd1', "tname"=>'Damen 1',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"d2" => Array("id"=>"1700", "name"=>"TuS Rüppurr 2",
	                        "team" => 'd2', "tname"=>'Damen 2',
							'liga_kurz'=>'BL', 'liga_lang' => 'Bezirksliga'),
				"d3" => Array("id"=>"1710", "name"=>"TuS Rüppurr 3",
	                        "team" => 'd3', "tname"=>'Damen 3',
							'liga_kurz'=>'KL', 'liga_lang' => 'Kreisliga'),
			 ),
			"2012" => Array(
				"h1" => Array("id"=>"1785", "name"=>"VSG Ettlingen/Rüppurr",
	                        "team" => 'h1', "tname"=>'Herren 1',
							'liga_kurz'=>'VL', 'liga_lang' => 'Verbandsliga'),
				"h2" => Array("id"=>"1838", "name"=>"VSG Ettl./Rüppurr 2",
	                        "team" => 'h2', "tname"=>'Herren 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"h3" => Array("id"=>"1835", "name"=>"VSG Ettl./Rüppurr 3",
	                        "team" => 'h3', "tname"=>'Herren 3',
							'liga_kurz'=>'BL', 'liga_lang' => 'Bezirksliga'),
				"d1" => Array("id"=>"1837", "name"=>"VSG Ettl./Rüppurr 1",
	                        "team" => 'd1', "tname"=>'Damen 1',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"d2" => Array("id"=>"1837", "name"=>"VSG Ettl./Rüppurr 2",
	                        "team" => 'd2', "tname"=>'Damen 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"d3" => Array("id"=>"1836", "name"=>"VSG Ettl./Rüppurr 3",
	                        "team" => 'd3', "tname"=>'Damen 3',
							'liga_kurz'=>'KL', 'liga_lang' => 'Kreisliga'),
			 ),
			"2013" => Array(
				"h1" => Array("id"=>"0", "name"=>"VSG Ettlingen/Rüppurr",
	                        "team" => 'h1', "tname"=>'Herren 1',
							'liga_kurz'=>'OL', 'liga_lang' => 'Oberliga'),
				"h2" => Array("id"=>"31", "name"=>"VSG Ettlingen/Rüppurr2",
	                        "team" => 'h2', "tname"=>'Herren 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"h3" => Array("id"=>"34", "name"=>"VSG Ettlingen/Rüppurr3",
	                        "team" => 'h3', "tname"=>'Herren 3',
							'liga_kurz'=>'BL', 'liga_lang' => 'Bezirksliga'),
				"d1" => Array("id"=>"09", "name"=>"VSG Ettlingen/Rüppurr",
	                        "team" => 'd1', "tname"=>'Damen 1',
							'liga_kurz'=>'VL', 'liga_lang' => 'Verbandsliga'),
				"d2" => Array("id"=>"11", "name"=>"VSG Ettlingen/Rüppurr2",
	                        "team" => 'd2', "tname"=>'Damen 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"d3" => Array("id"=>"18", "name"=>"VSG Ettlingen/Rüppurr3",
	                        "team" => 'd3', "tname"=>'Damen 3',
							'liga_kurz'=>'BK', 'liga_lang' => 'Bezirksklasse'),
			 ),
			"2014" => Array(
				"h1" => Array("id"=>"08", "name"=>"VSG Ettlingen/Rüppurr",
	                        "team" => 'h1', "tname"=>'Herren 1',
							'liga_kurz'=>'VL', 'liga_lang' => 'Verbandsliga'),
				"h2" => Array("id"=>"31", "name"=>"VSG Ettlingen/Rüppurr2",
	                        "team" => 'h2', "tname"=>'Herren 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
				"h3" => Array("id"=>"36", "name"=>"VSG Ettlingen/Rüppurr3",
	                        "team" => 'h3', "tname"=>'Herren 3',
							'liga_kurz'=>'BK', 'liga_lang' => 'Bezirksklasse'),
				"d1" => Array("id"=>"01", "name"=>"VSG Ettlingen/Rüppurr",
	                        "team" => 'd1', "tname"=>'Damen 1',
							'liga_kurz'=>'OL', 'liga_lang' => 'Oberliga'),
				"d2" => Array("id"=>"11", "name"=>"VSG Ettlingen/Rüppurr2",
	                        "team" => 'd2', "tname"=>'Damen 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga'),
			 ),
	        );
        $this->replace = array(
            # Zu lange Namen in der kleinen Matchvorschau kürzen
            "TuS Durmersheim II" 		=> "Durmersheim II",
            "TuS Durmersheim III"	 	=> "Durmersheim III",
            "TuS Durmersheim" 			=> "Durmersheim",
            "TuS Durmersheim 2" 		=> "Durmersheim 2",
            "TuS Durmersheim 3" 		=> "Durmersheim 3",
            "VSG Kleinsteinbach" 		=> "Kleinsteinbach",
            "VSG Kleinsteinbach 2" 		=> "Kleinsteinbach 2",
            "VSG Ettl/Rüppurr I" 		=> "Ettl/Rüppurr I",
			"VSG Ettlingen/Rüppurr" 	=> "Ettl/Rüppurr",
			"VSG Ettl./Rüppurr 1" 		=> "Ettl/Rüppurr 1",
            "VSG Ettl/Rüppurr II" 		=> "Ettl/Rüppurr II",
			"VSG Ettlingen/Rüppurr 2" 	=> "Ettl/Rüppurr 2",
			"VSG Ettl./Rüppurr 2" 		=> "Ettl/Rüppurr 2",
            "VSG Ettlingen/Rüppurr III" => "Ettl/Rüppurr III",
			"VSG Ettl./Rüppurr 3" 		=> "Ettl/Rüppurr 3",
			"VSG Ettlingen/Rüppurr 3" 	=> "Ettl/Rüppurr 3",
            "VSG Ettlingen/Rüppurr IV" 	=> "Ettl/Rüppurr IV",
            "SV Ka/Beiertheim III" 		=> "Beiertheim III",
            "TSV Mühlh./Würm" 			=> "Mühlh./Würm",
			"TSV Mühlhausen/Würm" 		=> "Mühlh./Würm",
			"SR Yburg Steinbach" 		=> "SR Yb. Steinb.",
			"SG Sinsheim/Waibstadt" 	=> "Sinsheim/Waib",
			"HTV/USC Heidelberg 2"		=> "Heidelberg 2",
			"VSG Mannheim DJK/MVC 2"	=> "Mannheim 2",
			"VSG Mannheim DJK/MVC 3"	=> "Mannheim 3",
            );
	    $this->kuerzel = array(
	    	# Kürzel für die kleine Tabelle
			
			# VL Herren
			"SG Sinsheim/Waibstadt"		=> "Sinsh/Waib",
			"HTV/USC Heidelberg 2"		=> "Heidelberg 2",
			"TSG Weinheim"				=> "Weinheim",
			"VSG Mannh. DJK/MVC 2"		=> "Mannheim 2",
			"VSG Mannh. DJK/MVC 1"		=> "Mannheim 1",
			"VSG Mannheim DJK/MVC 2"	=> "Mannheim 2",
			"SSC Karlsruhe"				=> "SSC Ka",
			"VSG Ettlingen/Rüppurr"		=> "Rüppurr",
			"VSG Ettlingen/Rüppurr 1"	=> "Rüppurr 1",
			"VSG Mannheim DJK/MVC 3"	=> "Mannheim 3",
			"TV Flehingen"				=> "Flehingen",
			"TG Ötigheim"				=> "Ötigheim",
			"TV Eberbach"				=> "Eberbach",
			"TSV  Handschuhsheim"		=> "Handschuh",

	    	
	        # LL Herren
	        "VSG Ettl/Rüppurr II"   	=> "Rüpp II",
			"VSG Ettlingen/Rüppurr 2" 	=> "Rüppurr 2",
			"VSG Ettl./Rüppurr 2" 		=> "Rüppurr 2",
			"TS Durlach 2"				=> "Durlach 2",
			"SVK Beiertheim"			=> "Beiertheim",
	        "SSC Karlsruhe I"       	=> "SSC I",
	        "TuS Durmersheim III"   	=> "Durm III",
	        "TuS Durmersheim 3"   		=> "Durmersh 3",
	        "VSG Ettl/Rüppurr I"    	=> "Rüpp I",
	        "FT Forchheim"          	=> "Forchheim",
	        "SSC Karlsruhe II"     		=> "SSC II",
	        "SSC Karlsruhe 2"			=> "SSC Ka 2",
	        "TS Durlach II"         	=> "Durl II",
	        "TV Neuweier"           	=> "Neuweier",
	        "TV Ersingen"           	=> "Ersingen",
	        "TSV Ubstadt"           	=> "Ubstadt",
	        "VSG Kleinsteinbach"    	=> "KlSteinbach",
		 	"TV Eppingen"				=> "Eppingen",
			"TSG Blankenloch 2"			=> "Blankenlo 2",

	        
	        # BL Herren
	        "VSG Ettlingen/Rüppurr III" => "Rüp III",
			"VSG Ettlingen/Rüppurr 3" 	=> "Rüppurr 3",
			"VSG Ettl./Rüppurr 3" 		=> "Rüppurr 3",
	        "VC Kuppenheim"         	=> "Kuppenh",
	        "SC Wettersbach"        	=> "Wettersbach",
	        "TSV Weingarten"        	=> "Weingarten",
	        "TV Forst"              	=> "Forst",
	        "TV Pforzheim II"       	=> "Pforzh II",
			"TV Neuweier 2"				=> "Neuweier 2",
			"TS Durlach 3"				=> "Durlach 3",
			"Rastatter TV"				=> "Rastatter TV",
			"KIT Sport-Club 2010"		=> "KIT SC",
			"SSC Karlsruhe 3"			=> "SSC Ka 3",
		 	"TSG Blankenloch 3"			=> "Blankenlo 3",
		 	"VC Kammachi Bühl"			=> "VC Bühl",


	        
	        # LL Damen
	        "DJK Bruchsal"          	=> "Bruchsal",
			"VSG Ettl./Rüppurr 1" 		=> "Rüppurr 1",
			"VSG Ettl./Rüppurr 2" 		=> "Rüppurr 2",
	        "SSC Karlsruhe II"      	=> "SSC II",
	        "SG Du/Wett"           		=> "Du/Wett",
			"VSG DuWEtt"				=> "DuWEtt",
	        "TuS Rüppurr"           	=> "Rüppurr",
	        "VSG Kleinsteinbach"  	 	=> "KlSteinbach",
	        "1. Ispringer VV"       	=> "Ispringen",
	        "TB Pforzheim"          	=> "Pforzheim",
	        "TV Brötzingen II"      	=> "Brötz II",
	        "TV Brötzingen 2"      		=> "Brötzing 2",
	        "SR Yb. Steinbach"      	=> "Yb. Steinb.",
			"SR Yburg Steinbach"		=> "Yb. Steinb.",
			"TSG Wiesloch 2"			=> "Wiesloch 2",
			"TV Bretten"				=> "Bretten",
			"SVK Beiertheim 3"			=> "Beierthe 3",
		 	"VC Eppingen"				=> "Eppingen",
	        
	        # BL Damen
	       	"SV Ka/Beiertheim III" 		=> "Beierth III",
	        "TV Bretten II"         	=> "Bretten II",
			"TV Bretten 2"				=> "Bretten 2",
	        "TSV Mühlh./Würm"       	=> "Mü/Würm",
	        "TuS Rüppurr II"        	=> "Rüpp II",
			"TuS Rüppurr 2"				=> "Rüppurr 2",
	        "TV Hochstetten"        	=> "Hochstet",
	        "TSG Bruchsal"          	=> "Bruchsal",
	        "TV Au/Rhein"           	=> "Au/Rhein",
	        "DJK Bruchsal II"       	=> "Bruchs II",
	        "SG DuWett II"          	=> "DuWett II",
			"TSV Knittlingen"			=> "Knittlingen",
			"TuS Durmersheim"			=> "Durmersheim",
			"VSG Kleinsteinbach 2"		=> "KlSteinbach 2",
			"TSV Mühlhausen/Würm"		=> "Mühlh/Würm",
			"TV Au am Rhein"			=> "Au am Rhein",
			
			# KL Damen
	        "Rastatter TV 2"			=> "Rastatt 2",
			"Post Südstadt Karlsruhe" 	=> "Post Südstadt",
			"VC Kuppenheim 1"			=> "Kuppenheim",
			"TuS Durmersheim 2"			=> "Durmersh 2",
			"SR Yburg Steinbach 2"		=> "Yb Steinb 2",
			"TuS Rüppurr 3"				=> "Rüppurr 3",
			"VSG Ettl./Rüppurr 3" 		=> "Rüppurr 3",
			"TS Steinmauern"			=> "Steinmauern",
			"VSG Kleinsteinbach 3"		=> "KlSteinb 3",
			"VSG Kleinsteinb. 3"		=> "KlSteinb 3",
			"VC Kuppenheim 2"			=> "Kuppenhe 2",
			"VC Königsbach"				=> "Königsbach",
			"TG Ötigheim 2"				=> "Ötigheim 2",
	        );
	    
	    # Datenstruktur für jede Saison aufbauen
		foreach($this->all_seasons as $s) {
			$this->xml_data[$s] = array("table", "games");
			foreach($this->teams[$s] as $k=>$t) {
			 $this->xml_data[$s]["table"][$t["id"]] = false;
			 $this->xml_data[$s]["games"][$k] = false;
			}
		}
	    
    }
    /**
     * return some info
     */
    function getInfo(){
        return array(
            'author' => 'Benjamin Förster',
            'email'  => 'dw@benni.dfoerster.de',
            'date'   => '2010-11-23',
            'name'   => 'nvvConnect Plugin',
            'desc'   => 'Zeigt NVV-Ergebnisse und Tabelle an',
            # 'url'    => 'http://dokuwiki.org/plugin:info',
        );
    }

    /**
     * What kind of syntax are we?
     */
    function getType(){
        return 'substition';
    }

    /**
     * What about paragraphs?
     */
    function getPType(){
        return 'block';
    }

    /**
     * Where to sort in?
     */
    function getSort(){
        return 155;
    }


    /**
     * Connect pattern to lexer
     */
    function connectTo($mode) {
        foreach($this->matches as $k=>$m) {
            $this->Lexer->addSpecialPattern('{'.$k.':.*?}',$mode,'plugin_nvvConnect');
        }
    }


    /**
     * Handle the match
     */
    function handle($match, $state, $pos, &$handler) {
        foreach($this->matches as $mt => $fkt) {
         if(substr($match, 0, strlen($mt)+2) == '{'.$mt.':') {
          # Inhalt des Treffers
          $m = substr($match, strlen($mt)+2, -1);
          # Treffer-Tag
          $funktion = $fkt;
          break;
         }
        }
        return array($funktion, $m);
    }

    /**
     * Create output
     */
    function render($format, &$renderer, $data) {
        
        if($format == 'xhtml'){
            
            # Tag-Methode aufrufen
            $fkt = $data[0];
            $tm = $data[1];
			
			if(strpos($tm, '|') !== false) {
				list($tm, $season) = explode('|', $tm);
			}
			else {
				$season = $this->season;
			}
            if($team = $this->_getTeam($tm, $season)) {
                $this->{$fkt}($renderer, $team, $season);
            }
            else {
                $renderer->doc .= '<p class="nvvFehler">Fehler: Team "' . 
                                    $tm . '" nicht vorhanden.</p>';
            }
            return true;
        }
        return false;
    }
	 
    function _getTeam($id, $season=false) {
    	# prüft Existenz des Teams und gibt das Team-Array aus
		
		# bestimmte Saison?
		if($season == false) {
			$season = $this->season;
		}
		
		# Daten aller Teams laden
        $teams = $this->teams[$season];
		
		# gibt es das Team mit ID $id?
	    if(isset($teams[strtolower($id)])) {
	        return $teams[strtolower($id)];
	    }
	    else {
	        return false; # Exception schmeißen?
	    }
    }
	 
    function _getTable($team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
        # Holt die Tabelle für $team, falls sie noch nicht 
        # geholt wurde, und verarbeitet sie.
        
	    $data = &$this->xml_data[$season]["table"][$team["id"]];
	    if($data == false) {
	     $data = array();
	     try {
	      $xmlstr = $this->_holeDatei('tabelle_'.$team["id"].'.xml', $season);
	      $xml = new SimpleXMLElement($xmlstr);
	      foreach($xml->platz as $platz)
          {
           $v = get_object_vars($platz);
           $data[$v["nr"]] = $v;
          }
	     }
	     catch(Exception $e) {
	      return false;
	     }
	    }
        return $data;
    }
	 
    function _getGames($team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
        # Holt die Spiele für $team, falls sie noch nicht 
        # geholt wurden, und verarbeitet sie.
        
	    $data = &$this->xml_data[$season]["games"][$team["team"]];
	    if($data == false) {
	     $data = array();
	     try {
	      $xmlstr = $this->_holeDatei('spiele_'.$team["id"].'.xml', $season);
	      $xml = new SimpleXMLElement($xmlstr);
	      
	     $data = array(
	        "alle" => array(),
	        "vorschau" => array(),
	        "gespielt" => array(),
	        );
	     
         foreach($xml->spiel as $sp)
         {
            $v = get_object_vars($sp);
            
            if($v["teamheim"] == $team["name"]){
		        $v["modus"] = "heim";
		        $v["gegner"] = $v["teamgast"];
	        } elseif($v["teamgast"] == $team["name"]) {
		        $v["modus"]="gast";
		        $v["gegner"] = $v["teamheim"];
	        } else {
	            continue;
	        }
	        
	        $datum = strtotime($v["datum"].', '.$v["uhrzeit"]);
	        
	        $gruppe = (''==$v["ergebnis"])?'vorschau':'gespielt';
	        
	        $halle = $v["halle"];
	        
	        foreach(array("alle", $gruppe) as $g) {
	         $data[$g][$datum]["spiele"][] = $v;
	         $data[$g][$datum]["datum"] = $datum;
	         $data[$g][$datum]["halle"] = $halle;
	        }
          }
	     }
	     catch(Exception $e) {
	      return false;
	     }
	    }
        return $data;
	 }
	 
	 function _printTabellenplatzKurz(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Gibt für $team den Tabellenplatz aus. Bsp.:
	    # H1 (LL): Platz 5/9
	    
	    if($tabelle = $this->_getTable($team, $season))
	    {
	      foreach($tabelle as $p)
          {
           if($p["teamname"]==$team["name"]){
           $renderer->doc .= strtoupper($team["team"]) . 
                        ' ('.$team["liga_kurz"].'): Platz '.$p["nr"] . 
                        '/' . count($tabelle).'<br />';
           break;
          }
         }
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Verbindungsfehler</p>';
	     return;
	    }
	 }
	 
	 function _printTabellenplaetze(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Gibt die Tabellenplätze aller unserer Teams aus. Bsp.:
	    # D1 (LL):	Platz 4/9
        # D2 (BL):	Platz 4/9
        # H1 (LL):	Platz 5/9
        # H2 (LL):	Platz 1/9
        # H3 (BL):	Platz 3/8
        # H4 (BK):	Platz 8/9
        
        $renderer->doc .= '<table class="nvvTabelleLeftmenu">'; 
            #'<tr><td>Team</td><td>Platz</td></tr>';
        foreach($this->teams[$season] as $t)
        {
         if($tabelle = $this->_getTable($t, $season)) {
          foreach($tabelle as $p)
          {
           if($p["teamname"]==$t["name"]){
		    if($p["spiele"] == "0") {
				$platz = "Sommerpause";
			}
			else {
				$platz = 'Platz '.$p["nr"] . '/' . count($tabelle);
			}
            $renderer->doc .= '<tr><td class="nvvTeam">' . strtoupper($t["team"]) . 
                        ' ('.$t["liga_kurz"].'):</td><td class="nvvPlatz">'.$platz.'</td></tr>';
            break;
           }
          }
         }
         //else {
         // $renderer->doc .= '<tr><td class="nvvTeam">' . strtoupper($t["team"]) . 
         //               ' ('.$t["liga_kurz"].'):</td><td class="nvvPlatz">0/0</td></tr>';
         //}
        }
        $renderer->doc .= '</table>';
	 }
	 
	 function _printTabellenplatz(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Gibt für $team den aktuellen Tabellenplatz aus. Bsp.:
	    # Platz 3 (10:6 Punkte)
	    
	    if($tabelle = $this->_getTable($team, $season))
	    {
	      foreach($tabelle as $p)
          {
           if($p["teamname"]==$team["name"]){
           $renderer->doc .= '<p class="nvvTabellenplatz">Platz '.$p["nr"].' (' .
                 $p["punkteplus"].':'.$p["punkteminus"] . ' Punkte)</p>';
                 # . ', ' . $v["setplus"].':'.$v["setminus"] . ' S.)</p>';
           break;
          }
         }
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Platz 0 (0:0 Punkte)</p>';
	     return;
	    }
	 }
	 
	 function _printTabelle(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
        $renderer->doc .= '<table class="nvvTabelle"><tr class="kopfzeile">' . 
            '<td>Platz</td>' . 
            '<td>Mannschaft</td>' . 
            '<td>Spiele</td>' . 
            '<td>Punkte</td>' . 
            '<td>S&auml;tze</td>' . 
            '<td>Ballpunkte</td></tr>';
	    try {
	     $tabelle = $this->_getTable($team, $season);
         
         // Teams auslesen
         $s = '';
          foreach($tabelle as $v)
          {
            #$v = get_object_vars($platz);
			
			$punkte = isset($v["punkteplus"])?$v["punkteplus"].':'.$v["punkteminus"]:$v["punkte"];
            
            if($v["teamname"] == $team["name"]){
            $s .= '<tr class="wir">';
            } else { $s .= "<tr>"; }
			
			$teamname = utf8_decode($v["teamname"])?utf8_decode($v["teamname"]):$v["teamname"];
            $teamname = htmlentities($teamname)?htmlentities($teamname):$teamname;
			
            $s .=  '<td>' . $v["nr"] . '</td>' . 
            '<td class="team">' . $teamname . '</td>' . 
            '<td>' . $v["spiele"] . '</td>' . 
            '<td>' . $punkte.'</td>' . 
            '<td>' . $v["setplus"] . ':' . $v["setminus"].'</td>' . 
            '<td>' . $v["ballpunkteplus"] . ':' . $v["ballpunkteminus"].'</td>' . 
            '</tr>';
          }
          $renderer->doc .= $s;

          $renderer->doc .= '</table>';
	    }
	    catch(Exception $e) {
          $renderer->doc .= '<tr><td colspan="5">Fehler beim Auswerten der NVV-Daten.</td></tr></table>';
          return;
         }
	 }
	 
	 function _printTabelleKlein(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Gibt eine kleine Tabelle für $team aus. Bsp.:
	    # 	Team	    Sp	Pkte
        # 1	Beierth III	8	16:	0
        # 2	Mü/Würm	    8	12:	4
        # 3	Bretten II	8	12:	4
        # 4	Rüpp II	    8	10:	6
        # 5	Hochstet	8	10:	6
        # 6	Bruchsal	8	6:	10
        # 7	Au/Rhein	8	4:	12
        # 8	Bruchs II	8	2:	14
        # 9	DuWett II	8	0:	16
        
        $renderer->doc .= '<table class="nvvTabelleKlein">' . 
            '<tr class="kopfzeile">' . 
            '<td class="platz"></td>' . 
            '<td class="team">Team</td>' . 
            '<td class="spiele">Sp</td>' . 
            '<td class="punkte">Pkte</td></tr>';
	    if($tabelle = $this->_getTable($team, $season))
	    {
         
         // Teams auslesen
         $s = '';
         foreach($tabelle as $v)
         {
          # sind wir es?
          if($v["teamname"] == $team["name"]){
          $s .= '<tr class="wir">';
          } else { $s .= "<tr>"; }
		  
			$teamname = $this->_kurzerTeamname($v["teamname"]);
			$teamname = utf8_decode($teamname)?utf8_decode($teamname):$teamname;
            $teamname = htmlentities($teamname)?htmlentities($teamname):$teamname;
          
          # Teamnamen durch kürzere ersetzen
          $s .=  '<td class="platz">'.$v["nr"].'</td>' . 
          '<td class="team">' . $teamname .'</td>' . 
          '<td class="spiele">' . $v["spiele"] . '</td>' . 
          '<td class="punkte">' . $v["punkte"] . '</td>' . 
          '</tr>';
         }
         $renderer->doc .= $s;
	    }
	    else {
          $renderer->doc .= '<tr><td colspan="5">Fehler beim Auswerten der NVV-Daten.</td></tr>';
        }

        $renderer->doc .= '</table>';
	 }
	 
	 
	 function _printGespielt(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Zeige die gespielten Spiele von $team. Bsp.:
	    # 14.11.2010, 10:00 Uhr (Heimspieltag)
        # VSG Ettlingen/Rüppurr III	- TV Forst	    3:2	(108:83)
        # VSG Ettlingen/Rüppurr III	- TSV Ubstadt	1:3	(84:95)
        
	    if($spiele = $this->_getGames($team, $season))
        {	        
	        $s = '';
	        if($spiele["gespielt"]) {
	            $s .= '<table class="nvvSpieleGespielt">';
	            foreach($spiele["gespielt"] as $t => $spieltag) {
	                $modus = false;
	                if(count($spieltag["spiele"]) == 2) {
	                    $modus = "heim";
	                }
	                $s .= $this->_printSpieltagTitelzeile(4, $t, $spieltag["halle"], $modus);
	                foreach($spieltag["spiele"] as $sp) {
	                    $s .= $this->_printSpieltagSpiel(4, $sp);
	                }
	            }
	            $s .= '</table>';
	        }
			
			# Spielplan noch nicht festgelegt?
	        elseif(!$spiele["vorschau"]) {
	            $s .= '<p class="keineSpiele">Spielplan noch nicht festgelegt.</p>';
			}
			else {
	            $s .= '<p class="keineSpiele">Noch keine Spiele gespielt.</p>';
	        }
            $renderer->doc .= $s;
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Konnte keine Verbindung zum NVV-Server herstellen.</p>';
	    }
     }
	 
	 function _printVorschau(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Zeige die künftigen Spiele von $team. Bsp.:
	    # 26.03.2011, 15:00 Uhr (Heimspieltag)
        # VSG Ettlingen/Rüppurr III	- VSG Kleinsteinbach
        # VSG Ettlingen/Rüppurr III	- TV Forst
        
	    if($spiele = $this->_getGames($team, $season))
        {	        
	        $s = '';
	        if($spiele["vorschau"]) {
	            $s .= '<table class="nvvSpieleVorschau">';
	            foreach($spiele["vorschau"] as $t => $spieltag) {
	                $modus = false;
	                if(count($spieltag["spiele"]) == 2) {
	                    $modus = "heim";
	                }
	                $s .= $this->_printSpieltagTitelzeile(2, $t, $spieltag["halle"], $modus);
	                foreach($spieltag["spiele"] as $sp) {
	                    $s .= $this->_printSpieltagSpiel(2, $sp);
	                }
	            }
	            $s .= '</table>';
	        }
			
			# Spielplan noch nicht festgelegt?
	        elseif(!$spiele["gespielt"]) {
	            $s .= '<p class="keineSpiele">Spielplan noch nicht festgelegt.</p>';
			}
	        else {
	            $s .= '<p class="keineSpiele">Saison beendet.</p>';
	        }
            $renderer->doc .= $s;
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Konnte keine Verbindung zum NVV-Server herstellen.</p>';
	     return;
	    }
	     
	 }
	 
	 function _printVorschauNext(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Zeige nächstes Spiel für $team. Bsp.:
	    # 23.01.2011, 11:00 Uhr
        # gg. VC Kuppenheim
        
	    if($spiele = $this->_getGames($team, $season))
        {	     
	        $s = '';
	        if($spiele["vorschau"]) {
	            $s .= '<p class="nvvNextSpieltag">';
	            ksort($spiele["vorschau"]);
	            $spieltag=reset($spiele["vorschau"]);
                if(count($spieltag["spiele"]) == 2) {
                    $modus = "heim";
                }
                $s .= '<span class="nvvNextDatum">'.date("d.m.Y, H:i", $spieltag["datum"]);
                $s .= ' Uhr</span><br />';
                $s .= 'gg. <span class="nvvNextTeam">';
                $s .= $this->_replace($spieltag["spiele"][0]["gegner"]).'</span>';
                $s .= ($modus == "heim")?'<br />und <span class="nvvNextTeam">' . 
                              $this->_replace($spieltag["spiele"][1]["gegner"]).'</span>' : '';
	            $s .= '</p>';
	        }
			
			# Spielplan noch nicht festgelegt?
	        elseif(!$spiele["gespielt"]) {
	            $s .= '<p class="keineSpiele">Spielplan noch nicht festgelegt.</p>';
			}
	        else {
	            $s .= '<p class="keineSpiele">Saison beendet.</p>';
	        }
            $renderer->doc .= $s;
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Verbindungsfehler</p>';
	     return;
	    }
	 }
	 
	 
	 function _printGespieltKlein(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Zeige die gespielten Spiele für $team in kleiner Liste an. Bsp.:
	    # 18.12.2010, 15:00 Uhr
	    # 2:3 TSV Ubstadt
	    # 
	    # 11.12.2010, 15:00 Uhr
	    # 3:2 TSV Weingarten
	    # 
	    # 20.11.2010, 15:00 Uhr
	    # 0:3 VSG Kl.steinbach
	    # 3:0 TV Pforzheim II
	    
	    if($spiele = $this->_getGames($team, $season))
        {	        
	        if($spiele["gespielt"]) {
	        $s = '';
	            $gespielt = $spiele["gespielt"];
	            krsort($gespielt);
	            foreach($gespielt as $t => $spieltag) {
	                $s .= '<p class="nvvGespieltKlein">';
	                $modus = false;
	                if(count($spieltag["spiele"]) == 2) {
	                    $modus = "heim";
	                }
	                $ergebnis = $spieltag["spiele"][0]["ergebnis"];
	                $ergebnis = ($modus=="heim")?$ergebnis:strrev($ergebnis);
	                $s .= '<span class="nvvGespieltKleinDatum">' . 
	                        date("d.m.Y, H:i", $spieltag["datum"]);
                    $s .= ' Uhr</span><br />';
                    $s .= $ergebnis;
                    $s .= ' <span class="nvvGespieltKleinGegner">';
                    $s .= $this->_replace($spieltag["spiele"][0]["gegner"]).'</span>';
                    $s .= ($modus == "heim")?'<br />' . 
                                $spieltag["spiele"][1]["ergebnis"] . 
                                ' <span class="nvvGespieltKleinGegner">' . 
                                $this->_replace($spieltag["spiele"][1]["gegner"]).'</span>' : '';
	                $s .= '</p>';
	            }
                $renderer->doc .= $s;
	        }
			
			# Spielplan noch nicht festgelegt?
	        elseif(!$spiele["vorschau"]) {
	            $s .= '<p class="keineSpiele">Spielplan noch nicht festgelegt.</p>';
			}
            else {
                $renderer->doc .= '<p class="keineSpiele">Noch keine Spiele.</p>';
            }
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Konnte keine Verbindung ' . 
	                        'zum NVV-Server herstellen.</p>';
	    }
	 }
	 
    function _printSpieltagTitelzeile($cols, $date, $halle, $modus) {
	    # Zeige die Titelzeile für einen Spieltag
	    
	    return '<tr class="spieltag">' . 
	        '<td colspan="'.$cols.'">' . 
	        '<span class="datum">'.date("d.m.Y, H:i", $date).' Uhr</span>' . 
	        # ' (<span class="ort">' . 
	        # ( $modus == "heim" ? 'Heimspieltag' : 'Ausw&auml;rtsspieltag' ) . 
	        ( $halle?' ('.$halle.')':'' ) . # '</span>)'
	        '</td></tr>';
	}
	  
    function _printSpieltagSpiel($cols, $v) {
	    # Zeige ein Spiel des Spieltags
	    
	    # Satzpunkte
	    $saetze = array();
	    for($i=1;$i<=5;$i++) {
	    	if(strlen($v["satz".$i]) > 3) { 
		    	$saetze[$i] .= $v["satz".$i];
		    }
	    }
		
		$teamname = $v["teamheim"];
		$teamname = utf8_decode($teamname)?utf8_decode($teamname):$teamname;
		$teamname = htmlentities($teamname)?htmlentities($teamname):$teamname;
		$teamheim = $teamname;
		
		$teamname = $v["teamgast"];
		$teamname = utf8_decode($teamname)?utf8_decode($teamname):$teamname;
		$teamname = htmlentities($teamname)?htmlentities($teamname):$teamname;
		$teamgast = $teamname;
	    
	    $ergebnis = ( $cols == 4 ) ? 
	        '<td class="ergebnis">'.$v["ergebnis"].'</td>' . 
	        '<td class="punkte">('. implode($saetze, ", ") . ')</td>':'';
	    return '<tr class="spiel"><td class="'.($v["modus"]=="heim"?'wir':'gegner').'">' . 
	        str_replace(' ', '&nbsp;', $teamheim) . 
	        '</td><td class="' . ($v["modus"]=="gast"?'wir':'gegner').'">-&nbsp;' . 
	        str_replace(' ', '&nbsp;', $teamgast) . 
	        '</td>'.$ergebnis.'</tr>';
    }
	  
    function _replace($teamname) {
        # Ersetze zu lange Namen für die Matchvorschau
        if( isset($this->replace[$teamname]) ) {
            return $this->replace[$teamname];
        }
        else {
            return $teamname;
        }
    }

    function _kurzerTeamname($teamname) {
        # ersetze den Teamnamen durch sein Kürzel (kleine Tabelle)
        
        if(isset($this->kuerzel[$teamname])) {
            return $this->kuerzel[$teamname];
        }
        else {
            return substr($teamname, 0, 10);
        }
    }
    
    
    function _holeDatei($dateiname, $season) {
		# bestimmte Saison?
		if($season == false) {
			$season = $this->season;
		}
		
		# Ort der Datei auf dem Server
    	$datei = 'lib/plugins/nvvConnect/cache/' . $season . '/' .$dateiname;
		
		# falls abgelaufene Saison: nicht mehr aktualisieren!
		if($season != $this->season) {
			$dateizeit = time(); # vorgeben, dass Datei topaktuell ist
		}
		elseif(is_file($datei)) {
			$dateizeit = filectime($datei); # echtes Änderungsdatum auslesen
		}
		else {
			$dateizeit = 0; # Datei muss eh heruntergeladen werden
		}
    	if( ( !is_file($datei) || ( time() - $dateizeit > ( date("N")<6?60*60*3:60*15 ) ) )
    		&& ( $neueDatei = file_get_contents(
					"http://www.volleyball-nordbaden.de/xml_export/".$dateiname
				) ) ) {
			# Mo-Fr: (date("N") = 1...5) => alle 3 Stunden aktualisieren
			# Sa/So: (date("N") = 6/7) => alle 15 Minuten aktualisieren
			
			$fp = fopen($datei, "w");
			fwrite($fp, $neueDatei);
			fclose($fp);
			return $neueDatei;
    	}
    	elseif( is_file($datei) ) {
    		return file_get_contents($datei);
    	}
    	else {
    		throw new Exception();
    	}			
    }
}
