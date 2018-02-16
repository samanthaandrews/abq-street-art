<?php

namespace Edu\Cnm\AbqStreetArt;


//Not sure what else would need to be required for the data-downloader
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
	 * url: http://coagisweb.cabq.gov/arcgis/rest/services/public/PublicArt/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=*&returnGeometry=true&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&f=pjson
	 **/

	/**
	 * Gets the etag from a file url
	 **/


}