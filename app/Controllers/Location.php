<?php
namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;

class Location extends BaseController
{
    public function reverseGeocode()
    {
        $lat = $this->request->getGet('lat');
        $lon = $this->request->getGet('lon');

        if (!$lat || !$lon) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Missing coordinates']);
        }

        $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=$lat&lon=$lon&zoom=10&addressdetails=1";

        $client = \Config\Services::curlrequest();
        try {
            $response = $client->get($url, [
                'headers' => [
                    'User-Agent' => 'Poledb/1.0 (+https://poledb)' // Required by Nominatim usage policy
                ]
            ]);
            return $this->response->setJSON(json_decode($response->getBody(), true));
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON(['error' => "Request failed with error: " . $e->getMessage()]);
        }
    }
}
