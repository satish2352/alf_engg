@extends('superadm.layout.master')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="row">
    <div class="col-lg-6 col-md-8 mx-auto">
        <div class="card">
            <div class="card-body">
                <h4>Add Employee Assignment</h4>
                <form action="{{ route('employee.assignments.save') }}" method="POST">
                    @csrf

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                <!-- Employee -->
                <div class="form-group">
                    <label>Employee <span class="text-danger">*</span></label>
                    <select name="employee_id" id="employee_select" class="form-control">
                        <option value="">Select Employee</option>
                        @foreach($employees as $emp)
                            <option value="{{ $emp->id }}" {{ old('employee_id') == $emp->id ? 'selected' : '' }}>
                                {{ $emp->employee_name }} - {{ $emp->role->role ?? 'No Role' }}
                            </option>
                        @endforeach
                    </select>
                    @error('employee_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Plant -->
                <div class="form-group">
                    <label>Plant <span class="text-danger">*</span></label>
                    <select name="plant_id" id="plant_id" class="form-control">
                        <option value="">Select Plant</option>
                        @foreach($plants as $plant)
                            <option value="{{ $plant->id }}" {{ old('plant_id') == $plant->id ? 'selected' : '' }}>
                                {{ $plant->plant_code }} - {{ $plant->plant_name }} 
                            </option>
                        @endforeach
                    </select>
                    @error('plant_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Projects (multi-select) -->
                <div class="form-group">
                    <label>Projects <span class="text-danger">*</span></label>
                    <select id="projects_id" name="projects_id[]" multiple class="form-control">
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}" {{ in_array($project->id, old('projects_id', [])) ? 'selected' : '' }}>
                                {{ $project->project_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('projects_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Departments (multi-select) -->
                <div class="form-group">
                    <label>Departments <span class="text-danger">*</span></label>
                    <select id="department_id" name="department_id[]" multiple class="form-control">
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}" {{ in_array($dept->id, old('department_id', [])) ? 'selected' : '' }}>
                                {{ $dept->department_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('department_id')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                    <div class="form-group d-flex justify-content-end">
                        <a href="{{ route('employee.assignments.list') }}" class="btn btn-secondary mr-2">Cancel</a>
                        <button type="submit" class="btn btn-success btn-add">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script> --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-multiselect/dist/css/bootstrap-multiselect.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap-multiselect/dist/js/bootstrap-multiselect.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(function() { // shorthand document.ready

    if (typeof $ === 'undefined') {
        console.error('jQuery not loaded!');
        return;
    }
    if (!$.fn.multiselect) {
        console.error('bootstrap-multiselect plugin NOT loaded.');
        return;
    }

    // Initialize empty multiselect
    $('#projects_id, #department_id').multiselect({
        includeSelectAllOption: true,
        enableFiltering: true,
        maxHeight: 250,
        buttonWidth: '100%',
        enableCaseInsensitiveFiltering: true
    });

    // start disabled
    $('#projects_id, #department_id').multiselect('disable');

    // AJAX helpers
    function loadProjects(plantId) {
        return $.ajax({
            url: "{{ route('projects.list-ajax') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", plant_id: plantId },
            dataType: "json"
        });
    }

    function loadDepartments(plantId) {
        return $.ajax({
            url: "{{ route('departments.list-ajax') }}",
            type: "POST",
            data: { _token: "{{ csrf_token() }}", plant_id: plantId },
            dataType: "json"
        });
    }

    // ON PLANT CHANGE
    $('#plant_id').off('change').on('change', function () {
        let plantId = $(this).val();
        console.log("plant changed ->", plantId);

        if (!plantId) {
            $('#projects_id, #department_id').multiselect('destroy');
            $('#projects_id').html('');
            $('#department_id').html('');
            $('#projects_id, #department_id').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                maxHeight: 250,
                buttonWidth: '100%',
                enableCaseInsensitiveFiltering: true
            });
            $('#projects_id, #department_id').multiselect('disable');
            return;
        }

        $('#projects_id, #department_id').multiselect('disable');

        $.when(loadProjects(plantId), loadDepartments(plantId))
            .done(function(projectResp, deptResp) {

                let projects = projectResp[0].projects ?? [];
                let departments = deptResp[0].department ?? deptResp[0].departments ?? [];

                console.log("Parsed projects:", projects);
                console.log("Parsed departments:", departments);

                // ----- RESET SELECTS -----
                $('#projects_id').multiselect('destroy');
                $('#department_id').multiselect('destroy');
                $('#projects_id').html('');
                $('#department_id').html('');

                // ----- ADD NEW OPTIONS -----
                projects.forEach(p => {
                    $('#projects_id').append(
                        `<option value="${p.id}">${p.project_name}</option>`
                    );
                });

                departments.forEach(d => {
                    $('#department_id').append(
                        `<option value="${d.id}">${d.department_name}</option>`
                    );
                });

                // ----- RE-INIT MULTISELECT -----
                $('#projects_id, #department_id').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    maxHeight: 250,
                    buttonWidth: '100%',
                    enableCaseInsensitiveFiltering: true
                });

                // enable if items exist
                if (projects.length > 0 || departments.length > 0) {
                    $('#projects_id, #department_id').multiselect('enable');
                }

                // restore old selected values
                let oldProjects = @json(old('projects_id', []));
                let oldDepartments = @json(old('department_id', []));

                if (oldProjects.length) {
                    $('#projects_id').multiselect('select', oldProjects);
                }
                if (oldDepartments.length) {
                    $('#department_id').multiselect('select', oldDepartments);
                }

                // alerts
                if (projects.length === 0 && departments.length === 0) {
                    Swal.fire('Required Fields Missing', 'Please add both Projects and Departments.', 'warning');
                } else if (projects.length === 0) {
                    Swal.fire('Projects Missing', 'Please add projects.', 'warning');
                } else if (departments.length === 0) {
                    Swal.fire('Departments Missing', 'Please add departments.', 'warning');
                }

            })
            .fail(function() {
                Swal.fire('Error', 'Failed to load data.', 'error');
            });
    });

    // Restore previous plant selection
    let oldPlant = "{{ old('plant_id') }}";
    if (oldPlant) {
        $('#plant_id').val(oldPlant).trigger('change');
    }

    // Select2
    $('#employee_select').select2({
        placeholder: 'Search Employee',
        allowClear: true
    });

});
</script>



{{-- <script>
$(document).ready(function() {
    $('#employee_select').select2({
        placeholder: 'Search Employee',
        allowClear: true
    });
});
</script> --}}

@endsection
