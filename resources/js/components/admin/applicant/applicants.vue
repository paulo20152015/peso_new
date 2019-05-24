<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand" href="#">Applicants/Employees</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <button class="nav-link btn" :class='{"bot-triangle-nav":is_approved==0}' type="button" @click="approvedFilter(0)">Pending</button>
                </li>
                <li class="nav-item active">
                    <button class="nav-link btn" :class='{"bot-triangle-nav":is_approved==1}' type="button" @click="approvedFilter(1)">Approved</button>
                </li>
                <li class="nav-item active">
                    <button class="nav-link btn" :class='{"bot-triangle-nav":is_approved==2}' type="button" @click="approvedFilter(2)">Rejected</button>
                </li>
                </ul>
            </div>
        </nav>
        <div class="jumbotron">
            <div class="row" >
                <div class="col-lg-6 offset-lg-3  ">
                    <div class="input-group">
                        <div class="input-group-append mr-2">
                            <h3 class="text-muted">keyword </h3>
                        </div>
                        <input class="form-control "  type="search" @keyup='getApplicants()' @keyup.delete="getApplicants()"  @keyup.ctrl='getApplicants()' @keyup.enter='getApplicants()' v-model='search' placeholder="username,email, or last name" aria-label="Search">     
                    </div>
                </div>
                <div class="col-lg-6 ">
                    <p>Order By: </p>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Last Login :</label>
                        <div class="col-sm-10">
                            <select class="form-control" v-model='orderFilter[1].value'  @change="getApplicants()">
                                <option value='0' selected>none</option>
                                <option value='2'>Recent</option>
                                <option value='1'>Oldest</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Date :</label>
                        <div class="col-sm-10">
                            <select class="form-control" v-model='orderFilter[2].value'  @change="getApplicants()">
                                <option value='0' selected>none</option>
                                <option value='2'>Recent</option>
                                <option value='1'>Oldest</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <p> </p><br>
                    <div class="form-group row">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Username :</label>
                        <div class="col-sm-10">
                            <select class="form-control" v-model='orderFilter[0].value'  @change="getApplicants()">
                                <option value='0' selected>none</option>
                                <option value='1'>Ascending</option>
                                <option value='2'>Descending</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 form-row">
                <button class="btn btn-secondary btn-sm" @click="reset()">Remove Filters</button>
                </div>
            </div>
        </div>
        <div class="col-lg-10 offset-lg-1 col-md-8 offset-md-2">
        <div class="card m-1 ">
        <p class="text-muted p-2"><small>Results : {{total}}</small></p>
        <div class="progress">
            <div class="progress-bar" :style='{width:(links.current_page*100)/links.last_page +"%"}' role="progressbar" :aria-valuenow="links.current_page" aria-valuemin="0" :aria-valuemax="links.last_page">
                Page {{links.current_page+' of '+links.last_page}}
            </div>
        </div>    
        <div class="row border border-dark m-2 p-2" v-for="(applicant,index) in applicants" :key='"applicant"+index'>   
            <div class="col-lg-3">
                <small class="text-muted">created {{humanTime(applicant.created_at)}}</small><br>
            <img :src="test_array(applicant.applicant_files) == true? applicant.applicant_files[0].url_name:'/img/unkown.png'" class="mr-3" alt="..." height="100" width="100">
            </div>
            <div class="col-lg-9">
                <p><a target="__blank"  :href="'/admin/applicant/'+applicant.id"> Username : {{applicant.username}}<br>
                    Fullname : {{applicant.personal_data.lname}},{{applicant.personal_data.fname}} {{applicant.personal_data.mname}}<br>
                    email: {{applicant.personal_data.email}}</a><br>
                    contact number: {{applicant.personal_data.contact_num}}<br>
                    gender: {{applicant.personal_data.gender == 0?'Male':'Female'}}<br>
                    Age: {{getAge(applicant.personal_data.date_of_birth)}}<br>
                    <small class="text-muted">last login : {{humanTime(applicant.last_log)}}</small>
                </p>
                <p class="text-right ">
                    <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                    <a :href="'/admin/applicant/'+applicant.id" target="__blank" class="btn btn-primary">view</a>
                    <button v-if="is_approved == 0" :disabled='disable ==1' @click="approveOrReject(applicant.personal_data.lname+' '+applicant.personal_data.fname,'approved',applicant.id)" class="btn btn-success">Approve</button>
                    <button v-if="is_approved == 0" :disabled='disable ==1' class="btn btn-danger" @click="approveOrReject(applicant.personal_data.lname+' '+applicant.personal_data.fname,'reject',applicant.id)">Reject</button>
                    <button v-if="is_approved == 2" :disabled='disable ==1' class="btn btn-warning" @click="approveOrReject(applicant.personal_data.lname+' '+applicant.personal_data.fname,'return to pending',applicant.id)">Return to pending</button>
                    <button class="btn btn-warning" :disabled='disable ==1' @click="archive(applicant.personal_data.lname+' '+applicant.personal_data.fname,applicant.id)">{{archiveapplicant == 1?'recover':'archive'}}</button>
                </p>
            </div>
        </div>     
        <applicants-pagination-component :links='links' />
        </div>
        </div> 
    </div>
</template>
<script>
export default {
    props:['archiveapplicant'],
    data(){
        return {
            spinner:'',
            applicants:[],
            search:'',
            current_page:'',
            total:'',
            disable:'',
            links:{
                first_page:'',
                last_page_url:'',
                prev_page:'',
                next_page:'',
                current_page:'',
                last_page:'',
                path:'', 
            },
            is_approved:0
            ,
            orderFilter:[
                {collumn:'username',value:''},
                {collumn:'last_log',value:''},
                {collumn:'created_at',value:''}
            ],
        }
    },
    mounted(){
        this.getApplicants();
    },
    methods:{
        getAge(date){
            return moment().diff(date, 'years');
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        test_array(totest){
            let flag = Array.isArray(totest) && totest.length > 0;
            return flag;
        }
        ,
        approvedFilter(val){
            this.is_approved = val;
            this.getApplicants();
        },
        humanTime:function(dateAndTime){
                    return moment(dateAndTime).fromNow();
        },
        getApplicants(url='/admin/applicants'){
            let vm = this;
            vm.current_page = url;
            let orders = vm.orderFilter;
            axios.post(url,{
                order:orders,
                search:vm.search,
                is_approved:vm.is_approved,
                arch:vm.archiveapplicant
            })
            .then(function(res){
                vm.applicants = res.data.data;
                vm.links.first_page = res.data.first_page_url;
                vm.links.last_page_url = res.data.last_page_url;
                vm.links.prev_page = res.data.prev_page_url;
                vm.links.next_page = res.data.next_page_url;
                vm.links.current_page = res.data.current_page;
                vm.links.last_page = res.data.last_page;
                vm.links.path = res.data.path;
                vm.total = res.data.total;
                console.log(res.data.data);
            }).catch(function(err){
                console.log(err.data);
            });
        },
        reset(){
            this.search = '';
            this.orderFilter[0].value=0;
            this.orderFilter[1].value=0;
            this.orderFilter[2].value=0;
            this.getApplicants();
        },
        archive:function(applicant,id){
            let action = this.archiveapplicant == 1?'recover':'archive';
            swal({
                title: "Are you sure?",
                text: "your about to "+action+" "+applicant+"!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                let vm = this;
                vm.disable = 1;
                vm.spinLoading();
                axios.patch('/admin/applicant-archive/'+id)
                .then(function(res){
                    if(res.data.result == 'success'){
                        vm.getApplicants(vm.current_page);
                    }
                    vm.disable = '';
                    vm.spinLoading();
                    console.log(res.data);
                }).catch(function(error){
                    vm.disable = '';
                    vm.spinLoading();
                    console.log(error.response);
                });
                swal('success', {
                icon: "success",
                });
            } else {
                swal("Aborted");
            }
            });
        },
        approveOrReject:function(applicant,action,id){
            swal({
                title: "Are you sure?",
                text: "your about to "+action+" "+applicant+"!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                let vm = this;
                vm.disable = 1;
                vm.spinLoading();
                axios.patch('/admin/applicant-ApprovedOrReject/'+id,{
                    todo:action
                })
                .then(function(res){
                    if(res.data.result == 'success'){
                        vm.getApplicants(vm.current_page);
                    }
                    vm.disable = '';
                    vm.spinLoading();
                    swal("Success", res.data.sms_status,"success");
                    console.log(res.data);
                }).catch(function(error){
                    vm.disable = '';
                    vm.spinLoading();
                    console.log(error.response);
                });
                
            } else {
                swal("Aborted");
            }
            });
        },
    }
}
</script>
