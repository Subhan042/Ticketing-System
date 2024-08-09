<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <title>Admin Ticket Management</title>
   
    <style>
        /* Your CSS styles here */
        body {
    font-family: "Open Sans", sans-serif;
    background-color: #f8f8f8;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    padding: 20px;
}
        .left-container {
    flex: 1;
    margin-right: 20px;
    background: linear-gradient(to bottom, #ff8a00, #e52e71);
    color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

.middle-container {
    flex: 2;
    margin-right: 20px;
    background: linear-gradient(to bottom, #ff8a00, #e52e71);
    color: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
}

        .ticket-list {
            cursor: pointer;
        }
        .ticket {
    cursor: pointer;
    padding: 15px;
    margin-bottom: 10px;
    background-color: #f1f1f1;
    border: 1px solid #ddd;
    border-radius: 6px;
    color:black;
}

        .ticket-details {
    border: 1px solid #ddd;
    padding: 20px;
    margin-bottom: 20px;
    display: none;
}
        .header {
            background-color: #343a40;
            color: #fff;
        }
        .custom-button {
            background-color: orange;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s;
        }

        .custom-button:hover {
            background-color: #0056b3;
        }
        .footer {
    background-color: #f8f9fa; /* Light gray background */
    background-color: #052950;
    padding: 40px 20px;
    display: flex;
    justify-content: space-between;
}


.footer-block h4 {
    color: #ffffff; /* Dark gray */
    font-size: 24px;
    margin-bottom: 15px;
}

.footer-block p {
    color: #d6d5d5; /* Medium gray */
    font-size: 16px;
    margin-bottom: 10px;
}

.footer-block ul {
    list-style: none;
    padding: 0;
    margin: 0;
}


/* Apollo Pharmacy blue color */
.footer-content {
    display: flex;
    flex-wrap: wrap;
    justify-content: space-between;
}

.footer-block {
    width: calc(20% - 20px); /* Adjust width as needed */
    margin-bottom: 20px;
    padding: 20px;
    background-color: rgba(255, 255, 255, 0.1);
}

.footer-block h5 {
    color: #ffffff; /* Dark gray */
    font-size: 24px;
    margin-bottom: 15px;
    text-align: center;
}

.footer-block p {
    font-size: 14px;
    margin-bottom: 10px;
}

.footer-block ul {
    padding-left: 0;
    list-style: none;
}

.footer-block ul li {
    margin-bottom: 5px;
}

.footer-block ul li a {
    color: #ccc;
    text-decoration: none;
}

.footer-block ul li a:hover {
    color: #fff;
}
#social-icons {
            display: flex;
            flex-direction: column; /* Arrange items vertically */
            align-items: center; /* Horizontally center the items */
            list-style: none; /* Remove default list styles */
            padding: 0; /* Remove default padding */
           
        }

        #social-icons li {
            margin-bottom: 20px; /* Add some space between icons */
         
        }

        #social-icons li a {
            font-size: 24px; /* Increase the size of the icons */
           
        }
        select, textarea {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 6px;
    outline: none;
    transition: border-color 0.3s ease-in-out;
}

select:focus, textarea:focus {
    border-color: #fff;
}

ul {
    list-style-type: none;
    padding: 0;
}
        
    </style>
</head>
<body>
<div class="header">
        <div class="container">
            <h1> Ticket Status Handling </h1>
            <button class=custom-button onclick="window.location.href='admin.html'">Home</button>

        </div>
    </div>
    <div class="container">
        <div class="left-container">
            <h2>Tickets</h2>
            <!-- Add a dropdown for selecting departments -->
            <?php
            // Initialize department filter
            $departmentFilter = isset($_GET['department']) ? $_GET['department'] : 'all';

            // Check if a department filter is set
            if (isset($_GET['department'])) {
                $departmentFilter = $_GET['department'];
            }
            ?>
            <select id="department-select">
                <option value="all" <?php echo ($departmentFilter === "all") ? "selected" : ""; ?>>All Departments</option>
                <option value="Bus Ticket" <?php echo ($departmentFilter === "Bus Ticket") ? "selected" : ""; ?>>Bus Ticket</option>
                <option value="Train Ticket" <?php echo ($departmentFilter === "Train Ticket") ? "selected" : ""; ?>>Train Ticket</option>
                <option value="Flight Ticket" <?php echo ($departmentFilter === "Flight Ticket") ? "selected" : ""; ?>>Flight Ticket</option>
            </select>
            <ul class="ticket-list">
                <?php
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
                // Construct the SQL query based on the department filter
                if ($departmentFilter === "all") {
                    $sql = "SELECT id, name, title, department, content, attachment, from_location, to_location, date_of_journey, status, bus_id, train_id, flight_id FROM tickets WHERE status != 'closed'";
                } else {
                    $sql = "SELECT id, name, title, department, content, attachment, from_location, to_location, date_of_journey, status, bus_id, train_id, flight_id FROM tickets WHERE department = '$departmentFilter' AND status != 'closed'";
                }
                


                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $dynamicField = '';
                    
                        switch ($row["department"]) {
                            case "Bus Ticket":
                                $busIdAvailable = isset($row["bus_id"]);
                                $dynamicField = $busIdAvailable ? 'Bus ID: ' . $row["bus_id"] : 'Bus ID not available';
                                break;
                            case "Train ticket":
                                $trainIdAvailable = isset($row["train_id"]);
                                $dynamicField = $trainIdAvailable ? 'Train ID: ' . $row["train_id"] : 'Train ID not available';
                                break;
                            case "Flight ticket":
                                $flightIdAvailable = isset($row["flight_id"]);
                                $dynamicField = $flightIdAvailable ? 'Flight ID: ' . $row["flight_id"] : 'Flight ID not available';
                                break;
                            default:
                                $dynamicField = 'ID not available for this ticket type';
                                break;
                        }
                        
                    
                        // Debugging output for Bus ID, Train ID, and Flight ID availability
                       
                    
                        // Debugging output
                       // Debugging output
// Debugging output
echo '<li class="ticket" 
    data-ticket-id="' . $row["id"] . '" 
    data-ticket-name="' . $row["name"] . '" 
    data-ticket-content="' . $row["content"] . '" 
    data-ticket-attachments="' . $row["attachment"] . '" 
    data-ticket-title="' . $row["title"] . '" 
    data-ticket-department="' . $row["department"] . '" 
    data-ticket-status="' . $row["status"] . '" 
    data-ticket-dynamic="' . $dynamicField . '" 
    data-ticket-from="' . $row["from_location"] . '" 
    data-ticket-to="' . $row["to_location"] . '" 
    data-ticket-date="' . $row["date_of_journey"] . '"';

// Add data attributes for train_id and flight_id
if ($row["department"] == "Train Ticket") {
    echo ' data-train-id="' . $row["train_id"] . '"';
} elseif ($row["department"] == "Flight Ticket") {
    echo ' data-flight-id="' . $row["flight_id"] . '"';
} elseif ($row["department"] == "Bus Ticket") {
    echo ' data-bus-id="' . $row["bus_id"] . '"';
}

echo '>Ticket ' . $row["id"] . ' - ' . $row["name"] . '</li>';


                    }
                    
                    
                } else {
                    echo '<li>No tickets found.</li>';
                }

                // Close the database connection
                $conn->close();
                ?>
            </ul>
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
        const departmentSelect = document.getElementById('department-select');
        

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
        departmentSelect.addEventListener('change', () => {
            const selectedDepartment = departmentSelect.value;
            console.log(`Selected Department: ${selectedDepartment}`);
            
            // Construct the new URL with the selected department as a query parameter
            const newUrl = window.location.pathname + '?department=' + selectedDepartment;
            
            // Redirect to the new URL
            window.location.href = newUrl;
        });
    </script>
<div class="footer">
    <div class="footer-block">
        <h4>About Us</h4>
        <p>We are here to resolve your queries related to the Ticketing System. Our dedicated team is ready to assist you with any inquiries or support you may need.</p>
    </div>
    <div class="footer-block">
        <h4>Quick Links</h4>
        <ul>
            <li><a href="Landing.html">Home</a></li>
            <li><a href="aticket.php">Ticket reply</a></li>
            <li><a href="admin.html">Services</a></li>
            <li><a href="amessage.html">Message reply</a></li>
            <li><a href="logout.php">Log out</a></li>
        </ul>
    </div>
    <div class="footer-block">
        <h4>Contact Us</h4>
        <p>123 Street, City, Country</p>
        <p>Email: Ticketingsystem@gmail.com</p>
        <p>Phone: +1234567890</p>
    </div>
    <div class="footer-block" id="follow-us">
        <h5>Follow Us</h5>
        <ul id="social-icons" class="list-unstyled d-flex">
            <li class="mr-3"><a href="#" class="text-dark"><i class="fab fa-facebook-f"></i></a></li>
            <li class="mr-3"><a href="#" class="text-dark"><i class="fab fa-twitter"></i></a></li>
            <li><a href="#" class="text-dark"><i class="fab fa-instagram"></i></a></li>
        </ul>
    </div>
</div>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-OgwbLK/mxr4PKlYtTqwH5aVlMFhg4+PmYrE6TW1lAMfhH8l tslczUywEXYNrqwuN" crossorigin="anonymous"></script>
</body>
</html>

