<?php
/**
 * Class Oeuvre
 * 
 * @author Jonathan Martel
 * @version 1.0
 * @update 2014-09-11
 * @license Creative Commons BY-NC 3.0 (Licence Creative Commons Attribution - Pas d’utilisation commerciale 3.0 non transposé)
 * @license http://creativecommons.org/licenses/by-nc/3.0/deed.fr
 * 
 * 
 * 
 */
class Oeuvre extends Modele {	
     
	const TABLE_OEUVRE = "oeuvre";
	const TABLE_LIAISON_ARTISTE_OEUVRE = "artiste_oeuvre";
	const TABLE_OEUVRE_DONNEES_EXTERNES = "apm__oeuvre_donnees_externes";
	const TABLE_LIAISON_OEUVRE_CATEGORIE = "categorie_oeuvre";
	const TABLE_CATEGORIE = "categorie";
	
	/**
	 * Retourne la liste des oeuvres
	 * @access public
	 * @return Array
	 * @TODO Modifier le query afin de tenir compte des oeuvres à plusieurs artistes.
	 */
	public function getListe() 
	{
        
		$res = Array();
		$query = "	SELECT * FROM ". self::TABLE_OEUVRE ." Oeu 
					inner join ". self::TABLE_LIAISON_ARTISTE_OEUVRE ." O_A ON Oeu.id_oeuvre = O_A.id_oeuvre
					"//left join ". self::TABLE_OEUVRE_DONNEES_EXTERNES ." OD_EXT ON Oeu.id = OD_EXT.id_oeuvre
					."inner join ". Artiste::TABLE_ARTISTE ." ART ON ART.id_artiste = O_A.id_artiste
					order by Oeu.id_oeuvre ASC
				";

//		echo $query;
		//SELECT * FROM `apm__oeuvre` Oeu inner join apm__oeuvre_artiste O_A ON Oeu.id = O_A.id_oeuvre
		if($mrResultat = $this->_db->query($query))
		{
			while($oeuvre = $mrResultat->fetch_assoc())
			{
				$oeu = end($res);
				
				if(isset($oeu) && $oeu['id_oeuvre'] != $oeuvre['id_oeuvre'])
				{
					
					$oeuvre['Artistes'] = Array();
					$oeuvre['Artistes'][] = Array	(	"id_artiste"=> $oeuvre['id_artiste'], 
														"Nom"=> $oeuvre['Nom'],
														"Prenom"=> $oeuvre['Prenom'],
														"NomCollectif"=> $oeuvre['NomCollectif']
													);
					unset($oeuvre['id_artiste']);
					unset($oeuvre['Nom']);
					unset($oeuvre['Prenom']);
					unset($oeuvre['NomCollectif']);
					
					$res[] = $oeuvre;
				}
				else if(isset($oeu) && $oeu['id_oeuvre'] == $oeuvre['id_oeuvre'])
				{
					$i = count($res)-1;
					$res[$i]['Artistes'][] = Array	(	"id_artiste"=> $oeuvre['id_artiste'], 
														"Nom"=> $oeuvre['Nom'],
														"Prenom"=> $oeuvre['Prenom'],
														"NomCollectif"=> $oeuvre['NomCollectif']
													);
				}
				
				  
			}
		}
		return $res;
	}
	
	/**
	 * Récupère une oeuvre avec son id
	 * @access public
	 * @param int $id Identifiant de l'oeuvre
	 * @return Array
	 */
     // @author Fred
	public function getOeuvre($id) 
	{
		

        
        $query="SELECT A.Nom as nom, 
        A.Prenom as prenom, 
        A.NomCollectif as nomCollectif, 
        A.Description as description, 
        A.id_artiste as id_artiste, 
        O.Titre as titre, 
        O.id_oeuvre as id_oeuvre, 
        O.NomCollection as nomCollection, 
        O.NomCollectionAng as nomCollectionAng, 
        O.dateCreation as dateCreation,  
        GROUP_CONCAT(M.Nom SEPARATOR ', ') as materiaux, 
        C.Nom as categorie, 
        S.Nom as sous_categorie, 
        O.Dimensions as dimensions,
        O.Arrondissement as arrondissement,
        O.Batiment as batiment,
        O.AdresseCivique  as adresseCivique,
        O.CoordonneeLatitude as coordonneeLatitude ,
        O.CoordonneeLongitude as coordonneeLongitude,
        O.Dimensions as dimensions,
        O.TechniqueAng as techniqueAng,
        O.Technique as technique FROM Oeuvre O 
        LEFT JOIN artiste_Oeuvre AO ON O.id_oeuvre = AO.id_oeuvre 
        LEFT JOIN artiste A ON A.id_artiste = AO.id_artiste
        LEFT JOIN materiaux_oeuvre MO ON O.id_oeuvre = MO.id_oeuvre 
        LEFT JOIN materiaux M ON M.id_mat = MO.id_materiaux
        LEFT JOIN categorie_oeuvre CO ON O.id_oeuvre=CO.id_oeuvre
        LEFT JOIN categorie C ON C.id_categorie=CO.id_categorie
        LEFT JOIN sous_categorie_oeuvre SC ON O.id_oeuvre=SC.id_oeuvre
        LEFT JOIN sous_categorie S ON SC.id_sous_categorie=S.id_sous_categorie
        WHERE O.id_oeuvre=$id";


		if($mrResultat = $this->_db->query($query))
		{
            $res = $mrResultat->fetch_assoc();
			
		}
		return $res;
        
	}
	
    
     
    //get toutes les infos de l'oeuvres uniquement avec son ID
     // @author Fred
        public function getOeuvreByID($id)
        {
            $request="SELECT * FROM oeuvre WHERE id_oeuvre='$id'";
            $result = $this->_db->query($request);

            if ($result !== FALSE) 
            {
                $infoTitre = $result->fetch_assoc();
         return $infoTitre;              

            } 
        }


    
         // @author Fred
        public function SupprimerOeuvreByID($id)
        {
            $request="DELETE FROM oeuvre WHERE id_oeuvre='$id'";
            $result = $this->_db->query($request);

            if ($result !== FALSE) 
            {
                return $infoTitre;              
            } 
        }




	
	/**
	 * Récupère les oeuvres avec l'id d'un artiste
	 * @access public
	 * @param int $id Identifiant de l'artiste
	 * @return Array
	 */
	public function getOeuvresParArtiste($id) 
	{
		$res = Array();
		$query = "	SELECT * FROM ". self::TABLE_OEUVRE ." Oeu 
					inner join ". self::TABLE_LIAISON_ARTISTE_OEUVRE ." O_A ON Oeu.id_oeuvre = O_A.id_oeuvre
					where id_artiste=". $id;
				
		if($mrResultat = $this->_db->query($query))
		{
			while($oeuvre = $mrResultat->fetch_assoc())
			{
				$res[] = $oeuvre;
			}
		}
		return $res;
	}
	
	
//	
//	/**
//	 * Modifie les informations sur une oeuvre
//	 * @access public
//	 * @param int $id Identifiant de l'oeuvre
//	 * @return Array
//	 */
//	public function modifOeuvre($id, $aData) 
//	{
//		//var_dump($aData);
//		$resQuery = false;
//		$res = Array();
//		if($this->verifDonneesExterne($id))
//		{
//            
//			if(isset($aData['Description']))
//			{
//                
//				foreach ($aData as $cle => $valeur) {
//					$aSet[] = ($cle . "= '".$valeur. "'");
//				}
//                //var_dump($aSet);
//                
//				if(count($aSet) > 0)
//				{
//					$query = "Update ". self::TABLE_OEUVRE_DONNEES_EXTERNES ." SET ";
//					$query .= join(", ", $aSet);
//					
//					$query .= (" WHERE id_oeuvre = ". $id);
//                    //echo $query;
//					$resQuery = $this->_db->query($query);
//					//echo $query;
//				}
//			}
//		}
//		else 
//		{
//			if(extract($aData) > 0)
//			{
//				$query = "INSERT INTO ". self::TABLE_OEUVRE_DONNEES_EXTERNES ."  (`id_oeuvre`, `Description`, `Categorie`, `cote`) 
//				VALUES ('".$id. "','". $Description. "','". $CategorieObjet. "','1')";
//				$resQuery = $this->_db->query($query);
//				//echo $query;
//			}
//		}
//	
//		return ($resQuery ? $id : 0);
//	}
	
    
    
    // @author Fred
    public function modifierOeuvre($array){
    
    //filtre tous les elements du tableau
    $ID=$this->filtre($array["ID"]);
    $Titre=$this->filtre($array["titre"]);
    $NomCollection=$this->filtre($array["nomCollection"]);
    $NomCollectionAng=$this->filtre($array["nomCollectionAng"]);
    $Technique=$this->filtre($array["technique"]);
    $TechniqueAng=$this->filtre($array["techniqueAng"]);
    $Dimensions=$this->filtre($array["dimensions"]);
    $Arrondissement=$this->filtre($array["arrondissement"]);
    $Batiment=$this->filtre($array["batiment"]);
    $AdresseCivique=$this->filtre($array["adresseCivique"]);
    $CoordonneeLatitude=$this->filtre($array["coordonneeLatitude"]);
    $CoordonneeLongitude=$this->filtre($array["coordonneeLongitude"]);
    $dateCreation=$this->filtre($array["dateCreation"]);
        
        //si les conditions des champs sont bien respectés
        if(is_numeric($ID) && is_numeric($CoordonneeLatitude) && is_numeric($CoordonneeLongitude) && is_string($Technique) && is_string($TechniqueAng) && is_string($NomCollection) && is_string($NomCollectionAng)){
            
                //requete
                $request="UPDATE oeuvre
                        SET Titre = '$Titre', NomCollection ='$NomCollection', NomCollectionAng='$NomCollectionAng',Technique='$Technique', TechniqueAng='$TechniqueAng', Dimensions='$Dimensions', Arrondissement='$Arrondissement', Batiment='$Batiment', AdresseCivique='$AdresseCivique', CoordonneeLatitude='$CoordonneeLatitude', CoordonneeLongitude='$CoordonneeLongitude', dateCreation= '$dateCreation'
                        WHERE id_oeuvre='$ID';";
    
                        //execute requete
                        $result = $this->_db->query($request);
                        //var_dump($result);
                        if ($result !== FALSE) 
                        {
                            return true;              
                        }
                        else 
                        {
                            return "wrong code";
                        }
            }
        //si conditions non respectés refresh la page et msg erreur
        else
        {
            header("Location: /art-public-mtl/api/admin/oeuvre/".$ID."/modifier");
            echo "Veuillez vérifiez vos champs. Vous ne pouvez entrez des caractères dans les coordonnées ou des chiffres dans les techniques !";
        }
        

        }
    
    
    // @author Fred
    public function modifierMateriaux($array){
        
       
    // recup ID oeuvre modifié
        $ID = $array["ID"];
        //reset le tableau intermediaire pour l'oeuvre en question
        $request = "DELETE FROM materiaux_oeuvre WHERE id_oeuvre='$ID'";
        $result = $this->_db->query($request);
        
        
        // separe les matériaux ajoutés
        $materiaux=explode (", ", $this->filtre($array["materiaux"]));
 
        //compte le nombre de matériaux
        $n=count($materiaux);
        if($n==0){
            return true;
        }
        //boucle dans le tableau de materiaux
        for ($i = 0; $i < $n; $i++) {
            //on associe a value un mat du tableau
             $value = $materiaux[$i];
            //verifie si le tableau existe deja
            $request = "SELECT * FROM materiaux WHERE Nom='$value'";
            $result = $this->_db->query($request);
            $rows=$result->num_rows;
            $exist=$result->fetch_assoc();
            //s'il existe alors ne pas l'ajouté
            if ($rows>0) {
                //verifie si le lien existe entre l'oeuvre et le materiau
                $id_mat=$exist["id_mat"];
                $request = "SELECT * FROM materiaux_oeuvre WHERE id_oeuvre='$ID' and id_materiaux='$id_mat'";
                $result = $this->_db->query($request);
                $rows=$result->num_rows;
                //si le lien existe pas alors return
                if($rows==0){
                    //si le lien n'existe pas , on le crée
                    echo $value;
                    $request = "INSERT INTO materiaux_oeuvre(id_materiaux, id_oeuvre) VALUES($id_mat, $ID)";
                    $result = $this->_db->query($request); 
                }
            //sinon ajouté le matériaux dans la table matériaux.
            }else{
                $request = "INSERT INTO materiaux(Nom) VALUES('$value')";
               
                // si le matériau a bien été ajouté, on add les liens dans la table intermédiaire. (+ recup dernier ID crée dans la table matériaux.)
                if( $result = $this->_db->query($request)){
                     $request = "INSERT INTO materiaux_oeuvre(id_materiaux, id_oeuvre) VALUES((SELECT MAX(id_mat) FROM materiaux), $ID)";
                    $result = $this->_db->query($request);
                }
            }
        }
        return true;
    }
    
    
        
    // @author Fred
    public function modifierCat($array){
    // recup ID oeuvre modifié
        $ID = $array["ID"];
        echo "test";
        die();
        $categorie = $array["categorie"];

            //verifie si le tableau existe deja
            $request = "SELECT * FROM categorie WHERE Nom='$categorie'";
            $result = $this->_db->query($request);
            $rows=$result->num_rows;
            $exist=$result->fetch_assoc();
//            var_dump($exist["id_categorie"]);
//        die();
            //s'il existe alors ne pas l'ajouté
            if ($rows>0) {
                //verifie si le lien existe entre l'oeuvre et le materiau
                $id_mat=$exist["id_mat"];
                $request = "SELECT * FROM materiaux_oeuvre WHERE id_oeuvre='$ID' and id_materiaux='$id_mat'";
                $result = $this->_db->query($request);
                $rows=$result->num_rows;
                //si le lien existe pas alors return
                if($rows==0){
                    //si le lien n'existe pas , on le crée
                    echo $value;
                    $request = "INSERT INTO materiaux_oeuvre(id_materiaux, id_oeuvre) VALUES($id_mat, $ID)";
                    $result = $this->_db->query($request); 
                    return true;
                }
            //sinon ajouté le matériaux dans la table matériaux.
            }else{
                $request = "INSERT INTO materiaux(Nom) VALUES('$value')";
               
                // si le matériau a bien été ajouté, on add les liens dans la table intermédiaire. (+ recup dernier ID crée dans la table matériaux.)
                if( $result = $this->_db->query($request)){
                     $request = "INSERT INTO materiaux_oeuvre(id_materiaux, id_oeuvre) VALUES((SELECT MAX(id_mat) FROM materiaux), $ID)";
                    $result = $this->_db->query($request);
                    return true;
                }
            
        }
    }
    
    
    function filtre($variable)
    {
        $varFiltre = $this->_db->real_escape_string($variable);
        $varFiltre=htmlspecialchars($varFiltre);
        //ici, on pourrait appliquer d'autres filtres.... (ex: strip_tags qui enlèverait les tags HTML dans un texte...)
        return $varFiltre;
    }
	
}




?>