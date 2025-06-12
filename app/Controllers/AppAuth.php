<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleRightsModel;
use App\Models\MenuModel;
use App\Models\RoleModel;
use App\Models\LogLoginAttemptModel;
use Config\Services;

helper('App\Helpers\CustomHelpers');

class AppAuth extends BaseController
{    
    protected $session;
    protected $userModel;
    protected $roleModel;
    protected $rightsModel;
    protected $menuModel;
    protected $logLoginAttemptModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
        $this->rightsModel = new RoleRightsModel();
        $this->menuModel = new MenuModel();
        $this->logLoginAttemptModel = new LogLoginAttemptModel();
        $this->session = Services::session();
      
    }

    public function index(){
        $data['page'] = "PolePosition Log-in";
        $islogged_in = $this->session->get('isLoggedIn');
        if($islogged_in){
            redirect()->to(base_url('home'),);
        }else{
            return view('forms/login-screen',$data);
        }
        
    }

    public function login()
    {
        try {

            // Sanitize input
            $pfNumber = trim(htmlspecialchars($this->request->getPost('pf-number')));
            $passPhrase = trim(htmlspecialchars($this->request->getPost('pass-phrase')));

            // Basic input validation
            if (empty($pfNumber) || empty($passPhrase)) {
                return jEncodeResponse([], "Username and password are required.", 'error', 400, false);
            }

            // Log login attempts check
            $recent_fails = $this->logLoginAttemptModel->where([
                'user_pf' => $pfNumber,
                'status' => 'failed',
                'timestamp >=' => date('Y-m-d H:i:s', strtotime("-".LOGIN_ATTEMPT_TIMEOUT." minutes"))
            ])->countAllResults();

            if ($recent_fails >= MAX_LOGIN_ATTEMPTS) {
                return jEncodeResponse([], "Too many login attempts. Please try again later.", 'error', 429, false);
            }

            // Fetch user by PF number
            $user = $this->userModel->where(['user_pf' => $pfNumber])->first();
            if (!$user) {
                // Log failed login attempt
                $this->logLoginAttempt($pfNumber, 'failed', 'Invalid username');
                return jEncodeResponse([], "Invalid username or password.", 'error', 401, false);
            }

            // Validate password
            if (!password_verify($passPhrase, $user['password_hash'])) {
                return jEncodeResponse([], "Invalid username or password.", 'error', 401, false);
            }

            // Fetch user role
            $role = $this->roleModel->select('role_name')->where(['role_id' => $user['role_id']])->first();
            $roleName = $role ? $role['role_name'] : null;

            // Get assigned rights
            $rights = $this->rightsModel->select('menu_id')->where([
                'entity_id' => $user['role_id'],
                'assign_type' => 'G'
            ])->findAll();


            if (empty($rights)) {
                return jEncodeResponse([], "<h6>Authorization Failed!</h6>Unauthorized User!", 'error', 403, false);
            }

            // Extract menu IDs
            $menuIds = array_column($rights, 'menu_id');

            // Fetch menu items
            $menus = !empty($menuIds)
                ? $this->menuModel->where(['type' => 'url'])->orderBy('menu_category')->find($menuIds)
                : [];

            $lists = !empty($menuIds)
                ? $this->menuModel->where(['type' => 'list'])->orderBy('menu_category')->find($menuIds) // $this->menuModel->where(['type' => 'list'])->orderBy('menu_category')->find($menuIds)
                : [];

            $redircet_url = base_url('home');

            if($user["force_pwd_change"]==='Y'){
                $redircet_url = base_url('reset-password');
            }

            // Initialize session and store user data
            $this->session->start();
            $this->session->set('isLoggedIn', true);
            $this->session->set('userData', [
                'id'            => $user["id"],
                'name'          => $user["name"],
                'firstname'     => $user["firstname"],
                'lastname'      => $user["lastname"],
                'email'         => $user["email"],
                'force_change'  => $user["force_pwd_change"],
                'pfNumber'      => $user['user_pf'],
                'active'        => $user['active'],
                'role_name'     => $roleName,
                'menus'         => $menus,
                'lists'         => $lists
            ]);

            return jEncodeResponse(
                $this->session->get('userData'),
                "Login successful!",
                'success',
                200,
                true,
                $redircet_url
            );

        } catch (\Throwable $e) {
            log_message('error', '[LOGIN ERROR] ' . $e->getMessage());
            return jEncodeResponse(
                [],
                "An internal error occurred.",
                'error',
                500,
                false
            );
        }
    }

    public function logout()
	{
        try {

            $this->session->destroy();
            return redirect()->to(base_url("/"))->with('message', 'You have been logged out successfully.');
        } catch (\Throwable $e) {
            // Optionally log the error or handle it as needed
            return jEncodeResponse(
                [],
                "An error occurred while logging out: " . $e->getMessage(),
                'error',
                500,
                false
            );
        }
	}

    public function logLoginAttempt($user_pf, $status, $reason = null)
    {
        try {
            $data = [
                'user_pf' => $user_pf,
                'ip_address' => $this->request->getIPAddress(),
                'user_agent' => $this->request->getUserAgent(),
                'timestamp' => date('Y-m-d H:i:s'),
                'reason' => $reason,
                'logout_time' => null,
                'status' => $status
            ];

            if (!$this->logLoginAttemptModel->insert($data)) {
                log_message('error', '[LOGIN ATTEMPT ERROR] Failed to log login attempt: ' . implode(", ", $this->logLoginAttemptModel->errors()));
            }
            log_message('info', '[LOGIN ATTEMPT] User: ' . $user_pf . ', Status: ' . $status . ', Reason: ' . $reason);
        } catch (\Throwable $e) {
            log_message('error', '[LOGIN ATTEMPT ERROR] ' . $e->getMessage());
        }
    }

    public function groupByCategory(array $items): array {
        $grouped = [];
        foreach ($items as $item) {
            $cat = $item['menu_category'];
            $grouped[$cat][] = $item;
        }
        log_message('info', '[MENU GROUPING] Grouped items by category: ' . json_encode($grouped));
        return $grouped;
    }

}