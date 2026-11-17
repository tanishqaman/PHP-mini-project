# Online Voting System - Full Stack PHP & MySQL Project

## 📌 Project Overview
The **Online Voting System** is a secure web-based application designed for educational institutions, organizations, and clubs to conduct digital elections. The system ensures voter authenticity, single-vote integrity, and provides live analytics for election results.

---

## 🛠️ Tech Stack
- **Frontend:** HTML5, CSS3 (Custom Responsive Layout), JavaScript (ES6)
- **Backend:** PHP 8.x
- **Database:** MySQL / MariaDB
- **Server:** Apache (XAMPP / WAMP / LAMP)

---

## 🚀 Setup & Installation Instructions

1. **Install XAMPP** (or WAMP/LAMP stack).
2. Start **Apache** and **MySQL** services from the XAMPP Control Panel.
3. Copy the `online_voting_system_project` folder to your server directory:
   - For XAMPP: `C:/xampp/htdocs/online_voting_system_project`
4. **Import Database:**
   - Open browser and go to `http://localhost/phpmyadmin`
   - Create a new database named `online_voting_db`
   - Click **Import** tab and select `database.sql` from the project folder.
5. **Run Application:**
   - Access Voter Portal: `http://localhost/online_voting_system_project/index.php`
   - Access Admin Portal: `http://localhost/online_voting_system_project/admin_login.php`

---

## 🔑 Default Admin Credentials
- **Voter ID:** `ADMIN001`
- **Password:** `admin123`

---

## 📋 Features & Functionalities
1. **Voter Registration & Login:** Secure authentication using bcrypt password hashing.
2. **Single-Vote Integrity:** Prevents voters from submitting multiple ballots.
3. **Candidate Manifestos:** Clear overview of candidate credentials and goals.
4. **Live Election Results:** Visual progress bars displaying real-time vote totals and percentages.
5. **Admin Management Dashboard:** Add/remove candidates, track total turnout, and monitor voting statistics.
