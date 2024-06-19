<?php
    require_once '../../api/requests/getData.php';
    // URL of your API endpoint
    $route = 'Players/index.php?route=getPlayers';

    $apiResponse = getDataFromApi($route);
    $data = $apiResponse['data'];

    // missing the idTeam
    $columnOrder = ['idPlayer', 'username', 'firstName', 'lastName', 'age', 'nacionality', 'weight', 'imc', 'position', 'state', 'salary', 'img', 'idClothingSize', 'email', 'pass'];

    $dataInOrder = [];

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

    // CASE I WANT TO REDUCE THE TEXT OF IMG
    // function truncateText($text, $maxLength) {
    //     return strlen($text) > $maxLength ? substr($text, 0, $maxLength) . '...' : $text;
    // }

    // foreach ($data as &$row) {
    //     // Truncate 'Name' field
    //     $row['img'] = truncateText($row['img'], 20);
    // }
?>




<h1 class="h3 mb-0 text-gray-800">Players</h1>

<div class="table-responsive">  
    <table class="table table-hover" id="tables">
        <thead>
            <tr>
                <th scope="col">Id Player</th>
                <th scope="col">Username</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">Age</th>
                <th scope="col">Nacionality</th>
                <th scope="col">Weight</th>
                <th scope="col">IMC</th>
                <th scope="col">Position</th>
                <th scope="col">State</th>
                <th scope="col">Salary</th>
                <th scope="col">Img</th>
                <th scope="col">Id Clothes Size</th>
                <th scope="col">Email</th>
                <th scope="col">Password</th>
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
                            <!-- Edit button -->
                            <a>
                                <i class="fas fa-edit"></i>
                            </a>
                            <!-- Delete button -->
                            <a>
                                <i class="fas fa-trash-alt"></i>
                            </a>                  
                        </td> 
                        <!-- <td>
                        <script src="https://cdn.lordicon.com/lordicon.js"></script>
                        <lord-icon
                            src="https://cdn.lordicon.com/skkahier.json"
                            trigger="hover"
                            style="width:25px;height:25px">
                        </lord-icon> -->

                        <!-- Edit button -->
                        <!-- <button>Edit</button> -->
                    
                        <!-- Delete button -->
                        <!-- <i class="fa-solid fa-trash-can fa-bounce" style="color: #041b2b;"></i> -->
                        <!-- <i class="bi bi-trash3"></i> -->
                        <!-- <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                        </svg> -->
                    
                        <!-- </td>                 -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
