<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Ticket Management</title>
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

        .container {
            display: flex;
            margin: 10px;
        }

        .left-container, .right-container {
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

        .attachment {
            margin-top: 20px;
        }

        /* Reply message styles */
        .reply-container {
            margin-top: 20px;
            border-top: 1px solid #ddd; /* Add a border above the reply container */
            padding-top: 20px;
        }

        .reply-container textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            resize: none;
        }

        .reply-container button, #change-status-button {
            margin-top: 10px;
            background-color: #333;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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
            $departmentFilter = "";

            // Check if a department filter is set
            if (isset($_GET['department'])) {
                $departmentFilter = $_GET['department'];
            }
            ?>
            <select id="department-select">
                <option value="all" <?php echo ($departmentFilter === "all") ? "selected" : ""; ?>>All Departments</option>
                <option value="general" <?php echo ($departmentFilter === "general") ? "selected" : ""; ?>>General</option>
                <option value="sales" <?php echo ($departmentFilter === "sales") ? "selected" : ""; ?>>Sales Department</option>
                <option value="marketing" <?php echo ($departmentFilter === "marketing") ? "selected" : ""; ?>>Marketing Department</option>
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
                if ($departmentFilter === "all") {
                    $sql = "SELECT id, name, title, department, content, attachment, status FROM tickets WHERE status != 'closed'";
                } else {
                    $sql = "SELECT id, name, title, department, content, attachment, status FROM tickets WHERE department = '$departmentFilter' AND status != 'closed'";
                }

                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo '<li class="ticket" data-ticket-id="' . $row["id"] . '" data-ticket-content="' . $row["content"] . '" data-ticket-attachments="' . $row["attachment"] . '" data-ticket-title="' . $row["title"] . '" data-ticket-department="' . $row["department"] . '" data-ticket-status="' . $row["status"] . '">Ticket ' . $row["id"] . ' - ' . $row["name"] . '</li>';
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
                <!-- Ticket details will be displayed here -->
            </div>

            <!-- Reply message container -->
            <div class="reply-container">
                <h2>Reply to Ticket</h2>
                <form id="reply-form">
                    <textarea rows="4" id="reply-text" placeholder="Type your reply here"></textarea>
                    <button id="send-reply-button">Send Reply</button>
                </form>
            </div>
        </div>

        <div class="right-container">
            <h2>Ticket Status</h2>
            <select id="status-select">
                <option value="open">Open</option>
                <option value="in-progress">In Progress</option>
                <option value="closed">Closed</option>
            </select>

            <!-- Button to change ticket status -->
            <button id="change-status-button">Change Status</button>
        </div>
    </div>

    <script>
        // JavaScript for interactivity (as shown in the previous example)
        const ticketList = document.querySelectorAll('.ticket');
        const ticketDetails = document.querySelector('.ticket-details');
        const statusSelect = document.getElementById('status-select');
        const replyContainer = document.querySelector('.reply-container');
        const sendReplyButton = document.getElementById('send-reply-button');
        const departmentSelect = document.getElementById('department-select');
        const changeStatusButton = document.getElementById('change-status-button');
        let selectedTicketId; // Define the variable

        ticketList.forEach(ticket => {
            ticket.addEventListener('click', () => {
                // Get data attributes for content and attachments
                const ticketContent = ticket.getAttribute('data-ticket-content');
                const ticketAttachments = ticket.getAttribute('data-ticket-attachments');
                const ticketTitle = ticket.getAttribute('data-ticket-title');
                const ticketDepartment = ticket.getAttribute('data-ticket-department');
                const ticketStatus = ticket.getAttribute('data-ticket-status'); // Get ticket status
                selectedTicketId = ticket.getAttribute('data-ticket-id'); // Store the selected ticket ID

                // Check if there are attachments
                const attachmentsArray = ticketAttachments.split(',');

                // Create an HTML list of attachment links or display "No attachment"
                let attachmentsList = "";
                if (attachmentsArray[0].trim() !== "") {
                    attachmentsList = attachmentsArray.map((attachment, index) => `
                        <a href="${attachment}" target="_blank">Attachment ${index + 1}</a>
                    `).join('<br>');
                } else {
                    attachmentsList = "No attachment";
                }

                // Populate ticket details
                ticketDetails.innerHTML = `
                    <h3>Ticket ${ticket.getAttribute('data-ticket-id')} - ${ticket.getAttribute('data-ticket-name')}</h3>
                    <p>Title: ${ticketTitle}</p>
                    <p>Department: ${ticketDepartment}</p>
                    <p>Content: ${ticketContent}</p>
                    <p>Status: ${ticketStatus}</p>
                    <p>Attachments:</p>
                    ${attachmentsList}
                `;

                // Set the selected status in the dropdown
                statusSelect.value = ticketStatus;

                // Show ticket details and reply container
                ticketDetails.style.display = 'block';
                replyContainer.style.display = 'block';
            });
        });

        statusSelect.addEventListener('change', () => {
            // Simulated status change
            const selectedStatus = statusSelect.value;
            console.log(`Status changed to: ${selectedStatus}`);
        });

        sendReplyButton.addEventListener('click', () => {
            // Ensure a ticket is selected
            if (selectedTicketId) {
                const replyText = document.getElementById('reply-text').value;

                // Send an AJAX request to submit the reply with the associated ticket ID
                fetch('update_ticket.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `ticketId=${selectedTicketId}&replyText=${replyText}`,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    // Handle the response data here if needed
                    console.log(data);

                    // You can also update the page or display a success message here
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                console.error('No ticket selected.');
            }
        });

        // Handle department filter change
        departmentSelect.addEventListener('change', () => {
            const selectedDepartment = departmentSelect.value;
            console.log(`Selected Department: ${selectedDepartment}`);
            
            // Construct the new URL with the selected department as a query parameter
            const newUrl = window.location.pathname + '?department=' + selectedDepartment;
            
            // Redirect to the new URL
            window.location.href = newUrl;
        });

        // Handle change status button click
        changeStatusButton.addEventListener('click', () => {
            // Ensure a ticket is selected
            if (selectedTicketId) {
                const selectedStatus = statusSelect.value;

                // Send an AJAX request to update the ticket status
                fetch('update_status.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `ticketId=${selectedTicketId}&status=${selectedStatus}`,
                })
                .then(response => response.json())
                .then(data => {
                    // Handle the response data here if needed
                    console.log(data);

                    // You can also update the page or display a success message here
                })
                .catch(error => {
                    console.error('Error:', error);
                });
            } else {
                console.error('No ticket selected.');
            }
        });
    </script>
</body>
</html>
