<?php

    namespace App\Http\Requests\GymAdmin\GymSetting;

    use App\Http\Requests\CoreRequest;

    class StoreSmsRequest extends CoreRequest
    {
        /**
         * Determine if the user is authorized to make this request.
         *
         * @return bool
         */
        public function authorize()
        {
            return true;
        }

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            $rules = [
                'sms_status' => 'in:enabled,disabled|required',
            ];

            $newRules = [];

            if ($this->sms_status == 'enabled') {
                $newRules = [
                    'sender_id' => 'required|string',
                    'username'  => 'required|string',
                    'password'  => 'required|string',
                ];
            }

            return array_merge($rules, $newRules);
        }
    }
