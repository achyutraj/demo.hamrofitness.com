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
                                    Profile API
                                </div>

                                <p>{{ config('app.name') }} Profile Api allows you to retrieve your profile information.</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/profile
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
                                        curl -X GET {{ config('app.url') }}/api/customer/profile/ \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a attendance object if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                         {
                                            "success": true,
                                            "data": {
                                                "id": 2,
                                                "api_token": "16|Ap4IkNysdsdsjT2fkJsdS6CATMrLo6jBMFrTYAcBL",
                                                "first_name": "Kamal",
                                                "middle_name": null,
                                                "last_name": "Sharma",
                                                "dob": "1997-06-13T00:00:00.000000Z",
                                                "gender": "male",
                                                "email": "kamal@yahoo.com",
                                                 ...
                                            },
                                            "message": "Profile Information"
                                        }
                                    </code>
                                </pre>
                                <p>If the request failed, an error object will be returned.</p>
                                <pre>
                                    <code class="language-json">
                                        {
                                        "success": false,
                                        "message": "No user found"
                                        }
                                    </code>
                                </pre>
                            </div>
                            <div>
                                <div
                                    class="text-uppercase text-primary font-medium-2 mb-3">
                                    Update Profile API
                                </div>

                                <p>{{ config('app.name') }} Update Profile Api allows you to change your profile information.</p>

                                <p class="font-medium-2 mt-2">API Endpoint</p>
                                <pre>
                                <code class="language-markup">
                                    {{ config('app.url') }}/api/customer/profile/store
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
                                            <td>first_name</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>string</td>
                                            <td>First Name</td>
                                        </tr>

                                        <tr>
                                            <td>last_name</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>string</td>
                                            <td>Last Name</td>
                                        </tr>

                                        <tr>
                                            <td>email</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>email</td>
                                            <td>Customer email</td>
                                        </tr>

                                        <tr>
                                            <td>mobile</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>string</td>
                                            <td>Customer Contact No.</td>
                                        </tr>

                                        <tr>
                                            <td>gender</td>
                                            <td>
                                                <div
                                                    class="badge badge-primary text-uppercase mr-1 mb-1">
                                                    <span>Yes</span></div>
                                            </td>
                                            <td>string</td>
                                            <td>Specify Customer gender</td>
                                        </tr>


                                        </tbody>
                                    </table>
                                </div>
                                <div class="mt-2 font-medium-2 text-primary">
                                    Example request
                                </div>
                                <pre>
                                    <code class="language-php">
                                        curl -X POST {{ config('app.url') }}/api/customer/profile/store \
                                        -H 'Authorization: Bearer 7|xs6pv2dspHJq8sWLhrpNFH5YLilMRQcVxLwSw2Sd' \
                                        -d "first_name=Test" \
                                        -d "last_name=User" \
                                        -d "mobile=9812121212" \
                                        -d "email=1500" \
                                        -d "gender=male" \
                                    </code>
                                </pre>

                                <div class="mt-2 font-medium-2 text-primary">
                                    Returns
                                </div>
                                <p>Returns a profile object if the request was
                                    successful. </p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "success": true,
                                            "data": {
                                                "id": 2,
                                                "first_name": "kamal",
                                                "middle_name": null,
                                                "last_name": "Sharma",
                                                "gender": "male",
                                                "email": "kamal@yahoo.com",
                                                "mobile": 9745454545,
                                                 ...
                                            },
                                            "message": "Profile Store Successfully"
                                        }
                                    </code>
                                </pre>
                                <p>If the request failed, an error object will be returned.</p>
                                <pre>
                                    <code class="language-json">
                                        {
                                            "message": "The given data was invalid.",
                                            "errors": {
                                                "mobile": [
                                                    "The mobile field is required."
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
