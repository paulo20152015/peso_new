<template>
    <div class="row mb-5 mt-2 bg-light p-3">
        <div class="col-lg-6 ">
            <h5 >Account Settings</h5>
            <p>Username: {{username}}</p>
            <p>Account status: {{status == 1?'Approved':'Not yet approved'}}</p>
            <div class="input-group mb-1 ">
                <div class="input-group-prepend">
                    <span class="input-group-text">Current Password</span>
                </div>
                <input v-model="applicantPassword.pass" type="password" name='name'  class="form-control" placeholder="enter your current password">
            </div>
            <div class="input-group mb-1 ">
                <div class="input-group-prepend">
                    <span class="input-group-text">New Password</span>
                </div>
                <input type="password" v-validate.continues="'required|min:8|max:30'"  v-model="applicantPassword.password" name='password'  class="form-control" placeholder="enter your new password" ref="password">
            </div>
            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                 <p class='error' v-for="(error,index) in errors.collect('password')" :key='"password"+index'>
                       {{ error }}
                 </p>  
            </transition-group>  
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="applicantPassword.errors.has('password')">
                    {{ applicantPassword.errors.get('password') }}
                </p>
            </transition>
            <div class="input-group mb-1 ">
                <div class="input-group-prepend">
                    <span class="input-group-text">Confirm New Password</span>
                </div>
                <input type="password" v-validate.continues="'required|confirmed:password'"  v-model="applicantPassword.password_confirmation" name='password_confirmation'  class="form-control" placeholder="enter your new password again" data-vv-as="password">
            </div>
            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-for="(error,index) in errors.collect('password_confirmation')" :key='"password_confirmation"+index'>
                    {{ error }}
                </p>  
            </transition-group>
            <button :disabled="applicantPassword.busy" @click="updateApplicantPassword()" class="btn btn-primary">
                <i  v-if='spinnerPass == 1'><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                save</button>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return {
            username:'',
            status:'',
            spinnerPass:'',
            applicantPassword:new Form({
                pass:'',
                password:'',
                password_confirmation:''
            }),
        }
    }
    ,
    mounted(){
        this.getProfile();
    },
    methods:{
        spinLoadingPass(){
            this.spinnerPass = this.spinnerPass != 1 ?1:'';
        },
        updateApplicantPassword(){
            let vm = this;
            vm.spinLoadingPass();
            vm.applicantPassword.post('/applicant/changePass')
            .then( ({data}) => {
                console.log(data);
                swal('Response',data);
                vm.spinLoadingPass();
                vm.getProfile();
            }).catch(function(error){
                vm.spinLoadingPass();
                console.log(error.data);
            });
        },
        getProfile(){
            let vm = this;
            axios.post('/applicant/account_data', {
                
            })
            .then(function (response) {
                vm.username = response.data.applicant_account_data.username;
                vm.status = response.data.applicant_account_data.is_approved;
                console.log(response.data);
            })
            .catch(function (error) {
                console.log(error);
            });
        }
    }
}
</script>

