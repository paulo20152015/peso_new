<template>
    <div class="jumbotron col-lg-6 offset-lg-3 bg-dark">
    <h3 class="text-center">Account Settings</h3>
    <div class="" >
            <h4>Username : {{userData.username}}</h4>
            <h5>Current mobile number : {{userData.number}}</h5>
       <div class="form-group">
           <hr>
            <label for="number">Mobile number * </label>
            <input @keyup.enter='updateCompany()' v-validate.continues="'required|digits:11'" v-model="companyNumber.number"  name="number" type="text" class="form-control" id="number" placeholder="Enter new mobile number">
            <div> 
               <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-for="(error,index) in errors.collect('number')" :key='"number"+index'>
                        {{ error }}
                    </p>  
                </transition-group>    
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyNumber.errors.has('number')">
                        {{ companyNumber.errors.get('number') }}
                    </p>
                </transition>          
            </div>
            <button :disabled="companyNumber.busy" @click="updateCompany()" type="submit" class="btn btn-primary mt-2" >
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                Update Mobile
            </button>
        </div>
        <hr>
        <div class="form-group">
            <label for="current_password">current password * </label>
            <input  v-model="companyPassword.pass" name="current_password" type="password" class="form-control" id="current_password" placeholder="Enter current password">
            <div> 
                            
            </div>
        </div>
        <div class="form-group">
            <label for="password">New Password* </label>
            <input v-validate.continues="'required|min:8|max:30'"  v-model="companyPassword.password" name="password" type="password" class="form-control" id="password" placeholder="Enter new password" ref="password">
            <div> 
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                 <p class='error' v-for="(error,index) in errors.collect('password')" :key='"password"+index'>
                       {{ error }}
                 </p>  
               </transition-group>  
               <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="companyPassword.errors.has('password')">
                        {{ companyPassword.errors.get('password') }}
                    </p>
                </transition>             
            </div>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password* </label>
            <input  v-validate.continues="'required|confirmed:password'"  v-model="companyPassword.password_confirmation"  name="password_confirmation" type="password" class="form-control" id="confirm" placeholder="Enter new password again" data-vv-as="password">
            <div> 
               <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                 <p class='error' v-for="(error,index) in errors.collect('password_confirmation')" :key='"password_confirmation"+index'>
                       {{ error }}
                 </p>  
               </transition-group>          
            </div>
            <button :disabled="companyPassword.busy" type="button" class="btn btn-primary mt-2" @click="updateCompanyPassword()">
                <i  v-if='spinnerPass == 1'><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                Update Password
            </button>
        </div>
    </div>
</div>
</template>
<script>
export default {
    data(){
        return{
            spinner:'',
            spinnerPass:'',
            userData: {
                username:'',
                number:'',
            },
            companyNumber:new Form({
                number:''
            }),
            companyPassword:new Form({
                pass:'',
                password:'',
                password_confirmation:''
            }),
        }
    },
    mounted(){
        this.getCompanyData();
    }
    ,
    methods:{
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        spinLoadingPass(){
            this.spinnerPass = this.spinnerPass != 1 ?1:'';
        },
        updateCompany(){
            let vm = this;
            vm.spinLoading();
            vm.companyNumber.post('/company/updateself')
            .then( ({data}) => {
                console.log(data);
                swal('Success',data,'success');
                vm.getCompanyData();
                vm.spinLoading();
            }).catch(function(error){
                vm.spinLoading();
                console.log(error.data);
            });
        },
        updateCompanyPassword(){
            let vm = this;
            vm.spinLoadingPass();
            vm.companyPassword.post('/company/updateself-pass')
            .then( ({data}) => {
                console.log(data);
                swal('Response',data);
                vm.spinLoadingPass();
                vm.getCompanyData();
            }).catch(function(error){
                vm.spinLoadingPass();
                console.log(error.data);
            });
        },
        getCompanyData(){
            let vm = this;
            axios.post('/company/data',{

            })
            .then(function(res){
                vm.userData.username = res.data.username;
                vm.userData.number = res.data.number;
                console.log(res.data);
            })
            .catch(function(error){
              
                console.log(error.data);
            })
        }
    }
}
</script>
