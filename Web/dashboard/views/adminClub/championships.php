<?php
    require_once '../../api/requests/getData.php';
    // URL of your API endpoint
    $route = 'Teams/index.php?route=getTeamsClub';
    $routeA = 'Adversaries/index.php?route=getAdversaries';
    $routeC = 'Coaches/index.php?route=getCoachesClub';
    $routeCP = 'Users/index.php?route=getCoachesPlayers';
    $routeChamp = 'Championships/index.php?route=getChampionshipsClub';

    $dataC = [
        'idClub' => $_SESSION['idClub']
    ];

    $apiResponseA = getDataFromApi($routeA);

    $apiResponse = sendDataToApi($route, $dataC);
    $apiResponseC = sendDataToApi($routeC, $dataC);
    $apiResponseChamp = sendDataToApi($routeChamp, $dataC);

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
    $championshipModal = false;
    $adversaries = [];
    $selectedTeam = null;
    $users = [];

    if (isset($_GET['editModal'])) {
        $championshipId = $_GET['championshipId'];
        $editModal = $_GET['editModal'];
    }
    else if(isset($_GET['deleteModal'])){
        $deleteModal = $_GET['deleteModal'];
        $championshipId = $_GET['championshipId'];

    } else if(isset($_GET['adversariesModal'])){
        $adversariesModal = $_GET['adversariesModal'];
        $championshipId = $_GET['championshipId'];

        foreach ($apiResponseA['data'] as $adversary) {
            if($adversary['idChampionship'] == $championshipId){
                $adversaries[] = $adversary;
            }
        }
    }

    // get the selected championship
    foreach ($championships as $championship) {
        if ($championship['idChampionship'] == $_GET['championshipId']) {
            $selectedChampionship = $championship;
            break;
        }
    }

    // to count the number of adversaries of each championship
    $allAdversaries = $apiResponseA['data'];

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

    // Get the current year
    $currentYear = date('Y');

    // Set the start year
    $startYear = 2010;

    // Set the end year as the next year
    $endYear = $currentYear + 1;

    // Initialize the seasons array
    $seasons = array();

    // Loop through the years and add seasons to the array
    for ($year = $startYear; $year <= $endYear; $year++) {
        $seasons[] = array('season' => $year . '/' . ($year + 1));
    }

    $fields = array(
        array('field' => 5),
        array('field' => 7),
        array('field' => 9),
        array('field' => 11),
    );
?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Championships</h1>
    </div>

    <div class="fixed-button-container">
        <a href="#" class="btn my-btn-primary" data-toggle="modal" data-target="#createChampionshipModal" id="createChampionship">
            <i class="fas fa-plus"></i> Create Championship
        </a>
    </div>

    <div class="row">
        <?php foreach ($championships as $index => $championship): ?>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="my-card-header py-3">
                        <a href="./base.php?route=championships&adversariesModal=true&championshipId=<?= $championship['idChampionship'] ?>" class="my-link">
                            <h6 class="m-0 font-weight-bold"><?php echo $championship['nameChampionship'] ?></h6>
                        </a>
                    </div>
                    <div class="card-body bgColor">
                        <!-- <p class="card-text">ID: <?= $championship['idChampionship'] ?></p> -->
                        <p class="card-text">Season: <?= $championship['season'] ?></p>
                        <p class="card-text">Rank: <?= $championship['rank'] ?></p>
                        <!-- get the number of adversary teams -->
                        <p class="card-text">Adversaries: <?= count(array_filter($allAdversaries, function($adversary) use ($championship) {
                            return $adversary['idChampionship'] == $championship['idChampionship'];
                        })) ?> </p>
                    </div>
                    <div class="my-card-footer">
                        <a class="btn my-btn-primary edit-team" data-championshipid="<?= $championship['idChampionship'] ?>">
                            Edit
                        </a>
                        <a class="btn my-btn-primary delete-championship" data-championshipid="<?= $championship['idChampionship'] ?>">
                            Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <div class="modal fade" id="createChampionshipModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Championship</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createChampionship" action="../../api/process/processCreateChampionship.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="teamName">Championship Name:</label>
                            <input type="text" class="form-control" id="championshipName" name="nameChampionship" required>
                        </div>

                        <div class="form-group">
                            <label for="seasonsDropdown">Season:</label>
                            <select id="seasonsDropdown" name="seasonsDropdown" class="form-control" required>
                                <option value=''>Select Season</option>
                                <?php                                
                                foreach ($seasons as $season) {
                                    echo "<option value='{$season['season']}'>{$season['season']}</option>";
                                }
                                ?>
                            </select>
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
                            <label for="teamsDropdown">Teams:</label>
                            <select id="teamsDropdown" name="teamsDropdown" class="form-control">
                                <option value=''>Select Team</option>
                                <?php
                                foreach ($teams as $team) {
                                    echo "<option value='{$team['idTeam']}'>{$team['nameTeam']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="createChampionship" class="btn my-btn-primary">
                                Create Championship
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

<?php if($adversariesModal == true): ?>
    <div class="modal fade" id="adversariesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <h5 class="modal-title" id="exampleModalLabel">Adversary Teams</h5>
                        <a class="add-adversary ml-2" href="#" data-toggle="modal" data-target="#addAdversaryModal" >
                            <i class="fas fa-user-plus text-gray-800"></i>
                        </a>
                    </div>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <?php if (isset($adversaries) && !empty($adversaries)): ?>
                        <ul class="list-group">
                            <?php foreach ($adversaries as $adversary): ?>
                                <li class="list-group-item adversary-row" data-adversary-id="<?php echo $adversary['idAdversaryTeam']; ?>">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <span class="club-name"><?php echo $adversary['nameClub']; ?></span>
                                            <span class="club-info">(Club)</span>
                                        </div>
                                        <div class="text-right">
                                            <span class="badge badge-pill badge-primary my-secondary-bgColor"><?php echo $adversary['goalsScored'] ? $adversary['goalsScored'] : '0'; ?> Scored</span>
                                            <span class="badge badge-pill badge-primary my-secondary-bgColor"><?php echo $adversary['goalsConceded'] ? $adversary['goalsConceded'] : '0'; ?> Conceded</span>
                                            <span>(Goals)</span>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php else: ?>
                        <p>No adversary teams information available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button id="closeAdversaryModal" type="button" class="btn my-btn-primary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Second Modal to Add Team -->
    <div class="modal fade" id="addAdversaryModal" tabindex="-1" role="dialog" aria-labelledby="addAdversaryModalLabel" aria-hidden="true">
        <!-- Add your content for the second modal here -->
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addAdversaryModalLabel">Create Adversary Team</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="addAdversaryForm" action="../../api/process/processCreateAdversary.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="idChampionship" value="<?= $selectedChampionship['idChampionship'] ?>">
                        <div class="form-group">
                            <label for="clubName">Club Name:</label>
                            <input type="text" class="form-control" id="clubName" name="clubName" required>
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
                        <div class="modal-footer">
                            <button type="submit" name="addAdversaryForm" class="btn my-btn-primary">Create Adversary Team</button>
                            <button type="button" class="btn my-btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($editModal == true): ?>
    <div class="modal fade" id="updateChampionshipModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Championship</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (isset($championship)): ?>
                        <form id="updateChampionship" method="post" action="../../api/process/processUpdateChampionship.php" enctype="multipart/form-data">
                            <input type="hidden" name="idChampionship" value="<?php echo $selectedChampionship['idChampionship']; ?>">

                            <div class="form-group">
                                <label for="championshipName">Championship Name:</label>
                                <input type="text" class="form-control" id="championshipName" name="nameChampionship" value="<?php echo $selectedChampionship['nameChampionship']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="seasonsDropdown">Season:</label>
                                <select id="seasonsDropdown" name="seasonsDropdown" class="form-control">
                                    <option value=''>Select Season</option>
                                    <?php foreach ($seasons as $season): ?>
                                        <?php
                                            $selected = ($selectedChampionship['season'] == $season['season']) ? 'selected' : '';
                                        ?>
                                        <option value='<?php echo $season['season']; ?>' <?php echo $selected; ?>><?php echo $season['season']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="ranksDropdown">Rank:</label>
                                <select id="ranksDropdown" name="ranksDropdown" class="form-control">
                                <option value=''>Select Rank</option>
                                <?php foreach ($ranks as $rank): ?>
                                    <?php
                                        $selected = ($selectedChampionship['rank'] == $rank['rank']) ? 'selected' : '';
                                    ?>
                                    <option value='<?php echo $rank['rank']; ?>' <?php echo $selected; ?>><?php echo $rank['rank']; ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="fieldsDropdown">Field Of:</label>
                                <select id="fieldsDropdown" name="fieldsDropdown" class="form-control">
                                <option value=''>Select Field</option>
                                <?php foreach ($fields as $field): ?>
                                    <?php
                                        $selected = ($selectedChampionship['fieldOf'] == $field['field']) ? 'selected' : '';
                                    ?>
                                    <option value='<?php echo $field['field']; ?>' <?php echo $selected; ?>><?php echo $field['field']; ?></option>
                                <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" name="updateChampionship" class="btn my-btn-primary">
                                    Update
                                </button>
                                <button id="closeEditModal" type="button" class="btn my-btn-primary" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p>No championship information available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php if ($deleteModal == true): ?>
    <div class="modal fade" id="deleteChampionshipModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Championship Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="../../api/process/processDeleteChampionship.php" method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to delete the championship <?php echo $championship['nameChampionship']; ?>?</p>
                    </div>
                    <input type="hidden" name="idChampionship" value="<?php echo $championshipId; ?>">
                    <div class="modal-footer">
                        <!-- Confirm delete button on the left side -->
                        <button type="submit" name="deleteChampionship" class="btn my-btn-primary">
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
        // Listen for the click event on elements with the class 'delete-championship'
        $('.delete-championship').on('click', function (e) {
            e.preventDefault(); // prevent the default link behavior

            // Get the championshipId from the data-championshipid attribute
            var championshipId = $(this).data('championshipid');

            console.log('Clicked Delete for championship ID:', championshipId);

            // Set $bool to true and update the URL with championshipId
            window.location.href = './base.php?route=championships&deleteModal=true&championshipId=' + championshipId;
        });

        // Show the deleteChampionshipModal when deleteModal=true is present in the URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('deleteModal') === 'true') {
            $('#deleteChampionshipModal').modal('show');
        }
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeDeleteModal').on('click', function () {
        window.location.href = './base.php?route=championships';
    });

</script>

<script>
    // when $bool = true the modal id=teamInfoModal its opened
    $(document).ready(function(){
        // Show the modal on page load
        $('#adversariesModal').modal('show');
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeAdversaryModal').on('click', function() {
        window.location.href = './base.php?route=championships';
    });
</script>

<!-- Script to open and close the modal -->
<script>
    // when $bool = true the modal id=teamInfoModal its opened
    $(document).ready(function(){
        // Show the modal on page load
        $('#updateChampionshipModal').modal('show');
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeEditModal').on('click', function() {
        // window.location.href = './teams.php?boll=false';
        window.location.href = './base.php?route=championships';
    });

    $('.edit-team').on('click', function() {
        // Get the idTeam from the data-idteam attribute
        var championshipId = $(this).data('championshipid');

        console.log('Clicked Edit for team ID:', championshipId);

        // Set $bool to true and update the URL with idTeam
        window.location.href = './base.php?route=championships&editModal=true&championshipId=' + championshipId;
    });
</script>
<!-- End of script modal -->

<script>
    $(document).ready(function () {
        $('#createChampionship').click(function () {
            $('#createChampionshipModal').modal('show');
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