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

    $route3 = 'Codes/index.php?route=getCodesClub';
    $apiResponseCodesClub = sendDataToApi($route3, $data);
    $decodedResponseClub = json_decode($apiResponseCodesClub, true);
    
    if($decodedResponseClub['status'] == '200'){
        $responseDataCodesClub = $decodedResponseClub['data'];

        $columnOrder1 = ['idCode', 'randomCode', 'used', 'nameClub', 'fullNameGenerator', 'fullName'];

        $dataInOrder1 = [];
        foreach ($responseDataCodesClub as $item) {
            $orderedItem1 = [];

                foreach ($columnOrder1 as $column) {
                    if ($column === 'fullNameGenerator') {
                        $orderedItem1['fullNameGenerator'] = $item['firstNameGenerator'] . ' ' . $item['lastNameGenerator'];
                    } else if ($column === 'fullName') {
                        $orderedItem1['fullName'] = $item['firstName'] . ' ' . $item['lastName'];
                    } else if ($column === 'used') {
                        if (isset($item['used'])) {
                            $orderedItem1['used'] = ($item['used'] == "0") ? 'Not Used' : 'Used';
                        } else {
                            $orderedItem1['used'] = null;
                        }
                    } else if (isset($item[$column])) {
                        $orderedItem1[$column] = $item[$column];
                    } else {
                        $orderedItem1[$column] = null;
                    }
                }
        
            $dataInOrder1[] = $orderedItem1;
        }
    } 

    $route4 = 'Codes/index.php?route=getCodesTeam';
    $apiResponseRequestsTeam = sendDataToApi($route4, $data);
    $decodedResponseTeam = json_decode($apiResponseRequestsTeam, true);
    if($decodedResponseTeam['status'] == '200'){
        $responseDataRequestsTeam = $decodedResponseTeam['data'];

        $columnOrder2 = ['idCode', 'randomCode', 'used', 'nameTeam', 'fullNameGenerator', 'fullName'];

        $dataInOrder2 = [];
        
        foreach ($responseDataRequestsTeam as $item) {
            $orderedItem2 = [];

            foreach ($columnOrder2 as $column) {
                if ($column === 'fullNameGenerator') {
                    $orderedItem2['fullNameGenerator'] = $item['firstNameGenerator'] . ' ' . $item['lastNameGenerator'];
                } else if ($column === 'fullName') {
                    $orderedItem2['fullName'] = $item['firstName'] . ' ' . $item['lastName'];
                } else if ($column === 'used') {
                    if (isset($item['used'])) {
                        $orderedItem2['used'] = ($item['used'] == "0") ? 'Not Used' : 'Used';
                    } else {
                        $orderedItem2['used'] = null;
                    }
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

    <!-- <br> -->
    <h1 class="h4 mb-0 text-gray-800">Codes to Club</h4>
    <br>

    <?php if($decodedResponseClub['status'] == '200'): ?> <!-- Check if has requests in Club -->
        <!-- show requests club -->
        
        <div class="table-responsive">  
            <table class="table table-hover" id="tables">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Id Code</th>
                        <th scope="col" class="text-center">Code</th>
                        <th scope="col" class="text-center">Used</th>
                        <th scope="col" class="text-center">Club</th>
                        <th scope="col" class="text-center">Generator</th>
                        <th scope="col" class="text-center">Receiver</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataInOrder1 as $index => $row): ?>
                    <tr data-id="<?= $row['idCode'] ?>">
                        <?php foreach ($row as $column => $cell): ?>
                            <?php if ($cell == ''): ?>
                                <td>-</td>
                            <?php else: ?>
                                    <td><?= $cell ?></td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <td>
                            <!-- Delete button -->
                            <a href="../../api/process/processDeleteCode.php?idCodeClub=<?= $row['idCode'] ?>" class="btn my-btn-primary">
                                <i class="fas fa-trash-alt"></i>
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
    <br>
    <h1 class="h4 mb-0 text-gray-800">Codes to Teams</h1>
    <br>

    <?php if($decodedResponseTeam['status'] == '200'): ?> <!-- Check if has requests in Team -->
        <!-- show requests team -->
        <div class="table-responsive">  
            <table class="table table-hover" id="tables">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">Id Code</th>
                        <th scope="col" class="text-center">Code</th>
                        <th scope="col" class="text-center">Used</th>
                        <th scope="col" class="text-center">Team</th>
                        <th scope="col" class="text-center">Generator</th>
                        <th scope="col" class="text-center">Receiver</th>
                        <th scope="col" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataInOrder2 as $index => $row): ?>
                        <tr>
                            <?php foreach ($row as $column => $cell): ?>
                                <?php if ($cell == ''): ?>
                                    <td>-</td>
                                <?php else: ?>
                                        <td><?= $cell ?></td>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <td>
                                <!-- Delete button -->
                                <a href="../../api/process/processDeleteCode.php?idCodeTeam=<?= $row['idCode'] ?>"
                                class="btn my-btn-primary"> 
                                    <i class="fas fa-trash-alt"></i>
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
