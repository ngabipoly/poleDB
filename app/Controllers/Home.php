<?php

namespace App\Controllers;
use App\Models\InfraElementModel;
use App\Models\RegionModel;

class Home extends BaseController
{
    protected $user;
    protected $session;
    protected $InfraElementModel;
    protected $regionModel;

    public function __construct()
    {
        // Initialize session
        $this->session = session();
        $this->user = $this->session->get('userData');
        $this->InfraElementModel = new InfraElementModel();
        $this->regionModel = new RegionModel();

        if (!$this->user) {
            // Redirect to login page if not logged in
            return redirect()->to('/');
        }
    }

    public function index()
    {
         if (!$this->user) {
            // Redirect to login page if not logged in
            return redirect()->to('/');
        }       
        $data = [
            'page' => 'Home',
            'user' => $this->user,
        ];

        return view('front-end', $data);
    }

    public function dashboard()
    {
        $data = [
            'page' => 'Dashboard',
            'user' => $this->user,
        ];

        return view('dashboards/dashboard', $data);
    }
    
    // Return poles per region for bar chart
    public function polesPerRegion()
    {
        $data = $this->regionModel->getInfraCount('Pole');
        return $this->response->setJSON($data);
    }

    // Return poles per size for pie chart
    public function polesPerSize()
    {
        $data = $this->InfraElementModel->getElementsGroupedBy('SizeLabel', 0, ['elmType'=>'Pole']);
        return $this->response->setJSON($data);
    }

    // Return poles by status for doughnut chart
    public function polesByStatus()
    {
        $data = $this->InfraElementModel->getElementsGroupedBy('elmCondition', 0, ['elmType'=>'Pole']);
        return $this->response->setJSON($data);
    }


    // Return dashboard summary stats (info boxes)
    public function summaryStats()
    {
        $data = [
            'totalPoles' => (clone $this->InfraElementModel)->where('elmCondition !=', 'stolen')->where('elmType', 'Pole')->countAllResults(), // Exclude soft-deleted poles countAllResults(),
            'polesPerDistrict' => (clone $this->InfraElementModel)->getElementsGroupedBy('districtName',0,['elmType'=>'Pole']),
            'PolesPerRegion' => (clone $this->regionModel)->getInfraCount('Pole'),
            'PolesByCondition' => (clone $this->InfraElementModel)->getElementsGroupedBy('elmCondition', 0, ['elmType'=>'Pole']),
            'damagedPoles' => (clone $this->InfraElementModel)->where('elmCondition', 'Damaged')->where('elmType','Pole')->countAllResults(),
            'goodPoles' => (clone $this->InfraElementModel)->where('elmCondition', 'Good')->where('elmType','Pole')->countAllResults(),
            'replantedPoles' => (clone $this->InfraElementModel)->where('elmCondition', 're-used')->where('elmType','Pole')->countAllResults(),
            'polesPerSize' => (clone  $this->InfraElementModel)->getElementsGroupedBy('SizeLabel',0,['elmType'=>'Pole']),
            'monthlyAddition' => (clone $this->InfraElementModel)->where('elmCreatedAt >=', date('Y-m-01'))->countAllResults()
        ];

        $conditionData = $this->regionModel->getInfraConditionCount('Pole');

        // Format for Chart.js
        $regionSet = [];
        $conditionSet = ['Good', 'Damaged', 'Stolen', 'Re-used']; // Fixed order
        $chartData = [];

        foreach ($conditionSet as $condition) {
            $chartData[$condition] = [];
        }

        foreach ($conditionData as $row) {
            $region = $row->RegionName ?? 'Unknown';
            $cond = $row->elmCondition ?? 'Unknown';
            $count = isset($row->count) ? (int)$row->count : 0;

            $regionSet[$region] = true;

            foreach ($conditionSet as $c) {
                if (!isset($chartData[$c][$region])) {
                    $chartData[$c][$region] = 0;
                }
            }

            $chartData[$cond][$region] = $count;
        }

        $regionLabels = array_keys($regionSet);
        $datasets = [];

        foreach ($conditionSet as $cond) {
            $datasets[] = [
                'label' => $cond,
                'data' => array_map(fn($r) => $chartData[$cond][$r] ?? 0, $regionLabels),
                'backgroundColor' => match($cond) {
                    'Good' => '#28a745',
                    'Damaged' => '#ffc107',
                    'Stolen' => '#dc3545',
                    'Re-used' => '#007bff',
                    default => '#6c757d'
                }
            ];
        }

       $data['PolesByConditionPerRegion'] = [
            'regionNames' => $regionLabels,
            'stackedData' => $datasets,
       ];
        
        return $this->response->setJSON($data);
    }

}
