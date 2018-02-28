<?php require_once ("head-utils.php");?>

<?php require_once("navbar.php");?>

<main class="bg-dark">
    <div class="container p-5">
        <div class="container p-2" id="map">
            <div class="row">
                <div class="col card mr-2">
                    <div class="card-block">
                        <h4 class="card-title">Find art!</h4></div>
                    <form id="form" action="">
                        <p>
                            <input id="distance" type="number" name="distance" placeholder="miles from you"/>
                        </p>
                        <p>
                            <input id="type" type="text" name="type" placeholder="type of art"/>
                        </p>
                        <input class="btn btn-outline-dark" type="submit" value="Search">
                    </form>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><a href="#" class="card-link">Murals</a></li>
                        <li class="list-group-item"><a href="#" class="card-link">Sculptures</a></li>
                        <li class="list-group-item"><a href="#" class="card-link">Photos</a></li>
                        <li class="list-group-item"><a href="#" class="card-link">Mosaics</a></li>
                        <li class="list-group-item"><a href="#" class="card-link">Metalwork</a></li>
                    </ul>
                </div class="col">
                <img class="img-responsive" src="img/Transylvania_map.jpg" alt="map">
            </div>
        </div>
        <div class="container p-5">
            <h1 class="text-white">Bookmarks</h1>
            <div class="row">
                <div class="col-5 col-md card m-2">
                    <img class="card-img-top" src="img/unicorn.jpg" alt="Card image cap">
                    <div class="card-block">
                        <h4 class="card-title">Card title</h4>
                    </div>
                </div>
                <div class="col-5 col-md card m-2">
                    <img class="card-img-top" src="img/unicorn.jpg" alt="Card image cap">
                    <div class="card-block">
                        <h4 class="card-title">Card title</h4>
                    </div>
                </div>
                <div class="col-5 col-md card m-2">
                    <img class="card-img-top" src="img/unicorn.jpg" alt="Card image cap">
                    <div class="card-block">
                        <h4 class="card-title">Card title</h4>
                    </div>
                </div>
                <div class="col-5 col-md card m-2">
                    <img class="card-img-top" src="img/unicorn.jpg" alt="Card image cap">
                    <div class="card-block">
                        <h4 class="card-title">Card title</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>