<?php
#Research a bit more
function escape($string){
	return htmlentities($string, ENT_QUOTES, 'UTF-8'); #convert characters to html entities
}

?>