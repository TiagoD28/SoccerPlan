<?php
// ... (your existing code)
require_once '../../includes/toast.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (isset($_SESSION['toast'])) {
    if($_SESSION['toast'] == '400'){
        toastShow('Error', $_SESSION['toastMessage'], 'error');
    } else if($_SESSION['toast']){
        toastShow('Success', $_SESSION['toastMessage'], 'success');
    }
    unset($_SESSION['toast']);
    unset($_SESSION['toastMessage']);
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Register Club</title>

        <!-- Bootstrap files -->
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    

        <!-- External CSS Files -->
        <!-- Scrollable table -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <!-- Animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

        <!-- Custom CSS Files -->
        <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
        <link href="../../css/index.css" rel="stylesheet">


        <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous"> -->
        <!-- Custom fonts for this template-->
        <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet"> -->
        
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        
    </head>
    <body>
        <div class="my-div">
            <img class="my-icon" src="../../img/splash.png">
            <h1 class="my-h1">SOCCERPLAN</h1>
            
        </div>
        <div class="my-container">

            <div class="my-register-card o-hidden border-0 my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-5 d-none d-lg-block my-bg-register">
                            <div class="my-bg-register-image"></div>
                        </div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 my-text-gray-900 mb-4">Create a Club!</h1>
                                </div>
                                <form class="user" action="../../api/process/processCreateClub.php" method="post">

                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" name="nameClub" id="ClubName"
                                                placeholder="Club Name">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control form-control-user" name="foundedYear" id="FoundedYear"
                                                placeholder="Founded Year">
                                        </div>
                                    </div>
                                    <div class="form-group row">>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-user" id="City" name="city"
                                                placeholder="City">
                                        </div>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-user" id="Country" name="country"
                                                placeholder="Country">
                                        </div>
                                    </div>
                                    <button type="submit" name="createClub" class="my-a-btn btn my-btn-primary btn-user btn-block">
                                        Create
                                    </button>
                                    <hr>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Get the selected value when an event (e.g., change) occurs
            document.getElementById('userTypeSelect').addEventListener('change', function () {
                var selectedValue = this.value;
                console.log(selectedValue); // You can use the selectedValue as needed
            });
        </script>
    </body>
</html>