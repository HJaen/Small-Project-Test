<?php
    // WIP
    require 'AddContact.php';

    // Eventually replace this with actual info
    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
    // $conn = new mysqli("localhost", "root", "0afdbc3e76a812133be14d1c737c6766a3988364f36eb65d", "Contacts");     // Ask Fez
    
    if( $conn->connect_error )
    {
        returnWithError( $conn->connect_error );
    }
    else
    {
        $deleteID = ###     // Ask Fez how to get ID to delete
        $query = "DELETE FROM Contacts WHERE id=$deleteID";

        if ( $conn->query($query) ) === TRUE)
        {
            returnWithError("Done. No error.");
        }
        else
        {
            returnWithError( $conn->error );
        }

        $conn->close();
    }
?>