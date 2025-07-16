<?php
    namespace App\Controllers;
    use App\Models\CarryTypeModel;
    use App\Models\CarryCapacityModel;
    use CodeIgniter\Controller;

    class MediaManagement extends Controller{
        protected $carryTypeModel;
        protected $carryCapacityModel;
        protected $session;
        protected $user;

        public function __construct(){
            $this->carryTypeModel = new CarryTypeModel();
            $this->carryCapacityModel = new CarryCapacityModel();
            $this->session = session();
            $this->user = $this->session->get('userData');
        }
        public function listMediaTypes(){
            $data = [
                'page' => 'Media Type Management',
                'user' => $this->user,
                'carryTypes' => $this->carryTypeModel->findAll()
            ];
            return view('forms/media_management/mediaTypeManager', $data);
        }

        public function listMediaCapacities(){
            $data = [
                'page' => 'Media capacity Management',
                'user' => $this->user,
                'carryCapacities' => $this->carryCapacityModel->
                            join('tbl_carrying_Types ct', 'carryType = ct.carryTypeId', 'left')->
                            findAll(),
                'carryTypes' => $this->carryTypeModel->findAll()
            ];
            return view('forms/media_management/mediaCapacityManager', $data);
        }


        public function saveDetails(){
            
            try {
                $formType = $this->request->getPost('formType');
                $data = $this->collectInputData($formType);
                writeLog('Form Type: ' . $formType . ' Data: ' . json_encode($data));

                if ($formType == 'carryType') {
                    return $this->saveMediaType($data);
                } elseif ($formType == 'carryCapacity') {
                    return $this->saveMediaCapacity($data);
                }
            } catch (\Exception $e) {
                return jEncodeResponse($data, $e->getMessage(), 'error', 500, false);
            }
        } 

        public function saveMediaType($mediaData){
            try {
                // if carryTypeId is set then we are performing update operations                
                $mediaData['typeUpdatedBy'] = isset($this->user['pfNumber']) ? $this->user['pfNumber'] : '';
                writeLog('Saving Media Type: ' . json_encode($mediaData));

                if((int)$mediaData['carryTypeId'] > 0){
                    if(!$this->carryTypeModel->update($mediaData['carryTypeId'], $mediaData)){
                        writeLog('Failed to update Media Type. ' . implode(', ', $this->carryTypeModel->errors()). ' Data: ' . json_encode($mediaData));
                        throw new \Exception("Failed to update Media Type. " . implode('<br> ', $this->carryTypeModel->errors()));
                    }
                    return jEncodeResponse($mediaData, "Media Type updated successfully.", 'success', 200, true,'media-types');
                }

                // If carryTypeId is not set, we are inserting a new record
                $mediaData['typeCreatedBy'] = isset($this->user['pfNumber']) ? $this->user['pfNumber'] : '';
                if (!$this->carryTypeModel->insert($mediaData)) {
                    writeLog('Failed to save Media Type. ' . implode(', ', $this->carryTypeModel->errors()). ' Data: ' . json_encode($mediaData));
                    throw new \Exception("Failed to save Media Type. " . implode('<br> ', $this->carryTypeModel->errors()));
                }
                return jEncodeResponse($mediaData, "Media Type saved successfully.", 'success', 200, true,'media-types');
            } catch (\Exception $e) {
                return jEncodeResponse($mediaData, $e->getMessage(), 'error', 500, false);
            }
        }

        public function deleteMediaType(){
            try {
                writeLog('Beginning deletion of Media Type');
                $carryTypeId = (int) htmlspecialchars(trim($this->request->getPost('deleteCarryTypeId') ?? ''));
                $carryTypeName = htmlspecialchars(trim($this->request->getPost('deleteCarryTypeName') ?? ''));
                
                writeLog("Deleting Media Type $carryTypeName with ID: $carryTypeId");

                if (!$carryTypeId) {
                    return jEncodeResponse([], 'Invalid Media Type ID.', 'error', 400, false);
                }

                if (!$this->carryTypeModel->delete($carryTypeId)) {
                    writeLog('Failed to delete Media Type. ' . implode(', ', $this->carryTypeModel->errors()));
                    throw new \Exception("Failed to delete Media Type. " . implode('<br> ', $this->carryTypeModel->errors()));
                }
                return jEncodeResponse([], 'Media Type deleted successfully.', 'success', 200, true,'media-types');
            } catch (\Exception $e) {
                return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
            }
        }

        /**
         * Saves media capacity details to the database.
         *
         * @param array $capacityData Associative array containing media capacity data.
         * @return mixed JSON encoded response indicating success or error.
         */
        public function saveMediaCapacity($capacityData){
            // Ensure $capacityData is always defined
            writeLog('Saving Media Capacity: ' . json_encode($capacityData));

            try {
                // if carryCapacityId is set then we are performing update operations
                if((int)$capacityData['carryCapacityId'] > 0){
                    writeLog('Updating Media Capacity with ID: ' . $capacityData['carryCapacityId']);
                    if(!$this->carryCapacityModel->update($capacityData['carryCapacityId'], $capacityData)){
                        writeLog('Failed to update Media Capacity. ' . implode(', ', $this->carryCapacityModel->errors()) . ' Data: ' . json_encode($capacityData));
                        throw new \Exception("<b>Failed to update Media Capacity. </b>" . implode('<br> ', $this->carryCapacityModel->errors()));
                    }
                    writeLog('Media Capacity updated successfully.');
                    // Return success response
                    return jEncodeResponse($capacityData, 'Media Capacity updated successfully.', 'success', 200, true,'media-capacities');  

                }

                // If carryCapacityId is not set, we are inserting a new record
                writeLog('Inserting new Media Capacity');
                $capacityData['capacityAddBy'] = isset($this->user['pfNumber']) ? $this->user['pfNumber'] : '';
                if (!$this->carryCapacityModel->insert($capacityData)) {
                    writeLog('Failed to save Media Capacity. ' . implode(', ', $this->carryCapacityModel->errors()) . ' Data: ' . json_encode($capacityData));
                    throw new \Exception("<b>Failed to save Media Capacity. </b>" . implode('<br> ', $this->carryCapacityModel->errors()));
                }

                return jEncodeResponse($capacityData, 'Media Capacity saved successfully.', 'success', 200, true,'media-capacities');
            } catch (\Exception $e) {
                return jEncodeResponse($capacityData, $e->getMessage(), 'error', 500, false);
            }
        }

        public function deleteMediaCapacity(){
            try {
                writeLog('Beginning deletion of Media Capacity');
                $carryCapacityId = (int) htmlspecialchars(trim($this->request->getPost('deleteCarryCapacityId') ?? ''));
                $carryCapacityLabel = htmlspecialchars(trim($this->request->getPost('deleteCarryCapacityLabel') ?? ''));
                
                writeLog("Deleting Media Capacity $carryCapacityLabel with ID: $carryCapacityId");

                if (!$carryCapacityId) {
                    return jEncodeResponse([], 'Invalid Media Capacity ID.', 'error', 400, false);
                }

                if (!$this->carryCapacityModel->delete($carryCapacityId)) {
                    writeLog('Failed to delete Media Capacity. ' . implode(', ', $this->carryCapacityModel->errors()));
                    throw new \Exception("Failed to delete Media Capacity. " . implode('<br> ', $this->carryCapacityModel->errors()));
                }
                return jEncodeResponse([], 'Media Capacity deleted successfully.', 'success', 200, true,'media-capacities');
                }
            catch (\Exception $e) {
                return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
            }
        }


        public function collectInputData($formType)
        {
            $data = [];
            writeLog('POST Data: ' . json_encode($this->request->getPost()));
            try {
            switch ($formType) {
                case 'carryType':
                $carryTypeId = (int) htmlspecialchars(trim($this->request->getPost('carryTypeId') ?? ''));
                $data = [
                    'carryTypeId'          => $carryTypeId,
                    'carryTypeDescription' => htmlspecialchars(trim($this->request->getPost('carryTypeDescription') ?? '')),
                ];
                if($carryTypeId > 0) {
                    $data['typeUpdatedBy'] = isset($this->user['pfNumber']) ? $this->user['pfNumber'] : '';
                } else {
                    $data['typeCreatedBy'] = isset($this->user['pfNumber']) ? $this->user['pfNumber'] : '';
                    $data['carryTypeName'] = htmlspecialchars(trim($this->request->getPost('carryTypeName') ?? ''));
                }
                break;

                case 'carryCapacity':
                // Collecting data for carry capacity
                $carryCapacityId = (int) filter_var($this->request->getPost('carryCapacityId') ?? null, FILTER_VALIDATE_INT, ["options" => ["default" => null]]);
                $data = [
                    'carryCapacityId'          => $carryCapacityId,
                    'carryType'              => filter_var($this->request->getPost('carryTypeId') ?? null, FILTER_VALIDATE_INT, ["options" => ["default" => null]]),
                    'carryCapacityValue'       => filter_var($this->request->getPost('carryCapacityValue') ?? 0, FILTER_VALIDATE_FLOAT, ["options" => ["default" => 0]]),
                    'CapacityDescription' => htmlspecialchars(trim($this->request->getPost('carryCapacityDescription') ?? '')),
                ];
                if($carryCapacityId > 0) {
                    $data['capacityModifyBy'] = isset($this->user['pfNumber']) ? $this->user['pfNumber'] : '';
                } else {
                    $data['capacityLabel'] = htmlspecialchars(trim($this->request->getPost('carryCapacityLabel') ?? ''));
                    $data['typeCreatedBy'] = isset($this->user['pfNumber']) ? $this->user['pfNumber'] : '';
                }
                break;

                default:
                throw new \Exception("Unknown form type: $formType");
            }
            } catch (\Exception $e) {
            log_message('error', 'Data collection error: ' . $e->getMessage());
            $data = [];
            }
            writeLog('Collected Data: ' . json_encode($data));

            return $data;
        }

    }