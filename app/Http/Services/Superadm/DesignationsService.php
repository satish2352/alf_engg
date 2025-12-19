<?php
namespace App\Http\Services\Superadm;

use Illuminate\Http\Request;
use App\Http\Repository\Superadm\DesignationsRepository;
use Exception;
use Log;

class DesignationsService
{
    protected $repo;

    public function __construct()
    {
        $this->repo = new DesignationsRepository();
    }

    public function list()
    {
        try {
            return $this->repo->list();
        } catch (Exception $e) {
            Log::error("Designation Service list error: " . $e->getMessage());
            return false;
        }
    }

    public function sendApi($request)
    {
        $designation = \DB::table('designations')
            ->where('id', $request->id)
            ->where('is_deleted', 0)
            ->first();

        if (!$designation) {
            return response()->json([
                'status' => false,
                'message' => 'Designation not found'
            ]);
        }

        $newProjects = $request->projects;
        $oldProjects = explode(",", $designation->send_api_project_id ?? "");

        // =====================================
        // STEP 1: Identify REMOVED Projects
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
                "https://alfitworld.com/{$projectName}/CommonController/api_update_designation_status",
                [
                    "desg_name" => $designation->designation,
                    "status" => 0
                ]
            );
        }

        // =====================================
        // STEP 2: SEND Designation to SELECTED Projects
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
                "desg_name"       => $designation->designation,
                "short_description" => $designation->short_description
            ];

            $apiUrl   = "https://alfitworld.com/{$projectName}/CommonController/addEditDesignationApi";
            $response = \Http::post($apiUrl, $payload);

            // SAME STRUCTURE AS ROLE
            $apiResponses[] = [
                'project_id'        => $proj->id,
                'project_name'      => $projectName,
                'api_url'           => $apiUrl,
                'desg_name'       => $designation->designation,
                'short_description' => $designation->short_description,
                'status_code'       => $response->status(),
                'api_response'      => $response->json()
            ];

            if (!$response->successful()) {
                $allSuccess = false;
            }
        }

        // =====================================
        // STEP 3: UPDATE LOCAL DB
        // =====================================
        if ($allSuccess) {

            \DB::table('designations')
                ->where('id', $designation->id)
                ->update([
                    'send_api' => 1,
                    'send_api_project_id' => implode(",", $newProjects)
                ]);

            return response()->json([
                'status'  => true,
                'message' => 'Designation data sent successfully',
                'data'    => $apiResponses
            ]);
        }

        return response()->json([
            'status'  => false,
            'message' => 'Some APIs failed',
            'data'    => $apiResponses
        ]);
    }


    public function save($req)
    {
        try {
            $data = ['designation' => $req->input('designation'), 'designation_code' => $req->designation_code, 'short_description' => $req->input('short_description')];
            return $this->repo->save($data);
        } catch (Exception $e) {
            Log::error("Designation Service save error: " . $e->getMessage());
            return false;
        }
    }

    public function edit($id)
    {
        try {
            return $this->repo->edit($id);
        } catch (Exception $e) {
            Log::error("Designation Service edit error: " . $e->getMessage());
            return false;
        }
    }

    public function update($req)
    {

        try {
            $id = $req->id;
            $data = [
                'designation' => $req->designation,
                'short_description' => $req->short_description,
                'designation_code' => $req->designation_code,
                'is_active' => $req->is_active,
                'send_api' => 0
            ];

            return $this->repo->update($data, $id);
        } catch (Exception $e) {
            Log::error("Designation Service update error: " . $e->getMessage());
            return false;
        }
    }


//     public function delete($req)
// {
//     try {
//         $id = base64_decode($req->id);

//         // Get designation name
//         $designation = $this->repo->edit($id); // assuming edit() returns designation details
//         $designationName = $designation->designation ?? 'This designation';

//         // Check if any employee has this designation assigned
//         $employeeCount = \DB::table('employees')
//             ->where('designation_id', $id)
//             ->where('is_deleted', 0)
//             ->count();

//         if ($employeeCount > 0) {
//             // Designation is assigned to employees, cannot delete
//             throw new Exception("Cannot delete the designation '{$designationName}' because it is assigned to one or more employees.");
//         }

//         // Soft delete designation
//         $data = ['is_deleted' => 1];
//         return $this->repo->delete($data, $id);

//     } catch (Exception $e) {
//         \Log::error("DesignationsService delete error: " . $e->getMessage());
//         throw $e; // re-throw so controller can catch and show message
//     }
// }

public function delete(Request $req)
{
    try {
        $id = base64_decode($req->id);

        $designation = \DB::table('designations')
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first();

        if (!$designation) {
            return redirect()->back()->with('error', 'Designation not found.');
        }

        // check if designation assigned to employees
        $employeeExists = \DB::table('employees')
            ->where('designation_id', $id)
            ->where('is_deleted', 0)
            ->exists();

        if ($employeeExists) {
            return redirect()->back()->with(
                'error',
                "Designation '{$designation->designation}' is assigned to employees."
            );
        }

        // call deleteDesignationApi for each assigned project
        $projectIds = array_filter(explode(",", $designation->send_api_project_id ?? ""));

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
                "https://alfitworld.com/{$projectName}/CommonController/api_delete_designation",
                [
                    "desg_name" => $designation->designation
                ]
            );
        }

        // local soft delete
        \DB::table('designations')
            ->where('id', $id)
            ->update(['is_deleted' => 1]);

        return redirect()
            ->route('designations.list')
            ->with('success', "Designation '{$designation->designation}' deleted successfully.");

    } catch (\Exception $e) {
        \Log::error("DesignationsService delete error: " . $e->getMessage());

        return redirect()->back()->with(
            'error',
            'Error deleting designation: ' . $e->getMessage()
        );
    }
}



public function updateStatus(Request $request)
{
    try {
        $id = base64_decode($request->id);

        $designation = \DB::table('designations')
            ->where('id', $id)
            ->where('is_deleted', 0)
            ->first();

        if (!$designation) {
            return response()->json([
                'status' => false,
                'message' => 'Designation not found'
            ]);
        }

        $is_active = $request->is_active ? 1 : 0;

        // 1️⃣ Local DB update
        \DB::table('designations')
            ->where('id', $id)
            ->update(['is_active' => $is_active]);

        // 2️⃣ Send API to projects
        $projectIds = array_filter(explode(",", $designation->send_api_project_id ?? ""));

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
                'desg_name' => $designation->designation,
                'status'      => $is_active
            ];

            $apiUrl = "https://alfitworld.com/{$projectName}/CommonController/api_update_designation_status";

            try {
                $response = \Http::post($apiUrl, $payload);

                $apiResponses[] = [
                    'project_id'   => $proj->id,
                    'project_name' => $projectName,
                    'api_url'      => $apiUrl,
                    'designation'  => $designation->designation,
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
                    'designation'  => $designation->designation,
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
            'message' => "Designation '{$designation->designation}' status updated successfully",
            'data'    => $apiResponses
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'status' => false,
            'message' => 'Error updating status: ' . $e->getMessage()
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
    //         Log::error("Designation Service updateStatus error: " . $e->getMessage());
    //         return false;
    //     }
    // }



    public function find($id)
    {
        try {
            return $this->repo->edit($id); // reuse existing edit() method
        } catch (Exception $e) {
            \Log::error("DesignationsService find error: " . $e->getMessage());
            return null;
        }
    }


}
