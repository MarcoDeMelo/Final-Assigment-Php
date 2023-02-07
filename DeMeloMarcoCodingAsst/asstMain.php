<?php
// http://localhost/DeMeloMarcoCodingAsst/asstmain.php 
require_once("asstinclude.php");
require_once("clsDeleteSunglassRecord.php");
function DisplayMainForm()
{
	echo "<p> Welcome to the Main page please click one of the buttons </p>";
  echo"<form action = ? method=post>"; //Form action reloads page and method post saves the variables Eg: f_CreateTable
	DisplayButton('f_CreateTable',"Create Table","createTable.png","Create Table");
	DisplayButton('f_AddRecord',"Add Record","addRecord.png", "Add Record");
	DisplayButton("f_DeleteRecord","Delete Record","deleteRecord.png", "Delete Record");
	DisplayButton("f_DisplayData","Display Data","displayData.png", "Display Data");
  
}
function DropTable(&$mysqlObj, $TableName)
{
    $success = $mysqlObj->prepare("drop table if exists $TableName;");
    $success ->execute();
    return $success;
}
function createTableForm(&$mysqlObj, $TableName)
{
   DropTable($mysqlObj, $TableName);
    $success = $mysqlObj->prepare("Create Table $TableName(BrandName varchar(10), DateManufactured date, CameraMp int, Colour varchar(15))");
    $success ->execute();
    if($success)
        echo "Created " . $TableName . " successfully.<br>";
    else
      "Unable to create " . $TableName;

    echo"<form action = ? method=post>";
    DisplayButton("f_Home","Home","home.png","home");
    echo "</form>";
    return $success;
        
}
function addRecordForm(&$mysqlObj, $TableName)
{

    
   echo"<form action = ? method=post>";
    echo"<div class = \"datapair\">";
    DisplayLabel("Brand Name: ");
    DisplayTextbox("f_BrandName",12,"text","");
    echo"</div>";
    echo"<div class = \"datapair\">";
    DisplayLabel("Date Manufactured: ");
    DisplayTextbox("f_DateManufactured",12,"date",'Y-m-d');
    echo"</div>";
    echo"<div class = \"datapair\">";
    DisplayLabel("Camera: ");
    DisplayLabel("5MP");
    echo"<input type = radio name = f_Camera size = 12 value = 5 checked>";
    DisplayLabel(" 10MP");
    echo"<input type = radio name = f_Camera size = 12 value = 10>";
    echo"</div>";
    echo"<div class = \"datapair\">";
    DisplayLabel("Colour: ");
    DisplayTextbox("f_Colour",12,"color");
    echo"</div>";
    DisplayButton("f_Save","Save Record","saveRecord.png","Save Record");
    DisplayButton("f_Home","Home","home.png","home");
    echo "</form>";
  

   
}
Function SaveRecordToTableForm(&$mysqlObj, $TableName)
{
  echo"<form action = ? method=post>";
  $brandName = $_POST["f_BrandName"];
  $dateManufactured = $_POST["f_DateManufactured"];
  $camera= $_POST["f_Camera"];
  $color = $_POST["f_Colour"];
     $query = "Insert into $TableName (BrandName, DateManufactured, CameraMp, Colour) Values (?,?,?,?)";
     $stmtObj = $mysqlObj ->prepare($query);
     $BindSuccess = $stmtObj ->bind_param("ssis", $brandName, $dateManufactured, $camera, $color);
     if ($BindSuccess)
       $sucess = $stmtObj -> execute();
     else 
         echo"Bind Failed: " . $stmtObj -> error;
     if ($sucess)
         echo $mysqlObj->affected_rows . " recored added.";
     else 
         echo $mysqlObj->error;
         $stmtObj->close();
  
    echo"<div class = \"datapair\">";
   DisplayButton("f_Home","Home","home.png","home");
   echo "</form>";
   echo"</div>";
}

Function DisplayDataForm(&$mysqlObj, $TableName)
{
  echo"<form action = ? method=post>";
  DisplayLabel("Displaying Data Here");
  $stmtObj = $mysqlObj->prepare("select BrandName, DateManufactured, CameraMP, Colour from $TableName;");
  $stmtObj->execute();
  $stmtObj->bind_result($brandName, $dateManufactured, $camera , $color);
  while($stmtObj->fetch()) {
    echo"<div class = \"datapair\">";
    echo"BrandName: $brandName " . "DateManufactured: $dateManufactured " . "CameraMP: $camera ". " Colour:  <input type = color name = $color size = 8 value = $color disabled <br>";
    echo"</div>";
  };
echo"<div class = \"datapair\">";
    DisplayButton("f_Home","Home","home.png","home");
    echo"</div>";
  echo "</form>";
}

Function DeleteRecordForm(&$mysqlObj,$TableName)
{
 
  echo"<form action = ? method=post>";
  echo"<div class = \"datapair\">";
    DisplayLabel("Insert Brand you want to delete: ");
    DisplayTextbox("f_BrandDelete",12,"text","");
    echo"</div>";
    echo"<div class = \"datapair\">";
    DisplayLabel("Warning!!! Deleting is final");
    echo"</div>";
    echo"<div class = \"datapair\">";
    DisplayButton("f_IssueDelete","Delete!","delete!.png","Delete!");
    DisplayButton("f_Home","Home","home.png","home");
    echo"</div>";
    echo "</form>";
}

function issueDeleteForm (&$mysqlObj,$TableName)
{
  echo"<form action = ? method=post>";
  $brandDelete = $_POST["f_BrandDelete"];
  $deleteRecord = new clsDeleteSunglassRecord();
  $worked = $deleteRecord ->deleteTheRecord($mysqlObj,$TableName, $brandDelete);
  if($worked === 0)
  {
   echo"<p> $brandDelete record does not exist";
  }
  else
  {
    echo"<p> $brandDelete record deleted </p>";
  }
   echo"<div class = \"datapair\">";
   DisplayButton("f_Home","Home","home.png","home");
   echo"</div>";
   echo "</form>";
   
}




// main
date_default_timezone_set ('America/Toronto');
$mysqlObj = CreateConnectionObject();
$TableName = "Sunglasses"; 

// writeHeaders call  
WriteHeaders("Sunglases","Marco De Melo");
if (isset($_POST['f_CreateTable']))
  createTableForm($mysqlObj,$TableName);
else if (isset($_POST['f_Save'])) saveRecordtoTableForm($mysqlObj,$TableName) ;
   else if (isset($_POST['f_AddRecord'])) addRecordForm($mysqlObj,$TableName) ;	   
	  else if (isset($_POST['f_DeleteRecord'])) deleteRecordForm($mysqlObj,$TableName) ;	 
      else if (isset($_POST['f_DisplayData'])) displayDataForm ($mysqlObj,$TableName);
    		else if (isset($_POST['f_IssueDelete'])) issueDeleteForm ($mysqlObj,$TableName);
		        else displayMainForm();
if (isset($mysqlObj)) $mysqlObj->close();
writeFooters();


?>