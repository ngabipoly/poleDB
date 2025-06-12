<?php 
namespace App\Controllers;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\MenuModel;
use App\Models\RoleRightsModel;

helper('App\Helpers\CustomHelpers');

class UserRoleMgr extends BaseController
{
    protected $session;
    protected $user;
    protected $roleModel;
    protected $menuModel;
    protected $rightsModel;
    protected $userModel;
    protected $roleRightsModel;


    public function __construct()
    { 
        // Initialize session
        $this->session = session();
        // Redirect if not logged in
        if (!$this->session->get('userData')) {
            redirect()->to('/')->send();
            exit;
        }
        $this->user = session()->get('userData');
        $this->roleModel = new RoleModel();
        $this->menuModel = new MenuModel();
        $this->roleRightsModel = new RoleRightsModel();
        $this->userModel = new UserModel();
    }    

    public function index()
    {
        // Fetch user records from the database
        $data['users'] = $this->userModel->getUsers();
        $data['page'] = "User Management";
        $data['roles']= $this->getRole();
        $data['user'] = $this->session->get('userData');
        return view('partials/user-listing', $data);
    }

    public function saveUser()
    {
        try {
            if (!$this->request->getPost()) {
                throw new \InvalidArgumentException("No data received for saving user.");
            }

            $firstName = trim($this->request->getPost('first-name'));
            $lastName = trim($this->request->getPost('last-name'));
            $pfNumber = trim($this->request->getPost('pf-number'));
            $email = filter_var($this->request->getPost('user-email'), FILTER_VALIDATE_EMAIL);
            $roleId = intval($this->request->getPost('user-role'));
            $isActive = $this->request->getPost('user-status');
            $userIdExists = $this->request->getPost('uid');

            if (!$firstName || !$lastName || !$pfNumber || !$email || !$roleId) {
                throw new \InvalidArgumentException("All fields are required.");
            }

            $userData = [
                'email'     => $email,
                'user_pf'   => $pfNumber,
                'firstname' => htmlspecialchars($firstName, ENT_QUOTES, 'UTF-8'),
                'lastname'  => htmlspecialchars($lastName, ENT_QUOTES, 'UTF-8'),
                'role_id'   => $roleId,
                'active'    => $isActive
            ];

            $generatedPassword = null;
            $mailSubject = "Pole Management:: New Account Created";

            if ($userIdExists) {
                $userData['id'] = intval($userIdExists);
            } else {
                $generatedPassword = $this->generatePwd(12);
                $userData['password'] = $generatedPassword;
                $userData['force_pwd_change'] = 'Y';
            }

            if (!$this->userModel->save($userData)) {
                return jEncodeResponse([], "Failed to save user: " . implode('<br/> ', $this->userModel->errors()), 'error', 500, false);
            }

            // Only send password if new user
            if (!$userIdExists && $generatedPassword) {
                sendMail(
                    'alerts@utcl.co.ug',
                    $email,
                    $mailSubject,
                    "Your Account has been created. One-time password is {$generatedPassword}. Please change this as soon as you log on."
                );
            }

            return jEncodeResponse(
                [],
                "User <strong>{$lastName} , {$firstName} </strong>" . ($userIdExists ? 'updated' : 'saved') . " successfully",
                'success',
                200,
                true,
                base_url('administration/usr-admin')
            );
        } catch (\Throwable $e) {
            writeLog('Error in saveUser: ' . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function resetPwd()
    {
        try {
            if (!$this->request->getPost()) {
                throw new \InvalidArgumentException("No data received for password reset.");
            }

            $user_id = $this->request->getPost('uid');
            $first_name = htmlspecialchars(trim($this->request->getPost('first-name')), ENT_QUOTES, 'UTF-8');
            $last_name = htmlspecialchars(trim($this->request->getPost('last-name')), ENT_QUOTES, 'UTF-8');
            $email = filter_var($this->request->getPost('user-email'), FILTER_VALIDATE_EMAIL);

            if (!$user_id || !$first_name || !$last_name || !$email) {
                throw new \InvalidArgumentException("All fields are required for password reset.");
            }

            $password = $this->generatePwd(8);

            $data = [
                'id' => $user_id,
                'password' => $password,
                'force_pwd_change' => 'Y'
            ];

            $names = "{$last_name}, {$first_name}";
            $mail_subject = 'Pole Management:: Password Reset';
            $body = "Dear {$names},\nYour account password has been reset. Your one-time password is {$password}. Please change this as soon as you log on.";

            if (!$this->userModel->save($data)) {
                return jEncodeResponse([], "Error resetting password: " . implode('<br/> ', $this->userModel->errors()), 'error', 500, false);
            }

            sendMail('alerts@utcl.co.ug', $email, $mail_subject, $body);

            writeLog("Password reset for user {$user_id}:{$names}");

            return jEncodeResponse(
                [], 
                "Password for {$names} has been Reset!", 
                'success', 200
            );
        } catch (\Throwable $e) {
            writeLog("Error resetting password for user {$user_id}:{$names} " . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function changePwd(){
        $data['page'] = "User Password Change";
        $data['user'] = $this->session->get('userData');
        return view('forms/change-password', $data);        
    }

    public function savePwdChange()
    {
        try {
            // Sanitize and validate input
            $user_id = intval($this->request->getPost('uid'));
            $c_password = trim($this->request->getPost('u-old-phrase'));
            $u_password = trim($this->request->getPost('u-phrase'));
            $forced = htmlspecialchars($this->request->getPost('forced'), ENT_QUOTES, 'UTF-8');
            $cmf_password = trim($this->request->getPost('repeat-phrase'));

            if (!$user_id || !$c_password || !$u_password || !$cmf_password) {
                throw new \InvalidArgumentException("All fields are required for password change.");
            }

            if ($u_password !== $cmf_password) {
                throw new \InvalidArgumentException("New password and confirmation do not match.");
            }

            if ($c_password === $u_password) {
               throw new \InvalidArgumentException("New password cannot be the same as the current password.");
            }

            $user_data = $this->userModel->find($user_id);
            if (!$user_data || empty($user_data['password_hash'])) {
                throw new \InvalidArgumentException("User not found.");
            }

            // Verify the user's password
            if (!password_verify($c_password, $user_data['password_hash'])) {
                throw new \InvalidArgumentException("Current password is incorrect.");
            }

            // Prepare data for update
            $data = [
                'id' => $user_id,
                'password' => $u_password,
                'password_confirm' => $cmf_password
            ];

            if ($forced === 'Y') {
                $data['force_pwd_change'] = 'N';
            }

            if (!$this->userModel->save($data)) {
                $errors = $this->userModel->errors();
                throw new \RuntimeException("Error saving new password: " . implode('<br/> ', $errors));
            }

            $msg = 'Password Successfully changed!';
            $redir = base_url('/');

            return jEncodeResponse([], $msg, 'success', 200, true, $redir);

        } catch (\Throwable $e) {
            writeLog('Error in savePwdChange: ' . $e->getMessage());
            return jEncodeResponse([], 'An error occurred while changing password.', 'error', 500, false);
        }
    }

    public function delete($id)
    {
        // Fetch user record by ID
        // Delete user record from the database
        // Redirect to index or show success message
    }

    public function roles()
    {
        $data['roles']= $this->getRole();
        $data['page']= 'Role Listing';
        $data['user'] = $this->session->get('userData');
        $data['roles'] = $this->roleModel->findAll();
        return view('partials/role-listing', $data);
    }
    public function getRole($role_id=null){
        //fetch role information
        if($role_id){
            return $this->roleModel->find($role_id);
        }
        return $this->roleModel->findAll();
    }

    public function saveRole()
    {
        try {
            $roleName = htmlspecialchars(trim($this->request->getPost('role-name')), ENT_QUOTES, 'UTF-8');
            $roleDesc = htmlspecialchars(trim($this->request->getPost('role-desc')), ENT_QUOTES, 'UTF-8');
            $roleStatus = htmlspecialchars(trim($this->request->getPost('role-status')), ENT_QUOTES, 'UTF-8');
            $roleId = $this->request->getPost('role-id');

            if (!$roleName || !$roleDesc || $roleStatus === null) {
                throw new \InvalidArgumentException("All fields are required.");
            }

            $data = [
                'role_name' => $roleName,
                'role_desc' => $roleDesc,
                'role_status' => $roleStatus
            ];

            if ($roleId) {
                $data['role_id'] = intval($roleId);
            }

            if (!$this->roleModel->save($data)) {
                $errors = $this->roleModel->errors();
                throw new \RuntimeException("Error saving role: " . implode('<br/> ', $errors));
            }

            return jEncodeResponse([], "Role saved successfully.", 'success', 200, true, base_url('administration/usr-roles'));
        } catch (\Throwable $e) {
            writeLog('Error in saveRole: ' . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }
    function loadRightsMenus(){
        $data = [];
        $entity_type = $this->request->getGet('entity_type');
        $entity_id = $this->request->getGet('entity_id');
        $given_menus = $this->getAssigned($entity_type,$entity_id);
        $assigned_ids = array_map(function($assign){
               return $assign['menu_id'];
        },$given_menus);
        $data['assigned'] = $this->getRoleMenus($assigned_ids);
        $data['unassigned'] = $this->getRoleMenus($assigned_ids,'U');
        return view('partials/rights-assignment',$data);
    }
    function getAssigned(string $type, int $id){
        $where_data = [
            'entity_id' =>$id,
            'assign_type'=>$type
        ];
        return $this->roleRightsModel->where($where_data)->findAll();
    }

    //gets role menus
    function getRoleMenus(array $menu_ids,string $op_type='A'){
        $model = new MenuModel();
        if($op_type=='U'){
            return (!empty($menu_ids))?$this->menuModel->whereNotIn('menu_id',$menu_ids)->findAll():$model->findAll();
        }else{
            return (!empty($menu_ids))? $this->menuModel->whereIn('menu_id',$menu_ids)->findAll():null;
        }

    }

    public function saveRights(){
        $entity_id = $this->request->getPost('entity-id');
        $entity_type = $this->request->getPost('entity-type');
        $rights_revoke = $this->request->getPost('revoke-list');
        $right_grant = $this->request->getPost('assign-list');
        $assign = [];
        $revoke = [];
        $message = '';

        $give_rights = explode(':',$right_grant);
        $revoke_rights =explode(':',$rights_revoke);

        if(!empty($right_grant)){
            $assign = $this->assignRights($entity_type,$entity_id,$give_rights);
            $message = 'Assignment '.$assign['msg'];
        }

        if(!empty($rights_revoke)){
            $revoke = $this->revokeRights($entity_id,$entity_type,$revoke_rights);
            $message = '\n Revocation:'.$revoke['msg'];
        }
            
        return json_encode(['status'=>'info','msg'=>$message]);        
    }

    public function revokeRights(int $entity_id, string $entity_type, array $selected_menus){
        
        try {
            $deleted = $this->roleRightsModel
            ->where('entity_id', $entity_id)
            ->where('assign_type', $entity_type)
            ->whereIn('menu_id', $selected_menus)
            ->delete();

            if (!$deleted) {               
                writeLog("Error revoking rights for entity {$entity_type}:{$entity_id} with menus: " . implode(',', $selected_menus));
                return jEncodeResponse([], "Error revoking rights: " . implode('<br/> ', $this->roleRightsModel->errors()), 'error', 500, false);
            } 
            writeLog("Rights revoked for entity {$entity_type}:{$entity_id} with menus: " . implode(',', $selected_menus));
            
            return jEncodeResponse([], 'Rights Revoked Successfully', 'success', 200);
        } catch (\Throwable $e) {
            writeLog('Error in revokeRights: ' . $e->getMessage());
            return jEncodeResponse([], $e->getMessage(), 'error', 500, false);
        }
    }

    public function assignRights(string $entity_type, int $entity_id, array $granted){
        $model = new RoleRightsModel();
        $data = [];
        foreach ($granted as $grant_id) {
            $row =['entity_id'=>$entity_id, 'menu_id'=>$grant_id,'assign_type'=>$entity_type,'assigned_by'=>$this->session->userData['id']];
            array_push($data,$row);
        }
        writeLog('Rigths to assin: '.json_encode($granted));
        if($model->insertBatch($data)){
            return ['status'=>'success','msg'=>'Rights Assignment Complete'];
        }else{
            return ['status'=>'error','msg'=>'Could not Revoke Rights'];
        }
    }

    public function generatePwd($length = 12) {
        $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()';
        $password = '';
    
        $charCount = strlen($characters);
        for ($i = 0; $i < $length; $i++) {
            $password .= $characters[rand(0, $charCount - 1)];
        }
    
        return $password;
    }  

}
