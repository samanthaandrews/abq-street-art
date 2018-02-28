<?php require_once ("head-utils.php");?>

<?php require_once("navbar.php");?>

<main>
    <div class="container text-center">
        <h1>Sign Up for ABQ Street Art!</h1>
        <form class="form-control-lg" id="form" action="" method="post">
            <div class="info">
                    <input class="form-control" id="name" type="text" name="name" placeholder=" User Name"/>
                    <input class="form-control" id="email" type="email" name="email" placeholder=" Email"/>
                    <input class="form-control" id="password" type="text" name="password" placeholder=" Password">
                    <input class="form-control" id="password-confirm" type="text" name="password-confirm" placeholder=" Re-enter Password">
                <input class="btn" type="submit" value="Sign Up!">
            </div>
        </form>
    </div>
</main>
