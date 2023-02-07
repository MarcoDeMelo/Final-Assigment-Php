<?php
function WriteHeaders($Heading="Welcome",$TitleBar="MySite")
{
    echo "
        <!doctype html> 
        <html lang = \"en\">
        <head>
            <meta charset = \"UTF-8\">
            <title>$TitleBar</title>\n
            <link rel =\"stylesheet\" type = \"text/css\" href=\"asstStyle.css\"/>

        </head>
        <body>\n  
	    <h1>$Heading - Marco De Melo</h1>";
        
}
function WriteFooters()
{
    echo "</body>";
    echo"<footer>";
    DisplayContactInfo();
    echo"</footer>";
    echo "</html>";  
}
function DisplayLabel($label)
{
    echo"<label>$label</label>";
}
function DisplayTextbox($name, $size, $type = "text", $value = 0)
{
    echo "<input type = $type name = $name size = $size value = $value>";
}
function DisplayContactInfo()
{
    echo"Questions? Comments? Contact me at 
    <a href=\"mailto:marco.demelo2@student.sl.on.ca\">marco.demelo2@student.sl.on.ca</a>";
}
function DisplayImage($filename, $alt, $height = 40, $width = 70)
{
    echo"<img src = $filename height = $height width = $width alt= $alt>";
}
function DisplayButton($name, $text, $filename = "", $alt = "")
{
    if (!$filename == "")
    {
        echo"<button type = Submit name = $name>";
        DisplayImage($filename, $alt);
        echo" </button>";
    }
    else 
    {
        echo"<button type=Submit name= $name>$text</button>";
    }
}
function CreateConnectionObject()
{
    $fh = fopen("auth.txt", "r");
    $Host = trim(fgets($fh));
    $UserName = trim(fgets($fh));
    $Password = trim(fgets($fh));
    $Database = trim(fgets($fh));
    $Port = trim(fgets($fh));
    fclose($fh);
    $mysqlObj = new mysqli($Host, $UserName, $Password, $Database, $Port);
    if($mysqlObj->connect_errno != 0)
    {
        echo "<p>Connection failed. Unable to open database $Database. Error: "
        . $mysqlObj->connect_error . "</p>";
        exit;
    }
    return ($mysqlObj);
}

?>

