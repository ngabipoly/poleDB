<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleRightsModel;
use App\Models\MenuModel;
use App\Models\RoleModel;
use CodeIgniter\Session\Handlers\FileHandler;
use Config\Services;

helper('App\Helpers\CustomHelpers');

class AppAuth extends BaseController
{    
    public function index(){
        $data['page'] = "Replica WebSmap Log-in";
        $islogged_in = $this->session->get('isLoggedIn');
        if($islogged_in){
            redirect()->route('home');
        }else{
            return view('forms/login-screen',$data);
        }
        
    }

    public function login(){
        $model= new UserModel();
        $role = new RoleModel();
        $rights = new RoleRightsModel();
        $menu = new MenuModel();
        
        $user_name = htmlspecialchars($this->request->getPost('pf-number'));
        $user_phrase = htmlspecialchars($this->request->getPost('pass-phrase')) ;
        $where_data =[
            'user_pf' => $user_name
        ];
        $user_data =  $model->where($where_data)->first();
        $db_hash = $user_data['password_hash'];

        $role_name = $role->select('role_name')->where(['role_id'=>$user_data['role_id']])->first();
        $assigned = $rights->select('menu_id')->where([
                'entity_id'=> $user_data['role_id'],
                'assign_type' => 'R'
            ]
            )->findAll();

        $ids = []; 
        
        foreach ($assigned as $menu_id) {
            array_push($ids,$menu_id['menu_id']);
        }

        $menus = $menu->where(['type'=>'url'])->orderBy('menu_category')->find($ids);
        $lists = $menu->where(['type'=>'list'])->orderBy('menu_category')->find($ids);

        // Verify the user's password
        if (password_verify($user_phrase, $db_hash)) {
            // login OK, save user data to session
            $this->session->start();
            $this->session->set('isLoggedIn', true);
            $this->session->set('userData', [
                'id' 			=> $user_data["id"],
                'name' 			=> $user_data["name"],
                'firstname' 	=> $user_data["firstname"],
                'lastname' 		=> $user_data["lastname"],
                'email' 		=> $user_data["email"],
                'force_change'  => $user_data["force_pwd_change"],
                'pfNumber'      => $user_data['user_pf'],
                'active'        => $user_data['active'],
                'role_name'     => $role_name,
                'menus'         => $menus,
                'lists'         => $lists
            ]);

            return json_encode(['status'=>'success','msg'=>"Login successful!",'force_pwd_change'=>$user_data["force_pwd_change"],'isLoggedIn'=>true]) ;
        } else {
            // Password is incorrect, show error message
            return json_encode(['status'=>'error', 'msg'=>"Invalid username or password."]) ;
        }

    }

    public function logout()
	{
		$this->session->remove(['isLoggedIn', 'userData']);
         return redirect()->to('/');
	}
}