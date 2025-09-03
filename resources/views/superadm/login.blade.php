<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
        <meta http-equiv="Pragma" content="no-cache">
        <meta http-equiv="Expires" content="0">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('asset/theamoriginalalf/images/logo_bg1.ico') }}">
          
   <title> ALF </title>
   
   <link id="pagestyle" href="{{ asset('asset/theamoriginalalf/css/soft-ui-dashboard.css?v=1.0.3')}}" rel="stylesheet" />
 
   <!-- DataTables-->        
        
    </head>
    <body style="background-image: url('{{ asset('asset/theamoriginalalf/images/bg_color2.jpg') }}'); 
             background-size: cover; 
             background-repeat: no-repeat; 
             background-position: center center;">

        <div class="container position-sticky z-index-sticky top-0">
            <div class="row">
                <div class="col-12">                 

                </div>
            </div>
        </div>
        <main class="main-content  mt-0">
            <section>
                <div class="page-header min-vh-75" style="padding-bottom: 40px; margin: 0px;">
                    <div class="container">

                            <div class="row">
                            <div class="col-sm-8">
                                <style>
                                  .reflected-text {
                                    display: inline-block;
                                    position: relative;
                                    color: #333; /* Change the color of the text */
                                    font-family: Arial, sans-serif; /* Set the font family */
                                  }
                                  .reflected-text:after {
                                    content: attr(data-text);
                                    position: absolute;
                                    top: 100%;
                                    left: 0;
                                    transform: scaleY(-1);
                                    color: #999; /* Change the color of the reflection */
                                    margin-top: -30px; /* Adjust this value to remove the space */
                                    opacity: 0.1; /* Set the opacity for a transparent reflection */
                                  }
                                </style>
                                 <div class="row">
                                    <div class="col-sm-12"> 

                                         <div class="reflected-text" style="font-size: 58px;width: 100%;color: white;margin-top: 42%;" data-text="ALF ENGINEERING PVT LTD">ALF ENGINEERING PVT LTD</div>

                                    </div>
                                    <div class="col-sm-12"> 
                                    </div>
                                </div>
                                <div class="row">
                                    
                                </div>
                            </div>
                            <div class="col-sm-4 d-flex flex-column mx-auto">
                                <div class="card card-plain mt-8">
                                    <div class="card-header pb-0 text-left bg-transparent text-center">
                                         
                                        <img src="{{ asset('asset/theamoriginalalf/images/logo_bg1.ico') }}" style="height: 80px;width:30%;">

                                       
                                    </div>
                                 
                                    <div class="card-body">

                                       @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                                                                                
                                            <form class="form-horizontal form-material" method="POST" id="loginform"
                        action="{{ route('superlogin') }}">
                        @csrf
                                            <label>User name</label>
                                            <div class="mb-3">
                                                <input type="text" id="superemail" name="superemail" value="" class="form-control" placeholder="User" aria-label="user" aria-describedby="email-addon">
                                            </div>
                                            <label>Password</label>
                                            <div class="mb-3">
                                                <input type="password" id="superpassword" name="superpassword" value="" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                                            </div>

                                            
                                            <div class="text-center">
                                                <button type="submit" class="btn bg-gradient-info w-100 mt-4 mb-0">Sign in</button>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                        <p class="mb-4 text-sm mx-auto">
                                            Don't have an account?
                                            <a href="javascript:void(0);" class="text-info text-gradient font-weight-bold">Sign up</a>
                                        </p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    
                    </div>
                </div>
            </section>
        </main>
                
    </body>
</html>