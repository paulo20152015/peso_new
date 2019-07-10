<template>
<div class="container-fluid">
    <ul class="nav  nav-pills bg-dark justify-content-center">
        <li class="nav-item">
            <a class="nav-link" :class="{'active': employment_status =='ongoing'}" @click="employment_tab('ongoing')" href="#">Current(ongoing)</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" :class="{'active': employment_status =='ended'}" @click="employment_tab('ended')" href="#">Past(ended)</a>
        </li>
    </ul>
    <hr>
    <div class="table-responsive">
        <form @submit.prevent="getEmployees()" class="form-inline ml-3 mb-3">
            <div class="input-group input-group-sm">
                <input class="form-control form-control-navbar" v-model="search" type="search" placeholder="Search by last name" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-navbar" type="submit">
                        <i class="fa fa-search"></i>
                    </button>
                </div>
            </div>
        </form>
        <table class="table table-bordered table-hover table-striped ">
            <caption>Employment Records</caption>
            <thead class="bg-secondary">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Name</th>
                <th scope="col">number</th>
                <th scope="col">Position</th>
                <th scope="col">Start date</th>
                <th scope="col">End date</th>
                <th scope="col">Status</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(employee,index) in employees" :key="'employee'+index">
                <th >{{index+1}}</th>
                <td>
                 <a :href="'/admin/applicant/'+employee.applicant_account_id">
                    {{employee.lname}},
                    {{employee.fname}} 
                    {{employee.mname}}
                 </a>   
                </td>
                <td>{{employee.contact_num}}</td>
                <td>{{employee.position}}</td>
                <th >{{employee.start_date == null || employee.start_date == ''?'':readable_date(employee.start_date)}}</th>
                <td>{{employee.end_date == null || employee.end_date == ''?'':readable_date(employee.end_date)}}</td>
                <td>{{employee.status}}</td>
              </tr>
            </tbody>
          </table>
    </div>
</div>
</template>
<script>
export default {
    data(){
        return{
            employment_status:'ongoing',
            search:'',
            total:'',
            employees:'',
        }
    },
    mounted(){
        this.getEmployees();
    },
    methods:{
        readable_date(date){
           return moment(date).format("MMMM D YYYY"); 
        },
        employment_tab(status){
            let vm = this;
            vm.employment_status = status;
            vm.search = '';
            vm.getEmployees();
        },
        getEmployees(url = '/admin/company/returnEmployees'){
            let vm = this;
            vm.current_page = url;
            axios.post(url,{
                search:vm.search,
                employment_status:vm.employment_status
            })
            .then(function(res){
                vm.employees = res.data;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            });
        }
    }
}
</script>

