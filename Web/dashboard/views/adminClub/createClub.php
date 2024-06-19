<?php
// ... (your existing code)
// include '../../toast.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo $_SESSION['idClub'];

if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
    $status = $_SESSION['status'];
    $message = $_SESSION['message'];

    // Clear the session variables after retrieving the values
    unset($_SESSION['status']);
    unset($_SESSION['message']);

    // Use $status and $message as needed in your HTML or logic
    // echo "Status: $status, Message: $message";
} 

echo 'ola';

// echo 'Status: ' . $status;

// $status = 'Success';
// $message = 'Operation completed successfully.';
// showToast($status, $message);

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
            <!-- <?php showToast($status, $message); ?> -->
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
                                <!-- Select Type User -->
                                    <!-- <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <label class="input-group-text" for="inputGroupSelect01">Type User</label>
                                        </div>
                                        <select class="custom-select" id="userTypeSelect" name="typeUser">
                                            <option selected>Choose...</option>
                                            <option value="ClubAdmin">Club Administrator</option>
                                            <option value="Employer">Employer</option>
                                        </select>
                                    </div> -->

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
                                    <div class="form-group row">
                                        <!-- <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" id="dateOfBirth"
                                                placeholder="Date of Birth">
                                        </div> -->
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-user" id="City" name="city"
                                                placeholder="City">
                                        </div>

                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-user" id="Country" name="country"
                                                placeholder="Country">
                                        </div>
                                        <!-- <div class="col-sm-6">
                                            <input type="number" class="form-control form-control-user" id="phoneNumber"
                                                placeholder="Phone Number">
                                        </div> -->
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6">
                                            <input type="email" class="form-control form-control-user" name="email" id="Email"
                                                placeholder="Email Address">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="number" class="form-control form-control-user" id="PhoneNumber" name="phoneNumber"
                                                placeholder="Phone Number">
                                        </div>
                                    </div>
                                    <!-- <div class="form-group">
                                        <input type="email" class="form-control form-control-user" name="email" id="exampleInputEmail"
                                            placeholder="Email Address">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleInputPassword" name="password" placeholder="Password">
                                        </div> -->
                                        <!-- <div class="col-sm-6">
                                            <input type="password" class="form-control form-control-user"
                                                id="exampleRepeatPassword" placeholder="Repeat Password">
                                        </div> -->
                                    <!-- </div> -->
                                    <div class="input-group mb-3">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputGroupFile02">
                                            <label class="custom-file-label" for="inputGroupFile02" aria-describedby="inputGroupFileAddon02">Choose Image</label>
                                        </div>
                                        <div class="input-group-append">
                                            <span class="input-group-text" id="inputGroupFileAddon02">Upload</span>
                                        </div>
                                    </div>
                                    <button type="submit" name="createClub" class="my-a-btn btn my-btn-primary btn-user btn-block">
                                        Create
                                    </button>
                                    <hr>
                                </form>
                                
                                <!-- <div class="text-center">
                                    <a class="my-a small" href="forgot-password.html">Forgot Password?</a>
                                </div>
                                <div class="text-center">
                                    <a class="my-a small" href="./login.php">Already have an account? Login!</a>
                                </div> -->
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