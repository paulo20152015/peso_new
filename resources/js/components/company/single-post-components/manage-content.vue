<template>
    <div class='container p-3 '>
        <div class="col-lg-10 p-2 offset-lg-1">
            <h4>Post Specializations : </h4>
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">List of Specializations :</h6>
                    <form @submit.prevent="AddOrRemoveSpecialization('added')">
                        <div class="row">
                            <div class="form-group form-check m-2" v-for='(specialization,index) in specializationDoesnt' :key='"specialization"+index'>
                                <input v-model='checkedItemsNot' type="checkbox" class="form-check-input" :id="'adding'+specialization.id" :value="specialization.id">
                                <label class="form-check-label" :for="'adding'+specialization.id"><ins>{{specialization.specialization}}</ins></label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary col-lg-6 offset-lg-3 mt-3">Add</button>
                    </form>
                </div>
            </div>
            <hr>
            <div class="card">
                <div class="card-body">
                    <h6 class="card-subtitle mb-2 text-muted">Job Post current specialization :</h6>
                    <form @submit.prevent="AddOrRemoveSpecialization('removed')">
                        <div class="row">
                            <div class="form-group form-check  m-2" v-for='(specialization,index) in specializationHas' :key='"specialization"+index'>
                                <input v-model='checkedItems' type="checkbox" class="form-check-input" :id="'removing'+specialization.id" :value="specialization.id">
                                <label class="form-check-label" :for="'removing'+specialization.id"><ins>{{specialization.specialization.specialization}}</ins></label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-danger col-lg-6 offset-lg-3 mt-3">Remove</button>
                    </form>
                </div>
            </div>
        </div>
        <hr>
        <div class="col-lg-10 p-2 offset-lg-1">
            <h4>Post Contents : </h4>
            <form @submit.prevent="updateJobPost()">
         <div class="card">
          <div class="card-body">
           <div class="form-group ">
                <label for="title">Title * </label>
                <input v-validate.continues="'required|min:10|max:120'" v-model="jobPostCreateForm.title"  name="title" type="text" class="form-control" id="title" placeholder="Enter job post title">
                <div> 
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('title')" :key='"title"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-if="jobPostCreateForm.errors.has('title')">
                            {{ jobPostCreateForm.errors.get('title') }}
                        </p>
                    </transition>
                </div>
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label for="city" >City : {{cityoriginal}}</label>
                    <div >
                        <select name='city' v-validate.continues="'required|excluded:0|numeric'" @click="getTown()" v-model="city" class="form-control" v-if='cities != undefined && cities != ""'>
                            <option value='0' selected>none</option>
                            <option v-for='(city,index) in cities' :value='city.id' :key='"city"+index'>{{city.city}}</option>
                        </select>
                    </div>
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('city')" :key='"city"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                </div>
                <div class="form-group col-lg-6">
                    <label for="town">Town : {{townoriginal}}</label>
                    <div>
                        <select  class="form-control" v-validate.continues="'required|excluded:0|numeric'" name='town' v-model="jobPostCreateForm.town" >
                            <option value='0' selected>none</option>
                            <option v-for='(town,index) in towns' :value='town.id' :key='"town"+index'>{{town.town}}</option>
                        </select>
                    </div>
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('town')" :key='"town"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-if="jobPostCreateForm.errors.has('town')">
                        {{ jobPostCreateForm.errors.get('town') }}
                        </p>
                    </transition>
                </div>
            </div>
            <div class="form-group">
                <label for="address">Full Address * </label>
                <input v-validate.continues="'required|min:10|max:120'" v-model="jobPostCreateForm.address"  name="address" type="text" class="form-control" id="address" placeholder="Enter full address">
                <div> 
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('address')" :key='"address"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-if="jobPostCreateForm.errors.has('address')">
                        {{ jobPostCreateForm.errors.get('address') }}
                        </p>
                    </transition>
                </div>
            </div>
            <div class="form-group">
                <label for="experience">Work Experienced * </label>
                <input v-validate.continues="'required|min:4|max:50'" v-model="jobPostCreateForm.experience"  name="experience" type="text" class="form-control" id="experience" placeholder="Enter work required experience">
                <div> 
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('experience')" :key='"experience"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-if="jobPostCreateForm.errors.has('experience')">
                        {{ jobPostCreateForm.errors.get('experience') }}
                        </p>
                    </transition>
                </div>
            </div>
            <div class="form-group">
                <label for="number">Job Description * </label>
                <ckeditor :editor="editor" v-model="jobPostCreateForm.job_description"  :config="editorConfig"></ckeditor>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="jobPostCreateForm.errors.has('job_description')">
                    {{ jobPostCreateForm.errors.get('job_description') }}
                    </p>
                </transition>
            </div>
            <div class="form-group">
                <label for="number">Job Requirements * </label>
                <ckeditor :editor="editor" v-model="jobPostCreateForm.job_requirements"  :config="editorConfig"></ckeditor>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="jobPostCreateForm.errors.has('job_requirements')">
                    {{ jobPostCreateForm.errors.get('job_requirements') }}
                    </p>
                </transition>
            </div>
            <button  type="submit" class="btn btn-primary col-lg-6 offset-lg-3 mt-3" >
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                Save
            </button>
          </div>
         </div>
            </form>
            
        </div>
    </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
export default {
    data(){
        return{
            post:'',
            cities:'',
            towns:'',
            townoriginal:'',
            cityoriginal:'',
            spinner:'',
            city:'',
            jobPostCreateForm:new Form({
                title:'',
                address:'',
                experience:'',
                town:'',
                job_description:'',
                job_requirements:''    
            }),
            editor: ClassicEditor,
            editorConfig: {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'bulletedList', 'numberedList', 'blockQuote','undo','redo'],
                    // The configuration of the rich-text editor.
            },
            specializationHas:'',
            specializationDoesnt:'',
            checkedItems:[],
            checkedItemsNot:[]
        }
    },
    mounted(){
        this.getCities();
        this.getPostData();
        this.getSpecializationDoesnt();
        this.getSpecializationHas();
    }
    ,
    methods:{
         getPostData(){
            let vm = this;
            axios.post('/company/getSinglePost',{

            })
            .then(function(res){
               vm.post =  res.data;
               vm.jobPostCreateForm.title = res.data.title;
               vm.townoriginal = res.data.town.town;
               vm.cityoriginal = res.data.town.city.city;
               vm.jobPostCreateForm.address = res.data.full_address;
               vm.jobPostCreateForm.experience = res.data.work_experience;
               vm.jobPostCreateForm.job_description = res.data.job_description;
               vm.jobPostCreateForm.job_requirements = res.data.job_requirements;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            })
        }
        ,
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
                id:vm.city
            })
            .then(function(res){
                vm.towns = res.data;
                vm.jobPostCreateForm.town = 0;
                console.log(res.data);
            }).catch(function(err){
                console.log(err);
            });
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        updateJobPost(){
            let vm = this;
            this.$validator.validateAll(['title','address','experience']).then(function(result){
                if(result){
                    vm.spinLoading();
                    vm.jobPostCreateForm.patch('/company/post_update')
                    .then( ({data}) => {
                         vm.spinLoading();
                         console.log(data);
                         swal('Updated job post successfully','','success');
                         vm.getPostData();
                    }).catch(function(error){
                         vm.spinLoading();
                          });
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            }); 
        },
        getSpecializationDoesnt(){
            let vm = this;
            axios.post('/company/specializationDoesnt',{

            })
            .then(function(res){
               vm.specializationDoesnt = res.data;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            })
        },
        getSpecializationHas(){
            let vm = this;
            axios.post('/company/specializationHas',{

            })
            .then(function(res){
                vm.specializationHas = res.data;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            })
        },
        AddOrRemoveSpecialization(action){
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
                    axios.post('/company/desOrCreate',{
                        check:vm.checkedItems,
                        action,
                        checkNot:vm.checkedItemsNot,
                    })
                    .then(function(res){
                        console.log(res.data);
                        swal("Specialization has been successfully "+action, {
                        icon: "success",
                        });
                        vm.getSpecializationDoesnt();
                        vm.getSpecializationHas();
                    })
                    .catch(function(error){
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
    }
}
</script>

