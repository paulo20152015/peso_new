<template>
    <div class="container">
        <div class="container-fluid row">
            <div class="col-lg-4">
                <div class="card " >
                    <div class="card-header text-white bg-secondary">
                       <strong>Job Sorting and Ordering</strong> 
                    </div>
                    <div class="card-body bg-light">
                        <p class="border-bottom border-dark">Filter By :</p>
                        <div class="form-group">
                        <label for="staticEmail" class="col-sm-12 col-form-label">Specialization :</label>
                        <div class="col-sm-12">
                            <select  class="form-control" @change='getJobPost()'  v-model='sortFilter[0].value' v-if='specializations != undefined && specializations != ""'>
                                <option value='0' selected>none</option>
                                <option v-for='(specialization,index) in specializations' :value='specialization.id' :key='"city"+index'>{{specialization.specialization}}</option>
                            </select>
                        </div>
                        </div>
                        <div class="form-group">
                        <label for="staticEmail" class="col-sm-12 col-form-label">City :</label>
                        <div class="col-sm-12">
                            <select  class="form-control" @change='getTown()' v-model='sortFilter[1].value' v-if='cities != undefined && cities != ""'>
                                <option value='0' selected>none</option>
                                <option v-for='(city,index) in cities' :value='city.id' :key='"city"+index'>{{city.city}}</option>
                            </select>
                        </div>
                        </div>
                        <div class="form-group">
                            <label for="staticEmail" class="col-sm-12 col-form-label">Town :</label>
                            <div class="col-sm-12">
                                <select  class="form-control" @change='getJobPost()' v-model='sortFilter[2].value' >
                                    <option value='0' selected>none</option>
                                    <option v-for='(town,index) in towns' :value='town.id' :key='"town"+index'>{{town.town}}</option>
                                </select>
                            </div>
                        </div>
                        <p class="border-bottom border-dark">Order By :</p>
                        <div class="form-group">
                            <label for="staticEmail" class="col-sm-12 col-form-label">Title:</label>
                            <div class="col-sm-12">
                                <select @change="getJobPost()"  class="form-control" v-model='orderFilter[0].value' >
                                    <option value='0' selected>none</option>
                                    <option value='1'>Ascending</option>
                                    <option value='2'>Descending</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="staticEmail" class="col-sm-12 col-form-label">Date :</label>
                            <div class="col-sm-12">
                                <select @change="getJobPost()"  class="form-control" v-model='orderFilter[1].value' >
                                    <option value='0' selected>none</option>
                                    <option value='2'>Recent</option>
                                    <option value='1'>Oldest</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="staticEmail" class="col-sm-12 col-form-label">Updated :</label>
                            <div class="col-sm-12">
                                <select @change="getJobPost()" class="form-control" v-model='orderFilter[2].value'>
                                    <option value='0' selected>none</option>
                                    <option value='2'>Recent</option>
                                    <option value='1'>Oldest</option>
                                </select>
                            </div>
                        </div>
                        <button class="btn btn-warning btn-sm form-control col-lg-8 offset-lg-2" @click="reset()">Reset</button>
                    </div>
                </div> 
            </div>

            <div class="col-lg-8">

                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control "  @keyup='getJobPost()' @keyup.delete="getJobPost()"  @keyup.ctrl='getJobPost()' @keyup.enter='getJobPost()' v-model='search'  type="search"    placeholder="search keyword here" aria-label="Search">
                            <div class="input-group-prepend">
                                <button  @click="getJobPost()" class="btn btn-primary " type='button'>
                                <i class="fa fa-search"></i> search
                                </button>
                            </div>
                        </div>
                        <p class="col-lg-12 text-left"><small >Total number of results : {{total}}</small></p>
                        <div class="progress ">
                            <div class="progress-bar" style="color:black" :style='{width:(links.current_page*100)/links.last_page +"%"}' role="progressbar" :aria-valuenow="links.current_page" aria-valuemin="0" :aria-valuemax="links.last_page">
                                Page {{links.current_page+' of '+links.last_page}}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" v-for='(post,index) in posts' :key='"post"+index'>
                    <div class="card-header text-white bg-dark">
                        <div >
                        <p v-for='(job_application) in post.job_applications' :key="'application'+job_application.id" class="text-right"><a :class="{'btn btn-info':job_application.status=='pending','btn btn-danger':job_application.status=='not qualified','btn btn-primary':job_application.status=='approved'}" :href="'/applicant/post/'+post.id">{{job_application.status}}</a></p>
                        <p v-if="test_array(post.job_applications) == false" class="text-right"><a class="btn btn-success" :href="'/applicant/post/'+post.id">Apply Now</a></p>
                        </div>
                        <h5 class="mt-0"><small class="text-muted">{{parseInt(links.current_page) * 10 - 10 + (index+1)}}</small>.
                        <img :src="test_array(post.company_account.company_detail.company_files) == true? post.company_account.company_detail.company_files[0].name:'/img/unkown.png'" style='max-width:30px;max-height:30px' class="align-self-start mr-3 img-fluid img-thumbnail" alt="blank">
                         <a :href="'/applicant/post/'+post.id" target="__blank">{{post.title}}</a> </h5>
                    </div>
                    <div class="card-body">
                        
                        <p>
                           <a :href="'/applicant/company/'+post.company_account.id"><strong>{{post.company_account.company_detail.name}}</strong></a>  <br>
                            <small><i class="fa fa-briefcase" aria-hidden="true"></i> Work Experienced {{post.work_experience}}</small><br>
                            <small><i class="fa fa-map-marker" aria-hidden="true"></i> Location : {{post.town.town}}, {{post.town.city.city}}</small><br>
                            <small><i class="fa fa-location-arrow" aria-hidden="true"></i> {{post.full_address}}</small><br>
                            <small><i class="fa fa-crosshairs" aria-hidden="true"></i> Specialization <i v-for="(job_specialization,index) in post.job_specializations" :key='"specail"+index'>{{index == 0?'':', '}}{{job_specialization.specialization.specialization}}</i></small>
                        </p>
                        <hr>
                            <p class="blockquote">Job requirements : </p>
                            <div class="col-sm-10 offset-sm-1" v-html="post.job_requirements">

                            </div>  
                    </div>
                    <div class="card-footer text-white bg-info">
                        <small class="text-muted">Posted {{humanTime(post.created_at)}}</small>
                        <small class="text-muted">||</small>
                        <small class="text-muted">updated {{humanTime(post.updated_at)}}</small>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body p-0 pt-1">
                        <company-post-pagination :links='links'></company-post-pagination>
                    </div>
                </div>

            </div>

        </div>
        
    </div>
</template>
<script>
export default {
    props:['archive_stat','company_post'],
    data(){
        return{
            employment_status:'',
            posts:[],
            account:'',
            search:'',
            current_page:'',
            specializations:'',
            cities:'',
            towns:'',
            sortFilter:[
                {collumn:'specialization',comparison:'=',value:''},
                {collumn:'city',comparison:'=',value:''},
                {collumn:'town',comparison:'=',value:''},
            ],
            orderFilter:[
                {collumn:'title',value:''},
                {collumn:'created_at',value:''},
                {collumn:'updated_at',value:''},
            ],
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
        this.checkEmployment();
        this.getJobPost();
        this.getCities();
        this.getSpecializations();
        if(this.archive_stat == 'yes'){
  
        }
    },
    methods:{
         test_array(totest){
            let flag = Array.isArray(totest) && totest.length > 0;
            return flag;
        }
        ,
        
        getJobPost(url = '/applicant/applicationHistory'){
            let vm = this;
            vm.current_page = url;
            let orders = vm.orderFilter;
            let sortfilter = vm.sortFilter;
            axios.post(url,{
                order:orders,
                search:vm.search,
                filter:sortfilter,
                archive_stat:vm.archive_stat,
                company_post:vm.company_post
            })
            .then(function(res){
                //getting data
                vm.checkEmployment();
                vm.posts = res.data.data;
                vm.links.first_page = res.data.first_page_url;
                vm.links.last_page_url = res.data.last_page_url;
                vm.links.prev_page = res.data.prev_page_url;
                vm.links.next_page = res.data.next_page_url;
                vm.links.current_page = res.data.current_page;
                vm.links.last_page = res.data.last_page;
                vm.links.path = res.data.path;
                vm.total = res.data.total;
                console.log(res.data);
            }).catch(function(err){
                console.log(err.data);
            });
        },
        getCities(){
            let vm = this;
            axios.post('/global/all-cities',{ 
            })
            .then(function(res){
                vm.cities = res.data;
                console.log(res.data);
            }).catch(function(err){
                console.log(err);
            });
        },
        getTown(){
            let vm = this;
            axios.post('/global/towns',{ 
                id:vm.sortFilter[1].value
            })
            .then(function(res){
                vm.towns = res.data;
                vm.sortFilter[2].value = 0;
                vm.getJobPost();
                console.log(res.data);
            }).catch(function(err){
                console.log(err);
            });
        },
        getSpecializations(){
            let vm = this;
            axios.post('/global/specializations',{ 
                
            })
            .then(function(res){
                vm.specializations = res.data;
                console.log(res.data);
            }).catch(function(err){
                console.log(err);
            });
        },
        reset(){
            this.search = '';
            this.orderFilter[0].value=0;
            this.orderFilter[1].value=0;
            this.orderFilter[2].value=0;
            this.sortFilter[0].value=0;
            this.sortFilter[1].value=0;
            this.sortFilter[2].value=0;
            this.getJobPost();
        }
        ,
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
<style scoped>

</style>


