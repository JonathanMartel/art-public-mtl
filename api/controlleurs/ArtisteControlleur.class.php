<?php
/**
 * Controlleur de la ressource Artiste
 * 
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2016-11-16
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 */
 
 /*
 * 
 *
 */

 
 
class ArtisteControlleur extends Controlleur 
{
	
	// GET : 
	// 		/artiste/ - Liste des oeuvres
	// 		/artiste/{id}/ - Une oeuvre
	// 		/artiste/?q=nom,prenom,etc&valeur=chaineDeRecherche
	
	public function getAction(Requete $requete)
	{
		$res = array();
		$page= "artistes";
		//  var_dump($requete->url_elements[1]);

		if(isset($requete->url_elements[1]) && is_numeric($requete->url_elements[1]))	// Normalement l'id de l'artiste 
		{
            $id_artiste = (int)$requete->url_elements[1];
			$res = $this->getArtiste($id_artiste);
			$page = "artiste";
			// die;
            
        } 
        else 	// Liste des oeuvres
        {
			$res = $this->getListeArtiste();
			
        }
		
		if(isset($_GET['json']))
		{
			echo json_encode($res);	
		}
		else
		{
				
			
			$oVue = new Vue();
			$oVue->afficheEntete($page);
			if(isset($requete->url_elements[1]) && is_numeric($requete->url_elements[1]))
			{
				//var_dump($res);
				$oVue->afficheArtiste($res);	
			}
			else
			{
				$oVue->afficheArtistes($res);
			}	
			
			$oVue->affichePied();
			
		}
			
		
		
	}
	
	
	
	
		
	protected function getArtiste($id_artiste)
	{
		$oArtiste= new Artiste();
        // on regarde si l'artiste entré existe
		$aArtiste = $oArtiste->getArtiste($id_artiste);
		$oOeuvre = new Oeuvre();
		$aArtiste['oeuvres'] = $oOeuvre->getOeuvresParArtiste($id_artiste);
		
		return $aArtiste;
	}
	
	protected function getListeArtiste()
	{
		
		$oArtiste= new Artiste();
		$aArtiste = $oArtiste->getListe();
		
		return $aArtiste;
	}
	
	
	
}
?>