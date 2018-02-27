<!DOCTYPE HTML>
<html>

<head>
        <link rel="stylesheet" type="text/css" href="stylesheet/header.css" media="all"/>
</head>

<body>

<div class="sign-bar">
   <nav>
        <ul>
        <li class="logo"><a href="gallery.php">Camagru</a>
        </li>
        <li class="menuprincipal"><a href="index.php">@<?php echo $_SESSION['login']?></a><img src="img/ic_low.png">
        <ul class="submenu">
            <?php if (isset($_SESSION['login']) && $_SESSION['login'] != "")
            {

                echo "<li><a href=\"principal.php\">Take a picture</a></li>";
                echo "<li><a href=\"gallery.php\">The gallery</a></li>";
                echo "<li><a href=\"yourpictures.php\">My pictures</a></li>";
                echo "<li><a href=\"yourprofile.php\">My profile</a></li>";
                echo "<li><a href=\"index.php\">Log out</a></li>";
            }
            else
            {
                echo "<li><a href=\"gallery.php\">The gallery</a></li>";
                echo "<li><a href=\"index.php\">Principal page</a></li>";
            }
            ?>
        </ul>
        </li>
        </ul>
    </nav>
</div>