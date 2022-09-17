
<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    $inData = getRequestInfo();

    $id = 0;
    $firstName = $inData["firstName"];
    $lastName = $inData["LastName"];
    $Login = $inData["Login"];
    $Password = $inData["Password"];

    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
    $conn = new mysqli("localhost", "TheBeast", "WeLoveCOP4331", "COP4331"); 	
    if( $conn->connect_error )
    {
        http_response_code(502);
        returnWithError( $conn->connect_error );
    }
    else
    {

        try {
            $stmt = $conn->prepare("INSERT into Users(FirstName, LastName, Login, Password) VALUES(?,?,?,?)");
            $stmt->bind_param("ssss", $inData["firstName"], $inData["LastName"], $inData["Login"], $inData["Password"]);
            $stmt->execute();
            http_response_code(200);
            returnWithSuccess("Created new user.");
        } catch (\mysqli_sql_exception $e) {
            if ($e->getCode() === 1062) {
                returnWithError("Username is already being used!");
                http_response_code(403);
            } else {
                throw $e;
            }
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

    function returnWithInfo( $firstName, $lastName, $id )
    {
        $retValue = '{"id":' . $id . ',"firstName":"' . $firstName . '","lastName":"' . $lastName . '}';
        sendResultInfoAsJson( $retValue );
    }
?>