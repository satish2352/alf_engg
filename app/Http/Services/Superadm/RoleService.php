<?php
namespace App\Http\Services\Superadm;

use Illuminate\Http\Request;
use App\Http\Repository\Superadm\RoleRepository;
use Exception;
use Log;

class RoleService
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new RoleRepository();
    }

    public function list()
    {
        try {
            return $this->repo->list();
        } catch (Exception $e) {
            Log::error("RoleService list error: " . $e->getMessage());
            return false;
        }
    }

public function sendApi($request)
{
    $role = \DB::table('roles')
        ->where('id', $request->id)
        ->where('is_deleted', 0)
        ->first();

    if (!$role) {
        return response()->json(['status' => false, 'message' => "Role not found"]);
    }

    $newProjects = $request->projects;
    $oldProjects = explode(",", $role->send_api_project_id ?? "");

    // =============================
    // STEP 1: Identify Removed Projects
    // =============================
    $removedProjects = array_diff($oldProjects, $newProjects);

    foreach ($removedProjects as $projId) {

        $proj = \DB::table('projects')
            ->where('id', $projId)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->first();

        if (!$proj) continue;

        // extract project folder name
        preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
        $projectName = $m[1] ?? null;
        if (!$projectName) continue;

        $payload = [
            "role_name" => $role->role,
            "status" => 0 // disable role
        ];

        \Http::post("https://alfitworld.com/{$projectName}/CommonController/api_update_role_status", $payload);
    }


    // =============================
    // STEP 2: SEND Role to CURRENT Selected Projects
    // =============================
    $projects = \DB::table('projects')
        ->whereIn('id', $newProjects)
        ->where('is_active', 1)
        ->where('is_deleted', 0)
        ->get();

    $allSuccess = true;
    $apiResponses = [];

    foreach ($projects as $proj) {

        preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
        $projectName = $m[1] ?? null;

        if (!$projectName) {
            $allSuccess = false;
            continue;
        }

        $payload = [
            "role_name" => $role->role,
            "short_description" => $role->short_description
        ];

        $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/addEditRoleApi";
        $response = \Http::post($apiUrl, $payload);

        $apiResponses[] = [
            'project_id' => $proj->id,
            'project_name' => $projectName,
            'api_url' => $apiUrl,
            'role' => $role->role,
            'short_description' => $role->short_description,
            'status_code' => $response->status(),
            'api_response' => $response->json()
        ];


        if (!$response->successful()) {
            $allSuccess = false;
        }
    }

    // =============================
    // STEP 3: UPDATE DB
    // =============================
    if ($allSuccess) {
        \DB::table('roles')
            ->where('id', $role->id)
            ->update([
                "send_api" => 1,
                "send_api_project_id" => implode(",", $newProjects)
            ]);

        return response()->json(['status' => true, 'message' => "Role data sent successfully", 'data' => $apiResponses]);
    }

    return response()->json(['status' => false, 'message' => "Some APIs failed", 'data' => $apiResponses]);
}



    public function save($req)
    {
        try {
               $data = [
                'role' => $req->input('role'),
                'short_description' => $req->input('short_description'),
            ];
            return $this->repo->save($data);
        } catch (Exception $e) {
            Log::error("RoleService save error: " . $e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        try {
            return $this->repo->edit($id);
        } catch (Exception $e) {
            Log::error("RoleService edit error: " . $e->getMessage());
            return false;
        }
    }

    public function update($req)
    {
        try {
            $id = $req->id;
            $data = [
                'role' => $req->input('role'),
                'short_description' => $req->input('short_description'),
                'is_active' => $req->is_active,
                'send_api' => 0, 
                'send_api_project_id' => null
            ];

            return $this->repo->update($data, $id);
        } catch (Exception $e) {
            Log::error("RoleService update error: " . $e->getMessage());
            return false;
        }
    }

    // public function delete($req)
    // {
    //     try {
    //         $id = base64_decode($req->id);
    //         $data = ['is_deleted' => 1];

    //         return $this->repo->delete($data, $id);
    //     } catch (Exception $e) {
    //         Log::error("RoleService delete error: " . $e->getMessage());
    //         return false;
    //     }
    // }

    // public function delete($req)
    // {
    //     try {
    //         $id = base64_decode($req->id);

    //         // Get role name
    //         $role = $this->repo->edit($id); // assuming edit() returns role details
    //         $roleName = $role->role ?? 'This role';

    //         // Check if any employee has this role assigned
    //         $employeeCount = \DB::table('employees')
    //             ->where('role_id', $id)
    //             ->where('is_deleted', 0)
    //             ->count();

    //         if ($employeeCount > 0) {
    //             // Role is assigned to employees, cannot delete
    //             throw new Exception("Cannot Delete The Role '{$roleName}' Because It Is Assigned To One Or More Employees.");
    //         }

    //         // Soft delete role
    //         $data = ['is_deleted' => 1];
    //         return $this->repo->delete($data, $id);

    //     } catch (Exception $e) {
    //         \Log::error("RoleService delete error: " . $e->getMessage());
    //         throw $e; // re-throw so controller can catch and show message
    //     }
    // }

public function delete(Request $request)
{
    try {
        $id = base64_decode($request->id);

        $role = \DB::table('roles')
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first();

        if (!$role) {
            return redirect()->back()->with('error', 'Role not found.');
        }

        // check if role assigned to employees
        $employeeExists = \DB::table('employees')
            ->where('role_id', $id)
            ->where('is_deleted', 0)
            ->exists();

        if ($employeeExists) {
            return redirect()->back()
                ->with('error', "Role '{$role->role}' is assigned to employees.");
        }

        // delete role from external projects
        $projectIds = array_filter(explode(",", $role->send_api_project_id ?? ""));

        $projects = \DB::table('projects')
            ->whereIn('id', $projectIds)
            ->where('is_active', 1)
            ->get();

        foreach ($projects as $proj) {
            preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
            $projectName = $m[1] ?? null;
            if (!$projectName) continue;

            \Http::post(
                "https://alfitworld.com/{$projectName}/CommonController/api_delete_role",
                ["role_name" => $role->role]
            );
        }

        // local soft delete
        \DB::table('roles')
            ->where('id', $id)
            ->update(['is_deleted' => 1]);

        return redirect()
            ->route('roles.list')
            ->with('success', "Role '{$role->role}' deleted successfully.");

    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Error deleting role: ' . $e->getMessage());
    }
}


    // public function updateStatus($req)
    // {
    //     try {
    //         $id = base64_decode($req->id);
    //         $data = ['is_active' => $req->is_active];

    //         return $this->repo->updateStatus($data, $id);
    //     } catch (Exception $e) {
    //         Log::error("RoleService updateStatus error: " . $e->getMessage());
    //         return false;
    //     }
    // }

public function updateStatus(Request $request)
{
    try {
        $id = base64_decode($request->id);

        $role = \DB::table('roles')
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first();

        if (!$role) {
            return response()->json([
                'status' => false,
                'message' => 'Role not found'
            ]);
        }

        $is_active = $request->is_active ? 1 : 0;

        // 1️⃣ Local DB update
        \DB::table('roles')
            ->where('id', $id)
            ->update(['is_active' => $is_active]);

        // 2️⃣ Send API to projects
        $projectIds = array_filter(explode(",", $role->send_api_project_id ?? ""));

        $projects = \DB::table('projects')
            ->whereIn('id', $projectIds)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();

        $apiResponses = [];
        $allSuccess = true;

        foreach ($projects as $proj) {

            preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
            $projectName = $m[1] ?? null;
            if (!$projectName) {
                $allSuccess = false;
                continue;
            }

            $payload = [
                'role_name' => $role->role,
                'status'    => $is_active
            ];

            $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/api_update_role_status";

            try {
                $response = \Http::post($apiUrl, $payload);

                $apiResponses[] = [
                    'project_id'   => $proj->id,
                    'project_name' => $projectName,
                    'api_url'      => $apiUrl,
                    'role_name'         => $role->role,
                    'status'       => $is_active,
                    'status_code'  => $response->status(),
                    'api_response' => $response->json()
                ];

                if (!$response->successful()) {
                    $allSuccess = false;
                }

            } catch (\Exception $e) {
                $allSuccess = false;

                $apiResponses[] = [
                    'project_id'   => $proj->id,
                    'project_name' => $projectName,
                    'api_url'      => $apiUrl,
                    'role_name'         => $role->role,
                    'status'       => $is_active,
                    'status_code'  => 500,
                    'api_response' => [
                        'status'  => false,
                        'message' => $e->getMessage()
                    ]
                ];
            }
        }

        return response()->json([
            'status'  => $allSuccess,
            'message' => "Role '{$role->role}' status updated successfully",
            'data'    => $apiResponses
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Error updating status: ' . $e->getMessage()
        ]);
    }
}



//     public function updateStatus($req)
// {
//     try {
//         $id = base64_decode($req->id);
//         $isActive = $req->is_active; // 1 = activate, 0 = deactivate

//         // If trying to deactivate the role
//         if ($isActive == 0) {
//             // Fetch role name
//             $role = $this->repo->edit($id);
//             $roleName = $role->role ?? 'This role';

//             // Check if employees are using this role
//             $employeeCount = \DB::table('employees')
//                 ->where('role_id', $id)
//                 ->where('is_deleted', 0)
//                 ->count();

//             if ($employeeCount > 0) {
//                 throw new Exception("Cannot deactivate the role '{$roleName}' because it is assigned to one or more employees.");
//             }
//         }

//         // If allowed, update status
//         $data = ['is_active' => $isActive];
//         return $this->repo->updateStatus($data, $id);

//     } catch (Exception $e) {
//         \Log::error("RoleService updateStatus error: " . $e->getMessage());
//         throw $e; // Let the controller handle the message
//     }
// }


    public function find($id)
{
    try {
        return $this->repo->edit($id); // reuse existing edit() to fetch role
    } catch (Exception $e) {
        \Log::error("RoleService find error: " . $e->getMessage());
        return null;
    }
}


}
