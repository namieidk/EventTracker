<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <style>
        body {
            min-height: 100vh;
            background-color: rgba(211, 211, 211, 0.8); 
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px); 
            margin: 0;
            padding: 20px;
            color: #333; 
        }
        .tracker-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .input-group-text {
            background-color: #fff;
            border-right: none;
        }
        .form-control {
            border-left: none;
        }
        .modal-content {
            background-color: rgba(255, 255, 255, 0.95);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="tracker-container">
        <h2>Event Tracker</h2>
        
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addEventModal">
            <i class="bi bi-plus-circle"></i> Add Event
        </button>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include '../database/database.php';
                $stmt = $conn->prepare("SELECT * FROM EventTracker ORDER BY event_date ASC");
                $stmt->execute();
                $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
                if ($events) {
                    foreach ($events as $event) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($event['event_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['event_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['description']) . "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4' class='text-center'>No events found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="event_name" class="form-label">Event Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                    <input type="text" class="form-control" id="event_name" name="event_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="location" class="form-label">Location</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" id="location" name="location">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="event_date" class="form-label">Event Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="date" class="form-control" id="event_date" name="event_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="description" class="form-label">Description</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="add_event" class="btn btn-primary w-100">Save Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    include '../database/database.php';

    if (isset($_POST['add_event'])) {
        try {
            $event_name = $_POST['event_name'];
            $event_date = $_POST['event_date'];
            $location = $_POST['location'] ?? '';
            $description = $_POST['description'] ?? '';

            $stmt = $conn->prepare("INSERT INTO EventTracker (event_name, event_date, location, description) 
                                  VALUES (:event_name, :event_date, :location, :description)");
            $stmt->bindParam(':event_name', $event_name);
            $stmt->bindParam(':event_date', $event_date);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':description', $description);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Event added successfully!');
                    window.location.href = 'tracker.php';
                </script>";
            } else {
                echo "<script>alert('Error: Unable to add event');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
    ?>

   
</body>
</html>