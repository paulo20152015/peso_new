<template>
    <div class="container-fluid jumbotron">
        <div class="position-fixed">
            <div v-if="spinner == 1" class="alert alert-success" role="alert">
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
            </div>
        </div>
        <ul class="nav justify-content-center bg-primary">
            <li class="nav-item">
                <a class="nav-link" href="#" :class='{"bot-triangle-nav":application_status=="pending"}'  @click="application_status_filter('pending')">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" :class='{"bot-triangle-nav":application_status=="approved"}' @click="application_status_filter('approved')">Approved</a>
            </li>
            <li class="nav-item">
                <a class="nav-link active" href="#" :class='{"bot-triangle-nav":application_status=="not qualified"}' @click="application_status_filter('not qualified')">Not Qualified</a>
            </li>
        </ul>
        <div class="jumbotron p-2">
            <p class="col-lg-12 text-left"><small >Total number of results : {{total}}</small></p>
            <div class="progress ">
                <div class="progress-bar" style="color:black" :style='{width:(links.current_page*100)/links.last_page +"%"}' role="progressbar" :aria-valuenow="links.current_page" aria-valuemin="0" :aria-valuemax="links.last_page">
                 Page {{links.current_page+' of '+links.last_page}}
                </div>
            </div>
            <div v-for="(applicant,index) in applicants" :key="'applicant'+index" class="row bg-light p-2 m-2">
                
                <div class="col-lg-2">
                    <div v-for="(applicantfile,index) in applicant.applicant_account.applicant_files" :key="'pic'+index">
                        <img  v-if="applicantfile.type == 'profile_pic'" :src="applicantfile.url_name"  alt="" height="100" width="100">
                    </div>
                </div>
                
                <div class="col-lg-7">
                    <p>
                      name: <strong>{{applicant.applicant_account.personal_data.fname}} {{applicant.applicant_account.personal_data.mname}}, {{applicant.applicant_account.personal_data.lname}}</strong> <br>
                      contact no. : <strong>{{applicant.applicant_account.personal_data.contact_num}}</strong> <br> 
                      gender. : <strong>{{applicant.applicant_account.personal_data.gender == 0?'male':'female'}}</strong><br> 
                      age : <strong>{{getAge(applicant.applicant_account.personal_data.date_of_birth)}} y/o</strong><br> 
                      specialization: <span class="badge badge-pill badge-info p-1 m-1">Info</span><span class="badge badge-pill badge-info p-1 m-1">Info</span>
                    </p>
                </div>
                <div class="col-lg-3">
                    <p class="text-right">
                        <button :disabled="spinner == 1" v-if='application_status=="pending"' @click="approve(applicant.applicant_account.id,applicant.id)" class="btn btn-primary btn-sm btn-block">Approve</button>
                        <button :disabled="spinner == 1"  v-if='application_status=="pending"' @click="notQualified(applicant.applicant_account.id,applicant.id)" class="btn btn-danger btn-sm btn-block">Not Qualified</button>
                        <button :disabled="spinner == 1"  v-if='application_status=="not qualified"' @click="pending(applicant.applicant_account.id,applicant.id)" class="btn btn-danger btn-sm btn-block">Back to pending</button>
                        <a target="__blank" :href="'/company/applicant/'+applicant.applicant_account.id" class="btn btn-sm bg-info text-light btn-block">Applicant Profile</a>
                        <button href="#" data-toggle="modal" :data-target="'#applicant'+applicant.id" class="btn btn-sm bg-success text-light btn-block">Applicant Resume</button>
                    </p>
                   

                </div>
                <div v-if='application_status=="approved"' class="col-lg-10 offset-lg-1">
                    <p class="text-center  bg-info">
                    <a   class=""  data-toggle="collapse" :href="'#hireform'+applicant.applicant_account_id" role="button" aria-expanded="false" aria-controls="collapseExample">
                       <strong class="text-white">Hiring form >>></strong> 
                    </a>
                    </p>
                    <div class="collapse" :id="'hireform'+applicant.applicant_account_id">
                        <application-hiringform :current_page='current_page' :applicant_id='applicant.applicant_account_id'></application-hiringform>
                    </div>
                </div>
                <p><small class="text-muted">{{humanTime(applicant.created_at)}}</small></p>
                <!-- Modal -->
                <div class="modal fade" :id="'applicant'+applicant.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div v-for="(applicantfile,index) in applicant.applicant_account.applicant_files" :key="'pic'+index">
                            <a v-if="applicantfile.type == 'resume'" :href="applicantfile.url_name" class="btn btn-sm bg-primary text-light btn-block">Download Resume</a>
                        </div>
                        <pre v-html="applicant.applicant_account.personal_data.resume">

                        </pre>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                    </div>
                </div>
                </div>

            </div>
         <div>
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center row">
                    <li class="page-item"><a class="page-link" @click="getApplicantData(links.first_page)" href="#">First Page</a></li>
                    <li class="page-item"><a class="page-link" @click="getApplicantData(links.prev_page)" href="#">Previous</a></li>
                    <li class="page-item" v-if='links.current_page-2 >= 1'><a class="page-link" @click="getApplicantData(links.path+'?page='+(links.current_page - 2))" href="#">{{links.current_page - 2}}</a></li>
                    <li class="page-item" v-if='links.current_page-1 >= 1'><a class="page-link" @click="getApplicantData(links.path+'?page='+(links.current_page - 1))" href="#">{{links.current_page - 1}}</a></li>
                    <li class="page-item disabled"><a class="page-link " href="#">{{links.current_page}}</a></li>
                    <li class="page-item" v-if='links.current_page+1 <= links.last_page'><a class="page-link" @click="getApplicantData(links.path+'?page='+(links.current_page + 1))" href="#">{{links.current_page + 1}}</a></li>
                    <li class="page-item" v-if='links.current_page+2 <= links.last_page'><a class="page-link" @click="getApplicantData(links.path+'?page='+(links.current_page + 2))" href="#">{{links.current_page + 2}}</a></li>
                    <li class="page-item"><a class="page-link" @click="getApplicantData(links.next_page)" href="#">Next</a></li>
                    <li class="page-item"><a class="page-link" @click="getApplicantData(links.last_page_url)" href="#">Last Page</a></li>
                </ul>
            </nav>
        </div>
        </div>
        
    </div>
</template>
<script>
export default {
    data(){
        return{
            application_status:'pending',
            archive:1,
            spinner:'',
            applicants:'',
            current_page:'',
            search:'',
            total:'',
            links:{
                first_page:'',
                last_page_url:'',
                prev_page:'',
                next_page:'',
                current_page:'',
                last_page:'',
                path:'', 
            },
        }
    },
    mounted(){
        this.getApplicantData();
    },
    methods:{
        approve(theid,applicantionid){
            let vm = this;
                swal({
                    title: "Are you sure?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    vm.spinLoading();
                    axios.post('/company/ApproveApplicant',{
                        id:theid,
                        applicantion:applicantionid
                    })
                    .then(function(res){
                        console.log(res.data);
                        vm.spinLoading();
                        vm.getApplicantData();
                        swal("Applicant has been approved", {
                        icon: "success",
                        });
                        
                    })
                    .catch(function(error){
                        vm.spinLoading();
                        console.log(error);
                        swal("aborted! an error occured", {
                        icon: "warning",
                        });
                    })
                } else {
                    swal("Request aborted!");
                }
            });
        },
        notQualified(theid,applicantionid){
            let vm = this;
                swal({
                    title: "Are you sure?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    vm.spinLoading();
                    axios.post('/company/NotApproved',{
                        id:theid,
                        applicantion:applicantionid
                    })
                    .then(function(res){
                        console.log(res.data);
                        vm.spinLoading();
                        vm.getApplicantData();
                        swal("Applicant has been set to not qualified", {
                        icon: "success",
                        });
                        
                    })
                    .catch(function(error){
                        vm.spinLoading();
                        console.log(error);
                        swal("aborted! an error occured", {
                        icon: "warning",
                        });
                    })
                } else {
                    swal("Request aborted!");
                }
            });
        }
        ,
        pending(theid,applicantionid){
            let vm = this;
                swal({
                    title: "Are you sure?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    vm.spinLoading();
                    axios.post('/company/pending',{
                        id:theid,
                        applicantion:applicantionid
                    })
                    .then(function(res){
                        console.log(res.data);
                        vm.spinLoading();
                        vm.getApplicantData();
                        swal("Applicant has been set to not qualified", {
                        icon: "success",
                        });
                        
                    })
                    .catch(function(error){
                        vm.spinLoading();
                        console.log(error);
                        swal("aborted! an error occured", {
                        icon: "warning",
                        });
                    })
                } else {
                    swal("Request aborted!");
                }
            });
        },
        application_status_filter(val){
            this.application_status = val;
            this.getApplicantData();
        },
        getAge(date){
            return moment().diff(date, 'years');
        },
        humanTime:function(dateAndTime){
            return moment(dateAndTime).fromNow();
        },
        getApplicantData(url = '/company/job_post_applicants'){
            let vm = this;
            vm.current_page = url;
            axios.post(url,{
                application_status:vm.application_status
            })
            .then(function(res){
                console.log(res.data);
                vm.links.first_page = res.data.applicants.first_page_url;
                vm.links.last_page_url = res.data.applicants.last_page_url;
                vm.links.prev_page = res.data.applicants.prev_page_url;
                vm.links.next_page = res.data.applicants.next_page_url;
                vm.links.current_page = res.data.applicants.current_page;
                vm.links.last_page = res.data.applicants.last_page;
                vm.links.path = res.data.applicants.path;
                vm.total = res.data.applicants.total;
                vm.applicants = res.data.applicants.data;
                vm.archive = res.data.archive;
                
            })
            .catch(function(error){
                console.log(error);
            });
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        hireapplicant(applicant_id){
            swal(applicant_id);
        }
    }
}
</script>

