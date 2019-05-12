<template>
    <div>
    <!-- Modal -->
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-center" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Create Admin Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form  @submit.prevent="createAdmin" >
                <div class="modal-body">
                
                   <h6 class="card-title"></h6>
                    <div class="form-group">
                        <label for="username">Username * </label>
                        <input v-validate.continues="'required|min:8|alpha_dash|max:30'" v-model="adminCreateForm.username" name="username" type="text" class="form-control" id="username" placeholder="Enter admin username">
                        <div> 
                            <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('username')" :key='"username"+index'>
                                {{ error }}
                            </p>  
                            </transition-group>
                            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                             <p class='error' v-if="adminCreateForm.errors.has('username')">
                            {{ adminCreateForm.errors.get('username') }}
                            </p>
                            </transition>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="password">Password *</label>
                        <input  v-validate.continues="'required|min:8|max:40'" v-model="adminCreateForm.password" type="password" class="form-control" id="password" name="password" placeholder="Enter desired password">
                         <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('password')" :key='"code"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                         <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                             <p class='error' v-if="adminCreateForm.errors.has('password')">
                            {{ adminCreateForm.errors.get('password') }}
                            </p>
                        </transition>
                    </div>
                    <div class="form-group">
                        <label for="number">Mobile Number(optional)</label>
                        <input  v-validate.continues="'integer|digits:11'" v-model="adminCreateForm.number" type="text" class="form-control" name='number' id="number" placeholder="Enter admin phone number">
                        <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('number')" :key='"number"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                             <p class='error' v-if="adminCreateForm.errors.has('number')">
                            {{ adminCreateForm.errors.get('number') }}
                            </p>
                        </transition>
                    </div>
                    <div class="form-group ">
                        <label for="inputState">Authority level *</label>
                        <select v-validate.continues="'required|integer'" v-model="adminCreateForm.level" id="inputState" name='level' class="form-control col-md-4">
                            <option value='1' selected>Admin</option>
                            <option value='2' >Master Admin</option>
                        </select>
                         <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('level')" :key='"level"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                         <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                             <p class='error' v-if="adminCreateForm.errors.has('level')">
                            {{ adminCreateForm.errors.get('level') }}
                            </p>
                        </transition>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button :disabled="adminCreateForm.busy" type="submit" class="btn btn-primary">Create</button>
                </div>
                </form>
            </div>
        </div>
        </div> 
    </div>
</template>
<script>
export default {
    props:['currentPage']
    ,
    data(){
        return{
            adminCreateForm:new Form({
                username:'',
                password:'',
                number:'',
                level:''
            })
        }
    },
    methods:{
        parentSortAdmin(url='/admin/filter'){
            this.$parent.sortAdmin(url);
        }
        ,
        createAdmin(){
            let vm = this;
            this.$validator.validateAll(['username','password','level']).then(function(result){
                if(result){
                    vm.adminCreateForm.post('/admin/store')
                    .then( ({data}) => {
                         console.log(data);
                         swal('Success','','success');
                         vm.parentSortAdmin(vm.currentPage);
                          })
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            }); 
        }
    }
}
</script>
