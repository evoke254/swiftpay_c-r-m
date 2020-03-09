<template>
    <div class="row">
         <div style="height: 580px" class="col overflow-auto">
          <form action="#" @submit.prevent="formSubmit">
              <div class="row">
                  <div class="panel-body  col-lg-12 col-md-12">
                      <table class="table table-lg rounded-top">
                          <thead class="thead-dark rounded-top">
                              <tr>
                                  <th class="text-nowrap">Tools</th>
                                  <th class="text-nowrap">Image</th>
                                  <th class="text-nowrap">Name</th>
                                  <th class="text-nowrap">Quantity</th>
                                  <th class="text-nowrap">Discount</th>
                                  <th class="text-nowrap"> Net Price</th>
                              </tr>
                          </thead>
                          <tbody> 
                              <tr v-for="(quotedInvoicedProduct, key) in quotedInvoicedProducts" class="element text-center">
                                  <td id="remove+key">
                                      <i @click="delTableRow(key)" class="hoverable myPointer fas fa-trash-alt fa-3x"></i></td>
                                  <td>
                                      <img 
                                          style="height: 75px !important"
                                          class="rounded-4 p-0 m-0" 
                                          :src="'/'+quotedInvoicedProduct['product']['Image']">
                                  </td>
                                  <td width="25%">            
                                      <input class="form-control border border-light text-dark" 
                                             type="text"
                                             disabled="" 
                                             :value="quotedInvoicedProduct['product']['Product_Name']"
                                      >
                                  </td>
                                  <td>
                                      <input class="form-control border border-light" 
                                              v-model="quotedInvoicedProduct['quantity']"
                                      >
                                      <input  class="form-control border border-light"  type="hidden" 
                                              v-model="quotedInvoicedProduct['product']['Selling_Price']"
                                      >
                                  </td>
                                  <td>
                                      <input class="form-control border border-light"  
                                             v-model="quotedInvoicedProduct['product']['Commission']"
                                      >
                                  </td>
                                  <td class="font-weight-bold"> {{prdctNetPrice(key) }}
                                      <input type="hidden" v-model="quotedInvoicedProducts[key]['net']">
                                  </td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
              <div class="row mb-5 justify-content-end">
                  <div class="panel-body col-md-5">
                      <table class="table table-bordered">
                          <tbody class="font-weight-bold">
                              <tr>
                                  <td>Items Total</td>
                                  <td class="font-weight-bold text-right" > {{ itemsamnt() }} </td>
                              </tr>
                              <tr>
                                  <td>Taxes</td>
                                  <td class="font-weight-bold text-right"> {{tax()}} </td>
                              </tr>
                              <tr>
                                  <td>Discount</td>
                                  <td class="font-weight-bold text-right">{{ comsn() }}</td>
                              </tr>
                              <tr>
                                  <td>Grand Total</td>
                                  <td class="font-weight-bold text-right">{{ grandtol() }}</td>
                              </tr>
                          </tbody>
                      </table>
                  </div>
              </div>
              <div class="row mt-n5 mb-4">
                  <div class="col-md-6">
                      <label for="lbl" style="font-size: 135%">Cash Amount:</label>
                      <div class="input-group input-group-lg" style="height: 35px">
                        <div class="input-group-prepend">
                          <span class="input-group-text" id="inputGroup-sizing-lg">Kshs.</span>
                        </div>
                        <input type="text" v-model.number="tenderedAmnt" id="lbl" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm">
                      </div>
                  </div>
                  <div class="col-md-6 text-center ">
                      <label for="lbl" class="pull-left" style="font-size: 135%">Change:</label>
                      <label for="lbl" class="pull-right" style="font-size: 150%">{{change()}}</label>
                  </div>
              </div>
          </form>
        <hr>         
        <div class="row justify-content-center text-center">
            <div class="col-md-12">
                <button v-on:click="payCash()" class="btn btn-sm btn-dark-green">
                    <i class="fas fa-wallet fa-3x"></i>
                    Cash: Kshs. <br> <span class="ml-5 text-right">{{grandTotal}}</span>
                </button>
                <hr>
            </div>

            <div class="col-md-4">
                <button v-on:click="setStatus('Pending')" class="btn btn-sm btn-outline-warning">
                    <i class="fas fa-cloud fa-3x"></i>
                    Save for <br> <span class="ml-5 text-center">later</span>
                </button>
            </div>
            <div class="col-md-4">
                <a href v-on:click="paymentMethods" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-credit-card fa-3x"></i>
                         Opt: Kshs. <br> <span class="ml-5 text-right">{{grandTotal}}</span>
                </a>
            </div>
            <div class="col-md-12">
                <hr>
            </div>
        </div>

        <div class="modal fade modal fade justify-content-center" id="paymentOptions" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-success modal-fluid w-75" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h4 class="modal-title w-100 text-center" id="myModalLabel">Payment Methods</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body row">
                   <div class="col border border-light hoverable myPointer text-center rounded m-5" v-on:click="payMpesa()">
                        <h4 class="content-title mt-1 mb-n1">M-PESA</h4>
                        <img src="/images/payOPt/M-pesa-logo.png" class="img-fluid" style="height: 175px">
                    </div>
                   <div class="col border border-light hoverable myPointer text-center rounded m-5" v-on:click="pay('VISA')">
                    <h4 class="content-title mt-1 mb-n1">VISA</h4>
                        <img src="/images/payOPt/Visa.jpg" class="img-fluid" style="height: 175px">
                  </div>
                   <div class="col border border-light hoverable myPointer text-center rounded m-5" v-on:click="pay('MASTER')">
                    <h4 class="content-title mt-1 mb-n1">MASTERCARD</h4>
                        <img src="/images/payOPt/mastercard-logo.jpg" class="img-fluid img-responsive" style="height: 175px">
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>

        <div class="modal fade" id="getmpesaNumber" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
            <div class="modal-dialog modal-success modal-lg" role="document">
              <div class="modal-content text-center">
                <div class="modal-header text-center">
                  <h4 class="modal-title w-100 text-center" id="myModalLabel">MPESA number to receive Prompt</h4>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body row d-flex justify-content-center">
                    <div class="col-md-6">
                        <label for="lbl" style="font-size: 135%">Cash Amount:</label>
                        <div class="input-group input-group-lg" style="height: 35px">
                          <div class="input-group-prepend">
                            <span class="input-group-text" id="inputGroup-sizing-lg">+254</span>
                          </div>
                          <input type="text" v-model.number="mpesaNumber" id="lbl" class="form-control" aria-label="Large" aria-describedby="inputGroup-sizing-sm" placeholder="MPESA Number">
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-around">
                  <button type="button" class="btn btn-light btn-md" data-dismiss="modal">Close</button>
                  <button v-on:click="payMpesaSubmit('MPESA')" type="button" class="btn btn-success btn-md" data-dismiss="modal">Pay</button>
                </div>
              </div>
            </div>
        </div>
        
        <div class="modal fade" id="alertSuccessModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                    <h6 id="controllerSuccess"> Waiting Confiration. <br> <br>{{srvResponse}}</h6>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-success btn-sm" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
        </div>

        <div class="modal fade" id="alertErrorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-notify modal-danger" role="document">
             <div class="modal-content">
               <!--Header-->
               <div class="modal-header">
                 <h5 class="heading lead text-center"> Error !!</h5>

                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true" class="white-text">&times;</span>
                 </button>
               </div>
               <div class="modal-body">
                 <div class="text-center">
                   <i class="far fa-frown-open fa-4x mb-3 animated rotateIn"></i>
                   <p>System Error: {{srvResponse}} </p>
                 </div>
               </div>
               <div class="modal-footer justify-content-center">
                 <a type="button" class="btn btn-outline-danger waves-effect" data-dismiss="modal">Close</a>
               </div>
             </div>
            </div>
        </div>
       </div>
       <div class="col">
            <div class="row d-flex justify-content-center mt-3">
                <div class="col-md-4 mx-auto">
                    <a href="/pendingbills" type="button" class="btn btn-sm btn-warning ">Pending Bills</a>
                </div>
                <div class="col-md-8 mx-auto text-center">
                    <model-select 
                            class="form-control border border-success rounded-4"
                            :options="requestparams['products']"
                            v-model="getproduct" 
                            @input="getselectedproduct"
                            placeholder="Select product">
                    </model-select>
                </div>
            </div>
            <hr>
            <div style="height: 150px" class="col-md-12 overflow-auto">
                <h5 class="content-title">Product Categories</h5>
            </div>
            <hr>
            <div style="height: 320px" class="col-md-12 overflow-auto">
                <h5 class="content-title">All Products</h5> <br>
                <div class="row mt-n5 p-0">
                    <div class="col-md-2 p-0 mr-2 text-center" v-for="product in requestparams.Allproducts">
                            <span class="badge badge-warning">Kshs. {{product.Selling_Price}}</span>
                            <img 
                                style="height: 75px !important"
                                class="myPointer hoverable rounded-4 p-0 m-0" 
                                :src="'/'+product.Image" 
                                v-on:click= "getclickedproduct(product.id)"
                            >
                    </div>
                </div>
            </div>
       </div>
    </div>
</template>

<script>


import 'vue-search-select/dist/VueSearchSelect.css';
import { ModelSelect } from 'vue-search-select';
  export default {
    props: ['requestparams'],
    mounted() {
        console.log('test');
    },
    data () {
      return {
            quoteData: [{
                
                Client: {
                  value: '',
                  text: ''
                },
                Assigned_To: {
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
            payVia:'',
            Status: '',
            Pay: '',
            srvResponse: '',
            mpesaNumber: '',
            tenderedAmnt: '',
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
      getclickedproduct(id) {
        
        axios.get('/getproduct/'+id).then((response) => {
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
      paymentMethods(e){
        e.preventDefault();
            $('#paymentOptions').modal({backdrop: false});
      },

      payCash() {
        this.Pay = 'Pay';
        this.payVia = 'CASH';
        this.formSubmit();
      },
      pay(payVia) {
        this.Pay = 'Pay';
        this.payVia = payVia;
        $('#paymentOptions').modal('hide');
        this.formSubmit();
      },
      payMpesa() {
        $('#paymentOptions').modal('hide');
        $('#getmpesaNumber').modal({backdrop: false});
      },
      payMpesaSubmit(payVia) {
        this.Pay = 'Pay';
        this.payVia = payVia;
        $('#paymentOptions').modal('hide');
        $('#paymentOptions').modal('hide');
        
        this.formSubmit();
      },



        prdctNetPrice(key) {
            var price = ((Number(this.quotedInvoicedProducts[key]['quantity']) * this.quotedInvoicedProducts[key]['product']['Selling_Price'])
                            - this.quotedInvoicedProducts[key]['product']['Commission'] );
            
            var netPrice = price;

            this.quotedInvoicedProducts[key]['net'] = netPrice;
            return(netPrice.toFixed(2));
        },
        itemsamnt(){
            var sum = 0;
            this.quotedInvoicedProducts.forEach((value, index) => {
                var price = (Number(this.quotedInvoicedProducts[index]['quantity']) * Number(this.quotedInvoicedProducts[index]['product']['Selling_Price'])) *0.84;
                sum += price;
            });
            this.itemsTotal = Number(sum);
            return sum;
        },

        tax(){
            var sum = 0;
            
            this.quotedInvoicedProducts.forEach((value, index) => {
                sum += Number(this.quotedInvoicedProducts[index]['net']/100) * 16;
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

        setStatus(Pending) {
            this.Status = Pending;
        },
        change() {
            if (this.tenderedAmnt == 0) {
              return 0;
            }
            var change = (this.tenderedAmnt - this.grandTotal);
            return change.toFixed(2);
        },

        formSubmit() {
          axios.post('/posorders', {
                mpesaNumber: this.mpesaNumber,
                payVia: this.payVia,
                Pay: this.Pay,
                Status: this.Status,
                Amount: this.grandTotal,
                products: this.quotedInvoicedProducts,

                }).then((response) => {
                    var srvResponse = response.data;

                    if (srvResponse.hasOwnProperty('Error')){
                        this.srvResponse = srvResponse.Error
                        $('#alertErrorModal').modal({backdrop: false});
                    } else {
                        this.srvResponse = srvResponse.Success
                        $('#alertSuccessModal').modal({backdrop: false});
                    }
                  this.redirectMe(path);
                });
        },
     //   redirectMe(path) {
      //      setTimeout(window.location.href ='/'+path, 3000)
        //}
    },

    components: {
      ModelSelect
    },

  }
</script> 