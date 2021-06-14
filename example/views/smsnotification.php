<?php
    include 'layout.php';
?>
<div class="container">
    <form (action="/smsnotification", method="post")>
    <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="message")> Webhook Event Reference</label>
            <div class="col-sm-7">
                <input class="form-control" name="webhookEventReference"  type='text' placeholder='Enter webhook event reference' required/>
                <div class="small form-text text-muted">
                    Enter the webhook event reference
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" (for="message")> Message </label>
            <div class="col-sm-7">
                <input class="form-control" name="message"  type='text' placeholder='Enter message' required/>
                <div class="small form-text text-muted">
                    Enter the message
                </div>
            </div>
        </div>
        <br/>
        <div class="form-group.row">
            <div class="col-sm-7">
                <button class="btn btn-success"(type='submit')> Send Transaction SMS Notification
            </div>
        </div>
    </form>
</div> 
