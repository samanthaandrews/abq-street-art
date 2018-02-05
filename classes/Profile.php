<?php
/**
 * Created by PhpStorm.
 * User: schmi
 * Date: 2/5/2018
 * Time: 6:33 AM
 **/

namespace Edu\Cnm\AbqStreetArt;

require_once ("autoload.php");
require_once (dirname(__DIR__) . "classes/autoload.php");

//use function Couchbase\passthruEncoder;
//use Edu\Cnm\AbqStreetArt\ValidateUuid;
//use Ramsey\Uuid\Uuid;

/**
 * Profile class for ABQ Street Art.
 *
 *
 * @author Mary MacMillan <mschmitt5@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 *
 **/

class Profile implements \JsonSerializable {
    use ValidateUuid;

    /**
     * ID for this profile. This will be the primary key.
     * @var Uuid $profileId
     **/
    private $profileId;

    /**
     * activation token for the profile
     * @var string $profileActivationToken
     **/
    private $profileActivationToken;

    /**
     * email for the profile
     * @var string $profileEmail
     **/
    private $profileEmail;

    /**
     * hash for validating password
     * @var $profileHash
     **/
    private $profileHash;

    /**
     * salt for validating password
     * @var $profileSalt
     **/
    private $profileSalt;

    /**
     * Name attached to this profile.
     * @var string $profileUserName
     **/
    private $profileUserName;

