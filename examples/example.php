<?php
// Include the class
require("../library/LBHToolkit/vCard/Generator.php");

$vcard = new LBHToolkit_vCard_Generator();

// Name, requires a little knowledge of vCard. 
$vcard->formatted_name = "Kevin Hallmark";
$vard->name = 'Hallmark,Kevin';

//Company nae
$vcard->company = "Little Black Hat";

// Assign an address
$vcard->work_address = "123 Fake St";
$vcard->work_city = 'Orlando';
$vcard->work_state = 'FL';
$vcard->work_zip = "12345";

$vcard->work_phone  = '555-555-5555';

$vcard->note = "Kevin is a nice guy.";

header("Content-type: text/directory");
header("Content-Disposition: attachment; filename=KevinHallmark.vcf");
header("Pragma: public");

echo $vcard->create();