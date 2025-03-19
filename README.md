Car Orders Tracking System
This is a Car Orders Tracking System built with HTML, CSS, JavaScript, Tailwind CSS, PHP, and MySQL. It allows users to track car orders, manage user authentication, and monitor order status with detailed timelines.

Features
View a table of car orders with client names, VIN numbers, and order status.
Track order status with corresponding dates.
User login and authentication.
Admin dashboard to manage users, create passwords, and assign roles.
Requirements
XAMPP (for running PHP and MySQL)
PHP (compatible version with your XAMPP installation)
MySQL Database
Setup Instructions
1. Install XAMPP
If you don't already have XAMPP installed, you can download it from here.

After downloading, follow the installation instructions for your operating system.

2. Setting up the Project
Download the Project Files:

Clone or download this repository to your local machine.
Place Project Files in XAMPP's 'htdocs' Folder:

Navigate to your XAMPP installation directory, typically at C:\xampp\htdocs.
Create a new folder within htdocs (e.g., car-orders-tracking).
Place all the downloaded project files inside this folder.
3. Set up the MySQL Database
Open phpMyAdmin:

Start the Apache and MySQL servers from the XAMPP control panel.
Open your browser and go to http://localhost/phpmyadmin.
Import the Database:

In phpMyAdmin, create a new database. You can name it car_orders_tracking or any name you'd prefer.
Once the database is created, click on the Import tab.
Select the .sql file (the database file) provided in the project, and click Go to import the data.
4. Configure the Database Connection
Edit the db.php File:

Open the db.php file located in the project folder (usually in the includes or config directory).
Update the database connection settings to match your local MySQL setup.

If you named your database something other than car_orders_tracking, replace "car_orders_tracking" with the correct database name.

5. Run the Project
Access the Project in Your Browser:

Open your web browser and navigate to http://localhost/car-orders-tracking/ (or whatever folder name you used in the htdocs directory).
You should be able to see the Car Orders Tracking System working!
Login:

Use the default credentials provided in the project (if any) or create new ones via the admin dashboard.
Troubleshooting
"Database connection failed" error:
Ensure that XAMPP's MySQL service is running. Check the db.php file to make sure the database credentials are correct.

Access Denied error:
Ensure that MySQL is running and that you’ve correctly set up the root user with no password (default for XAMPP).

Technologies Used
Frontend: HTML, CSS, Tailwind CSS, JavaScript
Backend: PHP
Database: MySQL
License
This project is open-source and available under the MIT License.
