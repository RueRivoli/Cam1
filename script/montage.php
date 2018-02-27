<?php
session_start();

$filters_set = array('none', 'lion', 'dolphin', 'noel', 'asmshirt', 'asmlogo', 'cadre', 'cadre2', 'scarlett', 'collieraclous', 'kungfu', 'mrbean');

$i = 0;
while ($filters_set[$i])
{
    if (isset($_GET[$filters_set[$i]]) && htmlspecialchars($_GET[$filters_set[$i]]) === "select")
    {
        $_SESSION['filter'] = $filters_set[$i];
        break;
    }
    $i++;
}

    header('Location: ../principal.php');
    exit;
?>