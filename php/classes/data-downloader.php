<?php

namespace Edu\Cnm\AbqStreetArt;


//TODO Not sure what else would need to be required for the data-downloader
require_once("autoload.php");
require_once("/etc/apache2/capstone-mysql/encrypted-config.php");

/**
 * This class will download data from the City of Albuquerque City Data Database.
 *
 * @author Nathaniel Gustafson <natjgus@gmail.com>
 **/
class DataDownloader {

	/**
	 * Art data (this url has been amended to reflect the correct and precise lat/long coordinates):
	 * art: http://coagisweb.cabq.gov/arcgis/rest/services/public/PublicArt/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=*&returnGeometry=true&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&f=pjson
	 **/

	/**
	 * Gets the eTag from a file url
	 *
	 * @param string $url to grab from
	 * @return string $eTag to be compared to previous eTag to determine last download
	 * @throws \RuntimeException if stream cant be opened.
	 **/

	public static function getMetaData(string $url, string $eTag) {
		if($eTag !== "art") {
			throw(new \RuntimeException("not a valid etag", 400));
		}

		$options = [];
		$options['http'] = [];
		//TODO How can you set the array of two strings like this? I need clarification on this line
		$options["http"]["method"] = "HEAD";
		$context = stream_context_create($options);
		$fd = fopen($url, "rb", false, $context);
		$metaData = stream_get_meta_data($fd);
		if($fd === false) {
			throw(new \RuntimeException("unable to open HTTP stream"));
		}

		// TODO need to look up fclose when I have internet
		fclose($fd);
		$header = $metaData["wrapper_data"];
		$eTag = null;
		foreach($header as $value) {
			//TODO what does explode do?
			$explodeETag = explode(":", $value);
			$findETag = array_search("ETag", $explodeETag);
			if($findETag !== false) {
				$eTag = $explodeETag[1];
			}
		}

		if($eTag === null) {
			throw(new \RuntimeException("etag cannot be found", 404));
		}

		//TODO find right ini file name
		$config = readConfig("/etc/apache2/capstone-mysql/AbqStreetArt.ini");
		$eTags = json_decode($config["etags"]);
		/**
		 * TODO this is the code from abquery. They have to databases to load... which is why they have this line of code. Do we need to check that the previousETag < eTag??
		 *
		 * $previousETag = $eTags->$whichETag;
		 * if($previousETag < $eTag) {
		 * return ($eTag);
		 * } else {
		 * throw(new \OutOfBoundsException("same old story, same old song and dance", 401));
		 * }
		 **/
	}
}

/**
 *
 * Decodes Json file, converts to string, sifts through the string and inserts the data into database
 *
 * @param string $url
 * @throws \PDOException for PDO related errors
 * @throws \Exception catch-all exceptions
 * @return \SplFixedArray $allData
 *
 **/

public static function readDataJson($url){

	// http://php.net/manual/en/function.stream-context-create.php creates a stream for file input
	$context = stream_context_create(["http" => ["ignore_errors" => true, "method" => "GET"]]);
	try{
		// http://php.net/manual/en/function.file-get-contents.php file-get-contents returns file in string context
		if(($jsonData = file_get_contents($url, null, $context)) === false) {
			throw(new \RuntimeException("cannot connect to city server"));
		}

		//decode the Json file
		$jsonConverted = json_decode($jsonData);

		//format
		$jsonFeatures = $jsonConverted->features;

		//create array from the converted Json file
		$features = \SplFixedArray::fromArray($jsonFeatures);

	} catch(\Exception $exception) {
		throw(new \PDOException($exception->getMessage(), 0, $exception));
	}
	return ($features);
}