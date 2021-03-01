<template>
    <div id="deleteResourceModal"
         class="modal fade"
         tabindex="-1"
         aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="text-danger mb-4">
                        <i class="far fa-times-circle fa-4x"></i>
                    </div>
                    <h4 class="mb-4">
                        Delete {{ resourceInfo.label }}?
                    </h4>
                    <p class="text-muted mb-4">
                        Do you really want to delete these resource? This action cannot be undone.
                    </p>
                    <div class="d-flex justify-content-center">
                        <button id="dialog-cancel"
                                class="btn btn-secondary mx-1"
                                type="button"
                                data-bs-dismiss="modal">
                            Cancel
                        </button>
                        <button id="dialog-confirm"
                                class="btn btn-danger mx-1"
                                type="button"
                                data-bs-dismiss="modal">
                            Confirm
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        resourceInfo: {
            type: Object,
            required: true
        }
    }
};
</script>
