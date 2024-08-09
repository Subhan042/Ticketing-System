CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    from_location VARCHAR(255) NOT NULL,
    to_location VARCHAR(255) NOT NULL,
    date_of_journey DATE NOT NULL,
    content TEXT NOT NULL,
    attachment VARCHAR(255),
    bus_id VARCHAR(255), -- Adjusted line
    train_id VARCHAR(255), -- Adjusted line
    flight_id VARCHAR(255), -- Adjusted line
    status VARCHAR(20) DEFAULT 'open',
    reply TEXT NOT NULL,
    submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME DEFAULT NULL
);
