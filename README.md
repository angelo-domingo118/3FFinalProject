# 🌿 SereneBook™ - Wellness Appointment Booking System

## ✨ Description

SereneBook™ is an online booking and reservation management system designed to streamline the process of booking appointments for wellness services. Catering to both customers and therapists, SereneBook™ provides an intuitive interface to manage bookings, payments, and reviews efficiently.

## 🚀 Features

- **🔒 User Authentication**: Secure registration and login for customers, therapists, and administrators.
- **🛠️ Service Management**: Comprehensive management of wellness services with detailed descriptions and pricing.
- **📅 Appointment Booking**: Users can book, view, reschedule, or cancel appointments seamlessly.
- **💳 Payment Integration**: Supports multiple payment methods for a hassle-free transaction experience.
- **👤 User Dashboard**: Personalized dashboards for users to manage their appointments and reviews.
- **🛡️ Admin Dashboard**: Administrators can oversee services, bookings, therapist schedules, and generate reports.
- **📝 Feedback System**: Customers can leave reviews and ratings for their appointments.

## 🖥️ Tech Stack

- **Frontend**: HTML, CSS, JavaScript, [Bootstrap](https://getbootstrap.com/) (via CDN)
- **Backend**: PHP (8.1+)
- **Database**: [MySQL](https://www.mysql.com/)
- **Server**: Apache (via [XAMPP](https://www.apachefriends.org/index.html))

## 🛠️ Prerequisites

Before setting up the project, ensure you have the following installed on your machine:

- **XAMPP**: Includes Apache, MySQL, and PHP.
  - [Download XAMPP](https://www.apachefriends.org/index.html)
- **Git**: For cloning the repository.
  - [Download Git](https://git-scm.com/downloads)

## 🛠️ Installation

Follow these detailed steps to set up and run SereneBook™ locally on your machine:

### 1. Clone the Repository

Open your terminal or command prompt and run the following command to clone the repository:

```bash
git clone https://github.com/angelo-domingo118/SereneBook-Wellness-Appointment-Booking-System
```

### 2. Set Up XAMPP

1. **Install XAMPP** if you haven't already. Follow the installation instructions [here](https://www.apachefriends.org/index.html).

2. **Start Apache and MySQL**:
   - Open the XAMPP Control Panel.
   - Start the **Apache** and **MySQL** modules.

### 3. Import the Database Schema

1. **Open phpMyAdmin**:
   - Navigate to [http://localhost/phpmyadmin](http://localhost/phpmyadmin) in your web browser.

2. **Create the Database**:
   - In phpMyAdmin, click on **Databases**.
   - Enter `spa_booking_db` as the database name and click **Create**.

3. **Import the Schema**:
   - Select the newly created `spa_booking_db` database.
   - Click on the **Import** tab.
   - Click **Choose File** and select the `sql/schema.sql` file from the cloned repository.
   - Click **Go** to import the database schema.

### 4. Configure the Application

1. **Database Configuration**:
   - Navigate to the `config/` directory in your project.
   - Open `config.php` in your preferred text editor.
   - Update the database credentials as needed:

   ```php
   <?php
   // config/config.php

   define('BASE_URL', 'http://localhost/cit17-final-project');
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', ''); // Enter your MySQL password if any
   define('DB_NAME', 'spa_booking_db');
   ```
   
### 5. Set Up the Project in XAMPP

1. **Move Project to XAMPP's `htdocs`**:
   - Copy the `cit17-final-project` folder into XAMPP's `htdocs` directory, typically found at:
     - **Windows**: `C:\xampp\htdocs\`
     - **macOS**: `/Applications/XAMPP/htdocs/`
     - **Linux**: `/opt/lampp/htdocs/`

2. **Access the Application**:
   - Open your web browser and navigate to [http://localhost/cit17-final-project/public](http://localhost/cit17-final-project/public).

### 6. (Optional) Configure Virtual Hosts

For a cleaner URL, you can set up a virtual host:

1. **Edit Apache's `httpd-vhosts.conf`**:
   - Open the `httpd-vhosts.conf` file located at `xampp/apache/conf/extra/httpd-vhosts.conf`.

2. **Add the Virtual Host Configuration**:

   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/cit17-final-project/public"
       ServerName serenebook.local
       <Directory "C:/xampp/htdocs/cit17-final-project/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

   Adjust paths as necessary based on your operating system.

3. **Edit Hosts File**:
   - **Windows**: `C:\Windows\System32\drivers\etc\hosts`
   - **macOS/Linux**: `/etc/hosts`

   Add the following line:

   ```
   127.0.0.1   serenebook.local
   ```

4. **Restart Apache** via XAMPP Control Panel.

5. **Access via Virtual Host**:
   - Navigate to [http://serenebook.local](http://serenebook.local).

## 🚀 Getting Started

### Test Accounts

After importing the database schema, you can use these pre-configured test accounts:

#### Admin Account
- **Email**: admin@spa.com
- **Password**: password
- **Role**: Administrator
- **Access**: Full system administration

#### Customer Account
- **Email**: customer@example.com
- **Password**: password
- **Role**: Customer
- **Access**: Booking and appointment management

#### Therapist Accounts
1. **Maria Santos**
   - Email: maria@spa.com
   - Password: password
   - Role: Therapist

2. **Juan Cruz**
   - Email: juan@spa.com
   - Password: password
   - Role: Therapist

3. **Ana Reyes**
   - Email: ana@spa.com
   - Password: password
   - Role: Therapist

4. **Pedro Garcia**
   - Email: pedro@spa.com
   - Password: password
   - Role: Therapist

5. **Lisa Gomez**
   - Email: lisa@spa.com
   - Password: password
   - Role: Therapist

All accounts use the same password: `password` (hashed in the database)

### Basic Usage

Once the setup is complete, you can:

- **📝 Register** as a new user or admin.
- **🔑 Login** to access the dashboard.
- **📅 Book Services** through the booking system.
- **📂 Manage Appointments** via user/admin dashboards.
- **⭐ Leave Reviews** for completed appointments.

## 🐛 Troubleshooting

- **⚠️ Database Connection Issues**:
  - Ensure MySQL is running in XAMPP.
  - Verify database credentials in `config/config.php`.

- **❌ 404 Not Found Errors**:
  - Ensure `.htaccess` is correctly placed in the `public/` directory.
  - Enable `mod_rewrite` in Apache's configuration.

- **🔓 Permission Issues**:
  - On Unix-based systems, ensure the `public/` directory has appropriate permissions.

```bash
// If needed, adjust permissions
chmod -R 755 public/
```

## 🤝 Contributing

Contributions are welcome! Please follow these steps:

1. **Fork the repository**.
2. **Create a new branch** for your feature or bugfix.
3. **Commit your changes** with clear and descriptive messages.
4. **Push to your forked repository**.
5. **Create a Pull Request** detailing your changes.

## 📄 License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgements

- [Bootstrap](https://getbootstrap.com/) for front-end components.
- [PHP](https://www.php.net/) for server-side scripting.
- [MySQL](https://www.mysql.com/) for database management.
- [XAMPP](https://www.apachefriends.org/index.html) for local server environment.
