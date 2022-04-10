<template>
    <div class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" style="position:relative">
        <div class="input-group">
            <input type="text" v-model="query" class="form-control bg-light border-0 small" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" @click="query == '' ? '' : clearResults()">
                    <i v-if="query == ''" class="fas fa-search fa-sm"></i>
                    <i v-else class="fas fa-times fa-sm"></i>
                </button>
            </div>
        </div>
        <div class="results-wrapper">
            <div v-for="(group, groupName) in groups" :key="groupName">
                <h6>{{groupName}}</h6>
                <ul v-if="group.length > 0" class="">
                    <li v-for="(result, index) in group" :key="index">
                        <a :href="result.url">
                            {{ result.title }}
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                query: '',
                results: []
            };
        },
        watch: {
            query(after, before) {
                this.query != '' ? this.getResults() : this.results = [];
            }
        },
        computed:{
            groups(){
                return groupBy(this.results, 'type')
            }
        },
        methods: {
            getResults() {
                axios.post('/admin/search', { query: this.query })
                    .then(res => this.results = res.data)
                    .catch(error => {});
            },
            clearResults(){
                this.query = ''
                this.results = []
            }
        }
    }
    function groupBy(array, key){
        const result = {}
        array.forEach(item => {
            if (!result[item[key]]){
                result[item[key]] = []
            }
            result[item[key]].push(item)
        })
        return result
    }
</script>
<style lang="scss">
    .results-wrapper{
        position: absolute;
        left: 0;
        background: #fff;
        width: inherit;
        z-index: 99;

        h6{
            padding: 5px 10px;
            margin: 0;
            color: #000;
            font-weight: 600;
            border: 1px solid #eee;
            background: #eee;
        }
        ul{
            list-style: none;
            padding: 0;
            margin: 0;
        }

        li>a{
            display: block;
            padding: 10px 10px;
            text-decoration: none;
            border: 1px solid #eee;
            &:hover{
                background: #eee;
            }
        }
    }
</style>
