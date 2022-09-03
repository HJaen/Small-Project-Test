<?php

    $inData = getRequestInfo();
	
	$searchResults = array();
	$foundResults = 0;

    //change later with the server's credentials
	//mysqli(server host name, username to log into database, database password, said database)
	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
	//$conn = new mysqli("localhost", "root", "0afdbc3e76a812133be14d1c737c6766a3988364f36eb65d", "Contacts");
    if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	}
    else
    {
		$stmt = $conn->prepare("SELECT * FROM Contacts WHERE (FirstName like ? or LastName like ? or Email like ? or Phone like ?) and UserID=?");
		$desired = "%" . $inData["search"] . "%";
		//$stmt->bind_param("sssss", $desired, $desired, $desired, $desired, $inData["userID"]);	Should the userID be returned as well? Or just the F and L name, phone and email?
		$stmt->bind_param("ssss", $desired, $desired, $desired, $desired);
		$stmt->execute();

		$result = $stmt->get_result();

		//equivalent to foreach?
		while($contact = $result->fetch_assoc())
		{
			//add new array with elements(FN, LN, E, P)
			$searchResults[] = array("FirstName" = $contact["FirstName"], "LastName" = $contact["LastName"], "Email" = $contacts["Email"], "Phone" = $contacts["Phone"]);

			$foundResults++;
		}

		if($foundResults == 0)
		{
			returnWithError("No contacts found that match your search.");
		}
		else
		{
			//put wrong variable by accident
			returnWithInfo($searchResults);
		}
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
		$retValue = '{"firstName":"","lastName":"","phoneNumber":"","email":"","error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $results )
	{
		$retValue = '{"results":[' . $results . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>
