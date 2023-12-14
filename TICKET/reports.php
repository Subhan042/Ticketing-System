<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Ticket Management</title>
    <div class="header">
        <button onclick="window.location.href='admin.html'">Home</button>
    </div>
    <style>
        /* Your CSS styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
            background-image: url('../TICKET/Images/admind.jpg'); /* Add your background image path here */
            background-size: cover;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }
        .header {
            background-color: rgba(0, 0, 0, 0.1);;
            color: white;
            padding: 10px;
            text-align: left;
        }
        .header button {
            background-color: #fff;
            color: black;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            cursor: pointer;
            transition: background-color 0.3s; /* Add smooth transition */
        }

        .header button:hover {
            background-color: grey; 
        }

        .container {
            display: flex;
            margin: 10px;
        }

        .left-container {
            flex: 1;
            padding: 20px;
            background-color: #fff;
        }

        .middle-container {
            flex: 2;
            padding: 20px;
            background-color: #fff;
            border-left: 1px solid #ddd; /* Add a border to separate middle and left containers */
            border-right: 1px solid #ddd; /* Add a border to separate middle and right containers */
        }

        .ticket-list {
            cursor: pointer;
        }

        .ticket-details {
            display: none;
        }

        
    </style>
</head>
<body>
    <div class="container">
        <div class="left-container">
            <h2>Tickets</h2>
            <!-- Add a dropdown for selecting departments -->
            <?php
            // Initialize department filter
            $departmentFilter = isset($_GET['department']) ? $_GET['department'] : 'all';

            ?>
            <div class="department-dropdown">
                <label for="department-select">Select Department:</label>
                <form id="department-form" method="get">
                    <select id="department-select" name="department" onchange="document.getElementById('department-form').submit();">
                        <option value="all" <?php if($departmentFilter == 'all') echo 'selected'; ?>>All Departments</option>
                        <option value="Bus Ticket" <?php if($departmentFilter == 'Bus Ticket') echo 'selected'; ?>>Bus Ticket</option>
                        <option value="Train Ticket" <?php if($departmentFilter == 'Train Ticket') echo 'selected'; ?>>Train Ticket</option>
                        <option value="Flight Ticket" <?php if($departmentFilter == 'Flight Ticket') echo 'selected'; ?>>Flight Ticket</option>
                    </select>
                </form>
            </div>

            <?php
            // Initialize department filter
            // Default department is set to "all"

            // Check if a department filter is set
            if (isset($_GET['department'])) {
                $departmentFilter = $_GET['department'];
            }

            // PHP code to fetch ticket data from a MySQL database
            $servername = "localhost";
            $username = "root";
            $password = "";
            $dbname = "tickets_db";
            $port = "3306";

            // Create a database connection
            $conn = new mysqli($servername, $username, $password, $dbname, $port);

            // Check the connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Construct the SQL query based on the department filter
            $sql = "SELECT id, name, title, department, content, attachment, submitted_at, resolved_at, status FROM tickets";

            if ($departmentFilter !== "all") {
                $sql .= " WHERE department = '$departmentFilter'";
            }

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<ul class="ticket-list">';
                while ($row = $result->fetch_assoc()) {
                    echo '<li class="ticket" data-ticket-id="' . $row["id"] . '" data-ticket-content="' . $row["content"] . '" data-ticket-attachments="' . $row["attachment"] . '" data-ticket-title="' . $row["title"] . '" data-ticket-department="' . $row["department"] . '" data-ticket-status="' . $row["status"] . '" data-ticket-submitted-at="' . $row["submitted_at"] . '" data-ticket-resolved-at="' . $row["resolved_at"] . '">Ticket ' . $row["id"] . ' - ' . $row["name"] . '</li>';
                }
                echo '</ul>';
            } else {
                echo '<p>No tickets found.</p>';
            }

            // Close the database connection
            $conn->close();
            ?>

        </div>
        <div class="middle-container">
            <h2>Ticket Details</h2>
            <div class="ticket-details">
                <!-- Ticket details will be displayed here using JavaScript -->
            </div>
        </div>
    </div>
    <script>
        // JavaScript for interactivity
        const ticketList = document.querySelectorAll('.ticket');
        const ticketDetails = document.querySelector('.ticket-details');
        

        ticketList.forEach(ticket => {
            ticket.addEventListener('click', () => {
                const ticketId = ticket.getAttribute('data-ticket-id');
                const ticketTitle = ticket.getAttribute('data-ticket-title');
                const ticketDepartment = ticket.getAttribute('data-ticket-department');
                const ticketStatus = ticket.getAttribute('data-ticket-status');
                const submittedAt = ticket.getAttribute('data-ticket-submitted-at');
                const resolvedAt = ticket.getAttribute('data-ticket-resolved-at');

                const resolvedText = resolvedAt ? resolvedAt : 'Not Resolved';

                ticketDetails.innerHTML = `
                    <h3>Ticket Details</h3>
                    <p><strong>Ticket ID:</strong> ${ticketId}</p>
                    <p><strong>Title:</strong> ${ticketTitle}</p>
                    <p><strong>Department:</strong> ${ticketDepartment}</p>
                    <p><strong>Status:</strong> ${ticketStatus}</p>
                    <p><strong>Submitted At:</strong> ${submittedAt}</p>
                    <p><strong>Resolved At:</strong> ${resolvedText}</p>
                `;

                ticketDetails.style.display = 'block';
            });
        });
    </script>
</body>
</html>

