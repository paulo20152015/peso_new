<template>
    <div class="login-container">
        <div class="message-area">
            <h1 class='message-head'>Admins Only :</h1>
            <p class='message-body'> 
                unauthorized access is prohibited and punishable by law.
            </p>
        </div>
        <div class="login-area">
            <h1 class="login-head">Sign-in</h1>
            <form action="" @submit.prevent="login()">
                <div class="form-container">
                    <label for="username">Username</label>
                    <input type="text" v-model="adminLoginForm.username" v-validate.continues="'required|max:40'" class='input-text' name='username' placeholder="enter your username" id='username' >
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('username')" :key='"username"+index'>
                            {{ error }}
                        </p>  
                    </transition-group><transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-if="adminLoginForm.errors.has('username')">
                            {{ adminLoginForm.errors.get('username') }}
                        </p>
                    </transition>
                    <label for='password'>Password</label>
                    <input type="password" v-model="adminLoginForm.password" v-validate.continues="'required|max:40'" class='input-text' name='password' placeholder="enter your password" id='password' >
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('password')" :key='"password"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-if="adminLoginForm.errors.has('password')">
                            {{ adminLoginForm.errors.get('password') }}
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
                    <label for="remember"><input v-model="adminLoginForm.remember" type="checkbox" name="remember" id="remember"> Remember Me</label>
                    <button :disabled="adminLoginForm.busy" class="btn" type="submit"><i v-if="spinner == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Login</button>
                </div>
            </form>
        </div>
        
    </div>
</template>
<script>
export default {
    data(){
        return {
            spinner:'',
            message:'',
            messageSuccess:'',
           
            adminLoginForm:new Form({
                username:'',
                password:'',
                remember:''
            }),
        }
    },
    methods:{
        spinLoading(){
            this.spinner = this.spinner != 1 ?1:'';
        },
        login(){
           let vm = this;
            this.$validator.validateAll(['username','password']).then(function(result){
                if(result){
                    vm.spinLoading();
              
                    vm.adminLoginForm.post('/admin/login')
                    .then( ({data}) => {
                         vm.spinLoading();
                         console.log(data);
                         if(data.success == 1){
                             vm.messageSuccess = 'Login successfully';
                             vm.message ='';
                             window.location.href = data.redirect;
                         }else{
                             if(data.success == 2){
                                vm.message ='password is incorrect! or account is disabled'
                             }else if(data.success == 3) {
                                vm.message ='This user does not exist!'
                             }
                         }
                          }).catch(function(error){
                          vm.message ='Failed!'    
                          vm.spinLoading();
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
.message-head{
    font-size: 16px;
    color: rgb(179, 216, 214);
    font-weight: 600;
    letter-spacing: 2px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}
.message-body{
    font-size: 14px;
    color: rgb(209, 186, 186);
    padding: .5em;
}
.message-area{
     background: rgb(94, 9, 9);
     padding: 1em;
     flex:1 1 30%;
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
.input-text:hover, 
.input-text:active,
.input-text:focus  
{
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


