<template>
    <div>
        <div class="card m-2" id="Ratings">
            <div class="card-body">
                <h5>Ratings</h5>
                <p>Average Rating : {{totalRatingValue}}</p>
                <hr>
                <div v-for="(rating,index) in ratings" :key="index" class="row border-bottom border-secondary p-2">
                    <div class="col-lg-11">
                        <blockquote> Rating : <span class="badge badge-secondary">{{rating.rate}} of 10</span> <i> <br>{{rating.message}}</i> 
                           <a :href="'/applicant/company/'+rating.company_account_id"> <strong class="blockquote-footer">{{rating.name}}</strong></a> <small class="text-muted">{{humanTime(rating.created_at)}}</small>
                        </blockquote>
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
            totalRatingValue:'',
            ratings:''
        }
    },
    mounted(){
        this.getApplicantRatings();
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
            this.totalRatingValue = totalAve/count;
        },
        getApplicantRatings(){
            let vm = this;
            axios.post('/applicant/ratings')
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

