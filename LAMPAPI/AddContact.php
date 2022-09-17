<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $inData = getRequestInfo();

    // Contact info
    $firstName = $inData["FirstName"];
    $lastName = $inData["LastName"];
    $email = $inData["Email"];
    $phone = $inData["Phone"];
    $userId = $inData["UserID"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
    
    if( $conn->connect_error )
    {
        http_response_code(502);
        returnWithError( $conn->connect_error );
    }
    else
    {
        $stmt = $conn->prepare("INSERT into Contacts(FirstName, LastName, Email, Phone, UserID) VALUES(?,?,?,?,?)");
        $stmt->bind_param("sssss", $firstName, $lastName, $email, $phone, $userId);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        http_response_code(200);
        returnWithSuccess("Added new contact.");
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

    function returnWithError( $err )
    {
        $retValue = '{"Error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }

    function returnWithSuccess( $msg )
    {
        $retValue = '{"Sucesss":"' . $msg . '"}';
        sendResultInfoAsJson( $retValue );
    }

    function returnWithInfo($firstName, $lastName, $email, $phone, $userId, $dateCreated)
    {
        $retValue = '{"FirstName": ' . $firstName . ',' .
                    '"LastName": ' . $lastName . ',' .
                    '"Email": ' . $email . ',' .
                    '"Phone": ' . $phone . ',' .
                    '"UserID": ' . $userId . ',' .
                    '"DateCreated": ' . $dateCreated . ',' .
                    '"Error": ""}';
        sendResultInfoAsJson( $retValue );
    }
?>
