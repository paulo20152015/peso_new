<template>
    <div class="container">
        <div class="col-lg-12">
            <div v-if="test_array(cover) == true">
                <img  v-for="(img,index) in cover" :key="index" :src="img.name" style="width:100%;height:300px;background-image:url('/img/default-cover-b.png');background-size:cover;">
            </div>
            <div v-else>
                <img  style="width:100%;height:300px;background-image:url('/img/default-cover-b.png');background-size:cover;">
            </div>
            
            <p v-if="test_array(logo) == true" style="margin-top:-15%" class=" ml-3 "><img v-for="(img,index) in logo" :key="index" :src="img.name" style='max-width:100px;max-height:100px;' class="align-self-start img-fluid img-thumbnail" alt="blank"><br> <strong>{{detail.name}}</strong></p> 
            <p v-else style="margin-top:-15%" class=" ml-3 "><img src="/img/unkown.png" style='max-width:100px;max-height:100px;' class="align-self-start img-fluid img-thumbnail" alt="blank"><br> <strong>{{detail.name}}</strong></p>
        </div>
        <div class="col-lg-12 mt-5" id="personalData">
            <div class="">
                <h4 >Company Details</h4>
                <hr>
                <div class="row p-3">
                    <div class="col-lg-6">
                        <p>
                            <i class="fa fa-envelope-o" aria-hidden="true"></i>: <strong>{{detail.email}}</strong> <br>
                            website: <a :href="detail.website"><strong>{{detail.website}}</strong></a> <br>
                            contact number: <strong>{{detail.contact_number}}</strong><br>
                            landline: <strong>{{detail.land_line}}</strong><br>
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <p>
                            <i class="fa fa-map-marker" aria-hidden="true"></i> : {{address.street}} {{address.barangay}} {{address.town}} {{address.city}}<br>
                            <i class="fa fa-users" aria-hidden="true"></i> company size : {{detail.company_size}}<br>
                        </p>
                    </div>
                    <hr>
                    <div class="col-lg-12 p-3">
                        <h5>Overview</h5>
                        <p class="p-3" v-html="detail.overview">
                        </p>
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
            detail:'',
            address:'',
            logo:'',
            cover:''
        }
    },
    mounted(){
        this.getCompanyData();
    },
    methods:{
        test_array(totest){
            let flag = Array.isArray(totest) && totest.length > 0;
            return flag;
        },
        getCompanyData(){
            let vm = this;
            axios.post('/company/returnCompanyDetails')
            .then(function(res){
                vm.detail = res.data.detail;
                vm.address = res.data.address;
                vm.logo = res.data.logo;
                vm.cover = res.data.cover;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            });
        }
    }
}
</script>
