<template>
    <header>
        <div class="container">
            <img src="" alt="no img"> <h1>PESO</h1>
            <nav class="nav-media">
                <div class="link-1"><router-link to="/applicant">Applicant/Employee</router-link></div>
                <div class="link-2"><router-link to="/company">Company</router-link></div>
            </nav>
        </div>
    </header>
</template>
<script>
export default {
    
}
</script>
<style scoped>
    .container{
        position: relative;
        display: flex;
        flex-flow: row wrap;
        max-width: 1000px;
        text-align: center;
        align-content: center;
    }
    h1{
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        color:white;
        font-weight:700;
        letter-spacing: 1px;
    }
    img{
        justify-self: flex-start;
    }
    nav{
        margin-left: auto;
        display: flex;
        flex-flow: row wrap;
        justify-content: flex-end;
        align-content: center;
    }
    .link-1{
        border-right: 1px solid white;
        flex-shrink: 1;
    }
    .link-2{
        border-left: 1px solid white;
        flex-shrink: 1;
    }
    header{
        padding: 1em;
        width:100%;
        background: rgba(10, 0, 8, 0.87);
    }
    a{
        font-weight: 600;
        letter-spacing: 1px;
        color: rgb(202, 193, 193);
        display: block;
        padding: .5em 1.5em;
        text-decoration: none;
        transition: color 300ms ease-in,transform 300ms ease-in;
    }
    a:hover{
        color: white;
        transform: scale(1.2);
    }
    .banner{
        display: flex;
        flex-flow: row wrap;
    }
    .router-link-active{
         color: white;
        transform: scale(1.2);
    }
    .container::before{
        z-index: -2;
        content:'';
        width: 100%;
        height: 60vh;
        top:0;
        left: 0;
        right: 0;
        position: absolute;
        background-image: url('/img/homeback.jpg');
        background-repeat: no-repeat;
        background-position: center top;
        filter: blur(2px);
        -webkit-filter: blur(2px);
        -moz-filter: blur(2px);
    }
    @media screen and (max-width: 570px) {
        .container{
            flex-flow: column wrap;
        }
        [class*="link"]{
            display: block;
            width: 100%;
            text-align: center;
            border:none;
        }
    }
</style>


