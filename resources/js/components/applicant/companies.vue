<template>
    <div>
        <div class="container-fluid col-lg-12 row">
            <div class="col-lg-4">
                <div class="card" >
                    <div class="card-header text-white bg-secondary">
                       <strong>Company Sorting and Ordering</strong> 
                    </div>
                    <div class="card-body">
                        <p class="border-bottom border-dark">Order By :</p>
                        <div class="form-group">
                        <label for="staticEmail" class="col-sm-10 col-form-label">Alphabetically :</label>
                        <div class="col-sm-10">
                            <select  class="form-control" @change='getCompanies()' v-model="orderFilter[0].value" >
                                <option value='0' selected>none</option>
                                <option value='1'>Ascending</option>
                                <option value='2'>Descending</option>
                            </select>
                        </div>
                        </div>
                        
                        <button class="btn btn-warning btn-sm form-control col-lg-8 offset-lg-2" @click="reset()">Reset</button>
                    </div>
                </div> 
            </div>

            <div class="col-lg-8">

                <div class="card text-white bg-info">
                    <div class="card-body">
                        <div class="input-group">
                            <input class="form-control "  type="search"  @keyup='getCompanies()' @keyup.delete="getCompanies()"  @keyup.ctrl='getCompanies()' v-model="search"  placeholder="keyword search company name,number etc" aria-label="Search">
                            <div class="input-group-prepend">
                                <button class="btn input-group-text" type='button' @click="getCompanies()">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                        <p class="col-lg-12 text-left"><small>Total number of results : {{total}}</small></p>
                        <div class="progress mb-2">
                                <div class="progress-bar" style="color:black" :style='{width:(links.current_page*100)/links.last_page +"%"}' role="progressbar" :aria-valuenow="links.current_page" aria-valuemin="0" :aria-valuemax="links.last_page">
                                    Page {{links.current_page+' of '+links.last_page}}
                                </div>
                        </div>
                    </div>
                    
                </div>
                
                <div class="card" v-for='(company,index) in companies' :key='"company"+index'>
                    <div class="card-header text-white bg-dark p-1"></div>
                    <div class="card-body">
                         <p>
                            <small class="text-muted">#{{parseInt(links.current_page) * 10 - 10 + (index+1)}}</small>
                            <a :href="'/applicant/company/'+company.company_account.id" target="__blank" > 
                            <img v-for="(img,index) in company.company_files" :key="index" :src="img.name" style='max-width:50px;max-height:50px' class="align-self-start mr-3 img-fluid img-thumbnail" alt="blank"> 
                            {{company.name}}
                            </a><br>
                            <small>Address : {{company.company_address.street}}, {{company.company_address.barangay}}, {{company.company_address.town}}, {{company.company_address.city}}</small><br>
                            <small> email : {{company.email}}</small><br>
                            <small> Contact number : {{company.contact_number}}</small><br>
                            <small> Landline number : {{company.land_line}}</small><br>
                            <small class="text-muted">Company Size: {{company.company_size}}</small><br>
                        </p>
                    </div>
                    <div class="card-footer text-white bg-info p-1"></div>
                </div>

                <div class="card">
                    <div class="card-body p-0 pt-1">
                        <admins-pagination-companies :links='links' />
                    </div>
                </div>

            </div>

        </div>
        
    </div>
</template>
<script>
export default {
    props:['archivecompany'],
    data(){
        return {
            companies:[],
            search:'',
            filterFlag:1,
            current_page:'',
            total:'',
            disable:'',
            links:{
                first_page:'',
                last_page_url:'',
                prev_page:'',
                next_page:'',
                current_page:'',
                last_page:'',
                path:'', 
            },
            orderFilter:[
                {collumn:'name',value:''},
                {collumn:'last_log',value:''},
                {collumn:'created_at',value:''},
                {collumn:'updated_at',value:''},
            ],
        }
    },
    mounted(){
        this.getCompanies();
    }
    ,
    methods:{
        getCompanies(url = '/applicant/companies/data'){
            let vm = this;
            vm.current_page = url;
            let orders = vm.orderFilter;
            axios.post(url,{
                order:orders,
                search:vm.search,
                archivestatus:vm.archivecompany,
            })
            .then(function(res){
                vm.companies = res.data.data;
                vm.links.first_page = res.data.first_page_url;
                vm.links.last_page_url = res.data.last_page_url;
                vm.links.prev_page = res.data.prev_page_url;
                vm.links.next_page = res.data.next_page_url;
                vm.links.current_page = res.data.current_page;
                vm.links.last_page = res.data.last_page;
                vm.links.path = res.data.path;
                vm.total = res.data.total;
                console.log(res.data.data);
            }).catch(function(err){
                console.log(err.data);
            });
        },
        reset(){
            this.search = '';
            this.orderFilter[0].value=0;
            this.orderFilter[1].value=0;
            this.orderFilter[2].value=0;
            this.orderFilter[3].value=0;
            this.getCompanies();
        }
        ,
        humanTime:function(dateAndTime){
            return moment(dateAndTime).fromNow();
        },
    }
}
</script>
<style>

</style>


