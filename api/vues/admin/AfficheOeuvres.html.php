	
		 
		 	<aside id="menu_aside">
				<a id="menu_oeuvre" href=""><img src="../../img/icons/imageBlanch.svg" alt="icon image"><p class="text_menu">Oeuvres</p></a>
				<a id="menu_artistes" href=""><img src="../../img/icons/paletteBlanch.svg" alt="icon image"><p class="text_menu">Artistes</p></a>
				<a id="menu_utilisateurs" href=""><img src="../../img/icons/personBlanch.svg" alt="icon image"><p class="text_menu">Utilisateurs</p></a>
			</aside>
			<section class="contenu_listeOeuvres">
				<section class="recherche">
					<form action="">
						<div class=bar_image>
							<input id="bar_recherche" type="text" name="recherche" value="Recherche une oeuvre">
							<img id="img_recherche" src="../../img/icons/search_40px.svg" alt="search">
						</div>						
						<input id="btn_submit" type="submit" name="submit" value="+ Ajouter une oeuvre">
						
					</form>
				</section>
				<section class="nomsChamps">
					<P>TITRE</P>
					<P>ARTISTE</P>
					<P>ARRONDISSEMENT</P>
					<p></p>
				</section>
				<section id="liste_oeuvres" class="oeuvres flex flex-col">
					<?php
					$compt=0;
					foreach ($aData as $cle => $oeuvre) {
						extract($oeuvre);            
						++$compt;
											
					?>

						<div class="info_oeuvre">
							<p class="valeur_info_oeuvre"><?php echo $Titre ?></p>
							<p class="valeur_info_oeuvre"><?php foreach($Artistes as $value=> $test){
                    
                            echo $test["Nom"];
                            echo " ";
                            echo $test["Prenom"];
                        
                       
                    } ?></p>
							<p class="valeur_info_oeuvre"><?php echo $Arrondissement ?></p>
							<span class="valeur_info_oeuvre">
								<a href="/art-public-mtl/api/admin/oeuvre/<?php echo $id_oeuvre ?>/modifier"><img id='crayon' src="../../img/icons/crayon.svg" alt="Modifier"></a>
								<a href="/art-public-mtl/api/admin/oeuvre/<?php echo $id_oeuvre ?>/supprimer"><img id='poubelle' src="../../img/icons/poubelle.svg" alt="Supprimer"></a>
							</span>

						</div>
						
						<?php
					}
					?>	
						
				</section>
					
			</section>
							
