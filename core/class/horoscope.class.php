<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class horoscope extends eqLogic {
    
	 public static $_widgetPossibility = array('custom' => true);
	
	public function Signe($Signe1) {
	log::add('horoscope', 'debug', 'Début de la fonction de calcul de l horoscope');
	$Signe=$Signe1;
//$Signe=$_GET["Signe"];
$Lien="http://www.asiaflash.com/horoscope/rss_horojour_$Signe.xml";
$Phrase="";
$Total=0;
$Fin=0;
$pos1=0;
$xmlData = file_get_contents($Lien);
str_replace('rss','xml',$xmlData );
$xml = new SimpleXMLElement($xmlData);
$Phrase=$xml->channel->item->description;

$pos1 = stripos($Phrase, "oeil</b><br>");
$pos1=$pos1+12;
$Total=strlen($Phrase);
$Fin=$Total-$pos1;
$Phrase=substr($Phrase,$pos1,$Fin);

$pos1 = stripos($Phrase, "<br><br>");
$Total=strlen($Phrase);

$Phrase=substr($Phrase,1,$pos1-1);

//echo $Phrase;
log::add('horoscope', 'debug', 'Phrase générée : '.$Phrase);

				//mise à jour base de donnée Jeedom
				$cmd = $this->getCmd(null, 'horoscopeDuJour');
                if (is_object($cmd)) {
                    // $cmd->setCollectDate($date);
                    $cmd->event($Phrase);
					$ID=$this->getId();
					$name=$this->getName();
                    log::add('horoscope', 'debug', 'Phrase stockée en BDD pour l ID : '.$ID.' et le nom : '.$name.' : ' . $Phrase);
                }

//$mi_horoscope->updateJeedom();
//log::add('horoscope', 'debug', 'Apprès updatejeedom '.$Phrase);

}
	
	
	
	/*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */

    /*
     * Fonction exécutée automatiquement toutes les minutes par Jeedom
       */
	  public static function cron() {
		$today = date('H');
		$frequence = config::byKey('frequence', 'horoscope');
		log::add('horoscope', 'debug', '--------------------------DEBUT HOROSCOPE CRON MINUTE-------------------------------------------');
		log::add('horoscope', 'debug', 'Fréquence : "'.$frequence.'" , heure actuelle : '.$today);
		
		if ($frequence == '1min') {
		log::add('horoscope', 'debug', 'Avant Lecture de chaque équipement');
		 foreach (eqLogic::byType('horoscope', true) as $mi_horoscope) {   
		log::add('horoscope', 'debug', 'Après chaque élément');

		   $ID=$mi_horoscope->getId();
		   $name=$mi_horoscope->getName();
		   log::add('horoscope', 'debug', 'Récupération de l ID : '.$ID.' et du nom de la personne : '.$name);
		   $Signe2=$mi_horoscope->getConfiguration('Signe');
		   log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : '.$Signe2);
		  
		$Signe1=$Signe2;
		if ($Signe1=='Taureau') { $Signe1='taureau'; } //ok
		if ($Signe1=='Bélier') { $Signe1='belier'; } // ok
		if ($Signe1=='Poissons') { $Signe1='poissons'; } //ok
		if ($Signe1=='Vierge') { $Signe1='vierge'; } //ok
		if ($Signe1=='Capricorne') { $Signe1='capricorne'; } //ok
		if ($Signe1=='Scorpion') { $Signe1='scorpion'; } // ok
		
		if ($Signe1=='Sagittaire') { $Signe1='sagittaire'; } // ok
		if ($Signe1=='Verseau') { $Signe1='verseau'; } //nok
		if ($Signe1=='Cancer') { $Signe1='cancer'; } // ok
		if ($Signe1=='Balance') { $Signe1='balance'; } // ok
		if ($Signe1=='Gémeaux') { $Signe1='gemeaux'; } //ok
		if ($Signe1=='Lion') { $Signe1='lion'; } // ok
		
		log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : "'.$Signe2.'", Envoi du signe : "'.$Signe1.'"');
			//Procédure de calcul de l horoscope
		   $mi_horoscope->Signe($Signe1);
		   $mi_horoscope->refreshWidget();
		   
		   }
		 }//FIN VERIF FREQUENCE
      } 
    
     // Fonction exécutée automatiquement toutes les heures par Jeedom
      public static function cronHourly() {
		$today = date('H');
		$frequence = config::byKey('frequence', 'horoscope');
		log::add('horoscope', 'debug', '--------------------------DEBUT HOROSCOPE CRON HEURE-------------------------------------------');
		log::add('horoscope', 'debug', 'Fréquence : "'.$frequence.'" , heure Actuelle : '.$today);
		
		
		if (($frequence == '1h') ||  (($today == '00') && ($frequence == 'minuit')) ||  (($today == '05') && ($frequence == '5h'))  ){
		log::add('horoscope', 'debug', 'Avant Lecture de chaque équipement');
		 foreach (eqLogic::byType('horoscope', true) as $mi_horoscope) {   
		log::add('horoscope', 'debug', 'Après chaque élément');

		   $ID=$mi_horoscope->getId();
		   $name=$mi_horoscope->getName();
		   log::add('horoscope', 'debug', 'Récupération de l ID : '.$ID.' et du nom de la personne : '.$name);
		   $Signe2=$mi_horoscope->getConfiguration('Signe');
		   log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : '.$Signe2);
		  
		$Signe1=$Signe2;
		if ($Signe1=='Taureau') { $Signe1='taureau'; } //ok
		if ($Signe1=='Bélier') { $Signe1='belier'; } // ok
		if ($Signe1=='Poissons') { $Signe1='poissons'; } //ok
		if ($Signe1=='Vierge') { $Signe1='vierge'; } //ok
		if ($Signe1=='Capricorne') { $Signe1='capricorne'; } //ok
		if ($Signe1=='Scorpion') { $Signe1='scorpion'; } // ok
		
		if ($Signe1=='Sagittaire') { $Signe1='sagittaire'; } // ok
		if ($Signe1=='Verseau') { $Signe1='verseau'; } //nok
		if ($Signe1=='Cancer') { $Signe1='cancer'; } // ok
		if ($Signe1=='Balance') { $Signe1='balance'; } // ok
		if ($Signe1=='Gémeaux') { $Signe1='gemeaux'; } //ok
		if ($Signe1=='Lion') { $Signe1='lion'; } // ok
		
		log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : "'.$Signe2.'", Envoi du signe : "'.$Signe1.'"');
			//Procédure de calcul de l horoscope
		   $mi_horoscope->Signe($Signe1);
		   $mi_horoscope->refreshWidget();
		   }
		 }//FIN VERIF FREQUENCE
      }
     
    /*
     * Fonction exécutée automatiquement tous les jours par Jeedom
      public static function cronDayly() {
      }
     */
    /*     * *********************Méthodes d'instance************************* */
	 public function updateJeedom() {
	log::add('horoscope', 'debug', 'updateJeedom  param='.$Phrase );
		/*
        // store into Jeedom DB
        if ($Phrase=='' ) {
            log::add('MiFlora', 'error', 'Toutes les mesures a 0, erreur de connection Mi Flora');
        } else {
            //if ($temperature > 100) {
                log::add('MiFlora', 'error', 'Temperature >100 erreur de connection bluetooth');
            //} else {
                
                //
                $cmd = $this->getCmd(null, $Phrase);
                if (is_object($cmd)) {
                    $cmd->event($Phrase);
                    log::add('horoscope', 'debug', $Phrase );
                }
				//
           // }
        }
    
	*/
	return 'ok';
	}
		
    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
        
    }

    public function postSave() {
        	log::add('horoscope', 'debug', 'Après chaque SAVE');
			$cmdlogic = horoscopeCmd::byEqLogicIdAndLogicalId($this->getId(), 'horoscopeDuJour');
        if (!is_object($cmdlogic)) {
		log::add('horoscope', 'debug', 'L équipement n pas a été trouvé dans SAVE donc pas de mise à jour :');
		
		}
		else {
			log::add('horoscope', 'debug', 'L équipement a été trouvé dans SAVE donc mise à jour :');
		
			
		log::add('horoscope', 'debug', 'Après chaque SAVE');

		   $ID=$this->getId();
		   $name=$this->getName();
		   log::add('horoscope', 'debug', 'Récupération de l ID : '.$ID.' et du nom de la personne : '.$name);
		   $Signe2=$this->getConfiguration('Signe');
		   log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : '.$Signe2);
		  
		$Signe1=$Signe2;
		if ($Signe1=='Taureau') { $Signe1='taureau'; } //ok
		if ($Signe1=='Bélier') { $Signe1='belier'; } // ok
		if ($Signe1=='Poissons') { $Signe1='poissons'; } //ok
		if ($Signe1=='Vierge') { $Signe1='vierge'; } //ok
		if ($Signe1=='Capricorne') { $Signe1='capricorne'; } //ok
		if ($Signe1=='Scorpion') { $Signe1='scorpion'; } // ok
		
		if ($Signe1=='Sagittaire') { $Signe1='sagittaire'; } // ok
		if ($Signe1=='Verseau') { $Signe1='verseau'; } //nok
		if ($Signe1=='Cancer') { $Signe1='cancer'; } // ok
		if ($Signe1=='Balance') { $Signe1='balance'; } // ok
		if ($Signe1=='Gémeaux') { $Signe1='gemeaux'; } //ok
		if ($Signe1=='Lion') { $Signe1='lion'; } // ok
		
		log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : "'.$Signe2.'", Envoi du signe : "'.$Signe1.'"');
			//Procédure de calcul de l horoscope
		   $this->Signe($Signe1);
		    if ($Signe1 == '') {
			log::add('horoscope', 'debug', 'Deuxième essai car variable récupérer vide : "'.$Signe2.'", Envoi du signe : "'.$Signe1.'"');
		   $this->Signe($Signe1);
		   
		   }
		    if ($Signe1 == '') {
			log::add('horoscope', 'debug', 'Troisième et dernier essai car variable récupérer vide : "'.$Signe2.'", Envoi du signe : "'.$Signe1.'"');
		   $this->Signe($Signe1);
		   
		   }
		   $this->refreshWidget();
		   
		  	
		
		
				$cmdlogic = horoscopeCmd::byEqLogicIdAndLogicalId($this->getId(), 'signe');
        if (!is_object($cmdlogic)) {
		log::add('horoscope', 'debug', 'L équipement (Signe) n a pas a été trouvé dans SAVE donc création :');
		    $horoscopeCmd = new horoscopeCmd();
            $horoscopeCmd->setName(__('signe', __FILE__));
            $horoscopeCmd->setEqLogic_id($this->id);
            $horoscopeCmd->setLogicalId('signe');
            $horoscopeCmd->setConfiguration('data', 'signe');
            $horoscopeCmd->setEqType('horoscope');
            $horoscopeCmd->setType('info');
            $horoscopeCmd->setSubType('string');
            //$horoscopeCmd->setUnite('');
            $horoscopeCmd->setIsHistorized(0);
            $horoscopeCmd->save();
			//$this->$Signe2;
			log::add('horoscope', 'debug', 'L équipement (Signe) a été créé dans SAVE donc mise à jour :');
		   //$ID=$this->getId();
		   //$name=$this->getName();
		   log::add('horoscope', 'debug', 'Signe - Récupération de l ID : '.$ID.' et du nom de la personne : '.$name);
		   $Signe2=$this->getConfiguration('Signe');
		   log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : '.$Signe2);
			$cmd = $this->getCmd(null, 'signe');
                if (is_object($cmd)) {
		   $cmd->event($Signe2);
			}	
		   $this->refreshWidget();  
			
		
		}
		else {
			log::add('horoscope', 'debug', 'L équipement (Signe) a été trouvé dans SAVE donc mise à jour :');
		   $ID=$this->getId();
		   $name=$this->getName();
		   log::add('horoscope', 'debug', 'Récupération de l ID : '.$ID.' et du nom de la personne : '.$name);
		   $Signe2=$this->getConfiguration('Signe');
		   log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : '.$Signe2);
		   //mise à jour base de donnée Jeedom
				$cmd = $this->getCmd(null, 'signe');
                if (is_object($cmd)) {
		   $cmd->event($Signe2);
			}		   
		   $this->refreshWidget();  
				   
		}
		}
    }

    public function preUpdate() {
        
    }

    public function postUpdate()
    {
        
		$cmdlogic = horoscopeCmd::byEqLogicIdAndLogicalId($this->getId(), 'horoscopeDuJour');
        if (!is_object($cmdlogic)) {
            $horoscopeCmd = new horoscopeCmd();
            $horoscopeCmd->setName(__('horoscopeDuJour', __FILE__));
            $horoscopeCmd->setEqLogic_id($this->id);
            $horoscopeCmd->setLogicalId('horoscopeDuJour');
            $horoscopeCmd->setConfiguration('data', 'horoscopeDuJour');
            $horoscopeCmd->setEqType('horoscope');
            $horoscopeCmd->setType('info');
            $horoscopeCmd->setSubType('string');
            //$horoscopeCmd->setUnite('');
            $horoscopeCmd->setIsHistorized(0);
            $horoscopeCmd->save();
			
		log::add('horoscope', 'debug', 'Après chaque UPDATE');

		   $ID=$this->getId();
		   $name=$this->getName();
		   log::add('horoscope', 'debug', 'Récupération de l ID : '.$ID.' et du nom de la personne : '.$name);
		   $Signe2=$this->getConfiguration('Signe');
		   log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : '.$Signe2);
		  
		$Signe1=$Signe2;
		if ($Signe1=='Taureau') { $Signe1='taureau'; } //ok
		if ($Signe1=='Bélier') { $Signe1='belier'; } // ok
		if ($Signe1=='Poissons') { $Signe1='poissons'; } //ok
		if ($Signe1=='Vierge') { $Signe1='vierge'; } //ok
		if ($Signe1=='Capricorne') { $Signe1='capricorne'; } //ok
		if ($Signe1=='Scorpion') { $Signe1='scorpion'; } // ok
		
		if ($Signe1=='Sagittaire') { $Signe1='sagittaire'; } // ok
		if ($Signe1=='Verseau') { $Signe1='verseau'; } //nok
		if ($Signe1=='Cancer') { $Signe1='cancer'; } // ok
		if ($Signe1=='Balance') { $Signe1='balance'; } // ok
		if ($Signe1=='Gémeaux') { $Signe1='gemeaux'; } //ok
		if ($Signe1=='Lion') { $Signe1='lion'; } // ok
		
		log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : "'.$Signe2.'", Envoi du signe : "'.$Signe1.'"');
			//Procédure de calcul de l horoscope
		   $this->Signe($Signe1);
		    if ($Signe1 == '') {
			log::add('horoscope', 'debug', 'Deuxième essai car variable récupérer vide : "'.$Signe2.'", Envoi du signe : "'.$Signe1.'"');
		   $this->Signe($Signe1);
		   
		   }
		    if ($Signe1 == '') {
			log::add('horoscope', 'debug', 'Troisième et dernier essai car variable récupérer vide : "'.$Signe2.'", Envoi du signe : "'.$Signe1.'"');
		   $this->Signe($Signe1);
		   
		   }
		   
		   }

		  	
        
		
		//Signe
		
			$cmdlogic = horoscopeCmd::byEqLogicIdAndLogicalId($this->getId(), 'signe');
        if (!is_object($cmdlogic)) {
		log::add('horoscope', 'debug', 'L équipement (Signe) n a pas a été trouvé dans SAVE donc création :');
		    $horoscopeCmd = new horoscopeCmd();
            $horoscopeCmd->setName(__('signe', __FILE__));
            $horoscopeCmd->setEqLogic_id($this->id);
            $horoscopeCmd->setLogicalId('signe');
            $horoscopeCmd->setConfiguration('data', 'signe');
            $horoscopeCmd->setEqType('horoscope');
            $horoscopeCmd->setType('info');
            $horoscopeCmd->setSubType('string');
            //$horoscopeCmd->setUnite('');
            $horoscopeCmd->setIsHistorized(0);
            $horoscopeCmd->save();
			$this->$Signe2;
			log::add('horoscope', 'debug', 'L équipement (Signe) a été créé dans SAVE donc mise à jour :');
		   $ID=$this->getId();
		   $name=$this->getName();
		   log::add('horoscope', 'debug', 'Signe - Récupération de l ID : '.$ID.' et du nom de la personne : '.$name);
		   $Signe2=$this->getConfiguration('Signe');
		   log::add('horoscope', 'debug', 'Signe du Zodiaque enregistré : '.$Signe2);
$cmd = $this->getCmd(null, 'signe');
                if (is_object($cmd)) {
		   $cmd->event($Signe2);
		   }
		   }
		   		   $this->refreshWidget();
	}
		

    public function preRemove() {
        
    }

    public function postRemove() {
        
    }

	

	
	
    /*
     * Non obligatoire mais permet de modifier l'affichage du widget si vous en avez besoin
      public function toHtml($_version = 'dashboard') {

      }
     */

	 public function toHtml($_version = 'dashboard') {
        $replace = $this->preToHtml($_version);
        if (!is_array($replace)) {
            return $replace;
        }
        $version = jeedom::versionAlias($_version);
        if ($this->getDisplay('hideOn' . $version) == 1) {
            return '';
        }
        foreach ($this->getCmd('info') as $cmd) {
            $replace['#' . $cmd->getLogicalId() . '_id#'] = $cmd->getId();
            $replace['#' . $cmd->getLogicalId() . '#'] = $cmd->execCmd();
            $replace['#' . $cmd->getLogicalId() . '_collect#'] = $cmd->getCollectDate();
            if ($cmd->getIsHistorized() == 1) {
                $replace['#' . $cmd->getLogicalId() . '_history#'] = 'history cursor';
            }
        }

        log::add('horoscope','debug', $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'horoscope', 'horoscope'))));

        return $this->postToHtml($_version, template_replace($replace, getTemplate('core', $version, 'horoscope', 'horoscope')));
    }
	 
	 
    /*     * **********************Getteur Setteur*************************** */
}

class horoscopeCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    /*
     * Non obligatoire permet de demander de ne pas supprimer les commandes même si elles ne sont pas dans la nouvelle configuration de l'équipement envoyé en JS
      public function dontRemoveCmd() {
      return true;
      }
     */

    public function execute($_options = array()) {
        
    }

    /*     * **********************Getteur Setteur*************************** */
}

?>
