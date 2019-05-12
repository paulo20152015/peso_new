<template>
    <img :src="logo" alt="">
</template>
<script>
export default {
    data(){
        return {
            logo:'',
        }
    },
    mounted(){
        this.getImage();
    }
    ,
    methods:{
        getImage(){
            let vm = this;
            axios.post('/company/all-data',{

            })
            .then(function(res){
                console.log(res.data);
                vm.logo = res.data.logo;
            })
            .catch(function(error){
                console.log(error);
            })
        }
    }
}
</script>
