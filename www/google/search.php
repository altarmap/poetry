<?php
setcookie($cookiename, NULL); //²M°£cookie
session_start();
?>
<form method="post" action="google2.php">
<label for="searchquery"><span class="caption">Search this site</span> <input type="text" size="20" maxlength="255" title="Enter your keywords and click the search button" name="searchquery" /></label> <input type="submit" value="Search" />
</form>

<?php
if(!empty($_SESSION['googleresults']))
{
echo $_SESSION['googleresults'];
unset($_SESSION['googleresults']);
}

?>