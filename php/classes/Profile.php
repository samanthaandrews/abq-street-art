<?php
/**
 * Created by PhpStorm.
 * User: schmi
 * Date: 2/5/2018
 * Time: 6:33 AM
 **/

namespace Edu\Cnm\AbqStreetArt;

require_once ("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");


use Edu\Cnm\AbqStreetArt\ValidateUuid;
use Ramsey\Uuid\Uuid;

/**
 * Profile class for ABQ Street Art.
 *
 *
 * @author Mary MacMillan <mschmitt5@cnm.edu>
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 *
 **/

class Profile implements \JsonSerializable
{
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
     * constructor for this profile
     *
     * @param string|Uuid $newProfileId
     * @param string $newProfileActivationToken
     * @param string $newProfileEmail
     * @param string $newProfileHash
     * @param string $newProfileSalt
     * @param string $newProfileUserName
     *
     * @throws \InvalidArgumentException if data types are not valid or are insecure
     * @throws \RangeException if data values are out of bounds
     * @throws \Exception if some other exception occurs
     * @throws \TypeError if a data type violates a data hint
     **/
    public function __construct($newProfileId, ?string $newProfileActivationToken, string $newProfileEmail, string $newProfileHash, string $newProfileSalt, string $newProfileUserName)
    {
        try {
            $this->setProfileId($newProfileId);
            $this->setProfileActivationToken($newProfileActivationToken);
            $this->setProfileEmail($newProfileEmail);
            $this->setProfileHash($newProfileHash);
            $this->setProfileSalt($newProfileSalt);
            $this->setProfileUserName($newProfileUserName);
        } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            //to determine what exception type was thrown
            $exceptionType = get_class($exception);
            throw (new $exceptionType($exception->getMessage(), 0, $exception));
        }
    }

    /**
     * accessor method for profile ID
     * @return Uuid value of profile ID
     **/
    public function getProfileId(): Uuid
    {
        return $this->profileId;
    }

    /**
     * mutator method for profile ID
     *
     * @param Uuid|string $newProfileId new value of profile ID
     * @throws \RangeException if $newProfileId is not positive
     * @throws \TypeError if $newProfileId is not a uuid or string
     **/
    public function setProfileId($newProfileId): void
    {
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
     * @return string or null value of profile activation token
     **/
    public function getProfileActivationToken(): ?string
    {
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
    public function setProfileActivationToken(?string $newProfileActivationToken): void
    {
        if ($newProfileActivationToken === null) {
            $this->profileActivationToken = null;
            return;
        }
        $newProfileActivationToken = strtolower(trim($newProfileActivationToken));
        if (ctype_xdigit($newProfileActivationToken) === false) {
            throw(new\RangeException("profile activation is not valid"));
        }
        //make sure activation token is only 32 characters
        if (strlen($newProfileActivationToken) !== 32) {
            throw(new\RangeException("user activation token has to be 32 characters"));
        }
        $this->profileActivationToken = $newProfileActivationToken;
    }

    /**
     *accessor method for profile email
     * @return string value of profile email
     **/
    public function getProfileEmail(): string
    {
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
    public function setProfileEmail($newProfileEmail): void
    {
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
    public function getProfileHash(): string
    {
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
    public function setProfileHash(string $newProfileHash): void
    {
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
    public function getProfileSalt(): string
    {
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
    public function setProfileSalt(string $newProfileSalt): void
    {
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

    /**
     * accessor method for profile user name
     *
     * @return string value of profile user name
     **/
    public function getProfileUserName(): string
    {
        return ($this->profileUserName);
    }

    /**
     * mutator method for profile user name
     *
     * @param string $newProfileUserName new value of profile user name
     * @throws \InvalidArgumentException if $newProfileUserName is not a string or is insecure
     * @throws \TypeError if $newProfileUserName is not a string
     * @throws \RangeException if $newProfileUserName is > 32 characters
     **/
    public function setProfileUserName(string $newProfileUserName): void
    {
        $newProfileUserName = trim($newProfileUserName);
        $newProfileUserName = filter_var($newProfileUserName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        if (empty($newProfileUserName) === true) {
            throw(new \InvalidArgumentException("profile user name is empty or insecure"));
        }

        if (strlen($newProfileUserName) > 32) {
            throw(new \RangeException("profile user name is too large"));
        }
        $this->profileUserName = $newProfileUserName;
    }


    /**
     * inserts this profile into mySQL
     *
     * @param \PDO $pdo PDO connection object
     *
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError if $pdo is not a PDO connection object
     **/
    public function insert(\PDO $pdo): void
    {

        //create query
        $query = "INSERT INTO profile(profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUserName) VALUES(:profileId, :profileActivationToken, :profileEmail, :profileHash, :profileSalt,  :profileUserName)";
        $statement = $pdo->prepare($query);

        //bind the member variables to the place holders in the template
        $parameters = ["profileId" => $this->profileId->getBytes(), "profileEmail" => $this->profileEmail, "profileActivationToken" => $this->profileActivationToken, "profileHash" => $this->profileHash, "profileSalt" => $this->profileSalt, "profileUserName" => $this->profileUserName];
        $statement->execute($parameters);
    }

    /**
     * deletes this profile in mySQL
     *
     * @param \PDO $pdo PDO connection object
     *
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError if $pdo is not a PDO connection object
     **/
    public function delete(\PDO $pdo): void
    {

        //create query template
        $query = "DELETE FROM profile WHERE profileId = :profileId";
        $statement = $pdo->prepare($query);

        //bind the member variables to the place holder in the template
        $parameters = ["profileId" => $this->profileId->getBytes()];
        $statement->execute($parameters);
    }


    /**
     * updates this profile in mySQL
     *
     * @param \PDO $pdo PDO connection object
     *
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError if $pdo is not a PDO connection object
     **/
    public function update(\PDO $pdo): void
    {

        //create query template
        $query = "UPDATE profile SET profileActivationToken = :profileActivationToken, profileEmail = :profileEmail, profileHash = :profileHash, profileSalt = :profileSalt, profileUserName = :profileUserName WHERE profileId = :profileId";
        $statement = $pdo->prepare($query);

        $parameters = ["profileId" => $this->profileId->getBytes(), "profileActivationToken" => $this->profileActivationToken, "profileEmail" => $this->profileEmail, "profileHash" => $this->profileHash, "profileSalt" => $this->profileSalt, "profileUserName" => $this->profileUserName];
        $statement->execute($parameters);
    }


    /**
     * gets profile by profileId
     *
     * @param \PDO $pdo PDO connection object
     * @param Uuid|string $profileId profile ID to search for
     *
     * @return Profile|null profile found or null if not found
     *
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when a variable is not the correct data type
     **/
    public static function getProfileByProfileId(\PDO $pdo, $profileId): ?Profile
    {

        //sanitize the profileId before searching
        try {
            $profileId = self::validateUuid($profileId);
        } catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
            throw (new \PDOException($exception->getMessage(), 0, $exception));
        }

        //create query template
        $query = "SELECT profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUserName FROM profile WHERE profileId = :profileId";
        $statement = $pdo->prepare($query);

        //bind the profile id to the place holder in the template
        $parameters = ["profileId" => $profileId->getBytes()];
        $statement->execute($parameters);

        //grab the profile from mySQL
        try {
            $profile = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if ($row !== false) {
                $profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"], $row["profileUserName"]);
            }
        } catch (\Exception $exception) {
            //if the row couldn't be converted, rethrow it
            throw (new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($profile);
    }


    /**
     * gets profile by profileActivationToken
     *
     * @param \PDO $pdo PDO connection object
     * @param string $profileActivationToken profile activation token to search for
     *
     * @return Profile|null profile found or null if not found
     *
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when a variable is not the correct data type
     **/
    public static function getProfileByProfileActivationToken(\PDO $pdo, $profileActivationToken): ?Profile
    {

        //make sure activation token is in the right format and that it is a string representation of a hexadecimal
        $profileActivationToken = trim($profileActivationToken);
        if (ctype_xdigit($profileActivationToken) === false) {
            throw(new \InvalidArgumentException("profile activation token is empty or in the wrong format"));
        }

        //create query template
        $query = "SELECT profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUserName FROM profile WHERE profileActivationToken = :profileActivationToken";
        $statement = $pdo->prepare($query);

        //bind the profile activation token to the place holder in the template
        $parameters = ["profileActivationToken" => $profileActivationToken];
        $statement->execute($parameters);

        //grab the profile from mySQL
        try {
            $profile = null;
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            if ($row !== false) {
                $profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"], $row["profileUserName"]);
            }
        } catch (\Exception $exception) {
            //if the row couldn't be converted, rethrow it
            throw (new \PDOException($exception->getMessage(), 0, $exception));
        }
        return ($profile);
    }

    /**
     * gets profile by profile user name
     *
     * @param \PDO $pdo PDO connection object
     * @param string $profileUserName profile name to search for
     *
     * @return \SplFixedArray SplFixedArray of profiles found
     *
     * @throws \PDOException when mySQL related errors occur
     * @throws \TypeError when variables are not the correct data type
     **/
    public static function getProfileByProfileUserName(\PDO $pdo, string $profileUserName): \SplFixedArray
    {
        //sanitize the name before searching
        $profileUserName = trim($profileUserName);
        $profileUserName = filter_var($profileUserName, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        if (empty($profileUserName) === true) {
            throw (new \PDOException("profile user name is invalid"));
        }
        //escape any mySQL wild cards
        $profileUserName = str_replace("_", "\\_", str_replace("%", "\\%", $profileUserName));

        //create query template
        $query = "SELECT profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUserName FROM profile WHERE profileUserName LIKE :profileUserName";
        $statement = $pdo->prepare($query);

        //bind the profile user name to the place holder in the template
        $profileUserName = "%$profileUserName%";
        $parameters = ["profileUserName" => $profileUserName];
        $statement->execute($parameters);

        //build an array of profiles
        $profiles = new \SplFixedArray($statement->rowCount());
        $statement->setFetchMode(\PDO::FETCH_ASSOC);
        while (($row = $statement->fetch()) !== false) {
            try {

                $profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"], $row["profileUserName"]);
                $profiles[$profiles->key()] = $profile;
                $profiles->next();
            } catch (\Exception $exception) {
                //if the row couldn't be converted, rethrow it
                throw (new \PDOException($exception->getMessage(), 0, $exception));
            }
        }
        return ($profiles);
    }

	/**
	 * gets profile by email
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $profileEmail profile email to search for
	 *
	 * @return Profile|null profile or null if no profiles found
	 *
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
    public static function getProfileByProfileEmail(\PDO $pdo, string $profileEmail) : ?Profile
    {
        //sanitize the name before searching
        $profileEmail = trim($profileEmail);
        $profileEmail = filter_var($profileEmail, FILTER_SANITIZE_EMAIL);
        if (empty($profileEmail) === true) {
            throw (new \PDOException("profile email is invalid"));
        }
        //escape any mySQL wild cards
        $profileEmail = str_replace("_", "\\_", str_replace("%", "\\%", $profileEmail));

        //create query template
        $query = "SELECT profileId, profileActivationToken, profileEmail, profileHash, profileSalt, profileUserName FROM profile WHERE profileEmail LIKE :profileEmail";
        $statement = $pdo->prepare($query);

        //bind the profile user name to the place holder in the template
        $profileEmail = "%$profileEmail%";
        $parameters = ["profileEmail" => $profileEmail];
        $statement->execute($parameters);

        //build an array of emails
        //$profiles = new \SplFixedArray($statement->rowCount());
        //$statement->setFetchMode(\PDO::FETCH_ASSOC);
        //while (($row = $statement->fetch()) !== false) {
            try  {
					$profile = null;
					$statement->setFetchMode(\PDO::FETCH_ASSOC);
					$row = $statement->fetch();
					if ($row !== false) {
						$profile = new Profile($row["profileId"], $row["profileActivationToken"], $row["profileEmail"], $row["profileHash"], $row["profileSalt"], $row["profileUserName"]);
					}
				} catch (\Exception $exception) {
					//if the row couldn't be converted, rethrow it
					throw (new \PDOException($exception->getMessage(), 0, $exception));
				}
			  return ($profile);
    }

    /**
     *formats the state variables for JSON serialization
     *
     * @return array resulting state variables to serialize
     **/
    public function jsonSerialize()
    {
        $fields = get_object_vars($this);
        $fields["profileId"] = $this->profileId->toString();
        unset($fields["profileHash"]);
        unset($fields["profileSalt"]);
        return ($fields);
    }
}