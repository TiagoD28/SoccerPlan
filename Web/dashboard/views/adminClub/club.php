<?php 
    require_once '../../api/requests/getData.php';
    // URL of your API endpoint
    $route = 'Users/index.php?route=getUsers';
    $responseData = getDataFromApi($route);
    $rowCount = count($responseData['data']);

    $routeClubs = 'Clubs/index.php?route=getClubs';
    $apiResponseClubs = getDataFromApi($routeClubs);
    $responseDataClubs = $apiResponseClubs['data'];

    // var_dump($responseDataClubs);
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800"><?php echo $_SESSION['club'] ?></h1>
    </div>

    <?php if($_SESSION['club'] == 'Join Club'): ?>
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
                            <a href="path/to/your/script.php" class="my-a-btn btn my-btn-primary btn-user btn-block">
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
        <!-- </div> -->
        </div>

    <?php else: ?>
    
    <!-- Content Row -->
    <div class="row">

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="my-card bgColor my-border-left-color shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold my-secondary-color text-uppercase mb-1">
                                Number Of Members</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-300"><?php echo $rowCount ?></div>
                        </div>
                        <div class="col-auto">
                            <!-- <i class="fas fa-calendar fa-2x text-gray-300"></i> -->
                            <!-- Spin fa-spin -->
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Monthly) Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="my-card bgColor my-border-left-color shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold my-secondary-color text-uppercase mb-1">
                                Number Of Teams</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-300">$215,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- sdfs -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="my-card bgColor my-border-left-color shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold my-secondary-color text-uppercase mb-1">
                                Number Of Coaches</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-300">$215,000</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests Card Example -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="my-card bgColor my-border-left-color shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold my-secondary-color text-uppercase mb-1">
                                Number Of Players</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">18</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-comments fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-4">
            <!-- Illustrations 1 -->
            
            <div class="card shadow mb-4">
                <div class="my-card-header py-3">
                    <!-- <a href="./teams.php?team_id=<?php echo $teamId; ?>"> -->
                    <a href="./base.php?route=teams&teamId=<?= 1 ?>">
                        <h6 class="m-0 font-weight-bold text-primary">Team Name</h6>
                    </a>
                    <a href="#">
                        <i class="fas fa-user-plus text-gray-300"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                            src="img/undraw_posting_photo.svg" alt="...">
                    </div>
                    <p>Add some quality, svg illustrations to your project courtesy of <a
                            target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                        constantly updated collection of beautiful svg images that you can use
                        completely free and without attribution!</p>
                    <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                        unDraw &rarr;</a>
                </div>
            </div>
            
        </div>

        <div class="col-lg-6 mb-4">
            <!-- Illustrations 2 -->
            <div class="card shadow mb-4">
                <div class="my-card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Team Name</h6>
                    <a href="./teams.php?team_id=<?php echo $teamId; ?>">
                        <i class="fas fa-user-plus text-gray-300"></i>
                    </a>
                </div>
                <div class="card-body">
                        <div class="text-center">
                            <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                                src="img/undraw_posting_photo.svg" alt="...">
                        </div>
                        <p>Add some quality, svg illustrations to your project courtesy of <a
                                target="_blank" rel="nofollow" href="https://undraw.co/">unDraw</a>, a
                            constantly updated collection of beautiful svg images that you can use
                            completely free and without attribution!</p>
                        <a target="_blank" rel="nofollow" href="https://undraw.co/">Browse Illustrations on
                            unDraw &rarr;</a>
                    </div>
            </div>
        </div>
    </div>

    <?php endif; ?>
</div>