<template>
    <div class="container">
        <div class="row">
        <div class="col-lg-12">
            <div class="card bg-light">
                <div class="card-header text-center text-white bg-dark p-1">
                  
                </div>
                <div class="card-body p-1 pr-3 pl-3">
                    <div class="row">
                        <div class="col-lg-6">
                                <p><strong> {{job_post.title}}</strong><br><img v-for="(img,index) in logo" :key="index" :src="img.name" style='max-width:100px;max-height:100px;' class="align-self-start img-fluid img-thumbnail" alt="blank"> <br> <a class="badge badge-info" :href="'/applicant/company/'+company_account.id" target="__blank">{{company_detail.name}}</a><br> 
                                <strong>  <i class="fa fa-envelope-o" aria-hidden="true"></i> {{company_detail.email}} </strong><br>
                                </p> 
                        </div>
                        <div class="col-lg-6">
                            <p>
                                <strong>  <i class="fa fa-mobile" aria-hidden="true"></i> {{company_detail.contact_number}} </strong><br>
                                <strong title="work experienced">  <i class="fa fa-briefcase" aria-hidden="true"></i> 3 years</strong><br>
                                <strong>  <i class="fa fa-map-marker" aria-hidden="true"></i> {{town.town}}, {{city.city}}</strong><br>
                                <small>{{humanTime(job_post.created_at)}}</small> 
                                
                            </p> 
                            <div v-if="employment_status == 0">
                            <p  v-for="(application,index) in job_application" :key="'apply'+index" class="text-right">
                                <span class="badge badge-pill badge-info p-2">Status : {{application.status}}</span> 
                                <button :disabled='disable == 1' v-if="application.is_approved ==1" class="btn btn-danger" @click="cancelApplication()"><i  v-if='spinner == 1'><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Cancel Application</button>
                            </p>
                            <p v-if="test_array(job_application) == false" class="text-right"><button :disabled='disable ==1' class="btn btn-success" @click="apply()"><i  v-if='spinner == 1'><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Apply now</button></p>
                            </div>
                            <div v-else class="alert alert-warning alert-dismissible fade show" role="alert">
                                <strong>Your currently employed</strong>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
              </div>
        </div>
        <div class="col-lg-3">
            <div class="card text-muted">
                <div class="card-header text-white bg-secondary">
                   <strong>Company Details</strong> 
                </div>
                <div class="card-body">
                  <p>
                       <i class="fa fa-phone-square" aria-hidden="true"></i> <strong title="landline number">{{company_detail.land_line}} </strong><br> 
                       <a :href="company_detail.website"><strong>{{company_detail.website}} </strong></a><br> 
                       <i class="fa fa-users" aria-hidden="true"></i> company size : <strong>{{company_detail.company_size}} </strong><br>         
                  </p> 
                </div>
            </div>
            <div class="card text-muted">
                <div class="card-header text-white bg-secondary">
                   <strong>Work location</strong> 
                </div>
                <div class="card-body">
                  <i class="fa fa-building"></i> {{job_post.full_address}}
                </div>
            </div>
        </div>
        <div class="col-lg-9">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header text-white bg-dark">
                        <strong>Job Specializations</strong> 
                    </div>
                    <div class="card-body">
                         <strong v-for="(specialization) in job_post_specializations" :key="'index'+specialization.id" class="badge badge-primary m-2 p-1">{{specialization.specialization.specialization}}</strong> 
                    </div>
                  </div>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-header text-white bg-info">
                            <strong>Job Description</strong> 
                        </div>
                            <div class="p-4" v-html="job_post.job_description">

                            </div>
                        <div class="card-header text-white bg-info">
                            <strong>Job Requirements</strong> 
                        </div>
                        <div class="p-4" v-html="job_post.job_requirements">

                            </div>
                    </div>
                  </div>
            </div>
        </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            company_detail:'',
            company_account:'',
            job_post:'',
            job_post_specializations:'',
            job_application:'',
            logo:'',
            town:'',
            city:'',
            disable:'',
            spinner:'',
            employment_status:'',
        }
    },
    mounted(){
        this.checkEmployment();
        this.getJobPost();
    },
    methods:{
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        test_array(totest){
            let flag = Array.isArray(totest) && totest.length > 0;
            return flag;
        },
        apply(){
            swal({
            text: "Click Yes to confirm and continue your application!",
            icon: "info",
            buttons: [true, "Yes"],
            dangerMode: false,
            })
            .then((willDelete) => {
            if (willDelete) {
                let vm = this;
                vm.disable = 1;
                vm.spinLoading();
                axios.post('/applicant/createJobApplication/'+vm.job_post.id)
                .then(function(res){
                    
                    vm.getJobPost();
                    vm.disable = '';
                    vm.spinLoading();
                    swal(res.data.message,res.data.sms,'success');
                    console.log(res);
                }).catch(function(error){
                    vm.disable = '';
                    vm.spinLoading();
                    console.log(error.response);
                });
            } else {
                swal("",'application has been cancelled!','warning');
            }
            });
        }
        ,
        cancelApplication(){
            swal({
            text: "Click Yes to cancel your application!",
            icon: "info",
            buttons: [true, "Yes"],
            dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                let vm = this;
                vm.disable = 1;
                vm.spinLoading();
                axios.post('/applicant/deleteJobApplication/'+vm.job_post.id)
                .then(function(res){
                    vm.getJobPost();
                    vm.disable = '';
                    vm.spinLoading();
                    swal(res.data.message,res.data.sms,'warning');
                    console.log(res);
                }).catch(function(error){
                    vm.disable = '';
                    vm.spinLoading();
                    console.log(error.response);
                });
                
            } else {
                swal("",'application cancellation has been cancelled!','warning');
            }
            });
        }
        ,
        getJobPost(){
            let vm = this;
            axios.post('/applicant/post')
            .then(function(res){
                vm.company_detail = res.data.company_details;
                vm.company_account = res.data.company_account;
                vm.job_post = res.data.job_post;
                vm.job_post_specializations = res.data.job_specializations;
                vm.job_application = res.data.job_application;
                vm.logo = res.data.company_logo;
                vm.town = res.data.town;
                vm.city = res.data.town.city;
                vm.checkEmployment();
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            });
        },
        humanTime:function(dateAndTime){
            return moment(dateAndTime).fromNow();
        },
        checkEmployment(){
            let vm = this;
            axios.post('/applicant/employmentStat')
                .then(function(res){
                    vm.employment_status = res.data;
                    console.log(res);
                }).catch(function(error){
                    
                    console.log(error.response);
                });
        }
    }
}
</script>
