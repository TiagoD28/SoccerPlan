<?php
require_once '../../api/requests/getData.php';
require_once '../../api/requests/sendData.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


$data = [
    'idClub' => $_SESSION['idClub']
];

// URL of your API endpoint
$route = 'Events/index.php?route=getEventsClub';
$responseData = sendDataToApi($route, $data);
$decodedResponse = json_decode($responseData, true);

$routeT = 'Teams/index.php?route=getTeamsClub';
$responseDataT = sendDataToApi($routeT, $data);
$decodeResponseT = json_decode($responseDataT, true);
$teams = $decodeResponseT['data'];

// Separate events into past and future based on their dates
$today = date('Y-m-d');
$nextEvents = [];
$pastEvents = [];
$todayEvents = [];

foreach ($decodedResponse['data'] as $event) {
    // Convert the API date format to 'Y-m-d'
    $eventDate = DateTime::createFromFormat('d-m-Y', $event['startDate']);

    // Check if the converted date is greater, less, or equal to today
    if ($eventDate->format('Y-m-d') > $today) {
        $nextEvents[] = $event;
    } elseif ($eventDate->format('Y-m-d') < $today) {
        $pastEvents[] = $event;
    } else {
        $todayEvents[] = $event;
    }
}

    // Determine the selected option or default to "All"
    $selectedType = isset($_GET['selectedType']) ? $_GET['selectedType'] : 'All';

    // Boolean variables to determine which events to display
    $showPastEvents = $selectedType === 'All' || $selectedType === 'Past';
    $showTodayEvents = $selectedType === 'All' || $selectedType === 'Today';
    $showNextEvents = $selectedType === 'All' || $selectedType === 'Next';
?>


    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Calendar</h1>
    </div>

    <!-- Segmented control for filtering events -->
    <div style="text-align: center; margin-top: 20px;">
        <div style="display: inline-block; width: 100%; padding: 10px;">
            <p style="padding-bottom: 10px; font-weight: bold; text-transform: uppercase;">
            <?= isset($_GET['selectedType']) ? $_GET['selectedType'] . ' ' : 'All ' ?> Events:
            </p>

            <?php
            // Display options for the segmented control
            $options = array("All", "Past", "Today", "Next");
            foreach ($options as $option):
                $isSelected = (isset($_GET['selectedType']) && $_GET['selectedType'] === $option) ? 'selected' : '';
                $url = "base.php?route=calendar&selectedType=" . urlencode($option);
                ?>
                <a href="<?= $url ?>" class="segmented-option <?= $isSelected ?>"><?= htmlspecialchars($option) ?></a>
            <?php endforeach; ?>
        </div>
    </div>

    <ul class="timeline">
    <?php if ($showPastEvents): ?>
        <!-- Display past events in reverse order (most recent first) -->
        <li class="section-title">
            <div class="timeline-time">
                <span class="date-title">Past Events</span>
            </div>
            <div class="timeline-icon">
                <a href="javascript:;">&nbsp;</a>
            </div>
        </li>

        <?php
        // Sort past events by date in descending order
        usort($pastEvents, function ($a, $b) {
            return strtotime($b['startDate']) - strtotime($a['startDate']);
        });

        foreach ($pastEvents as $event): ?>
            <!-- Timeline item code here -->
            <li>
                <div class="timeline-time">
                    <span class="date"><?php echo date('F j, Y', strtotime($event['startDate'])); ?></span>
                </div>
                <div class="timeline-icon">
                    <a href="javascript:;">&nbsp;</a>
                </div>
                <div class="timeline-body">
                    <div class="timeline-header">
                        <a href="javascript:;"><?php echo $eventIcon; ?></a>
                        <?php 
                            if ($event['typeEvent'] == 'Game') {
                                echo '<i class="fa-solid fa-trophy"></i>';
                            } elseif ($event['typeEvent'] == 'Practice') {
                                echo '<i class="fa-solid fa-futbol"></i>';
                            } elseif ($event['typeEvent'] == 'Event') {
                                echo '<i class="fa-solid fa-champagne-glasses"></i>';
                            } else {
                                // Default icon or handle other cases if needed
                                echo '<i class="fa-solid fa-question"></i>';
                            }
                        ?>
                        <span class="username"><a href="javascript:;"><?php echo $event['typeEvent']; ?></a> <small></small></span>
                        <!-- You can customize the content based on your event data -->
                        <span class="pull-right text-muted"><?php echo $event['description']; ?></span>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($showTodayEvents): ?>
        <!-- Display past events in reverse order (most recent first) -->
        <li class="section-title">
            <div class="timeline-time">
                <span class="date-title">Today Events</span>
            </div>
            <div class="timeline-icon">
                <a href="javascript:;">&nbsp;</a>
            </div>
        </li>

        <?php
        // Sort past events by date in descending order
        usort($todayEvents, function ($a, $b) {
            return strtotime($a['startDate']) - strtotime($b['startDate']);
        });

        foreach ($todayEvents as $event): ?>
            <!-- Timeline item code here -->
            <li>
                <div class="timeline-time">
                    <span class="date"><?php echo date('F j, Y', strtotime($event['startDate'])); ?></span>
                </div>
                <div class="timeline-icon">
                    <a href="javascript:;">&nbsp;</a>
                </div>
                <div class="timeline-body">
                    <div class="timeline-header">
                        <a href="javascript:;"><?php echo $eventIcon; ?></a>
                        <?php 
                            if ($event['typeEvent'] == 'Game') {
                                echo '<i class="fa-solid fa-trophy"></i>';
                            } elseif ($event['typeEvent'] == 'Practice') {
                                echo '<i class="fa-solid fa-futbol"></i>';
                            } elseif ($event['typeEvent'] == 'Event') {
                                echo '<i class="fa-solid fa-champagne-glasses"></i>';
                            } else {
                                // Default icon or handle other cases if needed
                                echo '<i class="fa-solid fa-question"></i>';
                            }
                        ?>
                        <span class="username"><a href="javascript:;"><?php echo $event['typeEvent']; ?></a> <small></small></span>
                        <!-- You can customize the content based on your event data -->
                        <span class="pull-right text-muted"><?php echo $event['description']; ?></span>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>

        <?php if ($showNextEvents): ?>
        <!-- Display past events in reverse order (most recent first) -->
        <li class="section-title">
            <div class="timeline-time">
                <span class="date-title">Next Events</span>
            </div>
            <div class="timeline-icon">
                <a href="javascript:;">&nbsp;</a>
            </div>
        </li>

        <?php
        // Sort past events by date in descending order
        usort($nextEvents, function ($a, $b) {
            return strtotime($a['startDate']) - strtotime($b['startDate']);
        });

        foreach ($nextEvents as $event): ?>
            <!-- Timeline item code here -->
            <li>
                <div class="timeline-time">
                    <span class="date"><?php echo date('F j, Y', strtotime($event['startDate'])); ?></span>
                </div>
                <div class="timeline-icon">
                    <a href="javascript:;">&nbsp;</a>
                </div>
                <div class="timeline-body">
                    <div class="timeline-header">
                        <a href="javascript:;"><?php echo $eventIcon; ?></a>
                        <?php 
                            if ($event['typeEvent'] == 'Game') {
                                echo '<i class="fa-solid fa-trophy"></i>';
                            } elseif ($event['typeEvent'] == 'Practice') {
                                echo '<i class="fa-solid fa-futbol"></i>';
                            } elseif ($event['typeEvent'] == 'Event') {
                                echo '<i class="fa-solid fa-champagne-glasses"></i>';
                            } else {
                                // Default icon or handle other cases if needed
                                echo '<i class="fa-solid fa-question"></i>';
                            }
                        ?>
                        <span class="username"><a href="javascript:;"><?php echo $event['typeEvent']; ?></a> <small></small></span>
                        <!-- You can customize the content based on your event data -->
                        <span class="pull-right text-muted"><?php echo $event['description']; ?></span>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    <?php endif; ?>

    </ul>

    <!-- Fixed "Create Event" button -->
    <div class="fixed-button-container">
        <a href="#" class="btn my-btn-primary" data-toggle="modal" data-target="#addEventModal">
            <i class="fas fa-plus"></i> Create Event
        </a>
    </div>

    <!-- Modal for adding events -->
    <div class="modal fade" id="addEventModal" tabindex="-1" role="dialog" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Create Event</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="createEvent" action="../../api/process/processCreateEvent.php" method="POST" enctype="multipart/form-data">
                        <!-- Select event type -->
                        <div class="form-group">
                            <label for="eventType">Event Type</label>
                            <select class="form-control" id="eventType" name="eventType" required>
                            <option value="">Select Event Type</option>
                                <option value="Game">Game</option>
                                <option value="Practice">Practice</option>
                                <option value="Event">Event</option>
                            </select>
                        </div>

                        <!-- Start date -->
                        <div class="form-group">
                            <label for="startDate">Start Date</label>
                            <input type="text" class="form-control datepicker" id="startDate" name="startDate" placeholder="Select start date" required>
                        </div>

                        <!-- End date -->
                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <input type="text" class="form-control datepicker" id="endDate" name="endDate" placeholder="Select end date" required>
                        </div>

                        <!-- Meeting time -->
                        <div class="form-group">
                            <label for="meetTime">Meeting Time</label>
                            <input type="text" class="form-control timepicker" id="meetTime" name="meetTime" placeholder="Select meeting time" required>
                        </div>

                        <!-- Meeting location for game -->
                        <div class="form-group">
                            <label for="meetLocal">Meeting Location</label>
                            <input type="text" class="form-control" id="meetLocal" name="meetLocal" placeholder="Enter meeting location" required>
                        </div>

                        <div class="form-group">
                            <label for="teamsDropdown">Teams:</label>
                            <select id="teamsDropdown" name="teamsDropdown" class="form-control" required>
                                <option value=''>Select Team</option>
                                <?php
                                foreach ($teams as $team) {
                                    echo "<option value='{$team['idTeam']}'>{$team['nameTeam']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" name="createEvent" class="btn my-btn-primary">
                                Create Event
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
    
<script>
    $(document).ready(function () {
        // Initialize datepickers and timepickers
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true
        });

        $('.timepicker').timepicker({
            showInputs: false,
            showMeridian: false
        });

        // Show/hide conditional fields based on the selected event type
        $('#eventType').change(function () {
            var selectedEventType = $(this).val();

            // Reset and hide conditional fields
            $('#gameFields').hide();
            $('#stadium, #meetLocal').val('');

            // Show fields based on the selected event type
            if (selectedEventType === 'Game') {
                $('#gameFields').show();
            }
        });
    });
</script>
