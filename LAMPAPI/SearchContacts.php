<?php
	ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $inData = getRequestInfo();
	$search = "%" . $inData["search"] . "%";
	$ID = $inData["ID"];
	$pageNumber = $inData["PageNumber"];
	$no_of_records_per_page = 35;
	$offset = ($pageNumber-1) * $no_of_records_per_page;
	$foundResults = 0;

	$conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331");
    if ($conn->connect_error) 
	{
		returnWithError( $conn->connect_error );
	}
    else
    {
		$desired = $inData["search"] . "%";
		$queryCount = "SELECT count(*) FROM Contacts WHERE UserID=$ID AND (FirstName like '$desired' or LastName like '$desired')";
		$resultCount = mysqli_query($conn,$queryCount);
		$data=mysqli_fetch_array($resultCount);
		$TotalCount = $data[0];

		$query = "SELECT * FROM Contacts WHERE UserID=$ID AND (FirstName like '$desired' or LastName like '$desired') LIMIT $offset, $no_of_records_per_page";

		$stmt = $conn->query($query);
		
		if ($stmt->num_rows > 0) {
			$searchResults ["Success"] = "200 ok";
			$searchResults ["TotalPages"] = ceil($TotalCount/$no_of_records_per_page);
			$searchResults ["TotalContacts"] = $TotalCount;
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
