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

    