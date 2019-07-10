<template>
    <div class="container">
        <nav class="navbar-expand-lg navbar-dark  bg-secondary mt-2 mb-4">
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item ">
                <a  @click="toggleComponents('personal_data')" :class="{'bot-triangle-nav':personal_data==1}" class="nav-link btn-outline-dark " href="#">Pesonal Data</a>
                </li>
                <li class="nav-item ">
                <a  @click="toggleComponents('account_settings')" :class="{'bot-triangle-nav':account_settings==1}" class="nav-link btn-outline-dark" href="#">Account Settings</a>
                </li>
                <li class="nav-item">
                <a  @click="toggleComponents('resume')" :class="{'bot-triangle-nav':resume==1}" class="nav-link btn-outline-dark" href="#">Resume and Specialization</a>
                </li>
                <li class="nav-item">
                    <a  @click="toggleComponents('my_ratings')" :class="{'bot-triangle-nav':my_ratings==1}" class="nav-link btn-outline-dark" href="#">My ratings</a>
                </li>
                <li class="nav-item">
                    <a  @click="toggleComponents('job_application_history')" :class="{'bot-triangle-nav':job_application_history==1}" class="nav-link btn-outline-dark" href="#">Job application history</a>
                </li>
            </ul>
            </div>
        </nav>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <personal-data-component v-if='personal_data == 1'></personal-data-component>
        </transition>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <account-setting-component v-if='account_settings == 1'></account-setting-component>
        </transition>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <resume-specialization-component v-if='resume == 1'></resume-specialization-component>
        </transition>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <my-ratings v-if='my_ratings == 1'> </my-ratings>
        </transition>
        <transition  enter-active-class="animated bounceInRight" leave-active-class="animated fadeOutRight">
            <application-history v-if='job_application_history == 1'> </application-history>
        </transition>
    </div>
</template>
<script>
export default {
    data(){
        return {
            personal_data:1,
            account_settings:'',
            resume:'',
            my_ratings:'',
            job_application_history:''
        }
    },
    methods:{
        toggleComponents(val){
            let vm = this;
            switch (val) {
                case 'personal_data':
                    vm.personal_data = 1;
                    vm.account_settings = '';
                    vm.resume = '';
                    vm.my_ratings = '';
                    vm.job_application_history ='';
                    break;
                case 'account_settings':
                    vm.personal_data = '';
                    vm.account_settings = 1;
                    vm.resume = '';
                    vm.my_ratings = '';
                    vm.job_application_history ='';
                    break;
                case 'resume':
                    vm.personal_data = '';
                    vm.account_settings = '';
                    vm.resume = 1;
                    vm.my_ratings = '';
                    vm.job_application_history ='';
                    break;
                case 'my_ratings':
                    vm.personal_data = '';
                    vm.account_settings = '';
                    vm.resume = '';
                    vm.my_ratings = 1;
                    vm.job_application_history ='';
                    break;  
                case 'job_application_history':
                    vm.personal_data = '';
                    vm.account_settings = '';
                    vm.resume = '';
                    vm.my_ratings = '';
                    vm.job_application_history =1;
                    break;  
                default:
                    break;
            }
        },
    }
}
</script>

