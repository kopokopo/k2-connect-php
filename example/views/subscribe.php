<?php
    include 'layout.php';
?>
<div class="container">
    <form id="bulkSmsForm"(action="/webhook/subscribe", method="post")>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="eventType")> Event Type </label>
            <div class="col-sm-7">
                <select class="form-control" name = "eventType">
                    <option value="buygoods_transaction_received"> Buygoods Transaction Received </option>
                    <option value="b2b_transaction_received"> B2b Transaction Received </option>
                    <option value="m2m_transaction_received"> M2m Transaction Received </option>
                    <option value="buygoods_transaction_reversed"> Buy Goods Reversed </option>
                    <option value="settlement_transfer_completed"> Settlement Completed </option>
                    <option value="customer_created"> Customer Created </option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="scope")> Scope </label>
            <div class="col-sm-7">
                <select class="form-control" name = "scope">
                    <option value="company"> Company </option>
                    <option value="till"> Till </option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="scope_ref")> Scope Reference(Till Number) </label>
            <div class="col-sm-7">
                <input class="form-control" name="scope_ref"  type='text' placeholder='Enter scope reference'/>
                <div class="small form-text text-muted">
                    Enter the scope reference(Leave null for company scope)
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="enableDarajaPayload")>Enable darja payload</label>
            <div class="col-sm-7">
                <input class="form-control" name="enableDarajaPayload" type="checkbox" style="width: 1.5rem; height: 1.5rem" switch/>
                <div class="small form-text text-muted">
                    Enable daraja payload
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-3 col-form-label" (for="url")> URL </label>
            <div class="col-sm-7">
                <input class="form-control" name="url"  type='text' placeholder='Enter URL' required/>
                <div class="small form-text text-muted">
                    Enter the webhook url
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Subscribe
            </div>
        </div>
    </form>
</div>

<script>
    $("document").ready(() => {
        const eventType = $("select[name='eventType']");
        const enableDarajaPayload = $("input[name='enableDarajaPayload']");
        const darajaEventTypes = ["buygoods_transaction_received", "b2b_transaction_received"]

        toggleEnableDarajaPayloadCheckbox(eventType.val());

        eventType.on("change", function() {
            toggleEnableDarajaPayloadCheckbox($(this).val());
        });

        function toggleEnableDarajaPayloadCheckbox(eventType) {
            if (darajaEventTypes.includes(eventType)) {
                enableDarajaPayload.prop("disabled", false);
            } else {
                enableDarajaPayload.prop("disabled", true);
                enableDarajaPayload.prop("checked", false);
            }
        }
    })
</script>

