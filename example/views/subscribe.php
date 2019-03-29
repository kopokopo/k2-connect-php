<?php
    include 'layout.php';
?>
<div class="container">
    <form id="bulkSmsForm"(action="/webhook/subscribe", method="post")>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="event_type")> Event Type </label>
            <div class="col-sm-7">
                <select name = "event_type">
                    <option value="buy_goods_received"> Buy Goods Received </option>
                    <option value="buy_goods_reversed"> Buy Goods Reversed </option>
                    <option value="settlement_completed"> Settlement Completed </option>
                    <option value="customer_created"> Customer Created </option>
                </select>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="url")> URL </label>
            <div class="col-sm-7">
                <input class="form-control" name="url"  type='text' placeholder='Enter URL' required/>
                <div class="small form-text text-muted">
                    Enter the webhook url
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Subscribe
            </div>
        </div>
    </form>
</div> 

