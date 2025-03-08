<?php
class SMS {
    private $TOKEN = "YOUR-TOKEN-ID";
    private $FROM = "SENDER-ID";
    private $API_URL = "https://send.lk/sms/send.php";
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function sendSMS($mobile, $message, $patientRegNo, $currentDateTime, $sendCatId) {
        try {
            $encodedMessage = urlencode($message);
            $encodedMobile = urlencode(trim($mobile));
            $encodedFrom = urlencode($this->FROM);

            $url = "$this->API_URL?token={$this->TOKEN}&to={$encodedMobile}&from={$encodedFrom}&message={$encodedMessage}";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            $statusId = ($httpCode == 200) ? "1" : "2";
            $this->logMessageStatus($statusId, $patientRegNo, $mobile, $message, $currentDateTime, $sendCatId);
            
            return ($httpCode == 200) ? "Message sent successfully" : "Failed to send message";
        } catch (Exception $e) {
            $this->logMessageStatus("2", $patientRegNo, $mobile, $message, $currentDateTime, $sendCatId);
            return "Error while sending SMS: " . $e->getMessage();
        }
    }

    private function logMessageStatus($statusId, $patientRegNo, $mobile, $message, $currentDateTime, $sendCatId) {
        try {
            $sql = "INSERT INTO massage_send_status (status_id, pations_reg_no, mobile, massage, date, send_cat_id) 
                    VALUES (:status_id, :pations_reg_no, :mobile, :massage, :date, :send_cat_id)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':status_id' => $statusId,
                ':pations_reg_no' => $patientRegNo,
                ':mobile' => $mobile,
                ':massage' => $message,
                ':date' => $currentDateTime,
                ':send_cat_id' => $sendCatId
            ]);
        } catch (Exception $e) {
            error_log("Database error: " . $e->getMessage());
        }
    }

    public function sendAddBooking($mobile, $name, $bookingNu, $bookingDate, $bookingId, $patientRegNo) {
        $message = "Hello $name,\nYour Booking Id Is $bookingId\n$bookingDate.\n$bookingNu.\nThank you for choosing with us!";
        return $this->sendSMS($mobile, $message, $patientRegNo, date('Y-m-d'), "2");
    }
}

// Usage Example
try {
    $pdo = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $sms = new SMS($pdo);
    echo $sms->sendSMS("1234567890", "Test message", "P12345", date('Y-m-d H:i:s'), "1");
} catch (Exception $e) {
    echo "Database connection error: " . $e->getMessage();
}
