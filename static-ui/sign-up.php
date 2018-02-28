<?php require_once ("head-utils.php");?>

<?php require_once("navbar.php");?>

<main class="gray">
    <div class="container text-center">
        <h1>Sign Up for ABQ Street Art!</h1>
        <form class="form-control-lg" id="form" action="" method="post">
            <div class="info">
                <p>
                    <input id="name" type="text" name="name" placeholder=" User Name"/>
                </p>
                <p>
                    <input id="email" type="email" name="email" placeholder=" Email"/>
                </p>
                <p>
                    <input id="password" type="text" name="password" placeholder=" Password">
                </p>
                <p>
                    <input id="password-confirm" type="text" name="password-confirm" placeholder=" Re-enter Password">
                </p>
                <input class="btn btn-outline-warning" type="submit" value="Sign Up!">
            </div>
        </form>
    </div>
</main>
