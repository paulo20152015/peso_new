<template>
    <div class="login-container">

        <div class="login-area" v-if="loginForm == 1">
            <h1 class="login-head">Company Sign-in</h1>
            <form action="" @submit.prevent="login()">
                <div class="form-container">
                    <label for="username">Username</label>
                    <input type="text" v-model="companyLoginForm.username" v-validate.continues="'required|max:40'" class='input-text' name='username' placeholder="enter your username" id='username' >
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('username')" :key='"username"+index'>
                            {{ error }}
                        </p>  
                    </transition-group><transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-if="companyLoginForm.errors.has('username')">
                            {{ companyLoginForm.errors.get('username') }}
                        </p>
                    </transition>
                    <label for='password'>Password</label>
                    <input type="password" v-model="companyLoginForm.password" v-validate.continues="'required|max:40'" class='input-text' name='password' placeholder="enter your password" id='password' >
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('password')" :key='"password"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-if="companyLoginForm.errors.has('password')">
                            {{ companyLoginForm.errors.get('password') }}
                        </p>
                    </transition>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error'  v-if="message!=''">
                            {{message}}
                        </p>
                    </transition>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error success'  v-if="messageSuccess!=''">
                            {{messageSuccess}}
                        </p>
                    </transition>
                    <label for="remember"><input v-model="companyLoginForm.remember" type="checkbox" name="remember" id="remember"> Remember Me</label>
                    <a href="#" @click="toggleLoginForgot()">Forgot Password</a>
                    <button :disabled='disable == 1' class="btn" type="submit"><i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Login</button>
                </div>
            </form>
        </div>

        <div class="login-area" v-else>
            <h1 class="login-head">Forgot Password</h1>
            <div class="form-container">
                    <label for="username">Username</label>
                    <input  type="text" v-model="companyForgotForm.username" class='input-text' name='username' placeholder="enter your username" id='username' >
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="companyForgotForm.errors.has('username')">
                                {{ companyForgotForm.errors.get('username') }}
                                </p>
                    </transition>
                    <label for="number">Number</label>
                    <input  type="text" v-model="companyForgotForm.number" class='input-text' name='number' placeholder="enter your number" id='number' >
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="companyForgotForm.errors.has('number')">
                                {{ companyForgotForm.errors.get('number') }}
                                </p>
                    </transition>
                     <button :disabled='companyForgotForm.busy' class="btn" type="button" @click="sendcode()"><i v-if="spinnerLogin == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Send Code </button>
                    <label for="code">Code</label>
                    <input  type="text" v-model="companyForgotForm.code" class='input-text' name='code' placeholder="enter your code" id='code' >
                    <button @click="generateNewPass()" :disabled='companyForgotForm.busy' class="btn" type="button"><i v-if="spinnerNewPass == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Generate New Password </button>
                    <a href="#" @click="toggleLoginForgot()">Login</a>
            </div>
        </div>
    </div>
</template>
<script>

export default {
    data(){
        return {
            resend:0,
            spinner:'',
            spinnerLogin:'',
            message:'',
            messageSuccess:'',
            disable:'',
            loginForm:1,
            spinnerNewPass:'',
            companyLoginForm:new Form({
                username:'',
                password:'',
                remember:''
            }),
            companyForgotForm:new Form({
                username:'',
                number:'',
                code:''
            }),
        }
    },
    methods:{
        spinLoadingPass(){
            this.spinnerNewPass = this.spinnerNewPass != 1 ?1:'';
        },
        spinLoadingLogin(){
            this.spinnerLogin = this.spinnerLogin != 1 ?1:'';
        },
        generateNewPass(){
            let vm = this;
            vm.spinLoadingPass();
            vm.companyForgotForm.post('/company/resetPass')
            .then( ({data}) => {
                vm.spinLoadingPass();
                console.log(data);
                swal(data);
                }).catch(function(error){
                swal('Failed');
                vm.spinLoadingPass();
                });
        }
        ,
        sendcode(){
            let vm = this;
            vm.spinLoadingLogin();
            vm.companyForgotForm.post('/company/resetCode')
            .then( ({data}) => {
                vm.spinLoadingLogin();
                console.log(data);
                swal(data);
                }).catch(function(error){
                swal('Failed');
                vm.spinLoadingLogin();
                });
        },
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        toggleLoginForgot(){
           this.loginForm = this.loginForm == 1?0:1;
        }
        ,
        login(){
           let vm = this;
            this.$validator.validateAll(['username','password']).then(function(result){
                if(result){
                    vm.disable = 1;
                    vm.spinLoading();
                    vm.companyLoginForm.post('/company/login')
                    .then( ({data}) => {
                         vm.spinLoading();
                         vm.disable = '';
                         console.log(data);
                         if(data.success == 1){
                             vm.messageSuccess = 'Login successfully';
                             window.location.href = data.redirect;
                         }else{
                             if(data.success == 2){
                                vm.message ='password is incorrect!, or account is disabled'
                             }else if(data.success == 3) {
                                vm.message ='This user does not exist!'
                             }

                         }
                          }).catch(function(error){
                            vm.message ='Failed!'    
                            vm.spinLoading();
                            vm.disable = '';
                          });
                }else{
                   
                }
            }); 
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

.login-head{
    color: rgb(210, 237, 238);
    font-size: 20px;
    letter-spacing: 2px;
    text-align: center;
    border-bottom: white 2px solid;
    padding-bottom: .5em;
}
.login-area{
    background: rgba(10%,10%,10%,0.7);
    padding: 1em;
    flex:2 1 60%;
}
.form-container{
    display: flex;
    flex-flow: column wrap;
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
.input-text:hover{
    transform: scaleX(1.1);
}
.success{
    text-align:center;
    background:rgba(37, 207, 22, 0.507);
    color:rgb(8, 4, 26);
    font-size:1.2em;
    font-weight:900;
    letter-spacing:2px;
}
</style>


