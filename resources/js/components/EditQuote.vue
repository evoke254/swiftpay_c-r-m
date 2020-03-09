<template>
    <div class="content">
        <div class="row d-flex justify-content-center">
          <div class="col-md-12 text-center">
            <h4>Update {{requestparams.name}} </h4>
          </div>
        </div>
        <form action="#" @submit="formSubmit">
            <div class="row">
                <div v-for="(column, column_name) in requestparams.columns" class="col-md-6">
                    <div v-for="(display_label, column_type) in column" class="form-group row mb-5">
                        <label v-if="column_type !== 'json'" class="col-form-label col-md-3">{{display_label}} </label>
                        <div class="col">
                        
                            <date-picker 
                                placeholder="Click on the calendar icon to select date and time" 
                                :config="options"
                                v-if="column_type == 'datetime' || column_type == 'date'" 
                                class="form-control datetimepicker-input border border-light"
                                v-model="quoteData[0][column_name]"
                            ></date-picker>
                             <textarea 
                                 v-else-if="column_type == 'text'" 
                                 class=" col-md-12 form-control rounded-4 border border-light"  
                                 rows="5"
                                v-model="quoteData[0][column_name]"
                                >
                                {{requestparams['showModuleData'][0][column_name]}}
                             </textarea>
                            <model-select
                                    v-else-if="((column_type == 'bigint') && (display_label == 'Quote' || display_label == 'Invoice'))"
                                    class="form-control border border-light"
                                    :options="requestparams['selectOptions'][column_name]"
                                    v-model="quoteData[0][column_name]" 
                                    placeholder="Drop and search"
                            >
                            </model-select>

                            <input  
                                v-else-if="((column_type == 'bigint') && (display_label == 'Quote' || display_label == 'Invoice'))" 
                                type="text" class="form-control border border-light" 
                                disabled="" 
                                v-model="Quote"
                            >
                            <input 
                                v-else-if="column_type == 'string' || column_type == 'integer' || column_type == 'float'" 
                                class="form-control border border-light" 
                                type="text" 
                                :name="column_name" 
                                v-model="quoteData[0][column_name]"
                            >
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="panel-body col-lg-12 col-md-12">
                    <table class="table">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-nowrap">Tools</th>
                                <th class="text-nowrap">Image</th>
                                <th class="text-nowrap">Name</th>
                                <th class="text-nowrap">Quantity</th>
                                <th class="text-nowrap">Selling Price</th>
                                <th class="text-nowrap">Discount</th>
                                <th class="text-nowrap">Tax %</th>
                                <th class="text-nowrap"> Net Price</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <tr v-for="(quotedInvoicedProduct, key) in quotedInvoicedProducts" class="element text-center">
                                <td id="remove+key">
                                    <i @click="delTableRow(key)" class="hoverable myPointer fas fa-trash-alt"></i></td>
                                <td>
                                    <img 
                                        style="height: 75px !important"
                                        class="rounded-4 p-0 m-0" 
                                        :src="'/'+quotedInvoicedProduct['product']['Image']">
                                </td>
                                <td width="25%">            
                                    <input class="form-control border border-light" 
                                           type="text" 
                                           :value="quotedInvoicedProduct['product']['Product_Name']"
                                    > <br>
                                    <textarea class="form-control border border-light rounded-4 border border-light mt-n2 p-0" 
                                           type="text" 
                                           :value="quotedInvoicedProduct['product']['Description']"
                                           rows="1" 
                                    ></textarea>
                                </td>
                                <td>
                                    <input class="form-control border border-light" 
                                            v-model="quotedInvoicedProduct['quantity']"
                                    >
                                </td>
                                <td><input  class="form-control border border-light"  
                                            v-model="quotedInvoicedProduct['product']['Selling_Price']"
                                    >
                                </td>
                                <td><input class="form-control border border-light"  
                                           v-model="quotedInvoicedProduct['product']['Commission']"
                                    >
                                </td>
                                <td>
                                    <input class="form-control border border-light" 
                                           v-model="quotedInvoicedProducts[key]['tax']"
                                    >
                                </td>
                                <td class="font-weight-bold"> {{prdctNetPrice(key) }}
                                    <input type="hidden" v-model="quotedInvoicedProducts[key]['net']">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="row justify-content-start">
                        <div class="col-md-4 text-center">
                            <h6 class="content-title mb-1">Select a product</h6>
                            <model-select 
                                    class="form-control border border-success"
                                    :options="requestparams['products']"
                                    v-model="getproduct" 
                                    @input="getselectedproduct"
                                    placeholder="Select product to add">
                            </model-select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mb-5 justify-content-end">
                <div class="panel-body col-md-5">
                    <table class="table table-bordered">
                        <tbody class="font-weight-bold">
                            <tr>
                                <td>Items Total</td>
                                <td class="font-weight-bold" > {{ itemsamnt() }} </td>
                            </tr>
                            <tr>
                                <td>Taxes</td>
                                <td class="font-weight-bold"> {{tax()}} </td>
                            </tr>
                            <tr>
                                <td>Discount</td>
                                <td class="font-weight-bold">{{ comsn() }}</td>
                            </tr>
                            <tr>
                                <td>Grand Total</td>
                                <td class="font-weight-bold">{{ grandtol() }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>  
            <div class="row m-b-15 justify-content-center text-center">
                <div class="form-group row mb-5 ">
                    <button type="submit" class="btn btn-md btn-success">Update {{requestparams.name}}</button>
                </div>
            </div>
        </form>
        <div class="modal fade" id="alertSuccessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-sm modal-notify modal-success" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title w-100 text-center" id="myModalLabel">Success</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                   <div class="text-center">
                    <i class="fas fa-check fa-4x mb-3 animated rotateIn"></i>
                    <h6 id="controllerSuccess"> {{requestparams.name}} updated successfully  </h6>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>
    </div>
</template>

<script>
Vue.directive('init', {
  bind: function(el, binding, vnode) {
    vnode.context[binding.arg] = binding.value;
  }
});
import 'vue-search-select/dist/VueSearchSelect.css';
import datePicker from 'vue-bootstrap-datetimepicker';
import { ModelSelect } from 'vue-search-select';
  export default {
    props: ['requestparams'],
    mounted() {
        this.quoteData[0]['Quote_Subject'] = this.requestparams['showModuleData'][0]['Quote_Subject'];
        this.quoteData[0]['Valid_To'] = this.requestparams['showModuleData'][0]['Valid_To'];
        this.quoteData[0]['Contact_Name'] = this.requestparams['showModuleData'][0]['Contact_Name'];
        this.quoteData[0]['Description'] = this.requestparams['showModuleData'][0]['Description'];

        this.quotedInvoicedProducts = JSON.parse(this.requestparams['showModuleData'][0]['products']);

        this.quoteData[0]['Client']['value'] = this.requestparams['showModuleData'][0]['get_client']['id'];
        this.quoteData[0]['Client']['text'] = this.requestparams['showModuleData'][0]['get_client']['name'];

        this.quoteData[0]['Assigned_To']['value'] = this.requestparams['showModuleData'][0]['get_user']['id'];
        this.quoteData[0]['Assigned_To']['text'] = this.requestparams['showModuleData'][0]['get_user']['name'];

        this.quoteData[0]['Opportunity']['value'] = this.requestparams['showModuleData'][0]['get_opportunity']['id'];
        this.quoteData[0]['Opportunity']['text'] = this.requestparams['showModuleData'][0]['get_opportunity']['Opportunity_Name'];

        this.quoteData[0]['Organization']['value'] = this.requestparams['showModuleData'][0]['get_organization']['id'];
        this.quoteData[0]['Organization']['text'] = this.requestparams['showModuleData'][0]['get_organization']['Organization_Name'];

        this.quoteData[0]['Quote_Stage']['value'] = this.requestparams['showModuleData'][0]['get_quote_stage']['id'];
        this.quoteData[0]['Quote_Stage']['text'] = this.requestparams['showModuleData'][0]['get_quote_stage']['name'];
    },
    data () {
      return {

            quoteData: [{
                Quote_Subject: '',
                Quote_Stage: '',
                Valid_To: '',
                Description: '',
                
                Assigned_To: {
                  value: '',
                  text: ''
                },
                Client: {
                  value: '',
                  text: ''
                },
                Opportunity: {
                  value: '',
                  text: ''
                },
                Quote_Stage: {
                  value: '',
                  text: ''
                },
                Organization: {
                  value: '',
                  text: ''
                },
            } ],
            quotedInvoicedProducts: [],

            itemsTotal:'',
            taxes:'',
            discount: '',
            grandTotal:'',

            getproduct: {
                  value: '',
                  text: ''
            },
            options: {
              format: 'YYYY-MM-DD HH:mm',
              useCurrent: false,
               icons: {
                    time: 'icon ion-ios-time',
                    date: 'icon ion-ios-calendar',
                    up: 'icon ion-ios-arrow-dropup',
                    down: 'icon ion-ios-arrow-dropdown',
                    previous: 'icon ion-ios-arrow-dropleft',
                    next: 'icon ion-ios-arrow-dropright',
                    today: 'icon ion-ios-calendar',
                    clear: 'icon ion-ios-trash',
                    close: 'icon ion-ios-close-circle-outline'
                },
            },
            
        }
    },
    methods: {
      reset () {
        this.column_name = {}
        },
      selectFromParentComponent1 () {
        // select option from parent component
      },
      delTableRow: function(key){
        this.$delete(this.quotedInvoicedProducts, key);
      },
    getselectedproduct() {
        axios.get('/getproduct/'+this.getproduct['value']).then((response) => {
                var product = response.data;
                this.quotedInvoicedProducts.push({
                    product,
                    quantity: 1,
                    tax: 16,
                    taxamnt: '',
                    net: '',            
                });

        });
      },
    prdctNetPrice(key) {
        var price = ((Number(this.quotedInvoicedProducts[key]['quantity']) * this.quotedInvoicedProducts[key]['product']['Selling_Price'])
                        - this.quotedInvoicedProducts[key]['product']['Commission'] );
        var tax = (this.quotedInvoicedProducts[key]['tax']/100)* price;
        this.quotedInvoicedProducts[key]['taxamnt'] = tax;
        var netPrice = price + tax;
        this.quotedInvoicedProducts[key]['net'] = netPrice;
        return(netPrice.toFixed(2));
    },
    itemsamnt(){
        var sum = 0;
        this.quotedInvoicedProducts.forEach((value, index) => {
            var price = (Number(this.quotedInvoicedProducts[index]['quantity']) * Number(this.quotedInvoicedProducts[index]['product']['Selling_Price']));
            sum += price;
        });
        this.itemsTotal = Number(sum);
        return sum;
    },

        tax(){
            var sum = 0;
            this.quotedInvoicedProducts.forEach((value, index) => {
                sum += Number(this.quotedInvoicedProducts[index]['taxamnt']);
            });
            this.taxes = Number(sum.toFixed(2));
            return sum.toFixed(2);
        },
        comsn(){
             var sum = 0;
                this.quotedInvoicedProducts.forEach((value, index) => {
                sum += Number(this.quotedInvoicedProducts[index]['product']['Commission']);
                });
                this.discount = Number(sum.toFixed(2));
                return sum.toFixed(2);
        },
        grandtol(){
            var grand = (this.itemsTotal + this.taxes) - this.discount;
            this.grandTotal = grand.toFixed(2); 
            return grand.toFixed(2);
        },

     formSubmit(e) {
        e.preventDefault();
          axios.post('/quotesUpdate/'+this.requestparams['showModuleData'][0]['id'], {
                Quote_Subject: this.quoteData[0]['Quote_Subject'],
                Quote_Stage: this.quoteData[0]['Quote_Stage'],
                Client: this.quoteData[0]['Client']['value'],
                Valid_To: this.quoteData[0]['Valid_To'],
                Amount: this.grandTotal,     
                Opportunity: this.quoteData[0]['Opportunity']['value'],
                Assigned_To: this.quoteData[0]['Assigned_To']['value'],
                Quote_Stage: this.quoteData[0]['Quote_Stage']['value'],  
                Organization: this.quoteData[0]['Organization']['value'],
                Description: this.quoteData[0]['Description'],
                products: this.quotedInvoicedProducts,

                }).then((response) => {
                    var path = response.data;
                  $('#alertSuccessModal').modal({backdrop: false});
                  this.redirectMe(path);
                });
        },
        redirectMe(path) {
            setTimeout(window.location.href ='/'+path, 3000)
        }
    },

    components: {
      ModelSelect, datePicker
    },

  }
</script> 