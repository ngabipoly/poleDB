<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PoleModel;
use App\Models\DistrictModel;
use App\Models\RegionModel;
use App\Models\PoleSizeModel;
helper('App\Helpers\CustomHelpers');

class PoleManagement extends Controller
{
    protected $poleModel;
    protected $districtModel;
    protected $regionModel;
    protected $poleSizeModel;

    public function __construct()
    {
        $this->poleModel = new PoleModel();
        $this->districtModel = new DistrictModel();
        $this->poleSizeModel = new PoleSizeModel();
    }
    public function index()
    {
        $data = ['sizes' => $this->poleSizeModel->findAll()];
        $data['poles'] = $this->poleModel
                                    ->join('tbl_polesize ps', 'ps.id = sizeId')
                                    ->join('tbldistrict d', 'd.id = district_id')
                                    ->join('region r', 'r.RegionId = d.region_id')
                                    ->findAll();
        $data['page'] = 'Pole Management';        
        $data['districts'] = $this->districtModel
                                    ->join('region r', 'region_id =RegionId')
                                    ->findAll(); 
        return view('forms/poleMgr', $data);
    }

    public function storePole()
    {
        $pole_id = $this->request->getPost('pole_id');
        if($pole_id){
            return $this->updatePole($pole_id);
        }
        return $this->addPole();
    }

    public function addPole()
    {
        try {
            writeLog('Adding new pole');
            $pole_code = $this->request->getPost('pole_code');
            $latitude = $this->request->getPost('pole_latitude');
            $longitude = $this->request->getPost('pole_longitude');
            $district_id = $this->request->getPost('district_id');
            $pole_size = $this->request->getPost('pole_size');
            $pole_condition = $this->request->getPost('pole_condition');
            $pole_code = "$pole_code-" . $this->getNextPoleNumber($district_id);

            writeLog("Received pole data: code: $pole_code, latitude: $latitude, longitude: $longitude, district_id: $district_id");

            $poleData = [
                'name'        => $pole_code,
                'latitude'    => $latitude,
                'longitude'   => $longitude,
                'district_id' => $district_id,
                'PoleCode'    => $pole_code,
                'sizeId'   => $pole_size,
                'pole_condition' => $pole_condition
            ];

            $model = new PoleModel();

            if (!$model->insert($poleData)) {
                writeLog("<h6>Failed to add pole: $pole_code </h6>" . implode("/r/n ", $model->errors()));
                throw new \Exception("<h6>Failed to add pole: $pole_code </h6>". implode("<br>", $model->errors()));
            }

            writeLog("Pole $pole_code was added successfully");

            return jEncodeResponse(
                $poleData,
                "<h6>Pole Successfully added</h6>Pole added successfully with code: $pole_code",
                'success',
                200,
                true,
                'poles'
            );
        } catch (\Exception $e) {
            writeLog('Error adding pole: ' . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }


    public function updatePole($pole_id)
    {
        try {
            writeLog("Updating pole with ID: $pole_id");
            if (empty($pole_id)) {
                return jEncodeResponse([], 'No Pole ID provided', 'error', 400, false);
            }

            $model = new PoleModel();
            $pole = $model->find($pole_id);

            if (!$pole) {
                writeLog("Pole not found with ID: $pole_id");
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pole not found');
            }

            $pole_code = $pole['code'];
            writeLog("Found pole: $pole_id - $pole_code");

            // Handle POST (form submission)
            $updateData = [
                'name'        => $this->request->getPost('name'),
                'latitude'    => $this->request->getPost('latitude'),
                'longitude'   => $this->request->getPost('longitude'),
                'district_id' => $this->request->getPost('district_id'),
            ];

            if (!$model->update($pole_id, $updateData)) {
                writeLog("Failed to update pole: $pole_id - $pole_code");
                throw new \Exception("Failed to update pole: $pole_id - $pole_code " . implode(', ', $model->errors()));
            }

            writeLog("Pole $pole_id - $pole_code updated successfully");

            return jEncodeResponse(
                $updateData,
                "Pole $pole_id - $pole_code updated successfully",
                'success',
                200,
                true,
                'public/pole-management'
            );
        } catch (\Exception $e) {
            writeLog("Error updating pole: $pole_id - $pole_code " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }


    public function deletePole()
    {
        try {
            writeLog("Initializing pole deletion process....");
            $id = $this->request->getPost('id');
            $pole_code = $this->request->getPost('code');
            writeLog("Received pole ID: $id, code: $pole_code");
            
            if (empty($id)) {
                writeLog("No Pole ID provided for deletion");
                throw new \Exception("No Pole ID provided for deletion");
            }

            $model = new PoleModel();
            $pole = $model->find($id);

            if (!$pole) {
                writeLog("Pole not found with ID: $id");
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pole not found');
            }

            $pole_code = $pole['code'];
            writeLog("Found pole: $id - $pole_code");

            if (!$model->delete($id)) {
                writeLog("Failed to delete pole: $id - $pole_code");
                throw new \Exception("Failed to delete pole: $id - $pole_code " . implode(', ', $model->errors()));
            }

            writeLog("Pole $id - $pole_code deleted successfully");

            return jEncodeResponse(
                [],
                "Pole $id - $pole_code deleted successfully",
                'success',
                200,
                true,
                'public/pole-management'
            );
        } catch (\Exception $e) {
            writeLog("Error deleting pole: $id " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function getNextPoleNumber($district_id)
    {
        $model = new PoleModel();
        $totalPoles = $model->where('district_id', $district_id)->countAllResults();
        $nextNumber = $totalPoles + 1;
        return str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

}
