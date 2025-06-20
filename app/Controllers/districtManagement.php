<?php 
namespace App\Controllers;

use App\Models\DistrictModel;
use App\Models\RegionModel;
use App\Models\PoleModel;
helper('App\Helpers\CustomHelpers');

class DistrictManagement extends BaseController
{
    protected $districtModel;
    protected $regionModel;
    protected $poleModel;

    public function __construct()
    {
        $this->districtModel = new DistrictModel();
        $this->regionModel = new RegionModel();
        $this->poleModel = new PoleModel();
    }

    public function index()
    {
        $model = $this->districtModel;
        $data['page'] = 'District Management';
        $data['regions'] = $this->regionModel->findAll();
        $data['districts'] = $model->join('region r', 'region_id =RegionId')->findAll();
        return view('forms/districtMgr', $data);
    }

    function saveDetails(){
        $district_id = $this->request->getPost('district_id');
        if ($district_id) {
            return $this->editDistrict($district_id);
        } else {
            return $this->addDistrict();
        }
    }

    /**
     * Add a new district
     *
     * @return \CodeIgniter\HTTP\Response
     */

    public function addDistrict()
    {
        try {
            $model = $this->districtModel;
            $data = [
                        'districtName' => $this->request->getPost('district_name'),
                        'code' => $this->request->getPost('district_code'),
                        'region_id' => $this->request->getPost('region_id')
                    ];
            if (!$model->insert($data)) {
                throw new \Exception('Failed to add district: ' . implode(', ', $model->errors()));   
            } 
            return jEncodeResponse(
                $data, 
                'District added successfully',
                'success', 
                200,  
                true, 
                base_url('districts')
            );       
        } catch (\Exception $e) {
            // Log the error message
            writeLog('Error adding district: ' . $e->getMessage());
            return jEncodeResponse($data, $e->getMessage(),'error', 500,  false);
        }
            
    }

public function editDistrict($district_id)
{
    try {
        if (empty($district_id)) {
            return jEncodeResponse([], 'Invalid district ID', 'error', 400, false);
        }

        $district = $this->districtModel->find($district_id);
        if (!$district) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('District not found');
        }

        $district_name = $this->request->getPost('district_name');
        $district_code = $this->request->getPost('district_code');
        $district_region_id = $this->request->getPost('region_id');

        $updateData = [
            'districtName'      => $district_name,
            'code'      => $district_code,
            'region_id' => $district_region_id,
        ];

        if (!$this->districtModel->update($district_id, $updateData)) {
            throw new \Exception("Failed to update district: $district_id - $district_name region-id:  $district_region_id " . implode(', ', $this->districtModel->errors()));
        }

        return jEncodeResponse(
            $updateData,
            'District updated successfully',
            'success',
            200,
            true,
            base_url('districts')
        );
    } catch (\Exception $e) {
        writeLog('Error editing district: ' . $e->getMessage());
        return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
    }
}


    public function deleteDistrict()
    {
        try {
            $district_id = $this->request->getPost('delete_district_id');
            $district_name = $this->request->getPost('delete_district_name');
            writeLog("Deleting district: $district_id - $district_name");

            if (empty($district_id)) {
            return jEncodeResponse([], 'Invalid district ID', 'error', 400, false);
            }

            $district = $this->districtModel->find($district_id);
            if (!$district) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('District not found');
            }

            if (!$this->districtModel->delete($district_id)) {
            throw new \Exception("Failed to delete district: $district_id - $district_name" . implode(', ', $this->districtModel->errors()));
            }

            return jEncodeResponse(
            [],
            'District deleted successfully',
            'success',
            200,
            true,
            base_url('public/districts')
            );
        } catch (\Exception $e) {
            writeLog("Error deleting district: $district_id - $district_name " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }
}
