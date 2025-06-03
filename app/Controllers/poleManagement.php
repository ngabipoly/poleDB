<?php namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PoleModel;

class PoleManagement extends Controller
{
    public function index()
    {
        $model = new PoleModel();
        $data['poles'] = $model->findAll();
        return view('pole-management/index', $data);
    }

    public function createPole()
    {
        return view('pole-management/create');
    }

    public function storePole()
    {
        $model = new PoleModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];
        if ($model->insert($data)) {
            return redirect()->to('pole-management')->with('success', 'Pole added successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error adding pole.');
        }
    }

    public function editPole($id)
    {
        $model = new PoleModel();
        $data['pole'] = $model->find($id);
        return view('pole-management/edit', $data);
    }

    public function updatePole($id)
    {
        $model = new PoleModel();
        $data = [
            'name' => $this->request->getPost('name'),
            'latitude' => $this->request->getPost('latitude'),
            'longitude' => $this->request->getPost('longitude'),
        ];
        if ($model->update($id, $data)) {
            return redirect()->to('pole-management')->with('success', 'Pole updated successfully.');
        } else {
            return redirect()->back()->withInput()->with('error', 'Error updating pole.');
        }
    }

    public function deletePole($id)
    {
        $model = new PoleModel();
        if ($model->delete($id)) {
            return redirect()->to('pole-management')->with('success', 'Pole deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Error deleting pole.');
        }
    }
}
