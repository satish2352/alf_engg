<nav class="sidebar-nav">
    <ul id="sidebarnav">

        <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <a href="{{ route('dashboard') }}">
                <i class="mdi mdi-view-dashboard"></i>
                <span>Dashboard</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('roles/list') || request()->is('roles/add') || request()->is('roles/edit/*') ? 'active' : '' }}">
            <a href="{{ route('roles.list') }}">
                <i class="mdi mdi-account-key"></i>
                <span>Role</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('designations/list') || request()->is('designations/add') || request()->is('designations/edit/*') ? 'active' : '' }}">
            <a href="{{ route('designations.list') }}">
                <i class="mdi mdi-badge-account"></i>
                <span>Designations</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('financial-year/list') || request()->is('financial-year/add') || request()->is('financial-year/edit/*') ? 'active' : '' }}">
            <a href="{{ route('financial-year.list') }}">
                <i class="mdi mdi-calendar"></i>
                <span>Financial Years</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('employee-types/list') || request()->is('employee-types/add') || request()->is('employee-types/edit/*') ? 'active' : '' }}">
            <a href="{{ route('employee-types.list') }}">
                <i class="mdi mdi-account-circle"></i>
                <span>Employee Types</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('plantmaster/list') || request()->is('plantmaster/add') || request()->is('plantmaster/edit/*') ? 'active' : '' }}">
            <a href="{{ route('plantmaster.list') }}">
                <i class="mdi mdi-factory"></i>
                <span>Plant</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('projects/list') || request()->is('projects/add') || request()->is('projects/edit/*') ? 'active' : '' }}">
            <a href="{{ route('projects.list') }}">
                <i class="mdi mdi-briefcase"></i>
                <span>Projects</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('departments/list') || request()->is('departments/add') || request()->is('departments/edit/*') ? 'active' : '' }}">
            <a href="{{ route('departments.list') }}">
                <i class="mdi mdi-office-building"></i>
                <span>Departments</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('employees/list') || request()->is('employees/add') || request()->is('employees/edit/*') ? 'active' : '' }}">
            <a href="{{ route('employees.list') }}">
                <i class="mdi mdi-account-group"></i>
                <span>Employees</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('employee/assignments/list') || request()->is('employee/assignments/add') || request()->is('employee/assignments/edit/*') ? 'active' : '' }}">
            <a href="{{ route('employee.assignments.list') }}">
                <i class="mdi mdi-account-switch"></i>
                <span>Assign Plant</span>
            </a>
        </li>

        <li class="nav-item">
            @if(session('role') == 'admin')
                <a href="{{ route('emp.logout') }}">
                    <i class="mdi mdi-logout"></i>
                    <span>Logout</span>
                </a>
            @else
                <a href="{{ route('emp.logout') }}">
                    <i class="mdi mdi-logout"></i>
                    <span>Logout</span>
                </a>
            @endif
        </li>

    </ul>
</nav>
