<template>
    <main>
        <div class="container">
            
            <router-view></router-view>
            
        </div>
    </main>
</template>
<script>
export default {
    
}
</script>
<style scoped>

    main{
        display:block;
        width:100%;
        background: transparent;
        min-height: 60vh;
    }
    a{
        color: white;
    }
    .container{
        padding-top: 1em;
        perspective: 1000px;
        max-width: 900px;
    }
  
</style>


