
<?php

include $_SERVER['DOCUMENT_ROOT'].'/libs/customerFilesTableString.php';

/*
echo "hello world<br>";

echo var_dump(get_object_vars($dude));

echo "<br>"."why no data here ---><br>".$dude->unitsAtSite;

echo "<br>";
echo "<br>";

*/
echo '<div class="col-sm-6"><h3> Welcome to iCon. </h3></div>';
echo '<div class="col-sm-6">';
echo strtolower();
$tableString = masterFilesTableMaker($dude->userCompany,"all");
echo '</div>';
?>

