<?php
require_once("header2.php");
echo "<script>";

echo		"function removeSearch() {";
echo			"document.getElementById('tfheader').style.visibility = 'hidden';";

echo		"}";

echo "</script>";

if(isset($_REQUEST['term']) && $_REQUEST['term'] !== ""){
	$term = $_REQUEST['term'];
	$terms = str_replace(' ', '%20', $term);
}

if(isset($_REQUEST['term1']) && $_REQUEST['term1'] !== ""){
	$term1 = $_REQUEST['term1'];
	$terms2 = str_replace(' ', '%20', $term1);

}

require("test.php");

if($terms == '' && $terms2 == ''){
	echo '<div id="error">Your search returned no results.  Please try again.</div>';
}

?>


<?php

if(isset($terms) && isset($terms2)){
	$url = "https://www.googleapis.com/books/v1/volumes?q=" . "intitle:" . $terms . "+" ."inauthor:" .  $terms2 . "&maxResults=20";
}else if(!isset($terms)){
	$url = "https://www.googleapis.com/books/v1/volumes?q=inauthor:" . $terms2 ."&maxResults=20";
}else if(!isset($terms2)){
	$url = "https://www.googleapis.com/books/v1/volumes?q=" . $terms ."&maxResults=20";
}

  $content= mb_convert_encoding(
    file_get_contents($url),
    "HTML-ENTITIES",
    "UTF-8"
  );

$obj = json_decode($content);

$totalitems = $obj->{'items'}; // 12345

echo "<script>";
echo "removeSearch();";
echo "</script>";

if(count($totalitems) !==0){
	echo "<div class='mason-parent'>";
	
	for ($i = 1; $i < count($totalitems); $i++) {
		$item = $totalitems[$i];
		$volumeinfo = $item->{'volumeInfo'}; // 12345
		$id = $item->{'id'}; // 12345
		$name = $volumeinfo->{'title'}; // 12345
		$authors = $volumeinfo->{'authors'}; // 12345
		$author = $authors[0]; // 12345
	
		$imageLink = $volumeinfo->{'imageLinks'};
		$largeImage = $imageLink->{'thumbnail'};
	
		//if (strpos(strtolower($author), strtolower($term)) !== false) {
			echo "<a href='test.php?id=" . $id ."'>";
			echo "<div class='mason-child id =" . $id . "'>";
			print "<img src='" . $largeImage . "' />";
			echo "<h3>";
			echo $name;
			echo '</h3>';
			echo $author;
			echo "</div>";
			echo "</a>";
	
	//	}
	}
	echo "</div>";

}else {
	echo '<div id="error">Your search returned no results.  Please try again.</div>
		<div id="tfheader">
		<form id="tfnewsearch" method="post" action="search.php">
				<div>
		        	<input type="text" class="tftextinput" name="term" size="100" placeholder = "Book Title">
		        </div>
		        <br>
		        <div>
		        	<input type="text" class="tftextinput" name="term1" size="100" placeholder = "Author">
		        </div>
		        <br>
		        <div>
		        	<input type="submit" value="search" class="tfbutton">
		    	</div>
		</form>
	<div class="tfclear"></div>

	</div>

	';
	
}

?>
<?php require_once("footer.php");?>