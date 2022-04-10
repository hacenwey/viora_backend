<template>
    <div>
        <div class="row">
            <div class="left-column">
                <input type="text" class="form-control mb-4" placeholder="Search by SKU" @keyup="searchProduct" v-model="searchQuery">
                <div class="card">
                    <div class="card-body">
                        <div class="products-list" infinite-wrapper>
                            <div class="products-item" v-for="(product, index) in products" :key="index" @click="addToCart(product)">
                                <img :src="product.photo" alt="">
                                <label :title="product.title">{{ product.title }}</label>
                            </div>
                            <infinite-loading ref="infiniteLoading" force-use-infinite-wrapper @distance="0" @infinite="loadData($event)"></infinite-loading>
                        </div>
                    </div>
                </div>
            </div>
            <div class="right-column">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control mb-4" autofocus ref="barcodeRef"  @keyup.enter="loadProduct" placeholder="Scan Barcode" v-model="productQr">
                    </div>
                    <div class="col">
                        <select name="customerId" id="" class="form-control mb-4 select2" @change="customerChange($event)" v-model="customerId">
                            <option value="0">Walking Customer</option>
                            <option v-for="(customer, index) in customers" :key="index" :value="customer.id">{{ customer.first_name }} {{ customer.last_name }}</option>
                        </select>
                    </div>
                </div>
                <div class="card">
                    <div class="loader" v-if="loading">
                        <span></span>
                    </div>
                    <div class="card-body">
                        <ul class="cart-list" v-if="$store.state.cart.length > 0">
                            <li class="cart-item" v-for="(item, i) in $store.state.cart" :key="i">
                                <div style="display:flex">
                                    <img :src="item.photo" alt="" width="40" height="40" class="mr-2">
                                    <div>
                                        <label class="label" for="">{{ item.title }}</label>
                                        <p>{{ item.price }} MRU</p>
                                    </div>
                                </div>
                                <div class="d-flex" style="align-items:center">
                                    <span>x <input type="text" style="width:50px;text-align:center" :ref="'qty'+item.sku" v-model="item.quantity" @input="updateCart(item)"> = {{ item.totalPrice }} MRU</span>
                                    <a href="" @click.prevent="removeFromCart(item)" class="remove-btn ml-1">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="total-div">
                    <b>Total:</b>
                    <h4>{{ totalPrice }} MRU</h4>
                </div>
                <div class="row">
                    <div class="col">
                        <button class="btn btn-danger btn-block" @click="clearCart">Clear</button>
                    </div>
                    <div class="col">
                        <button class="btn btn-success btn-block" @click="submitCart">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            products: [],
            customers: [],
            customerId: 0,
            page: 1,
            searchQuery: '',
            productQr: '',
            loading: false,
        }
    },
    created () {
      const eventBus = this.$barcodeScanner.init(this.onBarcodeScanned, { eventBus: true })
      if (eventBus) {
        eventBus.$on('start', () => { this.loading = true })
        eventBus.$on('finish', () => { this.loading = false })
      }
    },
    destroyed () {
      this.$barcodeScanner.destroy()
    },
    mounted() {
        this.loadData();
        this.loadCustomers();
    },
    computed: {
        totalPrice() {
            let total = 0;

            for (let item of this.$store.state.cart) {
                total += item.totalPrice;
            }

            return total.toFixed(2);
        }
    },
    methods: {
        addToCart(item) {
            this.$store.commit('addToCart', item);
        },
        updateCart(item) {
            this.$store.commit('updateCart', item);
            this.loading = false
        },
        removeFromCart(item) {
            this.$store.commit('removeFromCart', item);
        },
        clearCart(){
            this.$store.commit('clearCart');
        },
        customerChange(event){
            console.log(event.target.value, this.customerId);
        },
        onBarcodeScanned (barcode) {
            // console.log(barcode)
        },
        resetBarcode () {
            let barcode = this.$barcodeScanner.getPreviousCode()
        },
        searchProduct: _.debounce(function() {
            axios.get('pos/products?searchQuery='+this.searchQuery+'&page=' + 1)
            .then(({ data }) => {
                if(data.data.length){
                    this.page += 1;
                    data.data.forEach(element => {
                        this.products = data.data
                    });
                    this.$refs.infiniteLoading.stateChanger.loaded();
                }else {
                    this.$refs.infiniteLoading.stateChanger.complete();
                }
            });
        }),
        loadData($state){
            axios.get('pos/products?searchQuery='+this.searchQuery+'&page=' + this.page)
            .then(({ data }) => {
                if(data.data.length){
                    this.page += 1;
                    data.data.forEach(element => {
                        if(!this.products.includes(element)){
                            data.data.forEach(element => {
                                this.products.push(element);
                            });
                        }
                    });
                    this.$refs.infiniteLoading.stateChanger.loaded();
                }else {
                    this.$refs.infiniteLoading.stateChanger.complete();
                }
            });
        },
        loadCustomers(){
            axios.get('pos/customers')
            .then(({ data }) => {
                this.customers = data
            });
        },
        loadProduct(){
            this.loading = true
            axios.get('pos/product?sku='+this.productQr)
            .then(({ data }) => {
                if(data.success){
                    this.addToCart(data.data)
                    this.productQr = ''
                    this.loading = false
                }
            });
        },
        submitCart(){
            this.loading = true
            axios.post('pos/place-order', {
                items: this.$store.state.cart,
                total: this.totalPrice,
                customer: this.customerId
            }).then(({ data }) => {
                if(data['success']){
                    this.clearCart()
                    Vue.$toast.success(
                        'Success!', {position: 'top-right'}
                    )
                } else {
                    Vue.$toast.error(
                        data['message'], {position: 'top-right'}
                    )
                }
                this.loading = false
            }).catch((error) => {
                console.log(error);
                Vue.$toast.error(
                    "Something went wrong", {position: 'top-right'}
                )
                this.loading = false
            });

            this.$refs.barcodeRef.focus();
        }
    },
}
</script>

<style lang="scss">
    .left-column{
        width: 51.8%;
    }
    .right-column{
        margin-left: 20px;
        width: 45%;
        .card-body{
            height: 570px;
            max-height: 570px;
        }
        .total-div{
            justify-content: space-between;
            display: flex;
            padding: 20px;
        }
    }
    .products-list{
        height: 670px;
        max-height: 670px;
        overflow: scroll;
        .products-item{
            width: 150px;
            height: 200px;
            min-width: 150px;
            float: left;
            border-width: 1px;
            border-style: solid;
            border-color: rgb(204, 204, 204);
            border-image: initial;
            border-radius: 3px;
            background: rgb(255, 255, 255);
            margin: 0px 2px 4px;
            overflow: hidden;
            cursor: pointer;
            label{
                cursor: pointer;
                padding: 0 5px;
            }
            img{
                width: 148px;
                background-size: cover;
                height: 148px;
            }
            &:hover{
                box-shadow: 0px 0px 6px 0px #888888;
            }
        }
    }
    .loader {
        position: absolute;
        text-align: center;
        width: 100%;
        height: 100%;
        background-color: #0000000d;
        span{
            border: 1px solid #f3f3f3; /* Light grey */
            border-top: 1px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 2s linear infinite;
            position: absolute;
            top: 50%;
        }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .cart-list{
        list-style: none;
        margin: 0;
        padding: 0;
        height: 570px;
        max-height: 570px;
        overflow: scroll;
        .cart-item{
            display: flex;
            justify-content: space-between;
            &:not(:last-child){
                margin-bottom: 15px;
            }
            label{
                font-size: 12px;
                color: #000;
                font-weight: 600;
            }
            .remove-btn{
                background-color: red;
                padding: 5px;
                color: #fff;
                width: 30px;
                height: 30px;
                text-align: center;
                border-radius: 5px;
            }
        }
    }
</style>
