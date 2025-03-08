/**
 * # SMS Sending System
 * 
 * This project allows you to send SMS using the Send.lk API with PHP and logs message statuses into a MySQL database.
Here is a sample README.md for your GitHub repository. It includes information about the PHP SMS class, its functionality, and how to use it.

```markdown
# SMS Sending System with PHP

This repository contains a simple PHP class to send SMS messages using the `send.lk` API. The `SMS` class handles sending SMS messages, logging the status of each message, and storing the status in a MySQL database. It supports sending booking confirmation messages to patients with relevant details.

## Features

- **Send SMS via API:** Sends SMS using `send.lk` API.
- **Message Logging:** Logs the status of sent messages (success/failure) in a MySQL database.
- **Supports Booking Confirmation:** Sends a booking confirmation message containing the booking ID and details.
- **Database Logging:** Automatically logs the message status in a database with relevant details.

## Installation

1. Clone this repository:
   ```bash
   git clone https://github.com/lochana2005/SMS_Gatway_PHP.git
   ```

2. Install the required dependencies (if needed). Make sure you have PHP and MySQL set up on your server.

3. Set up your database:
   - Create a database and table (`massage_send_status`) in MySQL. 
   - Example SQL to create the table:
     ```sql
     CREATE TABLE massage_send_status (
         id INT AUTO_INCREMENT PRIMARY KEY,
         status_id INT NOT NULL,
         pations_reg_no VARCHAR(100) NOT NULL,
         mobile VARCHAR(20) NOT NULL,
         massage TEXT NOT NULL,
         date DATETIME NOT NULL,
         send_cat_id INT NOT NULL
     );
     ```

4. Replace the following placeholders in the `SMS.php` file with your actual values:
   - `YOUR-TOKEN-ID`: Your API token from `send.lk`.
   - `SENDER-ID`: Your sender ID as configured in the API.
   - Database connection credentials: `localhost`, `your_database`, `username`, `password`.

## Usage

### Creating an SMS Object

To create an instance of the SMS class, you need to pass a PDO object with a database connection:

```php
$pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sms = new SMS($pdo);
```

### Sending an SMS

You can send an SMS by calling the `sendSMS` method and providing the recipient's mobile number, message, patient registration number, current date/time, and the sending category ID.

```php
echo $sms->sendSMS("1234567890", "Test message", "P12345", date('Y-m-d H:i:s'), "1");
```

### Sending Booking Confirmation

You can send a booking confirmation message using the `sendAddBooking` method:

```php
$mobile = "1234567890";
$name = "John Doe";
$bookingNu = "12345";
$bookingDate = "2025-03-10";
$bookingId = "A123";
$patientRegNo = "P12345";

echo $sms->sendAddBooking($mobile, $name, $bookingNu, $bookingDate, $bookingId, $patientRegNo);
```

## Error Handling

The system logs any errors related to the database or the SMS sending process. If an error occurs during SMS sending, the error message is captured and logged in the `massage_send_status` table with a status of `2` (failed).

## Contribution

Feel free to fork this repository, create issues, and submit pull requests. Contributions are welcome!

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
```

### Key Sections:
- **Features**: Explains the functionality of your class.
- **Installation**: Details the setup process for the project.
- **Usage**: Provides code examples for using the SMS class and methods.
- **Error Handling**: Describes how errors are logged and managed.
- **Contribution**: Invites others to contribute to the project.
- **License**: Includes a basic license section.

This README should help others understand how to use and contribute to your project. Let me know if you'd like to add or modify anything!
