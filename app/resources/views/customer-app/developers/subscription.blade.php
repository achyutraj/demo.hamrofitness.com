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
                                    Subscriptions API
                                </div>

                                <p>{{ config('app.name') }} Subscription API helps you
                                    to view all your subscription.</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/manage-subscription
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
                                        curl -X GET {{ config('app.url') }}/api/customer/manage-subscription/ \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a subscription object if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "success": true,
                                            "data": [{
                                                "amount_to_be_paid":15000,
                                                "paid_amount":0,
                                                "membership":"Silver Membership",
                                                "date":"2022-08-16",
                                                "next_payment_date":"2022-08-16T00:00:00.000000Z",
                                                "expires_on":"2022-09-16T00:00:00.000000Z",
                                                "status":"active"
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
                                    View Subscription API
                                </div>

                                <p>{{ config('app.name') }} Subscription API helps you
                                    to view selected subscription.</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/manage-subscription/show/123
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
                                        curl -X GET {{ config('app.url') }}/api/customer/manage-subscription/show/123 \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a subscription object if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "success": true,
                                            "data": {
                                                "amount_to_be_paid":15000,
                                                "paid_amount":0,
                                                "membership":"Silver Membership",
                                                "date":"2022-08-16",
                                                "next_payment_date":"2022-08-16T00:00:00.000000Z",
                                                "expires_on":"2022-09-16T00:00:00.000000Z",
                                                "status":"active"
                                            },
                                            "message": ""
                                        }
                                    </code>
                                </pre>
                            </div>
                            <div>
                                <div
                                    class="text-uppercase text-primary font-medium-2 mb-3">
                                    Create a Subscription
                                </div>

                                <p>Creates a new Subscription object. {{config('app.name')}} returns the created subscription object with each request</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/manage-subscription/store
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
                                            <td>branch_id</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>integer</td>
                                            <td>Common Details<code>(id) Note: Common details store all branches data </code> </td>
                                        </tr>

                                        <tr>
                                            <td>membership_id</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>integer</td>
                                            <td>Gym Memberships <code>id</code>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>cost</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>integer</td>
                                            <td>Price of membership</td>
                                        </tr>
                                        <tr>
                                        <td>joining_date</td>
                                        <td>
                                            <div
                                                class="badge badge-primary text-uppercase mr-1 mb-1">
                                                <span>Yes</span></div>
                                        </td>
                                        <td>date</td>
                                        <td>Joining date of membership plan <code>Note: date must be in m/d/Y format</code></td>
                                        </tr>

                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 font-medium-2 text-primary">
                                    Example request
                                </div>
                                <pre>
                                    <code class="language-php">
                                        curl -X POST {{ config('app.url') }}/api/customer/manage-subscription/store \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                        -d "branch_id=BranchId" \
                                        -d "membership_id=MembershipId" \
                                        -d "cost=1500" \
                                        -d "joining_date=02/18/2022" \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a subscription object if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "success": true,
                                            "data": {
                                                "client_id": 2,
                                                "membership_id": 1,
                                                "detail_id": 1,
                                                "purchase_amount": 1500,
                                                "amount_to_be_paid": 1500,
                                                ...
                                            },
                                            "message": "Membership Subscription Added."
                                        }
                                    </code>
                                </pre>
                            </div>
                            <div>
                                <div
                                    class="text-uppercase text-primary font-medium-2 mb-3">
                                    Delete a Subscription
                                </div>

                                <p>Delete a subscription object which is requested by customer themselves. Note: for deletion subscription need to be in pending status otherwise, it not able to customer.</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/manage-subscription/destroy/123
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
                                            <td>id</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>integer</td>
                                            <td>Gym Purchases <code>(id) Note: Subscription detail are store as Gym Purchases </code> </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 font-medium-2 text-primary">
                                    Example request
                                </div>
                                <pre>
                                    <code class="language-php">
                                        curl -X DELETE {{ config('app.url') }}/api/customer/manage-subscription/destroy/123 \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                        -d "id=123" \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a subscription deleted message if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "success": true,
                                            "data": {
                                            },
                                            "message": "Membership Subscription Deleted."
                                        }
                                    </code>
                                </pre>
                                <p>If the request failed, an error object will be returned.</p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "success": false,
                                            "data": {
                                            },
                                            "message": "Subscription not deleted."
                                        }
                                    </code>
                                        OR
                                     <code class="language-json">
                                        {
                                            "success": false,
                                            "data": {
                                            },
                                            "message": "Membership Subscription not found."
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
