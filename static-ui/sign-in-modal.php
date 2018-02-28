<?php require_once ("head-utils.php");?>

<?php require_once("navbar.php");?>

<main>
<!--    <!-- Button trigger modal -->
<!--    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModalLong">-->
<!--        Launch demo modal-->
<!--    </button>-->

    <!-- Modal -->
    <div class="modal fade" id="signIn" tabindex="-1" role="dialog" aria-labelledby="signIn" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content gray">
                <div class="modal-header gray">
                    <h5 class="modal-title" id="signIn">Sign In</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body gray">
                    <form class="form-control-lg" id="form" action="" method="post">
                        <div class="info">
                                <input id="email" type="email" name="email" placeholder=" Email"/>
                                <input id="password" type="text" name="password" placeholder=" Password">
                        </div>
                    </form>
                    <div class="modal-footer">
                        <input class="btn btn-outline-warning" type="submit" value="Sign In!">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
