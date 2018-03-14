<?php
require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/php/classes/autoload.php";
require_once dirname(__DIR__,3)."/php/classes/autoload.php";
require_once dirname(__DIR__,3)."/php/lib/xsrf.php";
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");
require_once dirname(__DIR__, 3) . "/php/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/php/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/php/lib/uuid.php";

use Edu\Cnm\AbqStreetArt\ {
	Profile
};

/**
 * API to check profile activation status
 *
 * @author Nathaniel Gustafson <natjgus@gmail.com> and Abq Street Art
 *
 **/

//Check the session. If it is not active, start the session.
if(session_status() !== PHP_SESSION_ACTIVE){
	session_start();
}
/**
 * prepare an empty reply
 *
 * Need to create a new stdClass named $reply. A stdClass is an empty container that we can use to store things
 *
 * We wuill use this object named $reply to store the results of the call to our API. The status 200 line adds a state variable
 **/
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;


try {

	//grab the database connection
	$pdo = connectToEncryptedMySQL("/etc/apache2/capstone-mysql/streetart.ini");

	//determine which HTTP method, store the result in $method
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize and store activation token
	//make sure "id" is changed to "activation" on line 5 of .htaccess (why?!) Rochelle did this in her example, Data Design has no .htacces for activation
	$activation = filter_input(INPUT_GET, "activation", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure the activation token is the correct size
	if(strlen($activation) !== 32 ){
		throw(new InvalidArgumentException("activation is not correct length", 405));
	}

	// verify that the activation token is a string value of a hexadecimal
	if(ctype_xdigit($activation) === false) {
		throw (new \InvalidArgumentException("activation is empty or has invalid content", 405));
	}

	// handle The GET HTTP request
	if($method === "GET"){

		// set XSRF Cookie
		setXsrfCookie();
		//find profile associated with the activation token
		$profile = Profile::getProfileByProfileActivationToken($pdo, $activation);

		//verify the profile is not null
		if($profile !== null){

			//make sure the activation token matches
			if($activation === $profile->getProfileActivationToken()) {

				//set activation to null
				$profile->setProfileActivationToken(null);

				//update the profile in the database
				$profile->update($pdo);

				//set the reply for the end user
				$reply->data = "Your profile is activated";
			}
		} else {
			//throw an exception if the activation token does not exist
			throw(new RuntimeException("Profile with this activation code does not exist", 404));
		}
	} else {
		//throw an exception if the HTTP request is not a GET
		throw(new InvalidArgumentException("Invalid HTTP method request", 403));
	}

		//update the reply objects status and message state variables if an exception or type exception was thrown;
} catch (Exception $exception){
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
} catch(TypeError $typeError) {
	$reply->status = $typeError->getCode();
	$reply->message = $typeError->getMessage();
}

//prepare and send the reply
//header("Content-type: application/json");
if($reply->data === null){
	unset($reply->data);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css"
          integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">

    <!-- Font Awesome -->
    <link type="text/css" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
          rel="stylesheet"/>


    <!-- JSON encode the $reply object and console.log it -->
    <script>
        console.log(<?php echo json_encode($reply);?>);
    </script>

    <title>Account Activation | ABQ Street Art</title>
</head>
<body>
<div class="container">
    <div class="jumbotron my-5">
        <h1>ABQ Street Art | Account Activation</h1>
        <hr>
        <p class="lead d-flex">

            <!-- echo the $reply message in a creative way to the front end :D -->
            <?php
//            echo $reply->message . "&nbsp;";
            if($reply->status === 200) {
                echo "<span class=\"align-self-center badge badge-success\">Congratulations! Sign in to bookmark art!</span>";
            } else {
                echo "<span class=\"align-self-center badge badge-danger\">Code:&nbsp;" . $reply->status . "</span>";
            }
            ?>

        </p>
        <div class="mt-4">
            <a class="btn btn-lg" href="https://bootcamp-coders.cnm.edu/~mschmitt5/abq-street-art/public_html/"><i class="fa fa-sign-in"></i>&nbsp;Sign In</a>
        </div>
    </div>
</div>
</body>
</html>


