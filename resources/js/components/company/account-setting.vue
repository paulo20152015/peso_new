<template>
    <div class="bg-dark">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark ">
            
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item"><a class="nav-link" href="#" @click="toggleComponents('detail')">Company Details</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" @click="toggleComponents('account')">Account</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" @click="toggleComponents('myratings')">ratings</a></li>
                </ul>
            </div>
        </nav>
        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
            <company-account-setting v-if='account == 1'></company-account-setting>
        </transition>
        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
            <company-details v-if='detail == 1'></company-details>
        </transition>
        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
            <company-ratings v-if='ratings == 1'></company-ratings>
        </transition>
    </div>
</template>
<script>
export default {
    data(){
        return{
            account:'',
            detail:1,
            ratings:''
        }
    },
    methods:{
        toggleComponents(val){
            let vm = this;
            switch (val) {
                case 'account':
                    vm.ratings = '';
                    vm.account = 1;
                    vm.detail = '';
                    break;
                case 'detail':
                    vm.ratings = '';
                    vm.detail = 1;
                    vm.account = '';
                    break;
                case 'myratings':
                    vm.ratings = 1;
                    vm.detail = '';
                    vm.account = '';
                    break;    
                default:
                    break;
            }
        }
    }
}
</script>
<style scoped>

</style>


