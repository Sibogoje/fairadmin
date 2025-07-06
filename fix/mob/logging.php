<?php
// Create a response array
$response = array();

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the username and password are set
    if (isset($_POST['username']) && isset($_POST['password'])) {
        // Get the username and password from the POST parameters
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Connect to the database
      $conn = new mysqli("194.5.156.43", "u747325399_fair2", "Fairline@151022", "u747325399_fair2");

        // Check if the connection is successful
        if ($conn->connect_error) {
            // Set the response status and message to error
            $response["status"] = "error";
            $response["message"] = "Connection failed: " . $conn->connect_error;
        } else {
            // Prepare a SQL query to select the user with the given username and password
            $stmt = $conn->prepare("SELECT * FROM MobUsers WHERE memberid = ? AND password = ?");
            
            // Bind the parameters to the query
            $stmt->bind_param("ss", $username, $password);

            // Execute the query
            $stmt->execute();

            // Get the result from the query
            $result = $stmt->get_result();

            // Check if the result has any row
            if ($result->num_rows > 0) {
                // Fetch the first row as an associative array
                $row = $result->fetch_assoc();

                // Set the response status and message to success
                $response["status"] = "success";
                $response["message"] = "Login successful";

                // Set the user data in the response array
                $response["id"] = $row["id"];
                $response["username"] = $row["username"];
            } else {
                // Set the response status and message to error
                $response["status"] = "error";
                $response["message"] = "Invalid username or password";
            }

            // Close the statement and connection
            $stmt->close();
            $conn->close();
        }
    } else {
        // Set the response status and message to error
        $response["status"] = "error";
        $response["message"] = "Missing username or password";
    }
} else {
    // Set the response status and message to error
    $response["status"] = "error";
    $response["message"] = "Invalid request method";
}

// Encode the response array as JSON and print it
echo json_encode($response);
?>
