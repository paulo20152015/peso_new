<template>
<div>
    <div class="input-group mb-3">
        <div  class="input-group mb-3">
        <input v-model="townForm.town" type="text" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary" type="button" id="button-addon1" @click="UpdateTown(town.id)">
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                Update</button>
        </div>
    </div>
    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
        <p class='error' v-if="townForm.errors.has('town')">
            {{ townForm.errors.get('town') }}
        </p>
    </transition>
    </div>
</div>
</template>
<script>
export default {
    props:['town'],
    data(){
        return{
            spinner:'',
            townForm:new Form({
                town:this.town.town
            }),
        }
    }
    ,
    methods:{
        spinloading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        UpdateTown(id){
            let vm = this;
            vm.spinloading();
             vm.townForm.post('/admin/updateTown/'+id)
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

