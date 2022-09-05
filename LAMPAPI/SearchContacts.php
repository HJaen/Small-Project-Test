<?php
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $inData = getRequestInfo();
	$search = $inData["search"] . "%";
	$ID = $inData["ID"];
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
		$desired = $inData["search"] . "%";
		$query = "SELECT * FROM Contacts WHERE UserID=$ID AND (FirstName like '$desired' or LastName like '$desired')";
		//$stmt->bind_param("sssss", $desired, $desired, $desired, $desired, $inData["userID"]);	Should the userID be returned as well? Or just the F and L name, phone and email?
		//$stmt->bind_param("ssss", $desired, $desired, $desired, $desired);
		//$stmt->execute();

		$stmt = $conn->query($query);

		if ($stmt->num_rows > 0) {
			$searchResults ["Success"] = "200 ok";
			$searchResults ["Length"] = $stmt->num_rows;
			$searchResults ["Contacts"] = [];
			while($contact = $stmt->fetch_assoc())
			{
				//add new array with elements(FN, LN, E, P)
				$arr = [$contact["ID"]=>[
					"FirstName"=>$contact["FirstName"],
					"LastName"=>$contact["LastName"],
					"Email"=>$contact["Email"],
					"PhoneNumber"=>$contact["Phone"],
					"DateCreated"=>$contact["DateCreated"]]];
				array_push($searchResults ["Contacts"], $arr);
				
			}
			return sendResultInfoAsJson(json_encode($searchResults));
		}
		else
		{
			returnWithError("No contacts found that match your search.");
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
		$retValue = '{"error":"' . $err . '"}';
		sendResultInfoAsJson( $retValue );
	}
	
	function returnWithInfo( $results )
	{
		$retValue = '{"results":[' . $results . '],"error":""}';
		sendResultInfoAsJson( $retValue );
	}
	
?>
