<?php
/**
 * nvvConnect Plugin: Zeigt NVV-Ergebnisse und Tabelle an
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Benjamin Förster <dw@benni.dfoerster.de>
 * @author	Sebastian Knapp <sebastian.knapp@bossmail.de>
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
		$this->all_seasons = Array("2015");
		
		# Aktuelle Saison
		$this->season = "2015";

		# API Key 
		$this->apiKey = "be80c43d-4bd5-49ff-8ba6-46ba1565c462";
		
        $this->matches = array(
            # Tag auf entsprechende Methode verweisen
            'nvvTabelle' => '_printRankings',
            'nvvTabelleKlein' => '_printRankingsSmall',
            'nvvGespielt' => '_printLastGames',
            'nvvGespieltKlein' => '_printLastGamesSmall',
            'nvvVorschau' => '_printNextGames',
            'nvvVorschauNext' => '_printNextGame',
            'nvvPlatz' => '_printRankingPlace',
            'nvvPlaetze' => '_printRankingPlaces',
            'nvvPlatzKurz' => '_printRankingPlaceShort',
            );
            
        $this->teams = array(
            # unsere Teams
			"2015" => Array(
				"h1" => Array("id"=>"4260811", "name"=>"VSG Ettlingen/Rüppurr",
	                        "team" => 'h1', "tname"=>'Herren 1',
							'liga_kurz'=>'VL', 'liga_lang' => 'Verbandsliga',"liga_id"=>"4241522"),
				"h2" => Array("id"=>"4260850", "name"=>"VSG Ettlingen/Rüppurr 2",
	                        "team" => 'h2', "tname"=>'Herren 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga',"liga_id"=>"4241513"),
				"h3" => Array("id"=>"4260886", "name"=>"VSG Ettlingen/Rüppurr 3",
	                        "team" => 'h3', "tname"=>'Herren 3',
							'liga_kurz'=>'BL', 'liga_lang' => 'Bezirksliga',"liga_id"=>"4241504"),
				"d1" => Array("id"=>"4241321", "name"=>"VSG Ettlingen/Rüppurr",
	                        "team" => 'd1', "tname"=>'Damen 1',
							'liga_kurz'=>'OL', 'liga_lang' => 'Oberliga', "liga_id"=>"4241287"),
				"d2" => Array("id"=>"4253074", "name"=>"VSG Ettlingen/Rüppurr 2",
	                        "team" => 'd2', "tname"=>'Damen 2',
							'liga_kurz'=>'LL', 'liga_lang' => 'Landesliga',"liga_id"=>"4241507"),
				"d3" => Array("id"=>"4254771", "name"=> "VSG Ettlingen/Rüppurr 3",
				"team" => 'd3', "tname"=>'Damen 3',
							'liga_kurz'=>'KL', 'liga_lang' => 'Kreisliga',"liga_id"=>"4241462"),
		),
	        );
        $this->replace = array(
            # Zu lange Namen in der kleinen Matchvorschau kürzen
           	"TuS Durmersheim II" 				=> "Durmersheim II",
           	"TuS Durmersheim III"	 			=> "Durmersheim III",
           	"TuS Durmersheim" 					=> "Durmersheim",
           	"TuS Durmersheim 2" 				=> "Durmersheim 2",
           	"TuS Durmersheim 3" 				=> "Durmersheim 3",
            "VSG Kleinsteinbach" 				=> "Kleinsteinbach",
           	"VSG Kleinsteinbach 2" 				=> "Kleinsteinbach 2",
			"VSG Kleinsteinbach 3"				=> "Kleinsteinbach 3",
			"SR Yburg Steinbach"				=> "Yburg Steinbach",
           	"VSG Ettl/Rüppurr I" 				=> "Ettl/Rüppurr I",
			"VSG Ettlingen/Rüppurr" 			=> "Ettl/Rüppurr",
			"VSG Ettl./Rüppurr 1" 				=> "Ettl/Rüppurr 1",
           	"VSG Ettl/Rüppurr II" 				=> "Ettl/Rüppurr II",
			"VSG Ettlingen/Rüppurr 2" 			=> "Ettl/Rüppurr 2",
			"VSG Ettl./Rüppurr 2" 				=> "Ettl/Rüppurr 2",
            "VSG Ettlingen/Rüppurr III" 		=> "Ettl/Rüppurr III",
			"VSG Ettl./Rüppurr 3" 				=> "Ettl/Rüppurr 3",
			"VSG Ettlingen/Rüppurr 3" 			=> "Ettl/Rüppurr 3",
        	"VSG Ettlingen/Rüppurr IV" 			=> "Ettl/Rüppurr IV",
			"SV KA-Beiertheim 2"				=> "Beiertheim 2",
           	"SV Ka/Beiertheim III" 				=> "Beiertheim III",
           	"TSV Mühlh./Würm" 					=> "Mühlh./Würm",
			"TSV Mühlhausen/Würm" 				=> "Mühlh./Würm",
			"SG Sinsheim/Waibstadt/Helmstadt" 	=> "Sins/Waib/Helm",
			"HTV/USC Heidelberg 2"				=> "Heidelberg 2",
			"VSG Mannheim DJK/MVC 2"			=> "Mannheim 2",
			"VSG Mannheim DJK/MVC 3"			=> "Mannheim 3",
			"VSG Ubstadt/Forst"					=> "Ubstadt/Forst",
			"VSG Kleinsteinbach"				=> "Kleinsteinbach",
			"VC KAMMACHI Bühl"					=> "KAMMACHI Bühl",
			"SG Ersingen-Ispringen-Pforzheim 2"	=> "Ers-Ispr-Pf 2",
            );
	    $this->kuerzel = array(
	    	# Kürzel für die kleine Tabelle
			
			# VL Herren
			"SG Sinsheim/Waibstadt/Helmstadt"	=> "Sins/Waib/Helm",
			"SG HTV/USC Heidelberg 2"			=> "Heidelberg 2",
			"TSG Weinheim"						=> "Weinheim",
			"VSG Mannh. DJK/MVC 2"				=> "Mannheim 2",
			"VSG Mannh. DJK/MVC 1"				=> "Mannheim 1",
			"VSG Mannheim DJK/MVC 2"			=> "Mannheim 2",
			"VSG Ubstadt/Forst"					=> "Ubstadt/Forst",
			"SSC Karlsruhe"						=> "SSC Ka",
			"VSG Ettlingen/Rüppurr"				=> "Rüppurr",
			"VSG Ettlingen/Rüppurr 1"			=> "Rüppurr 1",
			"VSG Mannheim DJK/MVC 3"			=> "Mannheim 3",
			"TV Flehingen"						=> "Flehingen",
			"TG Ötigheim"						=> "Ötigheim",
			"TV Eberbach"						=> "Eberbach",
			"TSV Handschuhsheim"				=> "Handschuh",
			"TS Durlach"						=> "Durlach",

	        # LL Herren
	        "VSG Ettl/Rüppurr II"   			=> "Rüpp II",
			"VSG Ettlingen/Rüppurr 2" 			=> "Rüppurr 2",
			"VSG Ettl./Rüppurr 2" 				=> "Rüppurr 2",
			"TS Durlach 2"						=> "Durlach 2",
			"SVK Beiertheim"					=> "Beiertheim",
	        "SSC Karlsruhe I"       			=> "SSC I",
	        "TuS Durmersheim III"   			=> "Durm III",
	        "TuS Durmersheim 3"   				=> "Durmersh 3",
	        "VSG Ettl/Rüppurr I"    			=> "Rüpp I",
	        "FT Forchheim"          			=> "Forchheim",
	        "SSC Karlsruhe II"     				=> "SSC II",
	        "SSC Karlsruhe 2"					=> "SSC Ka 2",
	        "TS Durlach II"         			=> "Durl II",
	        "TV Neuweier"           			=> "Neuweier",
	        "TV Ersingen"           			=> "Ersingen",
	        "TSV Ubstadt"           			=> "Ubstadt",
	        "VSG Kleinsteinbach"    			=> "KlSteinbach",
			"TV Eppingen"						=> "Eppingen",
			"TSG Blankenloch 2"					=> "Blankenlo 2",
			"SC Wettersbach"					=> "Wettersbach",
			"TSG Wiesloch"						=> "Wiesloch",
			"AVC St. Leon-Rot"					=> "Leon-Rot",
	        
	        # BL Herren
	        "VSG Ettlingen/Rüppurr III" 		=> "Rüp III",
			"VSG Ettlingen/Rüppurr 3" 			=> "Rüppurr 3",
			"VSG Ettl./Rüppurr 3" 				=> "Rüppurr 3",
	        "VC Kuppenheim"         			=> "Kuppenh",
	        "TSV Weingarten"        			=> "Weingarten",
	        "TV Forst"              			=> "Forst",
	        "TV Pforzheim II"       			=> "Pforzh II",
			"TV Neuweier"						=> "Neuweier",
			"TS Durlach 3"						=> "Durlach 3",
			"Rastatter TV"						=> "Rastatter TV",
			"KIT Sport-Club 2010"				=> "KIT SC",
			"SSC Karlsruhe 4"					=> "SSC Ka 4",
			"TSG Blankenloch 3"					=> "Blankenlo 3",
			"VC KAMMACHI Bühl"					=> "VC Bühl",
			"TV Flehingen 2"					=> "Fleh 2",
			"TV Öschelbronn"					=> "Öschelbr",
	        "VSG Kleinsteinbach 2"    			=> "KlSteinb 2",
	        
	        # OL Damen
	        "FT 1844 Freiburg"					=> "Freiburg",
	        "TB Bad Dürrheim"					=> "Dürrheim",
	        "USC Freiburg"						=> "Freiburg",
	        "SV KA-Beiertheim 2"				=> "Beierth 2",
	        
	        # LL Damen
	        "DJK Bruchsal"          			=> "Bruchsal",
			"VSG Ettl./Rüppurr 1" 				=> "Rüppurr 1",
			"VSG Ettl./Rüppurr 2" 				=> "Rüppurr 2",
	        "SSC Karlsruhe II"      			=> "SSC II",
	        "SG Du/Wett"           				=> "Du/Wett",
			"VSG DuWEtt"						=> "DuWEtt",
	        "TuS Rüppurr"           			=> "Rüppurr",
	        "1. Ispringer VV"       			=> "Ispringen",
	        "TB Pforzheim"          			=> "Pforzheim",
	        "TV Brötzingen II"      			=> "Brötz II",
	        "TV Brötzingen 2"      				=> "Brötzing 2",
	        "SR Yb. Steinbach"      			=> "Yb. Steinb.",
			"SR Yburg Steinbach"				=> "Yb. Steinb.",
			"TSG Wiesloch 2"					=> "Wiesloch 2",
			"TV Bretten"						=> "Bretten",
			"SVK Beiertheim 3"					=> "Beierthe 3",
			"VC Eppingen"						=> "Eppingen",
			"VC Kuppenheim"						=> "Kuppenh",
			"TSV Mühlhausen/Würm"				=> "Mühlh/Würm",
			"TV Waibstadt"						=> "Waibstadt",
			"KIT Sport-Club 2010"				=> "KIT",
			"SV Sinsheim 2"						=> "Sinsh 2",
	        
	        # BL Damen
	       	"SV Ka/Beiertheim III" 				=> "Beierth III",
	        "TV Bretten II"         			=> "Bretten II",
			"TV Bretten 2"						=> "Bretten 2",
	        "TSV Mühlh./Würm"       			=> "Mü/Würm",
	        "TuS Rüppurr II"        			=> "Rüpp II",
			"TuS Rüppurr 2"						=> "Rüppurr 2",
	        "TV Hochstetten"        			=> "Hochstet",
	        "TSG Bruchsal"          			=> "Bruchsal",
	        "TV Au/Rhein"           			=> "Au/Rhein",
	        "DJK Bruchsal II"       			=> "Bruchs II",
	        "SG DuWett II"          			=> "DuWett II",
			"TSV Knittlingen"					=> "Knittlingen",
			"TuS Durmersheim"					=> "Durmersheim",
			"TSV Mühlhausen/Würm"				=> "Mühlh/Würm",
			"TV Au am Rhein"					=> "Au am Rhein",
			"Rastatter TV"						=> "Rastatt",
			
			# KL Damen
	        "Rastatter TV 2"					=> "Rastatt 2",
			"Post Südstadt Karlsruhe" 			=> "Post Südstadt",
			"VC Kuppenheim 1"					=> "Kuppenheim",
			"TuS Durmersheim 2"					=> "Durmersh 2",
			"SR Yburg Steinbach 2"				=> "Yb Steinb 2",
			"TuS Rüppurr 3"						=> "Rüppurr 3",
			"VSG Ettl./Rüppurr 3" 				=> "Rüppurr 3",
			"TS Steinmauern"					=> "Steinmauern",
			"VSG Kleinsteinbach 3"				=> "KlSteinb 3",
			"VSG Kleinsteinb. 3"				=> "KlSteinb 3",
			"VC Kuppenheim 2"					=> "Kuppenhe 2",
			"VC Königsbach"						=> "Königsbach",
			"TG Ötigheim 2"						=> "Ötigheim 2",
			"VC Neureut 2"						=> "Neureut 2",
			"SG Ersingen-Ispringen-Pforzheim 2"	=> "Ers-Isp-Pf 2"
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
            'author' => 'Benjamin Förster & Sebastian Knapp',
            'email'  => 'dw@benni.dfoerster.de, sebastian.knapp@bossmail.de',
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
	 
    function _getRankingsSorted($team, $season=false) {
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
	      $xmlstr = $this->_getXmlString('rankings','matchSeriesId='.$team["liga_id"], $season);
	      $xml = new SimpleXMLElement($xmlstr);
	      foreach($xml->ranking as $ra)
          {
           $ranking = get_object_vars($ra);
           $ranking["team"] = get_object_vars($ranking["team"]);
           $data[$ranking["place"]] = $ranking;
          }
	     }
	     catch(Exception $e) {
	      return false;
	     }
	    }
        return $data;
    }
    
	 # {{"all{matches}"},{"next{matches}"},{"last{matches}"}}
    function _getMatches($team, $season=false) {
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
	      $xmlstr = $this->_getXmlString('matches','teamId='.$team["id"], $season);
	      $xml = new SimpleXMLElement($xmlstr);
	      
	     $data = array(
	        "all" => array(),
	        "next" => array(),
	        "last" => array(),
	        );
	     
         foreach($xml->match as $ma)
         {
            $match = get_object_vars($ma);
            
            $match["location"] = get_object_vars($match["location"]);
            $match["results"] = get_object_vars($match["results"]);
            $match["results"]["sets"] = get_object_vars($match["results"]["sets"]);
	        
	        $date = strtotime($match["date"].', '.$match["time"]);
	        
	        $group = ($date<time())?"last":"next";
	        
	        $location = htmlentities($match["location"]["name"],ENT_XHTML)?htmlentities($match["location"]["name"],ENT_XHTML):$match["location"]["name"];
	        
	        # define oponent and match type (home/false)
	        foreach($match["team"] as $teamNew) {
				$teamNew = get_object_vars($teamNew);
				if($teamNew["name"] == $team["name"] ){
					# wir
					if($teamNew["number"] == 1) {
						# Heimspiel
						$mode = "home";
					 }else {
						 $mode = false;
					 }
				 } else {
					 # opponent
					 $opponent = $teamNew["name"];
					 $opponent = htmlentities($opponent,ENT_XHTML)?htmlentities($opponent,ENT_XHTML):$opponent;
				 }
			 }
			 $match["mode"] = $mode;
			 $match["opponent"] = $opponent;
	        
	        foreach(array("all", $group) as $g) {
	         $data[$g][$date]["matches"][] = $match;
	         $data[$g][$date]["date"] = $date;
	         $data[$g][$date]["location"] = $location;
	        }
          }
	     }
	     catch(Exception $e) {
	      return false;
	     }
	    }
        return $data;
	 }
	 
	 function _printRankingPlaceShort(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Gibt für $team den Tabellenplatz aus. Bsp.:
	    # H1 (LL): Platz 5/9
	    
	    if($rankings = $this->_getRankingsSorted($team, $season))
	    {
	      foreach($rankings as $ranking)
          {
           if($ranking["team"]["name"]==$team["name"]){
			   # unser team --> gebe Position aus
           $renderer->doc .= strtoupper($team["team"]) . 
                        ' ('.$team["liga_kurz"].'): Platz '.$ranking["place"] . 
                        '/' . count($rankings).'<br />';
           break;
          }
         }
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Verbindungsfehler</p>';
	     return;
	    }
	 }
	 
	 function _printRankingPlaces(&$renderer, $team, $season=false) {
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
        foreach($this->teams[$season] as $teamNow)
        {
         if($rankings = $this->_getRankingsSorted($teamNow, $season)) {
          foreach($rankings as $ranking)
          {
           if($ranking["team"]["name"]==$teamNow["name"]){
		    if($ranking["matchesPlayed"] == "0") {
				$place = "Sommerpause";
			}
			else {
				$place = 'Platz '.$ranking["place"] . '/' . count($rankings);
			}
            $renderer->doc .= '<tr><td class="nvvTeam">' . strtoupper($teamNow["team"]) . 
                        ' ('.$teamNow["liga_kurz"].'):</td><td class="nvvPlatz">'.$place.'</td></tr>';
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
	 
	 function _printRankingPlace(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Gibt für $team den aktuellen Tabellenplatz aus. Bsp.:
	    # Platz 3 (10:6 Punkte)
	    
	    if($rankings = $this->_getRankingsSorted($team, $season))
	    {
	      foreach($rankings as $ranking)
          {
           if($ranking["team"]["name"]==$team["name"]){
           $renderer->doc .= '<p class="nvvTabellenplatz">Platz '.$ranking["place"].' (' .
                 $ranking["setWinScore"].':'.$ranking["setLoseScore"] . ' Punkte)</p>';
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
	 
	 function _printRankings(&$renderer, $team, $season=false) {
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
	     $rankings = $this->_getRankingsSorted($team, $season);
         
         // Teams auslesen
         $s = '';
          foreach($rankings as $ranking)
          {
            #$v = get_object_vars($platz);
            
            if($ranking["team"]["name"] == $team["name"]){
            $s .= '<tr class="wir">';
            } else { $s .= "<tr>"; }
            
            $teamname = $ranking["team"]["name"];
            $teamname = htmlentities($teamname,ENT_XHTML)?htmlentities($teamname,ENT_XHTML):$teamname;
			
            $s .=  '<td>' . $ranking["place"] . '</td>' . 
            '<td class="team">' . $teamname . '</td>' . 
            '<td>' . $ranking["matchesPlayed"] . '</td>' . 
            '<td>' . $ranking["points"].'</td>' . 
            '<td>' . $ranking["setPoints"].'</td>' . 
            '<td>' . $ranking["ballPoints"].'</td>' . 
            '</tr>';
          }
          $renderer->doc .= $s;

          $renderer->doc .= '</table>';
	    }
	    catch(Exception $e) {
          $renderer->doc .= '<tr><td colspan="5">Fehler beim Auswerten der "volleyball-baden.de"-Daten.</td></tr></table>';
          return;
         }
	 }
	 
	 function _printRankingsSmall(&$renderer, $team, $season=false) {
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
	    if($rankings = $this->_getRankingsSorted($team, $season))
	    {
         
         // Teams auslesen
         $s = '';
         foreach($rankings as $ranking)
         {
          # sind wir es?
          if($ranking["team"]["name"] == $team["name"]){
          $s .= '<tr class="wir">';
          } else { $s .= "<tr>"; }
		  
			$teamname = $this->_shortTeamname($ranking["team"]["name"]);
            $teamname = htmlentities($teamname,ENT_XHTML)?htmlentities($teamname,ENT_XHTML):$teamname;
          
          # Teamnamen durch kürzere ersetzen
          $s .=  '<td class="platz">'.$ranking["place"].'</td>' . 
          '<td class="team">' . $teamname .'</td>' . 
          '<td class="spiele">' . $ranking["matchesPlayed"] . '</td>' . 
          '<td class="punkte">' . $ranking["points"] . '</td>' . 
          '</tr>';
         }
         $renderer->doc .= $s;
	    }
	    else {
          $renderer->doc .= '<tr><td colspan="5">Fehler beim Auswerten der "volleyball-baden.de"-Daten.</td></tr>';
        }

        $renderer->doc .= '</table>';
	 }
	 
	 
	 function _printLastGames(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Zeige die gespielten Spiele von $team. Bsp.:
	    # 14.11.2010, 10:00 Uhr (Heimspieltag)
        # VSG Ettlingen/Rüppurr III	- TV Forst	    3:2	(108:83)
        # VSG Ettlingen/Rüppurr III	- TSV Ubstadt	1:3	(84:95)
        
	    if($matches = $this->_getMatches($team, $season))
        {	        
	        $s = '';
	        if($matches["last"]) {
	            $s .= '<table class="nvvSpieleGespielt">';
	            foreach($matches["last"] as $t => $matchDay) {
	                $s .= $this->_printSpieltagTitelzeile(4, $t, $matchDay["location"], $matchDay["matches"][0]["mode"]);
	                foreach($matchDay["matches"] as $match) {
	                    $s .= $this->_printMatchdayGame(4, $match);
	                }
	            }
	            $s .= '</table>';
	        }
			
			# Spielplan noch nicht festgelegt?
	        elseif(!$matches["next"]) {
	            $s .= '<p class="keineSpiele">Spielplan noch nicht festgelegt.</p>';
			}
			else {
	            $s .= '<p class="keineSpiele">Noch keine Spiele gespielt.</p>';
	        }
            $renderer->doc .= $s;
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Konnte keine Verbindung zum "volleyball-baden.de"-Server herstellen.</p>';
	    }
     }
	 
	 function _printNextGames(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Zeige die künftigen Spiele von $team. Bsp.:
	    # 26.03.2011, 15:00 Uhr (Heimspieltag)
        # VSG Ettlingen/Rüppurr III	- VSG Kleinsteinbach
        # VSG Ettlingen/Rüppurr III	- TV Forst
        
	    if($matches = $this->_getMatches($team, $season))
        {	        
	        $s = '';
	        if($matches["next"]) {
	            $s .= '<table class="nvvSpieleVorschau">';
	            foreach($matches["next"] as $t => $matchDay) {
	                $s .= $this->_printSpieltagTitelzeile(2, $t, $matchDay["location"], $matchDay["matches"][0]["mode"]);
	                foreach($matchDay["matches"] as $match) {
	                    $s .= $this->_printMatchdayGame(2, $match);
	                }
	            }
	            $s .= '</table>';
	        }
			
			# Spielplan noch nicht festgelegt?
	        elseif(!$matches["last"]) {
	            $s .= '<p class="keineSpiele">Spielplan noch nicht festgelegt.</p>';
			}
	        else {
	            $s .= '<p class="keineSpiele">Saison beendet.</p>';
	        }
            $renderer->doc .= $s;
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Konnte keine Verbindung zum "volleyball-baden.de"-Server herstellen.</p>';
	     return;
	    }
	 }
	 
	 function _printNextGame(&$renderer, $team, $season=false) {
		# bestimme Saison?
		if($season == false) {
			$season = $this->season;
		}
		
	    # Zeige nächstes Spiel für $team. Bsp.:
	    # 23.01.2011, 11:00 Uhr
        # gg. VC Kuppenheim
        
	    if($matches = $this->_getMatches($team, $season))
        {	     
	        $s = '';
	        if($matches["next"]) {
	            $s .= '<p class="nvvNextSpieltag">';
	            $matchDay=reset($matches["next"]);
                if(count($matchDay["matches"]) == 2) {
                    $mode = "home";
                }
                $s .= '<span class="nvvNextDatum">'.date("d.m.Y, H:i", $matchDay["date"]);
                $s .= ' Uhr</span><br />';
                $s .= 'gg. <span class="nvvNextTeam">';
                $s .= $this->_replace($matchDay["matches"][0]["opponent"]).'</span>';
                $s .= ($mode == "home")?'<br />und <span class="nvvNextTeam">' . 
                              $this->_replace($matchDay["matches"][1]["opponent"]).'</span>' : '';
	            $s .= '</p>';
	        }
			
			# Spielplan noch nicht festgelegt?
	        elseif(!$matches["last"]) {
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
	 
	 #go on...
	 function _printLastGamesSmall(&$renderer, $team, $season=false) {
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
	    
	    if($matches = $this->_getMatches($team, $season))
        {	        
	        if($matches["last"]) {
	        $s = '';
	            $last = $matches["last"];
	            krsort($last);
	            foreach($last as $t => $matchDay) {
	                $s .= '<p class="nvvGespieltKlein">';
	                $result = $matchDay["matches"][0]["results"]["setPoints"];
	                $result = ($matchDay["matches"][0]["mode"]=="home")?$result:strrev($result);
	                $s .= '<span class="nvvGespieltKleinDatum">' . 
	                        date("d.m.Y, H:i", $matchDay["date"]);
                    $s .= ' Uhr</span><br />';
                    $s .= $result;
                    $s .= ' <span class="nvvGespieltKleinGegner">';
                    $s .= $this->_replace($matchDay["matches"][0]["opponent"]).'</span>';
                    $s .= (count($matchDay["matches"])==2)?'<br />' . 
                                $matchDay["matches"][1]["results"]["setPoints"] . 
                                ' <span class="nvvGespieltKleinGegner">' . 
                                $this->_replace($matchDay["matches"][1]["opponent"]).'</span>' : '';
	                $s .= '</p>';
	            }
                $renderer->doc .= $s;
	        }
			
			# Spielplan noch nicht festgelegt?
	        elseif(!$matches["next"]) {
	            $s .= '<p class="keineSpiele">Spielplan noch nicht festgelegt.</p>';
			}
            else {
                $renderer->doc .= '<p class="keineSpiele">Noch keine Spiele.</p>';
            }
	    }
	    else {
	     $renderer->doc .= '<p class="nvvFehler">Konnte keine Verbindung ' . 
	                        'zum "volleyball-baden.de"-Server herstellen.</p>';
	    }
	 }
	 
    function _printSpieltagTitelzeile($cols, $date, $location, $mode) {
	    # Zeige die Titelzeile für einen Spieltag
	    
	    return '<tr class="spieltag">' . 
	        '<td colspan="'.$cols.'">' . 
	        '<span class="datum">'.date("d.m.Y, H:i", $date).' Uhr</span>' . 
	        # ' (<span class="ort">' . 
	        # ( $mode == "home" ? 'Heimspieltag' : 'Ausw&auml;rtsspieltag' ) . 
	        ( $location?' ('.$location.')':'' ) . # '</span>)'
	        '</td></tr>';
	}
	  
    function _printMatchdayGame($cols, $match) {
	    # Zeige ein Spiel des Spieltags
	    
	    # Satzpunkte
	    $setPoints = array();
	    foreach($match["results"]["sets"]["set"] as $set) {
			$set = get_object_vars($set);
	    	$setPoints[$set["number"]]=$set["points"];
	    }
		
		$teamHome = "";
		$teamGuest = "";
		foreach($match["team"] as $team) {
			$team = get_object_vars($team);
			$teamname = $team["name"];
			$teamname = htmlentities($teamname,ENT_XHTML)?htmlentities($teamname,ENT_XHTML):$teamname;
			if($team["number"]==1) {
				#heim team
				$teamHome = $teamname;
			} else {
				#gast team
				$teamGuest = $teamname;
			}
		}
	    
	    $result = ( $cols == 4 ) ? 
	        '<td class="ergebnis">'.$match["results"]["setPoints"].'</td>' . 
	        '<td class="punkte">((' . implode($setPoints, "),(") . '))</td>':'';
	    return '<tr class="spiel"><td class="'.($match["mode"]=="home"?'wir':'gegner').'">' . 
	        str_replace(' ', '&nbsp;', $teamHome) . 
	        '</td><td class="' . ($v["modus"]=="gast"?'wir':'gegner').'">-&nbsp;' . 
	        str_replace(' ', '&nbsp;', $teamGuest) . 
	        '</td>'.$result.'</tr>';
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

    function _shortTeamname($teamname) {
        # ersetze den Teamnamen durch sein Kürzel (kleine Tabelle)
        
        if(isset($this->kuerzel[$teamname])) {
            return $this->kuerzel[$teamname];
        }
        else {
            return substr($teamname, 0, 10);
        }
    }
    
    
	# typ = {matches, rankings}
	# parameter example: teamId=12345
    function _getXmlString($typ, $parameter , $season) {
		# bestimmte Saison?
		if($season == false) {
			$season = $this->season;
		}
		
		# Ort der Datei auf dem Server
    	$file = 'lib/plugins/nvvConnect/cache/' . $season . '/' . $typ . '/' . explode("=", $parameter, 2)[1] . '.xml';
		
		# falls abgelaufene Saison: nicht mehr aktualisieren!
		if($season != $this->season) {
			$file_time = time(); # vorgeben, dass Datei topaktuell ist
		}
		elseif(is_file($file)) {
			$file_time = filectime($file); # echtes Änderungsdatum auslesen
		}
		else {
			$file_time = 0; # Datei muss eh heruntergeladen werden
		}
    	if( ( !is_file($file) || ( time() - $file_time > ( date("N")<6?60*60*3:60*15 ) ) )
    		&& ( $newFile = file_get_contents("https://www.volleyball-baden.de/xml/" . $typ . ".xhtml?apiKey=" . $this->apiKey . "&" . $parameter) ) ) {
			# Mo-Fr: (date("N") = 1...5) => alle 3 Stunden aktualisieren
			# Sa/So: (date("N") = 6/7) => alle 15 Minuten aktualisieren
			
			$fp = fopen($file, "w");
			fwrite($fp, $newFile);
			fclose($fp);
			return $newFile;
    	}
    	elseif( is_file($file) ) {
    		return file_get_contents($file);
    	}
    	else {
    		throw new Exception();
    	}			
    }
}
