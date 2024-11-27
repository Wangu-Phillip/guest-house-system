<!-- SUCCESS/ERROR TOAST -->
<div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
    <div id="toastNotification" class="toast align-items-center" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header bg-light">
            <span id="toastIcon" class="me-2"></span>
            <strong id="toastHeading" class="me-auto">Message</strong>
            <small id="toastTime">Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            <!-- Toast Message -->
        </div>
        <div id="toastProgress" class="progress position-relative bottom-0 start-2 w-100" style="height: 3px;">
            <div class="progress-bar bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
</div>
