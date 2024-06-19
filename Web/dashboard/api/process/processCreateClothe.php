<?php
require_once '../../api/requests/sendData.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

error_reporting(E_ALL);
ini_set('display_errors', 1);

$data = [];

if (isset($_POST['createClothe'])) {
    // Get the values from the form
    $nameClothe = $_POST['nameClothe'];
    $season = $_POST['seasonsDropdown'];
    $idClub = $_SESSION['idClub'];

    if (isset($_FILES['clotheImage']) && $_FILES['clotheImage']['error'] != UPLOAD_ERR_NO_FILE) {

        // to get the image
        $file = $_FILES['clotheImage'];

        // File properties
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        // Check if the file is an image
        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExt, $allowedExtensions)) {
            if ($fileError === 0) {
                // Read the file content
                $fileData = file_get_contents($fileTmpName);

                if ($fileData !== false) {
                    // Encode the file content in base64
                    $fileData = base64_encode($fileData);

                    $data = [
                        'nameClothe' => $nameClothe,
                        'season' => $season,
                        'img' => $fileData,
                        'idClub' => $idClub
                    ];
                } else {
                    echo "Error reading the file content.";
                    $_SESSION['toast'] = '400';
                    $_SESSION['toastMessage'] = 'Error reading the image content!';
                    header('Location: ../../views/adminClub/base.php?route=clothes');
                    exit;
                }
            } else {
                echo "Error uploading the file.";
                $_SESSION['toast'] = '400';
                $_SESSION['toastMessage'] = 'Error uploading the image!';
                header('Location: ../../views/adminClub/base.php?route=clothes');
                exit;
            }
        } else {
            echo "Invalid file type. Allowed types: " . implode(", ", $allowedExtensions);
            $_SESSION['toast'] = '400';
            $_SESSION['toastMessage'] = 'Image must be .png!';
            header('Location: ../../views/adminClub/base.php?route=clothes');
            exit;
        }
        // end of getting image
    } else {
        // If no image is uploaded, set other data
        $data = [
            'nameClothe' => $nameClothe,
            'season' => $season,
            'idClub' => $idClub
        ];
    }
}

$route = 'Clothes/index.php?route=createClothe';

$apiResponse = sendDataToApi($route, $data);
$decodedResponse = json_decode($apiResponse, true);
$_SESSION['toast'] = $decodedResponse['status'];
$_SESSION['toastMessage'] = $decodedResponse['message'];

header('Location: ../../views/adminClub/base.php?route=clothes');
?>