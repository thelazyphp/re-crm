import { Modal } from 'bootstrap';

export default {
    methods: {
        confirm (id, onCancel, onConfirm) {
            const modal = new Modal(document.getElementById(id));
            modal.show();

            document.getElementById('dialog-cancel').addEventListener('click', event => {
                event.stopPropagation();
                return onCancel();
            });

            document.getElementById('dialog-confirm').addEventListener('click', event => {
                event.stopPropagation();
                return onConfirm();
            });
        }
    }
};
