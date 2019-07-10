<template>
<div class="container-fluid">
    
    <hr>
    <div class="table-responsive">
        <table class="table table-bordered table-hover table-striped ">
            <caption>Employment Records</caption>
            <thead class="bg-secondary">
              <tr>
                <th scope="col">#</th>
                <th scope="col">Company</th>
                <th scope="col">Position</th>
                <th scope="col">Start date</th>
                <th scope="col">End date</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(employee,index) in employment" :key="'employee'+index">
                <th >{{index+1}}</th>
                <td>
                 <a :href="'/applicant/company/'+employee.company_detail.company_account_id">
                    {{employee.company_detail.name}},
                 </a>   
                </td>
                <td>{{employee.position}}</td>
                <th >{{employee.start_date == null || employee.start_date == ''?'':readable_date(employee.start_date)}}</th>
                <td>{{employee.end_date == null || employee.end_date == ''?'':readable_date(employee.end_date)}}</td>
                <td>{{employee.status}}</td>
                <td>
                    <create-rating-show :employee='employee' :index='index'></create-rating-show>
                </td>
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
            total:'',
            employment:'',
        }
    },
    mounted(){
        this.getEmployees();
    },
    methods:{
        readable_date(date){
           return moment(date).format("MMMM D YYYY"); 
        },
        getEmployees(url = '/applicant/employmentRecords'){
            let vm = this;
            axios.post(url,{
                
            })
            .then(function(res){
                vm.employment = res.data;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            });
        }
    }
}
</script>

