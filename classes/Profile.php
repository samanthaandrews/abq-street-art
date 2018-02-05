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

    /**
     *accessor method for profile email
     * @return string value of profile email
     **/
    public function getProfileEmail(): string {
        return ($this->profileEmail);
    }

    /**
     * mutator method for profile email
     *
     * @param string $newProfileEmail new value of profile email
     * @throws \RangeException if $newProfileEmail is > 120 characters
     * @throws \TypeError if $newProfileEmail is not a string
     * @throws \InvalidArgumentException if $newProfileEmail is invalid or insecure
     **/
    public function setProfileEmail($newProfileEmail): void {
        $newProfileEmail = trim($newProfileEmail);
        $newProfileEmail = filter_var($newProfileEmail, FILTER_SANITIZE_EMAIL);
        if (empty($newProfileEmail) === true) {
            throw(new \InvalidArgumentException("profile email is empty or insecure"));
        }
        if (strlen($newProfileEmail) > 128) {
            throw(new \RangeException("profile email is too large"));
        }
        $this->profileEmail = $newProfileEmail;
    }


    /**
     * accessor method for profileHash
     * @return string value of profile hash
     **/
    public function getProfileHash() : string {
        return ($this->profileHash);
    }

    /**
     * mutator method for profile hash
     *
     * @param $newProfileHash
     * @throws \InvalidArgumentException if the hash is not secure
     * @throws \RangeException if the hash is not 128 characters
     * @throws \TypeError if profile hash is not a string
     **/
    public function setProfileHash(string $newProfileHash): void {
        $newProfileHash = trim($newProfileHash);
        $newProfileHash = strtolower($newProfileHash);
        if (empty($newProfileHash) === true) {
            throw(new \InvalidArgumentException("profile hash empty or insecure"));
        }
        if (!ctype_xdigit($newProfileHash)) {
            throw(new \RangeException("profile hash must be 128 characters"));
        }
        $this->profileHash = $newProfileHash;
    }


    /**
     *accessor method for profile salt
     *
     * @return string value of the salt
     **/
    public function getProfileSalt(): string {
        return $this->profileSalt;
    }

    /**
     * mutator method for profile salt
     *
     * @param string $newProfileSalt
     * @throws \InvalidArgumentException if the salt is not secure
     * @throws \RangeException if the salt is not 64 characters
     * @throws \TypeError if the profile salt is not a string
     **/
    public function setProfileSalt(string $newProfileSalt): void {
        $newProfileSalt = trim($newProfileSalt);
        $newProfileSalt = strtolower($newProfileSalt);
        if (!ctype_xdigit($newProfileSalt)) {
            throw(new \InvalidArgumentException("profile password salt is empty or insecure"));
        }
        if (strlen($newProfileSalt) !== 64) {
            throw(new \RangeException("profile salt must be 128 characters"));
        }
        $this->profileSalt = $newProfileSalt;
    }