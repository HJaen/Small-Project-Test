<?php
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
        $query = "DELETE FROM Contacts WHERE id=$deleteID";
        $stmt = $conn->query($query);
        $affectedRows = mysqli_affected_rows($conn);
        if($affectedRows > 0)
        {
            $conn->close();
            returnWithSuccess("Done. No error.");
        }
        else
        {
            $err = "Record Not Found.";
            $conn->close();
            returnWithError($err);
        }
        
    }

    function returnWithError( $err )
    {
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }

    function returnWithSuccess( $msg )
    {
        $retValue = '{"Sucesss":"' . $msg . '"}';
        sendResultInfoAsJson( $retValue );
    }

    function getRequestInfo()
    {
        return json_decode(file_get_contents('php://input'), true);
    }

    function sendResultInfoAsJson( $obj )
    {
        header('Content-type: application/json');
        echo $obj;
    }


?>