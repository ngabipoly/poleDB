<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\DistrictModel;
use APP\Models\RegionModel;

class DistrictManagement extends Controller
{

    public function index()
    {
        $model = new DistrictModel();
        $regionModel = new RegionModel();
        $data['page'] = 'District Management';
        $data['regions'] = 
        $data['districts'] = $model->join('region r', 'region_id =r.id')->findAll();
        return view('forms/districtMgr', $data);
    }

    public function addDistrict()
    {
        $model = new DistrictModel();
        if ($this->request->getMethod() == 'post') {
            $data = [
                'district_name' => $this->request->getPost('district_name'),
                'district_code' => $this->request->getPost('district_code'),
                'region_id' => $this->request->getPost('region_id')
            ];
            if ($model->insert($data)) {
                return redirect()->to('/districts')->with('success', 'District added successfully');
            }
        }
        $data['regions'] = (new \App\Models\RegionModel())->findAll();
        return view('districts/add_district', $data);
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
                    return redirect()->to("/districts")->with('success', 'District updated successfully');
                }
            }
            $data['regions'] = (new \App\Models\RegionModel())->findAll();
            return view('districts/edit_district', $data);
        }
        return redirect()->to('/districts');
    }

    public function deleteDistrict($district_id = null)
    {
        $model = new DistrictModel();
        if ($district_id) {
            if ($model->delete($district_id)) {
                return redirect()->to('/districts')->with('success', 'District deleted successfully');
            }
        }
        return redirect()->to('/districts');
    }
}
