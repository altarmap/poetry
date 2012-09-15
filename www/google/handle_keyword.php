<?php
setcookie($cookiename, NULL); //²M°£cookie
session_start();
?>
<form method="post" action="getpage.php">
<label for="searchquery"><span class="caption">handle this keyword</span> <input type="text" size="20" maxlength="255" title="Enter your keywords and click the 'do it' button" name="searchquery" /></label> <input type="submit" value=" do it "/>
</form>

<?php
if(!empty($_SESSION['googleresults']))
{
echo $_SESSION['googleresults'];
unset($_SESSION['googleresults']);
}

?>