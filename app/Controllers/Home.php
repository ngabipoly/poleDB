<?php

namespace App\Controllers;
use App\Models\PoleModel;
use App\Models\RegionModel;

class Home extends BaseController
{
    protected $user;
    protected $session;
    protected $poleModel;
    protected $regionModel;

    public function __construct()
    {
        // Initialize session
        $this->session = session();
        $this->user = $this->session->get('userData');
        $this->poleModel = new PoleModel();
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
        $data = $this->regionModel->getRegionsWithPolesCount();
        return $this->response->setJSON($data);
    }

    // Return poles per size for pie chart
    public function polesPerSize()
    {
        $data = $this->poleModel->getPolesGroupedBy('SizeLabel');
        return $this->response->setJSON($data);
    }

    // Return poles by status for doughnut chart
    public function polesByStatus()
    {
        $data = $this->poleModel->getPolesGroupedBy('pole_condition');
        return $this->response->setJSON($data);
    }

    // Return dashboard summary stats (info boxes)
    public function summaryStats()
    {
        $data = [
            'totalPoles' => $this->poleModel->where('pole_condition !=', 'stolen')->countAllResults(), // Exclude soft-deleted poles countAllResults(),
            'polesPerDistrict' => $this->poleModel->getPolesGroupedBy('districtName'),
            'PolesPerRegion' => $this->regionModel->getRegionsWithPolesCount(),
            'PolesByCondition' => $this->poleModel->getPolesGroupedBy('pole_condition'),
            'damagedPoles' => $this->poleModel->where('pole_condition', 'Damaged')->countAllResults(),
            'goodPoles' => $this->poleModel->where('pole_condition', 'Good')->countAllResults(),
            'replantedPoles' => $this->poleModel->where('pole_condition', 're-used')->countAllResults(),
            'polesPerSize' => $this->poleModel->getPolesGroupedBy('SizeLabel'),
            'monthlyAddition' => $this->poleModel->where('created_at >=', date('Y-m-01'))->countAllResults()
        ];

        $conditionData = $this->regionModel->getPoleConditionsPerRegion();

        // Format for Chart.js
        $regionSet = [];
        $conditionSet = ['Good', 'Damaged', 'Stolen', 'Re-used']; // Fixed order
        $chartData = [];

        foreach ($conditionSet as $condition) {
            $chartData[$condition] = [];
        }

        foreach ($conditionData as $row) {
            $region = $row->RegionName ?? 'Unknown';
            $cond = $row->pole_condition ?? 'Unknown';
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
