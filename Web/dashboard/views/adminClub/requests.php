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

        $columnOrder1 = ['idRequestClub', 'fullName', 'nameClub', 'statee', 'idRequester', 'idClub'];

        $dataInOrder1 = [];

        foreach ($responseDataRequestsClub as $item) {
            $orderedItem1 = [];

            foreach ($columnOrder1 as $column) {
                if ($column === 'fullName') {
                    $orderedItem1['fullName'] = $item['firstName'] . ' ' . $item['lastName'];
                } else if (isset($item[$column])) {
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

        $columnOrder2 = ['idRequestTeam', 'fullName', 'nameTeam','statee', 'idRequester', 'idClub', 'idTeam'];

        $dataInOrder2 = [];
        
        foreach ($responseDataRequestsTeam as $item) {
            $orderedItem2 = [];

            foreach ($columnOrder2 as $column) {
                if ($column === 'fullName') {
                    $orderedItem2['fullName'] = $item['firstName'] . ' ' . $item['lastName'];
                } else if (isset($item[$column])) {
                    $orderedItem2[$column] = $item[$column];
                } else {
                    $orderedItem2[$column] = null;
                }
            }

            $dataInOrder2[] = $orderedItem2;
        }
    }    
?>

<!-- Begin Page Content -->

    <h1 class="h4 mb-0 text-gray-800">Requests to Club</h4>
    <br>

    <?php if($decodedResponseClub['status'] == '200'): ?> <!-- Check if has requests in Club -->
        <!-- show requests club -->
        
        <div class="table-responsive">  
            <table class="table table-hover" id="tables">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Id Request</th>
                        <th scope="col" class="text-center">Requester</th>
                        <th scope="col" class="text-center">Club</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataInOrder1 as $index => $row): ?>
                        <tr>
                            <?php foreach ($row as $columnName => $cell): ?>
                                <?php if ($columnName !== 'idRequester' && $columnName !== 'idClub'): ?>
                                    <?php if ($cell == ''): ?>
                                        <td>-</td>
                                    <?php else: ?>
                                        <td><?= $cell ?></td>
                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach; ?>
                            <td>
                                <!-- Edit button -->
                                <a href="../../api/process/processAccept.php?idRequestClub=<?= $row['idRequestClub'] ?>
                                &idRequester=<?= $row['idRequester'] ?>&idClub=<?= $row['idClub'] ?>" class="btn my-btn-primary">
                                    <i class="fas fa-check"></i>
                                </a>
                                <!-- Reject button -->
                                <a href="../../api/process/processReject.php?idRequestClub=<?= $row['idRequestClub'] ?>
                                &idRequester=<?= $row['idRequester'] ?>&idClub=<?= $row['idClub'] ?>" class="btn my-btn-primary">
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
                        <th scope="col" class="text-center">Requester</th>
                        <th scope="col" class="text-center">Team</th>
                        <th scope="col" class="text-center">Status</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataInOrder2 as $index => $row): ?>
                        <tr>
                            <?php foreach ($row as $columnName => $cell): ?>
                                <?php if ($columnName !== 'idRequester' && $columnName !== 'idClub' && $columnName !== 'idTeam'): ?>
                                    <?php if ($cell == ''): ?>
                                        <td>-</td>
                                    <?php else: ?>
                                        <td><?= $cell ?></td>
                                    <?php endif ?>
                                <?php endif ?>
                            <?php endforeach; ?>
                            <td>
                                <!-- Edit button -->
                                <a href="../../api/process/processAccept.php?idRequestTeam=<?= $row['idRequestTeam'] ?>
                                &idRequester=<?= $row['idRequester'] ?>
                                &idClub=<?= $row['idClub'] ?>
                                &idTeam=<?= $row['idTeam'] ?>" class="btn my-btn-primary">
                                    <i class="fas fa-check"></i>
                                </a>
                                <!-- Delete button -->
                                <a href="../../api/process/processReject.php?idRequestTeam=<?= $row['idRequestTeam'] ?>
                                &idRequester=<?= $row['idRequester'] ?>
                                &idClub=<?= $row['idClub'] ?>
                                &idTeam=<?= $row['idTeam'] ?>" class="btn my-btn-primary"> 
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