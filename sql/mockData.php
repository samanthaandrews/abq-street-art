<?php

$VALID_ARTADDRESS = "3900 Artsy Fartsy Lane NE";

$VALID_ARTADDRESS2 = "this is still a valid address for this art";

$VALID_ARTARTIST = "Mr. Art Man";

$VALID_ARTIMAGEURL = "http://artsyfartsy.org";

$VALID_ARTLAT = 35.0931;

 $VALID_ARTLOCATION = "in the alley where everyone pees";

 $VALID_ARTLONG = -106.6641772;

 $VALID_ARTTITLE = "A Very Nice ART!";

 $VALID_ARTTYPE = "mural";

 $VALID_ARTYEAR = 1992;


$numRows = $this->getConnection()->getRowCount("art");
// create a new Art and insert to into mySQL
$artId = generateUuidV4();
$art = new Art($artId, $this->VALID_ARTADDRESS, $this->VALID_ARTARTIST, $this->VALID_ARTIMAGEURL, $this->VALID_ARTLAT, $this->VALID_ARTLOCATION, $this->VALID_ARTLONG, $this->VALID_ARTTITLE, $this->VALID_ARTTYPE, $this->VALID_ARTYEAR);
$art->insert($this->getPDO());

$numRows = $this->getConnection()->getRowCount("art");
// create a new Art and insert to into mySQL
$artId = generateUuidV4();
$art = new Art($artId, $this->VALID_ARTADDRESS, $this->VALID_ARTARTIST, $this->VALID_ARTIMAGEURL, $this->VALID_ARTLAT, $this->VALID_ARTLOCATION, $this->VALID_ARTLONG, $this->VALID_ARTTITLE, $this->VALID_ARTTYPE, $this->VALID_ARTYEAR);
$art->insert($this->getPDO());

$numRows = $this->getConnection()->getRowCount("art");
// create a new Art and insert to into mySQL
$artId = generateUuidV4();
$art = new Art($artId, $this->VALID_ARTADDRESS, $this->VALID_ARTARTIST, $this->VALID_ARTIMAGEURL, $this->VALID_ARTLAT, $this->VALID_ARTLOCATION, $this->VALID_ARTLONG, $this->VALID_ARTTITLE, $this->VALID_ARTTYPE, $this->VALID_ARTYEAR);
$art->insert($this->getPDO());