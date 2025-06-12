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

            if (!$this->poleModel->insert($poleData)) {
                writeLog("<h6>Failed to add pole: $pole_code </h6>" . implode("/r/n ", $this->poleModel->errors()));
                throw new \Exception("<h6>Failed to add pole: $pole_code </h6>". implode("<br>", $this->poleModel->errors()));
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

            $pole = $this->poleModel->find($pole_id);

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

            if (!$this->poleModel->update($pole_id, $updateData)) {
                writeLog("Failed to update pole: $pole_id - $pole_code");
                throw new \Exception("Failed to update pole: $pole_id - $pole_code " . implode('<br/> ', $this->poleModel->errors()));
            }

            writeLog("Pole $pole_id - $pole_code updated successfully");

            return jEncodeResponse(
                $updateData,
                "Pole $pole_id - $pole_code updated successfully",
                'success',
                200,
                true,
                'poles'
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
;
            $pole = $this->poleModel->find($id);

            if (!$pole) {
                writeLog("Pole not found with ID: $id");
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Pole not found');
            }

            $pole_code = $pole['code'];
            writeLog("Found pole: $id - $pole_code");

            if (!$this->poleModel->delete($id)) {
                writeLog("Failed to delete pole: $id - $pole_code");
                throw new \Exception("Failed to delete pole: $id - $pole_code " . implode('<br/> ', $this->poleModel->errors()));
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
        $totalPoles = $this->poleModel->where('district_id', $district_id)->countAllResults();
        $nextNumber = $totalPoles + 1;
        return str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
    }

    public function poleTypes(){
        try {
            $data['title'] = 'Pole Types';
            $data['page'] = 'Pole Sizes';
            $data['poleSizes'] = $this->poleSizeModel->findAll();
            return view('forms/pole-types', $data); ;
        } catch (\Exception $e) {
            writeLog("Error fetching pole types: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function savePoleType(){
        try{
            $poleId = $this->request->getPost('poleSizeId');
            if ($poleId) {
                return $this->editPoleType($poleId);
            }
            return $this->addPoleType();
        } catch (\Exception $e) {
            writeLog("Error saving pole type: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }
    public function addPoleType()
    {
        try {
            // Sanitize and validate input
            $poleType = trim(strip_tags($this->request->getPost('poleType')));
            $sizeMtrs = $this->request->getPost('sizeMeteres');

            if (empty($poleType) || !is_string($poleType)) {
                throw new \Exception("Invalid pole type provided.");
            }

            if (!is_numeric($sizeMtrs) || $sizeMtrs <= 0) {
                throw new \Exception("Invalid size (meters) provided.");
            }
            $sizeMtrs = floatval($sizeMtrs);
            $data = [
                'SizeLabel' => $poleType,
                'SizeMtrs' => $sizeMtrs
            ];

            if (!$this->poleSizeModel->insert($data)) {
                writeLog("Failed to save pole type: " . implode(', ', $this->poleSizeModel->errors()));
                throw new \Exception("Failed to save pole type: " . implode('<br/> ', $this->poleSizeModel->errors()));
            }

            writeLog("Pole type saved successfully: $poleType");
            return jEncodeResponse(
                $data,
                "Pole type $poleType saved successfully",
                'success',
                200,
                true,
                base_url('pole-management/pole-types')
            );
        } catch (\Exception $e) {
            writeLog("Error saving pole type: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function editPoleType($poleId)
    {
        try {
            $id = $poleId;
            $poleType = trim(strip_tags($this->request->getPost('poleType')));
            $sizeMtrs = $this->request->getPost('sizeMeteres');

            if (empty($id) || !is_numeric($id)) {
                throw new \Exception("Invalid pole size ID provided.");
            }

            if (empty($poleType) || !is_string($poleType)) {
                throw new \Exception("Invalid pole type provided.");
            }

            if (!is_numeric($sizeMtrs) || $sizeMtrs <= 0) {
                throw new \Exception("Invalid size (meters) provided.");
            }
            $sizeMtrs = floatval($sizeMtrs);

            $data = [
                'SizeLabel' => $poleType,
                'SizeMtrs' => $sizeMtrs
            ];

            if (!$this->poleSizeModel->update($id, $data)) {
                writeLog("Failed to update pole type $id - $poleType: " . implode(', ', $this->poleSizeModel->errors()));
                throw new \Exception("Failed to update pole type: $poleType " . implode('<br/> ', $this->poleSizeModel->errors()));
            }

            writeLog("Pole type $poleType updated successfully");
            return jEncodeResponse(
                $data,
                "Pole type $poleType updated successfully",
                'success',
                200,
                true,
                base_url('pole-management/pole-types') 
            );
        } catch (\Exception $e) {
            writeLog("Error updating pole type: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function deletePoleType()
    {
        try {
            $id = $this->request->getPost('delPoleSizeId');
            $poleType = $this->request->getPost('delPoleLabel');
            $sizeMtrs = $this->request->getPost('delSizeMeteres');
            writeLog("Received pole size ID: $id, type: $poleType of size: $sizeMtrs Meters");

            if (empty($id) || !is_numeric($id)) {
                throw new \Exception("Invalid pole size ID provided.");
            }

            // Check if the pole size is being used by any poles
            $polesUsingSize = $this->checkPolesForSize($id);

            if ($polesUsingSize) {
                $usingPoleCount = count($polesUsingSize);
                writeLog("Pole size ID: $id - $poleType, $sizeMtrs Meters is being used by $usingPoleCount poles. Cannot delete.");
                throw new \Exception("Pole size ID: $id - $poleType, $sizeMtrs Meters is being used by $usingPoleCount poles. Cannot delete.");
            }

            if (!$this->poleSizeModel->delete($id)) {
                writeLog("Failed to delete pole size ID: $id - $poleType, $sizeMtrs Meters: " . implode(', ', $this->poleSizeModel->errors()));
                throw new \Exception("Failed to delete pole size with ID: $id - $poleType, $sizeMtrs Meters " . implode('<br/> ', $this->poleSizeModel->errors()));
            }

            writeLog("Pole size ID: $id - $poleType, $sizeMtrs Meters has been deleted successfully");
            return jEncodeResponse(
                [],
                "Deleted Pole size ID: $id - $poleType, $sizeMtrs Meters",
                'success',
                200,
                true,
                base_url('pole-management/pole-types')
            );
        } catch (\Exception $e) {
            writeLog("Error deleting pole size: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    /**
     * Check if there are poles that use the given size ID.
     * This is useful for preventing accidental deletion of a pole size that is being used by at least one pole.
     *
     * @param int $sizeId The ID of the pole size to check for.
     * @return array An array of pole objects that use the given size ID.
     * @throws \Exception If the size ID is invalid.
     */
    public function checkPolesForSize($sizeId) {
        try {
            if (empty($sizeId) || !is_numeric($sizeId)) {
                throw new \Exception("Invalid pole size ID provided.");
            }
            $poles = $this->poleModel->where('sizeId', $sizeId)->findAll();
            return $poles;
        } catch (\Exception $e) {
            writeLog("Error checking poles for size: " . $e->getMessage());
            return false;
        }
    }

}
