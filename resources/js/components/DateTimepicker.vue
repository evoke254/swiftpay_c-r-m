<template>           
      <date-picker class="form-control datetimepicker-input" placeholder="Click on the calendar icon to select date and time" :config="options"></date-picker>

</template>
<script>
import datePicker from 'vue-bootstrap-datetimepicker';
    
    export default {
              mounted() {
                console.log('datetime');
            },
        components: {  datePicker },

        data () {
              return {
                date: new Date(),
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
                myselectedevent: [],    
              }
            }
          }
</script>

