<template>
    <div>
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#specialization">
                    Job Specialization <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                </button></li>
                <li class="breadcrumb-item"><button type="button" class="btn btn-primary" data-toggle="modal" data-target="#city">
                    City <i class="fa fa-plus-square-o" aria-hidden="true"></i>
                </button></li>
                </ol>
            </nav>
            <div class="row">
            <div class="col-lg-6">
                <h5>Specializations</h5>
                <div v-for="(spe,index) in specialization" :key="index">
                    <p>#{{index+1}}</p>
                    <specialization  :speciali='spe'></specialization>
                </div>
                
            </div>

            <div class="col-lg-6">
                <h5>Cities and Towns</h5>
                <div v-for="(city,index) in city" :key="index">
                    <p>#{{index+1}}</p>
                    <city  :cit='city'></city>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="specialization" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Job Specialization Form</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="jobspecialization">Job Specialization * </label>
                        <input v-model="specializationCreateForm.specialization"   name="specialization" type="text" class="form-control" id="jobspecialization" placeholder="Enter a job specialization">
                        <div> 
                            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="specializationCreateForm.errors.has('specialization')">
                                {{ specializationCreateForm.errors.get('specialization') }}
                                </p>
                            </transition>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button  type="button" class="btn btn-primary" @click="addSpecialization()">
                     <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                     Add Specialization</button>
                </div>
            </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="city" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    
                    
                    <div class="form-group">
                        <label for="City">City </label>
                        <input v-model="cityCreateForm.city"   name="City" type="text" class="form-control" id="City" placeholder="Enter a city name">
                        <div> 
                            <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="cityCreateForm.errors.has('city')">
                                {{ cityCreateForm.errors.get('city') }}
                                </p>
                            </transition>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="addCity()">
                    <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                    Add City</button>
                </div>
            </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data(){
        return{
            spinner:'',
            specialization:'',
            city:'',
            specializationCreateForm:new Form({
                specialization:''
            }),
            cityCreateForm:new Form({
                city:''
            }),
        }
    },
    mounted(){
        this.getSpecialization();
        this.getCity();
    },
    methods:{
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        addSpecialization(){
            let vm = this;
            vm.spinLoading();
             vm.specializationCreateForm.post('/admin/addSpecialization')
            .then( ({data}) => {
                console.log(data);
                swal('Success','','success');
                vm.spinLoading();
                window.location.reload();
            }).catch(function(error){
                vm.spinLoading();
            });
        },
        addCity(){
            let vm = this;
            vm.spinLoading();
             vm.cityCreateForm.post('/admin/addCity')
            .then( ({data}) => {
                console.log(data);
                swal('Success','','success');
                vm.spinLoading();
                window.location.reload();
            }).catch(function(error){
                vm.spinLoading();
            });
        },
        getSpecialization(url = '/admin/specialization'){
            let vm = this;
            axios.post(url,{
                //properties to pass
            }).then(function(res){
                vm.specialization = res.data;
                console.log(res.data);
            }).catch(function(error){
                console.log(error);
            });
        },
        getCity(url = '/admin/city'){
            let vm = this;
            axios.post(url,{
                
            })
            .then(function(res){
                vm.city = res.data;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            });
        }
    }
}
</script>

