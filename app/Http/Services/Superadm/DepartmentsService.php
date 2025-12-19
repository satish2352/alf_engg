<?php
namespace App\Http\Services\Superadm;

use Illuminate\Http\Request;
use App\Http\Repository\Superadm\DepartmentsRepository;
use Exception;
use Log;

class DepartmentsService
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new DepartmentsRepository();
    }


    public function list($plant_id = null)
    {
        try {
            return $this->repo->list($plant_id);
        } catch (Exception $e) {
            Log::error("Department Service list error: " . $e->getMessage());
            return false;
        }
    }

    public function sendApi($request)
{
    $department = \DB::table('departments')
        ->join('plant_masters', 'departments.plant_id', '=', 'plant_masters.id')
        ->where('departments.id', $request->id)
        ->where('departments.is_deleted', 0)
        ->select(
            'departments.*',
            'plant_masters.plant_name',
            'plant_masters.plant_code'
        )
        ->first();

    if (!$department) {
        return response()->json([
            'status' => false,
            'message' => 'Department not found'
        ]);
    }

    $newProjects = $request->projects;
    $oldProjects = explode(",", $department->send_api_project_id ?? "");

    // =====================================
    // STEP 1: REMOVED PROJECTS → disable department
    // =====================================
    $removedProjects = array_diff($oldProjects, $newProjects);

    foreach ($removedProjects as $projId) {

        $proj = \DB::table('projects')
            ->where('id', $projId)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->first();

        if (!$proj) continue;

        preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
        $projectName = $m[1] ?? null;
        if (!$projectName) continue;

        \Http::post(
            "https://alfitworld.com/{$projectName}/CommonController/api_update_department_status",
            [
                "dept_code" => $department->department_code,
                "dept_name"        => $department->department_name,
                "plant" => $department->plant_code,
                "status" => 0
            ]
        );
    }

    // =====================================
    // STEP 2: SEND / UPDATE department
    // =====================================
    $projects = \DB::table('projects')
        ->whereIn('id', $newProjects)
        ->where('is_active', 1)
        ->where('is_deleted', 0)
        ->get();

    $allSuccess   = true;
    $apiResponses = [];

    foreach ($projects as $proj) {

        preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
        $projectName = $m[1] ?? null;

        if (!$projectName) {
            $allSuccess = false;
            continue;
        }

        $payload = [
            "plant" => $department->plant_code,
            "plant_name"             => $department->plant_name,
            "dept_code"        => $department->department_code,
            "dept_name"        => $department->department_name,
            "dept_short"  => $department->department_short_name
        ];

        $apiUrl   = "https://alfitworld.com/{$projectName}/CommonController/api_add_department";
        $response = \Http::post($apiUrl, $payload);

        // 🔹 SAME AS ROLE / DESIGNATION
        $apiResponses[] = [
            'project_id'   => $proj->id,
            'project_name' => $projectName,
            'api_url'      => $apiUrl,
            'dept_name'   => $department->department_name,
            "dept_code"   => $department->department_code,
            "dept_short"  => $department->department_short_name,
            "plant" => $department->plant_code,
            'status_code'  => $response->status(),
            'api_response' => $response->json()
        ];

        if (!$response->successful()) {
            $allSuccess = false;
        }
    }

    // =====================================
    // STEP 3: UPDATE LOCAL DB
    // =====================================
    if ($allSuccess) {
        \DB::table('departments')
            ->where('id', $department->id)
            ->update([
                'send_api' => 1,
                'send_api_project_id' => implode(",", $newProjects)
            ]);

        return response()->json([
            'status'  => true,
            'message' => 'Department data sent successfully',
            'data'    => $apiResponses
        ]);
    }

    return response()->json([
        'status'  => false,
        'message' => 'Some APIs failed',
        'data'    => $apiResponses
    ]);
}



     public function listajaxlist($req)
    {
        try {
            return $this->repo->listajaxlist($req['plant_id']);
        } catch (Exception $e) {
            Log::error("Department Service list error: " . $e->getMessage());
            return false;
        }
    }

    public function save(array $data)
    {
        try {
            $insertData = [
                'plant_id'        => $data['plant_id'],
                'department_code' => $data['department_code'],
                'department_name' => $data['department_name'],
                'created_by'      => $data['created_by'] ?? auth()->id(),
                'is_active'       => $data['is_active'] ?? 1,
            ];

            if (!empty($data['department_short_name'])) {
                $insertData['department_short_name'] = $data['department_short_name'];
            }

            return $this->repo->save($insertData);
        } catch (Exception $e) {
            Log::error("Department Service save error: " . $e->getMessage());
            return false;
        }
    }


    public function edit($id)
    {
        try {
            return $this->repo->edit($id);
        } catch (Exception $e) {
            Log::error("Department Service edit error: " . $e->getMessage());
            return false;
        }
    }

    public function update($req)
    {
        try {
            $id = $req->id;
            $currentUser = session('employee_user_name'); // from session

            // Fetch existing department record
            $existing = \DB::table('departments')->where('id', $id)->first();

            $data = [
                'plant_id'         => $req->input('plant_id'),
                'department_code'  => $req->input('department_code'),
                'department_name'  => $req->input('department_name'),
                'is_active'        => $req->is_active,
                'send_api' => 0
            ];

            // ✅ Always update created_by with the current logged-in user
            // (if empty or already has value — both cases)
            $data['created_by'] = $currentUser;

            // Optional field
            if ($req->filled('department_short_name')) {
                $data['department_short_name'] = $req->input('department_short_name');
            }

            return $this->repo->update($data, $id);
        } catch (Exception $e) {
            Log::error("Department Service update error: " . $e->getMessage());
            return false;
        }
    }



    // public function delete($req)
    // {
    //     try {
    //         $id = base64_decode($req->id);

    //         // Get department details to show name in message
    //         $department = $this->repo->edit($id); // assuming edit() returns department data
    //         $departmentName = $department->department_name ?? 'This department';

    //         // Check if any employee uses this department
    //         $employeeCount = \DB::table('employee_plant_assignments')
    //             // ->where('department_id', $id) // ✅ correct column
    //             ->whereRaw('JSON_CONTAINS(department_id, ?)', [json_encode((string)$id)])
    //             ->where('is_deleted', 0)
    //             ->count();

    //         if ($employeeCount > 0) {
    //             throw new \Exception("Cannot delete the department '{$departmentName}' because it is assigned to one or more employees.");
    //         }

    //         // If no employees use it, soft delete
    //         $data = ['is_deleted' => 1];
    //         return $this->repo->delete($data, $id);

    //     } catch (\Exception $e) {
    //         \Log::error("Department Service delete error: " . $e->getMessage());
    //         throw $e; // rethrow so controller can show message
    //     }
    // }

public function delete(Request $req)
{
    try {
        $id = base64_decode($req->id);

        $department = \DB::table('departments')
            ->join('plant_masters', 'departments.plant_id', '=', 'plant_masters.id')
            ->where('departments.id', $id)
            ->where('departments.is_deleted', 0)
            ->select(
                'departments.*',
                'plant_masters.plant_code',
                'plant_masters.plant_name'
            )
            ->first();

        if (!$department) {
            return redirect()->back()->with('error', 'Department not found.');
        }

        // employee mapping check
        $employeeCount = \DB::table('employee_plant_assignments')
            ->whereRaw('JSON_CONTAINS(department_id, ?)', [json_encode((string)$id)])
            ->where('is_deleted', 0)
            ->count();

        if ($employeeCount > 0) {
            return redirect()->back()->with(
                'error',
                "Department '{$department->department_name}' is assigned to employees."
            );
        }

        // call deleteDepartmentApi for each project
        $projectIds = array_filter(explode(",", $department->send_api_project_id ?? ""));

        $projects = \DB::table('projects')
            ->whereIn('id', $projectIds)
            ->where('is_active', 1)
            ->where('is_deleted', 0)
            ->get();

        foreach ($projects as $proj) {

            preg_match('#https?://[^/]+/([^/]+)/#', $proj->project_url, $m);
            $projectName = $m[1] ?? null;
            if (!$projectName) continue;

            \Http::post(
                "https://alfitworld.com/{$projectName}/CommonController/api_delete_department",
                [
                    "dept_code" => $department->department_code,
                    "plant"      => $department->plant_code
                ]
            );
        }

        // local soft delete
        \DB::table('departments')
            ->where('id', $id)
            ->update(['is_deleted' => 1]);

        return redirect()
            ->route('departments.list')
            ->with('success', "Department '{$department->department_name}' deleted successfully.");

    } catch (\Exception $e) {
        \Log::error("DepartmentService delete error: " . $e->getMessage());

        return redirect()->back()->with(
            'error',
            'Error deleting department: ' . $e->getMessage()
        );
    }
}


public function updateStatus(Request $request)
{
    try {
        $id = base64_decode($request->id);

        $department = \DB::table('departments')
            ->join('plant_masters', 'departments.plant_id', '=', 'plant_masters.id')
            ->where('departments.id', $id)
            ->where('departments.is_deleted', 0)
            ->select(
                'departments.*',
                'plant_masters.plant_code',
                'plant_masters.plant_name'
            )
            ->first();

        if (!$department) {
            return response()->json([
                'status' => false,
                'message' => 'Department not found'
            ]);
        }

        $is_active = $request->is_active ? 1 : 0;

        // 1️⃣ Local DB update
        \DB::table('departments')
            ->where('id', $id)
            ->update(['is_active' => $is_active]);

        // 2️⃣ Send API to assigned projects
        $projectIds = array_filter(explode(",", $department->send_api_project_id ?? ""));

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
                'dept_code' => $department->department_code,
                'plant'      => $department->plant_code, 
                'plant_name'      => $department->plant_name,
                'status'          => $is_active
            ];

            $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/api_update_department_status";

            try {
                $response = \Http::post($apiUrl, $payload);

                $apiResponses[] = [
                    'project_id'     => $proj->id,
                    'project_name'   => $projectName,
                    'api_url'        => $apiUrl,
                    'department'     => $department->department_name,
                    'dept_code' => $department->department_code,
                    'plant'      => $department->plant_code, 
                    'plant_name'      => $department->plant_name,
                    'status'         => $is_active,
                    'status_code'    => $response->status(),
                    'api_response'   => $response->json()
                ];

                if (!$response->successful()) {
                    $allSuccess = false;
                }

            } catch (\Exception $e) {
                $allSuccess = false;

                $apiResponses[] = [
                    'project_id'     => $proj->id,
                    'project_name'   => $projectName,
                    'api_url'        => $apiUrl,
                    'department'     => $department->department_name,
                    'department_code'=> $department->department_code,
                    'status'         => $is_active,
                    'status_code'    => 500,
                    'api_response'   => [
                        'status'  => false,
                        'message' => $e->getMessage()
                    ]
                ];
            }
        }

        return response()->json([
            'status'  => $allSuccess,
            'message' => "Department '{$department->department_name}' status updated successfully",
            'data'    => $apiResponses
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Error updating department status: ' . $e->getMessage()
        ]);
    }
}


    // public function updateStatus($req)
    // {
    //     try {
    //         $id = base64_decode($req->id);
    //         $data = ['is_active' => $req->is_active];

    //         return $this->repo->updateStatus($data, $id);
    //     } catch (Exception $e) {
    //         Log::error("Department Service updateStatus error: " . $e->getMessage());
    //         return false;
    //     }
    // }

}
