<template>
    <div class="col">
            <div class="text-center">
                <h5 class="content-title">Conversations</h5>
            </div>
        <div class="chat-box" id="chat">
            <form action="#" @submit="formSubmit">
                <div class="input-group align-middle">
                  <input type="text" placeholder="Type a message" aria-describedby="button-addon2" class="form-control rounded-pill border border-success py-4" id="message" v-model='message'>
                    <button type="submit" class="btn btn-link"> <i class="fa fa-paper-plane"></i></button>
                </div>
            </form>
            <messagechatbox-component :messagepayloads="messagepayloads"></messagechatbox-component>
        </div>
    </div>
</template>

<script>
 import Event from '../chatEvent.js';
    export default {
        mounted() {
                console.log('Mounted');
            },
        data() {
            return {
              message: '',
              module_name: '',
              object_id: '',
              User_id:'',
              messagepayloads: []
            };
        },
        props: ['requestparams'],

        methods: {
           formSubmit(e) {
            e.preventDefault();
            if(!this.message || this.message.trim() === '') {
                    return
                }
            axios.post('/moduleChats', {
                    message: this.message,
                    module_name: this.requestparams.moduleName,
                    object_id: this.requestparams.objectId,
                    User_id: this.requestparams.userId
                    }).catch(() => {
                         console.log('failed');
                    });
            this.messagepayloads.push({
                    message: this.message,
                    created_at: new Date()
                    })
            
            this.message = "";
            }
        }
    }
</script>