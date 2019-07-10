<template>
    <div class="row mb-5 mt-2 bg-light p-3">
        <div class="col-lg-2">
            <img :src="profile_pic == 'na'?'/img/unkown.png':profile_pic"  height="100" width="100">
            <form>
            <div class="form-group">
                <label for="profile-picture"><i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Profile Picture</label>
                <input @change="uploadProfilePic" type="file" v-validate.continues="'image|size:2000'" class="form-control-file" id="profile-picture" name='profile picture'>
            </div>
            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-for="(error,index) in errors.collect('profile picture')" :key='"profile-picture"+index'>
                    {{ error }}
                </p>  
            </transition-group>
            </form>
        </div>
        <div class="col-lg-9">
            <h5 class="mt-0">Personal Data Settings</h5>
            <div class="input-group mb-1 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Name</span>
                    </div>
                    <input v-model='personalDataForm.first_name'  type="text" name='first name'  class="form-control" placeholder="First">
                    <input v-model='personalDataForm.middle_name'  type="text" name='name'  class="form-control" placeholder="Middle">
                    <input v-model='personalDataForm.last_name'  type="text" name='name'  class="form-control" placeholder="Last">
            </div>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="personalDataForm.errors.has('first_name')">
                    {{ personalDataForm.errors.get('first_name') }}
                </p>
            </transition>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="personalDataForm.errors.has('middle_name')">
                    {{ personalDataForm.errors.get('middle_name') }}
                </p>
            </transition>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="personalDataForm.errors.has('last_name')">
                    {{ personalDataForm.errors.get('last_name') }}
                </p>
            </transition>
            <div class="input-group mb-1 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Email</span>
                    </div>
                    <input v-model='personalDataForm.email' type="email" name='email'  class="form-control" placeholder="Email address">
            </div>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="personalDataForm.errors.has('email')">
                    {{ personalDataForm.errors.get('email') }}
                </p>
            </transition>
            <div class="input-group mb-1 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Mobile Number</span>
                    </div>
                    <input v-model='personalDataForm.number' type="text" name='number'  class="form-control" placeholder="Mobile Number">
            </div>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="personalDataForm.errors.has('number')">
                    {{ personalDataForm.errors.get('number') }}
                </p>
            </transition>
            <div class="input-group mb-1 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Civil Status</span>
                    </div>
                    <select  v-model='personalDataForm.civil_status' class='form-control'  name="civil_status" id="civil_status">
                        <option value=""> </option>
                        <option value="Married">Married </option>
                        <option value="Widowed">Widowed </option>
                        <option value="Separated">Separated </option>
                        <option value="Divorced">Divorced </option>
                        <option value="Single">Single </option>
                    </select>
            </div>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="personalDataForm.errors.has('civil_status')">
                    {{ personalDataForm.errors.get('civil_status') }}
                </p>
            </transition>
            <div class="input-group mb-1 ">
                    <div class="input-group-prepend">
                        <span class="input-group-text">Date of Birth</span>
                    </div>
                    <input  v-model='personalDataForm.date_of_birth' type="date" name='name'  class="form-control" placeholder="dob">
            </div>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="personalDataForm.errors.has('date_of_birth')">
                    {{ personalDataForm.errors.get('date_of_birth') }}
                </p>
            </transition>
            <button :disabled='personalDataForm.busy' @click="save()" class="btn btn-primary">
                <i v-if="spinner1 == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> 
                save</button>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            spinner:'',
            spinner1:'',
            profile_pic:'',
            personalDataForm:new Form({
                first_name:'',
                middle_name:'',
                last_name:'',
                email:'',
                number:'',
                civil_status:'',
                date_of_birth:'',
            }),
        }
    },
    mounted(){
        this.getPersonalData();
    }
    ,
    methods:{
        uploadProfilePic(e){
            var vm = this;
            console.log(e);
            const formData = new FormData;
            let files1 = e.target.files[0];
            console.log(files1);
            formData.append('filetoupload',files1);
            formData.append('type','profile_pic');
            console.log(formData);
            vm.$validator.validateAll(['profile picture']).then(function(result){
                if(result){
                    vm.spinLoading();
                    axios.post('/applicant/uploadImage',formData,{
                        headers: {
                            'Content-Type': 'multipart/form-data'
                        }
                    }).then(function(res){
                        swal('Uploaded','','success');
                        vm.spinLoading();
                        vm.getPersonalData();
                    }).catch(function(errors){
                        console.log(errors);
                        vm.spinLoading();
                    });
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            });   
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        spinLoading1(){
            this.spinner1 = this.spinner1 != 1 ?1:'';
        },
        save(){
            let vm = this;
            vm.spinLoading1();
            vm.personalDataForm.post('/applicant/updatePersonalData')
             .then( ({data}) => {
                vm.spinLoading1();
                console.log(data);
                swal('Save','','success');
                vm.getPersonalData();
            }).catch(function(error){
                vm.spinLoading1();
                vm.getPersonalData();
                swal('Aborted! ',"Errors detected","error");
            });      
        },
        getPersonalData(){
            let vm = this;
            axios.post('/applicant/profile_data', {
                
            })
            .then(function (response) {
                vm.personalDataForm.first_name = response.data.personal_data.fname;
                vm.personalDataForm.middle_name = response.data.personal_data.mname;
                vm.personalDataForm.last_name = response.data.personal_data.lname;
                vm.personalDataForm.email = response.data.personal_data.email;
                vm.personalDataForm.number = response.data.personal_data.contact_num;
                vm.personalDataForm.civil_status = response.data.personal_data.civil_status;
                vm.personalDataForm.date_of_birth = response.data.personal_data.date_of_birth;
                vm.profile_pic = response.data.profile_pic;
                console.log(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }
}
</script>

