<template>
    <div class="container">
        <h5>Ratings</h5>
        <p>Average Rating : {{totalRatingValue}}</p>
        <hr>
        <div v-for="(rating,index) in ratings" :key="index" class="row border-bottom border-secondary p-2">
            <div class="col-lg-11">
                <blockquote> Rating : <span class="badge badge-secondary">{{rating.rate}} of 10</span> <i> <br>{{rating.message}}</i> 
                    <strong class="blockquote-footer">{{rating.applicant_account.username}}</strong><small class="text-muted">{{humanTime(rating.created_at)}}</small>
                </blockquote>
            </div>
        </div>  
    </div>
</template>
<script>
export default {
    data(){
        return{
            totalRatingValue:'',
            ratings:''
        }
    },
    mounted(){
        this.getCompanyRatings();
    },
    methods:{
        humanTime:function(dateAndTime){
                    return moment(dateAndTime).fromNow();
        },
        totalRating(arrayRating){
           let totalAve = 0;
           let count = 0;
           arrayRating.forEach(function(rate){
               totalAve += parseInt(rate.rate);
               count++;
           });
            this.totalRatingValue = parseInt(totalAve/count);
        },
        getCompanyRatings(){
            let vm = this;
            axios.post('/company/ratings')
            .then(function(res){
                vm.ratings = res.data;
                vm.totalRating(res.data);
                console.log(res.data);
            })
            .catch(function(error){
                console.log(error);
            });
        }
    }
}
</script>

