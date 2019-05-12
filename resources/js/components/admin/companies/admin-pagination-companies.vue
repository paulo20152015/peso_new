<template>
    <div>
        <nav aria-label="Page navigation example">
            <ul class="pagination justify-content-center row">
                <li class="page-item"><a class="page-link" @click="parentGetCompanies(links.first_page)" href="#">First Page</a></li>
                <li class="page-item"><a class="page-link" @click="parentGetCompanies(links.prev_page)" href="#">Previous</a></li>
                <li class="page-item" v-if='links.current_page-2 >= 1'><a class="page-link" @click="parentGetCompanies(links.path+'?page='+(links.current_page - 2))" href="#">{{links.current_page - 2}}</a></li>
                <li class="page-item" v-if='links.current_page-1 >= 1'><a class="page-link" @click="parentGetCompanies(links.path+'?page='+(links.current_page - 1))" href="#">{{links.current_page - 1}}</a></li>
                <li class="page-item disabled"><a class="page-link " href="#">{{links.current_page}}</a></li>
                <li class="page-item" v-if='links.current_page+1 <= links.last_page'><a class="page-link" @click="parentGetCompanies(links.path+'?page='+(links.current_page + 1))" href="#">{{links.current_page + 1}}</a></li>
                <li class="page-item" v-if='links.current_page+2 <= links.last_page'><a class="page-link" @click="parentGetCompanies(links.path+'?page='+(links.current_page + 2))" href="#">{{links.current_page + 2}}</a></li>
                <li class="page-item"><a class="page-link" @click="parentGetCompanies(links.next_page)" href="#">Next</a></li>
                <li class="page-item"><a class="page-link" @click="parentGetCompanies(links.last_page_url)" href="#">Last Page</a></li>
            </ul>
        </nav>
    </div>
</template>
<script>
export default {
    props:{
        'links':Object
    },
    methods:{
        parentGetCompanies(url = '/admin/companies/data'){
            this.$parent.getCompanies(url);
        }

    }
}
</script>
<style>

</style>
