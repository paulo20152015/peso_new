<template>

    <div class="login-container">
        <div class="nav-bar">
            <a class="link" href="#" @click="toggleSingup()">Sign-up</a>
            <a class="link" href="#" @click="togglelogin()">Login</a>
        </div>
        <transition enter-active-class="animated bounceIn" leave-active-class="animated bounceOut">
        <div class="section-applicant" v-if="toggle==1">
            <h1 class="header-text">Applicant Registration</h1>
            <form @submit.prevent="register()">
                <div class="form-container">
                    <div class="flex-row">
                        <label for="first_name" class="half-40">First Name *</label>
                        <input type="text" v-model="applicantRegistrationForm.first_name" v-validate.continues="'required|min:1|max:40|alpha'" class='input-text half' name='first name' placeholder="enter your First Name" id='first_name'>
                        <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('first name')" :key='"first_name"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('first_name')">
                                {{ applicantRegistrationForm.errors.get('first_name') }}
                                </p>
                        </transition>
                        <label for="middle_name" class="half-40">middle name(if applicable)</label>
                        <input type="text" v-model="applicantRegistrationForm.middle_name" v-validate.continues="'min:1|max:40|alpha'" class='input-text half' name='middle name' placeholder="enter your middle Name" id='middle_name'>
                        <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('middle name')" :key='"middle_name"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('middle_name')">
                                {{ applicantRegistrationForm.errors.get('middle_name') }}
                                </p>
                        </transition>  
                        <label for="last_name" class="half-40">last name *</label>
                        <input type="text" v-model="applicantRegistrationForm.last_name" v-validate.continues="'required|min:1|max:40|alpha'" class='input-text half' name='last name' placeholder="enter your last name" id='last_name'>  
                        <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                            <p class='error' v-for="(error,index) in errors.collect('last name')" :key='"last_name"+index'>
                                {{ error }}
                            </p>  
                        </transition-group>
                        <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('last_name')">
                                {{ applicantRegistrationForm.errors.get('last_name') }}
                                </p>
                        </transition>
                    </div>
                    <label for="email">Email *</label>
                    <input type="email" v-model="applicantRegistrationForm.email" v-validate.continues="'required|email|min:8|max:70'" class='input-text' name='email' placeholder="enter your Email" id='email'>
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('email')" :key='"email"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('email')">
                                {{ applicantRegistrationForm.errors.get('email') }}
                                </p>
                    </transition>
                    <label for="number">Contact Number *</label>
                    <input type="text" v-model="applicantRegistrationForm.number" v-validate.continues="'required|digits:11'" class='input-text' name='number' placeholder="enter your mobile number" id='number'>
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('number')" :key='"number"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('number')">
                                {{ applicantRegistrationForm.errors.get('number') }}
                                </p>
                    </transition>
                    <label for="civil_status">Civil Status *</label>
                    <select class='input-text' v-model="applicantRegistrationForm.civil_status" v-validate.continues="'required|included:Married,Widowed,Separated,Divorced,Single'" name="civil_status" id="civil_status">
                        <option value=""> </option>
                        <option value="Married">Married </option>
                        <option value="Widowed">Widowed </option>
                        <option value="Separated">Separated </option>
                        <option value="Divorced">Divorced </option>
                        <option value="Single">Single </option>
                    </select>
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('civil_status')" :key='"civil_status"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('civil_status')">
                                {{ applicantRegistrationForm.errors.get('civil_status') }}
                                </p>
                    </transition>
                    <label for="date_of_birth">Date of Birth *</label>
                    <input type="date" v-model="applicantRegistrationForm.date_of_birth"  v-validate.continues="'required|date_between:1950-01-01,2002-01-01'" class='input-text' name='date of birth' placeholder="enter your date of birth" id='date_of_birth'>
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('date of birth')" :key='"date_of_birth"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('date_of_birth')">
                                {{ applicantRegistrationForm.errors.get('date_of_birth') }}
                                </p>
                    </transition>
                    <label for="username">Username *</label>
                    <input type="text" v-model="applicantRegistrationForm.username" v-validate.continues="'required|min:8|max:30'" class='input-text' name='username' placeholder="enter your username" id='username'>
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('username')" :key='"username"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('username')">
                                {{ applicantRegistrationForm.errors.get('username') }}
                                </p>
                    </transition>
                    <label for='password'>Password *</label>
                    <input type="password" v-model="applicantRegistrationForm.password" v-validate.continues="'required|min:8|max:30'" class='input-text' name='password' placeholder="enter your password" id='password' ref="password" >
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('password')" :key='"passwordReg"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('password')">
                                {{ applicantRegistrationForm.errors.get('password') }}
                                </p>
                    </transition>
                    <label for='confirm_password'>Confirm Password *</label>
                    <input type="password" v-model="applicantRegistrationForm.password_confirmation" v-validate.continues="'required|confirmed:password'" class='input-text' name='confirm password' placeholder="enter your password again" id='confirm_password' data-vv-as="password">
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('confirm password')" :key='"password_confirmation"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <label >Gender *</label>
                    <label for="male"><input type="radio" v-model="applicantRegistrationForm.gender"  v-validate.continues="'required|included:0,1'" id='male' name="gender" value="0" checked> Male </label>
                    <label for="female"><input type="radio" v-model="applicantRegistrationForm.gender" id='female' name="gender" value="1" checked> Female </label>
                    <transition-group  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                        <p class='error' v-for="(error,index) in errors.collect('gender')" :key='"gender"+index'>
                            {{ error }}
                        </p>  
                    </transition-group>
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantRegistrationForm.errors.has('gender')">
                                {{ applicantRegistrationForm.errors.get('gender') }}
                                </p>
                    </transition>
                    <button class="btn" type="submit" :disabled="applicantRegistrationForm.busy"><i v-if="spinnerRegister == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Register</button>
                </div>
            </form>
        </div>
        </transition>
        <transition enter-active-class="animated bounceIn" leave-active-class="animated bounceOut">
        <div class="section-applicant" v-if='toggle == 2'>
            <h1 class="header-text">Login</h1>
            <form @submit.prevent="login()" >
                <div class="form-container">
                    <label for="username">Username</label>
                    <input  type="text" v-model="applicantLoginForm.username" class='input-text' name='username' placeholder="enter your username" id='username' >
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantLoginForm.errors.has('username')">
                                {{ applicantLoginForm.errors.get('username') }}
                                </p>
                    </transition>
                    <label for='password'>Password</label>
                    <input type="password" v-model="applicantLoginForm.password" class='input-text' name='password' placeholder="enter your password" id='password' >
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantLoginForm.errors.has('password')">
                                {{ applicantLoginForm.errors.get('password') }}
                                </p>
                    </transition>
                    <label for="remember"><input type="checkbox" v-model="applicantLoginForm.remember" name="remember" id="remember" > Remember Me</label>
                    <label for=""><a href="#"  @click="toggleForgot()">Forgot Password</a></label> 
                    <button :disabled='applicantLoginForm.busy' class="btn" type="submit"><i v-if="spinnerLogin == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Login</button>
                </div>
            </form>
        </div>
        </transition>
        <transition enter-active-class="animated bounceIn" leave-active-class="animated bounceOut">
        <div class="section-applicant" v-if='toggle == 3'>
            <h1 class="header-text">Forgot Password</h1>
                <div class="form-container">
                    <label for="username">Username</label>
                    <input  type="text" v-model="applicantForgotForm.username" class='input-text' name='username' placeholder="enter your username" id='username' >
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantForgotForm.errors.has('username')">
                                {{ applicantForgotForm.errors.get('username') }}
                                </p>
                    </transition>
                    <label for="number">Number</label>
                    <input  type="text" v-model="applicantForgotForm.number" class='input-text' name='number' placeholder="enter your number" id='number' >
                    <transition  enter-active-class="animated flipInX" leave-active-class="animated fadeOutRight">
                                <p class='error' v-if="applicantForgotForm.errors.has('number')">
                                {{ applicantForgotForm.errors.get('number') }}
                                </p>
                    </transition>
                     <button :disabled='applicantForgotForm.busy' class="btn" type="button" @click="sendcode()"><i v-if="spinnerLogin == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Send Code </button>
                    <label for="code">Code</label>
                    <input  type="text" v-model="applicantForgotForm.code" class='input-text' name='code' placeholder="enter your code" id='code' >
                    <button @click="generateNewPass()" :disabled='applicantForgotForm.busy' class="btn" type="button"><i v-if="spinnerNewPass == 1"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span></i> Generate New Password </button>
                </div>
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
           spinnerRegister:'',
           spinnerLogin:'',
           spinnerNewPass:'',
           applicantRegistrationForm:new Form({
                first_name:'',
                middle_name:'',
                last_name:'',
                email:'',
                number:'',
                civil_status:'',
                date_of_birth:'',
                username:'',
                password:'',
                password_confirmation:'',
                gender:''
            }),
            applicantLoginForm:new Form({
                username:'',
                password:'',
                remember:''
            }),
            applicantForgotForm:new Form({
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
        generateNewPass(){
            let vm = this;
            vm.spinLoadingPass();
            vm.applicantForgotForm.post('/applicant/resetPass')
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
            vm.applicantForgotForm.post('/applicant/resetCode')
            .then( ({data}) => {
                vm.spinLoadingLogin();
                console.log(data);
                swal(data);
                }).catch(function(error){
                swal('Failed');
                vm.spinLoadingLogin();
                });
        }
        ,
        spinLoadingRegister(){
            this.spinnerRegister = this.spinnerRegister != 1 ?1:'';
        },
        spinLoadingLogin(){
            this.spinnerLogin = this.spinnerLogin != 1 ?1:'';
        },
       register(){
           let vm = this;
            this.$validator.validateAll(['first name','last name','email','number','civil_status','date of birth','username','password','confirm password','gender']).then(function(result){
                if(result){
                    vm.spinLoadingRegister();
                    
                    vm.applicantRegistrationForm.post('/applicant/register')
                    .then( ({data}) => {
                         vm.spinLoadingRegister();
                         console.log(data);
                         swal('Created',data,'success');
                         if(data =='Registered Successfully'){
                            vm.togglelogin();
                         }
                          }).catch(function(error){
                         vm.spinLoadingRegister();
                          });
                }else{
                    swal('Aborted! ',"Errors detected","error");
                }
            }); 
       },
       login(){
           let vm = this;
            vm.spinLoadingLogin();
            vm.applicantLoginForm.post('/applicant/login')
            .then( ({data}) => {
                vm.spinLoadingLogin();   
                console.log(data);
                if(data.success == 1){
                    swal('Login successfully');
                    window.location.href = data.redirect;
                }else{
                    if(data.success == 2){
                        swal('password is incorrect!, or account is disabled');
                    }else if(data.success == 3) {
                        swal('This user does not exist!');
                    }
                }
            }).catch(function(error){
                swal('Failed!');   
                vm.spinLoadingLogin();
            });    
        },
       toggleSingup(){
           this.toggle = this.toggle != 1?1:'';
       }
       ,
       togglelogin(){
           this.toggle = this.toggle != 2?2:'';
       },
       toggleForgot(){
           this.toggle = this.toggle != 3?3:'';
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
.flex-row{
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
    padding: .2em .2em .2em .2em;
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
.half{
  
    flex-shrink: 2;
    flex-grow: 2;
}
.half-40{

    flex-shrink: 2;
    flex-grow: 2;
}
</style>


