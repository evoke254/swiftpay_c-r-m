<template>           
        <ul class="nav nav-tabs" id="myTab" role="tablist" :counttasks="counttasks" >

          <li v-for="tabpaneitem in tabpaneitems" class="nav-item">
            <a v-if="tabpaneitem.ariaselected == 'true' " class="font-weight-bold nav-link active text-center" :id="tabpaneitem.id" data-toggle="tab" :href="tabpaneitem.href" role="tab" :aria-controls="tabpaneitem.ariacontrols"  :aria-selected="tabpaneitem.ariaselected"> <span v-html="tabpaneitem.display"></span> </a>

            <a v-else class="font-weight-bold nav-link" :id="tabpaneitem.id" data-toggle="tab" :href="tabpaneitem.href" role="tab" :aria-controls="tabpaneitem.ariacontrols"  :aria-selected="tabpaneitem.ariaselected"> 

                <span v-html="tabpaneitem.display"></span>
                <span v-if="requestparams.counttasks !== 0 && tabpaneitem.id == 'tasks-tab' " class="badge badge-secondary notification"> {{requestparams.counttasks}} </span>
                <span v-if="requestparams.countfiles !== 0 && tabpaneitem.id == 'documents-tab' " class="badge badge-secondary notification"> {{requestparams.countfiles}} </span>
            </a>
          </li>
        </ul>
</template>

<script>
    export default {
        props: ['requestparams'],
         data() {
            return {
                tabpaneitems: [
                    {
                        id:"details-tab",
                        href:"#Details",
                        ariacontrols:"Details",
                        ariaselected:"true",
                        display:'<i class="fas fa-info-circle"></i> Details'
                    },
                    {
                        id:"updates-tab",
                        href:"#updates",
                        ariacontrols:"updates",
                        ariaselected:"false",
                        display: '<i class="fas fa-history"></i> History'
                    },
                    {
                        id:"tasks-tab",
                        href:"#tasks",
                        ariacontrols:"tasks",
                        ariaselected:"false",
                        display: '<i class="fas fa-calendar-alt"></i> Tasks'
                    },
                    {
                        id:"products-tab",
                        href:"#products",
                        ariacontrols:"products",
                        ariaselected:"false",
                        display: '<i class="fas fa-shopping-basket"></i> Products'
                    },
                    {
                        id:"documents-tab",
                        href:"#documents",
                        ariacontrols:"documents",
                        ariaselected:"false",
                        display: '<i class="fas fa-file-pdf"></i> Documents'
                    },
                    {
                        id:"quote-tab",
                        href:"#quote",
                        ariacontrols:"quote",
                        ariaselected:"false",
                        display: '<i class="fas fa-file-invoice"></i> Quotes'
                    },
                    {
                        id:"invoice-tab",
                        href:"#invoice",
                        ariacontrols:"invoice",
                        ariaselected:"false",
                        display: '<i class="fas fa-file-invoice-dollar"></i> Invoices'
                    },
                    {
                        id:"mails-tab",
                        href:"#mails",
                        ariacontrols:"mails",
                        ariaselected:"false",
                        display: '<i class="fas fa-envelope"></i> Mails'
                    },
                    {
                        id:"contact-tab",
                        href:"#contact",
                        ariacontrols:"contact",
                        ariaselected:"false",
                        display: "<i class='fas fa-users'></i> Contacts"
                    }
                ],
                counttasks: '',
                countfiles: '',
            }
        },
        mounted() {
            if (this.requestparams.moduleName == 'quotes' ) {
                this.$delete(this.tabpaneitems, 5);
            }
            else if (this.requestparams.moduleName == 'invoices') {
                this.$delete(this.tabpaneitems, 6);
            }
        
                console.log(this.counttasks);
    }
}
</script>