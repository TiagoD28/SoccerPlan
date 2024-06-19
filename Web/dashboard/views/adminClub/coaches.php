<?php
    require_once '../../api/requests/getData.php';
    require_once '../../api/requests/sendData.php';
    
    $route = 'Coaches/index.php?route=getCoachesClub';

    $data = [
        'idClub' => $_SESSION['idClub'],
    ];

    $apiResponse = sendDataToApi($route, $data);
    $decodedResponse = json_decode($apiResponse, true);
    $data = $decodedResponse['data'];

    $columnOrder = ['idCoach', 'username', 'firstName', 'lastName', 'age', 'nacionality', 'email'];

    $dataInOrder = [];

    if(!empty($data)){
    
        foreach ($data as $item) {
            $orderedItem = [];
    
            foreach ($columnOrder as $column) {
                if (isset($item[$column])) {
                    $orderedItem[$column] = $item[$column];
                } else {
                    $orderedItem[$column] = null;
                }
            }
    
            $dataInOrder[] = $orderedItem;
        }
    }

?>

<h1 class="h3 mb-0 text-gray-800">Coaches</h1>

<?php if($dataInOrder): ?>
<div class="table-responsive">  
    <table class="table table-hover" id="tables">
        <thead>
            <tr>
                <th scope="col">Id Coach</th>
                <th scope="col">Username</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Age</th>
                <th scope="col">Nationality</th>
                <th scope="col">Email</th>
                <th scope="col">Actions</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dataInOrder as $index => $row): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                        <?php if($cell == ''): ?>
                        <td>-</td>
                    <?php else: ?>
                        <td><?= $cell ?></td>
                    <?php endif ?>
                    <?php endforeach; ?>
                        <td>
                            <!-- Delete button -->
                            <a href="../../api/process/processLeaveClub.php?idCoach=<?= $row['idCoach'] ?>
                            &idClub=<?= $_SESSION['idClub'] ?>" class="btn my-btn-primary"> 
                                <i class="fas fa-trash-alt"></i>
                            </a>                     
                        </td> 
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php else: ?>
    <div>
        <p>Club doesn't have coaches!</p>
    </div>
<?php endif; ?>