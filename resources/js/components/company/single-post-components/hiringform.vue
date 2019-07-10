<template>
    <div class=" text-center">
        <form @submit.prevent="hireApplicant()">
            <div class="form-group">
                <label for="Position">Position </label>
                <input type="text" v-model="hireForm.position" class="form-control" id="Position" placeholder="Position title">
            </div>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="hireForm.errors.has('position')">
                    {{ hireForm.errors.get('position') }}
                </p>
            </transition>
            <div class="form-group">
                <label for="date">Start Date</label>
                <input type="date" v-model="hireForm.start_date" class="form-control" id="date">
            </div>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="hireForm.errors.has('start_date')">
                    {{ hireForm.errors.get('start_date') }}
                </p>
            </transition>
            <button :disabled="spinner == 1" type="submit" class="btn btn-primary"><i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> hire</button>
        </form> 
    </div>
</template>
<script>
export default {
    props:['applicant_id','current_page']
    ,
    data(){
        return{
            hireForm:new Form({
                position:'',
                start_date:'',
            }),
            spinner:'',
        }
    },
    methods:{
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        getApplicantData(url = '/company/job_post_applicants'){
            this.$parent.getApplicantData(url);
        },
        hireApplicant(){
            let vm = this;
            vm.spinLoading();
             vm.hireForm.post('/company/hireApplicant/'+vm.applicant_id)
            .then( ({data}) => {
                console.log(data);
                swal('Success','','success');
                vm.spinLoading();
                vm.getApplicantData(vm.current_page);
            });
        }
    }
}
</script>

