<template>
    <div>
        <div class="modal fade" id="modalCreateCompany" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create Company Account</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent="createCompany()">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username * </label>
                        <input v-validate.continues="'required|min:8|max:30'" v-model="companyCreateForm.username"  name="username" type="text" class="form-control" id="username" placeholder="Enter company username">
                        <div> 
                            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-for="(error,index) in errors.collect('username')" :key='"username"+index'>
                                    {{ error }}
                                </p>  
                            </transition-group>
                            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="companyCreateForm.errors.has('username')">
                                {{ companyCreateForm.errors.get('username') }}
                                </p>
                            </transition>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name">Name * </label>
                        <input v-validate.continues="'required|max:40'" v-model="companyCreateForm.name"  name="name" type="text" class="form-control" id="name" placeholder="Enter company name">
                        <div> 
                            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-for="(error,index) in errors.collect('name')" :key='"name"+index'>
                                    {{ error }}
                                </p>  
                            </transition-group>
                            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="companyCreateForm.errors.has('name')">
                                {{ companyCreateForm.errors.get('name') }}
                                </p>
                            </transition>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email * </label>
                        <input v-validate.continues="'required|email|min:8|max:40'" v-model="companyCreateForm.email"  name="email" type="email" class="form-control" id="email" placeholder="Enter company email address">
                        <div> 
                            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-for="(error,index) in errors.collect('email')" :key='"email"+index'>
                                    {{ error }}
                                </p>  
                            </transition-group>
                            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="companyCreateForm.errors.has('email')">
                                {{ companyCreateForm.errors.get('email') }}
                                </p>
                            </transition>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="number">Mobile number * </label>
                        <input v-validate.continues="'required|digits:11'" v-model="companyCreateForm.number"  name="number" type="text" class="form-control" id="number" placeholder="Enter company mobile number">
                        <div> 
                            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-for="(error,index) in errors.collect('number')" :key='"number"+index'>
                                    {{ error }}
                                </p>  
                            </transition-group>
                            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="companyCreateForm.errors.has('number')">
                                {{ companyCreateForm.errors.get('number') }}
                                </p>
                            </transition>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="remember"><input type="checkbox" name="remember" id="remember" @click='autoGenerate()'>Use Default Password : 12345678</label>
                    </div>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                    <div class="form-group">
                        <label for="password">Password * </label>
                        <input   v-validate.continues="'required|min:8|max:40'" v-model="companyCreateForm.password" name="password" type="password" class="form-control" id="password" placeholder="Enter Company password">
                        <div> 
                            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-for="(error,index) in errors.collect('password')" :key='"password"+index'>
                                    {{ error }}
                                </p>  
                            </transition-group>
                            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="companyCreateForm.errors.has('password')">
                                {{ companyCreateForm.errors.get('password') }}
                                </p>
                            </transition>
                        </div>
                    </div>
                    </transition>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" :disabled='disable == 1'>
                        <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                        Create
                    </button>
                </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    props:['currentPage'],
    data(){
        return {
            autopassword:'',
            spinner:'',
            disable:'',
            companyCreateForm:new Form({
                username:'',
                name:'',
                email:'',
                number:'',
                password:''
            }),
        }
    },
    methods:{
        autoGenerate(){
            //this.autopassword = this.autopassword != 1?1:'';
            if(this.autopassword != 1){
                this.autopassword = 1;
                this.companyCreateForm.password = '12345678';
            }else{
                this.autopassword = '';
                this.companyCreateForm.password = '';
            }
        },
        parentGetCompanies(url = '/admin/companies/data'){
            this.$parent.getCompanies(url);
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        }
        ,
        createCompany(){
            let vm = this;
            this.$validator.validateAll(['username','password','email','number','password']).then(function(result){
                if(result){
                    vm.spinLoading();
                    vm.disable = 1;
                    vm.companyCreateForm.post('/admin/companies/create')
                    .then( ({data}) => {
                         vm.spinLoading();
                         console.log(data);
                         swal('Created',data,'success');
                         vm.parentGetCompanies(vm.currentPage);
                         vm.disable = '';
                          }).catch(function(error){
                         vm.spinLoading();
                         vm.disable = '';
                          });
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            }); 
        }
    }
    
}
</script>

