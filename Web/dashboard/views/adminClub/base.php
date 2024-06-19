<?php 
require_once '../../api/requests/sendData.php';
require_once '../../includes/toast.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Toast
if (isset($_SESSION['toast'])) {
    if($_SESSION['toast'] == '400'){
        toastShow($_SESSION['toastMessage'], 'error');
    } else if($_SESSION['toast']){
        toastShow($_SESSION['toastMessage'], 'success');
    }
    unset($_SESSION['toast']);
    unset($_SESSION['toastMessage']);
    // unset($_SESSION['message']);
}
// End Toast

//Get the current URI
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (isset($_GET['route'])) {
    $_SESSION['route'] = $_GET['route'];
}else{
    $_SESSION['route'] = 'dashboard';
}

// echo $_SESSION['idAdmin'];

$data = [];
if(!empty($_SESSION['idClubAdmin'])){
    $data = [
        'idClubAdmin' => $_SESSION['idClubAdmin']
    ];
} else if(!empty($_SESSION['idEmployer'])){
    $data = [
        'idEmployer' => $_SESSION['idEmployer'],
        'idClub' => $_SESSION['idClub']
    ];
}

$route = 'ClubAdmins/index.php?route=getClubWeb';
$_SESSION['club'] = '';

if ($_SESSION['club'] == ''){
    // Call the sendDataToApi function to get data from the API
    $apiResponse = sendDataToApi($route, $data);
    // echo $apiResponse;
    // Decode the JSON response
    $decodedResponse = json_decode($apiResponse, true);

    // Check if decoding was successful
    if ($decodedResponse !== null) {
        $status = $decodedResponse['status'];

        if ($status === '200') {
            // Check if $clubs is not empty
            $clubs = $decodedResponse['data']; 
            if (!empty($clubs)) {
                foreach ($clubs as $club) {
                    // Access specific elements of each club
                    // $idClub = $club['idClub'];
                    $_SESSION['club'] = $club['nameClub'];
                    // $age = $club['age'];
                    // ... (access other elements as needed)

                    // Echo or use the values as needed
                    // echo "Club ID: $idClub, Name: $nome, Age: $age<br>";
                }
            } else {
                $_SESSION['club'] = 'Create Club';
            }
            

        } else {
            // Handle the case where the API response indicates an error
            // echo "Error: {$decodedResponse['message']}";
            $_SESSION['club'] = 'Join Club';
        }
    } else {
        // Handle JSON decoding error
        echo $apiResponse;
        echo "Failed to decode JSON";
        // $_SESSION['club'] = 'Club';
    }
}

?>

<!DOCTYPE html>
<html lang="pt">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>SoccerPlan</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

    <!-- ====== Fontawesome CDN Link ====== -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

    <link href="../../css/index.css" rel="stylesheet">

    <!-- Scrollable table -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
    <!-- End of Scrollable -->

    <!-- Animations -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <!-- Custom fonts for this template-->
    <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="base.php?route=dashboard">
                <div class="sidebar-brand-icon rotate-n-15">
                    <!-- <i src="../../img/splash.png"></i> -->
                    <!-- <i src="../../img/splash.png"></i> -->
                    <img src="../../img/splash.png" class="my-logo">
                    <!-- <img src="../../img/splash.png"> -->
                </div>
                <div class="sidebar-brand-text mx-3">SoccerPlan</div>
                <!-- <i class="fas fa-camera-retro"></i> fa-camera-retro -->
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'dashboard') ? 'active' : ''; ?>" aria-selected="true" href="base.php?route=dashboard">
                <i class="fas fa-fw fa-tachometer-alt my <?php echo ($_SESSION['route'] == 'dashboard') ? 'active' : ''; ?>"></i>
                <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <!-- <hr class="sidebar-divider my-0"> -->

            <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'club') ? 'active' : ''; ?>" href="base.php?route=club">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'club') ? 'active' : ''; ?>"></i>
                    <span><?php echo $_SESSION['club'] ?></span></a>
            </li>

            <li class="nav-item">
            <a class="nav-link my <?php echo ($_SESSION['route'] == 'teams') ? 'active' : ''; ?> collapsed" href="#" data-toggle="collapse" 
                data-target="#collapseTeams" aria-expanded="false" aria-controls="collapseTeams">
                <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'teams') ? 'active' : ''; ?>"></i>
                <span>Tables</span>
            </a>
                <div id="collapseTeams" class="collapse" aria-labelledby="headingTeams" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Tables:</h6>
                        <a class="collapse-item" href="base.php?route=teams">Teams</a>
                        <a class="collapse-item" href="base.php?route=teams">Players</a>
                        <a class="collapse-item" href="base.php?route=teamscodes">Codes</a>
                        <!-- <a class="collapse-item" href="base.php?route=teamsevents">Teams Codes</a> -->
                        <!-- <a class="collapse-item" href="base.php?route=teamsmembers">Teams Members</a> -->
                    </div>
                </div>
            </li>

            <!-- <hr class="sidebar-divider my-0"> -->

            <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'clothes') ? 'active' : ''; ?>" href="base.php?route=clothesClub">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'clothes') ? 'active' : ''; ?>"></i>
                    <span>Clothes</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'requestsClub') ? 'active' : ''; ?>" href="base.php?route=requestsClub">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'requestsClub') ? 'active' : ''; ?>"></i>
                    <span>Requests</span>
                </a>
            </li>

            <!-- <hr class="sidebar-divider my-0"> -->

            <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'calendary') ? 'active' : ''; ?>" href="base.php?route=calendary">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'calendary') ? 'active' : ''; ?>"></i>
                    <span>Calendary</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'calendary') ? 'active' : ''; ?>" href="base.php?route=codes">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'calendary') ? 'active' : ''; ?>"></i>
                    <span>Codes</span>
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'calendary') ? 'active' : ''; ?>" href="base.php?route=championships">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'calendary') ? 'active' : ''; ?>"></i>
                    <span>Championships</span>
                </a>
            </li>

            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Tables
            </div>

            <!-- Nav Item - Tables -->
            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
            <a class="nav-link my <?php echo ($_SESSION['route'] == 'teams') ? 'active' : ''; ?> collapsed" href="#" data-toggle="collapse" 
                data-target="#collapseClubs" aria-expanded="false" aria-controls="collapseClubs">
                <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'teams') ? 'active' : ''; ?>"></i>
                <span>Tables</span>
            </a>
                <div id="collapseClubs" class="collapse" aria-labelledby="headingClubs" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Tables:</h6>
                        <a class="collapse-item" href="base.php?route=coachesClub">Coaches</a>
                        <a class="collapse-item" href="base.php?route=playersClub">Players</a>
                        <!-- <a class="collapse-item" href="base.php?route=teamsevents">Teams Codes</a> -->
                        <!-- <a class="collapse-item" href="base.php?route=teamsmembers">Teams Members</a> -->
                    </div>
                </div>
            </li>
            
            <!-- <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'coaches') ? 'active' : ''; ?>" href="base.php?route=coaches">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'coaches') ? 'active' : ''; ?>"></i>
                    <span>Coaches</span></a>
            </li> -->

            <!-- <li class="nav-item">
                <a class="nav-link my <?php echo ($_SESSION['route'] == 'players') ? 'active' : ''; ?>" href="base.php?route=players">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'players') ? 'active' : ''; ?>"></i>
                    <span>Players</span></a>
            </li> -->

            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Search -->
                    <!-- <form
                        class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..."
                                aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form> -->

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small">
                                    <?php echo $_SESSION['firstName'] . ' ' . $_SESSION['lastName'] ?>
                                </span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <!-- <a class="dropdown-item" href="../api/logout.php" data-toggle="modal" data-target="#logoutModal"> -->
                                <a class="dropdown-item" href="../../api/logout.php">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                                <!-- Sidebar Toggler (Sidebar) -->
                        <!-- <div class="text-center d-none d-md-inline">
                            <button class="rounded-circle border-0" id="sidebarToggle"></button>
                        </div> -->

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php include '../../routes.php' ?>
                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>SoccerPlan &copy; <br>Tiago Domingos</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

    <!-- Script to open and close the modal -->
    <script>
        // when $bool = true the modal id=teamInfoModal its opened
        $(document).ready(function(){
            // Show the modal on page load
            // $('#teamInfoModal').modal('show');
            
            // Show the modal on page load
            $('#teamInfoModal').modal('show');
            
        });

        // Set a parameter in the URL when the close button is pressed
        $('#closeButton').on('click', function() {
            // Assuming you're using jQuery for simplicity
            // window.location.href = './teams.php?boll=false';
            window.location.href = './base.php?route=teams&modal=false';
        });

        $('.edit-team').on('click', function() {
            // Get the idTeam from the data-idteam attribute
            var idTeam = $(this).data('teamid');

            console.log('Clicked Edit for team ID:', idTeam);

            // Set $bool to true and update the URL with idTeam
            window.location.href = './base.php?route=teams&modal=true&teamId=' + idTeam;
        });
    </script>
    <!-- End of script modal -->

    <!-- Expanded sections of navbar -->
    <script>
        $(document).ready(function () {
            $('.nav-link[data-target="#collapseClubs"]').click(function () {
                $('#collapseClubs').collapse('toggle');
            });
        });
    </script>

    <script>
        $(document).ready(function () {
            $('.nav-link[data-target="#collapseTeams"]').click(function () {
                $('#collapseTeams').collapse('toggle');
            });
        });
    </script>
<!-- End of Expanded sections of navbar -->

    <!-- Bootstrap core JavaScript-->
    <script src="../../vendor/jquery/jquery.min.js"></script>
    <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../../js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="../../vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="../../js/demo/chart-area-demo.js"></script>
    <script src="../../js/demo/chart-pie-demo.js"></script>

</body>

</html>