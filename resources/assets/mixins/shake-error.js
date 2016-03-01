import MessageHelper from '../helpers/message'

export default {

    data() {
        return {
            error: false,
        }
    },

    methods: {

        shakeError(message) {
            MessageHelper.error(message);

            this.error = true;
            setTimeout(function() {
              this.error = false;
            }.bind(this), 1000);
        }

    }

}
