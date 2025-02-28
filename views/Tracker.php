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
                    <th>ID</th>
                    <th>Event Name</th>
                    <th>Event Date</th>
                    <th>Location</th>
                    <th>Description</th>
                    <th>Actions</th>
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
                        echo "<td>" . htmlspecialchars($event['event_id']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['event_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['event_date']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['location']) . "</td>";
                        echo "<td>" . htmlspecialchars($event['description']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-sm btn-primary me-1 edit-btn' data-bs-toggle='modal' data-bs-target='#editEventModal' 
                              data-event-id='" . $event['event_id'] . "' 
                              data-name='" . htmlspecialchars($event['event_name']) . "' 
                              data-date='" . htmlspecialchars($event['event_date']) . "' 
                              data-location='" . htmlspecialchars($event['location']) . "' 
                              data-description='" . htmlspecialchars($event['description']) . "'>
                              <i class='bi bi-pencil'></i></button>";
                        echo "<button class='btn btn-sm btn-danger delete-btn' 
                              data-event-id='" . $event['event_id'] . "'>
                              <i class='bi bi-trash'></i></button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6' class='text-center'>No events found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <!-- Add Event Modal -->
    <div class="modal fade" id="addEventModal" tabindex="-1" aria-labelledby="addEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEventModalLabel">Add New Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="tracker.php">
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

    <!-- Edit Event Modal -->
    <div class="modal fade" id="editEventModal" tabindex="-1" aria-labelledby="editEventModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editEventModalLabel">Edit Event</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="tracker.php">
                        <input type="hidden" name="event_id" id="edit_event_id">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_event_name" class="form-label">Event Name</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar-event"></i></span>
                                    <input type="text" class="form-control" id="edit_event_name" name="event_name" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_location" class="form-label">Location</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="text" class="form-control" id="edit_location" name="location">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="edit_event_date" class="form-label">Event Date</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                                    <input type="date" class="form-control" id="edit_event_date" name="event_date" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="edit_description" class="form-label">Description</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-text-paragraph"></i></span>
                                    <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" name="edit_event" class="btn btn-primary w-100">Update Event</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
    include '../database/database.php';

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    // Edit Event
    if (isset($_POST['edit_event'])) {
        try {
            $event_id = $_POST['event_id'];
            $event_name = $_POST['event_name'];
            $event_date = $_POST['event_date'];
            $location = $_POST['location'] ?? '';
            $description = $_POST['description'] ?? '';

            $stmt = $conn->prepare("UPDATE EventTracker SET 
                                  event_name = :event_name, 
                                  event_date = :event_date, 
                                  location = :location, 
                                  description = :description 
                                  WHERE event_id = :event_id");
            $stmt->bindParam(':event_id', $event_id, PDO::PARAM_INT);
            $stmt->bindParam(':event_name', $event_name, PDO::PARAM_STR);
            $stmt->bindParam(':event_date', $event_date, PDO::PARAM_STR);
            $stmt->bindParam(':location', $location, PDO::PARAM_STR);
            $stmt->bindParam(':description', $description, PDO::PARAM_STR);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Event updated successfully!');
                    window.location.href = 'tracker.php';
                </script>";
                exit();
            } else {
                $errorInfo = $stmt->errorInfo();
                echo "<script>alert('Error updating event: " . addslashes($errorInfo[2]) . "');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Database error: " . addslashes($e->getMessage()) . "');</script>";
        }
    }

    // Add Event
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

    // Delete Event
    if (isset($_POST['delete_event'])) {
        try {
            $event_id = $_POST['event_id'];
            $stmt = $conn->prepare("DELETE FROM EventTracker WHERE event_id = :event_id");
            $stmt->bindParam(':event_id', $event_id);
            
            if ($stmt->execute()) {
                echo "<script>window.location.href = 'tracker.php';</script>";
            } else {
                echo "<script>alert('Error: Unable to delete event');</script>";
            }
        } catch (PDOException $e) {
            echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
        }
    }
    ?>

    <script>
    // Edit button click handler
    document.querySelectorAll('.edit-btn').forEach(button => {
        button.addEventListener('click', function() {
            const eventId = this.getAttribute('data-event-id');
            const name = this.getAttribute('data-name');
            const date = this.getAttribute('data-date');
            const location = this.getAttribute('data-location');
            const description = this.getAttribute('data-description');

            document.getElementById('edit_event_id').value = eventId;
            document.getElementById('edit_event_name').value = name;
            document.getElementById('edit_event_date').value = date;
            document.getElementById('edit_location').value = location;
            document.getElementById('edit_description').value = description;
        });
    });

    // Delete button click handler
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function() {
            if (confirm('Are you sure you want to delete this event?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '';
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_event';
                input.value = '1';
                const idInput = document.createElement('input');
                idInput.type = 'hidden';
                idInput.name = 'event_id';
                idInput.value = this.getAttribute('data-event-id');
                form.appendChild(input);
                form.appendChild(idInput);
                document.body.appendChild(form);
                form.submit();
            }
        });
    });

    // Add form submission debug
    document.querySelector('#editEventModal form').addEventListener('submit', function(e) {
        const formData = new FormData(this);
        console.log('Form submitting with data:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
    });
    </script>
</body>
</html>