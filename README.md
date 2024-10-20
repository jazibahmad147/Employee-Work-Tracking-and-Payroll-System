# Employee Work Tracking and Payroll System

## Overview

The **Employee Work Tracking and Payroll System** is a web-based application that helps manage employee data, festival records, and related financial details. The system is built using PHP and MySQL, providing easy management of employee shifts, payroll, and festival-based working hours.

## Features

- **Employee Management**: Add, update, and manage employee details such as personal information, work rates, and working hours.
- **Festival Management**: Record and manage festivals and events where employees are assigned to work.
- **Financial Records**: Track employee payments, generate invoices, and manage salary records.
- **Work Hours Management**: Record and manage employees' working hours during different festivals, including break times.
- **User Authentication**: Secure login system for administrators.

## Database Structure

The system uses a MySQL database with the following tables:

- `bezeichnungs`: Manages various job titles or designations.
- `festivals`: Records information about festivals or events.
- `gelds`: Manages employee payments with details on amounts, dates, and notes.
- `mitarbeiters`: Stores employee details, including personal information, status, and work hours.
- `mitarbeiterstundes`: Logs employee work hours for different festivals, including start and end times.
- `rechnungdetails`: Manages invoice details related to employees and their working hours.
- `rechnungs`: Stores invoice records linked to specific festivals.
- `users`: Stores information for user authentication.

### Table Structure

1. **`bezeichnungs`**
    - `id`: Primary key, auto-incremented.
    - `name`: Designation name.
    - `status`: Status flag (default 1).

2. **`festivals`**
    - `id`: Primary key, auto-incremented.
    - `name`: Festival name.
    - `status`: Status flag (default 1).

3. **`gelds`**
    - `id`: Primary key, auto-incremented.
    - `date`: Date of payment.
    - `mitarbeiterId`: Employee ID.
    - `amount`: Payment amount.
    - `month`: Month related to the payment.
    - `note`: Notes for the payment.
    - `status`: Status flag (default 1).

4. **`mitarbeiters`**
    - `id`: Primary key, auto-incremented.
    - `vorname`: First name.
    - `nachname`: Last name.
    - `geburtsdatum`: Date of birth.
    - `geburtsort`: Place of birth.
    - `handynummer`: Mobile number.
    - `anschrift`: Address.
    - `rate`: Hourly rate (default '9').
    - `mitarbeiterStatus`: Employee status (default '0').
    - `arbeitszeit`: Working hours (default '0').
    - `arbeitszeitGehalt`: Salary for working hours (default '0').
    - `status`: Status flag (default 1).

5. **`mitarbeiterstundes`**
    - `id`: Primary key, auto-incremented.
    - `date`: Date of the shift.
    - `mitarbeiterId`: Employee ID.
    - `festivalId`: Festival ID.
    - `bezeichnungId`: Job designation ID.
    - `beginn`: Start time.
    - `ende`: End time.
    - `pause`: Break duration (default '0').

6. **`rechnungdetails`**
    - `id`: Primary key, auto-incremented.
    - `rechnungId`: Invoice ID.
    - `date`: Date of the shift.
    - `mitarbeiter`: Employee ID.
    - `bezeichnung`: Job designation.
    - `von`: Start time.
    - `bis`: End time.
    - `pause`: Break duration.

7. **`rechnungs`**
    - `id`: Primary key, auto-incremented.
    - `festivalId`: Festival ID.

8. **`users`**
    - `id`: Primary key, auto-incremented.
    - `name`: Username.
    - `email`: User email.
    - `password`: User password (hashed).

## Installation

1. **Clone the repository:**

    ```bash
    git clone https://github.com/jazibahmad147/Employee-Work-Tracking-and-Payroll-System.git
    cd hb-employee-management
    ```

2. **Database Setup:**

    - Import the `hb.sql` file to set up the database structure:

      ```bash
      mysql -u yourusername -p hb < hb.sql
      ```

    - Update the database connection settings in the project to reflect your MySQL credentials.

3. **Configure Environment:**

    Set up your `.env` file to configure database credentials, environment settings, etc.

4. **Run the Application:**

    Deploy the application on a local or remote server. Make sure PHP, MySQL, and a web server (Apache or Nginx) are properly set up.

## Usage

1. Log in to the system using the administrator credentials.
2. Navigate through the dashboard to manage employees, festivals, and payment records.
3. Generate invoices and manage work hour records.

## Contact

For any issues, questions, or suggestions, please contact:

- Name: Jazib Ahmad
- Email: [jazib.ahmad147@hotmail.com](mailto:jazib.ahmad147@hotmail.com)

