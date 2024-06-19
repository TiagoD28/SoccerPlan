<?php
    require_once '../../api/requests/getData.php';
    // URL of your API endpoint
    $route = 'Teams/index.php?route=getTeams';

    $apiResponse = getDataFromApi($route);

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

<table class="table table-hover" id="tables">
    <thead>
        <tr>
            <th scope="col">Id Team</th>
            <th scope="col">Name</th>
            <th scope="col">Age</th>
            <th scope="col">Leage</th>
            <th scope="col">Img</th>
            <th scope="col">Coach</th>
            <th scope="col">Actions</th>
            
        </tr>
    </thead>
    <tbody>
        <?php foreach ($apiResponse['data'] as $index => $row): ?>
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
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>