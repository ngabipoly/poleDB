<?php
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

helper('filesystem');
/**
 * Writes a log entry to a file.
 *
 * @param string $content The content to be written to the log.
 * @throws Exception If there is an error writing to the log file.
 */
    function writeLog(string $content){
        $log_file = APPLOG.'log_'.date('Ymd').'.log';

        if (!file_exists($log_file)) {
            //log  File doesn't exist, create a new file and write to it
            write_file($log_file, date('Y-m-d h:i:s')." - $content");
        } else {
            //append the text content to it
            file_put_contents($log_file, "\r\n".date('Y-m-d h:i:s')." - $content", FILE_APPEND);
        }
    }

/**
 * Checks if a substring of a string matches a given pattern and optionally a specified length.
 *
 * @param string $input_string The input string to be checked.
 * @param int $begin_pos The starting position of the substring.
 * @param int $end_pos The ending position of the substring.
 * @param string $pattern The pattern to match.
 * @param int|null $length The optional length to check against.
 * @return bool Returns true if the substring matches the pattern and the specified length (if provided), false otherwise.
 */
    function checkNumber($input_string,$begin_pos,$end_pos,$pattern,$length=null) {

        if($length){
            if (substr($input_string, $begin_pos, $end_pos) === $pattern && strlen($input_string) === $length) {
                return true;
            }                   
        }

        /*** 
         * when no length is specified
         * only Check if the contains a specific pattern
         ***/
        if (substr($input_string, $begin_pos, $end_pos) === $pattern) {
            return true;
        } 
            return false;              

    }


    /**
     * Calculates the distance between two points on the surface of a sphere (such as the Earth)
     * using the Haversine formula.
     *
     * @param float $point1Latitude The latitude of the first point in decimal degrees.
     * @param float $point1Longitude The longitude of the first point in decimal degrees.
     * @param float $point2Latitude The latitude of the second point in decimal degrees.
     * @param float $point2Longitude The longitude of the second point in decimal degrees.
     * @return float $distanceKms The distance between the two points in kilometers.
     */
    function calculatePointDistance(float $point1Latitude, float $point1Longitude, float $point2Latitude, float $point2Longitude): float {
        $earthRadius = 6371; // Earth radius in kilometers

        $dLat = deg2rad($point2Latitude - $point1Latitude);
        $dLon = deg2rad($point2Longitude - $point1Longitude);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($point1Latitude)) * cos(deg2rad($point2Latitude)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distanceKms = $earthRadius * $c;

        return $distanceKms; // Distance in kilometers
    }

    /**
     * Calculates the total length of a media (e.g. a route) in kilometers, given an array of coordinates.
     *
     * @param array $coordinates An array of coordinates, each containing 'lat' and 'lon' keys.
     * @return float The total length of the media in kilometers.
     */
    function calculateMediaLength(array $coordinates): float {
        $totalMediaLength = 0.0;

        // Loop through consecutive points in the route
        for ($i = 0; $i < count($coordinates) - 1; $i++) {
            $point1 = $coordinates[$i];
            $point2 = $coordinates[$i + 1];

            $totalMediaLength += calculatePointDistance(
                $point1['lat'], $point1['lon'],
                $point2['lat'], $point2['lon']
            );
        }

        return $totalMediaLength;
    }

    function smsAlert(int $msisdn, string $message, string $sender){
        $curl = curl_init();
        $fields = json_encode([
            "sender"=>$sender,
            "recipient"=>$msisdn,
            "message"=>$message
        ]);

        curl_setopt_array($curl, array(
          CURLOPT_URL => SMS_API_URL,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>$fields,
          CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json',
            'Authorization: Basic VmFzQXBwOlZhc0RldkAxMjM0'
          ),
        ));
        
        $response = curl_exec($curl);
        writeLog("Sending SMS {$message} to {$msisdn} from {$sender} - {$response}");
        
        curl_close($curl);
        return $response;        
    }


    function readOut(string $path,string $file_name){
        $file_path = trim($path.$file_name);
        $file_content = [];
        $open_file = fopen($file_path,'r');
        CLI::write('Opening: Success, Getting Content!');
        while (($content=fgetcsv($open_file))!==false) {            
            if(!empty($content[0]) && !empty($content[1])){
                array_push($file_content,$content);
            }                
        }
        fclose($open_file);
        return $file_content;
    }    
    
    function moveFile($source_path, $destination_path){
        if (rename($source_path, $destination_path)) {
            CLI::write('INFO:: File moved successfully.');
        } else {
            CLI::write('Error:: Error moving the file.');
        }
    }

    function showError($message)
    {
       $message= date("Y-m-d H:i:s")." ERROR:: ".$message;
        CLI::write($message);
    }

    function showMessage($message)
    {
        $message= date("Y-m-d H:i:s")." INFO:: ".$message;
        CLI::write($message);
    }


    function sendMail(string $from, string $to, string $subject,string $message,string $cc=null){
        $curl = curl_init();
        $fields = json_encode([
            "From"=> $from,
            "To"=> $to,
            "Sub"=> $subject,
            "Msg"=> $message
        ]);
        curl_setopt_array($curl, array(
            CURLOPT_URL => EMAIL_API_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS =>$fields,
            CURLOPT_HTTPHEADER => array(
              'Content-Type: application/json'
            ),
          ));
          
          writeLog("Sending Email  to {$to} about {$subject}");    
          $response = curl_exec($curl);        
          curl_close($curl);
          writeLog("Mail sending Complete Response:{$response}");
          return $response;         

    }

    //function to encode responses to JSON format
    function jEncodeResponse($data,$msg, $status, $status_code = 200, $redirect=false, $redirect_url = null) {
        return json_encode([
            'status_code' => $status_code,
            'status' => $status,
            'msg' => $msg,
            'data' => $data,
            'redirect' => $redirect,
            'redirect_url' => $redirect_url
        ]);
    }

    