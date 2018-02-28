<?php require_once ("head-utils.php");?>

<?php require_once("navbar.php");?>

<main>

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
                                <input class="form-control" id="email" type="email" name="email" placeholder=" Email"/>
                                <input class="form-control" id="password" type="text" name="password" placeholder=" Password">
                        </div>
                    </form>
                    <div class="modal-footer">
                        <input class="btn" type="submit" value="Sign In!">
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
