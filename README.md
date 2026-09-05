# Mini Drive

> A secure, LDAP-authenticated lightweight self-hosted file storage built with PHP, MariaDB and a custom MVC architecture.

![PHP](https://img.shields.io/badge/PHP-Native-777BB4?logo=php&logoColor=white)
![MariaDB](https://img.shields.io/badge/MariaDB-Database-003545?logo=mariadb&logoColor=white)
![OpenLDAP](https://img.shields.io/badge/Authentication-OpenLDAP-0088CC?logo=ldap&logoColor=white)
![Linux](https://img.shields.io/badge/Linux-Ubuntu-FCC624?logo=linux&logoColor=black)
![Apache](https://img.shields.io/badge/Web_Server-Apache-D22128?logo=apache&logoColor=white)
![Status](https://img.shields.io/badge/Status-In%20Development-orange)

**Mini-Drive** is a lightweight self-hosted file storage web application designed to operate within a Linux server infrastructure with centralized user authentication.

Instead of maintaining a separate application account database, Mini-Drive authenticates users against the server's **OpenLDAP directory**, which is integrated with the mail infrastructure. Users access the application using the same **email address and password** associated with their directory account.

Once authenticated, the application maintains the user's email address in the session and uses it as the ownership identifier for stored files in **MariaDB**. This ensures that each user's files remain logically isolated: a user can access and manage their own files through the application without seeing files belonging to other users.

The project is intentionally built with native PHP, without a third-party framework, to provide a practical implementation of MVC architecture, custom routing, database abstraction with PDO, LDAP authentication, file handling, and Linux server deployment.

The application is deployed as part of a broader Linux server environment combining **web, mail, directory, DNS, and application services**.

---

## 📁 Project Structure

```text
mini-drive/
│
├── app/
│   ├── Controllers/   # Application request handlers
│   ├── Core/          # MVC core components, routing and database layer
│   ├── Models/        # Database and application models
│   └── Views/         # Application views and layouts
│
├── config/            # Application configuration and database schema
├── public/            # Web assets and file storage
├── index.php          # Application entry point
└── README.md
```

---

## ✨ Features

- **User Authentication** — Authentication against the server's centralized OpenLDAP directory using email and password.
- **Centralized Identity** — Application access is based on existing directory accounts rather than a separate local user-account system.
- **File Upload** — Upload and store files through the web interface.
- **File Management** — Organize, access and manage stored files.
- **Per-User File Isolation** — Files are associated with the authenticated user's email address, preventing users from accessing other users' files through the application.
- **File Sharing** — Share stored files through the application.
- **Trash Management** — Soft-delete files and manage deleted items.
- **Custom MVC Architecture** — Controllers, models, views and a custom routing layer built from scratch.
- **Database Access** — MariaDB integration through PDO.
- **Linux File Permissions** — Storage permissions managed at the filesystem level.
- **Responsive Interface** — Web interface designed for desktop and mobile usage.
- **Self-Hosted Deployment** — Designed to run on an organization's own Linux server infrastructure.

---

## 🏗️ Architecture & Data Architecture

Mini Drive follows a lightweight MVC architecture implemented from scratch in native PHP.

The application uses:

- **Controllers** to handle application requests and business flow.
- **Models** to interact with the database.
- **Views** to render the user interface.
- **Router** to map HTTP requests to application controllers.
- **PDO** for database communication.
- **OpenLDAP** as the centralized authentication source.
- **PHP LDAP extension** to communicate with the LDAP directory.
- **Session-based identity** to associate the authenticated user with their files.
- **MariaDB** to store file metadata and ownership information.

### Authentication and File Ownership Flow

```text
                  ┌─────────────────────┐
                  │    Mail Server      │
                  │                     │
                  │   OpenLDAP Users    │
                  │                     │
                  │ email + password    │
                  └──────────┬──────────┘
                             │
                       LDAP authentication
                             │
                             ▼
                  ┌─────────────────────┐
                  │     Mini-Drive      │
                  │                     │
                  │   Native PHP MVC    │
                  │                     │
                  │  Session: email     │
                  └──────────┬──────────┘
                             │
                        user_email
                             │
                             ▼
                  ┌─────────────────────┐
                  │       MariaDB       │
                  │                     │
                  │  File metadata      │
                  │  user_email         │
                  └─────────────────────┘
```

### Infrastructure Context

Mini-Drive is not intended to operate as an isolated web application. It is deployed within a Linux server environment where several services work together:

```text
                    ┌───────────────────┐
                    │      Client       │
                    │   Web Browser     │
                    └─────────┬─────────┘
                              │
                         HTTPS / HTTP
                              │
                              ▼
                    ┌───────────────────┐
                    │      Apache2      │
                    │    Web Server     │
                    └─────────┬─────────┘
                              │
                              ▼
                    ┌───────────────────┐
                    │    Mini-Drive     │
                    │     PHP MVC       │
                    └──────┬───────┬────┘
                           │       │
                    LDAP   │       │ PDO
                           │       │
                           ▼       ▼
                    ┌──────────┐ ┌──────────┐
                    │ OpenLDAP │ │ MariaDB  │
                    │ Users    │ │ Files    │
                    └──────────┘ └──────────┘
```

The **mail server and LDAP directory provide the centralized identity source**, while Mini-Drive consumes that identity for application authentication. MariaDB remains responsible for the application's file metadata and ownership mapping.

---

## 🛠️ Technical Stack

| Layer | Technology |
|---|---|
| Backend | Native PHP 8.x |
| Authentication | OpenLDAP (`php-ldap`) |
| Identity | Email-based LDAP accounts |
| Architecture | Custom MVC |
| Database | MariaDB |
| Database Access | PDO (`pdo_mysql`) |
| Frontend | HTML5, CSS3, JavaScript |
| Web Server | Apache2 (`mod_rewrite`, SSL/TLS) |
| Directory Service | OpenLDAP |
| Operating System | Ubuntu Server / Linux |
| Deployment | Self-hosted Linux server |

For local development, the application can be run using a native PHP/Apache environment. However, the complete architecture is designed around a Linux server deployment with LDAP-based authentication.

---

## ⚙️ Installation

### Prerequisites

A complete deployment requires a Linux server environment with:

- Ubuntu Server
- Apache2
- PHP 8.x
- MariaDB
- OpenLDAP
- PHP LDAP extension
- PHP MySQL/PDO extension
- Apache `mod_rewrite`
- SSL/TLS support for HTTPS deployment

> **Note:** Mini-Drive relies on the existing LDAP directory for authentication. The LDAP users and their credentials are therefore managed outside the application.

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

Or import `config/mini_drive.sql` using a database administration tool such as phpMyAdmin.

### 3. Configure the application

Update the application configuration in:

```text
config/config.php
```

The configuration must contain the appropriate database connection settings and LDAP connection parameters for the target server environment.

Typical LDAP parameters include:

```text
LDAP_HOST
LDAP_PORT
LDAP_BASE_DN
```

Do not commit server-specific credentials or sensitive configuration values to the repository.

### 4. Configure file storage permissions

On the Linux server, ensure that Apache can access the application's storage location:

```bash
sudo chown -R www-data:www-data public/uploads
sudo chmod -R 775 public/uploads
```

Adjust the permissions according to the security requirements of the deployment environment.

---

## 🌐 LDAP & Server Integration

Mini-Drive uses **OpenLDAP as the authentication authority** rather than storing application passwords locally.

The intended authentication flow is:

```text
User
  │
  │ email + password
  ▼
Mini-Drive
  │
  │ LDAP authentication request
  ▼
OpenLDAP
  │
  ├── Valid credentials ──► Application access
  │
  └── Invalid credentials ► Authentication rejected
```

After successful authentication, the user's email address is stored in the PHP session and used to associate application data with the authenticated identity.

The application itself does **not** need to maintain a separate password database.

### Why this architecture?

Centralizing authentication provides a cleaner separation of responsibilities:

- **OpenLDAP** manages user identities and credentials.
- **Mini-Drive** manages application functionality and file ownership.
- **MariaDB** stores application data and file metadata.
- **Apache2** serves the web application.
- **Linux** provides the underlying server and filesystem security.

This architecture also makes Mini-Drive suitable for environments where multiple services already rely on a centralized directory.

> The repository documents the application's LDAP integration. The installation and configuration of the organization's complete mail/LDAP infrastructure are deployment-specific and should be documented separately rather than embedded as mandatory application installation steps.

---

## 🔐 Security Considerations

Mini-Drive is designed around several basic security principles:

- Centralized authentication through OpenLDAP
- No application-level password storage
- User-specific file ownership mapping
- Session-based authenticated identity
- Server-side file management
- Linux filesystem permissions
- HTTPS/TLS support through Apache
- PDO-based database access

A production deployment should additionally enforce appropriate policies for password management, LDAP access, HTTPS certificates, file upload validation, session security, filesystem permissions, backups, logging, and server hardening.

---

## 🖥️ Deployment

Mini Drive is intended to be deployed on a Linux server.

The deployment environment includes:

- Ubuntu Server
- Apache2
- PHP
- MariaDB
- OpenLDAP
- Linux filesystem permissions
- HTTPS/SSL/TLS

The deployment is part of the project itself and provides practical experience with:

- Linux server administration
- Web server configuration
- LDAP directory integration
- Database services
- PHP application deployment
- Filesystem permissions
- HTTPS configuration
- Service integration

The application can therefore be viewed not only as a PHP web application, but as a component integrated into a broader **Linux-based infrastructure**.

---

## 📌 Project Status

**Status:** In development

Mini Drive is actively being developed as a self-hosted file storage application focused on:

- Native PHP development
- Custom MVC architecture
- LDAP authentication
- Linux server administration
- Web server deployment
- Database management
- Secure service integration

The current architecture successfully integrates the application with a centralized LDAP identity source and associates authenticated users with their own file data in MariaDB.

---

## 📸 Screenshots

### Dashboard

![Mini Drive Dashboard](docs/dashboard.png)

### Trash

![Mini Drive Trash](docs/trash.png)

---

## 👥 Authors

Computer Science students focused on **Systems & Network Administration**.

### **Arotiana Brad Florentin MAHERINANDRASANA**

- GitHub: [@aroutiana18](https://github.com/aroutiana18)
- LinkedIn: [Arotiana](https://www.linkedin.com/in/arotiana-brad-florentin-maherinandrasana/)

### **Fy Mijoro LAHATRINIAVO**

- GitHub: [@fymijoro](https://github.com/fymijoro)
- LinkedIn: [Fy Mijoro](https://www.linkedin.com/in/fy-mijoro-lahatriniavo-1453a93b1/)