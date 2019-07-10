<template>
<div>
    <div  class="input-group mb-3">
        <input v-model="specializationForm.specialization" type="text" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary" type="button" id="button-addon1" @click="UpdateSpecialization(speciali.id)">
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                Update</button>
        </div>
    </div>
    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
        <p class='error' v-if="specializationForm.errors.has('specialization')">
            {{ specializationForm.errors.get('specialization') }}
        </p>
    </transition>
</div>
</template>
<script>
export default {
    props:['speciali'],
    data(){
        return{
            spinner:'',
            specializationForm:new Form({
                specialization:this.speciali.specialization
            }),
        }
    },
    methods:{
        spinloading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        UpdateSpecialization(id){
            let vm = this;
            vm.spinloading();
             vm.specializationForm.post('/admin/updateSpecialization/'+id)
            .then( ({data}) => {
                console.log(data);
                swal('Success','','success');
                vm.spinloading();
            }).catch(function(error){
                vm.spinloading();
            });
        },
    }
}
</script>

