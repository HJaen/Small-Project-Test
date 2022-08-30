<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $inData = getRequestInfo();

    // Contact info
    // Eventually these change keys to match the keys in JavaScript
    $id = $inData["ID"];
    $firstName = $inData["FirstName"];
    $lastName = $inData["LastName"];
    $email = $inDate["Email"];
    $phone = $inDate["Phone"];
    $userId = $inData["UserID"]
    $dateCreated = $inDate["DateCreated"];

    // Eventually replace this with actual info
    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
    if( $conn->connect_error )
    {
        returnWithError( $conn->connect_error );
    }
    else
    {
        $stmt = $conn->prepare("INSERT into Contacts(ID, FirstName, LastName, Email, Phone, UserID, DateCreated) VALUES(?,?,?,?,?,?,?)");
        $stmt->bind_param("dssssds", $inData["ID"], $inData["FirstName"], $inData["LastName"], $inData["Email"], $inData["Phone"], $inData["UserId"], $inDate["DateCreated"]);
        $stmt->execute();
        $stmt->close();
        $conn->close();
        returnWithError("Done. No error.");
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
        $retValue = '{"error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }

    function returnWithInfo( $id, $firstName, $lastName, $email, $phone, $userId, $dateCreated)
    {
        $retValue = '{"id": ' . $id . ',' .
                    '"firstName": ' . $firstName . ',' .
                    '"lastName": ' . $lastName . ',' .
                    '"email": ' . $email . ',' .
                    '"phone": ' . $phone . ',' .
                    '"userID": ' . $userId . ',' .
                    '"dateCreated": ' . $dateCreated . ',' .
                    '"error": ""}';
        sendResultInfoAsJson( $retValue );
    }
?>