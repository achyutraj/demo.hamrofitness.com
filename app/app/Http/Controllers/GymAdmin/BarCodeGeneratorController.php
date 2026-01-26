<?php

namespace App\Http\Controllers\GymAdmin;

use App\Classes\Reply;
use App\Mail\BarCodeNotification;
use App\Models\GymClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class BarCodeGeneratorController extends GymAdminBaseController
{
    public function index()
    {
        if (!$this->data['user']->can("generate_i_cards")) {
            return App::abort(401);
        }

        $this->data['title'] = 'Generate Barcode';
        return view('gym-admin.i-card.barcode',$this->data);
    }

    public function clientList($filter)
    {
        if (!$this->data['user']->can("generate_i_cards")) {
            return App::abort(401);
        }
        $clients = GymClient::GetClients($this->data['user']->detail_id);

        return Datatables::of($clients)
            ->editColumn(
                'id', function ($row) use ($filter) {
                if ($filter == 'all') {
                    return '<div class="md-checkbox">
                                <input type="checkbox" id="checkbox' . $row->id . '" checked name="userIds[]" value="' . $row->id . '" class="md-check">
                                <label for="checkbox' . $row->id . '">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span></label>
                            </div>';
                }

                return '<div class="md-checkbox">
                                <input type="checkbox" id="checkbox' . $row->id . '"  name="userIds[]" value="' . $row->id . '" class="md-check">
                                <label for="checkbox' . $row->id . '">
                                    <span></span>
                                    <span class="check"></span>
                                    <span class="box"></span></label>
                            </div>';
            }
            )
            ->editColumn(
                'first_name', function ($row) {
                if ($row->image != '') {
                    return '<img style="width:50px;height:50px;" class="img-circle" src="' . $this->data['profileHeaderPath'] . $row->image . '" alt="" /><br>' . $row->fullname;
                } else {
                    return '<img src="' . asset('/fitsigma/images/') . '/' . 'user.svg" style="width:50px;height:50px;" class="img-circle" /><br> ' . $row->fullname;
                }
            }
            )
            ->editColumn('mobile', function ($row) {
                return $row->mobile;
            })->editColumn('email', function ($row) {
                return $row->email;
            })
            ->rawColumns(['id', 'first_name'])
            ->make();
    }

    public function store(Request $request)
    {
        if (!$this->data['user']->can("generate_i_cards")) {
            return App::abort(401);
        }

        $validator = Validator::make($request->all(), ['filter' => 'required']);

        if ($validator->fails()) {
            return Reply::formErrors($validator);
        }

        $filter = $request->input('filter');

        if ($filter == 'manual') {
            if (!count($request->input('userIds')) > 0) {
                return Reply::error('Please Select at least one client.');
            }
        }

        $this->data['clients'] = GymClient::whereIn('id', $request->input('userIds'))->get();

        $data = [
            'status'  => 'success',
            'content' => view('gym-admin.i-card.barcodeCreate', $this->data)->render()
        ];

        return Reply::successWithData('Barcode generated successfully.', $data);

    }

    public function emailQrCode(Request $request)
    {

        if (!$this->data['user']->can("generate_i_cards")) {
            return App::abort(401);
        }

        $user = GymClient::whereIn('id', $request->input('userIds'))->get();

        foreach ($user as $usr) {
            $email        = $usr->email;
            $name         = ucwords($usr->first_name . ' ' . $usr->middle_name . ' ' . $usr->last_name);
            $emailSubject = '';

            $this->email['emailText']  = 'Here is your Barcode you will need to check in every day at ' . ucwords($this->data['common_details']->title);
            $this->email['emailTitle'] = 'Check In Barcode - ' . ucwords($this->data['common_details']->title);
            $this->email['url']        = null;
            $this->email['email']      = $email;
            $this->email['clientId']   = $usr->id;

            if (is_null($this->data['gymSettings'])) {
                $this->email['logo'] = '<img src="' . $this->data['gymSettingPath'] . 'fitsigma-logo-full.png" height="50" alt="Business Logo" style="border:none">';
            } else {
                if ($this->data['gymSettings']->image != '') {
                    $this->email['logo'] = '<img src="' . $this->data['gymSettingPath'] . $this->data['gymSettings']->image . '" height="50" alt="Business Logo" style="border:none">';
                } else {
                    $this->email['logo'] = '<img src="' . $this->data['gymSettingPath'] . 'fitsigma-logo-full.png" height="150" alt="Business Logo" style="border:none">';
                }
            }

            $this->email['businessName'] = ucwords($this->data['common_details']->title);
            $data                        = $this->email;
            try {
                Mail::to($data['email'])->send(new BarCodeNotification($this->email));
            } catch (\Exception $e) {
                $response['errorEmailMessage'] = 'error';
            }

        }

        return Reply::successWithData('I-cards emailed successfully.', ['status' => 'success']);


    }
}
