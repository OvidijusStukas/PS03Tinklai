<?php
    if (isset($_SESSION['user']))
      header("Location: index.php");

    include 'models/user_model.php';
    $user = new user_model();

    $user_fields = array();

    if (!empty($_POST['submit']))
    {
        $data = $user->get($_POST['username'], $_POST['password']);
        if (isset($data['username'])) {
            $_SESSION['user'] = $data;
            header("Location: index.php");
        }
        else
            echo "<script type='text/javascript'>alert('Naudotojas nerastas sistemoje.')</script>";
    }
?>
<h2>Prisijungimas</h2>
<form id="form" method="POST">
    <div class="list-container">
        <div class="form-group">
            <label>Prisijungimo vardas</label>
            <input class="form-control" name="username" type="text" placeholder="Prisijungimo vardas" value="">
        </div>
        <div class="form-group">
            <label>Slaptažodis</label>
            <input class="form-control" name="password" type="password" placeholder="Slaptažodis" value="">
        </div>
    </div>
    <input class="btn btn-success" type="submit" name="submit" value="Prisijungti">
    <a class="btn btn-warning pull-right" href="index.php?module=<?php echo $module; ?>&action=new">Registracija</a>
</form>