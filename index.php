<?php
    include 'utils/config.php';
    include 'utils/mysql.php';

    $module = isset($_GET['module']) ? $_GET['module'] : '';
    $remove_id = isset($_GET['remove']) ? $_GET['remove'] : '';
    $item_id = isset($_GET['id']) ? $_GET['id'] : '';
    $action = isset($_GET['action']) ? $_GET['action'] : '';

    session_start();
    $userRole = '';
    if (isset($_SESSION['user']) && isset($_SESSION['user']['userRoleId']))
        $userRole = $_SESSION['user']['userRoleId'];
?>
<head lang="lt">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>Mobilus Internetas</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u"
          crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.12/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="content/site.css">

    <script src="https://code.jquery.com/jquery-3.1.1.min.js"
            integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
            crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"
            integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa"
            crossorigin="anonymous"></script>
    <script src="https://cdn.datatables.net/1.10.12/js/jquery.dataTables.min.js" type="application/javascript"></script>
    <script src="content/site.js" type="application/javascript"></script>
</head>
<body>
    <div class="container">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="./">Mobilus internetas</a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php?module=plans">Planai</a></li>
                        <?php
                            if ($userRole == '1' || $userRole == '2')
                            {
                                echo '<li><a href="index.php?module=accounting">Užsakyti planai</a></li>';
                            }
                            else if ($userRole == '3' && isset($_SESSION['user']['planId']))
                            {
                                echo "<li><a href='index.php?module=my_plan&id={$_SESSION['user']['planId']}'>Mano planas</a></li>";
                            }
                        ?>
                    </ul>
                    <ul class="nav navbar-nav pull-right">
                        <?php
                            if ($userRole == '')
                            {
                                echo '<li><a href="index.php?module=login">Prisijungimas</a></li>';
                            }
                            else
                            {
                                echo '<li><a href="index.php?module=logout">Atsijungti</a></li>';
                            }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="jumbotron">
            <?php
                if (empty($module)) {
                    echo '<h2>T120B145 modulio semestrinis projektas</h2></br>';
                    echo '<p>Darbą atliko:</p>';
                    echo '<p>Ovidijus Stukas IFF-4/3</p>';
                }
                else if (empty($item_id) && empty($action)) {
                    include "modules/${module}_list.php";
                }
                else if (!empty($action) && $action == 'view')
                    include "modules/${module}_view.php";
                else
                    include "modules/${module}_edit.php";
            ?>
        </div>
    </div>
</body>