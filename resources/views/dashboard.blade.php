<!DOCTYPE html>
<html>
<head>
<title>Dashboard - Tutsmake.com</title>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
<meta name="description" content="">
<meta name="author" content="">
<meta name="csrf-token" content="{{ csrf_token() }}">
<!--Bootsrap 4 CDN-->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/dashboard.js') }}"></script>

</head>
<body handleServer='{{route("handle.server")}}' handleServerDelete='{{route("handle.server.delete")}}'>
    <div class="container-fluid">
        <div class="row no-gutter">
            <!-- <div class="d-none d-md-flex col-md-4 col-lg-6 bg-image"></div> -->
            <div class="col-md-12 col-lg-12">
                <div class="login d-flex align-items-center py-5">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 col-lg-12 mx-auto">
                                <h3 class="login-heading mb-4">Welcome {{ $name }}! <a class="small" href="{{url('logout')}}">Logout</a></h3>
                                <div class="card">
                                    <div class="card-body">
                                    Server List
                                    </div>
                                    <div class="card-body col-md-12 col-lg-12 container">
                                        <div class="row" style='background-color: LightGray; text-align:center; border-bottom: 2px solid black;'>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                Name
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                Location
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                IPV4
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                Status
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                Last Update
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                Action
                                            </div>
                                        </div>
                                        <!-- Template -->
                                        <div class="row" style='text-align:center; border-bottom: 2px solid black; display:none' server-id>
                                            <div class="col-md-2 col-lg-2 py-3" fieldName='name'>
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-3" fieldName='location'>
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-3" fieldName='ipv4'>
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-3" fieldName='status'>
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-3" fieldName='updated_at'>
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-3">
                                                <button class="btn btn-warning" type="submit" onclick='handleDelete(this)'>Delete</button>
                                            </div>
                                        </div>
                                        <!-- End Template -->
                                        @foreach($servers as $server)
                                            <div class="row" style='text-align:center; border-bottom: 2px solid black;' server-id='{{ $server->id }}'>
                                                <div class="col-md-2 col-lg-2 py-3" fieldName='name'>
                                                    {{ $server->name }}
                                                </div>
                                                <div class="col-md-2 col-lg-2 py-3" fieldName='location'>
                                                    {{ $server->location }}
                                                </div>
                                                <div class="col-md-2 col-lg-2 py-3" fieldName='ipv4'>
                                                    {{ $server->ipv4 }}
                                                </div>
                                                <div class="col-md-2 col-lg-2 py-3" fieldName='status'>
                                                    {{ $server->status }}
                                                </div>
                                                <div class="col-md-2 col-lg-2 py-3" fieldName='updated_at'>
                                                    {{ $server->updated_at }}
                                                </div>
                                                <div class="col-md-2 col-lg-2 py-3">
                                                    <button class="btn btn-warning" type="submit" onclick='handleDelete(this)'>Delete</button>
                                                </div>
                                            </div>
                                        @endforeach
                                        
                                        <div class="row" style='text-align:center; border-bottom: 2px solid black;'>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                <input class="form-control" maxlength="50" placeholder="max length: 50" name='name' id='name' />
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                <input class="form-control" maxlength="50" placeholder="max length: 50" name='location' id='location' />
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                <input class="form-control" maxlength="16" placeholder="XXX.XXX.XXX.XXX" name='ipv4' id='ipv4' />
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2">
                                                <select class="form-control"  name='status' id='status'>
                                                    <option value='Up'>Up</option>
                                                    <option value='Down'>Down</option>
                                                    <option value='Maintenance'>Maintenance</option>
                                                </select>
                                            </div>
                                            <div class="col-md-2 col-lg-2 py-2 offset-2">
                                            <button class="btn btn-primary" type="submit" onclick='handleSave(this)'>Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>