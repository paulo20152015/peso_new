<template>
    <div class="container-fluid">
        <ul class="nav justify-content-center bg-primary mb-2">
            <li class="nav-item">
                <a @click="toggleComponents('company_data')" :class="{'bot-triangle-nav':company_data==1}" class="nav-link" href="#">Company</a>
            </li>
            <li class="nav-item">
                <a @click="toggleComponents('job_post')" :class="{'bot-triangle-nav':job_post==1}" class="nav-link" href="#">Job Posts</a>
            </li>
        </ul>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <company-data v-if='company_data == 1'></company-data>
        </transition>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <jobpost-component :company_post="'yes'" v-if='job_post == 1'></jobpost-component>
        </transition>
    </div>
</template>
<script>
export default {
    data(){
        return{
            company_data:1,
            job_post:'',
        }
    },
    methods:{
        toggleComponents(val){
            let vm = this;
            switch (val) {
                case 'company_data':
                    vm.company_data = 1;
                    vm.job_post = '';
                    break;
                case 'job_post':
                    vm.company_data = '';
                    vm.job_post = 1;
                    break; 
                default:
                    break;
            }
        },
    }
}
</script>

