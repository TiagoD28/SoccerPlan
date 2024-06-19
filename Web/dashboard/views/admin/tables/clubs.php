<?php
    require_once '../../api/requests/getData.php';

    // URL of my API endpoint
    $route = 'Clubs/index.php?route=getClubs';

    $apiResponse = getDataFromApi($route);

    // CASE I WANT TO REDUCE THE TEXT OF IMG
    // function truncateText($text, $maxLength) {
    //     return strlen($text) > $maxLength ? substr($text, 0, $maxLength) . '...' : $text;
    // }

    // foreach ($data as &$row) {
    //     // Truncate 'Name' field
    //     $row['img'] = truncateText($row['img'], 20);
    // }
?>

<!-- IMPORTANT -->
<!-- to eliminate a club i have two options to do -->
<!-- 1. Put this in all tables that contains FK idClub -->
<!-- FOREIGN KEY (idClub) REFERENCES clubs(idClub) ON DELETE CASCADE; -->
<!-- 2. Do this in all tables that contains FK idClub -->
<!-- DELETE FROM events WHERE idClub = :clubId;
DELETE FROM clubs WHERE idClub = :clubId; -->



<h1 class="h3 mb-0 text-gray-800">Clubs</h1>
<div class="table-responsive">
    <!-- <table class="table table-hover" id="tables"> -->
    <table class="table table-hover">
    <!-- <table class="table align-middle mb-0 bg-white"> -->
        <!-- <thead class="bg-light"> -->
        <thead>
            <tr>
                <th scope="col" class="text-center">Id Club</th>
                <th scope="col" class="text-center">Name</th>
                <th scope="col" class="text-center">Age</th>
                <th scope="col" class="text-center">Local</th>
                <th scope="col" class="text-center">Nacionality</th>
                <th scope="col" class="text-center">Img</th>
                <th scope="col" class="text-center">Id Amin</th>
                <th scope="col" class="text-center">Actions</th>
                
            </tr>
        </thead>
        <tbody>
            <?php foreach ($apiResponse['data'] as $index => $row): ?>
                <tr>
                    <?php foreach ($row as $cell): ?>
                        <?php if($cell == ''): ?>
                        <td class="align-middle">-</td>
                    <?php else: ?>
                        <td class="align-middle"><?= $cell ?></td>
                    <?php endif ?>
                    <?php endforeach; ?>
                        <td>
                        <!-- Edit button -->
                        <a href="edit.php?idClub=<?= $row['idClub'] ?>">
                            <i class="fas fa-edit"></i>
                        </a>
                        <!-- Delete button -->
                        <a  href="../../api/actions/delete.php?tablename=<?= 'Clubs' ?>&key=<?= 'idClub' ?>&value=<?= $row['idClub'] ?>">
                            <i class="fas fa-trash-alt"></i>
                        </a>                    
                        </td>                
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>