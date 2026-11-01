
CREATE DATABASE IF NOT EXISTS studenthub;
USE studenthub;


DROP TABLE IF EXISTS audit_logs; DROP TABLE IF EXISTS registrations; DROP TABLE IF EXISTS events;
DROP TABLE IF EXISTS students; DROP TABLE IF EXISTS users;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY, username VARCHAR(50) NOT NULL UNIQUE, email VARCHAR(100) NOT NULL UNIQUE, password VARCHAR(255) NOT NULL,
role ENUM('admin', 'student') NOT NULL DEFAULT 'student', remember_token VARCHAR(255) DEFAULT NULL, last_login DATETIME DEFAULT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE students (
id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL UNIQUE,

course VARCHAR(100) NOT NULL, year INT NOT NULL DEFAULT 1, phone VARCHAR(15) DEFAULT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;


CREATE TABLE events (
id INT AUTO_INCREMENT PRIMARY KEY, title VARCHAR(200) NOT NULL,
description TEXT,
event_date DATE NOT NULL,
category ENUM('Workshop', 'Seminar', 'Cultural', 'Sports', 'Technical') NOT NULL DEFAULT 'Workshop',
status ENUM('Open', 'Closed') NOT NULL DEFAULT 'Open', poster VARCHAR(255) DEFAULT NULL,
created_by INT DEFAULT NULL,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;


CREATE TABLE registrations (
id INT AUTO_INCREMENT PRIMARY KEY,
student_id INT NOT NULL, event_id INT NOT NULL,
registered_at DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE, FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
) ENGINE=InnoDB;


CREATE TABLE audit_logs (
id INT AUTO_INCREMENT PRIMARY KEY,
user_id INT DEFAULT NULL,

action VARCHAR(255) NOT NULL,
details TEXT,
created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;



-- Admin user (password: Admin@123)
INSERT INTO users (username, email, password, role) VALUES ('admin', 'admin@studenthub.com',
'$2y$10$92IXUNpkjO0rOQ5byMi/kOcIO38IeEOaNfeYHNEjJNbnohn8e', 'admin'),
('hemanshu', 'hemanshu@studenthub.com', '$2y$10$92IXUNpkjO0rOQ5byMi/kOcIO38IeEOaNfeYHNEjJNbnohn8e', 'student');


-- 20+ Students
INSERT INTO students (name, email, course, year, phone) VALUES
('Hemanshu Dholariya', 'hemanshu@example.com', 'Computer Engineering', 2, '9876543210'), ('Arjun Patel', 'arjun@example.com', 'Computer Engineering', 2, '9876543211'), ('Priya Sharma', 'priya@example.com', 'Information Technology', 3, '9876543212'), ('Ravi Kumar', 'ravi@example.com', 'Computer Engineering', 1, '9876543213'), ('Ananya Desai', 'ananya@example.com', 'Electronics', 2, '9876543214'),
('Karan Mehta', 'karan@example.com', 'Computer Engineering', 3, '9876543215'), ('Sneha Joshi', 'sneha@example.com', 'Information Technology', 1, '9876543216'), ('Vikram Singh', 'vikram@example.com', 'Mechanical', 2, '9876543217'),
('Nisha Gupta', 'nisha@example.com', 'Computer Engineering', 2, '9876543218'), ('Amit Shah', 'amit@example.com', 'Electronics', 3, '9876543219'),
('Divya Rana', 'divya@example.com', 'Information Technology', 2, '9876543220'), ('Rohit Verma', 'rohit@example.com', 'Computer Engineering', 1, '9876543221'), ('Pooja Nair', 'pooja@example.com', 'Mechanical', 3, '9876543222'),
('Siddharth Jain', 'siddharth@example.com', 'Computer Engineering', 2, '9876543223'), ('Meera Iyer', 'meera@example.com', 'Information Technology', 1, '9876543224'), ('Harsh Trivedi', 'harsh@example.com', 'Electronics', 2, '9876543225'),
('Kavita Bhatt', 'kavita@example.com', 'Computer Engineering', 3, '9876543226'),

('Nikhil Tiwari', 'nikhil@example.com', 'Information Technology', 2, '9876543227'), ('Swati Kulkarni', 'swati@example.com', 'Computer Engineering', 1, '9876543228'), ('Deepak Chauhan', 'deepak@example.com', 'Mechanical', 2, '9876543229'),
('Isha Pandey', 'isha@example.com', 'Computer Engineering', 3, '9876543230'), ('Manish Agarwal', 'manish@example.com', 'Electronics', 1, '9876543231');

-- Events
INSERT INTO events (title, description, event_date, category, status, created_by) VALUES ('Web Dev Workshop', 'Learn HTML, CSS, JS basics', '2026-11-15', 'Workshop', 'Open', 1), ('AI Seminar', 'Introduction to Artificial Intelligence', '2026-11-20', 'Seminar', 'Open', 1), ('Cultural Fest', 'Annual cultural festival', '2026-12-01', 'Cultural', 'Open', 1),
('Cricket Tournament', 'Inter-department cricket', '2026-12-10', 'Sports', 'Open', 1), ('Hackathon 2026', 'Annual coding competition', '2026-11-25', 'Technical', 'Open', 1),
('Cloud Computing Seminar', 'Explore cloud technologies', '2026-12-05', 'Seminar', 'Closed', 1);


-- Registrations
INSERT INTO registrations (student_id, event_id) VALUES (1, 1), (1, 5), (2, 1), (2, 2), (3, 3), (4, 1),
(5, 4), (6, 5), (7, 2), (8, 4), (9, 1), (10, 3),
(11, 2), (12, 5), (13, 4), (14, 1), (15, 3), (16, 6);


-- Audit logs
INSERT INTO audit_logs (user_id, action, details) VALUES (1, 'LOGIN', 'Admin logged in'),
(1, 'CREATE_EVENT', 'Created event: Web Dev Workshop'), (1, 'CREATE_EVENT', 'Created event: AI Seminar');


