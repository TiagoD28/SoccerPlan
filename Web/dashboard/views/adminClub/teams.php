<?php
    require_once '../../api/requests/getData.php';
    $route = 'Teams/index.php?route=getTeamsClub';
    $routeC = 'Coaches/index.php?route=getCoachesClub';
    $routeCP = 'Users/index.php?route=getCoachesPlayers';
    $routeChamp = 'Championships/index.php?route=getChampionshipsClub';

    $data = [
        'idClub' => $_SESSION['idClub']
    ];

    $apiResponse = sendDataToApi($route, $data);
    $apiResponseC = sendDataToApi($routeC, $data);
    $apiResponseChamp = sendDataToApi($routeChamp, $data);

    $decodedResponse = json_decode($apiResponse, true);
    $decodedResponseC = json_decode($apiResponseC, true);
    $decodedResponseChamp = json_decode($apiResponseChamp, true);

    if($decodedResponse['status'] === '200'){
        $teams = $decodedResponse['data'];
    }

    if($decodedResponseC['status'] === '200'){
        $coaches = $decodedResponseC['data'];
    }

    if($decodedResponseChamp['status'] === '200'){
        $championships = $decodedResponseChamp['data'];
    }
    
    $editModal = false;
    $addModal = false;
    $deleteModal = false;
    $playersModal = false;
    $selectedTeam = null;
    $users = [];

    if (isset($_GET['editModal'])) {
        $teamId = $_GET['teamId'];
        $editModal = $_GET['editModal'];

    } else if(isset($_GET['addModal'])) {
        $addModal = $_GET['addModal'];
        $teamId = $_GET['teamId'];

        $apiResponseCP = sendDataToApi($routeCP, ['idTeam' => $teamId]);
        $decodedResponseCP = json_decode($apiResponseCP, true);
        if($decodedResponseCP['status'] === '200'){
            $users = $decodedResponseCP['data'];
        }

    } else if(isset($_GET['deleteModal'])){
        $deleteModal = $_GET['deleteModal'];
        $teamId = $_GET['teamId'];

    } else if(isset($_GET['playersModal'])){
        $playersModal = $_GET['playersModal'];
        $teamId = $_GET['teamId'];

        $apiResponseCP = sendDataToApi($routeCP, ['idTeam' => $teamId]);
        $decodedResponseCP = json_decode($apiResponseCP, true);
        if($decodedResponseCP['status'] === '200'){
            foreach ($decodedResponseCP['data'] as $player) {
                if($player['idTeam'] == $teamId && $player['typeUser'] == 'Player'){
                    $teamPlayers[] = $player;
                }
            }
        }
    }

    // Find the selected team in the $teams array
    foreach ($teams as $team) {
        if ($team['idTeam'] == $_GET['teamId']) {
            $selectedTeam = $team;
            break;
        }
    }

    $ranks = array(
        array('rank' => 'Petizes'),
        array('rank' => 'Traquinas'),
        array('rank' => 'Benjamins'),
        array('rank' => 'Infantis'),
        array('rank' => 'Iniciados'),
        array('rank' => 'Juvenis'),
        array('rank' => 'Juniores'),
        array('rank' => 'Seniores'),
        array('rank' => 'Sub-20'),
        array('rank' => 'Sub-21'),
        array('rank' => 'Sub-22'),
        array('rank' => 'Sub-23'),
    );


    $abs = array(
        array('ab' => 'A'),
        array('ab' => 'B'),
        array('ab' => 'A/B'),
    );


    $ages = array(
        array('age' => '7'),
        array('age' => '8'),
        array('age' => '9'),
        array('age' => '8/9'),
        array('age' => '10'),
        array('age' => '11'),
        array('age' => '10/11'),
        array('age' => '12'),
        array('age' => '13'),
        array('age' => '12/13'),
        array('age' => '14'),
        array('age' => '15'),
        array('age' => '14/15'),
        array('age' => '16'),
        array('age' => '17'),
        array('age' => '16/17'),
        array('age' => '18'),
        array('age' => '19'),
        array('age' => '18/19'),
        array('age' => '20'),
        array('age' => '21'),
        array('age' => '22'),
        array('age' => '23'),
    );

    $fields = array(
        array('field' => 5),
        array('field' => 7),
        array('field' => 9),
        array('field' => 11),
    );

?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Teams</h1>
    </div>

    <div class="fixed-button-container">
        <a href="#" class="btn my-btn-primary" data-toggle="modal" data-target="#createTeamModal" id="createTeam">
            <i class="fas fa-plus"></i> Create Team
        </a>
    </div>

    <div class="row">

        <?php foreach ($teams as $index => $team): ?>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="my-card-header py-3">
                    <a class="my-link" href="./base.php?route=teams&playersModal=true&teamId=<?= $team['idTeam'] ?>">
                        <h6 class="m-0 font-weight-bold"><?php echo $team['nameTeam'] ?></h6>
                    </a>
                        <a class="add-team" href="./base.php?route=teams&addModal=true&teamId=<?= $team['idTeam'] ?>">
                            <i class="fas fa-user-plus text-gray-300"></i>
                        </a>
                    </div>
                    <div class="card-body bgColor">
                        <p class="card-text">Age: <?= !empty($team['age']) ? $team['age'] : '-' ?></p>
                        <p class="card-text">Championship: <?= !empty($team['idChampionship']) ? $team['idChampionship'] : '-' ?></p>
                    </div>
                    <div class="my-card-footer">
                        <a class="btn my-btn-primary edit-team" data-teamid="<?= $team['idTeam'] ?>">
                            Edit
                        </a>
                        <a class="btn my-btn-primary delete-team" href="./base.php?route=teams&deleteModal=true&teamId=<?= $team['idTeam'] ?>">
                            Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <div class="modal fade" id="createTeamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createTeam" action="../../api/process/processCreateTeam.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="teamName">Team Name:</label>
                            <input type="text" class="form-control" id="teamName" name="nameTeam" required>
                        </div>

                        <div class="form-group">
                            <label for="ranksDropdown">Rank:</label>
                            <select id="ranksDropdown" name="ranksDropdown" class="form-control">
                                <option value=''>Select Rank</option>
                                <?php
                                foreach ($ranks as $rank) {
                                    echo "<option value='{$rank['rank']}'>{$rank['rank']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group" id="abDropdownContainer" style="display:none;">
                            <label for="abDropdown" id="selectedRank"></label>
                            <select id="abDropdown" name="abDropdown" class="form-control">
                                <option value=''>Select </option>
                                <?php             
                                    foreach ($abs as $ab) {
                                        echo "<option value='{$ab['ab']}'>{$ab['ab']}</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="agesDropdown">Age:</label>
                            <select id="agesDropdown" name="agesDropdown" class="form-control">
                                <option value=''>Select Age</option>
                                <?php
                                foreach ($ages as $age) {
                                    echo "<option value='{$age['age']}'>{$age['age']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="fieldsDropdown">Field Of:</label>
                            <select id="fieldsDropdown" name="fieldsDropdown" class="form-control">
                                <option value=''>Select Field</option>
                                <?php                                
                                foreach ($fields as $field) {
                                    echo "<option value='{$field['field']}'>{$field['field']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="coachesDropdown">Coach:</label>
                            <select id="coachesDropdown" name="coachesDropdown" class="form-control">
                                <option value=''>Select Coach</option>
                                <?php                                
                                foreach ($coaches as $coach) {
                                    echo "<option value='{$coach['idCoach']}'>{$coach['firstName']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="championshipsDropdown">Championship:</label>
                            <select id="championshipsDropdown" name="championshipsDropdown" class="form-control">
                                <option value=''>Select Championship</option>
                                <?php
                                foreach ($championships as $championship) {
                                    echo "<option value='{$championship['idChampionship']}'>{$championship['nameChampionship']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="createTeam" class="btn my-btn-primary">
                                Create Team
                            </button>
                            <button id="closeButton" type="button" class="btn my-btn-primary" data-dismiss="modal">
                                Close
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


<?php if ($editModal == true): ?>
    <div class="modal fade" id="updateTeamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (isset($team)): ?>
                        <form id="updateTeam" method="post" action="../../api/process/processUpdateTeam.php" enctype="multipart/form-data">
                            <input type="hidden" name="idTeam" value="<?php echo $selectedTeam['idTeam']; ?>">

                            <div class="form-group">
                                <label for="teamName">Team Name:</label>
                                <input type="text" class="form-control" id="teamName" name="nameTeam" value="<?php echo $selectedTeam['nameTeam']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="ranksDropdown">Rank:</label>
                                <select id="ranksDropdown" name="ranksDropdown" class="form-control">
                                <option value=''>Select Rank</option>
                                    <?php 
                                        foreach ($ranks as $rank) {
                                            $selected = ($selectedTeam['rank'] == $rank['rank']) ? 'selected' : '';
                                            echo "<option value='{$rank['rank']}' $selected>{$rank['rank']}</option>";
                                        }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group" id="abDropdownContainer">
                                <label for="abDropdown">AB:</label>
                                <select id="abDropdown" name="abDropdown" class="form-control">
                                <option value=''>Select </option>
                                    <?php

                                    foreach ($abs as $ab) {
                                        $selected = ($selectedTeam['ab'] == $ab['ab']) ? 'selected' : '';
                                        echo "<option value='{$ab['ab']}' $selected>{$ab['ab']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="agesDropdown">Age:</label>
                                <select id="agesDropdown" name="agesDropdown" class="form-control">
                                <option value=''>Select Age</option>
                                    <?php

                                    foreach ($ages as $age) {
                                        $selected = ($selectedTeam['age'] == $age['age']) ? 'selected' : '';
                                        echo "<option value='{$age['age']}' $selected>{$age['age']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="fieldsDropdown">Field Of:</label>
                                <select id="fieldsDropdown" name="fieldsDropdown" class="form-control">
                                <option value=''>Select Field</option>
                                    <?php
                                    foreach ($fields as $field) {
                                        $selected = ($selectedTeam['fieldOf'] == $field['field']) ? 'selected' : '';
                                        echo "<option value='{$field['field']}' $selected>{$field['field']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="coachesDropdown">Coach:</label>
                                <select id="coachesDropdown" name="coachesDropdown" class="form-control">
                                <option value=''>Select Coach</option>
                                <?php foreach ($coaches as $coach): ?>
                                    <?php
                                        $selected = ($selectedTeam['idCoach'] == $coach['idCoach']) ? 'selected' : '';
                                    ?>
                                    <option value='<?php echo $coach['idCoach']; ?>' <?php echo $selected; ?>><?php echo $coach['firstName']; ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="championshipsDropdown">Championship:</label>
                                <select id="championshipsDropdown" name="championshipsDropdown" class="form-control">
                                <option value=''>Select Championship</option>
                                <?php foreach ($championships as $championship): ?>
                                    <?php
                                        $selected = ($selectedTeam['idChampionship'] == $championship['idChampionship']) ? 'selected' : '';
                                    ?>
                                    <option value='<?php echo $championship['idChampionship']; ?>' <?php echo $selected; ?>><?php echo $championship['nameChampionship']; ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" name="updateTeam" class="btn my-btn-primary">
                                    Update
                                </button>
                                <button id="closeEditModal" type="button" class="btn my-btn-primary" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p>No team information available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($addModal == true): ?>
    <div class="modal fade" id="addUserTeam" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Users</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="generateCode" class="user" action="../../api/process/processInviteToTeam.php" method="POST">
                    <div class="modal-body">
                        <!-- Dropdown list -->
                        <label for="user">User:</label>
                        <select id="user" name="user" class="form-control">
                            <option value="" selected>Select User</option>
                            <?php
                                foreach ($users as $user) {
                                    $value = strtolower(str_replace(' ', '', $user['idUser'])); // Create a unique value based on the user's name
                                    $club = $user['nameClub'] == '' ? 'Doesn t have a Club!' : $user['nameClub'];
                                    $optionText = $user['firstName'] . ' ' . $user['lastName'] . ' - ' . $user['typeUser'] . ' - ' . $club;
                                    if($user['idTeam'] != $teamId){
                                        echo "<option value='" . $user['idUser'] . "' data-id-club='" . $user['idClub'] . "'>$optionText</option>";
                                    }
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
                        
                        <p>Code to enter Team:</p>
                        <div class="input-group">
                            <input type="text" class="form-control" id="teamRandomCode" name="teamRandomCode" readonly>
                            <div class="input-group-append">
                                <button type="button" class="btn my-btn-primary" onclick="generateAndSetTeamRandomCode()">Generate Code</button>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="idTeam" value="<?php echo $teamId; ?>">
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

<?php if ($deleteModal == true): ?>
    <div class="modal fade" id="deleteTeamModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Team Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="../../api/process/processDeleteTeam.php" method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to delete the team <?php echo $team['nameTeam']; ?>?</p>
                    </div>
                    <input type="hidden" name="idTeam" value="<?php echo $teamId; ?>">
                    <div class="modal-footer">
                        <!-- Confirm delete button on the left side -->
                        <button type="submit" name="deleteTeam" class="btn my-btn-primary">
                            Confirm Delete
                        </button>
                        
                        <!-- Cancel button on the right side -->
                        <button id="closeDeleteModal" type="button" class="btn my-btn-primary" data-dismiss="modal">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if($playersModal == true): ?>
    <div class="modal fade" id="addUserTeam" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Players</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <?php foreach ($teamPlayers as $player): ?>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <?= $player['firstName'] . ' ' . $player['lastName'] ?>
                                <a href="../../api/process/processLeaveTeam.php?idPlayer=<?= $player['idPlayer'] ?>&idTeam=<?= $teamId ?>" class="btn my-btn-primary">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </div>
                    <button id="closeAddUser" type="button" class="btn my-btn-primary mt-3" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>




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

    // Function to generate and set random code using PHP function in JavaScript
    function generateAndSetTeamRandomCode() {
        // Generate random code using PHP function
        var userDropdown = document.getElementById('user'); // get option selected
        var teamRandomCode = generateRandomCode();

        if (userDropdown.value !== "") {
            document.getElementById("teamRandomCode").value = teamRandomCode;
        }
        
    }
</script>

<script>
    // configurations to modal delete team
    $(document).ready(function () {
        // Listen for the click event on elements with the class 'delete-team'
        $('.delete-team').on('click', function () {
            // Get the idTeam from the data-teamid attribute
            var teamId = $(this).data('teamid');

            console.log('Clicked Delete for team ID:', teamId);

            // Set $bool to true and update the URL with teamId
            window.location.href = './base.php?route=teams&deleteModal=true&teamId=' + teamId;
        });

        // Show the deleteTeamModal when deleteModal=true is present in the URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('deleteModal') === 'true') {
            $('#deleteTeamModal').modal('show');
        }
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeDeleteModal').on('click', function () {
        window.location.href = './base.php?route=teams';
    });
    // end delete team
</script>


<!-- Script to open and close the modal -->
<script>
    // when $bool = true the modal id=teamInfoModal its opened
    $(document).ready(function(){
        // Show the modal on page load
        $('#updateTeamModal').modal('show');
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeEditModal').on('click', function() {
        window.location.href = './base.php?route=teams';
    });

    $('.edit-team').on('click', function() {
        // Get the idTeam from the data-idteam attribute
        var idTeam = $(this).data('teamid');

        console.log('Clicked Edit for team ID:', idTeam);

        // Set $bool to true and update the URL with idTeam
        window.location.href = './base.php?route=teams&editModal=true&teamId=' + idTeam;
    });
</script>
    <!-- End of script modal -->

<!-- Script to open and close the modal to add user-->
<script>
    // when $bool = true the modal id=teamInfoModal its opened
    $(document).ready(function(){        
        // Show the modal on page load
        $('#addUserTeam').modal('show');
        
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeAddUser').on('click', function() {
        window.location.href = './base.php?route=teams';
    });

    $('.add-team').on('click', function() {
        // Get the idTeam from the data-idteam attribute
        var idTeam = $(this).data('teamid');

        console.log('Clicked Add for team ID:', idTeam);

        // Set $bool to true and update the URL with idTeam
        window.location.href = './base.php?route=teams&addModal=true&teamId=' + idTeam;
    });
</script>
<!-- End of script modal -->

<script>
    $(document).ready(function () {
        // Use the correct id when triggering the modal
        $('#createTeam').click(function () {
            $('#createTeamModal').modal('show');
        });
    });
    
</script>

<script>
    // Add change event listener to the rank dropdown
    $('#ranksDropdown').change(function() {

        var selectedRank = $(this).val();
        
        // Update the label with the selected rank
        $('#selectedRank').text(selectedRank);

        // Check if a rank is selected
        if (selectedRank !== '') {
            // Show the A/B dropdown and its container
            $('#abDropdownContainer').show();
        } else {
            // Hide the A/B dropdown and its container
            $('#abDropdownContainer').hide();
        }
    });
</script>