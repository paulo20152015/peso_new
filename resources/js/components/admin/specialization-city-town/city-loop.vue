<template>
<div>
    <div class="input-group mb-3">
        <div  class="input-group mb-3">
        <input v-model="cityForm.city" type="text" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
        <div class="input-group-prepend">
            <button class="btn btn-outline-secondary" type="button" id="button-addon1" @click="UpdateCity(cit.id)">
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                Update</button>
        </div>
    </div>
    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
        <p class='error' v-if="cityForm.errors.has('city')">
            {{ cityForm.errors.get('city') }}
        </p>
    </transition>
    </div>
    <p>
        <a class="btn btn-outline-secondary" data-toggle="collapse" :href="'#city'+cit.id" role="button" aria-expanded="false" aria-controls="collapseExample">
            Show towns
        </a>   
    </p>
    <div class="collapse" :id="'city'+cit.id">
        <div class="card card-body">
            <div class="input-group mb-3">
                <input v-model="townCreateForm.town" type="text" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                <div class="input-group-prepend">
                    <button @click="addTown(cit.id)" class="btn btn-outline-secondary" type="button" id="button-addon1">
                        <i v-if="spinner2 == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                        Add <i class="fa fa-plus-square-o" aria-hidden="true"></i></button>
                </div>
            </div>
            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                <p class='error' v-if="townCreateForm.errors.has('town')">
                    {{ townCreateForm.errors.get('town') }}
                </p>
            </transition>
           <div v-for="(town,index) in towns" :key="index">
                    <p>#{{index+1}}</p>
                    <town  :town='town'></town>
            </div>
            
        </div>
    </div>
</div>
</template>
<script>
export default {
    props:['cit'],
    data(){
        return{
            towns:'',
            spinner:'',
            spinner2:'',
            cityForm:new Form({
                city:this.cit.city
            }),
            townCreateForm:new Form({
                town:''
            }),
        }
    },
    mounted(){
        this.getTowns();
    }
    ,
    methods:{
        spinloading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        spinloading2(){
            this.spinner2 = this.spinner2 != 1 ?1:'';
        },
        addTown(id){
            let vm = this;
            vm.spinloading2();
             vm.townCreateForm.post('/admin/addtown/'+id)
            .then( ({data}) => {
                console.log(data);
                swal('Success','','success');
                vm.spinloading2();
                window.location.reload();
            }).catch(function(error){
                vm.spinloading2();
            });
        },
        getTowns(url = '/admin/town/'+this.cit.id){
            let vm = this;
            axios.post(url,{
                //properties to pass
            }).then(function(res){
                vm.towns = res.data;
                console.log(res.data);
            }).catch(function(error){
                console.log(error);
            });
        },
        UpdateCity(id){
            let vm = this;
            vm.spinloading();
             vm.cityForm.post('/admin/updateCity/'+id)
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

