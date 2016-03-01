import Vue from 'vue'
import MessageHelper from '../helpers/message'

const component = Vue.extend({
    template: `
        <div class="panel panel-default" v-bind:class="{ 'shake': error, 'animated': error }">
            <slot></slot>
        </div>
    `,

    data() {
        return {
            error: false,
        }
    },

    ready() {
        this.$on("shake", (message) => {
            MessageHelper.error(message);

            this.error = true;
            setTimeout(function() {
              this.error = false;
            }.bind(this), 1000);
        })
    }
});

const mixin = {
    methods: {
        shakeError(message) {
            this.$broadcast("shake", message);
        }
    }
}

export default {
    component,
    mixin
};
