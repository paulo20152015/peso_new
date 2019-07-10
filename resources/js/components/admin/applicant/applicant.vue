<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light bg-dark">
            <a class="navbar-brand" href="#">Applicant</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link btn btn-outline-secondary" href="#personalData">Pesonal Data</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-secondary" href="#Specialization">Specialization</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-secondary" href="#Resume">Resume</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-secondary" href="#Employment_History">Employment History</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-outline-secondary" href="#Ratings">Ratings</a>
                </li>         
                </ul>
            </div>
        </nav>
        <div class="card m-2" id="personalData">
            <div class="card-body">
                <h4>Personal Data</h4>
                <hr>
                <div class="row">
                    <div class="col-lg-2">
                        <img v-for="(pic,index) in profile_pic" :key="index" :src="pic.url_name" height="70" width="70" alt="">
                    </div>
                    <div class="col-lg-5">
                        <p>Username : <strong>{{account_data.username}}</strong> <br>
                        Fullname : <strong>{{personal_data.fname}} {{personal_data.mname}}, {{personal_data.lname}}</strong><br>
                        Email:<strong> {{personal_data.email}}</strong><br>
                        Gender:<strong> {{personal_data.gender == 0?'male':'female'}}</strong><br>
                        Civil Status: <strong>{{personal_data.civil_status}}</strong><br>
                        </p>
                    </div>
                    <div class="col-lg-5">
                        <p>Contact Number : <strong>{{personal_data.contact_num}}</strong> <br>
                        Date of Birth : <strong>{{personal_data.date_of_birth}}</strong><br>
                        Age : <strong>{{getAge(personal_data.date_of_birth)}} </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="card m-2" id="Specialization">
            <div class="card-body">
                <h4>Specializations : </h4>
                <hr>
                <ul>
                    <li v-for="(specialization,index) in specializations" :key="index"><strong>{{specialization.specialization.specialization}}</strong></li>
                </ul>
            </div>
        </div>
        <div class="card m-2" id="Resume">
            <div class="card-body">
                <h4>Resume</h4>
                <hr>
                <div class="row">
                    <div class="col-lg-2">
                        <a v-for="(resume,index) in resumes" :key="index" :href="resume.url_name" class="btn btn-success">download</a>
                    </div>
                    <div class="col-lg-10">
                        <pre v-html="personal_data.resume">
                            
                        </pre>
                    </div>
                </div>
            </div>
        </div>
        <div class="card m-2" id="Employment_History">
            <div class="card-body">
                <h4>Employment History</h4>
                <hr>
                <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                        <th scope="col">Company</th>
                        <th scope="col">Position</th>
                        <th scope="col">Start Date</th>
                        <th scope="col">End Date</th>
                        <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for='(employment,index) in employments_history' :key="index">
                            <td><img v-for="(pic,index) in employment.company_detail.company_files" :key="index" :src="pic.name" height="30" width="30" alt=""> {{employment.company_detail.name}}</td>
                            <td>{{employment.position}}</td>
                            <td>{{employment.start_date}}</td>
                            <td>{{employment.end_date}}</td>
                            <td>{{employment.status}}</td>
                        </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
        <div class="card m-2" id="Ratings">
            <div class="card-body">
                <h5>Ratings</h5>
                <p>Average Rating : {{totalRatingValue}}</p>
                <hr>
                <div v-for="(rating,index) in ratings" :key="index" class="row border-bottom border-secondary p-2">
                    <div class="col-lg-1">
                        <img v-for="(pic,index) in rating.company_account.company_detail.company_files" :key="index" :src="pic.name" height="30" width="30" alt="">
                    </div>
                    <div class="col-lg-11">
                        <blockquote> Rating : <span class="badge badge-secondary">{{rating.rate}} of 10</span> <i> <br>{{rating.message}}</i> 
                            <strong class="blockquote-footer">{{rating.company_account.company_detail.name}}</strong> <small class="text-muted">{{humanTime(rating.created_at)}}</small>
                        </blockquote>
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
            account_data:'',
            personal_data:'',
            profile_pic:'',
            specializations:'',
            resumes:'',
            employments_history:'',
            ratings:'',
            totalRatingValue:''
        }
    },
    mounted(){
        this.getApplicantData();
    }
    ,
    methods:{
        humanTime:function(dateAndTime){
                    return moment(dateAndTime).fromNow();
        },
        getAge(date){
            return moment().diff(date, 'years');
        },
        totalRating(arrayRating){
           let totalAve = 0;
           let count = 0;
           arrayRating.forEach(function(rate){
               totalAve += parseInt(rate.rate);
               count++;
           });
            this.totalRatingValue = totalAve/count;
        }
        ,
        getApplicantData(){
            let vm = this;
            axios.post('/applicant/singleViewData')
            .then(function(res){
                vm.specializations = res.data.specialization;
                vm.resumes = res.data.resume;
                vm.profile_pic = res.data.profile_pic;
                vm.account_data = res.data.account_data;
                vm.personal_data = res.data.account_data.personal_data;
                vm.employments_history = res.data.employment_track;
                vm.ratings = res.data.ratings;
                vm.totalRating(res.data.ratings);
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            });
        }
    }

}
</script>

