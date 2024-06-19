<?php
    require_once '../../api/requests/getData.php';
    // URL of your API endpoint
    

    $data = [
        'idClub' => $_SESSION['idClub']
    ];


    $routeClothe = 'Clothes/index.php?route=getClothes';
    $apiResponse = sendDataToApi($routeClothe, $data);
    $decodedResponse = json_decode($apiResponse, true);    

    if($decodedResponse['status'] === '200'){
        $clothes = $decodedResponse['data'];
    }

    
    $editModal = false;
    $deleteModal = false;
    $clotheModal = false;

    if (isset($_GET['editModal'])) {
        $clotheId = $_GET['clotheId'];
        $editModal = $_GET['editModal'];
    } else if(isset($_GET['deleteModal'])){
        $deleteModal = $_GET['deleteModal'];
        $clotheId = $_GET['clotheId'];

    }

    // get the selected clothe
    foreach ($clothes as $clothe) {
        if ($clothe['idClothe'] == $_GET['clotheId']) {
            $selectedClothe = $clothe;
            break;
        }
    }

    // Get the current year
    $currentYear = date('Y');
    $startYear = 2010;
    $endYear = $currentYear + 1;

    $seasons = array();

    // Loop through the years and add seasons to the array
    for ($year = $startYear; $year <= $endYear; $year++) {
        $seasons[] = array('season' => $year . '/' . ($year + 1));
    }

?>

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Clothes</h1>
    </div>

    <div class="fixed-button-container">
        <a href="#" class="btn my-btn-primary" data-toggle="modal" data-target="#createClotheModal" id="createClothe">
            <i class="fas fa-plus"></i> Create Clothe
        </a>
    </div>

    <div class="row">
        <?php foreach ($clothes as $index => $clothe): ?>
            <div class="col-lg-4 mb-4">
                <div class="card">
                    <div class="my-card-header py-3">
                        <h6 class="m-0 font-weight-bold"><?php echo $clothe['nameClothe'] ?></h6>
                        <p class="card-text">Season: <?= $clothe['season'] ?></p>
                    </div>

                    <div class="card-body bgColor d-flex align-items-center justify-content-center" style="height: 300px;">
                        <?php 
                            if(!empty($clothe['img'])){
                                $base64Image = base64_decode($clothe['img']);
                                $imgSrc = 'data:image/png;base64,' . $base64Image;
                                echo '<img class="img-fluid" src="' . $imgSrc . '" alt="' . $clothe['nameClothe'] . '" style="max-width: 100%; max-height: 100%;">'; 
                            }
                        ?>
                    </div>

                    <div class="my-card-footer">
                        <a class="btn my-btn-primary edit-clothe" data-clotheid="<?= $clothe['idClothe'] ?>">
                            Edit
                        </a>
                        <a class="btn my-btn-primary delete-clothe" data-clotheid="<?= $clothe['idClothe'] ?>">
                            Delete
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>


    <div class="modal fade" id="createClotheModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create Clothe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createClothe" action="../../api/process/processCreateClothe.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="teamName">Clothe Name:</label>
                            <input type="text" class="form-control" id="clotheName" name="nameClothe" required>
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
                            <label for="teamImage">Upload Clothe Image:</label>
                            <input type="file" class="form-control-file" id="clotheImage" name="clotheImage" accept="image/*" required>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="createClothe" class="btn my-btn-primary">
                                Create Clothe
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
    <div class="modal fade" id="updateClotheModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Clothe</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php if (isset($clothe)): ?>
                        <form id="updateClothe" method="post" action="../../api/process/processUpdateClothe.php" enctype="multipart/form-data">
                            <input type="hidden" name="idClothe" value="<?php echo $selectedClothe['idClothe']; ?>">
                            <input type="hidden" name="image" value="<?php echo $selectedClothe['img']; ?>">

                            <div class="form-group">
                                <label for="clotheName">Clothe Name:</label>
                                <input type="text" class="form-control" id="clotheName" name="nameClothe" value="<?php echo $selectedClothe['nameClothe']; ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="seasonsDropdown">Season:</label>
                                <select id="seasonsDropdown" name="seasonsDropdown" class="form-control">
                                    <option value=''>Select Season</option>
                                    <?php foreach ($seasons as $season): ?>
                                        <?php
                                            $selected = ($selectedClothe['season'] == $season['season']) ? 'selected' : '';
                                        ?>
                                        <option value='<?php echo $season['season']; ?>' <?php echo $selected; ?>><?php echo $season['season']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="clotheImage">Upload Clothes Image:</label>

                                <?php if (isset($selectedClothe['img']) && !empty($selectedClothe['img'])): ?>
                                    <img id="previewImage" src="data:image/png;base64,<?php echo base64_decode($selectedClothe['img']); ?>" alt="Selected Image" style="margin-bottom: 10px;">
                                <?php endif; ?>

                                <input type="file" class="form-control-file" id="clotheImageInput" name="clotheImage" accept="image/*" onchange="previewFile()">
                            </div>


                            <div class="modal-footer">
                                <button type="submit" name="updateClothe" class="btn my-btn-primary">
                                    Update
                                </button>
                                <button id="closeEditModal" type="button" class="btn my-btn-primary" data-dismiss="modal">
                                    Close
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <p>No clothe information available.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>


<?php if ($deleteModal == true): ?>
    <div class="modal fade" id="deleteClotheModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Clothe Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="../../api/process/processDeleteClothe.php" method="POST">
                    <div class="modal-body">
                        <p>Are you sure you want to delete the clothe <?php echo $clothe['nameClothe']; ?>?</p>
                    </div>
                    <input type="hidden" name="idClothe" value="<?php echo $clothe['idClothe']; ?>">
                    <div class="modal-footer">
                        <!-- Confirm delete button on the left side -->
                        <button type="submit" name="deleteClothe" class="btn my-btn-primary">
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
    var isNewImageSelected = false;
    function previewFile() {
        
        var preview = document.getElementById('previewImage');
        var fileInput = document.getElementById('clotheImageInput');
        var file = fileInput.files[0];

        isNewImageSelected = file && file.size > 0;
        console.log('Value: ' + isNewImageSelected);

        var reader = new FileReader();
        reader.onloadend = function () {
            // Extract base64 content from data URL
            var base64Content = reader.result.split(',')[1];

            // Set the src attribute of the image with the decoded content
            setPreviewImage(base64Content);
        };

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = 'data:image/png;base64,' + '<?php echo base64_decode($selectedClothe['img']); ?>';
        }
    }

    function setPreviewImage(base64Content) {
        var preview = document.getElementById('previewImage');
        preview.src = 'data:image/png;base64,' + base64Content;
    }
</script>

<script>
    // configurations to modal delete team
    $(document).ready(function () {
        // Listen for the click event on elements with the class 'delete-championship'
        $('.delete-clothe').on('click', function (e) {
            e.preventDefault(); // prevent the default link behavior

            // Get the championshipId from the data-championshipid attribute
            var clotheId = $(this).data('clotheid');

            console.log('Clicked Delete for clothe ID:', clotheId);

            // Set $bool to true and update the URL with championshipId
            window.location.href = './base.php?route=clothes&deleteModal=true&clotheId=' + clotheId;
        });

        // Show the deleteChampionshipModal when deleteModal=true is present in the URL
        var urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('deleteModal') === 'true') {
            $('#deleteClotheModal').modal('show');
        }
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeDeleteModal').on('click', function () {
        window.location.href = './base.php?route=clothes';
    });

</script>

<!-- Script to open and close the modal -->
<script>
    // when $bool = true the modal id=teamInfoModal its opened
    $(document).ready(function(){
        // Show the modal on page load
        $('#updateClotheModal').modal('show');
    });

    // Set a parameter in the URL when the close button is pressed
    $('#closeEditModal').on('click', function() {
        window.location.href = './base.php?route=clothes';
    });

    $('.edit-clothe').on('click', function() {
        // Get the idTeam from the data-idteam attribute
        var clotheId = $(this).data('clotheid');

        console.log('Clicked Edit for team ID:', clotheId);

        // Set $bool to true and update the URL with idTeam
        window.location.href = './base.php?route=clothes&editModal=true&clotheId=' + clotheId;
    });
</script>
    <!-- End of script modal -->

<script>
    $(document).ready(function () {
        // Use the correct id when triggering the modal
        $('#createClothe').click(function () {
            $('#createClotheModal').modal('show');
        });
    });
    
</script>
