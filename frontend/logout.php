<?php

session_start();


// Remove all session data

session_unset();


// Destroy session

session_destroy();


// Go to public home

header("Location: index.php");

exit();
