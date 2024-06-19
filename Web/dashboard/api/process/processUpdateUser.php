<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block

    if(isset($_POST['updateUser'])){
        // Get the values from the form
        $idUser = $_SESSION['idUser'];
        $firstName = $_POST['firstName'];
        $lastName = $_POST['lastName'];
        $age = $_POST['age'];
        $nacionality = $_POST['nacionality'];
        $email = $_POST['email'];
        $phoneNumber = $_POST['phoneNumber'];
        $img = $_SESSION['img'];
        $typeUser = $_SESSION['typeUser'];
        $img = $_SESSION['img'];
        

        if(isset($_FILES['userImage']) && $_FILES['userImage']['error'] != UPLOAD_ERR_NO_FILE) {
            // to get the image
            $file = $_FILES['userImage'];
    
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
                            'idUser' => $idUser,
                            'firstName' => $firstName,
                            'lastName' => $lastName,
                            'age' => $age,
                            'nacionality' => $nacionality,
                            'email' => $email,
                            'phoneNumber' => $phoneNumber,
                            'typeUser' => $typeUser,
                            'img' => $fileData,
                        ];
                    } else {
                        echo "Error reading the file content.";
                        $_SESSION['toast'] = '400';
                        $_SESSION['toastMessage'] = 'Error reading the image content!';
                        header('Location: ../../views/adminClub/base.php?route=club');
                        exit;
                    }
                } else {
                    echo "Error uploading the file.";
                    $_SESSION['toast'] = '400';
                    $_SESSION['toastMessage'] = 'Error uploading the image!';
                    header('Location: ../../views/adminClub/base.php?route=club');
                    exit;
                }
            } else {
                echo "Invalid file type. Allowed types: " . implode(", ", $allowedExtensions);
                $_SESSION['toast'] = '400';
                $_SESSION['toastMessage'] = 'Image must be .png!';
                header('Location: ../../views/adminClub/base.php?route=club');
                exit;
            }
            // end of getting image
        } else {
            $data = [
                'idUser' => $idUser,
                'firstName' => $firstName,
                'lastName' => $lastName,
                'age' => $age,
                'nacionality' => $nacionality,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'typeUser' => $typeUser
            ];
        }
    }

    $route = 'Users/index.php?route=updateUser';  

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $data = $decodedResponse['data'];
    
    if($decodedResponse['status'] == '200'){
        $_SESSION['email'] = $data['email'];
        $_SESSION['typeUser'] = $data['typeUser'];
        $_SESSION['firstName'] = $data['firstName'];
        $_SESSION['lastName'] = $data['lastName'];
        $_SESSION['age'] = $data['age'];
        $_SESSION['nacionality'] = $data['nacionality'];
        $_SESSION['phoneNumber'] = $data['phoneNumber'];
        $_SESSION['img'] = $data['img'];
    }   
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];
    header('Location: ../../views/adminClub/base.php?route=club');
    exit;
?>