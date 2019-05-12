<template>
    <div class="container-fluid bg-dark col-lg-10">
        <div class="col-lg-12">
            <img :src="cover" style="width:100%;height:300px;background-image:url('/img/default-cover-b.png');background-size:cover;">

                <div class="form-group mt-n5">
                    <label for="cover">Company Cover Image</label>
                    <input v-validate.continues="'image|size:2000'" @change="uploadCover" name='companyCover' type="file" class="form-control-file" id="cover">
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('companyCover')" :key='"companyCover"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <i v-if="spinner2 == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                </div>

        </div>
        <hr class="bg-light">
        <div class="col-lg-12 row">
            <div class="col-lg-2 p-2 text-center">
               <img :src="logo"  height="100" width="100" style="">

                <div class="form-group">
                    <label for="exampleFormControlFile1">Company Logo</label>
                    <input v-validate.continues="'image|size:2000'" name='companyLogo' id='companyLogo' type="file" class="form-control-file"  @change="uploadLogo">
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('companyLogo')" :key='"companyLogo"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                </div>
                
            </div>
            <div class="col-lg-5 p-2">
                <div class="input-group mb-1 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Name</span>
                    </div>
                    <input type="text" name='name' v-validate.continues="'required|min:2|max:40'" v-model='companyDetailsForm.name' class="form-control" placeholder="Enter company name">

                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('name')" :key='"name"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('name')">
                        {{ companyDetailsForm.errors.get('name') }}
                    </p>
                </transition>
                <div class="input-group mb-1 mt-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Email</span>
                    </div>
                    <input type="email" name='email' v-validate.continues="'required|email|max:40'" v-model='companyDetailsForm.email' class="form-control" placeholder="Enter company email">
                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('email')" :key='"email"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('email')">
                        {{ companyDetailsForm.errors.get('email') }}
                    </p>
                </transition>
                <div class="input-group mb-1 mt-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Mobile Number</span>
                    </div>
                    <input type="text" name='mobile number' v-validate.continues="'digits:11|'" v-model='companyDetailsForm.number' class="form-control" placeholder="Enter company mobile number">
                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('mobile number')" :key='"mobile number"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('number')">
                        {{ companyDetailsForm.errors.get('number') }}
                    </p>
                </transition>
                <div class="input-group mb-1 mt-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">LandLine</span>
                    </div>
                    <input type="text" name='landline number' v-validate.continues="'min:2|max:50'" v-model='companyDetailsForm.landline' class="form-control" placeholder="Enter company landline number">
                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('landline number')" :key='"landline number"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('landline')">
                        {{ companyDetailsForm.errors.get('landline') }}
                    </p>
                </transition>
            </div>
            <div class="col-lg-5 p-2">
                <div class="input-group mb-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Website</span>
                    </div>
                    <input type="text" name='website' v-validate.continues="'url|max:150'" v-model='companyDetailsForm.website' class="form-control" placeholder="Enter your company website">
                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('website')" :key='"website"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('website')">
                        {{ companyDetailsForm.errors.get('website') }}
                    </p>
                </transition>
                <div class="input-group mb-1 mt-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Company Size</span>
                    </div>
                    <input type="text"  name='company size' v-validate.continues="'min:2|max:40'" v-model='companyDetailsForm.size' class="form-control" placeholder="small,enterprise, or employees range">
                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('company size')" :key='"company size"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('size')">
                        {{ companyDetailsForm.errors.get('size') }}
                    </p>
                </transition>
                <div class="input-group mb-1 mt-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">City</span>
                    </div>
                    <input type="text"  name='city' v-validate.continues="'min:2|max:50'" v-model="companyDetailsForm.city" class="form-control" placeholder="Enter company address city">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Town</span>
                    </div>
                    <input type="text" name='town' v-validate.continues="'min:2|max:50'" v-model="companyDetailsForm.town" class="form-control" placeholder="Enter company address town">
                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('city')" :key='"city"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('city')">
                        {{ companyDetailsForm.errors.get('city') }}
                    </p>
                </transition>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('town')" :key='"town"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('town')">
                        {{ companyDetailsForm.errors.get('town') }}
                    </p>
                </transition>
                <div class="input-group mb-1 mt-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Street or Building</span>
                    </div>
                    <input type="text"  name='steet' v-validate.continues="'min:2|max:50'" v-model="companyDetailsForm.street" class="form-control" placeholder="Enter company address street or building">
                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('steet')" :key='"steet"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('street')">
                        {{ companyDetailsForm.errors.get('street') }}
                    </p>
                </transition>
                <div class="input-group mb-1 mt-1">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Baranggay</span>
                    </div>
                    <input type="text" name='baranggay' v-validate.continues="'min:2|max:50'" v-model="companyDetailsForm.baranggay" class="form-control" placeholder="Enter company address Baranggay">
                </div>
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('baranggay')" :key='"baranggay"+index'>
                            {{ error }}
                        </p>  
                </transition-group>
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('baranggay')">
                        {{ companyDetailsForm.errors.get('baranggay') }}
                    </p>
                </transition>
            </div>
        </div>
        <div class="col-lg-10 offset-lg-2 p-2">
            <label for="exampleFormControlTextarea1">Company overview</label>
             <ckeditor :editor="editor" v-model="companyDetailsForm.overview" :config="editorConfig"></ckeditor>
             <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyDetailsForm.errors.has('overview')">
                        {{ companyDetailsForm.errors.get('overview') }}
                    </p>
            </transition>
            <button @click='save' type="submit" class="btn btn-primary col-lg-6 offset-lg-3 mt-3" >
                        <i v-if="spinner3 == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                        Save
            </button>
            
        </div>
    </div>
</template>
<script>
import ClassicEditor from '@ckeditor/ckeditor5-build-classic';
export default {
    data(){
        return{
            spinner:'',
            spinner2:'',
            spinner3:'',
            logo:'', 
            cover:'',
            companyDetailsForm:new Form({
                name:'',
                email:'',
                number:'',
                landline:'',
                website:'',
                overview:'',
                size:'',
                city:'',
                town:'',
                baranggay:'',
                street:''
            }),
            editor: ClassicEditor,
            editorConfig: {
                toolbar: [ 'heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote','undo','redo'],
                    // The configuration of the rich-text editor.
            }
        }
    },
    mounted(){
        this.getCompanyData();
    }
    ,
    methods:{
        save(){
            let vm = this;
            vm.spinLoading3();
            vm.companyDetailsForm.post('/company/save-profile')
             .then( ({data}) => {
                vm.spinLoading3();
                console.log(data);
                swal('Save','','success');
                this.getCompanyData();
                }).catch(function(error){
                  vm.spinLoading3();
                  swal('Aborted! ',"Errors detected","error");
                });
                     
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        spinLoading2(){
            this.spinner2 = this.spinner2 != 1 ?1:'';
        },
        spinLoading3(){
            this.spinner3 = this.spinner3 != 1 ?1:'';
        },
        uploadLogo(e){
            var vm = this;
            console.log(e);
            const formData = new FormData;
            let files1 = e.target.files[0];
            console.log(files1);
            formData.append('filetoupload',files1);
            formData.append('type','company_logo');
            console.log(formData);
            vm.$validator.validateAll(['companyLogo']).then(function(result){
                if(result){
                    vm.spinLoading();
                    axios.post('/company/upload-logo',formData,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function(res){
                        vm.$data.logo = res.data;
                        console.log(res);
                        swal('Uploaded','','success');
                        vm.spinLoading();
                        vm.getCompanyData();
                    }).catch(function(errors){
                        console.log(errors);
                        vm.spinLoading();
                    });
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            });   
        },
        uploadCover(e){
            var vm = this;
            console.log(e);
            const formData = new FormData;
            let files1 = e.target.files[0];
            console.log(files1);
            formData.append('filetoupload',files1);
            formData.append('type','company_cover');
            console.log(formData);
            vm.$validator.validateAll(['companyCover']).then(function(result){
                if(result){
                    vm.spinLoading2();
                    axios.post('/company/upload-logo',formData,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function(res){
                        vm.$data.logo = res.data;
                        console.log(res);
                        swal('Uploaded','','success');
                        vm.spinLoading2();
                        vm.getCompanyData();
                    }).catch(function(errors){
                        console.log(errors);
                        vm.spinLoading2();
                    });
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            });   
        },
        getCompanyData(){
            let vm = this;
            axios.post('/company/all-data',{

            })
            .then(function(res){
                console.log(res.data);
             
                vm.cover = res.data.cover;
                vm.logo = res.data.logo;
                vm.companyDetailsForm.name = res.data.detail.name;
                vm.companyDetailsForm.email = res.data.detail.email;
                vm.companyDetailsForm.number = res.data.detail.contact_number;
                vm.companyDetailsForm.landline = res.data.detail.land_line;
                vm.companyDetailsForm.website = res.data.detail.website;
                vm.companyDetailsForm.size = res.data.detail.company_size;
                vm.companyDetailsForm.overview = res.data.detail.overview != undefined && res.data.detail.overview != null && res.data.detail.overview != ''?res.data.detail.overview:'No content';

                vm.companyDetailsForm.street = res.data.address.street;
                vm.companyDetailsForm.town = res.data.address.town;
                vm.companyDetailsForm.baranggay = res.data.address.barangay;
                vm.companyDetailsForm.city = res.data.address.city;

            })
            .catch(function(error){
                console.log(error);
            })
        }
    }
}
</script>



