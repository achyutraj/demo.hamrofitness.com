<div class="row">
    <div class="col-md-12">
        <div class="portlet light ">
            <div class="portlet-body">
                <div class="card">
                    <div class="card-body features_description">
                        <div class="title mb-2">
                            <div
                                class="text-uppercase text-primary font-medium-2 mb-3">
                                Dashboard API
                            </div>

                            <p>{{ config('app.name') }} Dashboard API helps you
                                to view all your subscription ,total amount paid
                                which is the total of membership and products.
                                Customer can also view their custom and default
                                diet plan along with class schedule.</p>
                            <p>The Dashboard API uses HTTP verbs and a RESTful
                                endpoint structure with an access key that is
                                used as the API Authorization.
                                Request and response payloads are formatted as
                                JSON using UTF-8 encoding and URL encoded
                                values.</p>

                            <p class="font-medium-2 mt-2">API Endpoint</p>
                            <pre>
                            <code class="language-markup">
                                {{ config('app.url') }}/api/customer/dashboard
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
                                    curl -X GET {{ config('app.url') }}/api/customer/dashboard/ \
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
                                        "data": {
                                            "customerValues": {},
                                            ....
                                            "class_schedule": []
                                        },
                                        "message": "Customer Dashboard"
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
