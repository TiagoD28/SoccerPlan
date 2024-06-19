<?php 
    require_once '../../api/requests/getData.php';
    require_once '../../api/requests/sendData.php';

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>


<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Edit Profile</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editProfileForm" method="post" action="../../api/process/processUpdateUser.php"  enctype="multipart/form-data">
                    <div class="text-center">
                        <?php if (isset($_SESSION['img']) && !empty($_SESSION['img'])): ?>
                            <?php 
                                if (!empty($_SESSION['img'])) {
                                $imgSrc = 'data:image/png;base64,' . $base64Image;
                                echo '<div class="avatar-container">';
                                echo '<img class="img-fluid rounded-circle" src="' . $imgSrc . '" alt="' . $_SESSION['firstName'] . '">';
                                echo '</div>';
                                }

                            ?>
                        <?php else: ?>
                            <p>You don't have Image</p>
                        <?php endif; ?>
                    </div>
                    <p><?php echo $_SESSION['typeUser']; ?></p>
                
                    <input type="hidden" name="idUser" value="<?php echo $user['idUser']; ?>">

                    <div class="form-group">
                        <label for="firstName">First Name:</label>
                        <input type="text" class="form-control" id="firstName" name="firstName" pattern="[A-Za-z]+" title="Only letters are allowed" 
                        value="<?php echo $_SESSION['firstName']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="lastName">Last Name:</label>
                        <input type="text" class="form-control" id="lastName" name="lastName" pattern="[A-Za-z]+" title="Only letters are allowed"
                        value="<?php echo $_SESSION['lastName']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="age">Age:</label>
                        <input type="text" class="form-control" id="age" name="age" pattern="[0-9]+" title="Only numbers are allowed"
                        value="<?php echo $_SESSION['age']; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="age">Nacionality:</label>
                        <input type="text" class="form-control" id="nacionality" name="nacionality" pattern="[A-Za-z]+" title="Only letters are allowed"
                        value="<?php echo $_SESSION['nacionality']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo $_SESSION['email']; ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="phoneNumber">Phone Number:</label>
                        <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" pattern="[0-9]+" title="Only numbers are allowed" 
                        value="<?php echo $_SESSION['phoneNumber']; ?>">
                    </div>

                    <div class="form-group">
                        <label for="userImage">Upload Image:</label>
                        <input type="file" class="form-control-file" id="userImageInput" name="userImage" accept="image/*">
                    </div>

                    <div class="modal-footer">
                        <button type="submit" name="updateUser" class="btn my-btn-primary">
                            Update
                        </button>
                        <button type="button" class="btn my-btn-primary" data-dismiss="modal">
                            Close
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>