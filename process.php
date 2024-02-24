<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect form data
    $username = $_POST["username"];
    $injured = $_POST["injured"];
    
    $description = $_POST["description"];
    $phonenumber = $_POST["phonenumber"];
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];

    // File upload handling
    $targetDirectory = "uploads/"; // Create a directory named "uploads" in the same location as your PHP script
    $targetFile = $targetDirectory . basename($_FILES["photo"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file is an actual image
    if (isset($_POST["submit"])) {
        $check = getimagesize($_FILES["photo"]["tmp_name"]);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".";
            $uploadOk = 1;
        } else {
            echo "File is not an image.";
            $uploadOk = 0;
        }
    }

    // Check if file already exists
    if (file_exists($targetFile)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (you can adjust the size limit)
    if ($_FILES["photo"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow only certain file formats (you can adjust the allowed formats)
    $allowedFormats = array("jpg", "jpeg", "png", "gif");
    if (!in_array($imageFileType, $allowedFormats)) {
        echo "Sorry, only JPG, JPEG, PNG, and GIF files are allowed.";
        $uploadOk = 0;
    }

    // If $uploadOk is set to 0, the file upload was not successful
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // If everything is OK, try to upload the file
        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $targetFile)) {
            echo "The file " . basename($_FILES["photo"]["name"]) . " has been uploaded.";
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }

    // Open a file for appending
    $file = fopen("user_data.txt", "a");

    // Write the user information to the file
    fwrite($file, "Name: $username, Injured: $injured, Description: $description, Phone: $phonenumber, Latitude: $latitude, Longitude: $longitude, Photo: $targetFile\n");

    // Close the file
    fclose($file);

    echo "Information has been stored successfully.";
} else {
    echo "Invalid request method.";
}
?>

