
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


window.Vue = require('vue');
import VueRouter from 'vue-router'

Vue.use(VueRouter)
import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);
import { Form, HasError, AlertError } from 'vform';
window.Form = Form;
Vue.component(HasError.name, HasError)
Vue.component(AlertError.name, AlertError)
import CKEditor from '@ckeditor/ckeditor5-vue';

Vue.use( CKEditor );
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));


Vue.component('admin-login-component', require('./components/admin/login.vue').default);

//homepage components
Vue.component('header-component',require('./components/homepage/header.vue').default);
Vue.component('body-component',require('./components/homepage/body.vue').default);
Vue.component('body-header',require('./components/homepage/bodyheader.vue').default);
Vue.component('section-component',require('./components/homepage/section.vue').default);
Vue.component('footer-component',require('./components/homepage/footer.vue').default);
//admin admin page components
Vue.component('admins-component',require('./components/admin/admins/admins.vue').default);
Vue.component('admins-edit',require('./components/admin/admins/admin-edit.vue').default);
Vue.component('admins-pagination',require('./components/admin/admins/admin-pagination.vue').default);
Vue.component('admins-create',require('./components/admin/admins/admin-create.vue').default);
Vue.component('account-setting',require('./components/admin/admins/account-setting.vue').default);
//admin job specialization ,city ,town
Vue.component('job-city-town',require('./components/admin/job-specialization-city.vue').default);

Vue.component('specialization',require('./components/admin/specialization-city-town/specialization-loop.vue').default);
Vue.component('city',require('./components/admin/specialization-city-town/city-loop.vue').default);
Vue.component('town',require('./components/admin/specialization-city-town/town-loop.vue').default);
//admin company pages components
Vue.component('admins-companies',require('./components/admin/companies/admin-companies.vue').default);
Vue.component('admins-create-companies',require('./components/admin/companies/admin-companies-create.vue').default);
Vue.component('admins-pagination-companies',require('./components/admin/companies/admin-pagination-companies.vue').default);
Vue.component('admins-company',require('./components/admin/companies/company.vue').default);//to use in company
Vue.component('company-data',require('./components/admin/companies/company-data.vue').default);
Vue.component('company-employment',require('./components/admin/companies/company-employment.vue').default);//company employment record
Vue.component('company-jobposts',require('./components/admin/companies/job_posts.vue').default);
//admin applicant components
Vue.component('applicants-component',require('./components/admin/applicant/applicants.vue').default);
Vue.component('applicant-component',require('./components/admin/applicant/applicant.vue').default);
Vue.component('applicants-pagination-component',require('./components/admin/applicant/applicants-pagination.vue').default);
//company components
Vue.component('account-company-component',require('./components/company/account-setting.vue').default);
Vue.component('company-account-setting',require('./components/company/account-setting-components/settings.vue').default);
Vue.component('company-details',require('./components/company/account-setting-components/company-detail.vue').default);
Vue.component('company-ratings',require('./components/company/account-setting-components/company-ratings.vue').default);
Vue.component('company-post-component',require('./components/company/job-posts.vue').default);
Vue.component('logo-company',require('./components/company/logo.vue').default);
Vue.component('company-post-pagination',require('./components/company/job-post-components/pagination-job-posts.vue').default);
Vue.component('post-create-modal',require('./components/company/job-post-components/create-post-modal.vue').default);
Vue.component('job-post-manange',require('./components/company/job-post.vue').default);
Vue.component('manage-content',require('./components/company/single-post-components/manage-content.vue').default);
Vue.component('application-management',require('./components/company/single-post-components/application-management.vue').default);
Vue.component('application-hiringform',require('./components/company/single-post-components/hiringform.vue').default);
Vue.component('employment-record',require('./components/company/employment-record.vue').default);//employment component
Vue.component('end-del',require('./components/company/employment-record-components/end-del.vue').default);//end and delete
Vue.component('applicant-rating',require('./components/company/employment-record-components/rate-applicant.vue').default);//end and delete
Vue.component('individual-applicant-rating',require('./components/company/employment-record-components/individual-applicant-rating.vue').default);//loop ratings
//applicant components
Vue.component('myaccount-component',require('./components/applicant/myaccount.vue').default);
Vue.component('companies-component',require('./components/applicant/companies.vue').default);
Vue.component('company-component',require('./components/applicant/company.vue').default);
Vue.component('jobpost-component',require('./components/applicant/job_posts.vue').default);
Vue.component('employment-component',require('./components/applicant/employment.vue').default);
Vue.component('jobpost-single-component',require('./components/applicant/job_post.vue').default);
Vue.component('personal-data-component',require('./components/applicant/myaccount/personal-data.vue').default);
Vue.component('account-setting-component',require('./components/applicant/myaccount/account-setting.vue').default);
Vue.component('my-ratings',require('./components/applicant/myaccount/my-ratings.vue').default);//applicant ratings
Vue.component('application-history',require('./components/applicant/myaccount/application-history.vue').default);//applicant application history
Vue.component('resume-specialization-component',require('./components/applicant/myaccount/resume-specialization.vue').default);
Vue.component('create-rating-show',require('./components/applicant/ratingCompany/create-rating.vue').default);//create and show rating
Vue.component('indi-rating-show',require('./components/applicant/ratingCompany/individual-rating.vue').default);//rating loop
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const routes = [
    {path:'/applicant',component: require('./components/homepage/login-registration-applicant.vue').default},
    {path:'/company',component: require('./components/homepage/login-registration-company.vue').default},
    {path:'/admin-login',component: require('./components/admin/login.vue').default},
 
];
const router = new VueRouter({
    routes
});

const app = new Vue({
    el: '#app',
    router
});

$(function () {
    $("#example1").DataTable();
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false
    });
  });