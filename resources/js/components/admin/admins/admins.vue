<template>
    <div>
            <!-- Main content -->
            <div class="content">      
            <div class="container-fluid">
                <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <button type="button" class="btn btn-primary triangle" data-toggle="modal" data-target="#exampleModalCenter">
                        <i class="fa fa-plus"></i>admin
                    </button>
                    <div class="card  card-outline table-responsive">
                        <div class="card-header bg-dark">
                            <h5 class="m-0">Admins Table</h5>
                        </div>
                        <div class="card-body">
                            <div class="form mb-3">
                                <div class="input-group">
                                    <input class="form-control " @keyup='sortAdmin()' @keyup.delete="sortAdmin()"  @keyup.ctrl='sortAdmin()' type="search" v-model="search" placeholder="Search username or number" aria-label="Search">
                                    <div class="input-group-append">
                                        <button class="btn btn-navbar" @click='sortAdmin()'>
                                            <i class="fa fa-search"></i>
                                        </button>
                                    </div>
                                    <button class="btn btn-navbar" @click='showFilters'>
                                        {{filterFlag == 1?'hide filter':'show filter'}}
                                    </button>
                                </div>
                            </div>
                             <transition  enter-active-class="animated rotateInDownLeft" leave-active-class="animated rotateOutDownLeft">
                            <div class="row" v-if='filterFlag == 1'>
                                <div class="col-lg-4 ">
                                    <p>Sort By: </p>
                                    <div class="input-group input-group-sm">
                                            <label >Authority : </label>
                                            <select  class="form-control " @change='sortAdmin()' v-model="sortFilter[0].value">
                                                <option value='0' selected>none</option>
                                                <option value='1'>Admin</option>
                                                <option value='2'>MasterAdmin</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <p>Order By: </p>
                                    <div class="input-group input-group-sm mb-2">
                                            <label >Date : </label>
                                            <select class="form-control" @change='sortAdmin()' v-model="orderFilter[0].value">
                                                <option value='0' selected>none</option>
                                                <option value='1'>Oldest</option>
                                                <option value='2'>Recent</option>
                                            </select>
                                    </div>
                                    <div class="input-group input-group-sm">
                                            <label >Alphabetically : </label>
                                            <select class="form-control" @change='sortAdmin()' v-model="orderFilter[1].value">
                                                <option value='0' selected>none</option>
                                                <option value='1'>Ascending</option>
                                                <option value='2'>Descending</option>
                                            </select>
                                    </div>
                                </div>
                                <div class="col-lg-4 ml-5 mt-2 mb-2">
                                <button class="btn btn-secondary btn-sm" @click="reset()">Remove Filters</button>
                                </div>
                            </div>
                             </transition>
                            <div class="progress">
                                <div class="progress-bar" :style='{width:(links.current_page*100)/links.last_page +"%"}' role="progressbar" :aria-valuenow="links.current_page" aria-valuemin="0" :aria-valuemax="links.last_page">
                                    Page {{links.current_page+' of '+links.last_page}}
                                </div>
                            </div>
                            <table class="table table-bordered table-striped table-sm mt-2">
                                <thead class="thead-dark">
                                    <tr>
                                        <th class="fit-table-content">#</th>
                                        <th>Username</th>
                                        <th>Authority</th>
                                        <th>Mobile Number</th>
                                        <th>Created</th>
                                        <th>Updated</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody v-if="admins.length != 0 || typeof(admins) != undefined">
                                    <tr v-for="(admin,index) in admins" :key='index'>
                                        <td class="fit-table-content">{{parseInt(links.current_page) * 10 - 10 + (index+1)}}</td>
                                        <td >{{admin.username}}</td>
                                        <td >{{admin.level > 1?'Master Admin':'Admin'}}</td>
                                        <td >{{admin.number != null && admin.number!=''?admin.number:'N/A'}}</td>
                                        <td >{{humanTime(admin.created_at)}}</td>
                                        <td >{{humanTime(admin.updated_at)}}</td>
                                        <td class="fit-table-content">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-secondary" data-toggle="modal"  :data-target='"#modal"+admin.id' >Update</button>
                                                <button type="button" class="btn btn-danger" @click='archive(admin.username,admin.id)'>Archive</button>
                                            </div>
                                        </td>
                                        <admins-edit :admindata='admin' :adminuser='admin.username' :current_page='current_page'></admins-edit>
                                    </tr>
                                </tbody>
                            </table>
                            <admins-pagination :links='links' class='mt-2'></admins-pagination>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
                </div>
                <!-- /.row -->
            </div><!-- /.container-fluid -->
            </div>
            <!-- /.content -->

           <admins-create :currentPage='current_page'></admins-create>       
    </div>
</template>
<script>
export default {
    data(){
        return{
            search:'',
            sortFilter:[
                {collumn:'level',comparison:'=',value:''},
            ],
            orderFilter:[
                {collumn:'created_at',value:''},
                {collumn:'username',value:''},
                ],
            filterFlag:0,
            admins:[],
            number:0,
            adminAuthoritySort:'',
            current_page:'',
            links:{
                first_page:'',
                last_page_url:'',
                prev_page:'',
                next_page:'',
                current_page:'',
                last_page:'',
                path:''
            },
            
        }
    },
    mounted(){
        this.sortAdmin();
    }
    ,
    methods:{
        showFilters(){
            this.filterFlag = this.filterFlag ==1?0:1;
        },
        archive:function(admin,id){
            swal({
                title: "Are you sure?",
                text: "your about to archive "+admin+"!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
            .then((willDelete) => {
            if (willDelete) {
                let vm = this;
                axios.patch('/admin/admins-archive/'+id)
                .then(function(res){
                    if(res.data.result == 'success'){
                        vm.sortAdmin(vm.current_page);
                    }
                    console.log(res.data);
                }).catch(function(error){
                    console.log(error.response);
                });
                swal(admin+" has been archived!", {
                icon: "success",
                });
            } else {
                swal("Aborted");
            }
            });
        },
        sortAdmin:function(url = '/admin/filter'){
            let vm = this;
            let sortByLevel = vm.sortFilter[0].value == 1 ||vm.sortFilter[0].value == 2? vm.sortFilter[0]:'';
            let orders = vm.orderFilter;
            vm.current_page = url;
            axios.post(url,{
                sortLevel:sortByLevel,
                order:orders,
                search:vm.search
            })
            .then(function(res){
                vm.admins = res.data.data;
                vm.links.first_page = res.data.first_page_url;
                vm.links.last_page_url = res.data.last_page_url;
                vm.links.prev_page = res.data.prev_page_url;
                vm.links.next_page = res.data.next_page_url;
                vm.links.current_page = res.data.current_page;
                vm.links.last_page = res.data.last_page;
                vm.links.path = res.data.path;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error.response);
            });
        },
        reset(){
            this.search = '';
            this.sortFilter[0].value = 0;
            this.orderFilter[0].value=0;
            this.orderFilter[1].value=0;
            this.sortAdmin();
        }
        ,
        humanTime:function(dateAndTime){
            return moment(dateAndTime).fromNow();
        },
        
    }
}

</script>
<style scoped>
.triangle{
    position:relative;
    margin-bottom: -5px;
}
.triangle::before{
    content: "";
    z-index: 2;
    position: absolute;
    border:.8em solid transparent;
    border-top-color:#3490dc;
    top:85%;
    left: 1em;
}

</style>

