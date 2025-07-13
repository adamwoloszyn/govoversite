<?php

namespace App\Services;

use \CURLFile;
use Exception;

// https://sonix.ai/docs/api#new_media
class SonixAI
{
    // Set the API endpoint URL
    protected   $sonix_ai_url;
    protected   $api_key;

     /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->sonix_ai_url       = env( "SONIX_AI_URL" );
        $this->api_key            = env( 'SONIX_API_KEY' );
    }

    public function DownloadTranscription( $mediaID, $whereToSaveFile )
    {
        $returnValue = false;

        // Create a cURL handle
        $ch = curl_init();

        //var_dump("/media/$mediaID");

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $this->sonix_ai_url . "/media/$mediaID/transcript");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $this->BearerToken()
        ));

        // Send the API request
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        if ($httpCode == 200) {
            // The file was downloaded successfully
            // The file contents are in the $response variable
            //echo $whereToSaveFile;
            $file = fopen( $whereToSaveFile, 'w'); // replace with the path and filename of your choice
            fwrite($file, $response);
            fclose($file);

            $returnValue = true;
        } else {
            // There was an error downloading the file
            //echo "Error downloading file. HTTP code: {$httpCode}";
        }

        // Close the cURL handle
        curl_close($ch);

        return $returnValue;
    }   // end of DownloadTranscription()

    public function IsTranscriptionCompleted( $mediaID )
    {
        // Create a cURL handle
        $ch = curl_init();

        //var_dump("/media/$mediaID");

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $this->sonix_ai_url . "/media/$mediaID");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $this->BearerToken()
        ));

        // Send the API request
        $response = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);

        //var_dump($response);

        // Decode response JSON into an array
        $result = json_decode($response, true);

        // Process the API response
        if ($response === false) {
            // Handle cURL error
            $error_message = curl_error($ch);
            $error_code = curl_errno($ch);
            throw new Exception( "cURL error: $error_message (Error code: $error_code)" );
        } else {
            // Print response for debugging
            //var_dump($result);
            return $result['status'] == 'completed';
        }
    }   // end of IsTranscriptionCompleted()

    protected function BearerToken()
    {
        return 'Authorization: Bearer ' . $this->api_key;
    }   // end of BearerToken()

    //
    // Upload given local file path
    // Sonix media ID is returned.
    public function UploadFile($fileNameToUpload)
    {
        // Set the API key and other parameters
        $language = "en-US";

        // Create a cURL handle
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $this->sonix_ai_url . "/media");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            "apikey" => $this->api_key,
            "language" => $language,
            "model"=> "default",
            "file" => new CURLFile($fileNameToUpload),
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $this->BearerToken()
        ));

        // Send the API request
        $response = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);

        // Decode response JSON into an array
        $result = json_decode($response, true);

        // Process the API response
        if ($response === false) {
            // Handle cURL error
            $error_message = curl_error($ch);
            $error_code = curl_errno($ch);
            throw new Exception( "cURL error: $error_message (Error code: $error_code)" );
        } else {
            // Print response for debugging
            //var_dump($result);
            return $result['id'];
        }
    }   // end of UploadFile()
    
    public function UploadFileVIAURL($URLToUpload)
    {
        // Set the API key and other parameters
        $language = "en-US";

        // Create a cURL handle
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $this->sonix_ai_url . "/media");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            "apikey" => $this->api_key,
            "language" => $language,
            "model"=> "default",
            "file_url" => $URLToUpload,
        ]);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            $this->BearerToken()
        ));

        // Send the API request
        $response = curl_exec($ch);

        // Close the cURL handle
        curl_close($ch);

        // Decode response JSON into an array
        $result = json_decode($response, true);

        // Process the API response
        if ($response === false) {
            // Handle cURL error
            $error_message = curl_error($ch);
            $error_code = curl_errno($ch);
            throw new Exception( "cURL error: $error_message (Error code: $error_code)" );
        } else {
            // Print response for debugging
            //var_dump($result);
            return $result['id'];
        }
    }   // end of UploadFileVIAURL()
}   // end of SonixAI class 
