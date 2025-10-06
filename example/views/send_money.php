<?php
    include "layout.php";
?>
<div class="container">
    <form id="sendMoney" action="/send_money" method="post">
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="destination[type]"> Destination Type </label>
            <div class="col-sm-7">
                <select class="form-control" id="destinationType" name = "destination[type]" required>
                    <option value="my_accounts">My Accounts</option>
                    <option value="merchant_wallet">Merchant Wallet</option>
                    <option value="merchant_bank_account">Merchant Bank Account</option>
                    <option value="mobile_wallet">Mobile Wallet</option>
                    <option value="bank_account"> Bank Account </option>
                    <option value="till">Till</option>
                    <option value="paybill">Paybill</option>
                </select>
                <div class="small form-text text-muted">Select destination type</div>
            </div>
        </div>

        <div id="destinationDetails">
        </div>

        <br/>
        <div class="form-group row">
            <div class="col-sm-7">
                <button class="btn btn-success" type="submit">Send Money</button>
            </div>
        </div>
    </form>
</div>

<script>
    $("document").ready(()=> {
        const destinationType = $("#destinationType")
        renderDestinationDetails(destinationType.val());

        destinationType.on("change", function () {
            renderDestinationDetails($(this).val());
        });
    });

    function renderDestinationDetails(destination) {
        const container = $("#destinationDetails");
        $(".destination").remove();

        switch (destination) {
            case "merchant_wallet":
                return renderMyAccountDestinationDetails(container);
            case "merchant_bank_account":
                return renderMyAccountDestinationDetails(container);
            case "mobile_wallet":
                return renderExternalMobileWalletDestinationDetails(container);
            case "bank_account":
                return renderExternalBankAccountDestinationDetails(container);
            case "till":
                return renderExternalTillDestinationDetails(container);
            case "paybill":
                return renderExternalPaybillDestinationDetails(container);
            default:
                return renderMyAccountsDestinationDetails(container);
        }
    }

    function renderMyAccountsDestinationDetails(parent){
        parent.append(`
            <!--  Send money to my accounts  -->
            <div id="sendMoneyToMyAccounts" class="destination">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="callbackUrl">Callback URL</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="callbackUrl" type="text" placeholder="Enter callback url" required/>
                        <div class="small form-text text-muted">Enter the callback url</div>
                    </div>
                </div>
            </div>
            <!-- End of send money to my accounts -->`
        )
    }

    function renderMyAccountDestinationDetails(parent) {
        parent.append(`
            <!--  Send money to my account  -->
            <div id="sendMoneyToMyAccount" class="destination">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[reference]">Destination Reference</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[reference]" type="text" placeholder="Enter destination reference" required/>
                        <div class="small form-text text-muted">Enter the destination reference</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[amount]">Amount</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[amount]" type="text" placeholder="Enter amount" required/>
                        <div class="small form-text text-muted">Enter the amount</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="sourceIdentifier">Source Identifier</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="sourceIdentifier" type="text" placeholder="Enter source identifier"/>
                        <div class="small form-text text-muted">Enter the source identifier</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="callbackUrl">Callback URL</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="callbackUrl" type="text" placeholder="Enter callback url" required/>
                        <div class="small form-text text-muted">Enter the callback url</div>
                    </div>
                </div>
            </div>
            <!-- End of send money to my account -->
            `
        );
    }

    function renderExternalMobileWalletDestinationDetails(parent) {
        parent.append(`
            <!-- Send money to external mobile wallet -->
            <div id="sendMoneyToEternalMobileWallet" class="destination">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[phoneNumber]">Phone Number</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[phoneNumber]" type="text" placeholder="Enter phone number" required/>
                        <div class="small form-text text-muted">Enter phone number</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[nickname]">Nickname</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[nickname]" type="text" placeholder="Enter nickname"/>
                        <div class="small form-text text-muted">Enter nickname</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[amount]">Amount</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[amount]" type="text" placeholder="Enter amount" required/>
                        <div class="small form-text text-muted">Enter the amount</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[network]"> Network </label>
                    <div class="col-sm-7">
                        <select class="form-control" name="destination[network]" required>
                            <option value="Safaricom">Safaricom</option>
                        </select>
                        <div class="small form-text text-muted">Select network</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[description]">Description</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[description]" type="text" placeholder="Enter description" required/>
                        <div class="small form-text text-muted">Enter description</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="sourceIdentifier">Source Identifier</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="sourceIdentifier" type="text" placeholder="Enter source identifier"/>
                        <div class="small form-text text-muted">Enter source identifier</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="callbackUrl">Callback URL</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="callbackUrl" type="text" placeholder="Enter callback url" required/>
                        <div class="small form-text text-muted">Enter the callback url</div>
                    </div>
                </div>
            </div>
            <!-- End of send money to external mobile wallet -->`
        )
    }

    function renderExternalBankAccountDestinationDetails(parent) {
        parent.append(`
            <!-- Send money to external bank account -->
            <div id="sendMoneyToExternalBankAccount" class="destination">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[bankBranchRef]">Bank Branch Reference</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[bankBranchRef]" type="text" placeholder="Enter bank branch reference" required/>
                        <div class="small form-text text-muted">Enter bank branch reference</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[accountName]">Account Name</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[accountName]" type="text" placeholder="Enter account name" required/>
                        <div class="small form-text text-muted">Enter account name</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[accountNumber]">Account Number</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[accountNumber]" type="text" placeholder="Enter account number" required/>
                        <div class="small form-text text-muted">Enter account number</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[nickname]">Nickname</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[nickname]" type="text" placeholder="Enter nickname"/>
                        <div class="small form-text text-muted">Enter nickname</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[amount]">Amount</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[amount]" type="text" placeholder="Enter amount" required/>
                        <div class="small form-text text-muted">Enter the amount</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[description]">Description</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[description]" type="text" placeholder="Enter description" required/>
                        <div class="small form-text text-muted">Enter description</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="sourceIdentifier">Source Identifier</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="sourceIdentifier" type="text" placeholder="Enter source identifier"/>
                        <div class="small form-text text-muted">Enter source identifier</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="callbackUrl">Callback URL</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="callbackUrl" type="text" placeholder="Enter callback url" required/>
                        <div class="small form-text text-muted">Enter the callback url</div>
                    </div>
                </div>
            </div>
            <!-- End of send money to external bank account -->`
        );
    }

    function renderExternalTillDestinationDetails(parent) {
        parent.append(`
            <!-- Send money to external till -->
            <div id="sendMoneyToExternalTill" class="destination">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[tillNumber]">Till Number</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[tillNumber]" type="text" placeholder="Enter till number" required/>
                        <div class="small form-text text-muted">Enter till number</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[nickname]">Nickname</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[nickname]" type="text" placeholder="Enter nickname"/>
                        <div class="small form-text text-muted">Enter nickname</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[amount]">Amount</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[amount]" type="text" placeholder="Enter amount" required/>
                        <div class="small form-text text-muted">Enter the amount</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[description]">Description</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[description]" type="text" placeholder="Enter description" required/>
                        <div class="small form-text text-muted">Enter description</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="sourceIdentifier">Source Identifier</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="sourceIdentifier" type="text" placeholder="Enter source identifier"/>
                        <div class="small form-text text-muted">Enter source identifier</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="callbackUrl">Callback URL</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="callbackUrl" type="text" placeholder="Enter callback url" required/>
                        <div class="small form-text text-muted">Enter the callback url</div>
                    </div>
                </div>
            </div>
            <!-- End of send money to external till -->`
        );
    }

    function renderExternalPaybillDestinationDetails(parent) {
        parent.append(`
            <!-- Send money to external paybill -->
            <div id="sendMoneyToExternalPaybill" class="destination">
                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[paybillNumber]">Paybill Number</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[paybillNumber]" type="text" placeholder="Enter paybill number" required/>
                        <div class="small form-text text-muted">Enter paybill number</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[paybillAccountNumber]">Account Number</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[paybillAccountNumber]" type="text" placeholder="Enter account number" required/>
                        <div class="small form-text text-muted">Enter account number</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[nickname]">Nickname</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[nickname]" type="text" placeholder="Enter nickname"/>
                        <div class="small form-text text-muted">Enter nickname</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[amount]">Amount</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[amount]" type="text" placeholder="Enter amount" required/>
                        <div class="small form-text text-muted">Enter the amount</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="destination[description]">Description</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="destination[description]" type="text" placeholder="Enter description" required/>
                        <div class="small form-text text-muted">Enter description</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="sourceIdentifier">Source Identifier</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="sourceIdentifier" type="text" placeholder="Enter source identifier"/>
                        <div class="small form-text text-muted">Enter source identifier</div>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-2 col-form-label" for="callbackUrl">Callback URL</label>
                    <div class="col-sm-7">
                        <input class="form-control" name="callbackUrl" type="text" placeholder="Enter callback url" required/>
                        <div class="small form-text text-muted">Enter the callback url</div>
                    </div>
                </div>
            </div>
            <!-- End of send money to external paybill -->`
        );
    }
</script>