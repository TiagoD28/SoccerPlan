<?php 
    require_once '../../api/requests/getData.php';
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // URL of your API endpoint
    $route = 'Users/index.php?route=getUsersClub';
    $responseData = sendDataToApi($route, ["idClub" => $_SESSION['idClub']]);
    $decodeResponse = json_decode($responseData, true);
    $numberMembers = $decodeResponse['data']['userCount'] + 1;

    $routeT = 'Teams/index.php?route=getTeamsClub';
    $responseDataT = sendDataToApi($routeT, ["idClub" => $_SESSION['idClub']]);
    $decodeResponseT = json_decode($responseDataT, true);
    $numberTeams = count($decodeResponseT['data']);
    
    $routeC = 'Coaches/index.php?route=getCoachesClub';
    $responseDataC = sendDataToApi($routeC, ["idClub" => $_SESSION['idClub']]);
    $decodeResponseC = json_decode($responseDataC, true);
    $numberCoaches = count($decodeResponseC['data']);
    
    $routeP = 'Players/index.php?route=getPlayersClub';
    $responseDataP = sendDataToApi($routeP, ["idClub" => $_SESSION['idClub']]);
    $decodeResponseP = json_decode($responseDataP, true);
    $numberPlayers = count($decodeResponseP['data']);

    $routeClubs = 'Clubs/index.php?route=getClubs';
    $apiResponseClubs = getDataFromApi($routeClubs);
    $responseDataClubs = $apiResponseClubs['data'];
    
    $routeUsers = 'Users/index.php?route=getEmpCoaPla';
    $addModal = false;
    if(isset($_GET['addModal'])) {
        $addModal = $_GET['addModal'];

        $apiResponseUsers = sendDataToApi($routeUsers, ['idClub' => $_SESSION['idClub']]);
        $decodedResponseUsers = json_decode($apiResponseUsers, true);
        if($decodedResponseUsers['status'] === '200'){
            $users = $decodedResponseUsers['data'];
        }
    }

?>

<!-- Begin Page Content -->

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4 position-relative">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $_SESSION['club'] ?></h1>
        <a class="add-team d-inline-block rounded-circle my-btn-primary p-2" 
        href="./base.php?route=club&addModal=true">
            <i class="fas fa-user-plus text-white"></i>
        </a>
    </div>


    <?php if($_SESSION['club'] == 'Join Club'): ?>
        <div class="row">
            <div class="col-lg-6 mb-4">            
                <div class="card shadow mb-4">
                    <div class="my-card-header py-3">
                        <h6 class="m-0 font-weight-bold my-text-primary">Make a Request!</h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center">
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Club</label>
                                </div>
                                <select class="custom-select" id="joinClubSelect" name="club">
                                    <option value='' selected>Choose...</option>
                                    <?php foreach ($responseDataClubs as $club): ?>
                                        <option value="<?php echo $club['idClub']; ?>"><?php echo $club['nameClub']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <a href="#" onclick="sendRequest()" class="my-a-btn btn my-btn-primary btn-user btn-block">
                                Send Request
                            </a>
                        </div>
                    </div>
                </div>
                
            </div>

            <div class="col-lg-6 mb-4">            
                <div class="card shadow mb-4">
                    <div class="my-card-header py-3">
                        <h6 class="m-0 font-weight-bold my-text-primary">I have a code!</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <select class="custom-select" id="codeClubSelect" name="club">
                                <option value='' selected>Choose...</option>
                                <?php foreach ($responseDataClubs as $club): ?>
                                    <option value="<?php echo $club['idClub']; ?>"><?php echo $club['nameClub']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control form-control-user" 
                                id="code" name="code" placeholder="Code">
                        </div>
                        <a href="#" onclick="sendCode()" class="my-a-btn btn my-btn-primary btn-user btn-block">
                            Send Code
                        </a>
                    </div>
                </div>
                
            </div>
        </div>

    <?php else: ?>
    
    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="my-card bgColor my-border-left-color shadow h-100 py-2 d-flex flex-column position-relative">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold my-secondary-color text-uppercase mb-1">
                                Number Of Members</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-300"><?php echo $numberMembers ?></div>
                        </div>
                        <div class="col-auto">
                            <!-- Spin fa-spin -->
                            <i class="fas fa-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="my-card bgColor my-border-left-color shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold my-secondary-color text-uppercase mb-1">
                                Number Of Teams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-300"><?php echo $numberTeams ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- sdfs -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="my-card bgColor my-border-left-color shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold my-secondary-color text-uppercase mb-1">
                                Number Of Coaches</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-300"><?php echo $numberCoaches ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-user-pen fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="my-card bgColor my-border-left-color shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold my-secondary-color text-uppercase mb-1">
                                Number Of Players</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-300"><?php echo $numberPlayers ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fa-solid fa-person-running fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold ">Poinst of the Teams</h6>
        </div>
        <div class="card-body">
            <div class="chart-bar">
                <canvas id="myBarChart">
                </canvas>
            </div>
            <hr>
            Number of Points of each Team
        </div>
    </div>
    <?php endif; ?>

    <?php if ($addModal == true): ?>
        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Add Members</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="generateCode" class="user" action="../../api/process/processInviteToClub.php" method="POST">
                        <div class="modal-body">
                            <!-- Dropdown list -->
                            <label for="user">Member:</label>
                            <select id="user" name="user" class="form-control">
                                <option value="" selected>Select Member</option>
                                <?php
                                    foreach ($users as $user) {
                                        $value = strtolower(str_replace(' ', '', $user['idUser'])); // Create a unique value based on the user's name
                                        $club = $user['nameClub'] == '' ? 'Doesn t have a Club!' : $user['nameClub'];
                                        $optionText = $user['firstName'] . ' ' . $user['lastName'] . ' - ' . $user['typeUser'] . ' - ' . $club;
                                        echo "<option value='" . $user['idUser'] . "' data-id-club='" . $user['idClub'] . "'>$optionText</option>";
                                    }
                                ?>
                            </select>
                            <br>

                            <p>Code to enter Club:</p>
                            <div class="input-group">
                                <input type="text" class="form-control" id="clubRandomCode" name="clubRandomCode" readonly>
                                <div class="input-group-append">
                                    <button type="button" class="btn my-btn-primary" onclick="generateAndSetClubRandomCode()">Generate Code</button>
                                </div>
                            </div>

                            <br>
                        </div>
                        <input type="hidden" name="idClub" value="<?php echo $_SESSION['idClub']; ?>">
                        <div class="modal-footer">
                            <!-- Submit button on the left side -->
                            <button type="submit" name="generateCode" class="btn my-btn-primary">
                                Submit
                            </button>
                            
                            <!-- Close button on the right side -->
                            <button id="closeAddUser" type="button" class="btn my-btn-primary" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="../../js/demo/chart-bar-demo.js" data-id-club="<?php echo $_SESSION['idClub']; ?>"></script>

<script>
    function sendRequest() {
        var selectedClub = document.getElementById('joinClubSelect').value;
        var idRequester = "<?php echo $_SESSION['idUser']; ?>";
        var url = "../../api/process/processSendRequest.php?idRequester=" + idRequester + "&idClub=" + selectedClub;
        window.location.href = url;
    }

    function sendCode() {
        var selectedClub = document.getElementById('codeClubSelect').value;
        var code = document.getElementById('code').value;
        var idUser = "<?php echo $_SESSION['idUser']; ?>";
        var idEmployer = "<?php echo $_SESSION['idEmployer']; ?>";
        console.log('Id club: ', selectedClub);
        var url = "../../api/process/processSendCode.php?idUser=" + idUser + "&idClub=" + selectedClub + "&idEmployer=" + idEmployer + "&code=" + code;
        window.location.href = url;
    }
</script>

<script>
    var userDropdown = document.getElementById('user');
    userDropdown.addEventListener('change', function () {
            document.getElementById("clubRandomCode").value = '';
            document.getElementById("teamRandomCode").value = '';
    });


    // generate random code
    function generateRandomCode() {
        var characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        var randomCode = '';
        for (var i = 0; i < 5; i++) {
            randomCode += characters.charAt(Math.floor(Math.random() * characters.length));
        }
        return randomCode.toUpperCase();
    }

    
    function generateAndSetClubRandomCode() {
        var selectedOption = userDropdown.options[userDropdown.selectedIndex]; // index of the selected option
        var idClubValue = selectedOption.getAttribute('data-id-club');
        console.log(idClubValue);
        var club = <?php echo json_encode($_SESSION['idClub']); ?>;
        var clubRandomCode = generateRandomCode();

        // Check if a user is selected (option value is not empty)
        if (userDropdown.value !== "" && club != idClubValue) {
            document.getElementById("clubRandomCode").value = clubRandomCode;
        }
    }
</script>

<script>
    $(document).ready(function(){        
        // Show the modal on page load
        $('#addUser').modal('show');
        
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeAddUser').on('click', function() {
        window.location.href = './base.php?route=club';
    });

    $('.add-user').on('click', function() {
        // Set $bool to true
        window.location.href = './base.php?route=club&addModal=true';
    });
</script>