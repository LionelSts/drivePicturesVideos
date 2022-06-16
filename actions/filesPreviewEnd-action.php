<?php
$fileName = str_replace("'","", $_POST['file']);
unlink("../temporary/".$fileName);
