<template>
    <div >
        <button type="button" class="btn btn-primary" data-toggle="modal" :data-target="'#employee'+index+employee.id">
            {{test_array(ratings) == false?'Rate':'Rated'}}
        </button>
        <div class="modal" :id="'employee'+index+employee.id" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Rate 
                            {{employee.company_detail.name}},
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <indi-rating-show v-for="(rating,index) in ratings" :key='"index"+index' :rating='rating'></indi-rating-show>
                        <hr>
                        
                        <div v-if="test_array(ratings) == false">
                        <div class="form-group">
                            <label for="rate">Rate </label>
                            <select v-model="newRatingForm.rate" name="" id="rate">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-if="newRatingForm.errors.has('rate')">
                                {{ newRatingForm.errors.get('rate') }}
                            </p>
                        </transition>                
                        <div class="form-group">
                            <label for="Message">Comment</label>
                            <input v-model="newRatingForm.comment" type="text"  class="form-control" id="Message" placeholder="comment on employee's performance">
                        </div>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-if="newRatingForm.errors.has('comment')">
                                {{ newRatingForm.errors.get('comment') }}
                            </p>
                        </transition>                
                        <button @click="rateCompany(employee.company_detail.company_account_id)" type="button" class="btn btn-primary">
                            <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                                Rate
                        </button>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>    
</template>
<script>
export default {
    props:['employee','index']
    ,
    data(){
        return{
            ratings:'',
            newRatingForm:new Form({
                rate:'',
                comment:'',
            }),
            spinner:'',
        }
    },
    mounted(){
        this.getCompanyRatings();
    },
    methods:{
        test_array(totest){
            let flag = Array.isArray(totest) && totest.length > 0;
            return flag;
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        getCompanyRatings(url = '/applicant/getCompanyRatingsByApplicant/'+this.employee.company_detail.company_account_id){
            let vm = this;
            axios.post(url,{
                
            })
            .then(function(res){
                vm.ratings = res.data;
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            });
        },
        rateCompany(id){
            let vm = this;
            vm.spinLoading();
             vm.newRatingForm.post('/applicant/rateCompany/'+id)
            .then( ({data}) => {
                console.log(data);
                swal('Success','','success');
                vm.spinLoading();
                window.location.reload();
            }).catch(function(error){
                vm.spinLoading();
                window.location.reload();
            });
        },
    }
}
</script>

