<?php
session_start();
if (!isset($_SESSION["filter"]))
    $_SESSION["filter"] = "lion";
$_SESSION['import'] = "";
if (!isset($_SESSION["login"]) || $_SESSION["login"] == "")
{
    header('Location: index.php');
    exit;
}
if (isset($_SESSION['error']))
    $_SESSION['error'] = "";
?>

<!DOCTYPE HTML>
<html>

<head>
        <title>Camagru</title>
        <meta charset="utf-8">
        <meta name="description" content="165c. uniques">
        <link rel="stylesheet" type="text/css" href="stylesheet/principal.css" media="all"/>
        <!-- <link rel="stylesheet" type="text/css" href="stylesheet/onscreen.css" media="all"/> -->
        <link rel="stylesheet" type="text/css" href="stylesheet/header.css" media="all"/>
</head>

<body>

<?php
include "functions/header.php";
?>

<div class="content">
    <div id="container">
        <div id="selector">
            <form method="GET" action="script/montage.php">
            <?php
            $filter = array('lion', 'dolphin', 'noel', 'asmshirt', 'asmlogo', 'cadre', 'cadre2', 'scarlett', 'collieraclous', 'kungfu', 'mrbean');
            $id = array('lion', 'dolphin', 'none', 'monacoshirt', 'monacologo', 'none', 'none', 'scarlett', 'none', 'none', 'mrbean'); 
            $i = 0;
            if (!isset($_SESSION['filter']))
                $_SESSION['filter'] = 'lion';
            while ($filter[$i])
            {
                $b = 1;
                if ($_SESSION['filter'] == $filter[$i])
                    $b = "class= \"valided\"";
                echo "<div class=\"filter\">";
                echo "<div class=\"collage\">";
                echo "<img id=\"".$id[$i]."\" src=\"img/".$filter[$i].".png\">";
                echo " </div>";
                echo "</div>";
                if ($b != 1)
                    echo " <input type= \"submit\" ".$b." name=\"".$filter[$i]."\" value=\"select\">";
                else
                    echo " <input type= \"submit\" name=\"".$filter[$i]."\" value=\"select\">"; 
                $i++;
            }
            ?>
            </form>
        </div>
        <div id="cam">
            <div id="space">
                <video id="video"></video>
                <canvas id="canvas" style="display: none;"></canvas>
                <!-- <div class="preview_right">
                    <img <?php if ($_SESSION['filter'] !== 'dolphin') {?> class = "none"<?php }?> id="dolphin_screen" src="img/dolphin.png"></img>
                </div>
                <div class="preview_center">
                    <img <?php if ($_SESSION['filter'] !== 'noel') {?> class = "none"<?php }?> id="noel_screen" src="img/noel.png"></img>
                </div>
                <div class="preview_cadre">
                    <img <?php if ($_SESSION['filter'] !== 'cadre') {?> class = "none"<?php }?> id="cadre_screen" src="img/cadre.png"></img>
                </div>
                <div class="preview_cadre2">
                    <img <?php if ($_SESSION['filter'] !== 'cadre2') {?> class = "none"<?php }?>  id="cadre2_screen" src="img/cadre2.png"></img>
                </div>
                <div class="preview_logo">
                    <img <?php if ($_SESSION['filter'] !== 'asmlogo') {?> class = "none"<?php }?> id="asmlogo_screen" src="img/asmlogo.png"></img>
                </div>
                <div class="preview_lion">
                    <img <?php if ($_SESSION['filter'] !== 'lion') {?> class = "none"<?php }?> id="lion_screen none" src="img/lion.png"></img>
                </div>
                <div class="preview_scarlett">
                    <img  <?php if ($_SESSION['filter'] !== 'scarlett') {?> class = "none"<?php }?> id="scarlett_screen" src="img/scarlett.png"></img>
                </div>
                <div class="preview_kungfu">
                    <img  <?php if ($_SESSION['filter'] !== 'kungfu') {?> class = "none"<?php }?> id="kungfu_screen" src="img/kungfu.png"></img>
                </div>
                <div class="preview_shirt">
                    <img <?php if ($_SESSION['filter'] !== 'asmshirt') {?> class = "none"<?php }?> id="asmshirt_screen" src="img/asmshirt.png"></img>
                </div>
                <div class="preview_neck">
                    <img <?php if ($_SESSION['filter'] !== 'collieraclous') {?> class = "none"<?php }?> id="collieraclous_screen" src="img/collieraclous.png"></img>
                </div>
                <div class="preview_mrbean">
                    <img <?php if ($_SESSION['filter'] !== 'mrbean') {?> class = "none"<?php }?> id="mrbean_screen" src="img/mrbean.png"></img>
                </div> -->
                   <!-- <video id="video"></video>
                <button id="startbutton">Prendre une photo</button>
                <canvas id="canvas"></canvas>
                <img src="http://placekitten.com/g/320/261" id="photo" alt="photo"> -->
                </div>
            
             <button id="startbutton">Take a picture</button>
            
             <div class="upload">
             <form action="script/upload.php" method="post" enctype="multipart/form-data">
                <?php if (isset($_SESSION['upload']) && $_SESSION['upload'] != ''){
                 echo $_SESSION['upload'];
                }?>
                    <input id= "upl" type="file" name="fileToUpload" id="fileToUpload">
                    <input id="sub_upl" type="submit" value="Upload" name="submit">
            </form>
            </div>
        </div>
    
    <div id="pic_view">
        <div class="cadrage">
            <?php
                
                include 'config/database.php';
                include 'functions/initdb.php';
                try {
                    
                    $st =  $db->prepare('SELECT id_user FROM users WHERE user_login = :user_login');
                    $st->bindParam(':user_login', $_SESSION['login']);
                    $st->execute();
                    $tab = $st->fetch();
                    $id_user = $tab['id_user'];
    
                    $req = $db->prepare("SELECT id_post, post_url, date_creation FROM post WHERE DATEDIFF(CURRENT_DATE(), date_creation) <= 1 AND id_user= :id_user ORDER BY date_creation DESC");
                    $req->execute(array(':id_user' => $id_user));
                    $tab = $req->fetchAll();
                    $i = 0;
                    while ($tab[$i])
                    {
                    
                        echo "<div class=\"cadrage\">";
                        echo "<img src=\"".$tab[$i]['post_url']."\" >";
                        echo "<div class=\"delete\">";
                        echo "<a href=\"script/delete_post.php?post_id=".$tab[$i]['id_post']."&amp;b=1\">";
                        echo "<img src=\"img/x2.png\" >";
                        echo "</a>";
                        echo "</div>";
                        echo "</div>";
                        $i++;
                    }
                }
                catch(PDOException $e) {
                    echo "Impossible to have access to table post! The mistake is : ".$e;
                }
            ?>
        </div>
    </div>
    <canvas id="canvas"></canvas>
    </div>
    </div>
    <script type="text/javascript" src="js/picture.js">
     </script>
    <footer>
    @fgallois 2018
</footer>
</body>

</html>