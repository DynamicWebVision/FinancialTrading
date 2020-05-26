<?php namespace App\Services;

class MailboxLayer  {

    public function validateEmail($email) {
        // set API Access Key
        $access_key = '1c187379fe075f6d36510a5879ba6ec0';

        // set email address
        $email_address = $email;

        // Initialize CURL:
        $ch = curl_init('https://apilayer.net/api/check?access_key='.$access_key.'&email='.$email_address.'');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $validationResult = json_decode($json, true);

        // Access and use your preferred validation result objects
        $validationResult['format_valid'];
        $validationResult['smtp_check'];
        $validationResult['score'];
    }
}