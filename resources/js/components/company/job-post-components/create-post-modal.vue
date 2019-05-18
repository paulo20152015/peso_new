<template>
    <div>
        <div class="modal fade" id="modalCreatePost" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create a Job Post</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createJobPost()">
                <div class="modal-body">
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
                        <label for="city" >City :</label>
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
                        <label for="town">Town :</label>
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
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button :disabled="jobPostCreateForm.busy" type="submit" class="btn btn-primary" >
                        <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                        Post
                    </button>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
export default {
    props:['currentPage','cities'],
    data(){
        return {
            towns:'',
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
            }
        }
    },
    methods:{
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
        parentGetJobPost(url = '/global/get-posts'){
            this.$parent.getJobPost(url);
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        }
        ,
        createJobPost(){
            let vm = this;
            this.$validator.validateAll(['title','address','experience','city','town']).then(function(result){
                if(result){
                    vm.spinLoading();
                    vm.jobPostCreateForm.post('/company/post_create')
                    .then( ({data}) => {
                         vm.spinLoading();
                         console.log(data);
                         swal('Created job post successfully','','success');
                         vm.jobPostCreateForm.reset();
                         vm.parentGetJobPost(vm.currentPage);
                    }).catch(function(error){
                         vm.spinLoading();
                          });
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            }); 
        }
    }
    
}
</script>

