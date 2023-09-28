CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    department VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    attachment VARCHAR(255),
    status VARCHAR(20) DEFAULT, 'open' -- Default status is 'open'
    reply TEXT NOT NULL
);
