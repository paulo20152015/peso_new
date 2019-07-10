<template>
    <div>
        <hr>
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
            <button @click="updateRating(rating.id)"  type="button" class="btn btn-primary">
                <i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i>
                    update
            </button>
    </div>
</template>
<script>
export default {
    props:['rating']
    ,
    data(){
        return{
            newRatingForm:new Form({
                rate:this.rating.rate,
                comment:this.rating.message,
            }),
            spinner:'',
        }
    },
    methods:{
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        EmployeeRating(url = '/company/returnEmployees'){
            this.$parent.getEmployeeRatings(url);
        },
        updateRating(id){
            let vm = this;
            vm.spinLoading();
             vm.newRatingForm.post('/company/updateApplicantRating/'+id)
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

