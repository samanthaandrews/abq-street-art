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

/**
 * constructor will go here
 **/


    /**
     * accessor method for profile ID
     * @return Uuid value of profile ID
     **/
    public function getProfileId(): Uuid {
        return $this->profileId;
    }

    /**
     * mutator method for profile ID
     *
     * @param Uuid|string $newProfileId new value of profile ID
     * @throws \RangeException if $newProfileId is not positive
     * @throws \TypeError if $newProfileId is not a uuid or string
     **/
    public function setProfileId($newProfileId): void {
        try {
            $uuid = self::validateUuid($newProfileId);
        } catch (\InvalidArgumentException | \ RangeException | \Exception | \TypeError $exception) {
            $exceptionType = get_class($exception);
            throw(new $exceptionType($exception->getMessage(), 0, $exception));
        }
        /**
         * convert and store the profile ID
         **/
        $this->profileId = $uuid;
    }


    /**
     * accessor method for profile activation token
     * @return string value of profile ID
     **/
    public function getProfileActivationToken(): string {
        return $this->profileActivationToken;
    }

    /**
     * mutator method for profile activation token
     *
     * @param string $newProfileActivationToken new value of profile activation token
     *
     * @throws \InvalidArgumentException if $newProfileActivationToken is invalid or insecure
     * @throws \RangeException if $newProfileActivationToken is > 32 characters
     * @throws \TypeError if $newProfileActivationToken is not a string
     **/
    public function setProfileActivationToken (?string $newProfileActivationToken) : void {
        if($newProfileActivationToken === null){
            $this->profileActivationToken = null;
            return;
        }
        $newProfileActivationToken = strtolower(trim($newProfileActivationToken));
        if(ctype_xdigit($newProfileActivationToken) === false) {
            throw(new\RangeException("profile activation is not valid"));
        }
        //make sure activation token is only 32 characters
        if(strlen($newProfileActivationToken) !== 32) {
            throw(new\RangeException("user activation token has to be 32 characters"));
        }
        $this->profileActivationToken = $newProfileActivationToken;
    }
