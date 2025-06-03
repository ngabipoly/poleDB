<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Models\MenuModel;
use App\Models\RoleRights;
use App\Models\RoleRightsModel;

helper('App\Helpers\CustomHelpers');

class UserRoleMgr extends BaseController
{
    protected $user;

    public function __construct()
    { 
        if(isset($this->session)){
            return redirect()->to('/');
        }
        $this->user = session()->get('userData');
    }    

    public function index()
    {
        // Fetch user records from the database
        $model = new UserModel();
        $data['users'] = $model->getUsers();
        $data['page'] = "User Management";
        $data['roles']= $this->getRole();
        $data['user'] = $this->session->get('userData');
        return view('partials/user-listing', $data);
    }

    public function saveUser()
    {
        // Handle user registration form submission
        if ($this->request->getPost()) {
            $first_name = $this->request->getPost('first-name');
            $last_name = $this->request->getPost('last-name');
            $pf_number = $this->request->getPost('pf-number');
            $email = $this->request->getPost('user-email');
            $role_id = $this->request->getPost('user-role');
            $status = $this->request->getPost('user-status');
            $user_id = $this->request->getPost('uid');
            // Validate form data
            // Hash password
            // Insert user record into the database
                // Generate a random password
            $password = $this->generatePwd(8);   
            
            $mail_subject = "Websmap Account Created";

            $data = [
                'email'=>$email,
                'user_pf' => $pf_number,
                'firstname' => $first_name,
                'lastname' => $last_name,
                'role_id' => $role_id,
                'active' => $status
            ];

            if($user_id){
                $data['id']=$user_id;
            }else{;                
                $data['password'] = $password;
            }

            $model = new UserModel();
            if($model->save($data)){
                sendMail('alerts.websmap@utcl.co.ug',$email,$mail_subject,"Your Account has been created one-time password is {$password}. Please change this as soon as you log on.");
                return json_encode(['status'=>'success','msg'=>'User Successfuly Added']);
            }else{
                return json_encode(['status'=>'error','msg'=>'Error saving Users']);
            }
            
        }
    }

    public function resetPwd(){
        $model = new UserModel();
        $user_id = $this->request->getPost('uid');
        $first_name = $this->request->getPost('first-name');
        $last_name = $this->request->getPost('last-name');
        $email = $this->request->getPost('user-email');        
        $password = $this->generatePwd(8);  

        $data= [
            'id'=>$user_id,
            'password'=>$password,
            'force_pwd_change'=>'Y'
            ] ;

        $names = "{$last_name} {$first_name}";
        $mail_subject = 'rWebSmap Password Reset';
        $body = "Dear {$names},\n Your rWebSmap password has been reset. Your one-time password is {$password}. Please change this as soon as you log on ";
        if($model->save($data)){
            sendMail('alerts.websmap@utcl.co.ug',$email,$mail_subject,$body);
            return json_encode(['status'=>'success','msg'=>'Password Reset!']);            
        }else{
            return json_encode(['status'=>'error','msg'=>'Error Resetting Password']);
        }
    }

    public function changePwd(){
        $data['page'] = "User Password Change";
        $data['user'] = $this->session->get('userData');
        return view('forms/change-password', $data);        
    }

    public function savePwdChange(){
        $model= new UserModel();
        $user_id = $this->request->getPost('uid');
        $c_password = $this->request->getPost('u-old-phrase');
        $u_password = $this->request->getPost('u-phrase');
        $forced = $this->request->getPost('forced');
        $cmf_password = $this->request->getPost('repeat-phrase'); 
        $data = [
            'id'=>$user_id,
            'password'=>$u_password,
            'password_confirm' =>$cmf_password            
        ]; 
        $where_data =[
            'id'=>$user_id
        ];
        $user_data =  $model->where($where_data)->first();
        $db_hash = $user_data['password_hash'];

        // Verify the user's password
        if (!password_verify($c_password, $db_hash)){
            return json_encode(['status'=>'error','msg'=>'Invalid Credentials!']);
        } 

        if($forced==='Y'){
            $data['force_pwd_change']= 'N';
        }

        if($c_password===$u_password){
            return json_encode(['status'=>'error','msg'=>'New Password same as old!']);
        }

        if($model->save($data)){
            if($forced=='Y'){
                return json_encode(['status'=>'success','msg'=>'Password has been Changed!','redir_to'=>base_url('home')]);
            }else{
            return json_encode(['status'=>'success','msg'=>'Password has been Changed']);
            }
        }else{
            return json_encode(['status'=>'error','msg'=>'Error Changing Password']);
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
        $model= new RoleModel();
        $data['roles']= $this->getRole();
        $data['page']= 'Role Listing';
        $data['user'] = $this->session->get('userData');
        $data['roles'] = $model->findAll();
        return view('partials/role-listing', $data);
    }
    public function getRole($role_id=null){
        //fetch role information
        $model = new RoleModel();
        return $model->findAll();
    }

    public function saveRole()
    {
        $data = [
            'role_name'=>$this->request->getPost('role-name'),
            'role_desc'=>$this->request->getPost('role-desc'),
            'role_status'=>$this->request->getPost('role-status')
        ];
        if($this->request->getPost('role-id'))
        {
            $data['role_id'] = $this->request->getPost('role-id');
        }

        $model= new RoleModel();
        if($model->save($data)){
            $this->roles();
        }else{
            return json_encode(['status'=>'error','msg'=>'Error Saving Role']);
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
        $model = new RoleRightsModel();
        $where_data = [
            'entity_id' =>$id,
            'assign_type'=>$type
        ];
        return $model->where($where_data)->findAll();
    }

    //gets role menus
    function getRoleMenus(array $menu_ids,string $op_type='A'){
        $model = new MenuModel();
        if($op_type=='U'){
            return (!empty($menu_ids))?$model->whereNotIn('menu_id',$menu_ids)->findAll():$model->findAll();
        }else{
            return (!empty($menu_ids))? $model->whereIn('menu_id',$menu_ids)->findAll():null;
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
        $model = new RoleRightsModel();
        if($model->where('entity_id',$entity_id)->where('assign_type',$entity_type)->whereIn('menu_id',$selected_menus)->delete()){
            return ['status'=>'success','msg'=>'Rights Revoked'];
        }else{
            return ['status'=>'error','msg'=>'Could not Revoke rights'];
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
