<?php
namespace App\Controllers;

use App\Models\RegionModel;
use App\Models\DistrictModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LocationsManager extends BaseController
{
    public function index()
    {
        $model = new RegionModel();
        $data['regions'] = $model->findAll();
        return view('locations/list_regions', $data);
    }

    public function addRegion()
    {
        $model = new RegionModel();
        if ($this->request->getMethod() == 'post') {
            $data = [
                'region_name' => $this->request->getPost('region_name'),
                'region_code' => $this->request->getPost('region_code')
            ];
            if ($model->insert($data)) {
                return redirect()->to('/locations')->with('success', 'Region added successfully');
            }
        }
        return view('locations/add_region');
    }

    public function editRegion($id = null)
    {
        $model = new RegionModel();
        if ($id) {
            $region = $model->find($id);
            if (!$region) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Region not found');
            }
            $data['region'] = $region;
            $data = [
                    'region_name' => $this->request->getPost('region_name'),
                    'region_code' => $this->request->getPost('region_code')
                ];
                if ($model->update($id, $data)) {
                    return redirect()->to('/locations')->with('success', 'Region updated successfully');
                }
            return view('locations/edit_region', $data);
        }
        return redirect()->to('/locations');
    }

    public function listDistricts($region_id = null)
    {
        $model = new DistrictModel();
        if ($region_id) {
            $data['districts'] = $model->where('region_id', $region_id)->findAll();
        } else {
            $data['districts'] = $model->findAll();
        }
        return view('locations/list_districts', $data);
    }

    public function addDistrict($region_id = null)
    {
        $model = new DistrictModel();
        if ($region_id) {
            $region = (new RegionModel())->find($region_id);
            if (!$region) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Region not found');
            }
            $data['region'] = $region;
            if ($this->request->getMethod() == 'post') {
                $data = [
                    'district_name' => $this->request->getPost('district_name'),
                    'district_code' => $this->request->getPost('district_code'),
                    'region_id' => $region_id
                ];
                if ($model->insert($data)) {
                    return redirect()->to("/locations/editRegion/$region_id")->with('success', 'District added successfully');
                }
            }
            return view('locations/add_district', $data);
        }
        return redirect()->to('/locations');
    }

    public function editDistrict($district_id = null)
    {
        $model = new DistrictModel();
        if ($district_id) {
            $district = $model->find($district_id);
            if (!$district) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('District not found');
            }
            $data['district'] = $district;
            if ($this->request->getMethod() == 'post') {
                $data = [
                    'district_name' => $this->request->getPost('district_name'),
                    'district_code' => $this->request->getPost('district_code')
                ];
                if ($model->update($district_id, $data)) {
                    return redirect()->to("/locations/editRegion/{$district->region_id}")->with('success', 'District updated successfully');
                }
            }
            return view('locations/edit_district', $data);
        }
        return redirect()->to('/locations');
    }

    public function deleteDistrict($district_id = null)
    {
        
        $model = new DistrictModel();
        if ($district_id) {
            if ($model->delete($district_id)) {
                return redirect()->to('/locations')->with('success', 'District deleted successfully');
            }
        }
        return redirect()->to('/locations');
    }
}