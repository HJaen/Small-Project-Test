<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $inData = getRequestInfo();
    $deleteID = $inData["ID"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
    
    if( $conn->connect_error )
    {
        http_response_code(502);
        returnWithError( $conn->connect_error );
    }
    else
    {
        $query = "DELETE FROM Contacts WHERE id=$deleteID";
        $stmt = $conn->query($query);
        $affectedRows = mysqli_affected_rows($conn);
        if($affectedRows > 0)
        {
            http_response_code(200);
            returnWithSuccess("Deleted contact.");
        }
        else
        {
            http_response_code(404);
            returnWithError("Record Not Found.");
        }

        $stmt->close();
        $conn->close();
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