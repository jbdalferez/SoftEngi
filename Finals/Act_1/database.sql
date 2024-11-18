CREATE TABLE applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    phone VARCHAR(15),
    subject_specialization VARCHAR(100),
    application_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Pending', 'Interviewed', 'Hired', 'Rejected') DEFAULT 'Pending'
);
