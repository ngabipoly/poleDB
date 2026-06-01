<?php
namespace App\Controllers;

use App\Models\LeasorModel;
helper('App\Helpers\CustomHelpers');

class LeasorManagement extends \CodeIgniter\Controller
{
    protected $leasorModel;

    public function __construct()
    {
        helper('form');
        $this->leasorModel = new LeasorModel();
    }
    public function index()
    {
        $data['title'] = 'Leasor Management';
        $data['page'] = 'Leasors';
        $data['leasors'] = $this->leasorModel->findAll();
        return view('forms'.DIRECTORY_SEPARATOR.'leasor-mgr', $data);
    }

    public function saveLeasorDetails()
    {
        $status = "Success";
        $exec_type = $this->request->getPost('_method');
        try{
            writeLog("Info: - Received request to $exec_type leasor details with data: " . json_encode($this->request->getPost()));
            $data = [
                'name' => $this->request->getPost('leasorName'),
                'contract_start_date' => $this->request->getPost('leaseStart'),
                'contract_end_date' => $this->request->getPost('leaseEnd'),
                'contact_person' => $this->request->getPost('contactPerson'),
                'contact_number' => $this->request->getPost('contactNumber'),
                'contact_email' => $this->request->getPost('contactEmail'),
            ];
            if ($exec_type==='EDIT'){
                //casting leasorId to int for update operation
                $leasor_id = (int) $this->request->getPost('leasorId');
                if (!$leasor_id){
                    throw new \Exception('Invalid leasor ID for update operation');
                }
                $data['id'] = $leasor_id;
                return $this->updateLeasor($leasor_id, $data);
            }

            return $this->addLeasor($data);            
        }catch(\Exception $e){
            $status = 'error';
            return jEncodeResponse(
                null, 
                $e->getMessage(),
                'error',
                400,
                false
            );
        }finally{
            writeLog("Info: - $exec_type Leasor operation Completed with status: $status and data: " . json_encode($this->request->getPost()));
        }

    }

    public function deactivateLeasor(){
            $leasor_id = (int) $this->request->getPost('deleteLeasorId'); 
            $status = null;
            try{
                writeLog('Info: - Attempting to deactivate leasor with ID: ' . $leasor_id);
                if (!$leasor_id){
                    $status = 'error';
                    throw new \Exception('Invalid leasor ID for deactivation');
                }
                return $this->deleteLeasor($leasor_id);
            }catch(\Exception $e){
                writeLog("Error deactivating leasor: " . $e->getMessage());
                return jEncodeResponse(
                    null, 
                    $e->getMessage(),
                    'error',
                    400,
                    false
                );
            }finally{
                writeLog("Info: - Completed deactivating Leasor with ID: $leasor_id with status: $status");
            }
    }

    public function addLeasor(array $data)
    {
        // This method can be used for any additional processing before adding a leasor
        $model = $this->leasorModel;
        try {
            writeLog('Info: - Attempting to add leasor with data: ' . json_encode($data));
            if (!$model->insert($data)) {
                throw new \Exception('Failed to add leasor: ' . implode(', ', $model->errors()));   
            } 
            return jEncodeResponse(
                $data, 
                'Leasor added successfully',
                'success',
                200,
                true,
                base_url('infrastructure/leasor-management')
            );
        } catch (\Exception $e) {
            writeLog("Error adding leasor: " . $e->getMessage());
            return jEncodeResponse(
                null, 
                $e->getMessage(),
                'error',
                400,
                false
            );
        }finally {
            writeLog('Info: - Completed Leasor addition operation for data: ' . json_encode($data));
        }
    }

    public function updateLeasor(int $id, array $data)
    {
        // This method can be used for any additional processing before updating a leasor
        $model = $this->leasorModel;
        $data['id'] = $id; // Include ID in data for validation rules
        try {
            writeLog('Info: - Attempting to update leasor with ID: ' . $id . ' and data: ' . json_encode($data));
            if (!$model->update($id, $data)) {
                throw new \Exception('Failed to update leasor: ' . implode(', ', $model->errors()));   
            } 
            return jEncodeResponse(
                $data, 
                'Leasor updated successfully',
                'success',
                200,
                true,
                base_url('infrastructure/leasor-management')
            );
        } catch (\Exception $e) {
            writeLog("Error updating leasor: " . $e->getMessage());
            return jEncodeResponse(
                null, 
                $e->getMessage(),
                'error',
                400,
                false
            );
        }finally{
            writeLog('Info: - Completed Leasor update operation for ID: ' . $id . ' and data: ' . json_encode($data));
        }
    }

    public function deleteLeasor(int $id)
    {
        // This method can be used for any additional processing before deleting a leasor
        $model = $this->leasorModel;
        try {
            if (!$model->delete($id)) {
                throw new \Exception('Failed to delete leasor: ' . implode(', ', $model->errors()));   
            } 
            return jEncodeResponse(
                null, 
                'Leasor deleted successfully',
                'success',
                200,
                true,
                base_url('infrastructure/leasor-management')
            );
        } catch (\Exception $e) {
            return jEncodeResponse(
                null, 
                $e->getMessage(),
                'error',
                400,
                false
            );
        }finally{
            writeLog('Info: - Completed Leasor deletion operation for ID: ' . $id); 
        }
    }
}