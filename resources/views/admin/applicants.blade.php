@extends('layouts.admin')
@section('applicants-active','active')
@section('applicants-active-triangle','triangle-nav')
@section('content')
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="#">Applicants/Employees</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        <li class="nav-item active">
            <button class="nav-link btn" type="button" data-toggle="modal" data-target="#modalCreateCompany">Pending Registration Requests</button>
            
        </li>
        </ul>
        <form class="form-inline">
            <div class="input-group">
              
              <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
              <div class="input-group-prepend">
                <button class="btn input-group-text">
                    <i class="fa fa-search"></i>
                </button>
              </div>
            </div>
          </form>
    </div>
</nav>
<div class="jumbotron">
    <h3>Ordering and Sorting Contents</h3>
    <div class="row" >
        <div class="col-lg-6 offset-lg-3  ">
            <div class="input-group">
                        <div class="input-group-append mr-2">
                            <h3 class="text-muted">keyword </h3>
                        </div>
                        <input class="form-control "  type="search"  placeholder="Search username or number" aria-label="Search">
                         
            </div>
        </div>
        <div class="col-lg-6 ">
            <p>Sort By: </p>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Authority</label>
                <div class="col-sm-10">
                    <select  class="form-control" >
                        <option value='0' selected>none</option>
                        <option value='1'>Admin</option>
                        <option value='2'>MasterAdmin</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Authority</label>
                <div class="col-sm-10">
                    <select  class="form-control" >
                        <option value='0' selected>none</option>
                        <option value='1'>Admin</option>
                        <option value='2'>MasterAdmin</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <select  class="form-control" >
                        <option value='0' selected>none</option>
                        <option value='1'>Admin</option>
                        <option value='2'>MasterAdmin</option>
                    </select>
                </div>
            </div>
          
                   
            
        </div>
        <div class="col-lg-6">
            <p>Order By: </p>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Authority</label>
                <div class="col-sm-10">
                    <select  class="form-control" >
                        <option value='0' selected>none</option>
                        <option value='1'>Admin</option>
                        <option value='2'>MasterAdmin</option>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label for="staticEmail" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    <select  class="form-control" >
                        <option value='0' selected>none</option>
                        <option value='1'>Admin</option>
                        <option value='2'>MasterAdmin</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="col-lg-12 form-row">
        <button class="btn btn-secondary btn-sm">Remove Filters</button>
        </div>
    </div>
</div>
    <admins-companies-create></admins-companies-create>
    <admins-companies />
    
@endsection