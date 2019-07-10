<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-light p-3 bg-secondary text-white">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
            <a class="navbar-brand" href="#" style="margin-right:auto">Job Posts Management</a>
            <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
                <li class="nav-item active">
                    <a class="nav-link badge-secondary" href="#" @click="toggleComponents('manage_content')" >Manage Content</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link badge-secondary" href="#" @click="toggleComponents('manage_application')" >Application Management</a>
                </li>
            </ul>
            </div>
        </nav>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <manage-content v-if='manage_content == 1' ></manage-content>
        </transition>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <application-management v-if='manage_application == 1' ></application-management>
        </transition>
    </div>
</template>
<script>
export default {
    data(){
        return{
            postdata:'',
            manage_content:1,
            manage_application:''
        }
    },
    mounted(){
 
    },
    methods:{
        toggleComponents(val){
            let vm = this;
            switch (val) {
                case 'manage_content':
                    vm.manage_content = 1;
                    vm.manage_application = '';
                    break;
                case 'manage_application':
                    vm.manage_content = '';
                    vm.manage_application = 1;
                    break;    
                default:
                    break;
            }
        },
       
    }
}
</script>

