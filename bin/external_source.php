<?php
# Title: External Sources Version 1.0.0.0
# Authour: LEVIDOC
# Description: Remote Storage For The Data used for static sites
# Build: 25/12/2024

class external_source
{
    private $api_endpont;
    private $error_file;
    private $external_links;

    public function __construct()
    {
        $this->error_file = "external_source_error.log";
        $this->api_endpont = "YOUR_API_ENDPOINT";
        $this->external_links = [
            'create' => '/external_server/create/',
            'read' => '/external_server/read/',
            'update' => '/external_server/update/',
            'delete' => '/external_server/delete/',
        ];
    }

    public function _active(){

        $url = $this->api_endpont; 
        $curl_service = curl_init($url); 
        curl_setopt($curl_service, CURLOPT_HEADER, 0);
        curl_setopt($curl_service, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_service, CURLOPT_FOLLOWLOCATION, 1);

        @$response = curl_exec($curl_service);
        curl_close($curl_service);
        if ($response === false){
            $this->error_log('Failed To Activate The API'); 
            return false;
        }

        return true; 
    }

    private function error_log($error_)
    {
        try {
            $FileHandle = fopen($this->error_file, 'a+');
            $date = date("m/d/Y");
            $errorMessage = $error_;
            $curlError = $date . ' Error: ' . $errorMessage . "\n\n";
            fwrite($FileHandle, $curlError);
            return fclose($FileHandle);
        } catch (\Throwable $th) {
            return FALSE;
        }
    }

    public function __create($data_key, $data_info)
    {
        try {

            $url = $this->api_endpont . $this->external_links['create'] ?? $this->error_log('Link Seems Broken');
            $data = array(
                "key" => $data_key,
                "value" => $data_info,
            );
            $encodedData = json_encode($data);
            $curl = curl_init($url);
            #$data_string = urlencode(json_encode($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        } catch (\Throwable $th) {
            $this->error_log($th);
            return null;
        }
    }

    public function __read($data_key)
    {
        try {
            $url = $this->api_endpont . $this->external_links['read'] . '?key=' . $data_key;
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($curl);
            curl_close($curl);
            return $response;
        } catch (\Throwable $th) {
            $this->error_log($th);
            return null;
        }
    }

    public function __update($data_key, $data_info)
    {
        if ($this->__read($data_key) !== null) {

            try {
                $url = $this->api_endpont . $this->external_links['update'] ?? $this->error_log('Link Seems Broken');
                $data = array(
                    "key" => $data_key,
                    "value" => $data_info,
                );
                $encodedData = json_encode($data);
                $curl = curl_init($url);
                #$data_string = urlencode(json_encode($data));
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                curl_setopt($curl, CURLOPT_POST, true);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
                $result = curl_exec($curl);
                curl_close($curl);
                return $result;
            } catch (\Throwable $th) {
                $this->error_log($th);
                return null;
            }
        }
    }

    public function __delete($data_key)
    {
        try {
            $url = $this->api_endpont . $this->external_links['delte'] ?? $this->error_log('Broken Link');
            $data = array(
                "key" => $data_key,
            );
            $encodedData = json_encode($data);
            $curl = curl_init($url);
            #$data_string = urlencode(json_encode($data));
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $encodedData);
            $result = curl_exec($curl);
            curl_close($curl);
            return $result;
        } catch (\Throwable $th) {
            $this->error_log($th);
            return null;
        }
    }
}

?>
