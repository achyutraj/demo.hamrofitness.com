<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-body">
                <div class="card">
                    <div class="card-body features_description">
                        <div class="title mb-2">
                            <div>
                                <div
                                    class="text-uppercase text-primary font-medium-2 mb-3">
                                    Membership API
                                </div>

                                <p>{{ config('app.name') }} Membership API helps you
                                    to list your payment done and need to be done for membership plan price.</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/membership/due-payments
                                </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Parameters
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-primary">
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Required</th>
                                            <th style="width:50%;">Description</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <td>Authorization</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>When calling our API, send your api
                                                token with the authentication type
                                                set as <code>Bearer</code> (Example:
                                                <code>Authorization: Bearer
                                                    {api_token}</code>)
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Accept</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>Set to <code>application/json</code>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 font-medium-2 text-primary">
                                    Example request
                                </div>
                                <pre>
                                    <code class="language-php">
                                        curl -X GET {{ config('app.url') }}/api/customer/membership/due-payments/ \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a contact object if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                     {
                                        "success": true,
                                        "data": [
                                            {
                                                "amount_to_be_paid": 15000,
                                                "paid_amount": 0,
                                                "discount": 0,
                                                "due_date": "2022-08-16",
                                                "membership": "Silver Membership",
                                                "id": 2
                                            },{
                                            ...
                                        }],
                                        "message": ""
                                    }
                                    </code>
                                </pre>
                            </div>
                            <div>
                                <div
                                    class="text-uppercase text-primary font-medium-2 mb-3">
                                     Membership Payment
                                </div>

                                <p>{{config('app.name')}} returns the payment object of membership.</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/payments
                                </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Parameters
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-primary">
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Required</th>
                                            <th style="width:50%;">Description</th>
                                        </tr>
                                        </thead>

                                        <tbody>
                                        <tr>
                                            <td>Authorization</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>When calling our API, send your api
                                                token with the authentication type
                                                set as <code>Bearer</code> (Example:
                                                <code>Authorization: Bearer
                                                    {api_token}</code>)
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>Accept</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>Set to <code>application/json</code>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 font-medium-2 text-primary">
                                    Example request
                                </div>
                                <pre>
                                    <code class="language-php">
                                        curl -X GET {{ config('app.url') }}/api/customer/payments \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a payment object if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "success": true,
                                            "data": [{
                                                "first_name":"Ram",
                                                "payment_id":1,
                                                "payment_amount":2000,
                                                "payment_source":"esewa",
                                                "payment_date":"2022-08-20",
                                                "payment_type":"membership",
                                            }],
                                            "message": "Membership Payment List"
                                        }
                                    </code>
                                </pre>
                            </div>
                            <div>
                                <div class="text-uppercase text-primary font-medium-2 mb-3">
                                     Create Membership Payment
                                </div>

                                <p>Creates a new payment object. {{config('app.name')}} returns the payment object</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/payments/store
                                </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Parameters
                                </div>
                                <div class="table-responsive">
                                    <table class="table">
                                        <thead class="thead-primary">
                                        <tr>
                                            <th>Parameter</th>
                                            <th>Required</th>
                                            <th>Type</th>
                                            <th style="width:50%;">Description</th>
                                        </tr>
                                        </thead>

                                        <tbody>

                                        <tr>
                                            <td>purchase_id</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>bigint</td>
                                            <td>Gym Client Purchases<code>id</code></td>
                                        </tr>
                                        <tr>
                                            <td>payment_amount</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>integer</td>
                                            <td>Cost paid by customer</td>
                                        </tr>
                                        <tr>
                                            <td>payment_source</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>enum</td>
                                            <td>Payment source</td>
                                        </tr>
                                        <tr>
                                            <td>remarks</td>
                                            <td> </td>
                                            <td>string</td>
                                            <td>Remarks</td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 font-medium-2 text-primary">
                                    Example request
                                </div>
                                <pre>
                                    <code class="language-php">
                                        curl -X POST {{ config('app.url') }}/api/customer/payments/store \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                         -d "purchase_id=123" \
                                        -d "payment_source=PaymentSource" \
                                        -d "payment_amount=1500" \
                                        -d "remarks=Testing Payment" \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a payment object if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "success": true,
                                            "data": [{
                                                "first_name":"Ram",
                                                "payment_id":1,
                                                "payment_amount":2000,
                                                "payment_source":"esewa",
                                                "payment_date":"2022-08-20",
                                                "payment_type":"membership",
                                            }],
                                            "message": "Membership Payment Added"
                                        }
                                    </code>
                                </pre>
                                <p>If the request failed, an error object will be returned.</p>
                                <pre>
                                    <code class="language-json">
                                       {
                                            "message": "The given data was invalid.",
                                            "errors": {
                                                "purchase_id": [
                                                    "Purchased Name is required."
                                                ],
                                                "payment_amount": [
                                                    "Membership Amount is required."
                                                ]
                                            }
                                        }
                                    </code>
                                </pre>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
