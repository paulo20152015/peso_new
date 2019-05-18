<template>
    <div class="jumbotron col-lg-6 offset-lg-3">
    <h3 class="text-center">Account Settings </h3>
    <div class="" >
            <h4>Username : {{userData.username}}</h4>
            <h5>Authority : {{userData.level > 1?'Master Admin':'Admin'}}</h5>
            <h5>Current mobile number : {{userData.number}}</h5>
       <div class="form-group">
           <hr>
            <label for="number">Mobile number * </label>
            <input @keyup.enter='updateAdmin()' v-validate.continues="'required|digits:11'" v-model="adminNumber.number"  name="number" type="text" class="form-control" id="number" placeholder="Enter new mobile number">
            <div> 
               <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-for="(error,index) in errors.collect('number')" :key='"number"+index'>
                        {{ error }}
                    </p>  
                </transition-group>    
                <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="adminNumber.errors.has('number')">
                        {{ adminNumber.errors.get('number') }}
                    </p>
                </transition>          
            </div>
            <button @click="updateAdmin()" type="submit" class="btn btn-primary mt-2" >
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                Update Mobile
            </button>
        </div>
        <hr>
        <div class="form-group">
            <label for="password">current password * </label>
            <input  v-model="adminPassword.pass" name="number" type="password" class="form-control" id="password" placeholder="Enter old password">
            <div> 
                            
            </div>
        </div>
        <div class="form-group">
            <label for="password">New Password* </label>
            <input v-validate.continues="'required|min:8|max:30'"  v-model="adminPassword.password" name="password" type="password" class="form-control" id="password" placeholder="Enter new password" ref="password">
            <div> 
                <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                 <p class='error' v-for="(error,index) in errors.collect('password')" :key='"password"+index'>
                       {{ error }}
                 </p>  
               </transition-group>  
               <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <p class='error' v-if="adminPassword.errors.has('password')">
                        {{ adminPassword.errors.get('password') }}
                    </p>
                </transition>             
            </div>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password* </label>
            <input  v-validate.continues="'required|confirmed:password'"  v-model="adminPassword.password_confirmation"  name="password_confirmation" type="password" class="form-control" id="confirm" placeholder="Enter new password again" data-vv-as="password">
            <div> 
               <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                 <p class='error' v-for="(error,index) in errors.collect('password_confirmation')" :key='"password_confirmation"+index'>
                       {{ error }}
                 </p>  
               </transition-group>          
            </div>
            <button :disabled='disable == 1' type="button" class="btn btn-primary mt-2" @click="updateAdminPassword()">
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
            disable:'',
            spinner:'',
            spinnerPass:'',
            userData: {
                username:'',
                number:'',
                level:''
            },
            adminNumber:new Form({
                number:''
            }),
            adminPassword:new Form({
                pass:'',
                password:'',
                password_confirmation:''
            }),
        }
    },
    mounted(){
        this.getAdminData();
    }
    ,
    methods:{
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        spinLoadingPass(){
            this.spinnerPass = this.spinnerPass != 1 ?1:'';
        },
        updateAdmin(){
            let vm = this;
            vm.spinLoading();
            vm.adminNumber.post('/admin/updateself')
            .then( ({data}) => {
                console.log(data);
                vm.spinLoading();
                swal('Success',data,'success');
                vm.getAdminData();
            }).catch(function(error){
                vm.spinLoading();
                console.log(error.data);
            });;
        },
        updateAdminPassword(){
            let vm = this;
            vm.spinLoadingPass();
             vm.disable = 1;
            vm.adminPassword.post('/admin/updateself-pass')
            .then( ({data}) => {
                console.log(data);
                swal('Response',data);
                vm.spinLoadingPass();
                vm.disable = '';
                vm.getAdminData();
            }).catch(function(error){
                vm.spinLoadingPass();
                vm.disable = '';
                console.log(error.data);
            });
        },
        getAdminData(){
            let vm = this;
            
            axios.post('/admin/data',{

            })
            .then(function(res){
                
                vm.userData.username = res.data.username;
                vm.userData.level = res.data.level;
                vm.userData.number = res.data.number;
                console.log(res.data);
            })
            .catch(function(error){
                
                console.log(error.data);
            })
        },
        
    }
}
</script>
