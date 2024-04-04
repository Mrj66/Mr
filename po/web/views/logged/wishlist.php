<?php
$controller = new MainController();

function humanTiming($time)
{
    $time = time() - $time; // to get the time since that moment
    $time = ($time < 1) ? 1 : $time;
    $tokens = array(
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
        return $numberOfUnits . ' ' . $text . (($numberOfUnits > 1) ? 's' : '');
    }
}

$glIndex = 0;
$curPage = isset($_GET["curPage"]) ? $_GET["curPage"] : 0;
$wishlists = $controller->mainManager->getAllFavorites(10, $curPage*10);

echo '<div class="page-recherche-liste-entreprises" style="max-width:fit-content;">';
if(sizeof($wishlists) > 0) {
    foreach ($wishlists as &$stageContainer) {
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
} else { //nothing found
    echo '
    <div class="page-recherche-liste-entreprises" style="max-width:100vw;">
        <span class="offre-stage-text1">
            Il me semble que vous n\'avez rien wishlisté...
            Vous devriez essayer!
        </span>
    </div>
    ';
}

echo '
</div>';