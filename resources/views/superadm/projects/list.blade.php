 <nav class="sidebar-nav">
     <ul id="sidebarnav">

         <li> <a href="{{ route('dashboard') }}">  <i class="mdi mdi-view-dashboard"></i><span>Dashboard
                 </span></a>
         </li>

         <!--    <li> <a href="index.html"><i class="mdi mdi-gauge"></i><span >System Setup </span></a>
                        </li> -->


         <li> <a href="{{ route('roles.list') }}"><i class="mdi mdi-account-key"></i><span>
                     Role</span></a>
         </li>

         <li> <a href="{{ route('designations.list') }}"><i class="mdi mdi-badge-account"></i><span>
                     Designations</span></a>
         </li>
        <li>
            <a href="{{ route('financial-year.list') }}">
                <i class="mdi mdi-calendar"></i>
                <span>Financial Years</span>
            </a>
        </li>
        <li> 
            <a href="{{ route('employee-types.list') }}"><i class="mdi mdi-account-circle"></i><span>Employee Types</span></a>
        </li>

         <li> <a href="{{ route('plantmaster.list') }}"> <i class="mdi mdi-factory"></i><span>
                     Plants</span></a>
         </li>

         <li> <a href="{{ route('projects.list') }}"> <i class="mdi mdi-briefcase"></i><span>
                     Projects</span></a>
         </li>

         <li> <a href="{{ route('departments.list') }}"> <i class="mdi mdi-office-building"></i><span>
                     Departments</span></a>
         </li>

         <li> <a href="{{ route('employees.list') }}"> <i class="mdi mdi-account-group"></i><span>
                     Employees</span></a>
         </li>
                <li> <a href="{{ route('employee.assignments.list') }}"> 
                    <i class="mdi mdi-account-switch"></i><span>Assign Plants</span>
                </a></li>
         </li>

        <li> 
            @if(session('role') == 'admin')
                <a href="{{ route('admin.logout') }}"> <i class="mdi mdi-logout"></i><span>Logout</span></a>
            @else
                <a href="{{ route('emp.logout') }}"> <i class="mdi mdi-logout"></i><span>Logout</span></a>
            @endif
        </li>

     </ul>
 </nav>
