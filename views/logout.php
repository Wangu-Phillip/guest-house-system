<?php 
/**
* The @ symbol before the include statement is an error suppression operator, 
* which means that if the "config.php" file is not found or there is an error 
* while including it, no error message will be displayed to the user.
*
* The "session_start()" function initializes a new session or resumes an existing session,
* allowing the script to store and retrieve information in the session array.
*
* The "session_unset()" function frees all session variables currently registered.
*
* The "session_destroy()" function removes all session variables and destroys the session.
*
* Finally, "the header()" function sends a HTTP redirect response to the browser, 
* instructing it to load the specified login.php page. The execution of the current 
* script ends here, and the user is redirected to the login page.
*/
@include "config.php"; // include the config file in the current script 

session_start(); // start a new session or resume an existing session 
session_unset(); // free all session variables currently registered 
session_destroy(); // remove all session variables and destroy the session

header("location: ../index.php"); // redirect the user to the login page
