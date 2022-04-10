<template>
    <div>
        <div class="card">
            <h5 class="card-header">Attributes</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="parent">Select an Attribute <span class="m-l-5 text-danger"> *</span></label>
                            <select id=parent class="form-control custom-select mt-15" v-model="attribute" @change="selectAttribute(attribute)">
                                <option value="" disabled selected>Select an attribute</option>
                                <option :value="attribute" v-for="(attribute, index) in attributes" :key="index"> {{ attribute.name }} </option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card" v-if="attributeSelected">
            <h5 class="card-header">Add Attributes To Product</h5>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="values">Select a value <span class="m-l-5 text-danger"> *</span></label>
                            <select id=values class="form-control custom-select mt-15" v-model="value" @change="selectValue(value)">
                                <option value="" disabled selected>Select a value</option>
                                <option :value="value" v-for="(value, index) in attributeValues" :key="index"> {{ value.value }} </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="valueSelected">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="stock">stock</label>
                            <input class="form-control" type="number" id="stock" v-model="currentQty"/>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="price">Price</label>
                            <input class="form-control" type="text" id="price" v-model="currentPrice"/>
                            <small class="text-danger">This price will be added to the main price of product on frontend.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="control-label" for="is_required">Is Required</label>
                            <input type="checkbox" id="is_required" v-model="isRequired" name="is_required"/> Yes
                        </div>
                    </div>
                    <div class="col-md-12">
                        <button class="btn btn-sm btn-primary" @click="addProductAttribute()">
                            <i class="fa fa-plus"></i> Add
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <h5 class="card-header">Product Attributes</h5>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                        <tr class="text-center">
                            <th>Value</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Is Required</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="(pa, index) in productAttributes" :key="index">
                            <td style="width: 20%" class="text-center">{{ pa.value}}</td>
                            <td style="width: 20%" class="text-center">{{ pa.stock}}</td>
                            <td style="width: 20%" class="text-center">{{ pa.price}}</td>
                            <td style="width: 20%" class="text-center">
                                <input type="checkbox" :checked="pa.is_required" style="pointer-events:none">
                            </td>
                            <td style="width: 20%" class="text-center">
                                <button class="btn btn-sm btn-danger" @click="deleteProductAttribute(pa)">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "product-attributes",
        props: ['productid'],
        data() {
            return {
                productAttributes: [],
                attributes: [],
                attribute: {},
                attributeSelected: false,
                attributeValues: [],
                value: {},
                valueSelected: false,
                currentAttributeId: '',
                currentValue: '',
                currentQty: '',
                currentPrice: '',
                isRequired: '',
            }
        },
        created: function() {
            this.loadAttributes();
            this.loadProductAttributes(this.productid);
        },
        methods: {
            loadProductAttributes(id) {
                let _this = this;
                axios.post('/admin/product/attributes', {
                    id: id
                }).then (function(response){
                    _this.productAttributes = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            loadAttributes() {
                let _this = this;
                axios.get('/admin/attributes/load').then (function(response){
                    _this.attributes = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
            },
            selectAttribute(attribute) {
                let _this = this;
                this.currentAttributeId = attribute.id;
                axios.post('/admin/attributes/values', {
                    id: attribute.id
                }).then (function(response){
                    _this.attributeValues = response.data;
                }).catch(function (error) {
                    console.log(error);
                });
                this.attributeSelected = true;
            },
            selectValue(value) {
                this.valueSelected = true;
                this.currentValue = value.value;
                this.currentQty = value.stock;
                this.currentPrice = value.price;
                this.isRequired = value.is_required;
            },
            addProductAttribute() {
                if (this.currentQty === null || this.currentPrice === null) {
                    this.$swal("Error, Some values are missing.", {
                        icon: "error",
                    });
                } else {
                    let _this = this;
                    let data = {
                        attribute_id: this.currentAttributeId,
                        value:  this.currentValue,
                        stock: this.currentQty,
                        price: this.currentPrice,
                        is_required: this.isRequired,
                        product_id: this.productid,
                    };

                    axios.post('/admin/attributes/add', {
                        data: data
                    }).then (function(response){
                        _this.$swal("Success! " + response.data.message, {
                            icon: "success",
                        });
                        _this.currentValue = '';
                        _this.currentQty = '';
                        _this.currentPrice = '';
                        _this.valueSelected = false;
                        _this.isRequired = 0;
                    }).catch(function (error) {
                        console.log(error);
                    });
                    _this.loadProductAttributes(this.productid);
                }
            },
            deleteProductAttribute(pa) {
                let _this = this;
                this.$swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        // console.log(pa.id);
                        axios.post('/admin/attributes/delete', {
                            id: pa.id,
                        }).then (function(response){
                            if (response.data.status === 'success') {
                                _this.$swal("Success! Product attribute has been deleted!", {
                                    icon: "success",
                                });
                                _this.loadProductAttributes(_this.productid);
                            } else {
                                _this.$swal("Your Product attribute not deleted!");
                            }
                        }).catch(function (error) {
                            console.log(error);
                        });
                    } else {
                        this.$swal("Action cancelled!");
                    }
                });
            }
        }
    }
</script>
