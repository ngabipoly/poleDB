<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\InfraElementModel;
use App\Models\DistrictModel;
use App\Models\RegionModel;
use App\Models\PoleTypeModel;
use App\Models\PoleSizeModel;
use App\Models\CarryTypeModel;
use App\Models\CarryCapacityModel;
use App\Models\PoleCarryingModel;
use App\Models\InfraCarryModel;

class InfraManagement extends Controller
{
    protected $infraModel;
    protected $districtModel;
    protected $regionModel;
    protected $poleSizes;
    protected $session;
    protected $user;
    protected $poleTypes;
    protected $carryCategories;
    protected $carryTypes;
    protected $poleCarrying;
    protected $carryCategoryModel;
    protected $carryTypeModel;
    protected $capacityModel;
    protected $poleCarryingModel;
    protected $carryModel;



    public function __construct()
    {
        $this->infraModel = new InfraElementModel();
        $this->districtModel = new DistrictModel();
        $this->regionModel = new RegionModel();
        $this->poleSizes = new PoleSizeModel();
        $this->poleTypes = new PoleTypeModel();
        $this->carryTypeModel = new CarryTypeModel();
        $this->capacityModel = new CarryCapacityModel();
        $this->poleCarryingModel = new PoleCarryingModel();
        $this->carryModel = new InfraCarryModel();
        $this->session = session();
        $this->user = $this->session->get('userData');
    }

    public function index()
    {
        $data['user'] = $this->user;
        $data['poles'] = $this->infraModel
                                ->join('tbl_pole_types pt', 'poleType = pt.TypeId', 'left')
                                ->join('tbl_polesize ps', 'poleSizeId = poleSize' , 'left')
                                ->join('tbldistrict d', 'district = d.districtId' , 'left')
                                ->join('region r', 'r.RegionId = region_id' , 'left')
                                ->join('tb_users user', 'user.user_pf = elmAddedBy' , 'left')
                                ->where('isElmDeleted', 0)->where('elmType','Pole')->findAll();
        $data['manholes'] = $this->infraModel->where('isElmDeleted', 0)
                                ->join('tbldistrict d', 'district = d.districtId' , 'left')
                                ->join('region r', 'r.RegionId = region_id', 'left')
                                ->join('tb_users user', 'user.user_pf = elmAddedBy' , 'left')

                                ->where('elmType','Manhole')->findAll();
        $data['districts'] = $this->districtModel
                                   ->join('region r', 'region_id = r.RegionId')
                                   ->findAll();
        $data['pole_types'] = $this->poleTypes->findAll();
        $data['media_types'] = $this->carryTypeModel->findAll();
        $data['media_capacity'] = $this->capacityModel->findAll();
        $data['sizes'] = $this->poleSizes->findAll();
        $data['page'] = 'Infrastructure Management';
        return view('forms/infraMgr', $data);
    }

    public function storeElement()
    {
        $elementType = $this->request->getPost('elmType');
        $pefix = $this->request->getPost('infra_code');
        $id = $this->request->getPost('elmId');
        return $id ? $this->updateElement($elementType, $id) : $this->addElement($elementType, $pefix);
    }

    public function addElement(string $elementType, string $prefix)
    {
        try {
            writeLog("Adding new $elementType");
            $typeKey = null;
            switch ($elementType) {
                case 'Pole':
                    $typeKey = POLE_KEY;
                    break;
                case 'Manhole':
                    $typeKey = MANHOLE_KEY;
                    break; 
                case 'Building':
                    $typeKey = BUILDING_KEY;
                    break;
                case 'OLTE':
                    $typeKey = OLTE_KEY;
                    break;
            }

            $elmCode = $prefix . '-'.$typeKey. $this->getNextCode($this->request->getPost('districtId'), $elementType);
            
            writeLog("New $elementType code: $elmCode...Generated Successfuly.");
            $data = $this->collectInputData("elementEntries", $elmCode);

            writeLog("Adding new $elementType: " . json_encode($data));

            if (!$this->infraModel->insert($data)) {
                writeLog("Error adding new $elementType: " . implode(', ', $this->infraModel->errors()));
                throw new \Exception(implode('<br>', $this->infraModel->errors()));
            }

            writeLog("$elementType $elmCode added successfully");
            return jEncodeResponse($data, "$elementType $elmCode added successfully", 'success', 200, true);
        } catch (\Exception $e) {
            writeLog("Error adding new $elementType: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function updateElement(string $elementType, $id)
    {
        try {
            $element = $this->infraModel->find($id);
            if (!$element) throw new \Exception("$elementType not found.");

            $elmCode = $element['elmCode'];
            $data = $this->collectInputData($elmCode);

            if (!$this->infraModel->update($id, $data)) {
                throw new \Exception(implode('<br>', $this->infraModel->errors()));
            }

            return jEncodeResponse($data, "$elementType $elmCode updated successfully", 'success', 200, true);
        } catch (\Exception $e) {
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function deleteElement()
    {
        try {
            $id = $this->request->getPost('del_elm_id');
            $elementType = $this->request->getPost('del_elm_type');
            $element = $this->infraModel->find($id);

            if (!$element) throw new \Exception("$elementType not found.");
            if (!$this->infraModel->update($id, ['deletedBy' => $this->user['user_pf']])) {
                throw new \Exception("Failed to delete $elementType.");
            }

            if (!$this->infraModel->delete($id)) {
                throw new \Exception(implode('<br>', $this->infraModel->errors()));
            }

            return jEncodeResponse([], "$elementType deleted successfully", 'success', 200, true);
        } catch (\Exception $e) {
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function carryTypes(){
        try {
            $data['title'] = 'Carry Types';
            $data['carryTypes'] = $this->carryTypeModel->findAll();
            return view('forms/carry-types', $data);
        } catch (\Exception $e) {
            writeLog("Error fetching carry types: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function saveCarryType(){
        try{
            $carryTypeId = $this->request->getPost('carryTypeId');
            if ($carryTypeId) {
                return $this->editCarryType($carryTypeId);
                }
            return $this->addCarryType();
        } catch (\Exception $e) {
            writeLog("Error saving carry type: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function addCarryType(){
        try{
            $data = $this->collectInputData('carryType');
            if (!$this->carryTypeModel->insert($data)) {
                throw new \Exception(implode('<br>', $this->carryTypeModel->errors()));
            }
            return jEncodeResponse($data, 'Carry Type added successfully', 'success', 200, true);
        } catch (\Exception $e) {
            writeLog("Error adding carry type: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function editCarryType($carryTypeId){
        try{
            $data = $this->collectInputData('carryType');
            if (!$this->carryTypeModel->update($carryTypeId, $data)) {
                throw new \Exception(implode('<br>', $this->carryTypeModel->errors()));
            }
            return jEncodeResponse($data, 'Carry Type updated successfully', 'success', 200, true);
        } catch (\Exception $e) {
            writeLog("Error updating carry type: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function collectTypeData($formType){
        $data = [];
        $data['carryTypeId'] = $this->request->getPost('carryTypeId');
        $data['carryTypeName'] = $this->request->getPost('carryTypeName');
        $data['carryTypeDesc'] = $this->request->getPost('carryTypeDesc');
        $data['carryTypeStatus'] = $this->request->getPost('carryTypeStatus');
        return $data;
        
    }
    

    
    protected function collectInputData($formType, $elmCode=null)
    {
        $data = [];
        if ($formType == 'cableCapacity') {
            $data['cableCapacity'] = $this->request->getPost('cableCapacity');
            $data['cableCapacityDesc'] = $this->request->getPost('cableCapacityDesc');
            $data['cableCapacityId'] = $this->request->getPost('cableCapacityId');
            $data['cableCapacityStatus'] = $this->request->getPost('cableCapacityStatus');
            return $data;
        }

        if ($formType == 'carryType') {
            $data = $this->collectTypeData($formType);
            $data['carryTypeCategory'] = $this->request->getPost('carryTypeCategory');
            $data['carryTypeCapacity'] = $this->request->getPost('carryTypeCapacity');
            $data['typeCreatedBy'] = $this->user['pfNumber'];
            return $data;
        }

        if ($formType == 'carryingCables') {
            $data['carryPole'] = $this->request->getPost('carryPole');
            $data['carryingType'] = $this->request->getPost('carryingType');
            $data['cableCapacity'] = $this->request->getPost('cableCapacity');  
            $data['sourceType'] = $this->request->getPost('sourceType');
            $data['carrySource'] = $this->request->getPost('carrySource');
            $data['carryNotes'] = $this->request->getPost('carryNotes');
            $data['carryAddBy'] = $this->user['pfNumber'];
            return $data;
        }

        if($formType=='elementEntries'){
            $type = $this->request->getPost('elmType');
            writeLog("User: " . json_encode($this->user));

            $data = [
                'elmCode'     => $elmCode,
                'elmType'     => $type,
                'elmCondition'=> $this->request->getPost('elmCondition'),
                'district'  => $this->request->getPost('districtId'),
                'latitude'    => $this->request->getPost('elmLatitude'),
                'longitude'   => $this->request->getPost('elmLongitude'),
                'notes'       => $this->request->getPost('notes'),
                'elmAddedBy'   => $this->user['pfNumber'],
            ];

            // Add conditional fields
            if ($type === 'Pole') {
                $data['poleTypeId'] = $this->request->getPost('poleTypeId');
                $data['poleSizeId'] = $this->request->getPost('poleSizeId');
            } elseif ($type === 'Manhole') {
                $data['manholeWidth']  = $this->request->getPost('manholeWidth');
                $data['manholeDepth']  = $this->request->getPost('manholeDepth');
                $data['manholeLength'] = $this->request->getPost('manholeLength');
                $data['manholeDiameter'] = $this->request->getPost('manholeDiameter');
            } elseif ($type === 'Building') {
                $data['buildingName']     = $this->request->getPost('buildingName');
                $data['buildingStreet']   = $this->request->getPost('buildingStreet');
                $data['landlordName']     = $this->request->getPost('landlordName');
                $data['landlordPhone']    = $this->request->getPost('landlordPhone');
                $data['landlordEmail']    = $this->request->getPost('landlordEmail');
            } elseif ($type === 'OLTE') {
                $data['olteTypeId'] = $this->request->getPost('olteTypeId');
            }

            return $data;
        }

        if ($formType == 'linkMedia') {
            writeLog("Getting media link data");
            $data['carryId'] = $this->request->getPost('carryId');
            $data['carryElement'] = $this->request->getPost('media-destination-element');
            $data['carryingType'] = $this->request->getPost('media_type');
            $data['carryCapacity'] = $this->request->getPost('media_capacity');
            $data['sourceType'] = $this->request->getPost('media_source_type');
            $data['carrySource'] = $this->request->getPost('source_element');
            $data['carryNotes'] = $this->request->getPost('media_notes');
            $data['carryAddBy'] = $this->user['pfNumber'];
            writeLog("Media link data collected: " . json_encode($data));
            return $data;
        }
    }


    public function deleteCarryType(){
        $carryTypeId = $this->request->getPost('carryTypeId');
        $carryTypeName = $this->request->getPost('carryTypeName');

        if (empty($carryTypeId)) {
            writeLog("No Carry Type ID provided for deletion");
            throw new \Exception("No Carry Type ID provided for deletion");
        }

        //check whether id exists
        $carryType = $this->carryTypeModel->find($carryTypeId);

        if (!$carryType) {
            writeLog("Carry Type not found with ID: $carryTypeId");
            throw new \Exception('Carry Type not found');
        }

        if (!$this->carryTypeModel->delete($carryTypeId)) {
            writeLog("Failed to delete carry type: $carryTypeId - $carryTypeName");
            throw new \Exception("Failed to delete carry type: $carryTypeId - $carryTypeName " . implode('<br/> ', $this->carryTypeModel->errors()));
        }

        writeLog("Carry Type $carryTypeId - $carryTypeName deleted successfully");
        return jEncodeResponse(
            [],
            "Carry Type $carryTypeId - $carryTypeName deleted successfully",
            'success',
            200,
            true,
            'carryTypes'
        );
    }

    public function cableCapacity(){
        try {
            $data['title'] = 'Cable Capacity';
            $data['cableCapacity'] = $this->capacityModel->findAll();
            return view('forms/cable-capacity', $data);
        } catch (\Exception $e) {
            writeLog("Error fetching cable capacity: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function saveCableCapacity(){
        try{
            $cableCapacityId = $this->request->getPost('cableCapacityId');
            if ($cableCapacityId) {
                return $this->editCableCapacity($cableCapacityId);
            }
            return $this->addCableCapacity();
        } catch (\Exception $e) {
            writeLog("Error saving cable capacity: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function addCableCapacity(){
        try{
            $data = $this->collectInputData('cableCapacity');
            if (!$this->capacityModel->insert($data)) {
                throw new \Exception(implode('<br>', $this->capacityModel->errors()));
            }
            return jEncodeResponse($data, 'Cable Capacity added successfully', 'success', 200, true);        
        } catch (\Exception $e) {
            writeLog("Error adding cable capacity: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function editCableCapacity($cableCapacityId){
        try{
            $data = $this->collectInputData('cableCapacity');
            if (!$this->capacityModel->update($cableCapacityId, $data)) {
                throw new \Exception(implode('<br>', $this->capacityModel->errors()));
            }
            return jEncodeResponse($data, 'Cable Capacity updated successfully', 'success', 200, true);
        } catch (\Exception $e) {
            writeLog("Error updating cable capacity: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function deleteCableCapacity(){
        $cableCapacityId = $this->request->getPost('cableCapacityId');
        $cableCapacity = $this->capacityModel->find($cableCapacityId);
        if (!$cableCapacity) {
            writeLog("Cable Capacity not found with ID: $cableCapacityId");
            throw new \Exception('Cable Capacity not found');
        }
        if (!$this->capacityModel->delete($cableCapacityId)) {
            writeLog("Failed to delete cable capacity: $cableCapacityId - {$cableCapacity['cableCapacityDesc']}");
            throw new \Exception("Failed to delete cable capacity: $cableCapacityId - {$cableCapacity['cableCapacityDesc']} " . implode('<br/> ', $this->capacityModel->errors()));
        }
        writeLog("Cable Capacity $cableCapacityId - {$cableCapacity['cableCapacityDesc']} deleted successfully");
        return jEncodeResponse(
            [],
            "Cable Capacity $cableCapacityId - {$cableCapacity['cableCapacityDesc']} deleted successfully",
            'success',
            200,
            true,
            'cableCapacity'
        );
    }

    
    public function carryingCables(){
        $elmentId = $this->request->getPost('poleId');
        $element = $this->infraModel->find($elmentId);
        if (!$element) {
            writeLog("Pole not found with ID: $element");
            throw new \Exception('Pole not found');
        }
        try{
            $data = $this->collectInputData('carryingCables');
            if (!$this->infraModel->update($element, $data)) {
                throw new \Exception(implode('<br>', $this->infraModel->errors()));
            }
            writeLog("Cables the pole $element is carrying updated successfully");
            return jEncodeResponse(
                [],
                "Cables the pole $element is carrying updated successfully",
                'success',
                200,
                true,
                'carryingCables'
            );
        } catch (\Exception $e) {
            writeLog("Error updating cables the pole $element is carrying: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }  

    public function linkMediaToElement()
    {
        try {
            $formType = $this->request->getPost('formType');
            $data = $this->collectInputData($formType);
            writeLog('Form Type: ' . $formType . ' Data: ' . json_encode($data));

            $carryId = (int)$data['carryId'];

            if($carryId > 0) {
                if(!$this->carryModel->update($carryId, $data)) {
                    writeLog("Failed to update media linked to element with ID: $carryId". implode('<br>', $this->carryModel->errors()));
                    throw new \Exception(implode('<br>', $this->carryModel->errors()));
                }
                writeLog("Media linked to element updated successfully with ID: $carryId");
                return jEncodeResponse($data, 'Media linked to element updated successfully', 'success', 200, true);
            } 

            if(!$this->carryModel->insert($data)) {
                writeLog("Failed to link media to element". implode('<br>', $this->carryModel->errors()));
                throw new \Exception(implode('<br>', $this->carryModel->errors()));
            }  

            return jEncodeResponse($data, 'Media linked to element successfully', 'success', 200, true, 'infrastructure');

        } catch (\Exception $e) {
            writeLog("Error linking media to element: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }
    /**
     * Get capacity information for the provided carry/media type.
     * This method retrieves the cable capacity based on the carry type.
     * 
     * @param int $carryTypeId The ID of the carry type.
     * @return array The cable capacity information.
     */
    public function getCableCapacityByCarryType()
    {
        try {
            $carryTypeId = $this->request->getPost('carryTypeId');
            if (empty($carryTypeId)) {
            writeLog("No Carry Type ID provided for cable capacity retrieval");
            throw new \Exception("No Carry Type ID provided for cable capacity retrieval");
            }

            // Check if carry type exists
            $carryType = $this->carryTypeModel->find($carryTypeId);
            if (!$carryType) {
            writeLog("Carry Type not found with ID: $carryTypeId");
            throw new \Exception('Carry Type not found');
            }

            // Retrieve cable capacity based on carry type
            $capacities = $this->capacityModel->where('carryType', $carryTypeId)->findAll();
            if (empty($capacities)) {
                writeLog("No cable capacities found for Carry Type ID: $carryTypeId");
                throw new \Exception('No cable capacities found for the specified Carry Type');
            }
            writeLog("Cable capacities retrieved successfully for Carry Type ID: $carryTypeId".json_encode($capacities));
            return jEncodeResponse(
                $capacities,
                'Cable capacities retrieved successfully',
                'success',
                200,
                true
            );
        } catch (\Exception $e) {
            writeLog("Error retrieving cable capacity: " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    /**
     * Get a list of source element candidates of the specified Element type.
     * 
     * This method retrieves a list of source elements of the specified type
     * that are not deleted and are not the destination element.
     * 
     * @return array The list of source element candidates.
     */
    public function getSourceElementCandidates()
    {
        try {
            writeLog('Retrieving source element candidates...');
            $candidates = null;
            $elementType = $this->request->getPost('elmType');
            $destElementId = $this->request->getPost('destElmId');
            writeLog("Retrieving source element candidates of type: $elementType for destination element ID: $destElementId");

            if (empty($elementType) || empty($destElementId)) {
                writeLog('Source element type and destination element ID are required.');
                throw new \Exception('Source element type and destination element ID are required.');
            }

            $candidates = $this->infraModel
                ->where('elmType', $elementType)
                ->where('elmId !=', $destElementId)
                ->where('isElmDeleted', 0)
                ->findAll();

            if ($this->infraModel->db->error()['code'] !== 0) {
                $err = $this->infraModel->db->error();
                writeLog('DB error: ' . json_encode($err));
                throw new \Exception('Database error fetching candidates.');
            }

            if (empty($candidates)) {
                writeLog('No source element candidates found.');
                throw new \Exception('No source element candidates found.');
            }

            return jEncodeResponse($candidates, 'Source elements retrieved successfully', 'success', 200, true);
        } catch (\Exception $e) {
            writeLog('Error retrieving source element candidates: ' . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    /**
     * Get the next code for a given district and element type.
     * This method generates a sequential code based on the number of existing elements.
     *
     * @param int $districtId The ID of the district.
     * @param string $elementType The type of the element (e.g., 'Pole', 'Manhole').
     * @return string The next code formatted as a 6-digit string.
     */

    public function getNextCode($districtId, $elementType)
    {
        $total = $this->infraModel
            ->withDeleted()
            ->where('district', $districtId)
            ->where('elmType',$elementType)
            ->countAllResults();
        return str_pad($total + 1, 6, '0', STR_PAD_LEFT);
    }

    public function mapData()
    {
        try {
            $data = $this->infraModel->where('deleted', 0)->findAll();
            return $this->response->setJSON($data);
        } catch (\Exception $e) {
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }
}
