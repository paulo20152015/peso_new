<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" @click="showFilters()" href="#">{{filterFlag == 1?'Hide filter,Sort and search':'Show filter,Sort and search'}}</a></li>
                </ul>
            </div>
        </nav>
        <!--search bar-->
        <transition  enter-active-class="animated slideInDown" leave-active-class="animated slideOutRight">
        <div class="jumbotron" v-if="filterFlag==1">
            <h3>filter, sort and Search</h3>
            <div class="row" >

                <div class="col-lg-6 offset-lg-3  ">
                    <div class="input-group">
                        <input class="form-control "  @keyup='getJobPost()' @keyup.delete="getJobPost()"  @keyup.ctrl='getJobPost()' @keyup.enter='getJobPost()' v-model='search'  type="search"    placeholder="search keyword here" aria-label="Search">
                        <div class="input-group-prepend">
                            <button  @click="getJobPost()" class="btn input-group-text" type='button'>
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>

                

                <div class="col-lg-6">
                    <p class="col-lg-12">Filter By: </p>
                    <div class="form-group">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Specialization :</label>
                        <div class="col-sm-10">
                            <select  class="form-control" @change='getJobPost()'  v-model='sortFilter[0].value' v-if='specializations != undefined && specializations != ""'>
                                <option value='0' selected>none</option>
                                <option v-for='(specialization,index) in specializations' :value='specialization.id' :key='"city"+index'>{{specialization.specialization}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="staticEmail" class="col-sm-10 col-form-label">City :</label>
                        <div class="col-sm-10">
                            <select  class="form-control" @change='getTown()' v-model='sortFilter[1].value' v-if='cities != undefined && cities != ""'>
                                <option value='0' selected>none</option>
                                <option v-for='(city,index) in cities' :value='city.id' :key='"city"+index'>{{city.city}}</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Town :</label>
                        <div class="col-sm-10">
                            <select  class="form-control" @change='getJobPost()' v-model='sortFilter[2].value' >
                                <option value='0' selected>none</option>
                                <option v-for='(town,index) in towns' :value='town.id' :key='"town"+index'>{{town.town}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <p class="col-lg-12">Order By: </p>
                    <div class="form-group">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Title:</label>
                        <div class="col-sm-10">
                            <select @change="getJobPost()"  class="form-control" v-model='orderFilter[0].value' >
                                <option value='0' selected>none</option>
                                <option value='1'>Ascending</option>
                                <option value='2'>Descending</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Created at:</label>
                        <div class="col-sm-10">
                            <select @change="getJobPost()"  class="form-control" v-model='orderFilter[1].value' >
                                <option value='0' selected>none</option>
                                <option value='2'>Recent</option>
                                <option value='1'>Oldest</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Updated at:</label>
                        <div class="col-sm-10">
                            <select @change="getJobPost()" class="form-control" v-model='orderFilter[2].value'>
                                <option value='0' selected>none</option>
                                <option value='2'>Recent</option>
                                <option value='1'>Oldest</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12 form-row">
                    <button class="btn btn-secondary btn-sm" @click="reset()">Remove Filters</button>
                </div>
            </div>
        </div>
        </transition>
        <div class="content mt-2 ">
            <div class='container-fluid'>
                <div class="card text-center">
                    <div class="card-header bg-dark">
                       <h4>Job Posts Lists</h4>
                    </div>
                    <div class="card-body bg-transparent text-dark-50">
                        <p class="col-lg-12 text-left"><small class="text-muted">Total number of items : {{total}}</small></p>
                        <div class="progress col-lg-12 mb-2">
                                <div class="progress-bar" style="color:black" :style='{width:(links.current_page*100)/links.last_page +"%"}' role="progressbar" :aria-valuenow="links.current_page" aria-valuemin="0" :aria-valuemax="links.last_page">
                                    Page {{links.current_page+' of '+links.last_page}}
                                </div>
                        </div>
                        
                        <div class="col-lg-12 row" v-if='total > 0'>
                            <div class="col-lg-12" v-for='(post,index) in posts' :key='"post"+index'>
                                <div class="card text-left">
                                <div class="card-header">
                                    <div class="media row">
                                    <img :src="test_array(post.company_account.company_detail.company_files) == true? post.company_account.company_detail.company_files[0].name:'/img/unkown.png'" style='max-width:70px;max-height:70px' class="align-self-start mr-3 img-fluid img-thumbnail" alt="blank">
                                    <div class="media-body">
                                        <h5 class="mt-0"><small class="text-muted">{{parseInt(links.current_page) * 10 - 10 + (index+1)}}</small>. Title : {{post.title}}</h5>
                                        <p>
                                            <small><i class="fa fa-briefcase" aria-hidden="true"></i> Work Experienced {{post.work_experience}}</small><br>
                                            <small><i class="fa fa-map-marker" aria-hidden="true"></i> Location : {{post.town.town}}, {{post.town.city.city}}</small><br>
                                            <small><i class="fa fa-location-arrow" aria-hidden="true"></i> {{post.full_address}}</small><br>
                                            <small><i class="fa fa-crosshairs" aria-hidden="true"></i> Specialization <i v-for="(job_specialization,index) in post.job_specializations" :key='"specail"+index'>{{index == 0?'':', '}}{{job_specialization.specialization.specialization}}</i></small>
                                        </p>
                                       
                                    </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <div class="media row">
                                    
                                    <div class="media-body p-3">
                                        <h5 class="mt-0">{{post.company_account.company_detail.name}} </h5>
                                        <hr>
                                        <p class="blockquote">Job descriptions : </p>
                                        <div class="col-sm-10 offset-sm-1" v-html="post.job_description">

                                        </div>
                                        <hr>
                                        <p class="blockquote">Job requirements : </p>
                                        <div class="col-sm-10 offset-sm-1" v-html="post.job_requirements">

                                        </div>                               
                                    </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <small class="text-muted">created {{humanTime(post.created_at)}}</small>
                                    <small class="text-muted">||</small>
                                    <small class="text-muted">updated {{humanTime(post.updated_at)}}</small>
                                </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 row" v-else>
                            <h3 class="col-lg-12 text-center">No result found</h3>
                        </div>
                      
                    </div>
                    <div class="card-footer">
                        <company-post-pagination :links='links'></company-post-pagination>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</template>
<script>
export default {
    props:['archive_stat'],
    data(){
        return{
            posts:[],
            filterFlag:0,
            account:'',
            detail:1,
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
        showFilters(){
            this.filterFlag = this.filterFlag ==1?0:1;
        },
        toggleComponents(val){
            let vm = this;
            switch (val) {
                case 'account':
                    vm.account = 1;
                    vm.detail = '';
                    break;
                case 'detail':
                    vm.detail = 1;
                    vm.account = '';
                    break;    
                default:
                    break;
            }
        },
        getJobPost(url = '/global/get-posts'){
            let vm = this;
            vm.current_page = url;
            let orders = vm.orderFilter;
            let sortfilter = vm.sortFilter;
            axios.post(url,{
                order:orders,
                search:vm.search,
                filter:sortfilter,
                archive_stat:vm.archive_stat
            })
            .then(function(res){
                //getting data
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
        archive:function(post,id){
            swal({
                title: "Are you sure?",
                text: "",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                let vm = this;
                axios.patch('/company/post-archive/'+id)
                .then(function(res){
                    if(res.data.result == 'success'){
                        vm.getJobPost(vm.current_page);
                    }
                    console.log(res.data);
                }).catch(function(error){
                    console.log(error.response);
                });
                if(vm.archive_stat == 'yes'){
                    swal(post+" has been recovered!", {
                    icon: "success",
                    });
                }else{
                    swal(post+" has been archived!", {
                    icon: "success",
                    });
                }
                
            } else {
                swal("Aborted");
            }
            });
        },
    }
}
</script>
<style scoped>

</style>


