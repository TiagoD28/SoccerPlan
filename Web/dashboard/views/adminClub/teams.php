<?php
    require_once '../../api/requests/getData.php';
    // URL of your API endpoint
    $route = 'Teams/index.php?route=getTeams';

    $apiResponse = getDataFromApi($route);
    $modal = false;
    $addModal = false;
    $selectedTeam = null;

    if (isset($_GET['teamId'])) {
        $teamId = $_GET['teamId'];
        $modal = true;
    } else {
        // There is no team id
        $modal = false;
    }

    // Decode JSON response
    // $responseData = json_decode($apiResponse, true);

    // // Check if the decoding was successful
    // if ($responseData !== null) {
    //     // Check if the 'status' key is set to success
    //     if (isset($responseData['status']) && $responseData['status'] === '200') {
    //         // Access the 'data' key and iterate through the clubs
    //         $data = $responseData['data'];
    //     } else {
    //         // Handle the case where the API response indicates an error
    //         echo "Error: {$responseData['message']}";
    //     }
    // } else {
    //     // Handle the case where JSON decoding failed
    //     echo "Error decoding API response.";
    // }

    // CASE I WANT TO REDUCE THE TEXT OF IMG
    // function truncateText($text, $maxLength) {
    //     return strlen($text) > $maxLength ? substr($text, 0, $maxLength) . '...' : $text;
    // }

    // foreach ($data as &$row) {
    //     // Truncate 'Name' field
    //     $row['img'] = truncateText($row['img'], 20);
    // }
?>


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Teams</h1>
    </div>

    <div class="row">
        <?php foreach ($apiResponse['data'] as $index => $team): ?>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="my-card-header py-3">
                        <!-- <a href="./teams.php?team_id=<?php echo $team['nameTeam']; ?>"> -->
                        <a href="./base.php?route=teams&teamId=<?= 1 ?>" class="my-link">
                            <h6 class="m-0 font-weight-bold"><?php echo $team['nameTeam'] ?></h6>
                        </a>
                        <a href="#">
                            <i class="fas fa-user-plus text-gray-300"></i>
                        </a>
                    </div>
                    <div class="card-body bgColor">
                        <p class="card-text">ID: <?= $team['idTeam'] ?></p>
                        <p class="card-text">Age: <?= $team['age'] ?></p>
                        <p class="card-text">Championship: <?= $team['idChampionship'] ?></p>
                        <!-- Add more information as needed -->
                    </div>
                    <div class="my-card-footer">
                        <!-- Edit and Delete buttons can be added here -->
                        <!-- <a id="editButton" class="btn btn-primary edit-team">
                            Edit
                        </a> -->
                        <a class="btn btn-primary edit-team" data-teamid="<?= $team['idTeam'] ?>">
                            Edit
                        </a>
                        <a href="#" class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


<?php if($modal == true): ?>
    <div class="modal fade" id="teamInfoModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Team Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Display team information here -->
                    <?php if (isset($team)): ?>
                        <p>Team ID: <?php echo $team['idTeam']; ?></p>
                        <p>Name: <?php echo $team['nameTeam']; ?></p>
                        <!-- Add more information as needed -->
                    <?php else: ?>
                        <p>No team information available.</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button id="closeButton" type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>                    
<?php endif; ?>

