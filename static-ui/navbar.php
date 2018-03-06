<?php require_once ("head-utils.php");?>

<?php require_once ("sign-in-modal.php");?>

<header>
	<div class="container-fluid p-0 black ">
		<nav class="navbar navbar-expand-lg">
            <img src="../src/img/logo1.svg" alt="logo" class="logo">
<!--			<span href="home-view.php" class="navbar-brand text-center ml-3 name">ABQ Street Art</span>-->
			<button class="navbar-toggler navbar-toggler-right navbar-dark" type="button" data-toggle="collapse"
					  data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
					  aria-label="Toggle Navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarSupportedContent">
				<ul class="navbar-nav ml-auto">
					<li class="nav-item">
						<a href="sign-up.php" class="nav-link">Sign up</a>
					</li>
					<li class="nav-item">
                        <!-- link trigger modal -->
						<signIn></signIn>
					</li>
					<li class="nav-item">
						<a href="update-profile.php" class="nav-link">Settings</a>
					</li>
					<li class="nav-item">
						<a href="home-view.php" class="nav-link">Sign out</a>
					</li>
				</ul>
			</div>
		</nav>
	</div>
</header>