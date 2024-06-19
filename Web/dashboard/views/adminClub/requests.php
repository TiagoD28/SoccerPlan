<?php 
    require_once '../../api/requests/getData.php';
    require_once '../../api/requests/sendData.php';
    
    // URL of your API endpoint
    $route = 'Users/index.php?route=getUsers';
    $responseData = getDataFromApi($route);
    $rowCount = count($responseData['data']);


    $routeClubs = 'Clubs/index.php?route=getClubs';
    $apiResponseClubs = getDataFromApi($routeClubs);
    $responseDataClubs = $apiResponseClubs['data'];

    $data = [
        "idClub" => $_SESSION['idClub']
    ];

    $route3 = 'Requests/index.php?route=getRequestsClub';
    $apiResponseRequestsClub = sendDataToApi($route3, $data);
    $decodedResponseClub = json_decode($apiResponseRequestsClub, true);
    if($decodedResponseClub['status'] == '200'){
        $responseDataRequestsClub = $decodedResponseClub['data'];

        $columnOrder1 = ['idRequestClub', 'idRequester', 'idClub', 'statee'];

        $dataInOrder1 = [];

        foreach ($responseDataRequestsClub as $item) {
            $orderedItem1 = [];

            foreach ($columnOrder1 as $column) {
                if (isset($item[$column])) {
                    $orderedItem1[$column] = $item[$column];
                } else {
                    $orderedItem1[$column] = null;
                }
            }

            $dataInOrder1[] = $orderedItem1;
        }
    }

    $route4 = 'Requests/index.php?route=getRequestsTeam';
    $apiResponseRequestsTeam = sendDataToApi($route4, $data);
    $decodedResponseTeam = json_decode($apiResponseRequestsTeam, true);
    if($decodedResponseTeam['status'] == '200'){
        $responseDataRequestsTeam = $decodedResponseTeam['data'];

        $columnOrder2 = ['idRequestTeam', 'idRequester', 'idClub', 'idTeam','statee'];

        $dataInOrder2 = [];
        
        foreach ($responseDataRequestsTeam as $item) {
            $orderedItem2 = [];

            foreach ($columnOrder2 as $column) {
                if (isset($item[$column])) {
                    $orderedItem2[$column] = $item[$column];
                } else {
                    $orderedItem2[$column] = null;
                }
            }

            $dataInOrder2[] = $orderedItem2;
        }
    }    

    // This is to get the idUser to set in script and then send joined with url request
    echo '<script>var idUser = ' . json_encode($_SESSION['idUser']) . ';</script>';
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <?php if(isset($_SESSION['idEmployer'])): ?>  <!-- If its an employer -->

        <br>
        <h1 class="h4 mb-0 text-gray-800">Requests to Club</h4>
        <br>

        <?php if($_SESSION['idClub'] != ''): ?>  <!-- Check if employer has a club -->

            <?php if($decodedResponseClub['status'] == '200'): ?> <!-- Check if has requests in Club -->
                <!-- show requests club -->
                
                <div class="table-responsive">  
                    <table class="table table-hover" id="tables">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Id Request</th>
                                <th scope="col" class="text-center">Id Requester</th>
                                <th scope="col" class="text-center">Id Club</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataInOrder1 as $index => $row): ?>
                                <tr>
                                    <?php foreach ($row as $cell): ?>
                                        <?php if($cell == ''): ?>
                                        <td>-</td>
                                    <?php else: ?>
                                        <td><?= $cell ?></td>
                                    <?php endif ?>
                                    <?php endforeach; ?>
                                    <td>

                                        <!-- Edit button -->
                                        <a href="../../api/process/processAccept.php?idRequestClub=<?= $row['idRequestClub'] ?>
                                        &idRequester=<?= $row['idRequester'] ?>
                                        &idClub=<?= $row['idClub'] ?>" class="btn btn-check"> 
                                            <!-- <i class="fas fa-edit"></i> -->
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <!-- Delete button -->
                                        <a href="../../api/process/processReject.php?idRequestClub=<?= $row['idRequestClub'] ?>
                                        &idRequester=<?= $row['idRequester'] ?>
                                        &idClub=<?= $row['idClub'] ?>" class="btn btn-danger">  
                                            <!-- <i class="fas fa-trash-alt"></i> -->
                                            <i class="fas fa-times"></i>
                                        </a>                     
                                    </td> 
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <?php echo $decodedResponseClub['message']; ?>

            <?php endif; ?>

            <br>
            <h1 class="h4 mb-0 text-gray-800">Requests to Teams</h1>
            <br>

            <?php if($decodedResponseTeam['status'] == '200'): ?> <!-- Check if has requests in Team -->
                <!-- show requests team -->
                
                <div class="table-responsive">  
                    <table class="table table-hover" id="tables">
                        <thead>
                            <tr>
                                <th scope="col" class="text-center">Id Request</th>
                                <th scope="col" class="text-center">Id Requester</th>
                                <th scope="col" class="text-center">Id Club</th>
                                <th scope="col" class="text-center">Id Team</th>
                                <th scope="col" class="text-center">Status</th>
                                <th scope="col" class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataInOrder2 as $index => $row): ?>
                                <tr>
                                    <?php foreach ($row as $cell): ?>
                                        <?php if($cell == ''): ?>
                                        <td>-</td>
                                    <?php else: ?>
                                        <td><?= $cell ?></td>
                                    <?php endif ?>
                                    <?php endforeach; ?>
                                    <td>
                                        <!-- Edit button -->
                                        <a href="../../api/process/processAccept.php?idRequestTeam=<?= $row['idRequestTeam'] ?>
                                        &idRequester=<?= $row['idRequester'] ?>
                                        &idClub=<?= $row['idClub'] ?>
                                        &idTeam=<?= $row['idTeam'] ?>" class="btn btn-check"> 
                                            <!-- <i class="fas fa-edit"></i> -->
                                            <i class="fas fa-check"></i>
                                        </a>
                                        <!-- Delete button -->
                                        <a href="../../api/process/processReject.php?idRequestTeam=<?= $row['idRequestTeam'] ?>
                                        &idRequester=<?= $row['idRequester'] ?>
                                        &idClub=<?= $row['idClub'] ?>
                                        &idTeam=<?= $row['idTeam'] ?>" class="btn btn-danger">  
                                            <!-- <i class="fas fa-trash-alt"></i> -->
                                            <i class="fas fa-times"></i>
                                        </a>                    
                                    </td> 
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>

                <?php echo $decodedResponseTeam['message']; ?>

            <?php endif; ?>

        <?php else: ?> <!-- Else if its not in a club -->
            <!-- Put the cards of requests -->
            <div class="row">
            <!-- <div class="row justify-content-center"> -->
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
                                    <select class="custom-select" id="userTypeSelect" name="typeUser">
                                        <option selected>Choose...</option>
                                        <?php foreach ($responseDataClubs as $club): ?>
                                            <option value="<?php echo $club['idClub']; ?>"><?php echo $club['nameClub']; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <a href="" class="my-a-btn btn my-btn-primary btn-user btn-block" id="sendRequestBtn">
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
                                <input type="email" class="form-control form-control-user" 
                                    id="code" name="code" placeholder="Code">
                            </div>
                            <a href="path/to/your/script.php" class="my-a-btn btn my-btn-primary btn-user btn-block">
                                Send Code
                            </a>
                        </div>
                    </div>
                    
                </div>
            </div>

        <?php endif; ?>

    <?php else: ?>

        <br>
        <h1 class="h4 mb-0 text-gray-800">Requests to Club</h4>
        <br>

        <?php if($decodedResponseClub['status'] == '200'): ?> <!-- Check if has requests in Club -->
            <!-- show requests club -->
            
            <div class="table-responsive">  
                <table class="table table-hover" id="tables">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Id Request</th>
                            <th scope="col" class="text-center">Id Requester</th>
                            <th scope="col" class="text-center">Id Club</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataInOrder1 as $index => $row): ?>
                            <tr data-id="<?= $row['idRequestClub'] ?>">
                                <?php foreach ($row as $cell): ?>
                                    <?php if($cell == ''): ?>
                                    <td>-</td>
                                <?php else: ?>
                                    <td><?= $cell ?></td>
                                <?php endif ?>
                                <?php endforeach; ?>
                                <td>
                                    <!-- Edit button -->
                                    <a href="../../api/process/processAccept.php?idRequestClub=<?= $row['idRequestClub'] ?>
                                    &idRequester=<?= $row['idRequester'] ?>
                                    &idClub=<?= $row['idClub'] ?>" class="btn btn-success">
                                        <!-- <i class="fas fa-edit"></i> -->
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <!-- Delete button -->
                                    <a href="../../api/process/processReject.php?idRequestClub=<?= $row['idRequestClub'] ?>
                                    &idRequester=<?= $row['idRequester'] ?>
                                    &idClub=<?= $row['idClub'] ?>" class="btn btn-danger"> 
                                        <!-- <i class="fas fa-trash-alt"></i> -->
                                        <i class="fas fa-times"></i>
                                    </a>                    
                                </td> 
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>

            <?php echo $decodedResponseClub['message']; ?>

        <?php endif; ?>


        <br>
        <h1 class="h4 mb-0 text-gray-800">Requests to Teams</h1>
        <br>

        <?php if($decodedResponseTeam['status'] == '200'): ?> <!-- Check if has requests in Team -->
            <!-- show requests team -->
            <div class="table-responsive">  
                <table class="table table-hover" id="tables">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">Id Request</th>
                            <th scope="col" class="text-center">Id Requester</th>
                            <th scope="col" class="text-center">Id Club</th>
                            <th scope="col" class="text-center">Id Team</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($dataInOrder2 as $index => $row): ?>
                            <tr>
                                <?php foreach ($row as $cell): ?>
                                    <?php if($cell == ''): ?>
                                    <td>-</td>
                                <?php else: ?>
                                    <td><?= $cell ?></td>
                                <?php endif ?>
                                <?php endforeach; ?>
                                <td>
                                    <!-- Edit button -->
                                    <a href="../../api/process/processAccept.php?idRequestTeam=<?= $row['idRequestTeam'] ?>
                                    &idRequester=<?= $row['idRequester'] ?>
                                    &idClub=<?= $row['idClub'] ?>
                                    &idTeam=<?= $row['idTeam'] ?>" class="btn btn-success">
                                        <!-- <i class="fas fa-edit"></i> -->
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <!-- Delete button -->
                                    <a href="../../api/process/processReject.php?idRequestTeam=<?= $row['idRequestTeam'] ?>
                                    &idRequester=<?= $row['idRequester'] ?>
                                    &idClub=<?= $row['idClub'] ?>
                                    &idTeam=<?= $row['idTeam'] ?>" class="btn btn-danger"> 
                                        <!-- <i class="fas fa-trash-alt"></i> -->
                                        <i class="fas fa-times"></i>
                                    </a>                    
                                </td> 
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        <?php else: ?>

            <?php echo $decodedResponseTeam['message']; ?>

        <?php endif; ?>

    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var sendRequestBtn = document.getElementById('sendRequestBtn');

            sendRequestBtn.addEventListener('click', function() {
                event.preventDefault();
                // Get the selected club ID from the dropdown
                var selectedClubId = document.getElementById('userTypeSelect').value;

                // Check if the ID is not empty
                if (!selectedClubId || selectedClubId === 'Choose...') {
                    // Alert the user that they need to choose a club
                    alert('Please choose a club before sending the request.');
                } else {
                    // Construct the URL with the selected club ID as a query parameter
                    var url = '../../api/sendRequest.php?idClub=' + selectedClubId + '&idRequester=' + idUser;

                    // Redirect to the specified URL
                    // alert(url);
                    window.location.href = url;
                }
            });
        });
    </script>
</div>