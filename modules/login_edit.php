<?php
    if (isset($_SESSION['user']))
        header("Location: index.php");

    include 'models/user_model.php';
    $user = new user_model();

    if (isset($_POST['submit']))
    {
        include 'utils/data_util.php';
        $data = data_util::gatherDataFromFields($_POST);

        $user->register($data);
        header("Location: index.php");
    }
?>
<h2>Registracija</h2>
<form id="form" method="POST">
    <div class="list-container">
        <div class="form-group">
            <label>Prisijungimo vardas</label>
            <input class="form-control" name="username" type="text" placeholder="Prisijungimo vardas" value="">
        </div>
        <div class="form-group">
            <label>El. paštas</label>
            <input class="form-control" name="email" type="text" placeholder="El. paštas" value="">
        </div>
        <div class="form-group">
            <label>Slaptažodis</label>
            <input class="form-control" name="password" type="password" placeholder="Slaptažodis" value="">
        </div>
    </div>
    <input class="btn btn-success" type="submit" name="submit" value="Registruotis">
</form>