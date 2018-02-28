<?php require_once ("head-utils.php");?>

<?php require_once("navbar.php");?>

<main>
    <div class="container-fluid">
        <h1 class="text-center">Update Profile</h1>
        <form class="form-control-lg" id="form" action="" method="post">
            <div class="info">
                <input class="form-control" id="email" type="email" name="email" placeholder=" Email"/>
                <input class="form-control" id="password" type="text" name="password" placeholder=" Password"/>
                <input class="form-control" id="password-confirm" type="text" name="password-confirm" placeholder=" Re-enter Password"/>
            </div>
            <button type="button" class="btn pull-right m-2" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn green pull-right m-2">Save changes</button>
        </form>
    </div>
</main>
