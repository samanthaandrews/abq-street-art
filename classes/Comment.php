<?php
namespace Edu\Cnm\ABQ-STREET-ART;

require_once("autoload.php");
require_once(dirname(__DIR__, 2) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * This is the cross section for the Comment class of ABQ Street Art
 *
 * The Comment class of ABQ Street Art FINISH THIS DOC BLOCK
 *
 * @author Dylan McDonald <dmcdonald21@cnm.edu>
 * @author Nathaniel Gustafson <natjgus@gmail.com>
 * @version 3.0.0
 **/

class Comment implements \JsonSerializable  {
	use ValidateUuid;
	/**
	 * id for this Comment; this is the primary key
	 * @var Uuid $commentId
	 **/
	private $commentId;
	/**
	 * id for the Art piece that the comment was made on; this is a foreign key
	 * @var Uuid $commentArtId
	 **/
	private $commentArtId;
	/**
	 * id for the Profile that made the comment; this is a foreign key
	 * @var Uuid $ProfileArtId
	 **/
}


<h3>Comment</h3>
		<ul>
			<li>commentId *primary key</li>
			<li>commentArtId *foreign key</li>
			<li>commentProfileId *foreign key</li>
			<li>commentContent</li>
			<li>commentDateTime</li>
		</ul>