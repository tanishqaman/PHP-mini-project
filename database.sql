CREATE DATABASE IF NOT EXISTS online_voting_db;
USE online_voting_db;

-- 1. Users Table (Voters & Admin)
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    voter_id VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('voter', 'admin') DEFAULT 'voter',
    has_voted TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Candidates Table
CREATE TABLE IF NOT EXISTS candidates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    party VARCHAR(100) NOT NULL,
    manifesto TEXT NOT NULL,
    vote_count INT DEFAULT 0
);

-- 3. Votes Audit Log Table
CREATE TABLE IF NOT EXISTS votes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    candidate_id INT NOT NULL,
    voted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (candidate_id) REFERENCES candidates(id) ON DELETE CASCADE
);

-- Clear old data safely without Foreign Key error
DELETE FROM votes;
DELETE FROM candidates;
DELETE FROM users;

ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE candidates AUTO_INCREMENT = 1;
ALTER TABLE votes AUTO_INCREMENT = 1;

-- Insert Default Admin (Voter ID: ADMIN001 | Password: password123)
INSERT INTO users (full_name, voter_id, email, password, role, has_voted) VALUES 
('Election Officer', 'ADMIN001', 'admin@college.edu', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 0);

-- Insert Verified Candidates mentioned in SRS (Tanishq & Barnali)
INSERT INTO candidates (name, party, manifesto, vote_count) VALUES 
('Tanishq', 'Student Progressive Alliance', 'Better lab facilities, extended library hours, and transparent cultural fest budgets.', 0),
('Barnali', 'Campus Unity Front', 'High-speed campus Wi-Fi, mental health workshops, and stronger placement cell support.', 0);