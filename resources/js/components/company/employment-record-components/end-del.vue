<template>
<div>
    <div  class="input-group mb-3">
        <input type="date" v-model="endcontractForm.endDate" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
        <div class="input-group-append">
            <button :disabled="spinner == 1" @click="endContract(employment_id)" class="btn btn-outline-secondary" type="button" id="button-addon1">
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                End
            </button>
            <button @click="deleteEmployment(employment_id)" :disabled="spinner == 1" class="btn btn-outline-danger" type="button" id="button-addon1"><i class="fa fa-trash"></i></button>
        </div>
    </div>
    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
        <p class='error' v-if="endcontractForm.errors.has('endDate')">
            {{ endcontractForm.errors.get('endDate') }}
        </p>
    </transition>
</div>
</template>
<script>
export default {
    props:['employment_id','employee_id']
    ,
    data(){
        return{
            endcontractForm:new Form({
                endDate:'',
            }),
            spinner:'',
        }
    },
    methods:{
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        getEmployeesData(url = '/company/returnEmployees'){
            this.$parent.getEmployees(url);
        },
        endContract(id){
            let vm = this;
            vm.spinLoading();
             vm.endcontractForm.post('/company/employmentContract/'+id)
            .then( ({data}) => {
                console.log(data);
                swal('Success','','success');
                vm.spinLoading();
                vm.getEmployeesData();
            }).catch(function(error){
                vm.spinLoading();
                vm.getEmployeesData();
            });
        },
        deleteEmployment(id){
            let vm = this;
                swal({
                    title: "Are you sure?",
                    text: "",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                if (willDelete) {
                    vm.spinLoading();
                    axios.post('/company/employment/'+id,{
                        
                    })
                    .then(function(res){
                        vm.getEmployeesData();
                        console.log(res.data);
                        swal("Deleted successfully", {
                        icon: "success",
                        });
                        vm.spinLoading();  
                    })
                    .catch(function(error){
                        vm.getEmployeesData();
                        console.log(error);
                        vm.spinLoading();
                        swal("aborted! an error occured", {
                        icon: "warning",
                        });
                    })
                    
                } else {
                    swal("Request aborted!");
                }
            });
        },
    }
}
</script>

