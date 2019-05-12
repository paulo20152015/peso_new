<template>

    <div class="login-container">
        <div class="nav-bar">
            <a class="link" href="#" @click="toggleSingup()">Sign-up</a>
            <a class="link" href="#" @click="togglelogin()">Login</a>
        </div>
        <transition enter-active-class="animated bounceIn" leave-active-class="animated bounceOut">
        <div class="section-applicant" v-if="toggle==1">
            <h1 class="header-text">Sign-up</h1>
            <form action="">
                <div class="form-container">
                    <label for="username">Username</label>
                    <input type="text" class='input-text' name='username' placeholder="enter your username" id='username'>

                    <label for='password'>Password</label>
                    <input type="password" class='input-text' name='password' placeholder="enter your password" id='password'>

                    <button class="btn" type="submit">Login</button>
                </div>
            </form>
        </div>
        </transition>
        <transition enter-active-class="animated bounceIn" leave-active-class="animated bounceOut">
        <div class="section-applicant" v-if='toggle == 2'>
            <h1 class="header-text">Login</h1>
            <form action="" >
                <div class="form-container">
                    <label for="username">Username</label>
                    <input type="text" class='input-text' name='username' placeholder="enter your username" id='username' >

                    <label for='password'>Password</label>
                    <input type="password" class='input-text' name='password' placeholder="enter your password" id='password' >
 
                    <label for="remember"><input type="checkbox" name="remember" id="remember" > Remember Me</label>
                    <button class="btn" type="submit">Login</button>
                </div>
            </form>
        </div>
        </transition>
    </div>
</template>
<script>

export default {
    
    name:"admin-login-component",
    data(){
        return {
           toggle:'',
        }
    },
    methods:{
       toggleSingup(){
           this.toggle = this.toggle != 1?1:'';
       }
       ,
       togglelogin(){
           this.toggle = this.toggle != 2?2:'';
       }
    }
}
</script>
<style scoped>
.login-container{
    max-width:400px;
    margin: 0 auto;
    padding: .5em;
    border-radius: 5px;
    display: flex;
    flex-flow: row wrap;
}
.nav-bar{
    background: rgba(0, 0, 0, 0.884);
    flex: 1 1 100%;
    padding: 2em;
    text-align: center;
    border-radius: 4em;
}
.link{
    outline: none;
    display: inline-block;
    color: aquamarine;
    font-weight: 400;
    margin: 0 1em 0 1em;
    font-size: 1.4em;
    text-decoration: none;
    transition: all 500ms ease-in-out;
}
.link:hover{
    color: rgb(87, 209, 142);
    border-bottom: 2px solid white;
}
.section-applicant{
    flex: 1 1 100%;
    background: rgba(0, 0, 0, 0.884);
    margin-top:1em;
    padding: 1em;
    border-radius: .5em;
    display: flex;
    flex-flow: column wrap;
}
.header-text{
    color: beige;
    text-align: center;
    font-size: 1.5em;
    padding: .7em;
    border-bottom: 3px solid beige;
}
.form-container{
    display: flex;
    flex-flow: column wrap;
}
label{
    color:rgb(211, 189, 189);
    width: 100%;
    letter-spacing: 1px;
    padding: .5em;
}
.input-text{
    color: rgb(12, 11, 10);
    font-weight: 700;
    padding: .5em .5em 1em .5em;
    letter-spacing: 1px;
    background-color: rgba(134, 226, 226, 0.404);
    border:none;
    margin-bottom: 1em;
    transition: all 500ms ease-in-out;
    width: 100%;
}
.input-text:hover, 
.input-text:active,
.input-text:focus  
{
    transform: scaleX(1.1);
}
.btn{
    border:none;
    color: white;
    background-color: rgba(1, 7, 8, 0.829);
    letter-spacing: 1px;
    font-weight: 700;
    transition: all 500ms ease-in-out;
    box-sizing: border-box;
    width: 100%;
}
.btn:hover{
    color: rgb(1, 7, 8);
    background-color: rgba(255, 255, 255, 0.616);
}
.btn:active{
    border:lightgreen 1px solid;
}
</style>


