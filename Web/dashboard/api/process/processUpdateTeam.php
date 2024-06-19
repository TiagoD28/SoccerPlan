<?php 
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $data = []; // Initialize $data outside the conditional block

    if(isset($_POST['updateTeam'])){

        // Get the values from the form
        $nameTeam = $_POST['nameTeam'];
        $age = $_POST['agesDropdown'];
        $fieldOf = $_POST['fieldsDropdown'];
        $rank = $_POST['ranksDropdown'];
        $ab = $_POST['abDropdown'];
        $coach = isset($_POST['coachesDropdown']) ? $_POST['coachesDropdown'] : '';
        $championship = isset($_POST['championshipsDropdown']) ? $_POST['championshipsDropdown'] : '';
        $idTeam = $_POST['idTeam'];
        $idClub = $_SESSION['idClub'];

        if (isset($_FILES['teamImage']) && $_FILES['teamImage']['error'] != UPLOAD_ERR_NO_FILE) {

            // to get the image
            $file = $_FILES['teamImage'];

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

                    $fileData = base64_encode($fileData);

                    $data = [
                        'nameTeam' => $nameTeam, 
                        'age' => $age,
                        'fieldOf' => $fieldOf,
                        'rank' => $rank,
                        'ab' => $ab,
                        'idCoach' => $coach,
                        'idChampionship' => $championship,
                        'img' => $fileData,
                        'idClub' => $idClub,
                        'idTeam' => $idTeam
                    ];

                } else {
                    echo "Error uploading the file.";
                }
            } else {
                echo "Invalid file type. Allowed types: " . implode(", ", $allowedExtensions);
            }
            // end of getting image
        } else {
            // If no image is uploaded, set other data
            $data = [
                'nameTeam' => $nameTeam, 
                'age' => $age,
                'fieldOf' => $fieldOf,
                'rank' => $rank,
                'ab' => $ab,
                'idCoach' => $coach,
                'idChampionship' => $championship,
                'idClub' => $idClub,
                'idTeam' => $idTeam
            ];
        }
    }

    // Now $data is defined in all code paths
    $route = 'Teams/index.php?route=updateTeam';

    // die($data['img']);    

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $_SESSION['toast'] = $decodedResponse['status'];
    $_SESSION['toastMessage'] = $decodedResponse['message'];

    header('Location: ../../views/adminClub/base.php?route=teams');
?>