    <?php 
    require_once '../../api/requests/sendData.php';
    require_once '../../includes/toast.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Toast
    if (isset($_SESSION['toast'])) {
        if($_SESSION['toast'] == '400'){
            toastShow('Error',$_SESSION['toastMessage'], 'error');
        } else if($_SESSION['toast']){
            toastShow('Success', $_SESSION['toastMessage'], 'success');
        }
        unset($_SESSION['toast']);
        unset($_SESSION['toastMessage']);
    }
    // End Toast

    //Get the current URI
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    if (isset($_GET['route'])) {
        $_SESSION['route'] = $_GET['route'];
    }else{
        $_SESSION['route'] = 'club';
    }

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
    } else{
        // Unset all session variables
        $_SESSION = array();

        // Destroy the session
        session_destroy();

        // Redirect to the login page
        header("Location: ../../views/authentication/login.php");
        exit();
    }

    $route = 'ClubAdmins/index.php?route=getClubWeb';
    $_SESSION['club'] = '';

    if ($_SESSION['club'] == ''){
        // Call the sendDataToApi function to get data from the API
        $apiResponse = sendDataToApi($route, $data);
        $decodedResponse = json_decode($apiResponse, true);

        if ($decodedResponse !== null) {
            $status = $decodedResponse['status'];

            if ($status === '200') {
                // Check if $clubs is not empty
                $clubs = $decodedResponse['data']; 
                if (!empty($clubs)) {
                    foreach ($clubs as $club) {
                        $_SESSION['club'] = $club['nameClub'];
                    }
                } else {
                    $_SESSION['club'] = 'Create Club';
                }
                

            } else {
                $_SESSION['club'] = 'Join Club';
            }
        } else {
            // Handle JSON decoding error
            echo $apiResponse;
            echo "Failed to decode JSON";
        }
    }
    
    $dataNotf = [
        'idClub' => $_SESSION['idClub']  
    ];
    $routeNotf = 'Notifications/index.php?route=getNotifications';
    $apiResponseNotf = sendDataToApi($routeNotf, $dataNotf);
    $decodedResponseNotf = json_decode($apiResponseNotf, true);
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
        <!-- <script src="https://cdn.jsdelivr.net/npm/chart.js"></script> -->
        <!-- <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script> -->

        <!-- ====== Fontawesome CDN Link ====== -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"/>

        <link href="../../css/index.css" rel="stylesheet">

        <!-- Scrollable table -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <script src="https://code.jquery.com/jquery-3.6.4.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.8/dist/umd/popper.min.js"></script>
        <!-- End of Scrollable -->

        <!-- Bootstrap-datepicker CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
        <!-- Bootstrap-timepicker CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">


        <!-- Animations -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
        <!-- Custom fonts for this template-->
        <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.18.0/font/bootstrap-icons.css" rel="stylesheet">

        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
        
        <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">

        <!-- Custom styles for this template-->
        <link href="../../css/sb-admin-2.min.css" rel="stylesheet">

    </head>

    <body id="page-top">
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
        <!-- Page Wrapper -->
        <div id="wrapper">

            <!-- Sidebar -->
            <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

                <!-- Sidebar - Brand -->
                <a class="sidebar-brand d-flex align-items-center justify-content-center" href="base.php?route=club">
                    <div class="sidebar-brand-icon rotate-n-15">
                        <img src="../../img/splash.png" class="my-logo">
                    </div>
                    <div class="sidebar-brand-text mx-3">SoccerPlan</div>
                    <!-- <i class="fas fa-camera-retro"></i> fa-camera-retro -->
                </a>

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <li class="nav-item">
                    <a class="nav-link my <?php echo ($_SESSION['route'] == 'club') ? 'active' : ''; ?>" href="base.php?route=club">
                        <i class="fas fa-fw fa-house my <?php echo ($_SESSION['route'] == 'club') ? 'active' : ''; ?>"></i>
                        <span><?php echo $_SESSION['club'] ?></span></a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SESSION['idClub'] == '') ? 'disabled-link' : ''; ?>  my <?php echo ($_SESSION['route'] == 'teams') ? 'active' : ''; ?>" href="base.php?route=teams">
                        <i class="fas fa-fw fa-users my <?php echo ($_SESSION['route'] == 'teams') ? 'active' : ''; ?>"></i>
                        <span>Teams</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SESSION['idClub'] == '') ? 'disabled-link' : ''; ?>  my <?php echo ($_SESSION['route'] == 'clothes') ? 'active' : ''; ?>" href="base.php?route=clothes">
                        <i class="fas fa-fw fa-shirt my <?php echo ($_SESSION['route'] == 'clothes') ? 'active' : ''; ?>"></i>
                        <span>Clothes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SESSION['idClub'] == '') ? 'disabled-link' : ''; ?>  my <?php echo ($_SESSION['route'] == 'requests') ? 'active' : ''; ?>" href="base.php?route=requests">
                        <i class="fas fa-fw fa-envelope-open my <?php echo ($_SESSION['route'] == 'requests') ? 'active' : ''; ?>"></i>
                        <span>Requests</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SESSION['idClub'] == '') ? 'disabled-link' : ''; ?>  my <?php echo ($_SESSION['route'] == 'calendar') ? 'active' : ''; ?>" href="base.php?route=calendar">
                        <i class="fas fa-fw fa-calendar my <?php echo ($_SESSION['route'] == 'calendar') ? 'active' : ''; ?>"></i>
                        <span>Calendar</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SESSION['idClub'] == '') ? 'disabled-link' : ''; ?>  my <?php echo ($_SESSION['route'] == 'codes') ? 'active' : ''; ?>" href="base.php?route=codes">
                        <i class="fas fa-fw fa-key my <?php echo ($_SESSION['route'] == 'codes') ? 'active' : ''; ?>"></i>
                        <span>Codes</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link <?php echo ($_SESSION['idClub'] == '') ? 'disabled-link' : ''; ?>  my <?php echo ($_SESSION['route'] == 'championships') ? 'active' : ''; ?>" href="base.php?route=championships">
                        <i class="fas fa-fw fa-trophy my <?php echo ($_SESSION['route'] == 'championships') ? 'active' : ''; ?>"></i>
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
                <a class="nav-link <?php echo ($_SESSION['idClub'] == '') ? 'disabled-link' : ''; ?>  my <?php echo ($_SESSION['route'] == 'teams') ? 'active' : ''; ?> collapsed" href="#" data-toggle="collapse" 
                    data-target="#collapseClubs" aria-expanded="false" aria-controls="collapseClubs">
                    <i class="fas fa-fw fa-table my <?php echo ($_SESSION['route'] == 'teams') ? 'active' : ''; ?>"></i>
                    <span>Tables</span>
                </a>
                    <div id="collapseClubs" class="collapse" aria-labelledby="headingClubs" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <h6 class="collapse-header">Tables:</h6>
                            <a class="collapse-item" href="base.php?route=coaches">Coaches</a>
                            <a class="collapse-item" href="base.php?route=players">Players</a>
                        </div>
                    </div>
                </li>
                
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

                            <li class="nav-item dropdown no-arrow mx-1">
                                <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class="fas fa-bell fa-fw"></i>
                                </a>
                                <!-- Dropdown - Alerts -->
                                <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="alertsDropdown">
                                    <h6 class="dropdown-header bgColor">
                                        Notifications Center
                                    </h6>
                                    <div style="max-height: 300px; overflow-y: auto;">
                                        <?php
                                        usort($decodedResponseNotf['data'], function ($a, $b) {
                                            return strtotime($b['timeExecuted']) - strtotime($a['timeExecuted']);
                                        });
                                        foreach ($decodedResponseNotf['data'] as $notification) {
                                            switch ($notification['typeNotification']) {
                                                case 'joinClub':
                                                    $icon = 'user-plus';
                                                    break;
                                                case 'joinTeam':
                                                    $icon = 'user-plus';
                                                    break;
                                                case 'joinClubAccepted':
                                                    $icon = 'user-check';
                                                    break;
                                                case 'joinTeamAccepted':
                                                    $icon = 'user-check';
                                                    break;
                                                case 'joinClubRejected':
                                                    $icon = 'user-xmark';
                                                    break;
                                                    case 'joinTeamRejected':
                                                        $icon = 'user-xmark';
                                                        break;
                                                case 'userJoinedClub':
                                                    $icon = 'user-check';
                                                    break;
                                                case 'userJoinedTeam':
                                                    $icon = 'user-check';
                                                    break;
                                                case 'eventAdded':
                                                    $icon = 'calendar-plus';
                                                    break;
                                                default:
                                                    $icon = 'help-circle';
                                            }

                                            $timeExecuted = new DateTime($notification['timeExecuted']);
                                            $currentTime = new DateTime();
                                            $timeDifference = $currentTime->diff($timeExecuted);
                                            $hoursDifference = $timeDifference->h;

                                            // Additional conditions to filter notifications
                                            if (
                                                ($notification['idClub'] != $_SESSION['idClub']) ||
                                                (strpos($notification['descriptionN'], 'entered') !== false && $notification['idExecuter'] == $dataUser['idUser']) ||
                                                (strpos($notification['descriptionN'], 'Welcome') !== false && $notification['idExecuter'] != $dataUser['idUser']) ||
                                                ($notification['typeNotification'] == 'joinClubRejected' && $notification['idExecuter'] != $dataUser['idUser']) ||
                                                ($notification['typeNotification'] == 'joinTeamRejected' && $notification['idExecuter'] != $dataUser['idUser'])
                                            ) {
                                                // Do nothing if conditions are not met
                                            } else {
                                                // Generate HTML for each notification
                                                echo '<a class="dropdown-item d-flex align-items-center" href="#">
                                                        <div class="mr-3">
                                                            <div class="icon-circle bgColor">
                                                                <i class="fas fa-' . $icon . ' text-white"></i>
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <div class="small text-gray-500">' . $notification['timeExecuted'] . '</div>
                                                            <span class="font-weight-bold">' . $notification['descriptionN'] . '</span>
                                                        </div>
                                                    </a>';
                                            }
                                        }
                                        ?>
                                    </div>
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
                                    <?php 
                                        $base64Image = base64_decode($_SESSION['img']);
                                        if (!empty($_SESSION['img'])) {
                                        $imgSrc = 'data:image/png;base64,' . $base64Image;
                                        echo '<img class="img-profile rounded-circle" src="' . $imgSrc . '" alt="' . $_SESSION['firstName'] . '">';
                                        }
                                    ?>
                                </a>
                                <!-- Dropdown - User Information -->
                                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                    aria-labelledby="userDropdown">
                                    <a class="dropdown-item" href="#" data-toggle="modal" data-target="#editProfileModal">
                                        <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Profile
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="../../api/logout.php">
                                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                        Logout
                                    </a>
                                </div>
                            </li>
                        </ul>

                    </nav>
                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">
                        <?php include '../../includes/routes.php' ?>
                        <?php include '../../includes/profile.php' ?> 
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
        <!-- <script src="../../js/demo/chart-area-demo.js"></script>
        <script src="../../js/demo/chart-pie-demo.js"></script> -->
        <script src="../../js/demo/chart-bar-demo.js"></script>

        <!-- Bootstrap-datepicker JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

        <!-- Bootstrap-timepicker JS -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>

    </body>

    </html>
