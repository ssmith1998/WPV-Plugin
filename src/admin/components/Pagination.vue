<template>
  <nav v-if="pages" class="paginationWrapper">
    <ul class="pagination">
    <li v-if="currentPage !== 1" class="page-item"><a class="page-link" href="#" @click="onChangePage(currentPage - 1)">Previous</a></li>
    <li v-for="index in pages" :key="index" @click="onChangePage(index)" class="page-item"><a :class="currentPage === index ? 'page-link active': 'page-link'" href="#">{{index}}</a></li>
    <li v-if="currentPage !== pages" class="page-item"><a class="page-link" href="#" @click="onChangePage(currentPage + 1)">Next</a></li>
  </ul>
  </nav>
</template>

<script>
export default {
name: 'pagination',
props: {
    pages: {
        type: Number,
        required: true,
    },
    currentPage: {
        type: Number,
        default: 1,
    }
},
methods: {
    onChangePage(page) {
        console.log('Page', page);
        this.$emit('pageChange', page);        
    },
},
mounted() {
    console.log('Pagest', this.pages);
}
}
</script>

<style>
.active {
    background-color: rgb(35, 35, 106);
    color: #ffffff;
}
</style>