
<?php

	$inData = getRequestInfo();
	
	$id = 0;
	$firstName = "";
	$lastName = "";
	$login = $inData["Login"];
	$password = $inData["Password"];

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
	if( $conn->connect_error )
	{
        // http_response_code(502);
		returnWithError( $conn->connect_error );
	}
	else
	{
		$stmt = $conn->prepare("SELECT ID,FirstName,LastName FROM Users WHERE Login=? AND Password =?");
		$stmt->bind_param("ss", $login, $password);
		$stmt->execute();
		$result = $stmt->get_result();

		if( $row = $result->fetch_assoc()  )
		{
			// http_response_code(200);
			returnWithInfo( $row['firstName'], $row['lastName'], $row['ID'] );
		}
		else
		{
			// http_response_code(404);
			returnWithError("No Records Found");
		}

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
        $retValue = '{"Error":"' . $err . '"}';
        sendResultInfoAsJson( $retValue );
    }

	function returnWithInfo( $firstName, $lastName, $id )
    {
        $retValue = '{"ID":' . $id . ',"FirstName":"' . $firstName . '","LastName":"' . $lastName . '"}';
        sendResultInfoAsJson( $retValue );
    }
	
?>
