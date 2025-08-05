# 🏛️ Ozeum – Museum Management System

> **📚 Web Programming Course Project**  
> This project is a student assignment for the *Web Programming* course.  
> It is inspired by the [Ozeum](https://ozeum.themerex.net) and was developed purely for academic purposes to improve PHP development skills.


## ✨ Project Overview

**Ozeum** is a lightweight PHP-based museum management system that allows both visitors and administrators to interact with museum content. It includes core features such as:

- Exhibition and artwork management  
- Ticket purchasing and category handling  
- Artist catalog browsing  
- User registration and shopping cart  
- Admin dashboard for museum staff

## 🔍 Features

### 🔓 Public Side

- Browse exhibitions, artworks, and artist profiles  
- Purchase tickets and merchandise  
- Register and log in as a user  
- Manage shopping cart and track orders  

### 🔐 Admin Side

- Dashboard overview of museum activity  
- Manage exhibitions, artworks, and artists  
- Handle ticket categories and merchandise orders  
- Manage user accounts and roles

### 🔒 Security Features

- Passwords hashed using password_hash()
- Prepared statements to prevent SQL injection
- XSS protection via htmlspecialchars()
- Session-based login authentication
- Role-based access control

## 🛠️ Tech Stack

- **Backend**: PHP 7.4+  
- **Frontend**: HTML5, CSS3, JavaScript  
- **Database**: MySQL 5.7+ using PDO  
- **Architecture**: MVC-like with Service Layer  
- **Server**: Apache/Nginx (recommended: XAMPP)

## 🛡️ Admin access

Newly registered users have the role `USER` by default.
To access the admin dashboard, manually promote a user by running this SQL:

UPDATE users
SET role = 'ADMIN'
WHERE id = 1; -- Replace with actual user ID



