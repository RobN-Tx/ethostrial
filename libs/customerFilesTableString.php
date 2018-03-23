<?php

//this document contains the customer File Table string creator functions
//I should apologise for my mix of camelCase and under_score notation, but such is the methods of somebody learning!
//trying to stick with camelCase
//updated and commented on 11/13/17

//start with the usual includes
include $_SERVER['DOCUMENT_ROOT'].'/sql/sqlSettings.php';
include $_SERVER['DOCUMENT_ROOT'].'/public/settings.php';

//boot up a session just incase one doesnt exist - it really should but better safe than sorry you know
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

//the goal of this code is to recieve a customer ($extension_company) and to create the html/php/java code to output a accordian table with all of the files for the customers units and sites

//the constructor will be passed the extension_company and if the user can only see one site the extension_site
//if the user is allowed to see all of the sites then the contstructor will be passed 'all'

//so for a simple 1 unit customer we need;
//      Unit
            //->file type 1
            //->file type 2
            //->file type 3 etc
            
//for a complex customer we need:
//  Site 1
    //  unit 1
             //->file type 1
            //->file type 2
            //->file type 3 etc
    // unit 2
        //  etc
//  Site 2
    //  unit1
    //etc




function masterFilesTableMaker($extensionCompany, $extensionSite) //the maqster for this whole function, this is  called in iisstart or return
    {
        $lowerCaseCompany = strtolower($extensionCompany);
       if($lowerCaseCompany == "eelt"){
            $customerListQuerry = "SELECT DISTINCT * FROM customers";
            $customersList = passTheQuerry($customerListQuerry, "no");

            foreach($customersList as $customer){
                $activeCompany = $customer["extension_Company"];
                customerFilesTableMaker($activeCompany, $extensionSite, "yes");
            }
       } else {
        customerFilesTableMaker($extensionCompany, $extensionSite);
    }
    };



function customerFilesTableMaker($extensionCompany, $extensionSite, $eeltUser = "no"){

        
        //if $extension_site = 'all" then list all of the sites for the customer, otherwise just echo the passed site
        if($extensionSite == "all"){
            //now we know we are doing all the sites lets go find them from SQL
            $companySites = FetchFullArraySQLColumnData("extension_Company", $extensionCompany, "extension_Site", "sites");
            

                   
                    //now we have the site lined up lets go do all the units at this site
                    if ($eeltUser =="yes"){

                        /*
                        <h4 data-toggle="collapse" data-target="#demo'.$noSpacesSite.$noSpacesUnits.'">Unit: '.$unit.'</h4>
                        </div><div class="row"></div>';
                    //we will call other things in here - such as unit the doc type
                echo '<div id="demo'.$noSpacesSite.$noSpacesUnits.'" class="collapse">';*/
                    $noSpacesCompany = str_replace(" ", "", $extensionCompany);
                    $noSpacesSite = str_replace(" ", "", $companySites[0]);
                        
                    if (sizeof($companySites)>1){
                        foreach($companySites as $site){
                            $noSpacesSite = str_replace(" ", "", $site);
                            //lets do the top level header for the site
                            echo '<div class="container"> <h3 data-toggle="collapse" data-target="#demo'.$noSpacesCompany.$noSpacesSite.'">'.$extensionCompany.': '.$site.' Site Documents</h3>
                            <div class="collapse" id="demo'.$noSpacesCompany.$noSpacesSite.'" >';
                            //now we have the site lined up lets go do all the units at this site <h4 data-toggle="collapse" data-target="#demo'.$noSpacesSite.$noSpacesUnits.'">Unit: '.$unit.'</h4>
                            unitFilesTableMaker($extensionCompany, $site);
                            //close out the site section/div
                            echo '</div></div>';
                        }
                    } else{


                        echo '<div class="container"> <h3 data-toggle="collapse" data-target="#demo'.$noSpacesCompany.$noSpacesSite.'">'.$extensionCompany.': '.$site.' Site Documents</h3>
                        <div class="collapse" id="demo'.$noSpacesCompany.$noSpacesSite.'" >';
                        //now we have the site lined up lets go do all the units at this site <h4 data-toggle="collapse" data-target="#demo'.$noSpacesSite.$noSpacesUnits.'">Unit: '.$unit.'</h4>
                        unitFilesTableMaker($extensionCompany, $companySites[0]);
                        //close out the site section/div
                        echo '</div></div>';
                    } }else {
                    
                    if (sizeof($companySites)>1){
                        foreach($companySites as $site){
                            //lets do the top level header for the site
                            echo '<div class="container"> <h3>'.$extensionCompany.': '.$site.' Site Documents</h3>
                                <div class="row" >';
                                //now we have the site lined up lets go do all the units at this site
                                unitFilesTableMaker($extensionCompany, $site);
                                //close out the site section/div
                            echo '</div></div>';
                        }
                    } else{
                        echo '<div class="container"> <h3>Document Center</h3>
                        <div class="row" >';

                    unitFilesTableMaker($extensionCompany, $companySites[0]);
                    //close out the site section/div
                    };
                echo '</div></div>';



                }
        } else {
            //if they are only allowed their site
            //echo "nope";
            echo '<div class="container"> <h3>Document Center</h3>
            <div class="row">';
            //now we have the site lined up lets go do all the units at this site
            unitFilesTableMaker($extensionCompany, $extensionSite);
            //close out the site section/div
            echo '</div></div>';
        };
        
        
        
    }

function unitFilesTableMaker($extensionCompany, $extensionSite)
    {
        //thhis bit is to work through all the units of the site for the customer (all passed down)
        $siteUnits = FetchFullArraySQLColumnData("extension_site", $extensionSite, "unit_name", "units");
        foreach($siteUnits as $unit){
            $siteUnitIndexQuerry = "SELECT site_unit_index FROM units WHERE extension_Company = '".$extensionCompany."' AND extension_Site='".$extensionSite."' AND unit_name='".$unit."'" ;
            
            $unitIndexNo = passTheQuerry($siteUnitIndexQuerry, "no")[0]["site_unit_index"]-1;
            
            //echo "<h2>".

            $noSpacesCompany = str_replace(" ", "", $extensionCompany);
            $noSpacesUnit = str_replace(" ", "", $unit);
            //need to change the #demo reference to #demo.$unit or something like that
            echo '  
                    
                    <div class="col-sm-4">
                    <h4 data-toggle="collapse" data-target="#demo'.$noSpacesSite.$noSpacesUnit.'">Unit: '.$unit.'</h4>
                    </div><div class="row"></div>';
                //we will call other things in here - such as unit the doc type
            echo '<div id="demo'.$noSpacesSite.$noSpacesUnit.'" class="collapse">';
            

            fileTypeTableMaker($extensionCompany, $extensionSite, $unitIndexNo);






            echo '</div>';
            
        }
    
    };

function fileTypeTableMaker($extensionCompany, $extensionSite, $activeUnitIndex){

    $fileTypesQuerry = "SELECT * FROM file_settings";
    $fileTypes = passTheQuerry($fileTypesQuerry, "no");
    //echo var_dump($fileTypes);

    foreach ($fileTypes as $fileType){
        $fileType = $fileType["file_type"];
        //$noSpaceFileType = str_replace(" ", "", $fileType["file_type"]);
        //echo $noSpaceFileType.$fileType["file_type"];
        if ($fileType != ""){
        
            $fileListQuerry = "SELECT * FROM files WHERE extension_Company = '".$extensionCompany."' AND extension_Site='".$extensionSite."' AND file_type ='".$fileType."' AND site_unit_index='".$activeUnitIndex."' ORDER BY date_Uploaded DESC" ;
            //echo  $fileListQuerry;
            $fileList = passTheQuerry($fileListQuerry, "no");
            //echo "--->".$fileType."--->".sizeof($fileList);
            
                if (sizeof($fileList)>0){
                    //echo "tis files";
                    $noSpaceFileType = str_replace(" ", "", $fileType);
                   // echo $noSpaceFileType.$fileType["file_type"];
                    $noSpacesSite = str_replace(" ", "", $extensionSite);
                    echo '<div class="row">';
                    echo '<div class="col-sm-6">';
                    echo '  <table class="table table-striped">
                            <thead>
                            <tr data-toggle="collapse" data-target="#demo'.$noSpacesSite.$activeUnitIndex.$noSpaceFileType.'">
                            <th>'.$fileType.'s</th>
                            <th></th>
                            </tr>';
                            //</thead>';
                    echo '<tbody id="demo'.$noSpacesSite.$activeUnitIndex.$noSpaceFileType.'" class="collapse">';
                            
                    //now we have the top of the table
                    //echo '<thead> 
                            echo'<tr><th>File Name</th>
                                <th> Uploaded On</th></tr>
                                </thead>';
                    
                                foreach($fileList as $file){

                                    echo '<tr><td><a href="/blob/'.$file["file_name"].'">'.$file["file_title"].'</td><td>'.date("jS M Y", $file["date_Uploaded"]).'</td></tr>';
                                }
                   
                    //echo var_dump($fileList);
                    
                    echo '</tbody></table></div></div>';








                } else {
                    //echo "tisnt files";
                }
        //echo "<br><br>".$fileType;
        
        };
    }

    $columns = fetchTableColumnHeaders("files");
    //echo var_dump($columns);

};






    




?>