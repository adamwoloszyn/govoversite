<?php
    // Get the number of arguments
    $numArgs = $argc;

    // Create a cURL handle
    $ch = curl_init();

    // Set the cURL options
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    // Send the API request
    $response = curl_exec($ch);

    // Check for errors
    if ($response === false) {
        $error_message = curl_error($ch);
        $error_code = curl_errno($ch);
        echo "cURL error: $error_message (Error code: $error_code)";
    }
    else{
        echo( $response );
    }

    // Close the cURL handle
    curl_close($ch);


?>

