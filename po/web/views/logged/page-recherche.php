<div class="page-recherche-container">
	<div class="page-recherche-container1">
		<div class="page-recherche-container2">
			<?php 
			if($datas["searchType"] == "stage"){
				echo '
			<form class="page-recherche-form" action="" method="post">
				<select name="date" class="page-recherche-select">
					<option value="null" '.(!isset($_POST["date"]) || $_POST["date"] == "null" ? "selected" : "").' disabled class="page-recherche-default"> Dates </option>
					<option value="24h" '.(isset($_POST["date"]) && $_POST["date"] == "24h" ? "selected" : "").' >Dernières 24h</option>
					<option value="3dj" '.(isset($_POST["date"]) && $_POST["date"] == "3dj" ? "selected" : "").' >3 derniers jours</option>
					<option value="7dj" '.(isset($_POST["date"]) && $_POST["date"] == "7dj" ? "selected" : "").' >7 derniers jours</option>
					<option value="14dj" '.(isset($_POST["date"]) && $_POST["date"] == "14dj" ? "selected" : "").' >14 derniers jours</option>
					<option value="null">Ne pas restreindre</option>
				</select>
				<select name="duree" class="page-recherche-select">
					<option value="null" '.(!isset($_POST["duree"]) || $_POST["duree"] == "null" ? "selected" : "").' disabled class="page-recherche-default"> Durée </option>
					<option value="2m" '.(isset($_POST["duree"]) && $_POST["duree"] == "2m" ? "selected" : "").' >2 mois</option>
					<option value="34m" '.(isset($_POST["duree"]) && $_POST["duree"] == "34m" ? "selected" : "").' >3-4 mois</option>
					<option value="56m" '.(isset($_POST["duree"]) && $_POST["duree"] == "56m" ? "selected" : "").' >5-6 mois</option>
					<option value="6+m" '.(isset($_POST["duree"]) && $_POST["duree"] == "6+m" ? "selected" : "").' >6 mois et +</option>
					<option value="null">Ne pas restreindre</option>
				</select>
				<select name="niv" class="page-recherche-select">
					<option value="null" '.(!isset($_POST["niv"]) || $_POST["niv"] == "null" ? "selected" : "").'  disabled class="page-recherche-default"> Niveau d\'études </option>
					<option value="b+2" '.(isset($_POST["niv"]) && $_POST["niv"] == "b+2" ? "selected" : "").' >Bac+2</option>
					<option value="b+3" '.(isset($_POST["niv"]) && $_POST["niv"] == "b+3" ? "selected" : "").' >Bac+3</option>
					<option value="b+4" '.(isset($_POST["niv"]) && $_POST["niv"] == "b+4" ? "selected" : "").' >Bac+4</option>
					<option value="b+5" '.(isset($_POST["niv"]) && $_POST["niv"] == "b+5" ? "selected" : "").' >Bac+5</option>
					<option value="null">Ne pas restreindre</option>
				</select>
				<select name="sec" class="page-recherche-select">
					<option value="null" '.(!isset($_POST["sec"]) || $_POST["sec"] == "null" ? "selected" : "").'  disabled class="page-recherche-default"> Secteur d\'activité </option>
					<option value="info" '.(isset($_POST["sec"]) && $_POST["sec"] == "info" ? "selected" : "").' >Informatique</option>
					<option value="btp" '.(isset($_POST["sec"]) && $_POST["sec"] == "btp" ? "selected" : "").' >BTP</option>
					<option value="null">Ne pas restreindre</option>
				</select>
				<input type="hidden" value="'.htmlentities($datas["searchType"]).'" name="searchType" />
				<input type="hidden" value="'.htmlentities($datas["searchValue"]).'" name="searchValue" />
				<div class="page-recherche-container3">
					<button type="submit" name="filter" class="page-recherche-button button">
						FILTRER
					</button>
				</div>
				'.($_SESSION["permissionLevel"] > 1 ? '
				<div class="page-recherche-container3">
					<button type="submit" name="creation" class="page-recherche-button button">
						CREER
					</button>
				</div>': '').'
			</form>
				';
			} else if ($datas["searchType"] == "entreprise") {
				echo '
					<form class="page-recherche-form" action="" method="post">
						<input type="hidden" value="'.htmlentities($datas["searchType"]).'" name="searchType" />
						<input type="hidden" value="'.htmlentities($datas["searchValue"]).'" name="searchValue" />
						'.($_SESSION["permissionLevel"] > 1 ? '
						<div class="page-recherche-container3">
							<button type="submit" name="creation" class="page-recherche-button button">
								CREER
							</button>
						</div>': '').'
					</form>
				';
			} else if ($datas["searchType"] == "utilisateur") {
				if($_SESSION["permissionLevel"] <= 1) header("Location: index");
				echo '
			<form class="page-recherche-form" action="" method="post">
				<input type="hidden" value="'.htmlentities($datas["searchType"]).'" name="searchType" />
				<input type="hidden" value="'.htmlentities($datas["searchValue"]).'" name="searchValue" />
				'.($_SESSION["permissionLevel"] > 1 ? '
				<div class="page-recherche-container3">
					<button type="submit" name="creation" class="page-recherche-button button">
						CREER
					</button>
				</div>': '').'
			</form>
				';
			} else if ($datas["searchType"] == "tuteur"){
				if($_SESSION["permissionLevel"] <= 2) header("Location: index");
				echo '
					<form class="page-recherche-form" action="" method="post">
						<input type="hidden" value="'.htmlentities($datas["searchType"]).'" name="searchType" />
						<input type="hidden" value="'.htmlentities($datas["searchValue"]).'" name="searchValue" />
						'.($_SESSION["permissionLevel"] > 2 ? '
						<div class="page-recherche-container3">
							<button type="submit" name="creation" class="page-recherche-button button">
								CREER
							</button>
						</div>': '').'
					</form>
				';
			}
			?>
		</div>
		<div class="page-recherche-liste-entreprises">
			<div class="search-bar-container search-bar-root-class-name3">
				<div class="search-bar-container1">
					<?php require("layouts/search-bar.php")?>
				</div>
			</div>
			<?php
				$controller = new MainController();

				function humanTiming ($time)
				{
					$time = time() - $time; // to get the time since that moment
					$time = ($time<1)? 1 : $time;
					$tokens = array (
						31536000 => 'année',
						2592000 => 'mois',
						604800 => 'semaine',
						86400 => 'jour',
						3600 => 'heure',
						60 => 'minute',
						1 => 'seconde'
					);

					foreach ($tokens as $unit => $text) {
						if ($time < $unit) continue;
						$numberOfUnits = floor($time / $unit);
						return $numberOfUnits.' '.$text.(($numberOfUnits>1)?'s':'');
					}
				}

				$allStageIDs = [];
				$glIndex = 0;

				if($datas["searchType"] == "stage"){
					if($datas["totalMatching"] > 0){
						echo '
						<span class="number-indicator">
							Nous avons trouvés '.($datas["totalMatching"]-1).' stages pour vous!
						</span>
						';
						foreach($datas["stages"] as &$stageContainer){
							$stageData = $controller->mainManager->getStageFromID($stageContainer["id_stage"])[0];
							$desc = $stageData["description"];
							$allStageIDs[$glIndex] = $stageContainer["id_stage"];
							if(strlen($desc) > 300){
								$desc = explode("$$;;$$", wordwrap($desc, 300, "$$;;$$"))[0]."...";
							}
							$jsonDesc = json_encode($desc);
		
							$datePostee = new DateTime($stageData["date_offre"]);
							$dateElapsed = humanTiming(strtotime($datePostee->format("Y-m-d H:i:s")));
		
							$tags = array(
								!isset($stageData["promo_concernees"]) ? null : ucfirst($stageData["promo_concernees"]), 
								!isset($stageData["domaine"]) ? null : $stageData["domaine"], 
								!isset($stageData["duree"]) ? null : $stageData["duree"], 
								!isset($stageData["remuneration"]) ? null : $stageData["remuneration"]."€");
							$temp = explode(",", $stageData["competences"]);
							$tags = array_merge($tags, $temp);
							$finalHTML = "";
							foreach($tags as &$tag){
								if(isset($tag)) {
								$finalHTML .= '<div class="tag-container">
									<label class="tag-text">
										<span>'.$tag.'</span>
									</label>
								</div>';
								}
							}

							$isFavorite = $controller->mainManager->getFavorite($stageContainer["id_stage"]);
							
							echo '
							<div class="offre-stage-blog-post-card" action="" method="post">
								<div class="offre-stage-container" id="stageCard_'.$stageContainer["id_stage"].'">
									<div class="offre-stage-container1">
										<span class="offre-stage-text">
											'.$stageData["nom_entreprise"].'
										</span>
										<span class="offre-stage-text1">
											<span>Il y a '.$dateElapsed.'</span>
										</span>
									</div>
									<h1 class="offre-stage-text2">
										<span>'.$stageData["titre"].'</span>
									</h1>
									<span class="offre-stage-text3">
										<span id="id_'.$stageData["titre"].'"></span>
									</span>
									<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
									<script>
										document.getElementById("id_'.$stageData["titre"].'").innerHTML =
										marked.parse('.$jsonDesc.');
									</script>
									<div class="offre-stage-container2">
										'.$finalHTML.'
									</div>
									<div class="offre-stage-container3">
										<a class="offre-stage-text4">
											Lire plus -&gt;
										</a>
										<button type="button" id="fav_'.$stageContainer["id_stage"].'" class="offre-stage-button button">
											<span class="'.(isset($isFavorite) && $isFavorite ? 'material-symbols-outlined material-symbols-outlined-fill' : 'material-symbols-outlined').'">
												bookmark
											</span>
										</button>
									</div>
								</div>
							</div>
							';
							$glIndex++;
						}
		
						$allStageCardIDs = $allStageIDs;
						$allStageFavIDs = $allStageIDs;
						foreach($allStageIDs as $key=>$id){
							$allStageCardIDs[$key] = "stageCard".$id;
							$allStageFavIDs[$key] = "fav".$id;
						}
						$allStageIDsStr = "['".implode("', '", $allStageIDs)."']";
		
						$perms = $_SESSION["permissionLevel"];
		
						echo '
						<script>
							let cards = [];
							let favs = [];
							let whichHovering = -1;
							let isHoveringFavorite = false;
		
							for(let x = 0; x < '.$allStageIDsStr.'.length; x++) {
								cards.push(document.getElementById("stageCard_"+'.$allStageIDsStr.'[x]));
								favs.push(document.getElementById("fav_"+'.$allStageIDsStr.'[x]));
		
								favs.forEach(function(element){
									element.onmouseover = function(){
										isHoveringFavorite = true;
									}
									element.onmouseout = function(){
										isHoveringFavorite = false;
									}
								});
		
								cards.forEach(function(element){
									element.onmouseover = function(){
										var parts = element.id.split("_");
										var result = parts.pop();
										whichHovering = Number(result);
										//console.log(whichHovering);
									}
									element.onmouseout = function(){
										whichHovering = Number(-1);
										//console.log(whichHovering);
									}
									element.onclick = function(){
										var parts = element.id.split("_");
										var result = parts.pop();
										if (isHoveringFavorite && whichHovering == Number(result)){
											'.($perms > 0 && $perms < 3 ? '
											$.ajax({
												url:"function--addWishlist",
												type: \'post\',
												dataType: "json",
												data: {
													id: result
												},
												success: function(data){
													isNewWishlist = (data[0]["wish_listed"] == 1);
													if(isNewWishlist){
														$("#fav_"+whichHovering+" > span").addClass("material-symbols-outlined-fill");
													} else {
														$("#fav_"+whichHovering+" > span").removeClass("material-symbols-outlined-fill");
													}
												},
												error: function(jqXHR, textStatus, errorThrown){
													console.log(errorThrown);
												}
											});' : '').'
											return false;
										} else {
											window.location.href = "affiche&offreID="+result;
										}
									}
								});
							}
						</script>';

						require_once("layouts/pagination.php");
					} else { //nothing found
						echo "Nous n'avons pas trouvé de stages pour vous...";
					}
				} else if ($datas["searchType"] == "entreprise") {
					if($datas["totalMatching"] > 0){
						echo '
						<span class="number-indicator">
							Nous avons trouvés '.($datas["totalMatching"]-1).' entreprises correspondantes!
						</span>
						';
						foreach($datas["entreprises"] as &$entrepriseContainer){
							$entrepriseData = $controller->mainManager->getEntrepriseFromID($entrepriseContainer["id_entreprise"])[0];
							
							$pfp = $entrepriseData["logo"]!="" ? $entrepriseData["logo"] :"placeholder.png";
							echo '
							<div onclick="window.location=\'affiche&entrepriseID='.$entrepriseContainer["id_entreprise"].'\';" class="entreprise-card-blog-post-card">
								<div class="entreprise-card-container">
									<img
										alt="Logo de '.$entrepriseData["nom"].'"
										src="'.URL.'public/pfp/'.$pfp.'"
										class="entreprise-card-image"
									/>
									<div class="entreprise-card-container1">
										<h1><span>'.$entrepriseData["nom"].'</span></h1>
										<span class="entreprise-card-text1"><span>'.$entrepriseData["secteur"].'</span></span>
									</div>
								</div>
							</div>
							';
						}
						require_once("layouts/pagination.php");
					} else { //nothing found
						echo "Nous n'avons pas trouvé d'entreprises correspondantes à cette recherche...";
					}
				} else if ($datas["searchType"] == "utilisateur"){
					if($datas["totalMatching"] > 0){
						echo '
						<span class="number-indicator">
							Nous avons trouvés '.($datas["totalMatching"]).' utilisateurs correspondants!
						</span>
						';
						foreach($datas["users"] as &$userContainer){
							$userData = $controller->mainManager->getUserFromID($userContainer["id_utilisateur"])[0];
							
							$pfp = isset($userData["pfp"]) ? $userData["pfp"] :"placeholder.png";
							echo '
							<div onclick="window.location=\'affiche&userID='.$userContainer["id_utilisateur"].'\';" class="etudiant-card-blog-post-card">
								<div class="etudiant-card-container">
									<img
										src="'.URL.'public/pfp/'.$pfp.'"
										class="etudiant-card-image"
										alt="Photo de '.$userData["name"].' '.$userData["surname"].'"
									/>
									<div class="etudiant-card-container1">
										<h1><span>'.strtoupper($userData["name"]).' '.$userData["surname"].'</span></h1>
										<span class="etudiant-card-text1"><span>'.$userData["centre"].'</span></span>
										<div class="etudiant-card-container2">
											<span class="etudiant-card-annee">'.$userData["promoName"].'</span>
											<span class="etudiant-card-promo">'.$userData["promo"].'</span>
										</div>
									</div>
								</div>
							</div>
							';
						}
						require_once("layouts/pagination.php");
					} else { //nothing found
						echo "Nous n'avons pas trouvé d'utilisateurs correspondants à cette recherche...";
					}
				} else if ($datas["searchType"] == "tuteur"){
					if($datas["totalMatching"] > 0){
						echo '
						<span class="number-indicator">
							Nous avons trouvés '.($datas["totalMatching"]).' tuteurs correspondants!
						</span>
						';
						foreach($datas["tuteurs"] as &$userContainer){
							$userData = $controller->mainManager->getUserFromID($userContainer["id_utilisateur"])[0];
							$HTMLPromoList = "";
							$HTMLCentreList = "";
							$promos = $controller->mainManager->getAllTuteurPromos($userContainer["id_utilisateur"]);
							foreach ($promos as $promo){
							  $HTMLPromoList .= '
							  <li class="visu-etudiant-li list-item">
								<span>'.$promo["promo"].', '.$promo["displayName"].', '.$promo["centre"].'</span>
							  </li>
							  ';
						
							  if(strstr($HTMLCentreList, $promo["centre"]) === false){
								$HTMLCentreList .= $promo["centre"].', ';
							  }
							}
							$HTMLCentreList = substr($HTMLCentreList, 0, -2);
							$pfp = $userData["pfp"]!="" ? $userData["pfp"] :"placeholder.png";
							
							echo '
							<div onclick="window.location=\'affiche&tuteurID='.$userContainer["id_utilisateur"].'\';" class="etudiant-card-blog-post-card">
								<div class="etudiant-card-container">
									<img
										src="'.URL.'public/pfp/'.$pfp.'"
										class="etudiant-card-image"
										alt="Photo de '.$userData["name"].' '.$userData["surname"].'"
									/>
									<div class="etudiant-card-container1">
										<h1><span>'.strtoupper($userData["name"]).' '.$userData["surname"].'</span></h1>
										<span class="etudiant-card-text1"><span>'.$HTMLCentreList.'</span></span>
									</div>
								</div>
							</div>
							';
						}
						require_once("layouts/pagination.php");
					} else { //nothing found
						echo "Nous n'avons pas trouvé de tuteurs correspondants à cette recherche...";
					}
				}
			?>

			
		</div>
	</div>
</div>