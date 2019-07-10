<template>
    <div class="row mb-5 mt-2 bg-light p-3">
        <div class="col-lg-6 p-3 border border-primary">
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
        <div class="col-lg-6 p-3 border border-dark">
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
        <div class="col-lg-2 mt-2">
            <p v-if='resumeFile !="no data"'><a :href="resumeFile" class="btn btn-success">Download Resume</a></p>
            
            <form>
            <div class="form-group">
                <p><small>Note: must be doc or docx file type</small></p>
                <label for="resume"><i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Upload A resume</label>
                <input type="file" @change="uploadResume" v-validate.continues="'ext:doc,docx|size:2000'" name='resume' class="form-control-file" id="resume">
            </div>
            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-for="(error,index) in errors.collect('resume')" :key='"resume"+index'>
                    {{ error }}
                </p>  
            </transition-group>
            </form>
        </div>
        <div class="col-lg-9 row table-responsive">
            <h3 class='text-center col-lg-12'>Resume Contents</h3>
            <ckeditor :editor="editor" v-model="personalDataForm.resume"  :config="editorConfig"></ckeditor>
            <button :disabled='personalDataForm.busy' @click="save()" class="btn btn-primary text-center col-lg-12 mt-3">
                <i v-if="spinner1 == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> 
                save</button>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error text-center col-lg-12' v-if="personalDataForm.errors.has('resume')">
                    {{ personalDataForm.errors.get('resume') }}
                </p>
            </transition>
        </div>
    </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
export default {
    data(){
        return {
            specializationHas:'',
            specializationDoesnt:'',
            checkedItems:[],
            checkedItemsNot:[],
            editor: ClassicEditor,
            spinner1:'',
            editorConfig: {
                
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote','undo','redo'],
                    // The configuration of the rich-text editor.
            },
            spinner:'',
            resumeFile:'',
            personalDataForm:new Form({
                resume:'', 
            }),
        }
    },
    mounted(){
        this.getPersonalData();
        this.getResumeFile();
        this.getSpecializationDoesnt();
        this.getSpecializationHas();
    }
    ,
    methods:{
        save(){
            let vm = this;
            vm.spinLoading1();
            vm.personalDataForm.post('/applicant/updateResume')
             .then( ({data}) => {
                vm.spinLoading1();
                console.log(data);
                swal('Save','','success');
                vm.getPersonalData();
            }).catch(function(error){
                vm.spinLoading1();
                vm.getPersonalData();
                vm.getResumeFile();
                swal('Aborted! ',"Errors detected","error");
            });      
        },
        spinLoading1(){
            this.spinner1 = this.spinner1 != 1 ?1:'';
        },
        getPersonalData(){
            let vm = this;
            axios.post('/applicant/profile_data', {
                
            })
            .then(function (response) {
                vm.personalDataForm.resume = response.data.personal_data.resume;
                console.log(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        getResumeFile(){
            let vm = this;
            axios.post('/applicant/returnResume', {
                
            })
            .then(function (response) {
                vm.resumeFile=response.data;
                console.log(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        uploadResume(e){
            var vm = this;
            console.log(e);
            const formData = new FormData;
            let files1 = e.target.files[0];
            console.log(files1);
            formData.append('filetoupload',files1);
            formData.append('type','resume');
            console.log(formData);
            vm.$validator.validateAll(['resume']).then(function(result){
                if(result){
                    vm.spinLoading();
                    axios.post('/applicant/uploadResume',formData,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function(res){
                        swal('Uploaded','','success');
                        vm.spinLoading();
                        vm.getPersonalData();
                        vm.getResumeFile();
                    }).catch(function(errors){
                        console.log(errors);
                        vm.spinLoading();
                    });
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            });   
        },
        getSpecializationDoesnt(){
            let vm = this;
            axios.post('/applicant/specializationDoesnt',{

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
            axios.post('/applicant/specializationHas',{

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
                    axios.post('/applicant/desOrCreate',{
                        check:vm.checkedItems,
                        action,
                        checkNot:vm.checkedItemsNot,
                    })
                    .then(function(res){
                        console.log(res);
                        swal(res.data);
                        window.location.reload();
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

