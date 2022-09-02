<?php
    // WIP
    require_once 'AddContact.php';
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $inData = getRequestInfo();

    $deleteID = $inData["ID"];

    // Eventually replace this with actual info
    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
    // $conn = new mysqli("localhost", "root", "0afdbc3e76a812133be14d1c737c6766a3988364f36eb65d", "Contacts");     // Ask Fez
    
    if( $conn->connect_error )
    {
        returnWithError( $conn->connect_error );
    }
    else
    {
        deleteFromDatabase(); 

        $conn->close();
    }

    function deleteFromDatabase()
    {
        $query = "DELETE FROM Contacts WHERE id=$deleteID";

        if ( $conn->query($query) ) === TRUE)
        {
            returnWithError("Done. No error.");
            
        }
        else
        {
            returnWithError( $conn->error );
        }
    }
?>