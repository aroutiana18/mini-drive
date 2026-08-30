# Mini Drive

> A secure, LDAP-authenticated lightweight self-hosted file storage built with PHP, MySQL and a custom MVC architecture.

![PHP](https://img.shields.io/badge/PHP-Native-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-Database-4479A1?logo=mysql&logoColor=white)
![Linux](https://img.shields.io/badge/Linux-Ubuntu-FCC624?logo=linux&logoColor=black)
![Status](https://img.shields.io/badge/Status-In%20Development-orange)

**Mini-Drive** is a self-hosted cloud storage web interface designed for enterprise directory infrastructures. Users authenticate against a central **OpenLDAP** directory server using their corporate credentials (`email` + `password`), while metadata and file ownership are mapped directly via **MariaDB** using session-based email tracking.

The project is intentionally built with native PHP, without a third-party framework, to provide a practical implementation of MVC architecture, custom routing, database abstraction with PDO, file handling, and Linux filesystem permissions.

The application is designed to be deployed on a Linux server, making it both a web development project and a practical system administration exercise.

---

## 📁 Project Structure

```text
mini-drive/
├── app/
│   ├── Controllers/   # Application request handlers
│   ├── Core/          # MVC core components, routing and database layer
│   ├── Models/        # Database and application models
│   └── Views/         # Application views and layouts
├── config/            # Application configuration and database schema
├── public/            # Web assets and file storage
├── index.php          # Application entry point
└── README.md
```

---

## ✨ Features

- **User Authentication** — User registration and login.
- **File Upload** — Upload and store files through the web interface.
- **File Management** — Organize, access and manage stored files.
- **File Sharing** — Share stored files through the application.
- **Trash Management** — Soft-delete files and manage deleted items.
- **Custom MVC Architecture** — Controllers, models, views and a custom routing layer built from scratch.
- **Database Access** — MySQL/MariaDB integration through PDO.
- **Linux File Permissions** — Storage permissions managed at the filesystem level.
- **Responsive Interface** — Web interface designed for desktop and mobile usage.

---

## 🏗️ Architecture & Data Architecture

Mini Drive follows a lightweight MVC architecture implemented from scratch in native PHP.

The application uses:

- **Controllers** to handle application requests and business flow.
- **Models** to interact with the database.
- **Views** to render the user interface.
- **Router** to map HTTP requests to application controllers.
- **PDO** for database communication.

```text
 ┌──────────────────┐
 │     OpenLDAP     │
 │                  │
 │ email + password │
 └────────┬─────────┘
          │
      authentifies
          │
          ▼
 ┌──────────────────┐
 │    Mini-Drive    │
 │                  │
 │ session email    │
 └────────┬─────────┘
          │
     user_email
          │
          ▼
 ┌──────────────────┐
 │     MariaDB      │
 │                  │
 │ files            │
 │ user_email       │
 └──────────────────┘
```

---

## 🛠️ Technical Stack

| Layer | Technology |
|---|---|
| Backend | Native PHP 8.x |
| Authentication | OpenLDAP (`php-ldap`) |
| Architecture | Custom MVC |
| Database | MySQL / MariaDB |
| Database Access | PDO (`pdo_mysql`) |
| Frontend | HTML5, CSS3, JavaScript |
| Web Server | Apache2 (`mod_rewrite`, SSL/TLS) |
| Target Environment | Ubuntu Server / Linux |

For local development, the application can be run using XAMPP or a native PHP/Apache environment.

---

## ⚙️ Installation

### Prerequisites

- Ubuntu Server running Apache2, MariaDB, and OpenLDAP.
- PHP extensions: `php-ldap`, `php-mysql`.

### 1. Clone the repository

   ```bash
   git clone https://github.com/aroutiana18/mini-drive.git
   cd mini-drive
   ```

### 2. Create the database

Import the SQL schema:

   ```bash
   mysql -u root -p < config/mini_drive.sql
   ```

Or import `config/mini_drive.sql` using phpMyAdmin.

### 3. Configure the application

Update the database configuration in: `config/config.php`

### 4. Configure file storage permissions

On Linux:

   ```bash
   sudo chown -R www-data:www-data public/uploads
   sudo chmod -R 775 public/uploads
   ```

---

## 🖥️ Deployment

Mini Drive is intended to be deployed on a Linux server.

The planned deployment environment includes:

- Ubuntu Server
- Apache
- PHP
- MySQL / MariaDB
- Linux filesystem permissions

The deployment process is part of the project itself and is intended to provide practical experience with Linux server administration, web server configuration, database services, and filesystem permissions.

---

## 📌 Project Status

**Status:** In development

Mini Drive is currently being developed as a personal learning project focused on PHP web development, Linux server administration, and self-hosted application deployment.

---

## 📸 Screenshots

### Dashboard

![Mini Drive Dashboard](docs/dashboard.png)

### Trash

![Mini Drive Trash](docs/trash.png)

## 👥 Authors

Computer Science student focused on **Systems & Network Administration**.

**Arotiana Brad Florentin MAHERINANDRASANA**

- GitHub: [@aroutiana18](https://github.com/aroutiana18)
- LinkedIn: [Arotiana](https://www.linkedin.com/in/arotiana-brad-florentin-maherinandrasana/)

**Fy Mijoro LAHATRINIAVO**

- GitHub: [@fymijoro](https://github.com/fymijoro)
- LinkedIn: [Fy Mijoro](https://www.linkedin.com/in/fy-mijoro-lahatriniavo-1453a93b1/)
