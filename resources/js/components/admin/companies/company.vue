<template>
    <div>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a @click="toggleComponents('company_data')" :class="{'bot-triangle-nav':company_data==1}" class="nav-link" href="#">Company</a>
                </li>
                <li class="nav-item">
                    <a @click="toggleComponents('job_post')" :class="{'bot-triangle-nav':job_post==1}" class="nav-link" href="#">Job Posts</a>
                </li>
                <li class="nav-item">
                    <a @click="toggleComponents('employment')" :class="{'bot-triangle-nav':employment==1}" class="nav-link" href="#">Employment</a>
                </li>
                </ul>
            </div>
        </nav>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <company-data v-if='company_data == 1'></company-data>
        </transition>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <company-jobposts v-if='job_post == 1'></company-jobposts>
        </transition>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <company-employment v-if='employment == 1'></company-employment>
        </transition>
    </div>
</template>
<script>
export default {
    data(){
        return{
            company_data:1,
            job_post:'',
            employment:''
        }
    },
    methods:{
        toggleComponents(val){
            let vm = this;
            switch (val) {
                case 'company_data':
                    vm.company_data = 1;
                    vm.job_post = '';
                    vm.employment = '';
                    break;
                case 'job_post':
                    vm.company_data = '';
                    vm.job_post = 1;
                    vm.employment = '';
                    break;
                case 'employment':
                    vm.company_data = '';
                    vm.job_post = '';
                    vm.employment = 1;
                    break;   
                default:
                    break;
            }
        },
    }
}
</script>

