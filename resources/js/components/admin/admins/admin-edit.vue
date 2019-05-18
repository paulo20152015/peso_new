<template>
            <div class="modal fade" :id="'modal'+admindata.id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-center" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalCenterTitle">Admin Edit Form </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form @submit.prevent='updateAdmin'>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="username">Username: {{admindata.username}}</label>
                        <input v-validate.continues="'min:8|alpha_dash|max:30'" name='username' v-model="adminUpdateForm.username" value='paulo'  type="text" class="form-control" id="username" placeholder="Enter new admin username">
                        <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('username')" :key='"username"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                             <p class='error' v-if="adminUpdateForm.errors.has('username')">
                            {{ adminUpdateForm.errors.get('username') }}
                            </p>
                        </transition>
                    </div>
                    <div class="form-group">
                        <label for="password">Password </label>
                        <input v-validate.continues="'min:8|max:40'" type="text" v-model='adminUpdateForm.password'  class="form-control" name="password" id="password" placeholder="Enter new desired password">
                        <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('password')" :key='"password"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                             <p class='error' v-if="adminUpdateForm.errors.has('password')">
                            {{ adminUpdateForm.errors.get('password') }}
                            </p>
                        </transition>
                    </div>
                    <div class="form-group">
                        <label for="number">Mobile Number : {{admindata.number != null && admindata.number!=''?admindata.number:'N/A'}}</label>
                        <input type="text" v-validate.continues="'digits:11'" class="form-control" id="number" name="number" v-model="adminUpdateForm.number" placeholder="Enter new admin phone number">
                        <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('number')" :key='"number"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                             <p class='error' v-if="adminUpdateForm.errors.has('number')">
                            {{ adminUpdateForm.errors.get('number') }}
                            </p>
                        </transition>
                    </div>
                    <div class="form-group ">
                        <label for="inputState">Authority level : {{admindata.level > 1?'Master Admin':'Admin'}}</label>
                        <select v-validate.continues="'integer'"  v-model='adminUpdateForm.level' id="inputState"  name='level' class="form-control col-md-4">
                            <option value='1' >Admin</option>
                            <option value='2' >Master Admin</option>
                        </select>
                        <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('level')" :key='"level"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                             <p class='error' v-if="adminUpdateForm.errors.has('level')">
                            {{ adminUpdateForm.errors.get('level') }}
                            </p>
                        </transition>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button :disabled="adminUpdateForm.busy" type="submit" class="btn btn-primary" >Update</button>
                </div>
                </form>
            </div>
            </div>
            </div>
</template>
<script>
export default {

    props:['admindata','current_page','adminuser'],
    data(){
        return{
            adminUpdateForm:new Form({
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
        },
        updateAdmin(){
            let vm = this;
            vm.adminUpdateForm.post('/admin/update/'+vm.admindata.id)
            .then( ({data}) => {
                console.log(data);
                swal('Success','','success');
                vm.parentSortAdmin(vm.current_page);
                window.location.reload();
            });
        }
        
    }
}
</script>

