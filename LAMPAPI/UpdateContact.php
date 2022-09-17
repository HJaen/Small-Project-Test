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
    $ID = $inData["ID"];

    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
    if( $conn->connect_error )
    {
        http_response_code(502);
        returnWithError( $conn->connect_error );
    }
    else
    {
        $query = "UPDATE Contacts SET FirstName='$firstName',LastName='$lastName',Email='$email',Phone='$phone' WHERE ID=$ID";
        $stmt = $conn->query($query);
        $affectedRows = mysqli_affected_rows($conn);
        
        if($affectedRows > 0)
        {
            http_response_code(200);
            $conn->close();
            returnWithSuccess("Done. No error.");
        }
        else
        {
            http_response_code(406);
            $err = "Could not change row.";
            $conn->close();
            returnWithError($err);
        }

        $stmt->close();
        $conn->close();
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

    function returnWithSuccess( $msg )
    {
        $retValue = '{"Sucesss":"' . $msg . '"}';
        sendResultInfoAsJson( $retValue );
    }

    function returnWithInfo($firstName, $lastName, $email, $phone, $userId, $query)
    {
        $retValue = '{"firstName": ' . $firstName . ',' .
                    '"lastName": ' . $lastName . ',' .
                    '"email": ' . $email . ',' .
                    '"phone": ' . $phone . ',' .
                    '"userID": ' . $userId . ',' .
                    '"query": ' . $query . ',' .
                    '"error": ""}';
        sendResultInfoAsJson( $retValue );
    }
?>